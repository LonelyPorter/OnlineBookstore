<?php
  session_start();

  // establish database connection
  $mydb = new mysqli('localhost', 'root', '', 'bookstore');

  // Initialize variable
  $pwd = uniqid();
  echo $pwd;

  // Add entry to Customers, assign random id/password and null on info
  $query = "INSERT INTO Customers (password) VALUES (?);";
  $stmt = $mydb->prepare($query);
  $stmt->bind_param('s', $pwd);
  $stmt->execute();

  // insert into Guest table
  $last_id = mysqli_insert_id($mydb);
  $query = "INSERT INTO Guest VALUES (?);";
  $stmt = $mydb->prepare($query);
  $stmt->bind_param('i', $last_id);
  $stmt->execute();

  // log in to store
  $_SESSION['id'] = $last_id;
  header("Location: store.php");

  // close database connection
  $mydb->close();
 ?>
