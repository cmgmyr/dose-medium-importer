<?php

use App\Pending;
use App\User;
use Illuminate\Database\Seeder;
use League\Csv\Reader;

class DatabaseSeeder extends Seeder
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

        Pending::truncate();

        $reader = Reader::createFromPath(base_path('data/ids.csv'));

        // skip the header
        $all = $reader->setOffset(1)->fetchAll();

        collect($all)->each(function ($article) {
            Pending::create([
                'article_id' => trim($article[0]),
            ]);
        });
    }
}
