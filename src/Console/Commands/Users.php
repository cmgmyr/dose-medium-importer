<?php

namespace Med\Console\Commands;

use App\User as UserModel;
use Illuminate\Console\Command;

class Users extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'medium:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'View all users in the Medium importer';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $headers = ['Id', 'Name'];

        $users = UserModel::all(['id', 'name'])->toArray();

        $this->table($headers, $users);
    }
}
