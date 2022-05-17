<?php

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

  // establish database connection
  $mydb = new mysqli('localhost', 'root', '', 'bookstore');

  // insert book into incart (after hitting "add to cart"/"reorder" button)
  if (!empty($_POST['ISBN'])) {
      $isbn = $_POST['ISBN'];
      $time = date("Y-m-d");
      $order = generateOrder();
      $id = $_POST['id'];
      $quantity = 1;
      # if the add cart support choosing how many books to add
      if (isset($_POST['quantity'])) {
          $quantity = $_POST['quantity'];
      }
      // check if there's exsisting shopping cart
      $query = "SELECT ID, orderNumber FROM ShoppingCart WHERE userID = ?;";
      $stmt = $mydb->prepare($query);
      $stmt->bind_param('i', $_POST['id']);
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
          echo "error";
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

  $mydb->close();
