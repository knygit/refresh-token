<?php

function startAppSession(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function currentUser(): ?array
{
    startAppSession();

    if (empty($_SESSION['user_id'])) {
        return null;
    }

    $stmt = db()->prepare('SELECT id, username, created_at FROM users WHERE id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch() ?: null;
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

function loginUser(string $username, string $password): ?array
{
    $stmt = db()->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password_hash'])) {
        return null;
    }

    startAppSession();
    session_regenerate_id(true);
    $_SESSION['user_id'] = $user['id'];

    return $user;
}

function registerUser(string $username, string $password): bool
{
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = db()->prepare('INSERT INTO users (username, password_hash) VALUES (?, ?)');

    try {
        $stmt->execute([$username, $hash]);
        return true;
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            return false; // Duplicate username
        }
        throw $e;
    }
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

function logoutEverywhere(int $userId): void
{
    // Delete all refresh tokens
    $stmt = db()->prepare('DELETE FROM refresh_tokens WHERE user_id = ?');
    $stmt->execute([$userId]);

    // Delete all sessions
    $stmt = db()->prepare('DELETE FROM sessions WHERE user_id = ?');
    $stmt->execute([$userId]);

    // Destroy current session
    logoutUser();
}
