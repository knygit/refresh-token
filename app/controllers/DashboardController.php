<?php

function index(): void
{
    $user = requireLogin();
    $refreshToken = $_SESSION['refresh_token'] ?? null;
    require BASE_PATH . '/templates/dashboard.php';
}
