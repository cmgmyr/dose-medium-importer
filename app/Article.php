<?php

namespace app;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'previous_id', 'medium_id', 'medium_url',
    ];
}
