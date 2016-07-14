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
    public $medium;

    /**
     * @var \stdClass
     */
    public $user;

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

    /**
     * Create a post on the authenticated user's profile.
     *
     * @param array $data
     *
     * @return \stdClass
     */
    public function createPost(array $data)
    {
        return $this->medium->createPost($this->user->id, $data);
    }

    /**
     * Create a post under a publication on the authenticated user's profile.
     *
     * @param string $publicationId
     * @param array $data
     *
     * @return \stdClass
     */
    public function createPostUnderPublication($publicationId, array $data)
    {
        return $this->medium->createPostUnderPublication($publicationId, $data);
    }
}
