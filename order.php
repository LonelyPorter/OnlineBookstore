<?php
  session_start();
 ?>

 <!DOCTYPE html>
 <html lang="en" dir="ltr">
   <head>
     <meta charset="utf-8">
     <title>My Order</title>
   </head>
   <body>
     <h1>My Order</h1>

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

     $mydb = new mysqli('localhost', 'root', '', 'bookstore');
     $query = "SELECT * FROM `order` WHERE userID = ?;";

     $stmt = $mydb->prepare($query);
     $stmt->bind_param('i', $_SESSION['id']);
     $stmt->execute();
     $result = $stmt->get_result();

     echo "<table>
       <thead>
         <tr>
           <th>Number</th>
           <th>Time</th>
           <th>Status</th>
         </tr>
       </thead>";

     // table body
     echo "<tbody>";
     while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
         echo "<tr>";
         echo "<td>&emsp;".$row['Number']."&emsp;</td>";
         echo "<td>&emsp;".$row['time']."&emsp;</td>";
         echo "<td>&emsp;".$row['status']."&emsp;</td>";
         echo "</tr>";
     }

     echo "</tbody>";
     echo "</table>";

     $result->free();
     $mydb->close();

      ?>
   </body>
 </html>
