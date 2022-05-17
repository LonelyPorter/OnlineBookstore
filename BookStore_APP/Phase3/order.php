<?php
  $id = $_POST['id'];
  $mydb = new mysqli('localhost', 'root', '', 'bookstore');

  if(!empty($id)) {
    $query = "SELECT orderNumber, title, inOrder.ISBN, quantity, status, time, price * quantity as total
              FROM inorder, `order`, books
              WHERE orderNumber = Number AND userID = ? AND inorder.ISBN = books.ISBN
              ORDER BY time ASC;";

    $stmt = $mydb->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    $orders = array();

    while($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
      $order = array('orderNumber' => $row['orderNumber'],
        'title' => $row['title'], 'quantity' => $row['quantity']);

      array_push($orders, $order);
    }
    echo json_encode($orders);
  }

  $mydb->close();
 ?>
