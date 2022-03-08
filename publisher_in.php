<?php
  session_start();
 ?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <title>Publisher</title>
</head>

<body>
  <?php
  // establish database connection
  $mydb = new mysqli('localhost', 'root', '', 'bookstore');
  $name = $_POST['name'];

  $query = "SELECT * FROM publisher WHERE name = ?;";
  $stmt = $mydb->prepare($query);
  $stmt->bind_param('s', $name);
  $stmt->execute();
  $result = $stmt->get_result();

  // if name not exists
  if (mysqli_num_rows($result) == 0) {
    $_SESSION['valid'] = false;
    $_SESSION['error'] = "Invalid name. Please Try again.";
    header("Location: publisher.php");
    exit;
  }

  // print header
  echo '<h1>Welcome Publisher!</h1>';
  // Login in information
  echo "You are login as: $name";
  // store for add book
  $_SESSION['pName'] = $name;

  // close database connection
  $result->free();
  $mydb->close();
  ?>
  <br><br>

  <?php
  echo '<form action="addbook.php" method="post">
          <button type="submit" value="'.$name.'">Add Book</button>
        </form>';
  ?>

  <!-- To add book -->
  <p>*Only exsisting author can add book through the publisher</p>
  <br><br>

</body>
</html>
