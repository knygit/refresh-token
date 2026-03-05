<?php

function index(): void
{
    $user = currentUser();
    if ($user) {
        header('Location: /dashboard');
        exit;
    }
    header('Location: /login');
    exit;
}
