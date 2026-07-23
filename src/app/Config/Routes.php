<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');

$routes->options('api/(:any)', static function (){
    return service('response')->setStatusCode(200);
});

$routes->group('api', ['filter' => 'cors'], function($routes){
    $routes->post('register','Api\AuthController::register');
    $routes->post('login','Api\AuthController::login');
    $routes->post('logout','Api\AuthController::logout');
    $routes->get('user','Api\AuthController::user');
});

$routes->group('api',['filter' => 'auth'], function($routes){
    $routes->get('user', 'Api\AuthController::user');
});