<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Record extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'area',
        'season',
        'rank',
        'last_rank',
        'gateway_id',
        'points',
        'wins',
        'losses',
        'disconnects',
        'toon',
        'battletag',
        'avatar',
        'feature_stat',
        'rating',
        'bucket'
    ];

    protected $hidden = [
        'id',
        'gateway_id',
        'points',
        'updated_at',
        'deleted_at',
    ];
}
