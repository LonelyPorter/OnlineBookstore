<?php
  session_start();
 ?>

 <!DOCTYPE html>
 <html lang="en" dir="ltr">
   <head>
     <meta charset="utf-8">
     <title>My Book</title>
   </head>
   <body>
     <h1>My Books</h1>

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

     echo '<h3>Books Infomation</h3>';

     // begin connection
      $mydb = new mysqli('localhost', 'root', '', 'bookstore');

      $query = "SELECT books.ISBN, title, type, price, Category, in_stock, pName, method, name
                FROM books, `write`, customers
                WHERE books.ISBN = `write`.ISBN AND customers.userID = `write`.userID AND customers.userID = ?;";
      $stmt = $mydb->prepare($query);
      $stmt->bind_param('s', $_SESSION['id']);
      $stmt->execute();
      $result = $stmt->get_result();

      if(mysqli_num_rows($result) == 0) {
        echo "<h3>No record can be found with search condition</h3>";
      } else {
        // table title
        echo "<table>
        <thead>
        <tr>
        <th>ISBN</th>
        <th>Title</th>
        <th>Type</th>
        <th>Price</th>
        <th>Category</th>
        <th>Author</th>
        <th>Stock</th>
        <th>Publisher Name</th>
        <th>Delivery Method</th>
        <th>Option</th>
        </tr>
        </thead>";

        // table body
        echo "<tbody>";
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
          echo '<form action="cart.php" method="post">';
          echo "<tr>";
          echo "<td>&emsp;".$row['ISBN']."&emsp;</td>";
          echo "<td>&emsp;".$row['title']."&emsp;</td>";
          echo "<td>&emsp;".$row['type']."&emsp;</td>";
          echo "<td>&emsp;".$row['price']."&emsp;</td>";
          echo "<td>&emsp;".$row['Category']."&emsp;</td>";
          echo "<td>&emsp;".$row['name']."&emsp;</td>";
          if ($row['in_stock'] >= 0) {
            echo "<td>&emsp;".$row['in_stock']."&emsp;</td>";
          } else {
            echo "<td>&emsp;&infin;&emsp;</td>";
          }
          echo "<td>&emsp;".$row['pName']."&emsp;</td>";
          echo "<td>&emsp;".$row['method']."&emsp;</td>";
          echo "<td>";
          // echo '<input type="submit" name="ISBN" value="add to cart">';
          echo '<button type="submit" name="ISBN" value="' .$row['ISBN']. '">add to cart</button>';
          echo '</td>';
          echo "</tr>";
          echo '</form>';
        }
        echo "</tbody></table>";
      }

      // close database connection
      $result->free();
      $mydb->close();
     ?>

    </body>
</html>
