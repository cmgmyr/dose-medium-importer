<?php

namespace Med\Console\Commands;

use App\Article as ArticleModel;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Med\Entities\Article;
use Med\Services\ApiService;
use Med\Services\MediumService;
use Symfony\Component\Console\Helper\ProgressBar;

class BaseImport extends Command
{
    /**
     * @var ApiService
     */
    protected $apiService;

    /**
     * @var ProgressBar
     */
    protected $progressBar;

    public function __construct()
    {
        parent::__construct();

        $this->apiService = new ApiService();
    }

    /**
     * Fetches the articles from the API endpoint.
     *
     * @return Collection
     */
    protected function getArticlesForImport()
    {
        $this->info('Gathering articles for import...');
        $limit = $this->option('limit');
        if ($limit === null) {
            $limit = 15;
        }

        $previous = ArticleModel::select('previous_id')->get()->implode('previous_id', ',');

        $apiUrlCall = 'lists?sort=latest&posted=true&only_feed_items=true&include_content=true';

        $response = $this->apiService->makeRequest('GET', $apiUrlCall . '&limit=' . $limit . '&blacklist=' . $previous);

        $articles = Collection::make($response['data'])->map(function ($article) {
            return new Article($article);
        });

        $this->progressBar = $this->output->createProgressBar($articles->count());
        $this->info('Gathering articles complete');

        return $articles;
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

    /**
     * Publishes the article to Medium.
     *
     * @param Article $article
     * @param MediumService $medium
     */
    protected function publishArticle(Article $article, MediumService $medium)
    {
        if ($this->getPublicationId() !== null) {
            $post = $medium->createPostUnderPublication($this->getPublicationId(), $article->buildDataForMedium());
        } else {
            $post = $medium->createPost($article->buildDataForMedium());
        }

        if (isset($post->errors)) {
            $errors = Collection::make($post->errors);
            $this->importError($article, $errors);
        } else {
            ArticleModel::create([
                'previous_id' => $article->id,
                'medium_id' => $post->data->id,
                'medium_url' => $post->data->url,
            ]);
        }

        $this->progressBar->advance();
    }

    /**
     * Returns the inputted publication id.
     *
     * @return null|string
     */
    protected function getPublicationId()
    {
        return $this->option('publication');
    }
}