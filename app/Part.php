<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class Part extends Model
{
    private static $lastPartNumber;

    protected $fillable = [
        'author',
        'text',
        'like_count',
        'dislike_count'
    ];

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function scopeOnVote($query)
    {
        $query->where('is_selected', false);
        //$builder->where('number', self::getLastPartNumber());
    }

    public function scopeSelected($query)
    {
        $query->where('is_selected', true);
        //$builder->where('number', self::getLastPartNumber());
    }

    public static function getLastPartNumber()
    {
        $redis = Redis::connection();

        return $redis->get('number');
    }

    public static function incLastPartNumber()
    {
        $redis = Redis::connection();

        $number = (int) self::getLastPartNumber();
        $number += 1;

        $redis->set('number', $number);
    }

    /**
     * @param $id
     * @return static
     */
    public static function addLike($id)
    {
        /** @var self $part */
        $part = self::find($id);
        $part->like_count += 1;
        $part->save();

        return $part;
    }

    /**
     * @param $id
     * @return static
     */
    public static function addDislike($id)
    {
        /** @var self $part */
        $part = self::find($id);
        $part->dislike_count += 1;
        $part->save();

        return $part;
    }
}