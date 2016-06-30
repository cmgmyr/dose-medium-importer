<?php

namespace Med\Console\Commands;

use Med\Console\Views\PublicationView;

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
        PublicationView::make($this, $this->getPublications())->render();
    }
}
