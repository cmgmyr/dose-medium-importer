<?php

namespace Med\Console\Commands;

use App\User as UserModel;
use Illuminate\Support\Collection;

class VerifyAuthors extends BaseImport
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'medium:verify-authors
                            {--L|limit= : Limit of articles you would like to import. Default 200}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifies that all found authors are in our system';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $userNames = UserModel::get(['name']);

        $limit = $this->option('limit');
        if ($limit === null) {
            $limit = 200;
        }

        $apiUrlCall = 'lists?sort=latest&posted=true&only_feed_items=true';

        $response = $this->apiService->makeRequest('GET', $apiUrlCall . '&limit=' . $limit);

        $authors = Collection::make($response['data'])->map(function ($article) {
            return $article['author_name'];
        })
        ->flip()->flip()
        ->map(function($author) use ($userNames) {
            return ['name' => $author, 'system' => $userNames->contains('name', $author) ? 'Yes' : 'No'];
        });

        $this->table(['Name', 'In System?'], $authors->toArray());
    }
}
