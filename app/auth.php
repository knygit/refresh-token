<?php

function startAppSession(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_set_cookie_params([
            'lifetime' => 0,
            'path'     => '/',
            'secure'   => isset($_SERVER['HTTPS']),
            'httponly'  => true,
            'samesite'  => 'Lax',
        ]);
        session_start();
    }
}

function currentUser(): ?array
{
    startAppSession();

    if (empty($_SESSION['user'])) {
        return null;
    }

    return $_SESSION['user'];
}

function requireLogin(): array
{
    $user = currentUser();
    if (!$user) {
        header('Location: /login');
        exit;
    }
    return $user;
}

function logoutUser(): void
{
    startAppSession();
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
}
