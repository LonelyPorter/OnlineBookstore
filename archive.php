  <?php
  try {
      $result = mysqli_query($myconnection, $query);
  } catch (Exception $e) {
      $_SESSION['valid'] = false;
      $_SESSION['error'] = "Invalid email/passowrd. Please Try again.";
      header("Location: index.php");
      exit;
  }
  ?>
