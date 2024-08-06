<?php
// Avvia la sessione
session_start();

// Verifica se l'utente è loggato e se è un dottore
if (isset($_SESSION["user"]) && $_SESSION['usertype'] === 'd') {
    $doctor_email = $_SESSION["user"]; // Ottieni l'email del dottore loggato

    // Connessione al database
    include("con.php");

    // Recupera il nome del dottore utilizzando l'email
    $doctor_query = $db->query("SELECT docname FROM doctor WHERE docemail='$doctor_email'");
    if ($doctor_row = $doctor_query->fetch_assoc()) {
        $docname = $doctor_row['docname'];

        // Recupera le prenotazioni per il dottore
        $appointments_query = $db->query("SELECT * FROM appointment WHERE docname='$docname'");

        // Recupera le informazioni sui pazienti
        $patients = [];
        while ($appointment = $appointments_query->fetch_assoc()) {
            $patient_email = $appointment['pemail'];
            if (!isset($patients[$patient_email])) {
                $patient_query = $db->query("SELECT * FROM patient WHERE pemail='$patient_email'");
                if ($patient_row = $patient_query->fetch_assoc()) {
                    $patients[$patient_email] = $patient_row;
                } else {
                    echo "Paziente con email $patient_email non trovato.";
                }
            }
        }
    } else {
        echo "Dottore non trovato.";
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizza Prenotazioni</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Inserisci qui il tuo CSS */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .header {
            background-color: #2c3e50;
            color: #ecf0f1;
            padding: 15px;
            text-align: center;
        }

        .main-content {
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #fff;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #2c3e50;
            color: #ecf0f1;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        .logout-btn {
            background-color: #e74c3c;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .logout-btn:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Visualizza Prenotazioni</h1>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>

    <div class="main-content">
        <h2>Prenotazioni per <?php echo htmlspecialchars($docname); ?></h2>

        <?php if ($appointments_query->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID Prenotazione</th>
                        <th>Data</th>
                        <th>Orario Inizio</th>
                        <th>Orario Fine</th>
                        <th>Nome Paziente</th>
                        <th>Email Paziente</th>
                        <th>Indirizzo Paziente</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($appointment = $appointments_query->fetch_assoc()): ?>
                        <?php $patient = $patients[$appointment['pemail']]; ?>
                        <tr>
                            <td><?php echo htmlspecialchars($appointment['appoid']); ?></td>
                            <td><?php echo htmlspecialchars($appointment['data']); ?></td>
                            <td><?php echo htmlspecialchars($appointment['ora_inizio']); ?></td>
                            <td><?php echo htmlspecialchars($appointment['ora_fine']); ?></td>
                            <td><?php echo htmlspecialchars($patient['pname']); ?></td>
                            <td><?php echo htmlspecialchars($appointment['pemail']); ?></td>
                            <td><?php echo htmlspecialchars($patient['paddress']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Non ci sono prenotazioni per questo dottore.</p>
        <?php endif; ?>
    </div>
</body>
</html>
