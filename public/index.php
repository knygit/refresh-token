<?php

declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));

require BASE_PATH . '/app/config.php';
require BASE_PATH . '/app/database.php';
require BASE_PATH . '/app/auth.php';
require BASE_PATH . '/app/token.php';
require BASE_PATH . '/app/router.php';

$router = new Router();

// Pages
$router->get('/', 'controllers/HomeController.php@index');
$router->get('/login', 'controllers/AuthController.php@loginForm');
$router->post('/login', 'controllers/AuthController.php@login');
$router->get('/register', 'controllers/AuthController.php@registerForm');
$router->post('/register', 'controllers/AuthController.php@register');
$router->get('/logout', 'controllers/AuthController.php@logout');
$router->get('/dashboard', 'controllers/DashboardController.php@index');
$router->post('/dashboard', 'controllers/DashboardController.php@index');
$router->post('/dashboard/logout-everywhere', 'controllers/DashboardController.php@logoutEverywhere');

// API
$router->post('/api/token/refresh', 'controllers/ApiController.php@refresh');

$router->dispatch();
