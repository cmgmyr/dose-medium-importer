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
        'previous_id', 'original_date', 'medium_id', 'medium_url',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'original_date', 'created_at', 'updated_at'
    ];
}
