<?php

namespace Med\Console\Commands;

use App\User as UserModel;
use Illuminate\Console\Command;
use Med\Console\Views\PublicationView;
use Med\Services\MediumService;

class Publications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'medium:publications
                            {--U|user= : User id to use from the system}
                            {--T|token= : Direct Medium token to use}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets the publications for the given user depending on id or token';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $userId = $this->option('user');
        if ($userId !== null) {
            $user = UserModel::find($userId);
            if (!$user) {
                $this->error('The given user id is incorrect.');
                exit();
            }

            $userToken = $user->medium_token;
        }

        if (!isset($userToken)) {
            $userToken = $this->option('token');
            if ($userToken === null) {
                $this->error('You will need to specify a user id or medium token');
                exit();
            }
        }

        $medium = new MediumService($userToken);
        PublicationView::make($this, $medium->getPublications())->render();
    }
}
