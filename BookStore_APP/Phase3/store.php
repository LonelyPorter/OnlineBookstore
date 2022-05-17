<?php
  $id = $_POST['id'];
  $mydb = new mysqli('localhost', 'root', '', 'bookstore');

  if(!empty($id)) {
    $query = "SELECT * FROM Books;";
    $stmt = $mydb->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();

    $books = array();

    while($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
      $bookResult = array('ISBN' => $row['ISBN'], 'title' => $row['title']);

      array_push($books, $bookResult);
    }
    echo json_encode($books);
  }

  $mydb->close();
 ?>
