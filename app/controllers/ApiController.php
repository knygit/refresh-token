<?php

function refresh(): void
{
    header('Content-Type: application/json');

    // Only accept JSON content type
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    if (stripos($contentType, 'application/json') === false) {
        http_response_code(400);
        echo json_encode(['error' => 'Content-Type must be application/json.']);
        return;
    }

    $input = json_decode(file_get_contents('php://input'), true);

    if (!is_array($input)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid JSON body.']);
        return;
    }

    $refreshToken = $input['refresh_token'] ?? '';

    if ($refreshToken === '' || !is_string($refreshToken)) {
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
