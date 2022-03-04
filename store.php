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
  $myconnection = mysqli_connect('localhost', 'root', '')
  or die('Could not connect: ' . mysql_error());
  $mydb = mysqli_select_db($myconnection, 'bookstore') or die('Could not select database');

  $email = $pwd = -1; // set non-empty so won't trigger empty login
  if(!empty($_POST)) { // if coming from a form, set email and password
    $email = $_POST['email'];
    $pwd = $_POST['password'];
  }

  // if one of the input from login is empty
  if (empty($email) || empty($pwd)) {
      $_SESSION['valid'] = false;
      $_SESSION['error'] = "Email/Password cannot be empty";
      header("Location: index.php");
      exit;
  }

  // fetch for the account in database
  $query = "SELECT userID FROM Customers WHERE email = ? AND password = ?;";
  // if succeed, $_SESSION['id'] will carry the correct ID;
  // otherwise, would be empty
  $stmt = mysqli_prepare($myconnection, $query);
  mysqli_stmt_bind_param($stmt, "ss", $email, $pwd);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_bind_result($stmt, $_SESSION['id']);
  mysqli_stmt_fetch($stmt);
  mysqli_stmt_close($stmt);

  // if the login credential is incorret/non-exist
  if (empty($_SESSION['id'])) {
      $_SESSION['valid'] = false;
      $_SESSION['error'] = "Invalid email/passowrd. Please Try again.";
      header("Location: index.php");
      exit;
  }

  // print header
  echo '<h1>Book Store</h1>';

  // Login in information
  echo "You are login as (userID): ".$_SESSION['id'];
  echo "&emsp;&emsp;";
  echo '<a href="index.php">Log Out</a>';
  echo "<br><br>";
  ?>

  <!-- To my order or shopping cart -->
  <form action="order.php" method="post">
    <button type="submit">My Order</button>
  </form>

  <form action="cart.php" method="post">
    <button type="submit">My Shopping Cart</button>
  </form>

  <?php
    // check for if it's superuser
    if($_SESSION['id'] == 1000) {
      echo '<form action="update.php" method="post">
            <button type="submit">Update</button>
            </form>';
    }
   ?>

  <br><br>

  <?php
  // get books data
  $query = 'SELECT * FROM Books';
  $result = mysqli_query($myconnection, $query) or die('Query failed: ' . mysql_error());

  // table title
  echo "<table>
    <thead>
      <tr>
        <th>ISBN</th>
        <th>Title</th>
        <th>Type</th>
        <th>Price</th>
        <th>Category</th>
        <th>Stock</th>
        <th>Publisher Name</th>
        <th>Delivery Method</th>
        <th>Option</th>
      </tr>
    </thead>";

  // table body
  echo "<tbody>";
  while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
      echo '<form action="cart.php" method="post">';
      echo "<tr>";
      echo "<td>&emsp;".$row['ISBN']."&emsp;</td>";
      echo "<td>&emsp;".$row['title']."&emsp;</td>";
      echo "<td>&emsp;".$row['type']."&emsp;</td>";
      echo "<td>&emsp;".$row['price']."&emsp;</td>";
      echo "<td>&emsp;".$row['Category']."&emsp;</td>";
      if ($row['in_stock'] >= 0) {
          echo "<td>&emsp;".$row['in_stock']."&emsp;</td>";
      } else {
          echo "<td>&emsp;&infin;&emsp;</td>";
      }
      echo "<td>&emsp;".$row['pName']."&emsp;</td>";
      echo "<td>&emsp;".$row['method']."&emsp;</td>";
      echo "<td>";
      // echo '<input type="submit" name="ISBN" value="add to cart">';
      echo '<button type="submit" name="ISBN" value="' .$row['ISBN']. '">add to cart</button>';
      echo '</td>';
      echo "</tr>";
      echo '</form>';
  }
  echo "</tbody></table>";

  // close database connection
  mysqli_free_result($result);
  mysqli_close($myconnection);
  ?>
  <!-- Go to Rating -->
  <br>
  <form class="" action="rating.php" method="post">
    <input type="submit" name="" value="View Rating">
  </form>

</body>
</html>
