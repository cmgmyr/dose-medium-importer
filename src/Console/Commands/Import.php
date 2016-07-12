<?php

namespace Med\Console\Commands;

use App\Article as ImportedArticle;
use App\User as UserModel;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Med\Entities\Article;
use Med\Services\ApiService;
use Med\Services\MediumService;

class Import extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'medium:import
                            {--L|limit= : Limit of articles you would like to import. Default 15}
                            {--P|publication= : Publication id to use. Ignore if publishing to user profile}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports some articles to Medium';

    /**
     * @var ApiService
     */
    protected $apiService;

    /**
     * Import constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->apiService = new ApiService();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $artisan = $this;

        $this->info('Authenticating users...');

        $authenticatedUsers = UserModel::all()->map(function ($user) use ($artisan) {
            try {
                $medium = new MediumService($user->medium_token);

                return ['name' => $user->name, 'medium' => $medium];
            } catch (\Exception $e) {
                \Log::error($user->name . ' (ID: ' . $user->id . ') was not authenticated. Please check token.');
            }
        });

        if ($authenticatedUsers->count() == 0) {
            $this->error('Sorry, there are no authenticated users in the system. Please add some or check token data.');
            exit;
        }
        $this->info('Authentication complete');

        $publicationId = $this->option('publication');

        $this->info('Gathering articles for import...');

        $articles = $this->getArticlesForImport();
        $progressBar = $this->output->createProgressBar($articles->count());
        $this->info('Gathering articles complete');

        $this->info('Importing articles to Medium...');
        $articles->each(function ($article) use ($artisan, $authenticatedUsers, $publicationId, $progressBar) {

            // is the author in our system?
            $author = $authenticatedUsers->where('name', $article->author_name)->first();
            if ($author === null) {
                \Log::error($article->author_name . ' not in system...skipping article.');
                $progressBar->advance();

                return;
            }

            $categories = $article->categories->take(5)->map(function ($category) {
                return $category->name;
            })->toArray();

            $data = [
                'title' => $article->page_title . ' (' . $article->getData()['included_content_types'] . ')',
                'contentFormat' => 'html',
                'content' => $article->renderHtml(),
                'publishStatus' => 'draft',
                'tags' => $categories,
            ];

            if ($publicationId !== null) {
                $post = $author['medium']->createPostUnderPublication($publicationId, $data);
            } else {
                $post = $author['medium']->createPost($data);
            }

            if (isset($post->errors)) {
                $errors = Collection::make($post->errors);
                $artisan->importError($article, $errors);
            } else {
                ImportedArticle::create([
                    'previous_id' => $article->id,
                    'medium_id' => $post->data->id,
                    'medium_url' => $post->data->url,
                ]);
            }

            $progressBar->advance();
        });

        $progressBar->finish();
        $this->info('');
        $this->info('Import completed!');
    }

    /**
     * Fetches the articles from the API endpoint.
     *
     * @return Collection
     */
    protected function getArticlesForImport()
    {
        $limit = $this->option('limit');
        if ($limit === null) {
            $limit = 15;
        }

        $previous = ImportedArticle::select('previous_id')->get()->implode('previous_id', ',');

        $apiUrlCall = 'lists?sort=latest&posted=true&only_feed_items=true&include_content=true';

        $response = $this->apiService->makeRequest('GET', $apiUrlCall . '&limit=' . $limit . '&blacklist=' . $previous);

        return Collection::make($response['data'])->map(function ($article) {
            return new Article($article);
        });
    }

    /**
     * Outputs the import errors to the console.
     *
     * @param Article $article
     * @param Collection $errors
     */
    protected function importError(Article $article, Collection $errors)
    {
        $errorText = 'Error: "' . $errors->first()->message . '" with article: ' . $article->id . ' - ' . $article->page_title;

        $this->error($errorText);
        \Log::error($errorText);
    }
}
