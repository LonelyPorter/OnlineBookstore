<?php
  session_start();
  function generateOrder() {
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $numbers = '0123456789';
    $orderNumber = '';

    for ($i = 0; $i < 2; $i++) {
      $orderNumber .= $characters[rand(0, 25)];
    }

    for ($i = 2; $i < 10; $i++) {
      $orderNumber .= $numbers[rand(0, 9)];
    }

    $orderNumber = str_shuffle($orderNumber);
    return $orderNumber;
  }
 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Shopping Cart</title>
  </head>
  <body>
    <h1>Shopping Cart</h1>
    <?php
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
      $isbn = $_POST['ISBN'];
      $time = date("Y-m-d");
      $order = generateOrder();
      $id = $_SESSION['id'];

      // check if there's exsisting shopping cart
      $query = "SELECT Number FROM `order`
                WHERE status='pending' AND userID=?;";
      $stmt = $mydb->prepare($query);
      $stmt->bind_param('i', $_SESSION['id']);
      $stmt->execute();
      $stmt->bind_result($order);
      $stmt->fetch();
      $stmt->close();

      // insert order
      $query = "INSERT into `order`(Number, time, status, userID)
                VALUES(?, ?, 'pending', ?);";
      $stmt = $mydb->prepare($query);
      $stmt->bind_param('ssi', $order, $time, $id);
      $stmt->execute();

      // insert shopping cart
      $query = "INSERT into `shoppingcart`(orderNumber, userID)
                VALUES(?, ?);";
      $stmt = $mydb->prepare($query);
      $stmt->bind_param('si', $order, $id);
      $stmt->execute();

      // insert incart
      $last_id = mysqli_insert_id($mydb);
      $query = "INSERT INTO `InCart` (`ISBN`, `cartID`, `cartOrder`, `quantity`)
                VALUES (?, ?, ?, 1);";
      $stmt = $mydb->prepare($query);
      $stmt->bind_param('sis', $isbn, $last_id, $order);
      $stmt->execute();
    }

    // after hit purchase
    if(!empty($_POST['purchase'])) {
      // add to in order
      $query = "INSERT INTO inorder(ISBN, orderNumber, quantity)
                SELECT incart.ISBN, shoppingcart.orderNumber, incart.quantity FROM incart, shoppingcart
                WHERE incart.cartID=shoppingcart.ID and incart.cartOrder=shoppingcart.orderNumber and userid=?;";
      $stmt = $mydb->prepare($query);
      $stmt->bind_param('i', $_SESSION['id']);
      $stmt->execute();

      // delete inCart
      $query = "DELETE FROM incart WHERE cartOrder in
        (SELECT cartOrder FROM incart, shoppingcart
          WHERE incart.cartOrder=shoppingcart.orderNumber and userID=?);";
      $stmt = $mydb->prepare($query);
      $stmt->bind_param('i', $_SESSION['id']);
      $stmt->execute();

      // delete shoppingcart
      $query = "DELETE FROM shoppingcart WHERE userid = ?;";
      $stmt = $mydb->prepare($query);
      $stmt->bind_param('i', $_SESSION['id']);
      $stmt->execute();

      // update order history
      $query = "UPDATE `order` SET status = 'processing'
                WHERE status = 'pending' and userID = ?;";
      $stmt = $mydb->prepare($query);
      $stmt->bind_param('i', $_SESSION['id']);
      $stmt->execute();
    }

    // display shopping cart
    $query = "SELECT title, books.ISBN, quantity, type FROM inCart, ShoppingCart,books
    WHERE cartorder=orderNumber AND books.ISBN=incart.ISBN AND userid = ?;";

    $stmt = $mydb->prepare($query);
    $stmt->bind_param('i', $_SESSION['id']);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "<table>
    <thead>
    <tr>
    <th>Title</th>
    <th>ISBN</th>
    <th>Quantity</th>
    <th>Type</th>
    </tr>
    </thead>";

    if (mysqli_num_rows($result) == 0) {
      //results are empty
      echo "<p>Your Shopping Cart is Still Empty</p>";
      echo "<br><br>";
    } else {
      echo "<tbody>";
      while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        echo "<tr>";
        echo "<td>&emsp;".$row['title']."&emsp;</td>";
        echo "<td>&emsp;".$row['ISBN']."&emsp;</td>";
        echo "<td>&emsp;".$row['quantity']."&emsp;</td>";
        echo "<td>&emsp;".$row['type']."&emsp;</td>";
        echo "</tr>";
      }
    }
    echo "</tbody>";
    echo "</table>";

    // display Bought message
    if(!empty($_POST['purchase']) && mysqli_num_rows($result) == 0) {
      echo "<h2>Bought!</h2>";
    }

    // close database connection
    $result->free();
    $mydb->close();

    ?>

    <br><br>

    <!-- Purchase -->
    <form action="" method="post">
      <input type="submit" name="purchase" value="Purchase">
    </form>
  </body>
</html>
