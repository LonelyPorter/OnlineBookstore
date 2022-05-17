<?php
  session_start();
 ?>

 <!DOCTYPE html>
 <html lang="en" dir="ltr">
   <head>
     <meta charset="utf-8">
     <title>Prime Overview</title>
   </head>
   <body>
     <h1>Prime Overview</h1>

     <?php
     // check if logged in
     if (!isset($_SESSION['id']) && $_SESSION['id'] != 1000) {
         exit;
     } else {
       // Login in information
       echo "You are login as (userID): ".$_SESSION['id'];
       echo "&emsp;&emsp;";
       echo '<a href="store.php">store home</a>';
       echo "<br><br>";
     }

    // connect database
    $mydb = new mysqli('localhost', 'root', '', 'bookstore');

    echo "<h4>Welcome, administrator!</h4>";

    /* Update prime status first if button is hit*/
    if(!empty($_POST['unenroll']) || !empty($_POST['enroll'])) {
      $status = NULL;
      $user = NULL;
      if(!empty($_POST['unenroll'])){
        $status = 0;
        $user = $_POST['unenroll'];
      } else if(!empty($_POST['enroll'])) {
        $status = 1;
        $user = $_POST['enroll'];
      }

      $query = "UPDATE member SET prime = ? WHERE userID = ?;";
      $stmt = $mydb->prepare($query);
      $stmt->bind_param('ii', $status, $user);
      $stmt->execute();

      $query = "UPDATE author SET prime = ? WHERE userID = ?;";
      $stmt = $mydb->prepare($query);
      $stmt->bind_param('ii', $status, $user);
      $stmt->execute();
    }

    // List prime overview
    $query = "SELECT * FROM member UNION SELECT * FROM author;";

    $stmt = $mydb->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "<table>
    <thead>
    <tr>
    <th>UserID</th>
    <th>Prime</th>
    </tr>
    </thead>";

    echo "<tbody>";
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
      echo "<tr>";
      echo "<td>&emsp;".$row['userID']."&emsp;</td>";
      echo "<td>&emsp;".$row['prime']."&emsp;</td>";
      echo '<td><form action="" method="post">';
      echo "<button type=\"submit\" name=\"unenroll\" value=".$row['userID'].">Unenroll</button>&emsp;";
      echo "<button type=\"submit\" name=\"enroll\" value=".$row['userID'].">Enroll</button>&emsp;";
      echo "</td></form>";
      echo "</tr>";
    }

    echo "</tbody>";
    echo "</table>";
    echo "<br>";

    echo "Notice: 0 represents that the user is not a prime; 1 represents that the user is a prime now.";

    $result->free();
    $mydb->close();
    ?>
   </body>
 </html>
