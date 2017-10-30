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
$router->get('CMS', 'AlitaController@showCMS');
$router->post('CMS', 'AlitaController@showCMS');

$router->get('CMS/content/{type}', 'AlitaController@showCMStype');

$router->get('CMS/content/{type}/add', 'AlitaController@showCMStypeAdd');

///////////////////////////////////////////////////////////////////////////////////////
/* API */
$router->get('API/{lang}/copy-content', 'CopaAPIController@getSiteCopy');

$router->post('API/{lang}/user/login', 'CopaAPIController@userLogin');
$router->post('API/{lang}/user/register', 'CopaAPIController@userRegister');
$router->get('API/{lang}/user/info/{id}', 'CopaAPIController@userInfo');

$router->get('API/{lang}/game/questions', 'CopaAPIController@gameQuestions');
$router->post('API/{lang}/game/validate', 'CopaAPIController@gameValidateAswer');

///////////////////////////////////////////////////////////////////////////////////////
//test
$router->get('/CMS/types', function() {
    return \App\Type::all();
});

$router->get('/CMS/type/{id}', 'TypeController@show');