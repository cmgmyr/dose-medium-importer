<?php

namespace Med\Console\Commands;

use App\User as UserModel;
use Illuminate\Console\Command;

class User extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:create
                            {name : Name of the user }
                            {token : Medium Token for the user }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adds a user to the Medium importer';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $userName = $this->argument('name');
        $userToken = $this->argument('token');

        UserModel::firstOrCreate([
            'name' => $userName,
            'medium_token' => $userToken,
        ]);

        $this->info($userName . ' has been successfully added.');
    }
}
