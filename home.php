<?php
session_start();
// print_r($_SESSION['user_data']); 
$sessionData = $_SESSION['user_data'];
echo $sessionData['id'] . "<br>" .
$sessionData['first_name'] . "<br>" .
$sessionData['last_name'] . "<br>" .
$sessionData['email'] . "<br>" .
$sessionData['image']['link'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>slm</h1>
</body>
</html>