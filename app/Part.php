<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function onVote(Builder $builder, Model $model)
    {
        $builder->where('is_selected', false);
        $builder->where('number', self::getLastPartNumber());
    }

    public static function getLastPartNumber()
    {
        if (!self::$lastPartNumber) {
            //self::$lastPartNumber =
        }

        return self::$lastPartNumber;
    }
}