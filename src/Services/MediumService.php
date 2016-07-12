<?php

namespace Med\Services;

use Exception;
use Illuminate\Support\Collection;
use JonathanTorres\MediumSdk\Medium;

class MediumService
{
    /**
     * @var Medium
     */
    protected $medium;

    /**
     * @var \JonathanTorres\MediumSdk\StdClass
     */
    protected $user;

    public function __construct($token)
    {
        $this->medium = new Medium($token);
        $user = $this->medium->getAuthenticatedUser();

        if (isset($user->errors)) {
            $errors = Collection::make($user->errors);
            throw new Exception('Authentication failed. ' . $errors->first()->message);
        }

        $this->user = $user->data;
    }

    /**
     * Returns all of the available publications for the given user.
     *
     * @return Collection
     */
    public function getPublications()
    {
        return Collection::make($this->medium->publications($this->user->id)->data);
    }
}
