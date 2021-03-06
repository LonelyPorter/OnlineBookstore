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
     $query = "SELECT orderNumber, title, inOrder.ISBN, quantity, status, time, price * quantity as total
               FROM inorder, `order`, books
               WHERE orderNumber = Number AND userID = ? AND inorder.ISBN = books.ISBN
               ORDER BY time ASC;";

     $stmt = $mydb->prepare($query);
     $stmt->bind_param('i', $_SESSION['id']);
     $stmt->execute();
     $result = $stmt->get_result();

     if(mysqli_num_rows($result) == 0) {
       echo "<h2>No order history can be found!</h2>";
     } else {
       echo "<table>
       <thead>
       <tr>
       <th>Number</th>
       <th>Title</th>
       <th>ISBN</th>
       <th>Quantity</th>
       <th>Status</th>
       <th>Time</th>
       <th>Total</th>
       <th>Option</th>
       </tr>
       </thead>";

       // table body
       echo "<tbody>";
       $order = -1; # to check if the books belong to one order
       $total = 0; # total price of the particular order
       while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
         // print out total price
         if ($order != $row['orderNumber'] && $order != -1) {
           echo "<tr>";
           echo "<td>&emsp;---</td>";
           echo "<td>&emsp;---</td>";
           echo "<td>&emsp;---</td>";
           echo "<td>&emsp;---</td>";
           echo "<td>&emsp;---</td>";
           echo "<td>&emsp;---</td>";
           echo "<td>&emsp;<b>".$total."</b>&emsp;</td>";
           echo "</tr>";
           $total = 0;
         }

         /* order:book entry */
         echo "<tr>";
         echo "<td>&emsp;".$row['orderNumber']."&emsp;</td>";
         echo "<td>&emsp;".$row['title']."&emsp;</td>";
         echo "<td>&emsp;".$row['ISBN']."&emsp;</td>";
         echo "<td>&emsp;".$row['quantity']."&emsp;</td>";
         echo "<td>&emsp;".$row['status']."&emsp;</td>";
         echo "<td>&emsp;".$row['time']."&emsp;</td>";
         echo "<td>&emsp;".$row['total']."&emsp;</td>";
         echo '<td><form action="cart.php" method="post">
         <button type="submit" name="ISBN" value="'.$row['ISBN'].'">Reorder</button>
         </form></td>';
         echo "</tr>";

         $order = $row['orderNumber'];
         $total += $row['total'];
       }

       // print last row of total price
       echo "<tr>";
       echo "<td>&emsp;---</td>";
       echo "<td>&emsp;---</td>";
       echo "<td>&emsp;---</td>";
       echo "<td>&emsp;---</td>";
       echo "<td>&emsp;---</td>";
       echo "<td>&emsp;---</td>";
       echo "<td>&emsp;<b>".$total."</b>&emsp;</td>";
       echo "</tr>";

       echo "</tbody>";
       echo "</table>";
     }


     $result->free();
     $mydb->close();
    ?>

   </body>
 </html>
