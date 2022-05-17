<?php
  session_start();
  $name = $_SESSION['pName'];
 ?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Publisher Manager</title>
  </head>
  <body>
  <?php
   // establish database connection
  $mydb = new mysqli('localhost', 'root', '', 'bookstore');

  // print header
  echo '<h1>Welcome Publisher!</h1>';
  // Login in information
  echo "You are login as: $name";
  echo "&emsp;&emsp;";
  echo '<a href="publisher_in.php">publisher home</a>';
  echo "<br><br>";

    if(!empty($_POST['price'])) {
      $query = "UPDATE books SET price = ? WHERE ISBN = ?;";

      $stmt = $mydb->prepare($query);
      $stmt->bind_param('ds', $_POST['price'], $_POST['price_submit']);
      $stmt->execute();
    }

     $query = "SELECT * FROM Books WHERE pName = ?;";
     $stmt = $mydb->prepare($query);
     $stmt->bind_param('s', $name);
     $stmt->execute();
     $result = $stmt->get_result();

     echo "<h3>Book Infomation</h3>";

     // table title
     echo "<table>
       <thead>
         <tr>
           <th>ISBN</th>
           <th>Title</th>
           <th>Type</th>
           <th>Price</th>
           <th>Category</th>
           <th>Stock</th>
           <th>Publisher Name</th>
           <th>Delivery Method</th>
           <th>Price Update</th>
         </tr>
       </thead>";

     // table body
     echo "<tbody>";
     while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
         echo '<form action="" method="post">';
         echo "<tr>";
         echo "<td>&emsp;".$row['ISBN']."&emsp;</td>";
         echo "<td>&emsp;".$row['title']."&emsp;</td>";
         echo "<td>&emsp;".$row['type']."&emsp;</td>";
         echo "<td>&emsp;".$row['price']."&emsp;</td>";
         echo "<td>&emsp;".$row['Category']."&emsp;</td>";
         if ($row['in_stock'] >= 0) {
             echo "<td>&emsp;".$row['in_stock']."&emsp;</td>";
         } else {
             echo "<td>&emsp;&infin;&emsp;</td>";
         }
         echo "<td>&emsp;".$row['pName']."&emsp;</td>";
         echo "<td>&emsp;".$row['method']."&emsp;</td>";
         // update price
         echo "<td>";
         echo '<input type="number" step="0.01" name="price" required/>';
         echo '<button type="submit" name="price_submit" value="'.$row['ISBN'].'">Update</button>';
         echo '<input type="reset" value="Reset">';
         echo "</td>";

         echo "</tr>";
         echo '</form>';
     }
     echo "</tbody></table>";
     echo "<br>";

  // close database connection
  $result->free();
  $mydb->close();
    ?>
  </body>
</html>
