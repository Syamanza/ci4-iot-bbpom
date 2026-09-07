<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');
$routes->get('/admin', 'Home::admin');
$routes->get('/api/data', 'ApiController::index');
$routes->put('/api/data', 'ApiController::updateData');
$routes->post('/api/sensor-data', 'ApiController::receiveSensor');
