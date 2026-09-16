<?php
session_start();
require_once "database.php";

//Error message 
if (isset($_SESSION["error"])) {
  foreach ($_SESSION["error"] as $err) {
    echo ('<p style="color:red">Error:' . $err . "</p>\n");
  }
  unset($_SESSION["error"]);
}
//Success message
if (isset($_SESSION["success"])) {
  echo ('<p style="color:green">' . $_SESSION["success"] . "</p>\n");
  unset($_SESSION["success"]);
}

//Checks if user has submitted a value for each. Runs if data for each
if (
  isset($_POST['Username']) && isset($_POST['Password']) && isset($_POST['FirstName'])
  && isset($_POST['Surname']) && isset($_POST['AddressLine1']) && isset($_POST['AddressLine2'])
  && isset($_POST['City']) && isset($_POST['Telephone']) && isset($_POST['Mobile'])
) {
  //Assign data
  $Uname = $_POST['Username'];
  $Pword = $_POST['Password'];
  $Fname = $_POST['FirstName'];
  $Sname = $_POST['Surname'];
  $Adr1 = $_POST['AddressLine1'];
  $Adr2 = $_POST['AddressLine2'];
  $City = $_POST['City'];
  $Tel = $_POST['Telephone'];
  $Mble = $_POST['Mobile'];

  //Empty array to flag for errors if it contains anything
  $error = [];

  $PwordConfirm = $_POST['CheckPassword'];

  //Check if the passwords match
  if ($Pword !== $PwordConfirm) {
    $error[] = "Password must match";
    //Appends msg to the array
  }

  //Check if username already exists
  $checkSql = "SELECT Username FROM users WHERE Username='$Uname'";
  $result = $conn->query($checkSql);
  if ($result->num_rows > 0) {
    $error[] = "Username is already taken";
  }

  if (empty($error)) {
    $sql = "INSERT INTO users (Username, Password, FirstName, Surname, AddressLine1, AddressLine2, City, Telephone, Mobile) 
    VALUES ('$Uname', '$Pword', '$Fname', '$Sname', '$Adr1', '$Adr2', '$City', '$Tel', '$Mble')";

    if ($conn->query($sql) === TRUE) {
      $_SESSION["success"] = "New registration created successfully. "; //Stores success
      header("Location: index.php");
      exit;
    } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
    }
  } else {
    $_SESSION["error"] = $error; //Stores all errors
    header("Location: registration.php"); //Goes back
    exit;
  }

  $conn->close();

}
?>
<html>

<head>
</head>

<body style="font-family: sans-serif;">
  <h1>Register for an account</h1>
  <!-- Form for user to register an account to the db-->
  <form method="post">
    <p>Username:
      <input type="text" name="Username" required>
    </p>
    <p>Password (Must be greater than 6 characters):
      <input type="password" name="Password" minlength="6" required>
    </p>
    <p>Confirm your password:
      <input type="password" name="CheckPassword" required>
    </p>
    <p>First Name:
      <input type="text" name="FirstName" required>
    </p>
    <p>Last Name:
      <input type="text" name="Surname" required>
    </p>
    <p>Address line 1:
      <input type="text" name="AddressLine1" required>
    </p>
    <p>Address line 2:
      <input type="text" name="AddressLine2" required>
    </p>
    <p>City:
      <input type="text" name="City" required>
    </p>
    <p>Telephone number:
      <input type="int" name="Telephone" required>
    </p>
    <p>Mobile number (Format e.g 8512345678. 10 characters):
      <input type="int" name="Mobile" minlength="10" maxlength="10" required>
    </p>
    <p><input type="submit" value="Register" /></p>
  </form>

</body>

</html>