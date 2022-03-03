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
      <form class="login" action="publisher1.php" method="post">
        <!-- login field -->
        <div>
          <label>Name: </label>
          <input type="text" name="name" value="" required>
        </div>
        <br>


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

         <!-- Login button -->
        <input type="submit" name="login" value="Log In">
        <input type="reset">&nbsp;&nbsp;
      </form>
  </body></center>
</html>
