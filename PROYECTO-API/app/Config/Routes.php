<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->group("api", function ($routes) {
    $routes->post("register", "Register::index");
    $routes->post("login", "Login::index");
    $routes->get("parqueadero", "Parqueadero::index", ['filter' => 'authFilter']);
    #$routes->post("parqueadero", "Parqueadero::update", ['filter' => 'authFilter']);
    $routes->post("parqueadero", "Parqueadero::solicitarParqueadero", ['filter' => 'authFilter']);
    $routes->get("residente", "Residente::index", ['filter' => 'authFilter']);
    $routes->put('residente/(:num)', 'Residente::update/$1', ['filter' => 'authFilter']);
    #$routes->put("residente", "Residente::update", ['filter' => 'authFilter']);
    $routes->post('residente-insert', 'Residente::insert', ['filter' => 'authFilter']);
    #$routes->post("residente-delete", "Residente::delete", ['filter' => 'authFilter']);
    $routes->delete('residente/(:num)', 'Residente::delete/$1', ['filter' => 'authFilter']);


	$routes->get('test', function () {
    	return 'API is working';
});   


});
