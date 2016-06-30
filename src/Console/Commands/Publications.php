<?php

namespace Med\Console\Commands;

use Illuminate\Support\Collection;

class Publications extends BaseCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'publications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets the publications for the current user';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $artisan = $this;

        Collection::make($this->medium->publications($this->user->id)->data)
            ->each(function($publication) use ($artisan) {
                $artisan->info($publication->id . ': ' . $publication->name);
            });
    }
}
