<?php

namespace Med\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use JonathanTorres\MediumSdk\Medium;

abstract class BaseCommand extends Command
{
    /**
     * @var Medium
     */
    protected $medium;

    /**
     * @var \JonathanTorres\MediumSdk\StdClass
     */
    protected $user;

    /**
     * BaseCommand constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->medium = new Medium(getenv('MEDIUM_TOKEN'));
        $user = $this->medium->getAuthenticatedUser();

        if(isset($user->errors)) {
            $errors = Collection::make($user->errors);
            throw new Exception('Authentication failed. ' . $errors->first()->message);
        }

        $this->user = $user->data;
    }
}
