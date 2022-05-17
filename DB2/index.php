<?php
  session_start();
 ?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<style media="screen">
  body{text-align: auto;}
</style>
  <head>
    <meta charset="utf-8">
    <title>My Book Store</title>
  </head>

  <center><body>
      <h1>Book Store</h1>
      <form class="login" action="store.php" method="post">
        <!-- login field -->
        <div id="email">
          <label>Email: </label>
          <input type="text" name="email" value="" required>
        </div><br>

        <div id="password">
          <label>Password: </label>
          <input type="text" name="password" value="" required>
        </div><br>

        <?php
          // check if user input is empty or not
          // from: store.php
          if (isset($_SESSION['valid']) and !$_SESSION['valid']) {
            // echo "Invalid email/passowrd. Please Try again.";
            echo $_SESSION['error'];
            echo '<br>';
            unset($_SESSION['valid']);
          }
         ?>

         <!-- Sign up button and Login button -->
        <a href="signup.php">Sign up</a>&nbsp;&nbsp;
        <input type="submit" name="login" value="Log In">
        <!-- reset will re-enter the page so the fill will be reset -->
        <input type="reset" value="Reset">&nbsp;&nbsp;
        <!-- link to log in as guest -->
        <a href="guest.php">Log In as Guest</a>&nbsp;
        <!-- link to publisher log in page -->
        <a href="publisher.php">Publisher</a>
      </form>
  </body></center>

  <?php
    // clear up session data
    session_destroy();
   ?>
</html>
