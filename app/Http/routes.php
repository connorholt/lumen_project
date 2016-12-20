<?php
$app->get('/', function() use ($app) {
    return view('index');
});
$app->get('api/part', 'PartController@index');
$app->post('api/part', 'PartController@store');
$app->put('api/part/like/{id}', 'PartController@like');
$app->put('api/part/dislike/{id}', 'PartController@dislike');