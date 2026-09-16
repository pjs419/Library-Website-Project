<?php
include "header.php";
?>
<!DOCTYPE html PUBLIC>
<html>

<body>

  <?php
  if (isset($_POST['delete']) && isset($_POST['id'])) {
    $id = $conn->real_escape_string($_POST['id']);
    $sql = "DELETE FROM reservedbooks WHERE ISBN='$id'";
    $conn->query($sql);
    echo 'Success - <a href="viewreservedbook.php">Continue...</a>';
    exit();
  }

  $id = $conn->real_escape_string($_GET['id']);
  $sql = "SELECT ISBN, BookTitle FROM reservedbooks 
  JOIN books 
  USING (ISBN)
  WHERE ISBN='$id'"
  ;
  $result = $conn->query($sql);
  $row = $result->fetch_assoc();
  echo "<p>Confirm: Deleting " . htmlentities($row["BookTitle"]) . "</p>\n";
  echo '<form method="post">
        <input type="hidden" name="id" value="' . htmlentities($row["ISBN"]) . '">
        <input type="submit" name="delete" value="Yes, Delete">
        <a href="viewreservedbook.php">Cancel</a>
      </form>';



  ?>
</body>

</html>