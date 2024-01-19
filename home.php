<?php
session_start();
// print_r($_SESSION['user_data']); 
$sessionData = $_SESSION['user_data'];
// echo $sessionData['id'] . "<br>" .
// $sessionData['first_name'] . "<br>" .
// $sessionData['last_name'] . "<br>" .
// $sessionData['email'] . "<br>" .
// $sessionData['image']['link'];


// Database connection
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
if (isset($_GET['search'])) {
    // The 'search' parameter is set in the GET request
    // You can now use $searchQuery in your code

    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        // This is an AJAX request
        if (isset($_GET['search'])) {
            $searchQuery = $_GET['search'];
            $sql = "SELECT * FROM users WHERE firstName LIKE ? OR lastName LIKE ? LIMIT 7";
            $stmt = $conn->prepare($sql);
            $param = "%{$searchQuery}%";
            $stmt->bind_param("ss", $param, $param);
            $stmt->execute();
            $result = $stmt->get_result();
    
            $users = [];
            while($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
    
            echo json_encode($users);
            $conn->close();
            exit; // Stop the script here
        }
    }
}


?>
<?php if(isset($_SESSION['user_data'])):?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <title>Test Typing</title>
</head>
<body>
    <div class="navserach">
        <div class="part1">
            <div class="containerIcone">
                <i class="fas fa-search"></i>
            </div>
            <div class="containerInput">
                <input type="search" id="searchInput" placeholder="Search ...">
            </div>
        </div>
        <div class="part2">
            <p><?php  echo $sessionData['first_name'] ." ". $sessionData['last_name']; ?></p>
            <img src=<?php echo $sessionData['image']['link'] ?> alt=<?php  echo $sessionData['first_name'] ?> onclick="displayAlert()" >
        </div>
    </div>
    <div class="containerAlert">
        <div class="alert">
            <ul>
                <li>Score</li>
                <li>Bord</li>
                <li>Play</li>
                <li>Log out</li>
            </ul>
        </div>
    </div>
    <div class="searchbar">
         <div class="parentSearch" >
            
        </div>
    </div>
    <div class="allPage">
        <form action="logout.php" method="get">
            <input type="submit" value="Logout">
        </form>
    </div>
   
    
<?php $conn->close(); ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>

$(document).ready(function(){
  $('#searchInput').on('input', function(){
    var searchQuery = $(this).val();
    if (searchQuery === '') {
      // If the input is empty, clear the search results
      $('.searchbar').html('');
      $('.searchbar').css('opacity', '0');
    //   $('.searchbar').css('transition', 'none');
    } else {
        $('.searchbar').css('transition', '0.3s all');
        $('.searchbar').css('opacity', '1');
      $.ajax({
        url: 'home.php',
        type: 'GET',
        data: { search: searchQuery},
        success: function(data){
            var parsedData = JSON.parse(data);
            if(parsedData.length != 0){
                
                var html = '';
                for (var i = 0; i <  parsedData.length; i++) {
                html += '<div class="parentSearch" >';
                html += '<img src="' +  parsedData[i].image + '" alt="User Image" class="img">';
                html += '<p class="name">' +  parsedData[i].firstName + ' ' +  parsedData[i].lastName + '</p>';
                html += '</div>';
                }
                $('.searchbar').html(html);
            }
            else
            {
                var html = '';
                html += '<div class="parentSearch" >';
                html += '<p class="noUser">no users</p>';
                html += '</div>';
                $('.searchbar').html(html);
            }
        },
        error: function(jqXHR, textStatus, errorThrown){
          console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
        }
      });
    }
  });
});

function displayAlert()
{
    
    var display = $('.alert').css('display');
    if (display == 'none') {
      
        $('.alert').css('display', 'block');
    } else if (display == 'block') {
       
        $('.alert').css('display', 'none');
    }
}
</script>
</body>
</html>
<?php else: ?>
    <?php header('Location: index.php'); ?>
<?php endif; ?>