<?php 
include "auth.php"; 
include "header.php"; 

// shows success when first logged in 
if (isset($_SESSION["success"])) { 
  echo ('<p style="color:green">' . $_SESSION["success"] . "</p>\n"); 
  unset($_SESSION["success"]); 
} 
?> 

<!DOCTYPE html> 

<head> 
  <meta charset="utf-8"> 
  <title>Main Page</title> 
  <link rel="stylesheet" href="style.css"> 
</head> 

<body> 

  <div class="container"> 

    <div class="actions"> 
      <!--main page with links to pages--> 
      <h3>This is the main page of the library system. You can:</h3> 
      <p>Search for a book <a href="searchbook.php">here!</a></p> 
      <p>Reserve a book <a href="reservebook.php">here!</a></p> 
      <p>View all your reserved books <a href="viewreservedbook.php">here!</a></p> 
    </div> 

  </div> 
 
  <?php include "footer.php"; ?> 

</body> 

<html> 