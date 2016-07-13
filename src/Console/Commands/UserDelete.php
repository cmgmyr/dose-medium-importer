<?php

namespace Med\Console\Commands;

use App\User as UserModel;
use Illuminate\Console\Command;

class UserDelete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:delete
                            {id : id of the user }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes a user from the Medium importer';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $userId = $this->argument('id');

        UserModel::destroy($userId);

        $this->info('The user has been successfully deleted.');
    }
}
