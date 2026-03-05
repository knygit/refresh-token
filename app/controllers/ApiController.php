<?php

function refresh(): void
{
    header('Content-Type: application/json');

    $input = json_decode(file_get_contents('php://input'), true);
    $token = $input['refresh_token'] ?? '';

    if ($token === '') {
        http_response_code(401);
        echo json_encode(['error' => 'Missing refresh token.']);
        return;
    }

    $result = rotateRefreshToken($token);

    if (!$result) {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid or expired refresh token.']);
        return;
    }

    echo json_encode([
        'access_token'  => $result['access_token'],
        'refresh_token' => $result['refresh_token'],
    ]);
}
