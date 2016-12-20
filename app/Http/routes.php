<?php
$app->get('/', function() use ($app) {
    return view('index');
});
$app->get('api/part', 'PartController@index');
$app->post('api/part', 'PartController@store');
$app->delete('api/part/{id}', 'PartController@destroy');