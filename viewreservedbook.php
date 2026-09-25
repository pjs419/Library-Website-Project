<?php

include "auth.php";
include "database.php";
include "header.php";

// check if the delete form has been submitted
if (isset($_POST['delete']) && isset($_POST['id'])) {

  $id = $conn->real_escape_string($_POST['id']);
  $sql = "DELETE FROM reservedbooks WHERE ISBN='$id'"; // sql query to delete the reserved book with ISBN

  if ($conn->query($sql)) {

    $updateSql = "UPDATE books SET Reserved='N' WHERE ISBN='$id'";

    if ($conn->query($updateSql)) {
      $_SESSION["success"] = "Successful deletion of reservation.";
      header("location: viewreservedbook.php");
      exit;
    }

  } else {
    $_SESSION["error"] = "Error deleting book.";
    header("Location: viewreservedbook.php");
    exit;
  }

  exit; // stop execution after handling the deletion
}

?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>View Logged Users Reserved</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <h3>These are all the books you have reserved</h3>
  <?php

  // have these in here bc i dont want header to be seen
  if (isset($_SESSION["error"])) {
    echo ('<p style="color:red">Error: ' . $_SESSION["error"] . "</p>");
    unset($_SESSION["error"]);
  }

  if (isset($_SESSION["success"])) {
    echo ('<p style="color:green">' . $_SESSION["success"] . "</p>\n");
    unset($_SESSION["success"]);
  }

  ?>

  <div class="confirmation">

    <?php

    // check if user pressed remove using id parameter (which is ISBN)
    if (isset($_GET['id'])) {
      $id = $conn->real_escape_string($_GET['id']);

      // book details for confirmation
      $sql = "SELECT ISBN, BookTitle FROM reservedbooks
        JOIN books
        USING (ISBN)
        WHERE ISBN='$id'";
      $result = $conn->query($sql);

      // if book is found confirmation form will show before deleting
      if ($row = $result->fetch_assoc()) {
        echo "<p>Confirm unreserve: " . htmlentities($row["BookTitle"]) . ".</p>\n";

        // form submit back to same page (the code up top)
        echo '<form method="post">
        <input type="hidden" name="id" value="' . htmlentities($row["ISBN"]) . '">
        <input type="submit" name="delete" value="Yes, Remove">
        <a href="viewreservedbook.php">Cancel</a>
      </form>';
      } else {
        $_SESSION['error'] = "Error with form";
        header("Location: viewreservedbook.php");
        exit;
      }
    }

    ?>

  </div> <!--confirmation-->

  <?php

  if (!isset($_GET['id'])) {
    // displays all user's reserved books
    $username = $_SESSION["account"];
    $sql = "SELECT ISBN, BookTitle, ReservedDate FROM reservedbooks
    JOIN books USING (ISBN)
    where Username='$username'"; // uses the sessions user for their specific details
    $result = $conn->query($sql);

    // check for results and creates table if found
    if ($result->num_rows > 0) {
      echo "<table border='4'>";
      echo "<tr>";
      echo "<th>ISBN</th>";
      echo "<th>Book Title</th>";
      echo "<th>Reserved Date (Y-M-D)</th>";
      echo "<th>Remove Reservation?</th>";

      // loop through reservedbook and display elements in table
      while ($row = $result->fetch_assoc()) {
        echo "<tr><td>";
        echo (htmlentities($row["ISBN"]));
        echo "</td><td>";
        echo (htmlentities($row["BookTitle"]));
        echo "</td><td>";
        echo (htmlentities($row["ReservedDate"]));
        echo "</td><td>";

        // link to remove reservation
        echo ('<a href="?id=' . htmlentities($row["ISBN"]) . '">Remove</a>');
      }
    } else {
      echo "0 results";
    }
  }

  ?>

</body>

</html>