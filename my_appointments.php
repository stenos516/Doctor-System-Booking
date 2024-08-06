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

// Recupera informazioni del dottore
$userrow = $db->query("SELECT * FROM doctor WHERE docemail='$useremail'");
$userfetch = $userrow->fetch_assoc();
$userid = $userfetch["docid"];
$username = $userfetch["docname"];

// Recupera tutte le prenotazioni per questo medico usando i parametri preparati
$stmt = $db->prepare("
    SELECT a.appoid, a.data, a.ora_inizio, a.ora_fine, p.pname, p.pemail, p.ptel
    FROM appointment a
    JOIN patient p ON a.pemail = p.pemail
    WHERE a.docname = ?
    ORDER BY a.data ASC
");
$stmt->bind_param("s", $username);
$stmt->execute();
$appointments_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Appointments</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">  
    <style>
        /* Inserisci qui il tuo CSS */
        body, html {
            height: 100%;
            width: 100%;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
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
            display: flex 1;
            margin-left: 30%;
            padding: 20px;
            width: 70%;
            position: relative;
            padding-bottom: 60px;
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

        .appointments {
            margin-bottom: 20px;
        }

        .appointments h2 {
            font-size: 20px;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        .appointments table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .appointments th, .appointments td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .appointments th {
            background-color: #f2f2f2;
        }

        .appointments tr:hover {
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

        .profile-name {
    margin-left: 10px; /* O qualsiasi valore tu preferisca */
    display: inline-block; /* Assicurati che sia inline per essere allineato con l'icona */
}
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="profile-container"> 
        <i class="bi bi-hospital" style="font-size: 5rem; color: #66c4ff;"></i>
          <!--  <img src="bdb4754f1a012bfc00936ad081fe9802.jpg" class="profile-image" alt="logo" width="30%" height="20%">  -->
            <p class="profile-name">  Doctor name :<?php echo substr($username, 0, 13); ?> / Email: </p>
            <p class="profile-subtitle"><?php echo substr($useremail, 0, 22); ?></p>
        </div>
        <ul>
        <li><a href="index.php"><i class="fas fa-user"></i> Dashboard</a></li>
            <li><a href="patients.php"><i class="fas fa-user"></i> Patients</a></li>
           
        </ul>
      <!--  <a href="logout.php" class="logout-btn">Logout</a> -->
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <header>
            <h1>My Appointments</h1>
            <div class="datetime">
                <p><?php echo date('l, F j, Y'); ?></p>
                <p><?php echo date('h:i:s A'); ?></p>
            </div>
        </header>

        <!-- Tabella per le prenotazioni -->
        <section class="appointments">
            <h2>Scheduled Appointments</h2>
            <table>
                <thead>
                    <tr>
                        <th>Appointment ID</th>
                        <th>Date</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Patient Name</th>
                        <th>Patient Email</th>
                        <th>Patient Phone</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($appointments_result->num_rows > 0) {
                        while ($row = $appointments_result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>{$row['appoid']}</td>";
                            echo "<td>{$row['data']}</td>";
                            echo "<td>{$row['ora_inizio']}</td>";
                            echo "<td>{$row['ora_fine']}</td>";
                            echo "<td>{$row['pname']}</td>";
                            echo "<td>{$row['pemail']}</td>";
                            echo "<td>{$row['ptel']}</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>No appointments found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </main>

    <!-- Footer -->
     <div class="footer">
    <footer>
        <p>&copy; 2024 Medical Dashboard. All rights reserved.</p>
    </footer>
</body>
</html>
