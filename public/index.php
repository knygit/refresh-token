<?php

declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));

require BASE_PATH . '/app/config.php';

// IP whitelist check (uses CF-Connecting-IP behind Cloudflare proxy)
if (!empty(ALLOWED_IPS)) {
    $clientIp = $_SERVER['HTTP_CF_CONNECTING_IP'] ?? $_SERVER['REMOTE_ADDR'];
    if (!in_array($clientIp, ALLOWED_IPS, true)) {
        http_response_code(403);
        exit('403 Forbidden');
    }
}

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
$router->post('/revoke', 'controllers/AuthController.php@revoke');

// API
$router->post('/api/token/refresh', 'controllers/ApiController.php@refresh');

$router->dispatch();
