<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>My Book Store</title>
  </head>
  <body>
  <?php
    $myconnection = mysqli_connect('localhost', 'root', '')
      or die ('Could not connect: ' . mysql_error());

    $mydb = mysqli_select_db ($myconnection, 'bookstore') or die ('Could not select database');

    if(isset($_POST['method']) && !empty($_POST['method'])) {
      $query1 = "UPDATE delivery SET cost = ".$_POST['cost']." WHERE method = '".$_POST['method']."';";

      mysqli_query($myconnection, $query1) or die('Query failed: ' . mysql_error());
    }
?>
  <center><form class="form" action="update.php" method="post">
      <h1 class="login-title">Book Store Delivery</h1>
      <a href="store.php">store home</a>
      <br><br>
      <input type="text" class="login-input" name="cost" placeholder="Cost" required /><br><br>
      <input type="radio" name="method" value="email"/>
      <label for="email">Email</label>
      <input type="radio" name="method" value="hardcover"/>
      <label for="hardcover">Hardcover</label>
      <input type="radio" name="method" value="loose leaf"/>
      <label for="looseleaf">Loose Leaf</label>
      <input type="radio" name="method" value="paperback"/>
      <label for="paperback">Paperback</label><br><br>
      <input type="submit" name="submit" value="Update" class="update-button">
      <input type="reset" name="reset" value="Reset" class="reset-button">
  </form></center><br><br><br>

  <center><h1>Summary</Summary></h1></center>
    <center><?php
    $query2 = "SELECT * FROM delivery";
    $result = mysqli_query($myconnection, $query2) or die ('Query failed: ' . mysql_error());

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

    mysqli_free_result($result);

    mysqli_close($myconnection);

    ?></center>
  </body>
</html>
