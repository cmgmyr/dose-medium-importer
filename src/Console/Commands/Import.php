<?php

namespace Med\Console\Commands;

use App\User as UserModel;
use Illuminate\Support\Collection;
use Med\Entities\Article;
use Med\Services\MediumService;

class Import extends BaseImport
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
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $authenticatedUsers = $this->authenticateUsers();
        $articles = $this->getArticlesForImport();

        $this->info('Importing articles to Medium...');
        $articles->map(function ($article) use ($authenticatedUsers) {
            $author = $authenticatedUsers->where('name', $article->author_name)->first();
            if ($author === null) {
                \Log::error($article->author_name . ' not in system...skipping article.');
                $this->progressBar->advance();
                $this->errorCount++;

                return;
            }

            $article->medium = $author['medium'];

            return $article;
        })
        ->filter()
        ->each(function ($article) {
            $this->publishArticle($article, $article->medium);
        });

        $this->finishImport();
    }

    /**
     * Authenticates all users to Medium.
     *
     * @return Collection
     */
    protected function authenticateUsers()
    {
        $this->info('Authenticating users...');

        $authenticatedUsers = UserModel::all()->map(function ($user) {
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

        return $authenticatedUsers;
    }
}
