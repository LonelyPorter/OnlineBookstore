<?php
  session_start();
  $mydb = new mysqli('localhost', 'root', '', 'bookstore');
  /* Sample Data:
     ISBN: 9781250114297
     title: Humans
     type: hardcover
     price: 14.90
     Category: Travel
     in_stock: 6
  */

 ?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Add Book</title>
</head>
<body>
  <br>
  <center>
    <h1>Adding Book</h1>
    <form class="form" action="" method="post">
      <input type="number" name="ISBN" placeholder="ISBN" required>*<br><br>
      <input type="text" name="title" placeholder="Title">*<br><br>
      <input type="text" name="type" placeholder="Type" required>*<br><br>
      <input type="number" step="0.01" name="price" placeholder="Price" required>*<br><br>
      <input type="text" name="category" placeholder="Category" required>*<br><br>
      <input type="number" name="in_stock" placeholder="Number in stock" required>*<br><br>
      <!-- Delivery Method -->
      <?php
        $query = "SELECT method FROM delivery;";
        $stmt = $mydb->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        echo '<select name="method">';
        echo '<option disabled selected value> -- Choose a Delivery Method -- </option>';
        while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
          // value = userID
          echo '<option value="' . $row['method'].'">'. $row['method'].'</option>';
        }
        echo '</select>';
        $result->free();
       ?>
       <br><br>
       <!-- author -->
      <?php
        $query = "SELECT author.userID, name FROM customers, author WHERE customers.userID = author.userID;";
        $stmt = $mydb->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        echo '<select name="author">';
        echo '<option disabled selected value> -- Choose an Author -- </option>';
        while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
          // value = userID
          echo '<option value="' . $row['userID'].'">'. $row['name'].'</option>';
        }
        echo '</select>';
        $result->free();
       ?>
       <br><br>
      <!-- <input type="text" name="author" placeholder="Author" required>*<br><br> -->
      <!-- <input type="email" name="email" placeholder="Email" required>*<br><br> -->
      <!-- <input type="tel" name="phone" placeholder="Phone"><br><br> -->
      <!-- <input type="text" name="address" placeholder="Address"><br><br> -->
      <input type="submit" name="submit" value="Finish" class="submit-button">
    </form>
  </center>

<?php
     /* When form submitted, insert values into the database */
     if(!empty($_POST['submit'])) {
       /* assign variable */
       // removes backslashes
       $ISBN = stripslashes($_POST['ISBN']);
       //escapes special characters in a string
       $ISBN = mysqli_real_escape_string($mydb, $ISBN);
       $title = stripslashes($_POST['title']);
       $title = mysqli_real_escape_string($mydb, $title);
       $type = stripslashes($_POST['type']);
       $type = mysqli_real_escape_string($mydb, $type);
       $price = stripslashes($_POST['price']);
       $price = mysqli_real_escape_string($mydb, $price);
       $category = stripslashes($_POST['category']);
       $category = mysqli_real_escape_string($mydb, $category);
       $in_stock = stripslashes($_POST['in_stock']);
       $in_stock = mysqli_real_escape_string($mydb, $in_stock);
       $author = $_POST['author'];

       /* Start transaction */
       $mydb->begin_transaction();
       try {
         // insert into books
         $query = "INSERT INTO books(ISBN, title, type, price, Category, in_stock)
         VALUES (?,?,?,?,?,?);";
         $stmt = $mydb->prepare($query);
         $stmt->bind_param('sssdsi', $ISBN, $title, $type, $price, $category, $in_stock);
         $stmt->execute();

         // insert into write
         $query = "INSERT INTO `write`(ISBN, userID) VALUES (?,?);";
         $stmt = $mydb->prepare($query);
         $stmt->bind_param('si', $ISBN, $author);
         $stmt->execute();

         $mydb->commit();
       } catch (\Exception $e) {
         $mydb->rollback();
         echo $e;
         echo "Something went wrong, try again later.";
         exit;
       }
       echo "<center><h3>Adding book successfully!</h3></center>";
     }
  ?>


</body>
</html>
