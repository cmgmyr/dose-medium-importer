<?php

namespace Med\Console\Commands;

use Illuminate\Support\Collection;
use Med\Console\Views\PublicationIndexView;
use Med\Entities\Article;
use Med\Services\ApiService;

class Import extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'medium:import
                            {--L|limit= : Limit of articles you would like to import. Default 15}
                            {--P|page= : Page number to use for API call. Default 1}';

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
        $this->comment('Gathering available publications...');

        $publications = $this->getPublications();
        PublicationIndexView::make($this, $publications)->render();

        $selectedPublicationIndex = $this->ask('What publication would you like to import to?', 0);

        if ($selectedPublicationIndex >= $publications->count()) {
            $this->error('Sorry, your selection is not valid. Please rerun the command.');
            exit();
        }

        $this->comment('Great, let\'s get going!');

        $selectedPublication = $publications->pull($selectedPublicationIndex)->id;
        $articles = $this->getArticlesForImport();

        $progressBar = $this->output->createProgressBar($articles->count());

        $articles->each(function ($article) use ($artisan, $selectedPublication, $progressBar) {
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

            $post = $this->medium->createPost($this->user->id, $data);
            //$post = $this->medium->createPostUnderPublication($selectedPublication, $data);

            if (isset($post->errors)) {
                $errors = Collection::make($post->errors);
                $artisan->importError($article, $errors);
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

        $page = $this->option('page');
        if ($page === null) {
            $page = 1;
        }

        $apiUrlCall = 'lists?id=29823&include_content=true';
        $apiUrlCall = 'lists?sort=latest&posted=true&only_feed_items=true&include_content=true';
//        $apiUrlCall .= '&supported_content_types=animation';
//        $apiUrlCall .= '&supported_content_types=html';
//        $apiUrlCall .= '&supported_content_types=image';
//        $apiUrlCall .= '&supported_content_types=instagram';
//        $apiUrlCall .= '&supported_content_types=text';
//        $apiUrlCall .= '&supported_content_types=twitter';
//        $apiUrlCall .= '&supported_content_types=video';

        $response = $this->apiService->makeRequest('GET', $apiUrlCall . '&limit=' . $limit . '&page=' . $page);

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
        $this->error('Error: "' . $errors->first()->message . '" with article: ' . $article->id . ' - ' . $article->page_title);

        // Log message and code?
    }
}
