<?php

function loginForm(): void
{
    $error = '';
    require BASE_PATH . '/templates/login.php';
}

function login(): void
{
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Please fill in all fields.';
        require BASE_PATH . '/templates/login.php';
        return;
    }

    $user = loginUser($username, $password);
    if (!$user) {
        $error = 'Invalid username or password.';
        require BASE_PATH . '/templates/login.php';
        return;
    }

    header('Location: /dashboard');
    exit;
}

function registerForm(): void
{
    $error = '';
    require BASE_PATH . '/templates/register.php';
}

function register(): void
{
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['password_confirm'] ?? '';

    if ($username === '' || $password === '' || $confirm === '') {
        $error = 'Please fill in all fields.';
        require BASE_PATH . '/templates/register.php';
        return;
    }

    if (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters.';
        require BASE_PATH . '/templates/register.php';
        return;
    }

    if ($password !== $confirm) {
        $error = 'Passwords do not match.';
        require BASE_PATH . '/templates/register.php';
        return;
    }

    if (!registerUser($username, $password)) {
        $error = 'Username is already taken.';
        require BASE_PATH . '/templates/register.php';
        return;
    }

    // Auto-login after registration
    loginUser($username, $password);
    header('Location: /dashboard');
    exit;
}

function logout(): void
{
    logoutUser();
    header('Location: /login');
    exit;
}
