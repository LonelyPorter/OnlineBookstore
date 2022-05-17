<?php
  $id = $_POST['id'];
  $mydb = new mysqli('localhost', 'root', '', 'bookstore');

  if(!empty($id)) {
    // get user basic informatoin
    $query = "SELECT * FROM customers WHERE userID = ?;";
    $stmt = $mydb->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    $profile = array();

    while($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
      $info = array('name' => $row['name'], 'email' => $row['email'],
      'phone' => $row['phone'], 'address' => $row['address']);

      // array_push($profile, $info);
    }
    $result->free();
    $stmt->close();

    // get prime status
    $query = "SELECT * FROM
             (SELECT * FROM Member UNION SELECT * FROM Author) AS T
             WHERE userID = ?;";
    $stmt = $mydb->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if (mysqli_num_rows($result) == 0) { // guest
      $info['prime'] = -1;
    } else { // normal user/author (non-guest)
      while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        if($row['prime']) {
          $info['prime'] = 1;
        } else {
          $info['prime'] = 0;
        }
      }
    }

    // make it a jsonArray
    array_push($profile, $info);

    // output json format of all informatoin
    echo json_encode($profile);
  }


  $mydb->close();
 ?>
