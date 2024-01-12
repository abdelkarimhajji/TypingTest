<?php
session_start();

// Replace these values with your actual client credentials and callback URL
$clientID = 'u-s4t2ud-2da37d65ce889d281b394fbcbd622a808d1c56d32519e803e2819e9e959e62ad';
$clientSecret = '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';
$redirectUri = 'http://localhost/api42/callback.php';

// Step 1: Redirect the user to the 42 authorization URL
$authorizeUrl = 'https://api.intra.42.fr/oauth/authorize';
$authParams = [
    'client_id' => $clientID,
    'redirect_uri' => $redirectUri,
    'response_type' => 'code',
];
$authUrl = $authorizeUrl . '?' . http_build_query($authParams);

// Check if authorization code is received
if (isset($_GET['code'])) {
    // Step 3: Exchange the authorization code for an access token
    $tokenUrl = 'https://api.intra.42.fr/oauth/token';
    $tokenParams = [
        'client_id' => $clientID,
        'client_secret' => $clientSecret,
        'code' => $_GET['code'],
        'redirect_uri' => $redirectUri,
        'grant_type' => 'authorization_code',
    ];

    $ch = curl_init($tokenUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $tokenParams);

    $response = curl_exec($ch);
    curl_close($ch);

    $tokenData = json_decode($response, true);

    if (isset($tokenData['access_token'])) {
        // Step 4: Use the access token to make API requests
        $apiUrl = 'https://api.intra.42.fr/v2/me';

        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $tokenData['access_token'],
        ]);

        $apiResponse = curl_exec($ch);
        curl_close($ch);

        $userData = json_decode($apiResponse, true);

        // Now you have the user data
        // Redirect to index.php with user data as a query parameter
        header('Location: index.php?user=' . urlencode(json_encode($userData)));
        exit();
    } else {
        echo 'Error obtaining access token';
    }
} else {
    // Redirect to the authorization URL
    header('Location: ' . $authUrl);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>hello word</h1>
</body>
</html>