<?php

namespace Med\Console\Commands;

use Med\Console\Views\PublicationView;

class Publications extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'medium:publications';

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
        PublicationView::make($this, $this->getPublications())->render();
    }
}
