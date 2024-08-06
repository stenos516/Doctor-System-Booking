<?php
// Inizia la sessione
session_start();

// Verifica se l'utente è loggato e se il tipo di utente è corretto
if (isset($_SESSION["user"])) {
    if (empty($_SESSION["user"]) || $_SESSION['usertype'] != 'd') {
        header("Location: login.php");
        exit(); // Ferma l'esecuzione dopo il redirect
    }
} else {
    header("Location: login.php");
    exit(); // Ferma l'esecuzione dopo il redirect
}

// Import database
include("con.php");

// Verifica se è stato passato un ID per l'appuntamento
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$schedule_id = (int)$_GET['id'];

// Recupera i dettagli dell'appuntamento
$schedule_query = $db->query("SELECT * FROM schedule WHERE scheduleid = $schedule_id");
if ($schedule_query->num_rows == 0) {
    header("Location: index.php");
    exit();
}

$schedule = $schedule_query->fetch_assoc();

// Gestione del modulo di aggiornamento
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date = $_POST['date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    // Escape dei dati
    $date = $db->real_escape_string($date);
    $start_time = $db->real_escape_string($start_time);
    $end_time = $db->real_escape_string($end_time);

    // Aggiorna gli orari nel database
    $update_query = "UPDATE schedule SET data='$date', ora_inizio='$start_time', ora_fine='$end_time' WHERE scheduleid=$schedule_id";
    if ($db->query($update_query) === TRUE) {
        $message = "Orario di visita aggiornato con successo.";
    } else {
        $message = "Errore: " . $db->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Appointment</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Inserisci qui il tuo CSS */
        body, html {
            height: 100%;
            width: 100%;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f4f4f4;
        }

        .sidebar {
            width: 30%;
            background-color: #2c3e50;
            color: #ecf0f1;
            padding: 20px;
            position: fixed;
            height: 100%;
            overflow-y: auto;
            border-right: 5px solid #bdc3c7;
        }

        .profile-container {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .profile-image {
            width: 30%;
            height: auto;
            border-radius: 20%;
            margin-right: 10px;
        }

        .profile-name {
            font-size: 18px;
            font-weight: bold;
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }

        .sidebar ul li {
            margin: 20px 0;
        }

        .sidebar ul li a {
            color: #ecf0f1;
            text-decoration: none;
            font-size: 18px;
            display: flex;
            align-items: center;
            padding: 10px;
            transition: color 0.3s;
        }

        .sidebar ul li a i {
            margin-right: 10px;
            font-size: 20px;
        }

        .sidebar ul li a:hover {
            background-color: #689aba;
            color: #fff;
            text-decoration: none;
        }

        .main-content {
            margin-left: 30%;
            padding: 20px;
            width: 70%;
            position: relative;
        }

        header {
            background-image: url('coversanità.jpg'); /* Sostituisci con il percorso della tua immagine */
            background-size: cover;
            background-position: center;
            color: #fff;
            padding: 20px;
            border-radius: 10px;
            margin: 0;
            width: 100%;
            height: 300px;
            position: relative;
            z-index: 1;
        }

        header h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .datetime {
            font-size: 16px;
        }

        .datetime p {
            margin: 0;
            color: black;
        }

        .appointments, .info {
            margin-bottom: 20px;
        }

        .appointments h2, .info h2 {
            font-size: 20px;
            color: #2c3e50;
        }

        .appointments ul {
            list-style-type: none;
        }

        .appointments ul li {
            margin: 10px 0;
            font-size: 16px;
            color: #34495e;
        }

        .profile {
            margin-left: 10px;
        }

        .profile-subtitle {
            font-size: 14px;
            color: #bdc3c7;
        }

        footer {
            background-color: #bdc3c7;
            color: #2c3e50;
            text-align: center;
            padding: 10px;
            position: fixed;
            width: 100%;
            bottom: 0;
            margin-top: auto;
        }

        /* Stile per il contenitore dell'orario di visita */
        .booking-container {
            background-color: #fff;
            padding: 20px;
            width: 50%;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            margin-top: 20px;
            margin-left: 0;
            margin-right: 0;
        }

        .booking-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .booking-container form label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .booking-container form input[type="date"],
        .booking-container form input[type="time"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .booking-container form input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 4px;
        }

        .booking-container form input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .booking-container .message {
            text-align: center;
            margin: 10px 0;
            color: green;
        }

        .booking-container .error {
            text-align: center;
            margin: 10px 0;
            color: red;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="profile-container"> 
      <!--   <img src="bdb4754f1a012bfc00936ad081fe9802.jpg" class="profile-image" alt="logo" width="30%" height="20%">
            <p class="profile"><?php echo substr($username, 0, 13); ?>..</p>
            <p class="profile-subtitle"><?php echo substr($useremail, 0, 22); ?></p> -->
        </div>
        <ul>
            <li><a href="index.php"><i class="fas fa-user"></i> Back</a></li>
         <!--   <li><a href="#appointments"><i class="fas fa-calendar-check"></i> My data</a></li>  -->
        </ul>
   <!--     <a href="logout.php" class="logout-btn">Logout</a> -->
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <header>
            <h1>Edit Appointment</h1>
            <div class="datetime">
                <p><?php echo date('l, F j, Y'); ?></p>
                <p><?php echo date('h:i:s A'); ?></p>
            </div>
        </header>

        <!-- Modulo per modificare orari di visita -->
        <div class="booking-container">
            <h2>Edit Appointment</h2>
            <?php if (isset($message)) { echo "<p class='message'>$message</p>"; } ?>
            <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
            <form action="" method="post">
                <label for="date">Data:</label>
                <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($schedule['data']); ?>" required>

                <label for="start_time">Ora di Inizio:</label>
                <input type="time" id="start_time" name="start_time" value="<?php echo htmlspecialchars($schedule['ora_inizio']); ?>" required>

                <label for="end_time">Ora di Fine:</label>
                <input type="time" id="end_time" name="end_time" value="<?php echo htmlspecialchars($schedule['ora_fine']); ?>" required>

                <input type="submit" value="Update Appointment">
            </form>
        </div>
    </main>
    <div class="footer">
        <!-- Footer -->
        <footer>
            <p>&copy; 2024 Medical Dashboard. All rights reserved.</p>
        </footer>
</body>
</html>
