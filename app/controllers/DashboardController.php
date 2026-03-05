<?php

function index(): void
{
    $user = requireLogin();

    // Check if user already has a valid refresh token
    $stmt = db()->prepare('SELECT COUNT(*) FROM refresh_tokens WHERE user_id = ? AND expires_at > NOW()');
    $stmt->execute([$user['id']]);
    $hasToken = $stmt->fetchColumn() > 0;

    $refreshToken = null;
    if (isset($_POST['generate_token'])) {
        $refreshToken = createRefreshToken($user['id']);
        $hasToken = true;
    }

    require BASE_PATH . '/templates/dashboard.php';
}

function logoutEverywhere(): void
{
    $user = requireLogin();
    \logoutEverywhere($user['id']);
    header('Location: /login');
    exit;
}
