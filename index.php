<?php
    session_start();
    $_SESSION['user_data'];
?>

<!DOCTYPE html>
   <html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">

      <!--=============== REMIXICONS ===============-->
      <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">

      <!--=============== CSS ===============-->
      <link rel="stylesheet" href="assets/css/styles.css">

      <title>Animated login form - Bedimcode</title>
   </head>
   <body>
      <div class="login">
         <img src="assets/img/login-bg.png" alt="login image" class="login__img">

         <form action="auth.php" class="login__form">
            <h1 class="login__title">Login 42</h1>

            <!-- <div class="login__content">
               <div class="login__box">
                  <i class="ri-user-3-line login__icon"></i>

                  <div class="login__box-input">
                     <input type="email" required class="login__input" id="login-email" placeholder=" ">
                     <label for="login-email" class="login__label">Email</label>
                  </div>
               </div>

               <div class="login__box">
                  <i class="ri-lock-2-line login__icon"></i>

                  <div class="login__box-input">
                     <input type="password" required class="login__input" id="login-pass" placeholder=" ">
                     <label for="login-pass" class="login__label">Password</label>
                     <i class="ri-eye-off-line login__eye" id="login-eye"></i>
                  </div>
               </div>
            </div> -->

            <div class="login__check">
               <div class="login__check-group">
                  <!-- <input type="checkbox" class="login__check-input" id="login-check"> -->
                  <!-- <label for="login-check" class="login__check-label">Remember me</label> -->
               </div>

               <!-- <a href="#" class="login__forgot">Forgot Password?</a> -->
            </div>

            <button type="submit" class="login__button">Login With 42</button>
            <p class="login__register">
               <a href="#">Play Without Login</a>
            </p>
        </form>
      </div>
      
      <!--=============== MAIN JS ===============-->
      <script src="assets/js/main.js"></script>
   </body>
</html>