<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
// Routes for Blog module
$routes->group('blog', ['namespace' => 'App\Modules\Blog\Controllers'], function($routes) {
    $routes->get('/', 'BlogController::index');
});
