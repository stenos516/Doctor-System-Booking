<?php
session_start();
include("con.php");

if (!isset($_SESSION['user']) || $_SESSION['usertype'] !== 'd') {
    header('Location: login.php');
    exit();
}

$email = $_SESSION['user'];

// Recupera i dati del dottore dal database
$query = $db->query("SELECT * FROM doctor WHERE docemail='$email'");
$doctor = $query->fetch_assoc();

// Inizializza variabili di errore
$error = '<label for="promter" class="form-label"></label>';

// Gestisci il modulo di aggiornamento
if ($_POST) {
    $email = $_POST['docemail'];
    $name = $_POST['docname'];
    $password = $_POST['docpassword'];
    $nic = $_POST['docnic'];
    $tel = $_POST['doctel'];

    // Aggiorna i dati del dottore nel database
    $updateDoctor = $db->query("UPDATE doctor SET docname='$name', docname='$name', docpassword='$password', docnic='$nic', doctel='$tel' WHERE docemail='$email'");

    if ($updateDoctor) {
        $success = '<label for="promter" class="form-label" style="color:rgb(62, 255, 62);text-align:center;">Profile updated successfully</label>';
        // Ricarica i dati aggiornati
        $query = $db->query("SELECT * FROM doctor WHERE docname='$name'");
        $doctor = $query->fetch_assoc();
    } else {
        $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Error updating profile. Please try again.</label>';
    }
} else {
    $error = '<label for="promter" class="form-label">&nbsp;</label>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <title>Doctor Profile</title>
</head>
<body>
<h1 class="Title"></h1>
<div class="login-box">
    <h2>My Profile</h2>
    
    <form method="post">
        <div class="user-box">
        <p class="white-text">Email</p>
            <input type="text" name="docemail" value="<?php echo $doctor['docemail']; ?>" readonly>
            <label></label>
        </div>
        <div class="user-box">
            <input type="text" name="docname" value="<?php echo $doctor['docname']; ?>" required="">
            <label>Name</label>
        </div>
        <div class="user-box">
            <input type="password" name="docpassword" value="<?php echo $doctor['docpassword']; ?>" required="">
            <label>Password</label>
        </div>
        <div class="user-box">
            <input type="text" name="docnic" value="<?php echo $doctor['docnic']; ?>" required="">
            <label>NIC</label>
        </div>
        <div class="user-box">
            <input type="text" name="doctel" value="<?php echo $doctor['doctel']; ?>" required="">
            <label>Phone</label>
        </div>
        <div>
            <?php echo $error ?>
            <?php if (isset($success)) echo $success; ?>
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
