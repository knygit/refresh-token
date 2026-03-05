<?php

declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));

require BASE_PATH . '/app/config.php';
require BASE_PATH . '/app/auth.php';
require BASE_PATH . '/app/microsoft.php';
require BASE_PATH . '/app/router.php';

$router = new Router();

// Pages
$router->get('/', 'controllers/HomeController.php@index');
$router->get('/login', 'controllers/AuthController.php@login');
$router->get('/callback', 'controllers/AuthController.php@callback');
$router->get('/logout', 'controllers/AuthController.php@logout');
$router->get('/dashboard', 'controllers/DashboardController.php@index');

// API
$router->post('/api/token/refresh', 'controllers/ApiController.php@refresh');

$router->dispatch();
