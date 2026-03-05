<?php

function refresh(): void
{
    header('Content-Type: application/json');

    $input = json_decode(file_get_contents('php://input'), true);
    $refreshToken = $input['refresh_token'] ?? '';

    if ($refreshToken === '') {
        http_response_code(401);
        echo json_encode(['error' => 'Missing refresh token.']);
        return;
    }

    $tokens = msRefreshToken($refreshToken);

    if (!$tokens) {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid or expired refresh token.']);
        return;
    }

    echo json_encode([
        'access_token'  => $tokens['access_token'],
        'refresh_token' => $tokens['refresh_token'] ?? '',
    ]);
}
