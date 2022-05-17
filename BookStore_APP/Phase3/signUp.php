<?php
  // establish database connection
  $mydb = new mysqli('localhost', 'root', '', 'bookstore');

  $pwd = $_POST['pwd'];
  $name = $_POST['name'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $addr = $_POST['addr'];
  $status = $_POST['status'];

  // Insert into table
  $mydb->begin_transaction();

  try {
    $query = "INSERT INTO customers(password, name, email, phone, address)
              VALUES (?, ?, ?, ?, ?);";
    $stmt = $mydb->prepare($query);
    $stmt->bind_param('sssss', $pwd, $name, $email, $phone, $addr);
    $stmt->execute();

    // if author
    if($status == 'author') {
      $last_id = mysqli_insert_id($mydb);
      $query = "INSERT INTO author(userID) VALUES (?)";
      $stmt = $mydb->prepare($query);
      $stmt->bind_param('i', $last_id);
      $stmt->execute();
    }

    // if normal customer (member)
    if($status == 'member') {
      $last_id = mysqli_insert_id($mydb);
      $query = "INSERT INTO member(userID) VALUES (?)";
      $stmt = $mydb->prepare($query);
      $stmt->bind_param('i', $last_id);
      $stmt->execute();
    }
    $mydb->commit();
    echo "true";
  } catch (\Exception $e) {
    // register fail then go back again
    $mydb->rollback();
    echo "false";
  }
  $mydb->close();
 ?>
