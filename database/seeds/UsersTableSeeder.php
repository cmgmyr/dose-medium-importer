<?php

use App\User;
use Illuminate\Database\Seeder;
use League\Csv\Reader;

class UsersTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();

        $reader = Reader::createFromPath(base_path('data/users.csv'));

        // skip the header
        $all = $reader->setOffset(1)->fetchAll();

        collect($all)->each(function ($user) {
            User::create([
                'name' => trim($user[0]),
                'medium_token' => trim($user[1]),
            ]);
        });
    }
}
