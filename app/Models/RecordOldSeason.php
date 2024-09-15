<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RecordOldSeason extends Model
{
    use SoftDeletes;

    // protected $table = 'record_old_seasons_test';

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
}
