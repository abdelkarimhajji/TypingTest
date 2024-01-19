<?php

session_start();
if(isset($_GET['data'])) {
    session_destroy();
    header('Location: index.php');
}

header('Location: index.php');
?>