<?php

namespace Med\Console\Commands;

use App\Article as ImportedArticle;
use App\User as UserModel;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Med\Entities\Article;
use Med\Services\ApiService;
use Med\Services\MediumService;

class ImportToUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'medium:import-to-user
                            {--L|limit= : Limit of articles you would like to import. Default 15}
                            {--U|user= : User id to use from the system}
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

        $this->info('Authenticating user...');

        $userId = $this->option('user');
        if ($userId !== null) {
            $user = UserModel::find($userId);
            if (!$user) {
                $this->error('The given user id is incorrect.');
                exit();
            }

            $userToken = $user->medium_token;
            $medium = new MediumService($userToken);
        } else {
            $this->error('You must specify a valid user id.');
            exit;
        }
        $this->info('Authentication complete');

        $publicationId = $this->option('publication');

        $this->info('Gathering articles for import...');

        $articles = $this->getArticlesForImport();
        $progressBar = $this->output->createProgressBar($articles->count());
        $this->info('Gathering articles complete');

        $this->info('Importing articles to Medium...');
        $articles->each(function ($article) use ($artisan, $medium, $publicationId, $progressBar) {
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
                $post = $medium->createPostUnderPublication($publicationId, $data);
            } else {
                $post = $medium->createPost($data);
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
