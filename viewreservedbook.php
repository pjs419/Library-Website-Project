<?php
include "header.php";
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title></title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="">
</head>

<body>
  <h1>These are all the books you have reserved</h1>

  <script src="" async defer></script>
</body>


<?php
$username = $_SESSION["account"];
$sql = "SELECT ISBN, BookTitle, ReservedDate FROM reservedbooks 
JOIN books USING (ISBN) 
where Username='$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  echo "<table border='4'";
  echo "<tr><td>";
  echo "ISBN";
  echo "<td>";
  echo "Book Title";
  echo "<td>";
  echo "Reserved Date";
  echo "<td>";
  echo "Remove Reservation?";
  while ($row = $result->fetch_assoc()) {
    echo "<tr><td>";
    echo (htmlentities($row["ISBN"]));
    echo "<td>";
    echo (htmlentities($row["BookTitle"]));
    echo "<td>";
    echo (htmlentities($row["ReservedDate"]));
    echo "<td>";
    echo ('<a href="deletereserved.php?id=' . htmlentities($row["ISBN"]) . '">Remove</a>');
  }
} else {
  echo "0 results";
}
?>

</html>