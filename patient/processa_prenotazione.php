<?php
session_start();

if (isset($_SESSION["user"]) && $_SESSION['usertype'] == 'p') {
    $useremail = $_SESSION["user"];
} else {
    header("Location: login.php");
    exit();
}

include("../con.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $scheduleid = $_POST['scheduleid'];
    $docid = $_POST['docid'];
    $data = $_POST['data'];
    $ora_inizio = $_POST['ora_inizio'];
    $ora_fine = $_POST['ora_fine'];
    
    // Assumi che ci sia una tabella `appointments` dove inserisci le prenotazioni
    $query = "INSERT INTO appointment (scheduleid, docid, pemail, data, ora_inizio, ora_fine) 
              VALUES ('$scheduleid', '$docid', '$useremail', '$data', '$ora_inizio', '$ora_fine')";
    
    if ($db->query($query) === TRUE) {
        echo "Prenotazione effettuata con successo!";
        header("Location: index.php");
    } else {
        echo "Errore: " . $query . "<br>" . $db->error;
    }
}
?>
