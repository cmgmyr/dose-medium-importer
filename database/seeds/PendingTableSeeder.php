<?php

use App\Pending;
use Illuminate\Database\Seeder;
use League\Csv\Reader;

class PendingTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Pending::truncate();

        $reader = Reader::createFromPath(base_path('data/ids.csv'));

        // skip the header
        $all = $reader->setOffset(1)->fetchAll();

        collect($all)->each(function ($article) {
            Pending::create([
                'article_id' => trim($article[0]),
                'site' => strtoupper(trim($article[1])),
                'publication' => strtoupper(trim($article[2])),
            ]);
        });
    }
}
