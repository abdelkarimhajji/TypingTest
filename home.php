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

$conn->close();
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
                <!-- <i class="fas fa-search"></i> -->
            </div>
            <div class="containerInput">
                <!-- <input type="search" id="searchInput" placeholder="Search ..."> -->
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
                <a href="score.php"><li>Score</li></a>
                <a href="board.php"><li>Bord</li></a>
                <a href="#"><li>Play</li></a>
                <a href="logout.php?data=value"><li>Log out</li></a>
            </ul>
        </div>
    </div>
    <div class="allPage">
        <div class="containerChoices">
            <div class="containerIcon"><i class="fas fa-keyboard"></i></div>
            <a href="" class="containerButton"><div><p>Play</p></div></a>
            <a href="score.php" class="containerButton"><div><p>See my score</p></div></a>
            <a href="board.php" class="containerButton"><div><p>Board</p></div></a>
            <a href="logout.php?data=value" class="containerButton"><div><p>Log out</p></div></a>
        </div>
    </div>
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
let valid = 1;
function displayAlert()
{
    
    let display = $('.alert').css('display');
    if (display == 'none') {
        valid = 0;
        $('.alert').css('display', 'block');
    } else if (display == 'block') {
       
        $('.alert').css('display', 'none');
    }
}


// console.log(valid);
$(window).click(function() {
let display = $('.alert').css('display');
if (display == 'block' && valid == 1) {
        if (display == 'block') {
            // alert('hello');
            $('.alert').css('display', 'none');
        }  
    }
});  // Closing brace was missing here
setInterval(function() {
    valid = 1;
}, 50);
</script>
</body>
</html>
<?php else: ?>
    <?php header('Location: index.php'); ?>
<?php endif; ?>