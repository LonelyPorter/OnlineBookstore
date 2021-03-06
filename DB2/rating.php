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
    <br><br>
    <?php
    // establish database connection
    $mydb = new mysqli('localhost', 'root', '', 'bookstore');

    // fetch for the account in database
    $query = "SELECT * FROM Rating ORDER BY time DESC;";
    $stmt = $mydb->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    echo "<table>
      <thead>
        <tr>
          <th>userID</th>
          <th>ISBN</th>
          <th>star</th>
          <th>comment</th>
          <th>time</th>
        </tr>
      </thead>";

    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        echo "<tr>";
        echo "<td>&emsp;".$row['userID']."&emsp;</td>";
        echo "<td>&emsp;".$row['ISBN']."&emsp;</td>";
        echo "<td>&emsp;".$row['star']."&emsp;</td>";
        echo "<td>&emsp;".$row['comment']."&emsp;</td>";
        echo "<td>&emsp;".$row['time']."&emsp;</td>";
        echo "</tr>";
    }

    echo "</tbody></table>";
    echo "<br><br>";
     ?>

     <form action="" method="post">
       <div>
         <label>Year:</label>
         <input type="text" name="year" value="">
         <input type="submit" value="Search">
       </div>
     </form>
     <br>
     <?php
      $year = '%';
      if (empty($_POST['year'])) {
          echo "Best Rating book of all time:";
      } else {
          echo "Best Rating book of year ".$_POST['year'].":";
          $year = $_POST['year'];
      }

      $query = "SELECT DISTINCT T1.ISBN, title, type, price, Category, in_stock, pName, method, name
        FROM books, customers, `write`,
        (SELECT ISBN, avg(star) AS star FROM rating WHERE EXTRACT(year FROM time) LIKE ? GROUP BY ISBN
        Having star = (SELECT max(star) FROM
        (SELECT ISBN, avg(star) AS star
        FROM rating WHERE EXTRACT(year FROM time) LIKE ?
        GROUP BY ISBN) AS T))AS T1
        WHERE T1.ISBN = books.ISBN AND books.ISBN = `write`.ISBN AND customers.userID = `write`.userID;";
      $stmt = $mydb->prepare($query);
      $stmt->bind_param('ss', $year, $year);
      $stmt->execute();
      $result = $stmt->get_result();
      $stmt->close();

      if (mysqli_num_rows($result) == 0) {
          //results are empty
          echo "<p>No books for the entered year.</p>";
          echo "<br><br>";
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
            </tr>
          </thead>";

          echo "<tbody>";
          while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
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
              echo "</tr>";
          }
      }
      ?>
  </body>
</html>
