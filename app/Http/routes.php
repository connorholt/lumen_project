<?php

$app->get('/', function() use ($app) {
    return view('app');
});

$app->get('api/text/parts[/{page}]', 'PartController@index');
$app->get('api/vote/parts[/{page}]', 'PartController@vote');

$app->post('api/part', 'PartController@store');
$app->post('api/select', 'PartController@selectPart');

$app->put('api/part/like/{id}', 'PartController@like');
$app->put('api/part/dislike/{id}', 'PartController@dislike');