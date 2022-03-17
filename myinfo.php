<?php
  session_start();
 ?>

 <!DOCTYPE html>
 <html lang="en" dir="ltr">
   <head>
     <meta charset="utf-8">
     <title>My Information</title>
   </head>
   <body>
     <h1>My Information</h1>

     <?php
     // begin connection
     $mydb = new mysqli('localhost', 'root', '', 'bookstore');

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
     echo "<h3>User Information</h3>";

      // if password being changed
      if (!empty($_POST['pwd'])) {
          $query = "UPDATE customers SET password = ? WHERE userID = ?;";
          $stmt = $mydb->prepare($query);
          $stmt->bind_param('si', $_POST['pwd'], $_SESSION['id']);
          $stmt->execute();

          echo "<p>*Password Updated!</p>";
      }

      // if name being changed
      if (!empty($_POST['name'])) {
          $query = "UPDATE customers SET name = ? WHERE userID = ?;";
          $stmt = $mydb->prepare($query);
          $stmt->bind_param('si', $_POST['name'], $_SESSION['id']);
          $stmt->execute();

          echo "<p>*Name Updated!</p>";
      }

      // if email being changed
      if (!empty($_POST['email'])) {
          $query = "UPDATE customers SET email = ? WHERE userID = ?;";
          $stmt = $mydb->prepare($query);
          $stmt->bind_param('si', $_POST['email'], $_SESSION['id']);
          $stmt->execute();

          echo "<p>*Email Updated!</p>";
      }

      // if phone being changed
      if (!empty($_POST['phone'])) {
          $query = "UPDATE customers SET phone = ? WHERE userID = ?;";
          $stmt = $mydb->prepare($query);
          $stmt->bind_param('si', $_POST['phone'], $_SESSION['id']);
          $stmt->execute();

          echo "<p>*Phone Number Updated!</p>";
      }

      // if address being changed
      if (!empty($_POST['addr'])) {
          $query = "UPDATE customers SET address = ? WHERE userID = ?;";
          $stmt = $mydb->prepare($query);
          $stmt->bind_param('si', $_POST['addr'], $_SESSION['id']);
          $stmt->execute();

          echo "<p>*Address Updated!</p>";
      }

      // get customer data
      $query = "SELECT * FROM customers WHERE userID = ?;";
      $stmt = $mydb->prepare($query);
      $stmt->bind_param('i', $_SESSION['id']);
      $stmt->execute();
      $result = $stmt->get_result();

      // table title
      echo "<table>
      <thead>
       <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Address</th>
       </tr>
      </thead>";

      // table body
      echo "<tbody>";
      while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
          echo "<tr>";
          // echo "<td>&emsp;".$row['userID']."&emsp;</td>";
          // echo "<td>&emsp;".$row['password']."&emsp;</td>";
          echo "<td>&emsp;".$row['name']."&emsp;</td>";
          echo "<td>&emsp;".$row['email']."&emsp;</td>";
          echo "<td>&emsp;".$row['phone']."&emsp;</td>";
          echo "<td>&emsp;".$row['address']."&emsp;</td>";
          echo "<td>";
          echo '</td>';
          echo "</tr>";
      }

      echo "</tbody></table>";
      $result->free();
    ?>

    <!-- Edit Information -->
    <h3>Edit My Information</h3>

    <!-- Form for changing password -->
    <?php
      $query = "SELECT * FROM Guest WHERE userID = ?;";
      $stmt = $mydb->prepare($query);
      $stmt->bind_param('i', $_SESSION['id']);
      $stmt->execute();
      $result = $stmt->get_result();

      // if not guest then display change password setting
      if (mysqli_num_rows($result) == 0) {
          echo '<form class="" action="" method="post">
              <label>Password: </label>
              <input type="password" name="pwd" required>
              <button type="submit">Submit</button>&nbsp;
              <input type="reset" value="Reset"><br><br>
            </form>';
      }
      $result->free();
     ?>

    <!-- Form for changing name -->
    <form class="" action="" method="post">
      <label>Name: </label>
      <input type="text" name="name" required>
      <button type="submit">Submit</button>&nbsp;
      <input type="reset" value="Reset"><br><br>
    </form>
    <!-- Form for changing email -->
    <form class="" action="" method="post">
      <label>Email: </label>
      <input type="email" name="email" required>
      <button type="submit">Submit</button>&nbsp;
      <input type="reset" value="Reset"><br><br>
    </form>
    <!-- Form for changing phone number -->
    <form class="" action="" method="post">
      <label>Phone: </label>
      <input type="text" name="phone" required>
      <button type="submit">Submit</button>&nbsp;
      <input type="reset" value="Reset"><br><br>
    </form>
   <!-- Form for changing address -->
    <form class="" action="" method="post">
      <label>Address: </label>
      <input type="text" name="addr" required>
      <button type="submit">Submit</button>&nbsp;
      <input type="reset" value="Reset"><br><br>
    </form>

    <p>Note: User cannot modify userID since userID is automatically generated by system!</p>

    <?php
      // close database connection
      $mydb->close();
    ?>
    </body>
</html>
