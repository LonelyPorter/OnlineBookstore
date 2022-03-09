<?php
  session_start();
 ?>

 <!DOCTYPE html>
 <html lang="en" dir="ltr">
   <head>
     <meta charset="utf-8">
     <title>Book Store</title>
   </head>
   <body>
     <h1>Search</h1>

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
    ?>

    <!-- Search Field -->
    <form class="" action="" method="post">
      <!-- Title -->
      <label>Title: </label>
      <input type="text" name="title">

      <!-- Author -->
      <label>Author: </label>
      <?php
        // begin connection
        $mydb = new mysqli('localhost', 'root', '', 'bookstore');

        $query = "SELECT author.userID, name FROM customers, author WHERE customers.userID = author.userID;";
        $stmt = $mydb->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        echo '<select name="author">';
        echo '<option disabled selected> -- Choose an Author -- </option>';
        while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
          // value = userID
          echo '<option value="' . $row['userID'].'">'. $row['name'].'</option>';
        }
        echo '<option value="%">Any</option>';
        echo '</select>';
        $result->free();
       ?>

       <!-- Button -->
       <input type="submit" name="submit" value="Search">
       <input type="reset" value="Reset"><br><br>
    </form>

    <?php


      /* Initialize */
      $title = "%";
      $author = "%";
      if(!empty($_POST['title'])) {
        $title = '%'.$_POST['title'].'%';
      }
      if(!empty($_POST['author'])) {
        $author = $_POST['author'];
      }

      /* Search book by title/author query */
      $query = "SELECT books.ISBN, title, type, price, Category, in_stock, pName, method, name
                FROM books, `write`, customers
                WHERE books.ISBN = `write`.ISBN AND customers.userID = `write`.userID
                  AND title LIKE ? AND `write`.userID LIKE ?;";
      $stmt = $mydb->prepare($query);
      $stmt->bind_param('ss', $title, $author);
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
