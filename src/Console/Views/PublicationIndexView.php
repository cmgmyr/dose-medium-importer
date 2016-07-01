<?php

namespace Med\Console\Views;

use Med\Console\Views\Contracts\ViewContract;

class PublicationIndexView extends BaseView implements ViewContract
{
    /**
     * Renders the view to the console.
     *
     * @return mixed
     */
    public function render()
    {
        $artisan = $this->artisan;

        $this->data->each(function ($publication, $index) use ($artisan) {
            $artisan->info('[' . $index . '] ' . $publication->id . ': ' . $publication->name);
        });
    }
}
