<?php

$envFile = BASE_PATH . '/.env';

if (!file_exists($envFile)) {
    die('Missing .env file. Copy .env.example to .env and fill in your values.');
}

$env = parse_ini_file($envFile);

if ($env === false) {
    die('Failed to parse .env file.');
}

define('DB_HOST', $env['DB_HOST'] ?? 'localhost');
define('DB_NAME', $env['DB_NAME'] ?? 'refresh_token');
define('DB_USER', $env['DB_USER'] ?? 'root');
define('DB_PASS', $env['DB_PASS'] ?? '');
define('DB_PORT', (int)($env['DB_PORT'] ?? 3306));
define('APP_URL', $env['APP_URL'] ?? 'http://localhost');
define('APP_SECRET', $env['APP_SECRET'] ?? '');
