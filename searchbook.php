<?php 
include "auth.php"; 
include "database.php"; 
include "header.php"; 

// categories for dropdown 
$cateQuery = "SELECT CategoryID, CategoryDescription FROM category"; 
$categories = $conn->query($cateQuery); 

// storing conditions for search 
$conditions = []; 

// base query before adding the condition for the search 
$sql = "SELECT * FROM books JOIN category USING (CategoryID)"; 

// adds the condition if an input is read from the form 
if (!empty($_GET['title'])) { 
  $title = $conn->real_escape_string($_GET['title']); 
  $conditions[] = "BookTitle LIKE '%$title%'"; // allows for partial search 
} 

if (!empty($_GET['author'])) { 
  $author = $conn->real_escape_string($_GET['author']); 
  $conditions[] = "Author LIKE '%$author%'"; 
} 

if (!empty($_GET['category'])) { 
  $category = $conn->real_escape_string($_GET['category']); 
  $conditions[] = "CategoryID LIKE '%$category%'"; 
} 

// appends the WHERE with the condition (implode joins elements with a separator) 
if (!empty($conditions)) { 
  $sql .= " WHERE " . implode(" AND ", $conditions); 
} 

// limit results to max 5 pieces of data 
$sql .= " LIMIT 5"; 
$result = $conn->query($sql); 
?> 

<!DOCTYPE html> 
<html> 

<head> 
  <meta charset="utf-8"> 
  <title>Search Book Page</title> 
  <link rel="stylesheet" href="style.css"> 
</head> 

<body> 
  <!-- form to get title, author or category--> 
  <form method="GET"> 
    <input type="text" name="title" placeholder="Book Title"> 
    <input type="text" name="author" placeholder="Author"> 

    <!-- dropdown menu for categories--> 
    <select name="category"> 
      <option value="">--All Categories--</option> 
      <?php while ($cat = $categories->fetch_assoc()): ?> 
        <option value="<?= $cat['CategoryID']; ?>"><?= $cat['CategoryDescription']; ?></option> 
      <?php endwhile; ?> 
    </select> 

    <button type="submit">Search</button> 
  </form> 

  <?php 
  // display results 
  if ($result->num_rows > 0) { 
    echo "<h3>Search Results (max 5):</h3>"; 
    echo "<table border='4'>"; 
    echo "<tr>"; 
    echo "<th>ISBN</th>"; 
    echo "<th>Book Title</th>"; 
    echo "<th>Author</th>"; 
    echo "<th>Edition</th>"; 
    echo "<th>Year</th>"; 
    echo "<th>Category Description</th>"; 
    echo "<th>Reserve</th>"; 

    while ($row = $result->fetch_assoc()) { 
      echo "<tr><td>"; 
      echo (htmlentities($row["ISBN"])); 
      echo "</td><td>"; 
      echo (htmlentities($row["BookTitle"])); 
      echo "</td><td>"; 
      echo (htmlentities($row["Author"])); 
      echo "</td><td>"; 
      echo (htmlentities($row["Edition"])); 
      echo "</td><td>"; 
      echo (htmlentities($row["Year"])); 
      echo "</td><td>"; 
      echo (htmlentities($row["CategoryDescription"])); 
      echo "</td><td>"; 

      // show reserve link if book has not been reserved 
      if ($row["Reserved"] == "N") { 
        echo ('<a href="reservebook.php?id=' . htmlentities($row["ISBN"]) . '">Reserve</a>'); 
      } else { 
        echo "Not reservable"; 
      } 
    } 
  } else { 
    echo "<h3>No results found.</h3>"; 
  } 
  ?> 

</body> 

</html> 