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




$app->post('login', function() use($app) {
    $credentials = app()->make('request')->input("credentials");
    return $app->make('App\Auth\Proxy')->attemptLogin($credentials);
});

$app->post('refresh-token', function() use($app) {
    return $app->make('App\Auth\Proxy')->attemptRefresh();
});

$app->post('oauth/access-token', function() use($app) {
    return response()->json($app->make('oauth2-server.authorizer')->issueAccessToken());
});

$app->group(['prefix' => 'api', 'middleware' => 'oauth'], function($app)
{
    $app->get('resource', function() {
        return response()->json([
            "id" => 1,
            "name" => "A resource"
        ]);
    });
});