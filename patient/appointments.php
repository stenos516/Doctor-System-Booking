<?php
// Avvia la sessione e gestisci l'accesso utente
session_start();

// Verifica se l'utente è loggato e se il tipo di utente è corretto
if (isset($_SESSION["user"])) {
    if (empty($_SESSION["user"]) || $_SESSION['usertype'] != 'p') {
        header("Location: login.php");
        exit(); // Ferma l'esecuzione dopo il redirect
    } else {
        $useremail = $_SESSION["user"]; // Inizializza $useremail con il valore della sessione
    }
} else {
    header("Location: login.php");
    exit(); // Ferma l'esecuzione dopo il redirect
}

// Connessione al database
include("../con.php");

$userrow = $db->query("SELECT * FROM patient WHERE pemail='$useremail'");
$userfetch = $userrow->fetch_assoc();
$userid = $userfetch["pid"];
$username = $userfetch["pname"];

// Messaggio di conferma
$message = "";

// Se il modulo di cancellazione è stato inviato, cancella la prenotazione
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
    $appointmentid = $_POST['appointmentid'];

    // Assumi che ci sia una tabella `appointments` dove inserisci le prenotazioni
    $query = "DELETE FROM appointment WHERE appoid='$appointmentid' AND pemail='$useremail'";

    if ($db->query($query) === TRUE) {
        $message = "Your reservation has been cancelled!";
    } else {
        $message = "Errore: " . $query . "<br>" . $db->error;
    }
}

// Ottieni le prenotazioni dal database
$query = "
    SELECT appointment.*, doctor.docname 
    FROM appointment 
    JOIN doctor ON appointment.docname = doctor.docname 
    WHERE pemail='$useremail'
     ORDER BY appointment.data DESC, appointment.ora_inizio DESC";
$result = $db->query($query);
$appointments = [];
while ($row = $result->fetch_assoc()) {
    $appointments[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Le Tue Prenotazioni</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">  
    <style>
            /* Inserisci qui il tuo CSS */
        
    /* Reset default margin and padding */
* {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

body, html {
            height: 100%;
            width: 100%;
        }

        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #f4f4f4; /* Colore di sfondo generale per la pagina */
        }

        .sidebar {
            width: 30%;
            background-color: #2c3e50;
            color: #ecf0f1;
            padding: 5px;
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
            position: relative; /* Assicurati che il posizionamento sia relativo */
        }

        header {
            background-image: url('coversanità.jpg'); /* Sostituisci con il percorso della tua immagine */
            background-size: cover; /* Adatta l'immagine alle dimensioni dell'elemento */
            background-position: center; /* Centra l'immagine */
            color: black; /* Colore del testo per contrastare con lo sfondo */
            padding: 20px; /* Padding per lo spazio interno */
            border-radius: 10px;
            margin: 0;
            width: 100%; /* Larghezza completa */
            height: 300px; /* Altezza fissa per l'header */
            position: relative; /* Posizionamento relativo */
            z-index: 1; /* Assicurati che l'header sia sopra gli altri elementi */
        }

        header h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .datetime {
            font-size: 16px;
        }

        .datetime p {
            margin: 0; /* Rimuove margini extra */
            color: black; /* Colore del testo per contrastare con lo sfondo */
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
            padding: 30px;
            position: relative;
            width: 100%;
            bottom: 0;
            margin-top: auto;
        }

        .message {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
            border-radius: 4px;
        }

        .appointments-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .appointments-table th, .appointments-table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .appointments-table th {
            background-color: #f8f8f8;
            font-weight: bold;
        }

        .appointment-row {
            background-color: #fff;
        }

        .appointment-row:hover {
            background-color: #f1f1f1;
        }

        .cancel-button {
            background-color: #e74c3c; /* Rosso */
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .cancel-button:hover {
            background-color: #c0392b; /* Rosso più scuro */
        }

        .appointments h2 {
            margin-top: 10px;
            

        }

    </style>

    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Funzione per far scomparire il messaggio dopo 5 secondi
            function hideMessage() {
                var messageDiv = document.getElementById('message');
                if (messageDiv) {
                    setTimeout(function() {
                        messageDiv.style.display = 'none';
                    }, 5000); // 5000 millisecondi = 5 secondi
                }
            }
            hideMessage(); // Richiama la funzione per nascondere il messaggio
        });
    </script>

</head>
<body>
  <!-- Sidebar -->
  <aside class="sidebar">
        <div class="profile-container"> 
        <i class="bi bi-calendar-heart-fill" style="font-size: 6rem; color: #66c4ff; margin-left: 10px; margin-top: 20px;"></i>
           <!-- <img src="coversanità.jpg" class="profile-image" alt="logo"> -->
            <p class="profile">Name : <?php echo substr($username, 0, 13); ?> / Email :</p>
            <p class="profile-subtitle"><?php echo substr($useremail, 0, 22); ?></p>
        </div>
        <ul>
        <!--    <li><a href="#patients"><i class="fas fa-user"></i> Patients</a></li>  -->
            <li><a href="index.php"><i class="fas fa-calendar-check"></i> Dashboard</a></li>
        </ul>
   <!--     <a href="logout.php" class="logout-btn">Logout</a>   -->
    </aside>

<!-- Main Content -->
<main class="main-content">
    <header>
        <h1>Your Bookings</h1>
        <div class="datetime">
            <p><?php echo date('l, F j, Y'); ?></p>
            <p><?php echo date('h:i A'); ?></p>
        </div>
    </header>

    <?php if ($message): ?>
        <div class="message">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <div class="appointments">
        <h2>Reservation List</h2>
        <table class="appointments-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Hour</th>
                    <th>Doctor</th>
                    <th>Specialization</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($appointments as $appointment): ?>
                    <tr class="appointment-row">
                        <td><?php echo $appointment['appoid']; ?></td>
                        <td><?php echo $appointment['data']; ?></td>
                        <td><?php echo $appointment['ora_inizio']; ?></td>
                        <td><?php echo $appointment['docname']; ?></td>
                        <td><?php echo $appointment['specialization']; ?></td>
                        <td>
                            <form method="POST" action="">
                            <input type="hidden" name="specialization" value="<?php echo $appointment['specialization']; ?>">
                                <input type="hidden" name="appointmentid" value="<?php echo $appointment['appoid']; ?>">
                                <button type="submit" name="delete" class="cancel-button">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

<footer>
    &copy;  2024 Medical Dashboard. All rights reserved
</footer>
</body>
</html>
