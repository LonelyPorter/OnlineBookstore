<?php
  // establish database connection
  $mydb = new mysqli('localhost', 'root', '', 'bookstore');

  // check if normal login or guess
  $guest = $_POST['guest'];

  if (!empty($guest) && $guest == "false") {
    // check if user exsisted
    $email = $_POST['email'];
    $pwd = $_POST['password'];

    $query = "SELECT userID FROM Customers WHERE email = ? AND password = ?;";
    $stmt = $mydb->prepare($query);
    $stmt->bind_param('ss', $email, $pwd);
    $stmt->execute();
    $stmt->bind_result($id);
    $stmt->fetch();
    $stmt->close();

    $res = array('id'=>$id); // json object
    $res = array($res);
    echo json_encode($res);
  } else if (!empty($guest) && $guest == "true") {
    // Initialize variable
    $pwd = uniqid();

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

    $res = array('id'=>$last_id); // json object
    $res = array($res);
    echo json_encode($res);
  } else {
    echo "error";
  }

  $mydb->close();

?>
