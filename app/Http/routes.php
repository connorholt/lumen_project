<?php
/**
 * @todo сделать авторизацию, токены и роутер на vue
 */
$app->get('/', function() use ($app) {
    return view('index');
});
$app->get('/vote', function() use ($app) {
    return view('vote');
});
$app->get('api/text/parts', 'PartController@index');
$app->get('api/vote/part', 'PartController@vote');
$app->post('api/part', 'PartController@store');
$app->put('api/part/like/{id}', 'PartController@like');
$app->put('api/part/dislike/{id}', 'PartController@dislike');