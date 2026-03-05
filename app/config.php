<?php

$envFile = BASE_PATH . '/.env';

if (!file_exists($envFile)) {
    die('Missing .env file. Copy .env.example to .env and fill in your values.');
}

$env = [];
$lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

foreach ($lines as $line) {
    $line = trim($line);

    // Skip comments
    if ($line === '' || $line[0] === '#') {
        continue;
    }

    $pos = strpos($line, '=');
    if ($pos === false) {
        continue;
    }

    $key = trim(substr($line, 0, $pos));
    $value = trim(substr($line, $pos + 1));

    // Remove surrounding quotes if present
    if (strlen($value) >= 2 && $value[0] === '"' && $value[-1] === '"') {
        $value = substr($value, 1, -1);
    }

    $env[$key] = $value;
}

define('MS_CLIENT_ID', $env['MS_CLIENT_ID'] ?? '');
define('MS_CLIENT_SECRET', $env['MS_CLIENT_SECRET'] ?? '');
define('MS_TENANT_ID', $env['MS_TENANT_ID'] ?? 'common');
define('MS_REDIRECT_URI', $env['MS_REDIRECT_URI'] ?? 'http://localhost/callback');
define('MS_SCOPES', $env['MS_SCOPES'] ?? 'offline_access User.Read');
define('APP_SECRET', $env['APP_SECRET'] ?? '');
define('ALLOWED_IPS', array_filter(array_map('trim', explode(',', $env['ALLOWED_IPS'] ?? ''))));

define('MS_AUTHORIZE_URL', 'https://login.microsoftonline.com/' . MS_TENANT_ID . '/oauth2/v2.0/authorize');
define('MS_TOKEN_URL', 'https://login.microsoftonline.com/' . MS_TENANT_ID . '/oauth2/v2.0/token');
