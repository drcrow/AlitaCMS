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
    'as' => 'login', 
    'uses' => 'AlitaController@showLogin'
]);

$router->post('CMS/login', [
    'as' => 'login', 
    'uses' => 'AlitaController@showLogin'
]);

$router->get('CMS', [
    'as' => 'home', 
    'middleware' => 'CMSLogin',
    'uses' => 'AlitaController@showCMS'
]);


<<<<<<< Updated upstream
=======
///////////////////////////////////////////////////////////////////////////////////////
/* EMAILS */
$router->get('API/{lang}/emails/view/{email}/{market}', 'CopaAPIController@viewEmail'); //market = CO, MX, PA, US
$router->post('API/{lang}/emails/invite', 'CopaAPIController@sendInvite');
>>>>>>> Stashed changes




$router->get('CMS/content/{type}', 'AlitaController@showCMStype');

$router->get('CMS/content/{type}/add', 'AlitaController@showCMStypeAdd');



