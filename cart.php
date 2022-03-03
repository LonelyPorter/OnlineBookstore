<?php
  session_start();
  // establish database connection
  $mydb = new mysqli('localhost', 'root', '', 'bookstore');

  // check if logged in
  if (!isset($_SESSION['id'])) {
      exit;
  } else {
    // Login in information
    echo "You are login as (userID): ".$_SESSION['id'];
    echo "&emsp;&emsp;";
    echo '<a href="store.php">store home</a>';
    echo "<br><br>";
  }

  // insert book into incart
  if (!empty($_POST['ISBN'])) {
    echo "add to cart now:";
    // $query = ""
  }

  $query = "SELECT ISBN, quantity FROM inCart, ShoppingCart
            where cartorder=orderNumber AND userid = ?;";

  $stmt = $mydb->prepare($query);
  $stmt->bind_param('i', $_SESSION['id']);
  $stmt->execute();
  $result = $stmt->get_result();

  echo "<table>
    <thead>
      <tr>
        <th>Books(ISBN)</th>
      <th>Quantity</th>
      </tr>
    </thead>";

  if (mysqli_num_rows($result) == 0) {
     //results are empty
     echo "<p>Your Shopping Cart is Still Empty</p>";
  } else {
    echo "<tbody>";
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
      echo "<tr>";
      echo "<td>&emsp;".$row['ISBN']."&emsp;</td>";
      echo "<td>&emsp;".$row['quantity']."&emsp;</td>";
      echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
  }

  // close database connection
  $result->free();
  $mydb->close();
?>
