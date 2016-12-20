<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    protected $fillable = [
        'author',
        'text',
        'like_count',
        'dislike_count'
    ];
}