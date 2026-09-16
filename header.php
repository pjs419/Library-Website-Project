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
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Library Book System</title>
</head>

<body>
  <header>
    <?php if (isset($_SESSION["logged_in"])) { ?> <!-- if they ARE logged in-->
      <h1>Library System</h1>
      <nav>
        <a href="index.php">Main Page!</a>
        <a href="searchbook.php">Search for a book!</a>
        <a href="reservebook.php">Reserve a book!</a>
        <a href="viewreservedbook.php">View all your reserved books!</a>
        <a href="logout.php">Logout here</a>

        <hr>
      </nav>
    <?php } else {
      header("Location: login.php"); // will not let unlogged user access anything in each page (not login or reg)
    }
    ?>
  </header>
</body>

</html>