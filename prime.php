<?php
  session_start();
 ?>

 <!DOCTYPE html>
 <html lang="en" dir="ltr">
   <head>
     <meta charset="utf-8">
     <title>My Prime</title>
   </head>
   <body>
     <h1>Prime Membership</h1>

     <?php
     // check if logged in
     if (!isset($_SESSION['id'])) {
         exit;
     } else {
       // Login in information
       echo "You are login as (userID): ".$_SESSION['id'];
       echo "&emsp;&emsp;";
       echo '<a href="store.php">store home</a>';
       echo "<br><br>";
     }
     // connect to database
     $mydb = new mysqli('localhost', 'root', '', 'bookstore');

     // if superuser, move to adm_prime.php
     if($_SESSION['id'] == 1000) {
       header("Location: adm_prime.php");
       exit();
     }

     // if update is hit
     if(!empty($_POST['prime'])) {
       /* update both table(member/author) since one id belongs to only one table;
          therefore, only one update will succeed, the other has no effect
       */
       $status = NULL;
       if($_POST['prime'] == "True"){
         $status = 1;
       } else {
         $status = 0;
       }

       $query = "UPDATE member SET prime = ? WHERE userID = ?;";
       $stmt = $mydb->prepare($query);
       $stmt->bind_param('ii', $status, $_SESSION['id']);
       $stmt->execute();

       $query = "UPDATE author SET prime = ? WHERE userID = ?;";
       $stmt = $mydb->prepare($query);
       $stmt->bind_param('ii', $status, $_SESSION['id']);
       $stmt->execute();
     }

     // find membership status
     $query = "SELECT * FROM
              (SELECT * FROM Member UNION SELECT * FROM Author) AS T
              WHERE userID = ?;";
     $stmt = $mydb->prepare($query);
     $stmt->bind_param('i', $_SESSION['id']);
     $stmt->execute();
     $result = $stmt->get_result();

     if (mysqli_num_rows($result) == 0) {
       // Empty result = Must be a Guest
       echo "<p>Guest cannot enroll in Prime MemberShip</p>";
       echo "<br><br>";
     } else { // otherwise, it's customer/author
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
         if($row['prime']) { // yes
           echo "<td>&emsp;&#9989;&emsp;</td>";
         } else { // no
           echo "<td>&emsp;&#10060;&emsp;</td>";
         }
         echo "</tr>";
       }

       echo "</tbody>";
       echo "</table>";
     }

     $stmt->close();
    ?>
      <form action="" method="post">
        <input type="radio" name="prime" value="False">
        <label>Non-Prime</label>
        <input type="radio" name="prime" value="True">
        <label>Prime</label><br><br>
        <input type="submit" name="submit" value="Update">
        <input type="reset" name="reset" value="Reset">
      </form><br>

   </body>
 </html>
