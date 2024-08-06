<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css\login.css">
    <title>Signup</title>
</head>
<body>
<link rel="stylesheet" href="signupstyle.css">
<?php
session_start();
include("con.php");

$error = '<label for="promter" class="form-label"></label>';

if ($_POST) {
    $email = $_POST['pemail'];
    $firstName = $_POST['pfname'];
    $lastName = $_POST['plname'];
    $password = $_POST['ppassword'];
    $address = $_POST['paddress'];
    $nic = $_POST['pnic'];
    $tel = $_POST['ptel'];
    $birth = $_POST['pdob'];

    $name = $firstName . ' ' . $lastName; // Concatenate first name and last name

    // Check if the email already exists
    $result = $db->query("SELECT * FROM webuser WHERE email='$email'");
    if ($result->num_rows > 0) {
        $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Email already registered</label>';
    } else {
        // Insert into patient table
        $insertPatient = $db->query("INSERT INTO patient (pemail, pname, ppassword, paddress, pnic, ptel, pdob) VALUES ('$email', '$name', '$password', '$address', '$nic', '$tel', '$birth')");
        
        // Insert into webuser table
        $insertWebUser = $db->query("INSERT INTO webuser (email, usertype) VALUES ('$email', 'p')");

        if ($insertPatient && $insertWebUser) {
            $_SESSION['user'] = $email;
            $_SESSION['usertype'] = 'p';
            header('location: patient/index.php');
        } else {
            $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Error creating account. Please try again.</label>';
        }
    }
} else {
    $error = '<label for="promter" class="form-label">&nbsp;</label>';
}
?>

<h1 class="Title">Doctor System booking</h1>
<div class="login-box">
    <h2>Signup</h2>
    
    <form method="post">
        <div class="user-box">
            <input type="text" name="pemail" required="">
            <label>Email</label>
        </div>
        <div class="user-box">
            <input type="text" name="pfname" required="">
            <label>First Name</label>
        </div>
        <div class="user-box">
            <input type="text" name="plname" required="">
            <label>Last Name</label>
        </div>
        <div class="user-box">
            <input type="password" name="ppassword" required="">
            <label>Password</label>
        </div>
        <div class="user-box">
            <input type="text" name="paddress" required="">
            <label>Address</label>
        </div>
        <div class="user-box">
            <input type="text" name="pnic" required="">
            <label>NIC</label>
        </div>
        <div class="user-box">
            <input type="text" name="ptel" required="">
            <label>Phone</label>
        </div>
        
        <p class="white-text">Birthday</p>
        <div class="user-box">
            <input type="date" name="pdob" required="">
           
            <label class="data">Date of Birth></label>
        </div>
        <div>
            <?php echo $error ?>
        </div>
        <div class="form-group">
            <input type="submit" value="Signup" class="login-btn btn-primary">
            <br>
            <a href="login.php">Already have an account?</a>
        </div>
    </form>
</div>

<!-- Footer -->
<footer>
    <p>&copy; 2024 Medical Dashboard. All rights reserved.</p>
</footer>
</body>
</html>
