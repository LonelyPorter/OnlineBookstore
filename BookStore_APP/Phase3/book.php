<?php
  // begin connection
  $mydb = new mysqli('localhost', 'root', '', 'bookstore');

  // get book info
  $query = "SELECT books.ISBN, title, type, price, Category, in_stock, pName, method, name
            FROM books, `write`, customers
            WHERE books.ISBN = `write`.ISBN AND customers.userID = `write`.userID
            AND books.ISBN = ?";
  $stmt = $mydb->prepare($query);
  $stmt->bind_param('s', $_POST['book']);
  $stmt->execute();
  $result = $stmt->get_result();

  // results
  $info = array();

  while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    $book = array('title' => $row['title'], 'type' => $row['type'], 'price' => $row['price'],
      'Category' => $row['Category'], 'author' => $row['name'], 'pName' => $row['pName']);

    array_push($info, $book);
  }

  echo json_encode($info);

  $mydb->close();
 ?>
