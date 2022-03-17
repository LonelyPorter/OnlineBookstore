<!--
Superuser Only:
Update delivery method price
-->
<?php
  session_start();
 ?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Administrator</title>
  </head>
  <body>

  <h1>Superuser Manage</h1>
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

    if(!empty($_POST['method'])) {
      $query = "UPDATE Delivery SET cost = ? WHERE method = ?;";
      $stmt = $mydb->prepare($query);
      $stmt->bind_param('ds',$_POST['cost'], $_POST['method']);
      $stmt->execute();
    }
?>
  <!-- Update delivery method -->
  <h1>&emsp;Delivery Method</h1>
  <form class="form" action="update.php" method="post">
      <label for="cost"> Cost: </label>
      <input type="number" name="cost" step=0.01 placeholder="$10.00" required/><br><br>
      <input type="radio" name="method" value="email" required/>
      <label for="email">Email</label>
      <input type="radio" name="method" value="hardcover" required/>
      <label for="hardcover">Hardcover</label>
      <input type="radio" name="method" value="loose leaf" required/>
      <label for="looseleaf">Loose Leaf</label>
      <input type="radio" name="method" value="paperback" required/>
      <label for="paperback">Paperback</label><br><br>
      <input type="submit" name="submit" value="Update" class="update-button">
      <input type="reset" name="reset" value="Reset" class="reset-button">
  </form>

  <h1>&emsp;Summary</h1>
  <?php
    $query = "SELECT * FROM delivery";
    $stmt = $mydb->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "<table>
      <thead>
        <tr>
          <th>Method</th>
          <th>Time</th>
          <th>Cost</th>
        </tr>
      </thead>";

    // table body
    echo "<tbody>";
    while ($row = mysqli_fetch_array ($result, MYSQLI_ASSOC)) {
      echo "<tr>";
      echo "<td>&emsp;".$row['method']."&emsp;</td>";
      echo "<td>&emsp;".$row['time']."&emsp;</td>";
      echo "<td>&emsp;".$row['cost']."&emsp;</td>";
      echo "</tr>";
    }
    echo "</tbody></table>";
    $result->free();
    ?>

    <!-- Update Order -->
    <?php
      if(isset($_POST['status'])) {
        foreach ($_POST['status'] as $key => $value) {
          $query = "UPDATE `order` SET status = ? WHERE `Number` = ?;";
          $stmt = $mydb->prepare($query);
          $stmt->bind_param('ss', $_POST['status'][$key], $_POST['order'][$key]);
          $stmt->execute();
          $stmt->close();
        }
      }
     ?>

    <!-- Manage Order -->
    <h1>&emsp;&emsp;&emsp;&emsp;Order(s)</h1>
    <?php
      $query = "SELECT * FROM `order` ORDER BY time;";
      $stmt = $mydb->prepare($query);
      $stmt->execute();
      $result = $stmt->get_result();

      echo "<table>
        <thead>
          <tr>
            <th>Order#</th>
            <th>userID</th>
            <th>Time</th>
            <th>Status</th>
            <th>Update To</th>
          </tr>
        </thead>";

      // table body
      echo "<tbody>";
      while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $status = array('pending'=>'pending', 'processing'=>'processing', 'finished'=>'finished');
        echo "<tr>";
        echo '<form action="" method="post">';
        echo "<td>&emsp;".$row['Number']."&emsp;</td>";
        // order number in form
        echo '<input type="hidden" name="order[]" value="'.$row['Number'].'">';
        echo "<td>&emsp;".$row['userID']."&emsp;</td>";
        echo "<td>&emsp;".$row['time']."&emsp;</td>";
        echo "<td>&emsp;".$row['status']."&emsp;</td>";
        // status
        echo '<td>';
        echo '<select name="status[]">';
        echo '<option selected>'.$status[$row['status']].'</option>';
        unset($status[$row['status']]);
        foreach ($status as $s) {
          echo '<option value="'.$s.'">'.$s.'</option>';
        }
        echo '</select>';
        echo '</td>';

        echo '<form>';
        echo "</tr>";
      }
      echo "</tbody>";
      echo "</table>";
     ?>
     <button type="submit" name="orders">Update</button>
     <br><br>

    <?php
      $mydb->close();
     ?>


  </body>
</html>
