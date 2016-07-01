<?php

namespace Med;

use Med\Console\Kernel;
use Pimple\Container;
use Symfony\Component\Console\Application;

class MiniArtisan
{
    public function init($addName, $appVersion)
    {
        $kernel = new Kernel();

        $app = new Application($addName, $appVersion);

        foreach ($kernel->getCommands() as $command => $path) {
            $container = new Container();
            $container['details'] = function ($c) use ($path) {
                $console = new $path($c);
                $console->setLaravel(new MiniLaravel($console));

                return $console;
            };

            $app->add($container['details']);
        }

        return $app;
    }
}
