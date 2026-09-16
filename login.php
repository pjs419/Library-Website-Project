<?php
session_start();
require_once "database.php";

if (isset($_SESSION["error"])) {
  echo ('<p style="color:red">Error: ' . $_SESSION["error"] . "</p>");
  unset($_SESSION["error"]);
}
if (isset($_SESSION["success"])) {
  echo ('<p style="color:green">' . $_SESSION["success"] . "</p>\n");
  unset($_SESSION["success"]);
}
if (isset($_POST['Username']) && isset($_POST['Password'])) {

  $username = $_POST["Username"];
  $password = $_POST["Password"];

  $sql = "SELECT Password FROM users WHERE Username='$username'";
  $result = $conn->query($sql); // object pointing to the rows

  if ($result->num_rows == 1) {
    $row = $result->fetch_assoc(); // fetches one row from result
    $db_password = $row['Password'];

    if ($password === $db_password) {
      $_SESSION["account"] = $username;
      $_SESSION["success"] = "Logged in successfully.";
      $_SESSION["logged_in"] = true; // allows to let logged in user access the main page
      header("Location: index.php");
      exit();
    } else {
      $_SESSION["error"] = "Incorrect password.";
      header("Location: login.php");
      exit();
    }
  } else {
    $_SESSION["error"] = "Account not found.";
    header("Location: login.php");
    exit;
  }

}

$conn->close();
?>
<html>

<head></head>

<body style="font-family: sans-serif;">

  <h1>Please Log In</h1>

  <form method="post">
    <p>Account: <input type="text" name="Username" required></p>
    <p>Password: <input type="password" name="Password" required></p>
    <p><input type="submit" value="Log In"></p>
  </form>

  <br>
  <p>Or register for a new account<a href="registration.php"> here</a></p>
</body>

</html>