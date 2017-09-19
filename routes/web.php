<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    //return 'Welcome to AlitaCMS '.$router->app->version();
    return view('greeting', ['version' => $router->app->version()]);
});

$router->get('/CMS/', function () use ($router) {
	$types = \App\Type::all();
    return view('CMS/index', ['types' => $types]);
});

//post user wen login
$router->post('/CMS/', function () use ($router) {
    return view('CMS/index');
});

//test
$router->get('/CMS/types', function() {
    return \App\Type::all();
});

$router->get('/CMS/type/{id}', 'TypeController@show');