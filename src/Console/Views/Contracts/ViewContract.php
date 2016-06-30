<?php

namespace Med\Console\Views\Contracts;

interface ViewContract
{
    /**
     * Renders the view to the console.
     *
     * @return mixed
     */
    public function render();
}
