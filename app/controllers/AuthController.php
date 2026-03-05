<?php

function login(): void
{
    startAppSession();
    $authUrl = msGetAuthUrl();
    header('Location: ' . $authUrl);
    exit;
}

function callback(): void
{
    startAppSession();

    // Verify state (CSRF protection)
    $state = $_GET['state'] ?? '';
    if (!hash_equals($_SESSION['oauth_state'] ?? '', $state) || $state === '') {
        http_response_code(403);
        require BASE_PATH . '/templates/error.php';
        return;
    }
    unset($_SESSION['oauth_state']);

    // Check for errors from Microsoft
    if (isset($_GET['error'])) {
        $error = 'Microsoft login failed. Please try again.';
        require BASE_PATH . '/templates/error.php';
        return;
    }

    $code = $_GET['code'] ?? '';
    if ($code === '') {
        $error = 'Missing authorization code. Please try again.';
        require BASE_PATH . '/templates/error.php';
        return;
    }

    // Exchange code for tokens
    $tokens = msExchangeCode($code);
    if (!$tokens) {
        $error = 'Failed to complete login. Please try again.';
        require BASE_PATH . '/templates/error.php';
        return;
    }

    // Get user profile from MS Graph
    $profile = msGetUserProfile($tokens['access_token']);
    if (!$profile) {
        $error = 'Failed to fetch your profile. Please try again.';
        require BASE_PATH . '/templates/error.php';
        return;
    }

    // Regenerate session ID to prevent session fixation
    session_regenerate_id(true);

    // Store in session
    $_SESSION['user'] = [
        'name'  => $profile['displayName'] ?? 'Unknown',
        'email' => $profile['mail'] ?? $profile['userPrincipalName'] ?? '',
    ];
    $_SESSION['access_token'] = $tokens['access_token'];
    $_SESSION['refresh_token'] = $tokens['refresh_token'] ?? null;

    header('Location: /dashboard');
    exit;
}

function logout(): void
{
    logoutUser();
    header('Location: /');
    exit;
}
