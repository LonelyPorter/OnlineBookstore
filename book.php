<?php
  session_start();
 ?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Book</title>
  </head>
  <body>
    <h1>Book Info</h1>
    <a href="store.php">store home</a>
    <br><br>

    <?php
     // begin connection
     $mydb = new mysqli('localhost', 'root', '', 'bookstore');

     $query = "SELECT books.ISBN, title, type, price, Category, in_stock, pName, method, name
               FROM books, `write`, customers
               WHERE books.ISBN = `write`.ISBN AND customers.userID = `write`.userID
               AND books.ISBN = ?";
     $stmt = $mydb->prepare($query);
     $stmt->bind_param('s', $_GET['book']);
     $stmt->execute();
     $result = $stmt->get_result();

     if(mysqli_num_rows($result) == 0) {
       echo "<h3>Error</h3>";
       exit();
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
         echo '<button type="submit" name="ISBN" value="' .$row['ISBN']. '">add to cart</button>';
         echo '</td>';
         echo "</tr>";
         echo '</form>';
       }
       echo "</tbody></table>";
     }
     $result->free();
    ?>

    <!-- Update comment -->
    <?php
    if(isset($_POST['rate'])) {
      $mydb->begin_transaction();

      $id = $_SESSION['id'];
      $isbn = $_GET['book'];
      $time = date("Y-m-d");
      $star = $_POST['star'];
      $comment = $_POST['comment'];

      try {
        $query = "INSERT INTO Rating (userID, ISBN, star, comment, time)
                  VALUES (?, ?, ?, ?, ?)";
        $stmt = $mydb->prepare($query);
        $stmt->bind_param('isiss', $id, $isbn, $star, $comment, $time);
        $stmt->execute();

        $mydb->commit();
      } catch (\Exception $e) {
        $mydb->rollback();
        echo "Error";
      }

    }
     ?>

    <!-- Comment/Rating -->
    <h3>Rating/Comment</h3>
    <?php
      $query = "SELECT * FROM Rating WHERE ISBN = ?;";
      $stmt = $mydb->prepare($query);
      $stmt->bind_param("s", $_GET['book']);
      $stmt->execute();
      $result = $stmt->get_result();

      echo "<table>
        <thead>
          <tr>
            <th>userID</th>
            <th>Number</th>
            <th>ISBN</th>
            <th>star</th>
            <th>comment</th>
            <th>time</th>
          </tr>
        </thead>";

      while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        echo "<tr>";
        echo "<td>&emsp;".$row['userID']."&emsp;</td>";
        echo "<td>&emsp;".$row['Number']."&emsp;</td>";
        echo "<td>&emsp;".$row['ISBN']."&emsp;</td>";
        echo "<td>&emsp;".$row['star']."&emsp;</td>";
        echo "<td>&emsp;".$row['comment']."&emsp;</td>";
        echo "<td>&emsp;".$row['time']."&emsp;</td>";
        echo "</tr>";
      }
      echo "</tbody></table>";
      $result->free();

      // if not logged in or user not purchase the book
      $query = "SELECT * FROM inorder, `order`
                WHERE inorder.orderNumber = `order`.Number and `order`.status = 'finished'
                 AND userID = ? AND ISBN = ?;";
      $stmt = $mydb->prepare($query);
      $stmt->bind_param('is', $_SESSION['id'], $_GET['book']);
      $stmt->execute();
      $result = $stmt->get_result();

      if(mysqli_num_rows($result) == 0) {
        echo '<h3>*You need to purchase the book before you can comment.</h3>';
        exit();
      }
      $result->free();
     ?>

     <h4>Rate the books:</h4>
     <form action="" method="post">
       <div>
         <label for="star">Star:</label>
         <input type="number" name="star" min="1" max="5" required/>
       </div>
       <br>
       <label for="comment">Comment:</label><br>
       <textarea name="comment" rows="3" cols="20"></textarea>
       <br>
       <input type="submit" name="rate" value="Submit">
     </form>

  </body>
</html>
