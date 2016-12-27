<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;

class Part extends Model
{
    protected $attributes = [
        'number' => false
    ];

    protected $fillable = [
        'author',
        'text',
        'like_count',
        'dislike_count'
    ];

    /**
     * Scope чтобы получить те части которые находятся на голосовании
     *
     * @param  \Illuminate\Database\Eloquent\Builder builder
     * @return void
     */
    public function scopeOnVote($query)
    {
        $query->where('is_selected', false);
    }

    /**
     * Scope чтобы получить выбранные списки
     *
     * @param  \Illuminate\Database\Eloquent\Builder builder
     */
    public function scopeSelected($query)
    {
        $query->where('is_selected', true);
    }

    /**
     * @return mixed
     */
    public static function getLastPartNumber()
    {
        $redis = Redis::connection();

        return $redis->get('number');
    }

    /**
     *
     */
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

    /**
     * Выбрать часть в текст
     *
     * @param $id
     * @return mixed
     */
    public static function selectPart($id)
    {
        $part = self::find($id);
        $part->is_selected = true;

        $part->save();

        return $part;
    }
}