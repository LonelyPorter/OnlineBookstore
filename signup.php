<!--
Sample data:
Email: ElenaMirjana@gmail.com
password: 1016
Name: Elena Mirjana
Phone: 4069278421
Address: 2663 River Road
Status: author/member
-->

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Registration</title>
</head>
<body>
  <?php
  // establish database connection
  $mydb = new mysqli('localhost', 'root', '', 'bookstore');

  if(!empty($_POST['Register'])) {
    // variable initialize
    $pwd = $_POST['pwd'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $addr = $_POST['addr'];
    $status = $_POST['status'];

    // Insert into table
    $mydb->begin_transaction();

    try {
      $query = "INSERT INTO customers(password, name, email, phone, address)
                VALUES (?, ?, ?, ?, ?);";
      $stmt = $mydb->prepare($query);
      $stmt->bind_param('sssss', $pwd, $name, $email, $phone, $addr);
      $stmt->execute();

      // if author
      if($status == 'author') {
        $last_id = mysqli_insert_id($mydb);
        $query = "INSERT INTO author(userID) VALUES (?)";
        $stmt = $mydb->prepare($query);
        $stmt->bind_param('i', $last_id);
        $stmt->execute();
      }

      // if normal customer (member)
      if($status == 'member') {
        $last_id = mysqli_insert_id($mydb);
        $query = "INSERT INTO member(userID) VALUES (?)";
        $stmt = $mydb->prepare($query);
        $stmt->bind_param('i', $last_id);
        $stmt->execute();
      }

      $mydb->commit();
    } catch (\Exception $e) {
      // register fail then go back again
      $mydb->rollback();
      echo "Already Registered.";
      unset($_POST['Register']);
      echo '<a href="signup.php">Go Back</a>';
      $mydb->close();
      exit();
    }

    // succeed go to log in page
    echo "<h3>Register Succeed!</h3>";
    unset($_POST['Register']);
    echo '<a href="index.php">Go Back</a>';
    $mydb->close();
    exit();
  }
   ?>

  <!-- Sing up Form Display -->
  <center><form action="" method="post">
    <h1>Registration</h1><br>
    <input type="email" name="email" placeholder="Email" required /><br><br>
    <input type="text" name="pwd" placeholder="Password" required><br><br>
    <input type="text" name="name" placeholder="Name" required /><br><br>
    <input type="text" name="phone" placeholder="Phone" required /><br><br>
    <input type="text" name="addr" placeholder="Address" required /><br><br>
    <select class="" name="status" required>
      <option disabled selected value>-- Are you a author or a normal customer? --</option>
      <option value="author">Author</option>
      <option value="member">Normal Customer</option>
    </select> <br><br>
    <input type="submit" name="Register" value="Register">
    <input type="reset" name="" value="Reset">
  </form></center>

</body>
</html>
