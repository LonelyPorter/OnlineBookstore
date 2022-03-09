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
  $name = NULL;
  if(empty($_SESSION['pName'])) {
    if(!empty($_POST['name'])) {
      $name = $_POST['name'];
    }
  } else {
    $name = $_SESSION['pName'];
  }

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
    echo "&nbsp;&nbsp;";
    echo '<a href="publisher.php">Sign Out</a><br><br>';
    // store for add book
    $_SESSION['pName'] = $name;

  echo '<h3>Publisher Infomation</h3>';
  if(isset($_POST['address'])) {
    $query = "UPDATE publisher SET address = ".$_POST['address']." WHERE name = ?;";

    $stmt = $mydb->prepare($query);
    $stmt->bind_param('s', $name);
    $stmt->execute();
    $result = $stmt->get_result();
  }
  // table title
  echo "<table>
    <thead>
      <tr>
        <th>Name</th>
        <th>Address</th>
      </tr>
    </thead>";

  // table body
  echo "<tbody>";
  while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
      echo '<form action=" " method="post">';
      echo "<tr>";
      echo "<td>&emsp;".$row['name']."&emsp;</td>";
      echo "<td>&emsp;".$row['address']."&emsp;</td>";
      echo '</form>';
  }

  echo "</tbody></table>";
  echo "<br>";
  // close database connection
  $mydb->close();
  ?>

  <!-- Publisher Function -->
  <form action="" method="post">
    <label>Update Address: </label>
    <input type="text" name="address" value="" required>
    <button type="submit">Submit</button>
    <input type="reset" value="Reset">&nbsp;
  </form>
  <br>

  <div style="display: flex;">
    <form action="bookprice.php" method="post">
      <button type="submit">Update Price</button>&nbsp;
    </form>

    <form action="addbook.php" method="post">
      <button type="submit">Add Books</button>&nbsp;
    </form>
  </div>

  <!-- To add book -->
  <p>*Only exsisting author can add book through the publisher</p>
  <br><br>

</body>
</html>
