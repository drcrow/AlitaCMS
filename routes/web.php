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

/*
$router->get('/CMS/', function () use ($router) {
	//$types = \App\Type::all();
    
    return view('CMS/index', ['types' => TypeController::all()]);
});

//post user wen login
$router->post('/CMS/', function () use ($router) {
    return view('CMS/index');
});
*/
///////////////////////////////////////////////////////////////////////////////////////
/* CMS */


$router->get('CMS/login', [
    'as' => 'profile', 'uses' => 'UserController@showProfile'
]);

$router->get('CMS', 'AlitaController@showCMS');
$router->post('CMS', 'AlitaController@showCMS');



$router->get('CMS/content/{type}', 'AlitaController@showCMStype');

$router->get('CMS/content/{type}/add', 'AlitaController@showCMStypeAdd');



