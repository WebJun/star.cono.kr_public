<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class Reply extends Model
{
    use SoftDeletes;
    // use UuidForKey;

    // 기본 키를 UUID로 설정
    public $incrementing = false;

    // UUID 생성을 위한 설정
    protected $keyType = 'string';
    protected $primaryKey = 'id';

    protected $fillable = [
        'toon',
        'area',
        'nickname',
        'content',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = (string) Uuid::generate(4);
        });
    }
}
