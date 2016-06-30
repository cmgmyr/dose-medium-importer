<?php

namespace Med\Console;

class Kernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \Med\Console\Commands\Inspire::class,
    ];

    public function getCommands()
    {
        return $this->commands;
    }

}
