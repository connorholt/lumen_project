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
$app->get('/selected', function() use ($app) {
    return view('selected');
});
$app->get('/about', function() use ($app) {
    return view('about');
});

$app->get('api/text/parts[/{page}]', 'PartController@index');
$app->get('api/vote/parts[/{page}]', 'PartController@vote');
$app->post('api/part', 'PartController@store');
$app->post('api/selected', 'PartController@selected');
$app->put('api/part/like/{id}', 'PartController@like');
$app->put('api/part/dislike/{id}', 'PartController@dislike');