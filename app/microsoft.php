<?php

function msGetAuthUrl(): string
{
    $params = [
        'client_id'     => MS_CLIENT_ID,
        'response_type' => 'code',
        'redirect_uri'  => MS_REDIRECT_URI,
        'scope'         => MS_SCOPES,
        'response_mode' => 'query',
        'state'         => bin2hex(random_bytes(32)),
    ];

    $_SESSION['oauth_state'] = $params['state'];

    return MS_AUTHORIZE_URL . '?' . http_build_query($params);
}

function msExchangeCode(string $code): ?array
{
    $params = [
        'client_id'     => MS_CLIENT_ID,
        'client_secret' => MS_CLIENT_SECRET,
        'grant_type'    => 'authorization_code',
        'code'          => $code,
        'redirect_uri'  => MS_REDIRECT_URI,
        'scope'         => MS_SCOPES,
    ];

    return msTokenRequest($params);
}

function msRefreshToken(string $refreshToken): ?array
{
    $params = [
        'client_id'     => MS_CLIENT_ID,
        'client_secret' => MS_CLIENT_SECRET,
        'grant_type'    => 'refresh_token',
        'refresh_token' => $refreshToken,
        'scope'         => MS_SCOPES,
    ];

    return msTokenRequest($params);
}

function msTokenRequest(array $params): ?array
{
    $ch = curl_init(MS_TOKEN_URL);
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => http_build_query($params),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => ['Content-Type: application/x-www-form-urlencoded'],
        CURLOPT_TIMEOUT        => 10,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2,
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200 || $response === false) {
        return null;
    }

    $data = json_decode($response, true);

    if (!isset($data['access_token'])) {
        return null;
    }

    return $data;
}

function msGetUserProfile(string $accessToken): ?array
{
    $ch = curl_init('https://graph.microsoft.com/v1.0/me');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => ['Authorization: Bearer ' . $accessToken],
        CURLOPT_TIMEOUT        => 10,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2,
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200 || $response === false) {
        return null;
    }

    return json_decode($response, true);
}
