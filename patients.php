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

// Ottieni tutti i dettagli dei pazienti
$query = "SELECT * FROM patient";
$result = $db->query($query);
$patiens = $result->fetch_all(MYSQLI_ASSOC);

// Ottieni il totale dei pazienti
$totalPatients = count($patiens);


// Ottieni il totale dei pazienti
$query = "SELECT COUNT(*) AS total_patients FROM patient";
$result = $db->query($query);
$totalPatients = $result->fetch_assoc()['total_patients'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Totale Pazienti</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">  
    <style>
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

        .info {
            margin-bottom: 20px;
        }

        .info h2 {
            margin-top: 20px;
            font-size: 20px;
            color: #2c3e50;
        }

        .info p {
            font-size: 20px;
            color: #34495e;
          margin-top: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #f8f8f8;
            padding: 10px;
            width: 20%;
            font-weight:bold;
        }

        
        .patients-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .patients-table th, .patients-table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .patients-table th {
            background-color: #f8f8f8;
            font-weight: bold;
        }

        .patient-row {
            background-color: #fff;
        }

        .patient-row:hover {
            background-color: #f1f1f1;
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
    </style>
</head>
<body>
  <!-- Sidebar -->
  <aside class="sidebar">
        <div class="profile-container"> 
        <i class="bi bi-file-medical" style="font-size: 5rem; color:#66c4ff;"></i>
         <!--   <img src="coversanità.jpg" class="profile-image" alt="logo">  -->
            <p class="profile">Doctor name :<?php echo substr($username, 0, 13); ?> / Email: </p>
            <p class="profile-subtitle"><?php echo substr($useremail, 0, 22); ?></p>
        </div>
        <ul>
        <li><a href="index.php"><i class="fas fa-user"></i> Dashboard</a></li>
            <li><a href="my_appointments.php"><i class="fas fa-calendar-check"></i> Appointments</a></li>
        </ul>
      <!--  <a href="logout.php" class="logout-btn">Logout</a>  -->
    </aside>

<!-- Main Content -->
<main class="main-content">
    <header>
        <h1>Patients Details</h1>
        <div class="datetime">
            <p><?php echo date('l, F j, Y'); ?></p>
            <p><?php echo date('h:i A'); ?></p>
        </div>
    </header>

    <div class="info">
        <h2>Total Patients</h2>
        <p><?php echo $totalPatients; ?></p>
    </div>

    <div class="info">
        <h2>List of patients</h2>
        <table class="patients-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>NIC</th>
                    <th>Birthday</th>
                    <th>Phone</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($patiens as $patient): ?>
                    <tr class="patient-row">
                        <td><?php echo htmlspecialchars($patient['pid']); ?></td>
                        <td><?php echo htmlspecialchars($patient['pemail']); ?></td>
                        <td><?php echo htmlspecialchars($patient['pname']); ?></td>
                        <td><?php echo htmlspecialchars($patient['paddress']); ?></td>
                        <td><?php echo htmlspecialchars($patient['pnic']); ?></td>
                        <td><?php echo htmlspecialchars($patient['pdob']); ?></td>
                        <td><?php echo htmlspecialchars($patient['ptel']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

<footer>
    &copy; 2024 Online Health care. All rights reserved.
</footer>
</body>
</html>
