<?php

function generateToken(): string
{
    return bin2hex(random_bytes(32));
}

function hashToken(string $token): string
{
    return hash('sha256', $token);
}

function createRefreshToken(int $userId): string
{
    // Remove existing refresh tokens for this user
    $stmt = db()->prepare('DELETE FROM refresh_tokens WHERE user_id = ?');
    $stmt->execute([$userId]);

    $token = generateToken();
    $hash = hashToken($token);
    $expiresAt = date('Y-m-d H:i:s', strtotime('+30 days'));

    $stmt = db()->prepare('INSERT INTO refresh_tokens (user_id, token_hash, expires_at) VALUES (?, ?, ?)');
    $stmt->execute([$userId, $hash, $expiresAt]);

    return $token;
}

function createAccessToken(int $userId): string
{
    $payload = [
        'user_id' => $userId,
        'exp'     => time() + 3600, // 1 hour
        'iat'     => time(),
    ];

    $header = base64url_encode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
    $body = base64url_encode(json_encode($payload));
    $signature = base64url_encode(hash_hmac('sha256', "$header.$body", APP_SECRET, true));

    return "$header.$body.$signature";
}

function validateRefreshToken(string $token): ?array
{
    $hash = hashToken($token);

    $stmt = db()->prepare(
        'SELECT rt.*, u.username FROM refresh_tokens rt
         JOIN users u ON u.id = rt.user_id
         WHERE rt.token_hash = ? AND rt.expires_at > NOW()'
    );
    $stmt->execute([$hash]);

    return $stmt->fetch() ?: null;
}

function rotateRefreshToken(string $oldToken): ?array
{
    $record = validateRefreshToken($oldToken);
    if (!$record) {
        return null;
    }

    // Invalidate old token
    $stmt = db()->prepare('DELETE FROM refresh_tokens WHERE id = ?');
    $stmt->execute([$record['id']]);

    // Issue new tokens
    $newRefreshToken = createRefreshToken($record['user_id']);
    $accessToken = createAccessToken($record['user_id']);

    return [
        'access_token'  => $accessToken,
        'refresh_token' => $newRefreshToken,
    ];
}

function getUserRefreshToken(int $userId): ?string
{
    // We can't retrieve the plain token from hash, so generate a new one
    return createRefreshToken($userId);
}

function base64url_encode(string $data): string
{
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}
