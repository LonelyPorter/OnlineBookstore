<?php
  session_start();
 ?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>My Book Store</title>
</head>
<body>
<?php
     $mydb = new mysqli('localhost', 'root', '', 'bookstore');

     //$pname = $_POST['name'];
     //echo "$pname";

    // When form submitted, insert values into the database.
    if (isset($_REQUEST['ISBN'])) {
        // removes backslashes
        $ISBN = stripslashes($_REQUEST['ISBN']);
        //escapes special characters in a string
        $ISBN = mysqli_real_escape_string($mydb, $ISBN);
        $title = stripslashes($_REQUEST['title']);
        $title = mysqli_real_escape_string($mydb, $title);
        $type = stripslashes($_REQUEST['type']);
        $type = mysqli_real_escape_string($mydb, $type);
        $price = stripslashes($_REQUEST['price']);
        $price = mysqli_real_escape_string($mydb, $price);
        $category = stripslashes($_REQUEST['category']);
        $category = mysqli_real_escape_string($mydb, $category);
        $instock = stripslashes($_REQUEST['instock']);
        $instock = mysqli_real_escape_string($mydb, $instock);

        $query    = "INSERT into books(ISBN, title, type, price, Category, in_stock)
                     VALUES ('$ISBN', '$title', '$type', '$price', '$category', '$instock')";
        $result = mysqli_query($mydb, $query) or die('Query failed: ' . mysql_error());
        //$query2   = "UPDATE books SET pName = ? WHERE ISBN = $ISBN";
        //$result   = mysqli_query($myconnection, $query2);
        //echo "$query2";

        if ($result) {
            echo "<div class='form'>
                  <center><h3>Adding book successfully! Add author information?</h3></center><br>
                  <center><p>Click here to <a href='store.php'>Login</a></p><center><br>
                  </div>";
        } else {
            echo "<div class='form'>
                  <center><h3>Required fields are missing.</h3></center><br>
                  <center><p>Click here to <a href='addbook.php'>registration</a> again.</p></center><br>
                  </div>";
        }
    } else {
?>
    <center><form class="form" action="" method="post">
        <h1 class="login-title">Adding Book</h1><br>
        <input type="text" class="login-input" name="ISBN" placeholder="ISBN" required /><br><br>
        <input type="text" class="login-input" name="title" placeholder="Title"><br><br>
        <input type="text" class="login-input" name="type" placeholder="Type" required /><br><br>
        <input type="text" class="login-input" name="price" placeholder="Price" required /><br><br>
        <input type="text" class="login-input" name="category" placeholder="Category" required /><br><br>
        <input type="text" class="login-input" name="instock" placeholder="In_stock" required /><br><br>
        <input type="submit" name="submit" value="Finish" class="submit-button">
    </form></center>

<?php
    }
?>
</body>
</html>
