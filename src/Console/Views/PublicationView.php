<?php

namespace Med\Console\Views;

use Med\Console\Views\Contracts\ViewContract;

class PublicationView extends BaseView implements ViewContract
{
    /**
     * Renders the view to the console.
     *
     * @return mixed
     */
    public function render()
    {
        $artisan = $this->artisan;
        
        $this->data->each(function($publication) use ($artisan) {
            $artisan->info($publication->id . ': ' . $publication->name);
        });
    }
}
