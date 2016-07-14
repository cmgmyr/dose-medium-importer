<?php

namespace Med\Console\Commands;

use App\User as UserModel;
use Med\Services\MediumService;

class ImportToUser extends BaseImport
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
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $medium = $this->authenticateUser();
        $articles = $this->getArticlesForImport();

        $this->info('Importing articles to Medium...');
        $articles->each(function ($article) use ($medium) {
            $this->publishArticle($article, $medium);
        });

        $this->progressBar->finish();
        $this->info('');
        $this->info('Import completed!');
    }

    /**
     * Authenticates the given user to Medium.
     *
     * @return MediumService
     */
    protected function authenticateUser()
    {
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

        return $medium;
    }
}
