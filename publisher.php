<?php
  session_start();
  unset($_SESSION['pName']);
 ?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Publisher Log In</title>
  </head>

  <center>
    <body>
      <h1>Publisher Log In</h1>
      <form class="login" action="publisher_in.php" method="post">
        <!-- login field -->
        <div>
          <label>Name: </label>
          <input type="text" name="name" value="" required>
        </div>
        <br>

        <?php
          // check if publisher exists
          // from: store.php
          if (isset($_SESSION['valid']) and !$_SESSION['valid']) {
            echo $_SESSION['error'];
            echo '<br>';
            unset($_SESSION['valid']);
          }
         ?>

         <!-- Login button -->
         <button type="submit" name="button">Log In</button>
         <button type="reset" name="button">Reset</button>
         <a href="index.php">Return</a>
      </form>
  </body>
</center>
</html>
