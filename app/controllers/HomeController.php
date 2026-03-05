<?php

function index(): void
{
    $user = currentUser();
    if ($user) {
        header('Location: /dashboard');
        exit;
    }
    require BASE_PATH . '/templates/home.php';
}
