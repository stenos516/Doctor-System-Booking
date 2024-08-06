<?php
// Inizia la sessione
session_start();

// Verifica se l'utente è loggato e se il tipo di utente è corretto
if (isset($_SESSION["user"])) {
    if (empty($_SESSION["user"]) || $_SESSION['usertype'] != 'd') {
        header("Location: login.php");
        exit(); // Ferma l'esecuzione dopo il redirect
    } else {
        $useremail = $_SESSION["user"]; // Inizializza $useremail con il valore della sessione
    }
} else {
    header("Location: login.php");
    exit(); // Ferma l'esecuzione dopo il redirect
}

// Import database
include("con.php");
$userrow = $db->query("SELECT * FROM doctor WHERE docemail='$useremail'");
$userfetch = $userrow->fetch_assoc();
$userid = $userfetch["docid"];
$username = $userfetch["docname"];
$specialization = $userfetch["specialization"]; 

// Gestione della form submission per impostare orari di visita
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['date'])) {
    $date = $_POST['date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $docname = $_POST['docname'];
    $Specialization = $_POST['specialization'];
    // Escape dei dati
    $date = $db->real_escape_string($date);
    $start_time = $db->real_escape_string($start_time);
    $end_time = $db->real_escape_string($end_time);

    // Inserimento degli orari nel database
    $query = "INSERT INTO schedule (docemail, data, ora_inizio, ora_fine, specialization) VALUES ('$useremail', '$date', '$start_time', '$end_time', '$Specialization')";
    if ($db->query($query) === TRUE) {
        $message = "Appointment created!.";
    } else {
        $message = "Errore: " . $db->error;
    }
}

// Gestione della rimozione di un appuntamento
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $delete_query = "DELETE FROM schedule WHERE scheduleid = $id";
    if ($db->query($delete_query) === TRUE) {
        $message = "The appointment has been removed!.";
    } else {
        $message = "Errore: " . $db->error;
    }
}

// Gestione della modifica di un appuntamento
if (isset($_POST['edit'])) {
    $id = intval($_POST['id']);
    $date = $_POST['date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    
    // Escape dei dati
    $date = $db->real_escape_string($date);
    $start_time = $db->real_escape_string($start_time);
    $end_time = $db->real_escape_string($end_time);
    $Specialization = $db->real_escape_string($Specialization);
    $update_query = "UPDATE schedule SET data='$date', ora_inizio='$start_time', ora_fine='$end_time' WHERE scheduleid=$id";
    if ($db->query($update_query) === TRUE) {
        $message = "Update appointment.";
    } else {
        $message = "Errore: " . $db->error;
    }
}

// Recupera tutti gli appuntamenti del medico
$schedule_query = "SELECT * FROM schedule WHERE docemail='$useremail' ORDER BY schedule.data ASC";
$schedule_result = $db->query($schedule_query);
$schedules = $schedule_result->fetch_all(MYSQLI_ASSOC);

// Recupera il totale dei pazienti e delle prenotazioni
$patients_result = $db->query("SELECT COUNT(*) as total FROM patient");
$patients_data = $patients_result->fetch_assoc();
$total_patients = $patients_data['total'];

$bookings_result = $db->query("SELECT COUNT(*) as total FROM appointment WHERE docname ='$username'");
$bookings_data = $bookings_result->fetch_assoc();
$total_bookings = $bookings_data['total'];

$today_date = date('Y-m-d');
$todays_bookings_result = $db->query("SELECT COUNT(*) as total FROM schedule WHERE data = '$today_date'");
$todays_bookings_data = $todays_bookings_result->fetch_assoc();
$todays_bookings = $todays_bookings_data['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        /* Inserisci qui il tuo CSS */

        /* Reset default margin and padding */
        * 

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
            color: #fff; /* Colore del testo per contrastare con lo sfondo */
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
            position: flex 1;
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
            position: relative; 
            width: 100%;
            bottom: 0;
            margin-top: 10px;
        }

        
        /* Stile per il contenitore dell'orario di visita */
        .booking-container {
            background-color: #fff; /* Colore di sfondo del contenitore */
            padding: 20px;
            width: 50%;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2); /* Ombra del contenitore */
            margin-top: 20px; /* Spazio sopra il contenitore */
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

        .stats-container {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
        }

        .stat-box {
            flex: 1; 
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin-right: 10px;
            text-align: center;
            text-decoration: none;
            color: #333; /* Colore del testo */
            transition: background-color 0.3s, box-shadow 0.3s; /* Aggiungi una transizione */
        }

        .stat-box:last-child {
            margin-right: 0;
        }

        .stat-box h3 {
            font-size: 20px;
            margin-bottom: 10px;
        }

        .stat-box p {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
        }

        .butn {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 10px;
            border-radius: 5px;
            color: white;
            background: #2278ae;
            text-align: center;
            text-decoration: none;
            font-size: 16px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .butn:hover {
            background: #66c4ff;
        }

        .appointments {
            margin-top: 20px;
            margin-bottom:20px;
          
        }

        .appointments-table {
            width: 100%;
          
            border-collapse: collapse;
            margin-top: 20px;
           
            
        }

        .appointments-table th, .appointments-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .appointments-table th {
            background-color: #f2f2f2;
        }

        .appointments-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .appointments-table tr:hover {
            background-color: #ddd;
        }

        .button-edit, .button-delete {
            border: none;
            color: white;
            padding: 8px 16px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            margin: 2px 1px;
            cursor: pointer;
            border-radius: 4px;
        }

        .button-edit {
            background-color: #007bff;
        }

        .button-edit:hover {
            background-color: #0056b3;
        }

        .button-delete {
            background-color: #dc3545;
        }

        .button-delete:hover {
            background-color: #c82333;
        }

        .logout-btn {
            border: none;
            color: white;
            background: #66c4ff;
            padding: 8px 16px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            margin: 8px 15px;
            cursor: pointer;
            border-radius: 4px;
        }

        .logout-btn:hover {
            background:#689aba;
        }


    /* Media Query per schermi piccoli */
    @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                border-right: none;
            }

            .main-content {
                margin-left: 0;
                width: 100%;
            }

            .profile-image {
                width: 20%;
                margin-right: 5px;
            }

            .profile-name {
                font-size: 16px;
            }

            .sidebar ul li a {
                font-size: 16px;
            }

            .booking-container {
                width: 90%;
                padding: 10px;
            }

            .stat-box {
                margin-right: 5px;
                padding: 10px;
            }

            .butn, .logout-btn, .button-edit, .button-delete {
                padding: 8px 12px;
                font-size: 12px;
            }
        }

        @media (max-width: 480px) {
            header {
                height: 200px;
            }

            header h1 {
                font-size: 20px;
            }

            .profile-name, .sidebar ul li a {
                font-size: 14px;
            }

            .stat-box h3 {
                font-size: 16px;
            }

            .stat-box p {
                font-size: 20px;
            }

            .butn, .logout-btn, .button-edit, .button-delete {
                padding: 6px 10px;
                font-size: 10px;
            }

            .booking-container {
                width: 100%;
                padding: 5px;
            }

            .appointments ul li {
                font-size: 14px;
            }

            .appointments-table th, .appointments-table td {
                padding: 6px;
                font-size: 12px;
            }
        }


    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="profile-container"> 
         <!--   <img src="bdb4754f1a012bfc00936ad081fe9802.jpg" class="profile-image" alt="logo" width="30%" height="20%">   -->
         <i class="bi bi-heart-pulse-fill" style="font-size: 6rem; color: #66c4ff;"></i>
            <p class="profile">Doctor name :<?php echo substr($username, 0, 13); ?> / Email: </p>
            <p class="profile-subtitle"><?php echo substr($useremail, 0, 22); ?></p>
        </div>
        <ul>
        <li><a href="index.php"><i class="fas fa-user"></i> Dashboard</a></li>
            <li><a href="patients.php"><i class="fas fa-user"></i> Patients</a></li>
            <li><a href="my_appointments.php"><i class="fas fa-calendar-check"></i> Appointments</a></li>
            <li><a href="doctor-profile.php"><i class="fas fa-calendar-check"></i> My Profile</a></li>
        </ul>
        <a href="logout.php" class="logout-btn">Logout</a>  
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <header>
            <h1>Welcome to Your Dashboard</h1>
            <div class="datetime">
                <p><?php echo date('l, F j, Y'); ?></p>
                <p><?php echo date('h:i:s A'); ?></p>
            </div>
           <a href="my_appointments.php" class="butn">View Appointment</a>   
        </header>   

        <!-- Nuova sezione statistiche -->
        <section class="stats-container">
            <a href="patients.php" class="stat-box">       
                <h3>Total Patients</h3>
                <p><?php echo $total_patients; ?></p>
            </a>
            <a href="my_appointments.php" class="stat-box">
                <h3>Total Bookings</h3>
                <p><?php echo $total_bookings; ?></p>
            </a>
            <a href="today_appointment.php" class="stat-box">
                <h3>Today's Bookings</h3>
                <p><?php echo $todays_bookings; ?></p>       
            </a>
        </section>

        <!-- Modulo per impostare orari di visita -->
        <div class="booking-container">
            <h2>Set visiting Times</h2>
            <?php if (isset($message)) { echo "<p class='message'>$message</p>"; } ?>
            <form action="" method="post">
                <label for="date">Data:</label>
                <input type="date" id="date" name="date" required>

                <label for="start_time">Start Time:</label>
                <input type="time" id="start_time" name="start_time" required>

                <label for="end_time">End Time:</label>
                <input type="time" id="end_time" name="end_time" required>

                <input type="hidden" name="specialization" value="<?php echo htmlspecialchars($specialization); ?>">

                <input type="hidden" name="docname" value="<?php echo htmlspecialchars($username); ?>">
                <input type="submit" value="Set">
            </form>
        </div>

        <!-- Tabella degli appuntamenti -->
        <section class="appointments">
            <h2>Scheduled Appointments</h2>
            <table class="appointments-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Specialization</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($schedules as $schedule): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($schedule['scheduleid']); ?></td>
                            <td><?php echo htmlspecialchars($schedule['data']); ?></td>
                            <td><?php echo htmlspecialchars($schedule['ora_inizio']); ?></td>
                            <td><?php echo htmlspecialchars($schedule['ora_fine']); ?></td>
                            <td><?php echo htmlspecialchars($schedule['specialization']); ?></td>
                            <td>
                                <a href="edit_appointment.php?id=<?php echo htmlspecialchars($schedule['scheduleid']); ?>" class="button-edit">Edit</a>
                                <a href="?delete=<?php echo htmlspecialchars($schedule['scheduleid']); ?>" class="button-delete">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

       
    </main>
    <div class="footer">
        <!-- Footer -->
        <footer>
            <p>&copy; 2024 Medical Dashboard. All rights reserved.</p>
        </footer>
</body>
</html>