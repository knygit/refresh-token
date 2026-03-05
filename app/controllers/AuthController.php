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

    // Verify state
    $state = $_GET['state'] ?? '';
    if ($state === '' || $state !== ($_SESSION['oauth_state'] ?? '')) {
        die('Invalid OAuth state. Please try again.');
    }
    unset($_SESSION['oauth_state']);

    // Check for errors from Microsoft
    if (isset($_GET['error'])) {
        die('Microsoft login error: ' . htmlspecialchars($_GET['error_description'] ?? $_GET['error']));
    }

    $code = $_GET['code'] ?? '';
    if ($code === '') {
        die('Missing authorization code.');
    }

    // Exchange code for tokens
    $tokens = msExchangeCode($code);
    if (!$tokens) {
        die('Failed to exchange authorization code for tokens.');
    }

    // Get user profile from MS Graph
    $profile = msGetUserProfile($tokens['access_token']);
    if (!$profile) {
        die('Failed to fetch user profile from Microsoft Graph.');
    }

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
