<?php
  session_start();
  function generateOrder() // Generate random order number
  {
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

    // insert book into incart (after hitting "add to cart"/"reorder" button)
    if (!empty($_POST['ISBN'])) {
        $isbn = $_POST['ISBN'];
        $time = date("Y-m-d");
        $order = generateOrder();
        $id = $_SESSION['id'];
        $quantity = $_POST['quantity'];

        // check if there's exsisting shopping cart
        $query = "SELECT ID, orderNumber FROM ShoppingCart WHERE userID = ?;";
        $stmt = $mydb->prepare($query);
        $stmt->bind_param('i', $_SESSION['id']);
        $stmt->execute();
        $stmt->bind_result($cartID, $order);
        $stmt->fetch();
        $stmt->close();

        /* Start transaction */
        $mydb->begin_transaction();

        try {
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
                  VALUES (?, ?, ?, ?);";
            $stmt = $mydb->prepare($query);
            $stmt->bind_param('sisi', $isbn, $last_id, $order, $quantity);
            $stmt->execute();

            /* If code reaches this point without errors then commit the data in the database */
            $mydb->commit();
        } catch (\Exception $e) { // if insert fail, meaning already have a shopping cart
            $mydb->rollback();
        }

        try {
            // insert incart
            $query = "INSERT INTO `InCart` (`ISBN`, `cartID`, `cartOrder`, `quantity`)
                  VALUES (?, ?, ?, ?);";
            $stmt = $mydb->prepare($query);
            $stmt->bind_param('sisi', $isbn, $cartID, $order, $quantity);
            $stmt->execute();

            $mydb->commit();
        } catch (\Exception $e) { // if insert fail, update quantity
            $mydb->rollback();

            $query = "UPDATE inCart SET quantity = quantity + ?
                  WHERE ISBN = ? AND cartID = ? AND cartOrder = ?";
            $stmt = $mydb->prepare($query);
            $stmt->bind_param('isis', $quantity, $isbn, $cartID, $order);
            $stmt->execute();
        }
    }

    // after hit purchase
    if (!empty($_POST['purchase'])) {
        /* Check for address exists */
        $query = "SELECT address FROM Customers
                WHERE userID = ?;";
        $stmt = $mydb->prepare($query);
        $stmt->bind_param('i', $_SESSION['id']);
        $stmt->execute();
        $stmt->bind_result($address);
        $stmt->fetch();
        $stmt->close();

        /* Start transaction */
        $mydb->begin_transaction();

        // if address exists
        if (!empty($address)) {
          try {
            // Add to inOrder and update stock in Books
            $query = "SELECT incart.ISBN, shoppingcart.orderNumber, incart.quantity, in_stock
              FROM incart, shoppingcart, books
              WHERE incart.cartID=shoppingcart.ID AND incart.cartOrder=shoppingcart.orderNumber
              AND userid= ? AND incart.ISBN = books.ISBN;";
            $stmt = $mydb->prepare($query);
            $stmt->bind_param('i', $_SESSION['id']);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
              // Add to Order
              $q = "INSERT INTO inOrder(ISBN, orderNumber, quantity)
              VALUES(?, ?, ?);";
              $s = $mydb->prepare($q);
              $s->bind_param('ssi', $row['ISBN'], $row['orderNumber'], $row['quantity']);
              $s->execute();
              $s->close();

              // if the quantity to buy > the stock number
              // throw an exception
              if ($row['in_stock'] > 0 && $row['quantity'] > $row['in_stock']) {
                throw new Exception('Not enough books in stock: ISBN='.$row['ISBN']);
              }

              // Update in_stock in Books
              $q = "UPDATE Books SET in_stock = in_stock - ? WHERE ISBN = ?;";
              $s = $mydb->prepare($q);
              $s->bind_param('is', $row['quantity'], $row['ISBN']);
              $s->execute();
              $s->close();
            }

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

            $mydb->commit();
          } catch (\Exception $e) { // not enough in stock
            $mydb->rollback();
            echo '<h3>';
            echo 'Purchase Failed: ' .$e->getMessage();
            echo '</h3>';
          }
        } else { # if address not exists
            echo "<h3>Warning: You need to have a valid address to be able to purchase.</h3>";
        }
    }

    // if hit delete
    if (!empty($_POST['delete'])) {
        /* Prepare */
        $isbn = $_POST['delete'];

        /* Start transaction */
        $mydb->begin_transaction();
        try {
            // First, remove from incart
            $query = "DELETE FROM inCart WHERE ISBN = ?;";
            $stmt = $mydb->prepare($query);
            $stmt->bind_param('s', $isbn);
            $stmt->execute();

            $mydb->commit();
        } catch (\Exception $e) {
            $mydb->rollback();

            echo "Something Went Wrong. Please Contact administrator!";
        }
    }

    // display shopping cart
    $query = "SELECT title, books.ISBN, quantity, type, price*quantity as `total price`, time, cost
      FROM inCart, ShoppingCart, Books, Delivery
      WHERE cartOrder=orderNumber AND Books.ISBN=inCart.ISBN AND userid = ? AND books.method=delivery.method;";

    $stmt = $mydb->prepare($query);
    $stmt->bind_param('i', $_SESSION['id']);
    $stmt->execute();
    $result = $stmt->get_result();

    if (mysqli_num_rows($result) == 0) {
        //results are empty
        echo "<p>Your Shopping Cart is Still Empty</p>";
        echo "<br>";
    } else {
      $total = 0; # total price of the shopping cart (books)
      $time = 0;
      $cost = 0;

      echo "<table>
      <thead>
      <tr>
      <th>Title</th>
      <th>ISBN</th>
      <th>Quantity</th>
      <th>Type</th>
      <th>Total Price</th>
      <th></th>
      </tr>
      </thead>";

      echo "<tbody>";
      while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
          echo "<tr>";
          echo "<td>&emsp;".$row['title']."&emsp;</td>";
          echo "<td>&emsp;".$row['ISBN']."&emsp;</td>";
          echo "<td>&emsp;".$row['quantity']."&emsp;</td>";
          echo "<td>&emsp;".$row['type']."&emsp;</td>";
          echo "<td>&emsp;".$row['total price']."&emsp;</td>";
          echo '<td><form action="" method="post">';
          echo '<button type="submit" name="delete" value="'.$row['ISBN'].'">Delete</button>';
          echo '</form></td>';
          echo "</tr>";

          $total += $row['total price'];
          $time = max($time, $row['time']);
          $cost = max($cost, $row['cost']);
      }
      echo "</tbody>";
      echo "</table>";

      echo "<br>";
      echo "<p><b>Total Price(books): </b>$<u>$total</u></p>";

      // Check if customer is prime
      $q = "SELECT * FROM Member WHERE userID = ? AND prime = 1
          UNION SELECT *  FROM Author WHERE userID = ? AND prime = 1;";
      $s = $mydb->prepare($q);
      $s->bind_param('ii', $_SESSION['id'], $_SESSION['id']);
      $s->execute();
      $r = $s->get_result();

      if (mysqli_num_rows($r) != 0) {
        $time = 2;
        $cost = 0;
      }

      $r->free();
      $s->close();

      echo "<p><b>Total Estimate Deliver time: </b><u>$time</u> days</p>";
      echo "<p><b>Total Delivery Fee: </b>$<u>$cost</u></p>";
    }

    // display Bought message
    if (!empty($_POST['purchase']) && mysqli_num_rows($result) == 0) {
        echo "<h2>Bought!</h2>";
    }
    ?>
    <br>

    <!-- Purchase -->
    <h2>Payment Method</h2>

    <?php
      if (!empty($_POST['add'])) {
          /* Add new payment if submit form add */
          $id = $_SESSION['id'];
          $account = $_POST['account'];
          $exp = $_POST['expire'];
          $cvs = $_POST['cvs'];

          $mydb->begin_transaction();
          try {
              $query = "INSERT INTO Payment(userID, Account, expire, cvs)
          VALUES(?, ?, ?, ?);";
              $stmt = $mydb->prepare($query);
              $stmt->bind_param('issi', $id, $account, $exp, $cvs);
              $stmt->execute();

              $mydb->commit();
          } catch (\Exception $e) {
              $mydb->rollback();
              echo "Add payment method failed. Please Contact administrator!";
          }
      }

      /* Query for searching user payment */
      $query = "SELECT * FROM payment WHERE userID = ?;";
      $stmt = $mydb->prepare($query);
      $stmt->bind_param('i', $_SESSION['id']);
      $stmt->execute();
      $result = $stmt->get_result();

      if (mysqli_num_rows($result) == 0) {
          echo "<h3>[You do not have any payment method on record]</h3>";
          echo "<h3>*You need to have at least one payment method to purchase any book</h3>";
      } else {
          // table title
          echo "<table>
        <thead>
        <tr>
        <th>Account</th>
        <th>Expire</th>
        <th></th>
        </tr>
        </thead>";

          // table body
          echo "<tbody>";
          echo '<form action="" method="post">';
          while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
              echo "<tr>";
              echo "<td>&emsp;".$row['Account']."&emsp;</td>";
              echo "<td>".$row['expire']."</td>";
              echo '<td>&emsp;<input type="radio" name="payment" value="'.$row['Account'].'" required/></td>';
              echo "</tr>";
          }
          echo "</tbody></table>";
          echo "<br>";
          echo '<input type="submit" name="purchase" value="Purchase">
            </form>';
      }
     ?>

     <h4>Add new payment method:</h4>
     <form class="" action="" method="post">
       <!-- Account -->
       <div class="">
         <label>Account: </label>
         <input type="text" name="account" required/>
         <br><br>
       </div>
       <!-- Expire Date-->
       <div class="">
         <label>Expire: </label>
         <input type="text" name="expire" required/>
         <br><br>
       </div>
       <!-- CVS -->
       <div class="">
         <label>CVS: </label>
         <input type="text" name="cvs" required/>
         <br><br>
       </div>
       <input type="submit" name="add" value="Submit">
       <input type="reset" value="Reset">
     </form>

    <?php
      // close database connection
      $result->free();
      $mydb->close();
     ?>
  </body>
</html>
