<?php
    session_start();
    $_SESSION['user_data'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div>
        <form action="auth.php" method="POST">
            <button>sigin with intra</button>
        </form>
    </div>
</body>
</html>