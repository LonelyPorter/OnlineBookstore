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
    
     $myconnection = mysqli_connect('localhost', 'root', '')
      or die ('Could not connect: ' . mysql_error());
     $mydb = mysqli_select_db ($myconnection, 'bookstore') or die ('Could not select database');
     ?>

    <form class="form" action="" method="post">
      <label>Title: </label>
      <input type="text" name="title">
      <label for="author">Author:</label>
      <select name="author" id="author">
        <optgroup label="Author Name">    
          <option disabled selected value> -- Choose an Author -- </option>
          <option value="Felix Daniel">Felix Daniel</option>
          <option value="Rita Swanson">Rita Swanson</option>
          <option value="Ernesto Robbins">Ernesto Robbins</option>
          <option value="Jared Terry">Jared Terry</option>
          <option value="Clara Farmer">Clara Farmer</option>
        </optgroup>
      </select>        
      <input type="submit" name="submit" value="Search">
      <input type="reset" value="Reset"><br><br>
    </form>


    <?php
     if(!empty($_POST['title']) && empty($_POST['author'])) { 
         $title = $_POST['title']; 
         
         $query = "SELECT * FROM books WHERE title LIKE '%".$title."%'";
         $result = mysqli_query($myconnection, $query) or die ('Query failed: ' . mysql_error());
         
         echo "<br>";
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
         </tr>
         </thead>";
         
         // table body
         echo "<tbody>";
         while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
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
                echo "<td>";
                echo '</td>';
                echo "</tr>";
                echo '</form>';
            }
            
            echo "</tbody></table>";

            // close database connection
            mysqli_free_result($result);
            mysqli_close($myconnection);
        }elseif(empty($_POST['title']) && !empty($_POST['author'])) {
            $author = $_POST['author'];
            
            $query ="SELECT distinct books.ISBN, books.title, books.type, books.price, books.Category, books.in_stock, books.pName, books.method, customers.name 
            FROM books, `write`, customers where books.ISBN=`write`.ISBN and `write`.userID=customers.userID and customers.name LIKE '%".$author."%';";
            $result = mysqli_query($myconnection, $query) or die ('Query failed: ' . mysql_error());
            
            echo "<br>";
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
            <th>Author Name</th>
            </tr>
            </thead>";
            
            // table body
            echo "<tbody>";
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
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
                echo "<td>&emsp;".$row['name']."&emsp;</td>";
                echo "<td>";
                echo '</td>';
                echo "</tr>";
                echo '</form>';
            }
            
            echo "</tbody></table>";

            // close database connection
            mysqli_free_result($result);
            mysqli_close($myconnection);
        }elseif(!empty($_POST['title']) && !empty($_POST['author'])) { 
            $title = $_POST['title'];
            $author = $_POST['author'];
            
            $query ="SELECT distinct books.ISBN, books.title, books.type, books.price, books.Category, books.in_stock, books.pName, books.method, customers.name 
            FROM books, `write`, customers where (books.ISBN=`write`.ISBN and `write`.userID=customers.userID) and (books.title LIKE '%".$title."%' and customers.name LIKE '%".$author."%');";
            $result = mysqli_query($myconnection, $query) or die ('Query failed: ' . mysql_error());
            
            echo "<br>";
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
            <th>Author Name</th>
            </tr>
            </thead>";
            
            // table body
            echo "<tbody>";
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
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
                echo "<td>&emsp;".$row['name']."&emsp;</td>";
                echo "<td>";
                echo '</td>';
                echo "</tr>";
                echo '</form>';
            }
            
            echo "</tbody></table>";

            // close database connection
            mysqli_free_result($result);
            mysqli_close($myconnection);
        }
    ?>
    </body>
</html>