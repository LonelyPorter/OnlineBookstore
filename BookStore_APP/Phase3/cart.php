<?php
  // establish database connection
  $mydb = new mysqli('localhost', 'root', '', 'bookstore');

  if(empty($_POST['id'])) {
    echo "error";
    exit;
  }
  $id = $_POST['id'];

  if (!empty($_POST['cart'])) {
      // display shopping cart
      $query = "SELECT title, books.ISBN, quantity, type, price*quantity as `total price`, time, cost
      FROM inCart, ShoppingCart, Books, Delivery
      WHERE cartOrder=orderNumber AND Books.ISBN=inCart.ISBN AND userID = ? AND books.method=delivery.method;";

      $stmt = $mydb->prepare($query);
      $stmt->bind_param('i', $id);
      $stmt->execute();
      $result = $stmt->get_result();

      $carts = array();

      if (mysqli_num_rows($result) == 0) {
          //results are empty
      } else {
          // $total = 0; # total price of the shopping cart (books)
          // $time = 0;
          // $cost = 0;

          while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
              $cart = array('title' => $row['title'], 'quantity' => $row['quantity'],
          'total price' => $row['total price'], 'ISBN' => $row['ISBN']);

              array_push($carts, $cart);

              // $total += $row['total price'];
        // $time = max($time, $row['time']);
        // $cost = max($cost, $row['cost']);
          }
      }

      echo json_encode($carts);
  }

  /* if hit delete */
  if (!empty($_POST['delete'])) {
      /* Prepare */
      $isbn = $_POST['delete'];

      /* Start transaction */
      $mydb->begin_transaction();
      try {
          // First, remove from incart
          $query = "DELETE FROM `incart` WHERE ISBN = ? and cartOrder =
            (SELECT DISTINCT cartOrder FROM `incart`, `shoppingcart`
            WHERE cartOrder = orderNumber AND userid = ?);";
          $stmt = $mydb->prepare($query);
          $stmt->bind_param('si', $isbn, $id);
          $stmt->execute();

          $mydb->commit();
      } catch (\Exception $e) {
          $mydb->rollback();
      }
  }

  /* if hit purchase */
  if (!empty($_POST['purchase'])) {
      /* Start transaction */
      $mydb->begin_transaction();

      try {
          // Add to inOrder and update stock in Books
          $query = "SELECT incart.ISBN, shoppingcart.orderNumber, incart.quantity, in_stock
            FROM incart, shoppingcart, books
            WHERE incart.cartID=shoppingcart.ID AND incart.cartOrder=shoppingcart.orderNumber
            AND userid= ? AND incart.ISBN = books.ISBN;";
          $stmt = $mydb->prepare($query);
          $stmt->bind_param('i', $id);
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
              if ($row['in_stock'] >= 0 && $row['quantity'] > $row['in_stock']) {
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
          $stmt->bind_param('i', $id);
          $stmt->execute();

          // delete shoppingcart
          $query = "DELETE FROM shoppingcart WHERE userid = ?;";
          $stmt = $mydb->prepare($query);
          $stmt->bind_param('i', $id);
          $stmt->execute();

          // update order history (processing change to finished)
          $query = "UPDATE `order` SET status = 'finished'
          WHERE status = 'pending' and userID = ?;";
          $stmt = $mydb->prepare($query);
          $stmt->bind_param('i', $id);
          $stmt->execute();

          $mydb->commit();
      } catch (\Exception $e) { // not enough in stock
          $mydb->rollback();
          echo $e;
          echo "error";
      }
      echo "succeed";
  }

  $mydb->close();
