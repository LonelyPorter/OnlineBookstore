<?php
  $mydb = new mysqli('localhost', 'root', '', 'bookstore');

  $title = "%";
  $author = "%";

  if (!empty($_POST['title'])) {
      $title = '%'.$_POST['title'].'%';
  }
  if (!empty($_POST['author'])) {
      $author = '%'.$_POST['author'].'%';
  }

  $query = "SELECT books.ISBN, title, type, price, Category, in_stock, pName, method, name
                FROM books, `write`, customers
                WHERE books.ISBN = `write`.ISBN AND customers.userID = `write`.userID
                AND title LIKE ? AND customers.name LIKE ?;";
  $stmt = $mydb->prepare($query);
  $stmt->bind_param('ss', $title, $author);
  $stmt->execute();
  $result = $stmt->get_result();

  $searchResult = array();

  while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
    $search = array('title' => $row['title'], 'author' => $row['name']);

      array_push($searchResult, $search);
  }
    echo json_encode($searchResult);

  $mydb->close();
