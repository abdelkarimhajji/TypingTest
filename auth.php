<?php
session_start();

// Database credentials
$dbHost = 'localhost';
$dbUsername = 'root'; // replace with your MySQL username
$dbPassword = ''; // replace with your MySQL password
$dbName = 'TypingTest';

// Create connection
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



// Replace these values with your actual client credentials and callback URL


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
        $sessionData = [
            'id' => $userData['id'],
            'email' => $userData['email'],
            'login' => $userData['login'],
            'first_name' => $userData['first_name'],
            'last_name' => $userData['last_name'],
            'usual_full_name' => $userData['usual_full_name'],
            'usual_first_name' => $userData['usual_first_name'],
            'url' => $userData['url'],
            'phone' => $userData['phone'],
            'displayname' => $userData['displayname'],
            'kind' => $userData['kind'],
            'image' => $userData['image'],
        ];
        // User data
        $id = $sessionData['id'];
        $firstName = $sessionData['first_name'];
        $lastName = $sessionData['last_name'];
        $email = $sessionData['email'];
        $image = $sessionData['image']['link']; // assuming 'link' contains the image URL

        // Insert user into the 'users' table
        $sql = "INSERT INTO users (id, firstName, lastName, email, image) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issss", $id, $firstName, $lastName, $email, $image); // 'i' for integer, 's' for string

        if ($stmt->execute()) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $stmt->close();
        $conn->close();
        $_SESSION['user_data'] = $sessionData;
        header('Location: home.php?user=' . urlencode(json_encode($sessionData)));
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
