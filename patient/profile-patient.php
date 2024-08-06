<?php
session_start();
include("../con.php");

if (!isset($_SESSION['user']) || $_SESSION['usertype'] !== 'p') {
    header('location: login.php');
    exit();
}

$email = $_SESSION['user'];

$error = '<label for="promter" class="form-label">&nbsp;</label>';

// Fetch current user data
$result = $db->query("SELECT * FROM patient WHERE pemail='$email'");
if ($result->num_rows == 1) {
    $patient = $result->fetch_assoc();
} else {
    echo "Error fetching user data";
    exit();
}

if ($_POST) {
    $firstName = $_POST['pfname'];
    $lastName = $_POST['plname'];
    $password = $_POST['ppassword'];
    $address = $_POST['paddress'];
    $nic = $_POST['pnic'];
    $tel = $_POST['ptel'];
    $birth = $_POST['pdob'];

    $name = $firstName . ' ' . $lastName;

    // Update patient table
    $updatePatient = $db->query("UPDATE patient SET pname='$name', ppassword='$password', paddress='$address', pnic='$nic', ptel='$tel', pdob='$birth' WHERE pemail='$email'");

    if ($updatePatient) {
        $error = '<label for="promter" class="form-label" style="color:green;text-align:center;">Profile updated successfully.</label>';
    } else {
        $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Error updating profile. Please try again.</label>';
    }
}

list($firstName, $lastName) = explode(' ', $patient['pname'], 2);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <title>Profile</title>
</head>
<body>

<h1 class="Title"></h1>
<div class="login-box">
    <h2>Profile</h2>
    
    <form method="post">
        <div class="user-box">
            <p>Email</p>
            <input type="text" name="pemail" value="<?php echo $patient['pemail']; ?>" readonly>
            <label></label>
        </div>
        <div class="user-box">
            <input type="text" name="pfname" value="<?php echo $firstName; ?>" required="">
            <label>First Name</label>
        </div>
        <div class="user-box">
            <input type="text" name="plname" value="<?php echo $lastName; ?>" required="">
            <label>Last Name</label>
        </div>
        <div class="user-box">
            <input type="password" name="ppassword" value="<?php echo $patient['ppassword']; ?>" required="">
            <label>Password</label>
        </div>
        <div class="user-box">
            <input type="text" name="paddress" value="<?php echo $patient['paddress']; ?>" required="">
            <label>Address</label>
        </div>
        <div class="user-box">
            <input type="text" name="pnic" value="<?php echo $patient['pnic']; ?>" required="">
            <label>NIC</label>
        </div>
        <div class="user-box">
            <input type="text" name="ptel" value="<?php echo $patient['ptel']; ?>" required="">
            <label>Phone</label>
        </div>
        <div class="user-box">
            <input type="date" name="pdob" value="<?php echo $patient['pdob']; ?>" required="">
            <label class="data">Date of Birth</label>
        </div>
        <div>
            <?php echo $error ?>
        </div>
        <div class="form-group">
            <input type="submit" value="Update Profile" class="login-btn btn-primary">
            <br>
            <a href="index.php">Back to Dashboard</a>
        </div>
    </form>
</div>

<!-- Footer -->
<footer>
    <p>&copy; 2024 Medical Dashboard. All rights reserved.</p>
</footer>
</body>
</html>
