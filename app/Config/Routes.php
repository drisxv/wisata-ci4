<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->post('/login', 'Login::login');
$routes->post('/register', 'Register::register');
$routes->get('/destinations', 'Destination::index');
$routes->get('/destinations/(:num)', 'Destination::show/$1');
$routes->post('/destinations', 'Destination::create');
$routes->put('/destinations/(:num)', 'Destination::update/$1');
$routes->delete('/destinations/(:num)', 'Destination::delete/$1');
$routes->post('/destinations/upload-image', 'Destination::uploadImage');
$routes->get('users', 'User::index');
$routes->get('users/(:num)', 'User::show/$1');
$routes->post('users', 'User::create');
$routes->put('users/(:num)', 'User::update/$1');
$routes->patch('users/(:num)', 'User::update/$1');
$routes->delete('users/(:num)', 'User::delete/$1');
