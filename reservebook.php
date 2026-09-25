<?php

include "auth.php";
include "database.php";
include "header.php";

// pagination setup
$limit = 5; // number of books per page
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
if ($page < 1)
  $page = 1;
$offset = ($page - 1) * $limit;

if (isset($_POST['reserve']) && isset($_POST['id'])) {

  // get all the details
  $id = $_POST["id"];
  $Uname = $_SESSION["account"];
  $date = date('Y/m/d H:i:s', time());
  $sql = "INSERT INTO reservedbooks (ISBN, Username, ReservedDate)
  VALUES ('$id','$Uname','$date')"; // sql query to add all details to reservedbook table

  if ($conn->query($sql)) {

    // query to update the books status from not reserved to reserved (N to Y)
    $updateSql = "UPDATE books SET Reserved='Y' WHERE ISBN='$id'";

    if ($conn->query($updateSql)) {
      echo 'Success - <a href="viewreservedbook.php">Continue...</a>'; // sends user to next page to see their reservation
    }

  } else {
    $_SESSION["error"] = "Error reserving book";
    header("Location: reservebook.php");
    exit;
  }

  exit; // stop execution after handling the reserving
}

?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title></title>
  <link rel="stylesheet" href="style.css">
</head>

<body>

  <?php

  // error and success messages
  if (isset($_SESSION["error"])) {
    echo ('<p style="color:red">Error: ' . $_SESSION["error"] . "</p>");
    unset($_SESSION["error"]);
  }

  if (isset($_SESSION["success"])) {
    echo ('<p style="color:green">' . $_SESSION["success"] . "</p>\n");
    unset($_SESSION["success"]);
  }

  ?>

  <?php

  if (isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);

    // book details for confirmation
    $sql = "SELECT ISBN, BookTitle, Author FROM books
    WHERE ISBN='$id'";
    $result = $conn->query($sql);

    if ($row = $result->fetch_assoc()) {
      echo "<p>Confirm reservation of " . htmlentities($row["BookTitle"]) . "</p>\n";

      // form submit back to same page (the code up top)
      echo '<form method="post">
        <input type="hidden" name="id" value="' . htmlentities($row["ISBN"]) . '">
        <input type="submit" name="reserve" value="Yes, reserve book">
        <a href="viewreservedbook.php">Cancel</a>
      </form>';
    } else {
      echo "Book not found";
    }

  } else {
    // count total books
    $countSql = "SELECT COUNT(*) AS total FROM books WHERE Reserved='N'";
    $countResult = $conn->query($countSql);
    $totalBooks = $countResult->fetch_assoc()['total'];
    $totalPages = ceil($totalBooks / $limit);

    // fetch books with LIMIT & OFFSET
    $sql = "SELECT ISBN, BookTitle FROM books WHERE Reserved='N' LIMIT $limit OFFSET $offset";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      echo "<h3>Reservable Books</h3>";
      echo "<table border='4'>";
      echo "<tr>";
      echo "<th>ISBN</th>";
      echo "<th>Book Title</th>";
      echo "<th>Action</th>";
      echo "</tr>";
      while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlentities($row["ISBN"]) . "</td>";
        echo "<td>" . htmlentities($row["BookTitle"]) . "</td>";
        echo "<td><a href='?id=" . htmlentities($row["ISBN"]) . "'>Reserve</a></td>";
        echo "</tr>";
      }
      echo "</table>";

      echo "<div style='margin-top:10px;'>";

      // pagination links
      if ($page > 1) {
        echo "<a href='?page=" . ($page - 1) . "'>&laquo; Previous</a> ";
      }

      if ($page < $totalPages) {
        echo "<a href='?page=" . ($page + 1) . "'>Next &raquo;</a>";
      }
      echo "</div>";
    } else {
      echo "No reservable books found.";
    }

  }

  ?>

</body>

</html>