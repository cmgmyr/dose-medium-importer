<?php

namespace app\Console;

use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \Med\Console\Commands\Import::class,
        \Med\Console\Commands\ImportIds::class,
        \Med\Console\Commands\ImportToUser::class,
        \Med\Console\Commands\Publications::class,
        \Med\Console\Commands\User::class,
        \Med\Console\Commands\UserDelete::class,
        \Med\Console\Commands\Users::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //
    }
}
