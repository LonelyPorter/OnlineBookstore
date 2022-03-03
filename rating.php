<?php
  session_start();
 ?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <h1>Rating</h1>
    <a href="store.php">store home</a>
    <?php
    // establish database connection
    $myconnection = mysqli_connect('localhost', 'root', '')
    or die('Could not connect: ' . mysql_error());
    $mydb = mysqli_select_db($myconnection, 'bookstore') or die('Could not select database');

    // fetch for the account in database
    $query = "SELECT * FROM Rating;";
    $stmt = mysqli_prepare($myconnection, $query);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);

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
     ?>

     <form action="" method="post">
       <div>
         <label>Year:</label>
         <input type="text" name="year" value="">
         <input type="submit" value="Search">
       </div>
     </form>

     <?php
      if(empty($_POST['year'])) {
        echo "Best Selling book of all time";
        // query
      } else {
        echo "Best selling book of year".$_POST['year']." is:";
      }
      ?>
  </body>
</html>
