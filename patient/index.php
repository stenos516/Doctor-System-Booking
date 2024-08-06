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

// Gestione della ricerca
$search_specialization = "";
if (isset($_POST['specialization'])) {
    $search_specialization = $db->real_escape_string($_POST['specialization']);
}

// Ottieni i medici in base alla specializzazione
$query = "SELECT * FROM doctor WHERE specialization LIKE '%$search_specialization%'";
$result = $db->query($query);
$doctors = [];
while ($row = $result->fetch_assoc()) {
    $doctors[] = $row;
}

// Messaggio di conferma
$message = "";

// Se il modulo è stato inviato, processa la prenotazione
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $scheduleid = $_POST['scheduleid'];
    $docid = $_POST['docid'];
    $data = $_POST['data'];
    $ora_inizio = $_POST['ora_inizio'];
    $ora_fine = $_POST['ora_fine'];
    $Docname = $_POST['docname'];
    $Specialization = $_POST['specialization'];
    // Assumi che ci sia una tabella `appointments` dove inserisci le prenotazioni
    $query = "INSERT INTO appointment (scheduleid, docid, pemail, data, ora_inizio, ora_fine, docname, specialization) 
              VALUES ('$scheduleid', '$docid', '$useremail', '$data', '$ora_inizio', '$ora_fine', '$Docname', '$Specialization')";

    if ($db->query($query) === TRUE) {
        $message = "Your reservation was successfull!";
    } else {
        $message = "Errore: " . $query . "<br>" . $db->error;
    }
}

// Ottieni gli orari disponibili dal database con il nome del dottore
$query = "
    SELECT schedule.*, doctor.docname 
    FROM schedule 
    JOIN doctor ON schedule.docemail = doctor.docemail
    ORDER by schedule.data ASC";
$result = $db->query($query);
$schedule = [];
while ($row = $result->fetch_assoc()) {
    $schedule[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleziona Data e Orario</title>
    <link rel="stylesheet" href="style.css">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">  
    <style>
     *
     {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
     .message {
    background-color: #d4edda;
    color: #155724;
    padding: 10px;
    margin-bottom: 20px;
    border: 1px solid #c3e6cb;
    border-radius: 4px;
    
}

.schedule-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    
}

.schedule-table th, .schedule-table td {
    padding: 10px;
    border: 1px solid #ddd;
    text-align: left;
}

.schedule-table th {
    background-color: #f8f8f8;
    font-weight: bold;
}

.schedule-row {
    background-color: #fff;
}

.schedule-row:hover {
    background-color: #f1f1f1;
}

.schedule-button {
    background-color: #4CAF50; /* Verde */
    color: white;
    border: none;
    padding: 10px 20px;
    cursor: pointer;
    border-radius: 4px;
    transition: background-color 0.3s ease;
    display: inline-block;
}

.schedule-button:hover {
    background-color: #45a049; /* Verde più scuro */
}

body, html {
    height: 100%;
    width: 100%;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
}

body {
    font-family: Arial, sans-serif;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    background-color: #f4f4f4;
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

.main-content  {
    margin-left: 30%;
    padding: 0px; 
    padding-bottom: 0px;
    padding-top: 0px;
    width: 70%;
    position: relative;
}

header {
    background-image: url('coversanità.jpg');
    background-size: cover;
    background-position: center;
    color: black;
    padding: 20px;
    border-radius: 10px;
    margin: 0;
    width: 100%;
    height: 300px;
    position: flex;
    flex-direction: column;
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
    position: relative;
    width: 100%;
    bottom: 0;
    margin-top: auto;
}

.booking-container {
    background-color: #fff;
    padding: 20px;
    width: 50%;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    margin-top: 20px;
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
    margin-left: 0;
    margin-right 0;
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
    color: #333;
    transition: background-color 0.3s, box-shadow 0.3s;
    z-index: 1;
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

footer {
    background-color: #bdc3c7;
    color: #2c3e50;
    text-align: center;
    padding: 10px;
    position: fixed;
    width: 100%;
    bottom: 0;
    margin-top: 10px;
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
    margin: 70px 18px;
    cursor: pointer;
    border-radius: 4px;
}

.logout-btn:hover {
    background: #689aba;
}

.container {
    margin-top: 20px;
    display: flex;
    flex-direction:column;
    position: flex;
}

/* Stile per la barra di ricerca */
.search-container {
   
   width: 80%;
  margin-left: 10px;
   padding: 7px;
   background: rgba(0,0,0,.4);
   box-shadow: 0 15px 25px rgba(0,0,0,.5);
   border-radius: 8px;
  
}

.search-container label {
   color: white;
   display: block;
   font-size: 16px;
   font-weight: bold;
   margin-bottom: 10px;
  
}

.search-container input[type="text"] {
   width: calc(100% - 110px); /* Dedicare spazio per il pulsante di ricerca */
   padding: 10px;
   border: 1px solid #ccc;
   border-radius: 4px;
   margin-right: 10px;
   font-size: 16px;
}

.search-container button {
   background-color: #03e9f4; /* Verde */
   color: white;
   border: none;
   padding: 7px 10px;
   cursor: pointer;
   border-radius: 4px;
   font-size: 16px;
   transition: background-color 0.3s ease;
}

.search-container button:hover {
   background-color: #0288d1;
}

.search-container .search_specialization{
color: white;
}

    
    
    </style>
</head>
<body>
    <script>document.querySelector('.schedule-button').addEventListener('click', function() {
    console.log('Main content height:', document.querySelector('.main-content').offsetHeight);
    console.log('Header position:', document.querySelector('header').getBoundingClientRect());
    console.log('Message height:', document.querySelector('.message').offsetHeight);
});
</script>
  <!-- Sidebar -->
  <aside class="sidebar">
        <div class="profile-container"> 
          <!--  <img src="coversanità.jpg" class="profile-image" alt="logo">  -->
          <i class="bi bi-hospital" style="font-size: 6rem; color: #66c4ff;"></i>
            <p class="profile">Name : <?php echo substr($username, 0, 13); ?> / Email :</p>
            <p class="profile-subtitle"><?php echo substr($useremail, 0, 22); ?></p>
        </div>
        <ul>
       <!--     <li><a href=""><i class="fas fa-user"></i> Patients</a></li> -->
            <li><a href="appointments.php"><i class="bi bi-calendar"></i> Appointments</a></li>
            <li><a href="profile-patient.php"><i class="bi bi-person-fill"></i> My Profile</a></li>
        </ul>

        <form action="search_doctors.php" method="post" class="search-container">
        <label for="search_specialization" id="search_specialization" >Search by Specialization:</label>
        <input type="text" id="search_specialization" name="search_specialization" placeholder="Enter specialization" required>
        <button type="submit" class="schedule-button">Search</button>
    </form>

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
       <a href="appointments.php" class="butn">View Appointment</a>
       
    </header>

    <div class="container">
        <h2>Book your doctor</h2>
        <?php if (!empty($message)) { echo "<div class='message'>$message</div>"; } ?>
        <?php if (empty($schedule)) { echo "<p>Nessun orario disponibile.</p>"; } ?>
        <table class="schedule-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Doctor Name</th>
                    <th>Specialization</th>
                    <th>Book</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($schedule as $dispo) { ?>
                    <tr class="schedule-row">
                        <td><?php echo htmlspecialchars($dispo['data']); ?></td>
                        <td><?php echo htmlspecialchars($dispo['ora_inizio']); ?></td>
                        <td><?php echo htmlspecialchars($dispo['ora_fine']); ?></td>
                        <td><?php echo htmlspecialchars($dispo['docname']); ?></td>
                        <td><?php echo htmlspecialchars($dispo['specialization']); ?></td>
                        <td>
                            <form action="" method="post">
                                <input type="hidden" name="scheduleid" value="<?php echo htmlspecialchars($dispo['scheduleid']); ?>">
                                <input type="hidden" name="docname" value="<?php echo htmlspecialchars($dispo['docname']); ?>">
                                <input type="hidden" name="data" value="<?php echo htmlspecialchars($dispo['data']); ?>">
                                <input type="hidden" name="ora_inizio" value="<?php echo htmlspecialchars($dispo['ora_inizio']); ?>">
                                <input type="hidden" name="ora_fine" value="<?php echo htmlspecialchars($dispo['ora_fine']); ?>">
                                <input type="hidden" name="specialization" value="<?php echo htmlspecialchars($dispo['specialization']); ?>">
                                <button type="submit" class="schedule-button">Book</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
                </main>
                <div class="footer">
        <!-- Footer -->
        <footer>
            <p>&copy; 2024 Medical Dashboard. All rights reserved.</p>
        </footer>

</body>
</html>