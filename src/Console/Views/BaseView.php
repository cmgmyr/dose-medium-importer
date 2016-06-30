<?php

namespace Med\Console\Views;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class BaseView
{
    /**
     * @var Command
     */
    protected $artisan;

    /**
     * @var Collection
     */
    protected $data;

    /**
     * @param Command $artisan
     * @param Collection $data
     */
    private function __construct(Command $artisan, Collection $data)
    {
        $this->artisan = $artisan;
        $this->data = $data;
    }

    /**
     * Static method helper.
     *
     * @param Command $artisan
     * @param Collection $data
     * @return BaseView
     */
    public static function make(Command $artisan, Collection $data)
    {
        return new static($artisan, $data);
    }
}
