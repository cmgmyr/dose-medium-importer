<?php

namespace app;

use Illuminate\Database\Eloquent\Model;

class Pending extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pending';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'site', 'publication', 'article_id', 'imported', 'skipped',
    ];
}
