<?php
  // establish database connection
  $mydb = new mysqli('localhost', 'root', '', 'bookstore');

  $name = $_POST['name'];

  if (!empty($name)) {
      $query = "SELECT * FROM publisher WHERE name = ?;";
      $stmt = $mydb->prepare($query);
      $stmt->bind_param('s', $name);
      $stmt->execute();
      $result = $stmt->get_result();

      $publisher = array();

      while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
          $publisherDetail = array('name' => $row['name'],
            'address' => $row['address']);

          array_push($publisher, $publisherDetail);
      }
  }

  if (!empty($name)) {
      $query = "SELECT * FROM Books WHERE pName = ?;";
      $stmt = $mydb->prepare($query);
      $stmt->bind_param('s', $name);
      $stmt->execute();
      $result = $stmt->get_result();

      while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
          $bookResult = array('title' => $row['title']);

          array_push($publisher, $bookResult);
      }
  }

  echo json_encode($publisher);


  $mydb->close();
