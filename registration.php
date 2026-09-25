<?php 
session_start(); 
require_once "database.php"; 

// checks if user has submitted a value for each. Runs if theres data for each 
if ( 
  isset($_POST['Username']) && isset($_POST['Password']) && isset($_POST['FirstName']) 
  && isset($_POST['Surname']) && isset($_POST['AddressLine1']) && isset($_POST['AddressLine2']) 
  && isset($_POST['City']) && isset($_POST['Telephone']) && isset($_POST['Mobile']) 
) { 
  // assign data from users input 
  $Uname = $_POST['Username']; 
  $Pword = $_POST['Password']; 
  $Fname = $_POST['FirstName']; 
  $Sname = $_POST['Surname']; 
  $Adr1 = $_POST['AddressLine1']; 
  $Adr2 = $_POST['AddressLine2']; 
  $City = $_POST['City']; 
  $Tel = $_POST['Telephone']; 
  $Mble = $_POST['Mobile']; 

  // empty array to flag for errors if it contains anything 
  $error = []; 

  if (strlen($Pword) !== 6) { 
    $error[] = "Password must be 6 characters"; 
  } 

  // check if the passwords match 
  $PwordConfirm = $_POST['CheckPassword']; 
  if ($Pword !== $PwordConfirm) { 
    $error[] = "Password must match"; 
    // appends msg to the array 
  } 

  // check if username already exists 
  $checkSql = "SELECT Username FROM users WHERE Username='$Uname'"; 
  $result = $conn->query($checkSql); 
  if ($result->num_rows > 0) { 
    $error[] = "Username is already taken"; 
  } 

  if (strlen($Mble) !== 10) { 
    $error[] = "Mobile number must be 10 digits"; 
  } 

  // runs if $error is empty 
  if (empty($error)) { 
    $hashedPassword = password_hash($Pword, PASSWORD_DEFAULT); // hashing the password 
    $sql = "INSERT INTO users (Username, Password, FirstName, Surname, AddressLine1, AddressLine2, City, Telephone, Mobile)  
    VALUES ('$Uname', '$hashedPassword', '$Fname', '$Sname', '$Adr1', '$Adr2', '$City', '$Tel', '$Mble')"; 

    // check equality of values and data type 
    if ($conn->query($sql) === TRUE) { 
      $_SESSION["success"] = "New registration created successfully. "; // stores success 
      header("Location: index.php"); 
      exit; 
    } else { 
      $_SESSION["error"] = "Error inserting data to db"; 
      header("Location: registration.php"); 
      exit; 
    } 
  } else { 
    // else when empty has values 
    $_SESSION["error"] = $error; //Stores all errors 
    header("Location: registration.php"); //Goes back 
    exit; 
  } 
} 

?> 

<!DOCTYPE html> 
<html> 

<head> 
  <meta charset="utf-8"> 
  <title> Registration page</title> 
  <link rel="stylesheet" href="style.css"> 
</head> 

<body> 

  <div class="container"> 

    <h1>Register for an account</h1> 

    <div class="messages"> 
      <?php 
      // error message  
      if (isset($_SESSION["error"])) { 
        foreach ($_SESSION["error"] as $err) { 
          echo ('<p style="color:red">Error:' . $err . "</p>\n"); 
        } 
        unset($_SESSION["error"]); 
      } 

      // success message 
      if (isset($_SESSION["success"])) { 
        echo ('<p style="color:green">' . $_SESSION["success"] . "</p>\n"); 
        unset($_SESSION["success"]); 
      } 
      ?> 
    </div> 

    <!-- Form for user to register an account to the db--> 
    <div class="registration-form"> 

      <form method="post"> 
        <div class="form-group"> 
          <p>Username: <input type="text" name="Username" required></p> 
        </div> 
        <div class="form-group"> 
          <p>Password (6 characters): <input type="password" name="Password" pattern="\d{6}" required></p> 
        </div> 
        <div class="form-group"> 
          <p>Confirm your password: <input type="password" name="CheckPassword" required></p> 
        </div> 
        <div class="form-group"> 
          <p>First Name: <input type="text" name="FirstName" required></p> 
        </div> 
        <div class="form-group"> 
          <p>Last Name: <input type="text" name="Surname" required></p> 
        </div> 
        <div class="form-group"> 
          <p>Address line 1: <input type="text" name="AddressLine1" required></p> 
        </div> 
        <div class="form-group"> 
          <p>Address line 2: <input type="text" name="AddressLine2" required></p> 
        </div> 
        <div class="form-group"> 
          <p>City: <input type="text" name="City" required></p> 
        </div> 
        <div class="form-group"> 
          <p>Telephone number: <input type="tel" name="Telephone" required></p> 
        </div> 
        <div class="form-group"> 
          <p>Mobile number (10 characters): <input type="tel" name="Mobile" pattern="\d{10}" required></p> 
        </div> 
        <div class="form-group"> 
          <p><input type="submit" value="Register" /></p> 
        </div> 
      </form> 

      <br> 

      <p>Or login<a href="login.php"> here!</a></p> 

    </div><!--registration-form--> 
  </div><!--container--> 
</body> 

</html> 