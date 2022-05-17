<?php
  $id = $_POST['id'];
  $mydb = new mysqli('localhost', 'root', '', 'bookstore');

  if(!empty($id)) {
    $status = $_POST['status'];

    $query = "UPDATE member SET prime = ? WHERE userID = ?;";
    $stmt = $mydb->prepare($query);
    $stmt->bind_param('ii', $status, $id);
    $stmt->execute();

    $query = "UPDATE author SET prime = ? WHERE userID = ?;";
    $stmt = $mydb->prepare($query);
    $stmt->bind_param('ii', $status, $id);
    $stmt->execute();
  }

  $mydb->close();
  ?>
