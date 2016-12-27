<?php

namespace App\Providers;

use App\Part;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    public function boot()
    {
        /**
         * Событие при создании модели
         * ставим номер части
         */
        Part::creating(function ($part) {
            $part->number = Part::getLastPartNumber();

            return true;
        });

        /**
         * Событие после создания, оповещаем всех о событии
         */
        Part::created(function ($part) {
            $redis = Redis::connection();
            $redis->publish('message', json_encode($part, JSON_PRETTY_PRINT));

            return true;
        });

        /**
         * Событие обновление модели, меняем номер частей
         */
        Part::updated(function ($part) {
            if ($part->is_selected) {
                Part::incLastPartNumber();
            }

            return true;
        });
    }
}
