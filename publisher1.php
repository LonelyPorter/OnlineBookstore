<?php
  session_start();
 ?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <title>Book Store</title>
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


  if (mysqli_num_rows($result) == 0) {
    $_SESSION['valid'] = false;
    $_SESSION['error'] = "Invalid name. Please Try again.";
    header("Location: publisher.php");
    exit;
  }

  echo $name;

  // print header
  echo '<h1>Publiser Information</h1>';

  // Login in information
  echo "You are login as (name): ".$_POST['name'];
  ?><br><br>

  <!-- To add book -->
  <a href="addbook.php" style="text-decoration: none;">
    <button type="button">Adding book</button>
  </a><br><br><br><br>

  <?php
  // get publisher data
  $query = "SELECT * FROM `publisher`";
  $result = mysqli_query($mydb, $query) or die('Query failed: ' . mysql_error());

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

  // close database connection
  $result->free();
  $mydb->close();
  ?>
</body>
</html>
