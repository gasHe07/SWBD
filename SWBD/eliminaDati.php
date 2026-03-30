<?php
session_start();

// Include the database connection file
include 'database.php';

// Verifico che l'utente risulti loggato
if (!isset($_SESSION['username'])) {
    header("Location: PhysiMonitor.php");
    exit;
}

$username = $_SESSION['username'];

// Inizia una transazione per assicurare l'integrità dei dati
$conn->begin_transaction();

// Elimina i dati della tabella "allenamenti" correlati all'utente
$queryDeleteWorkouts = $conn->prepare("DELETE FROM allenamenti WHERE username = ?");
$queryDeleteWorkouts->bind_param("s", $username);
$queryDeleteWorkouts->execute();

// Elimina i dati della tabella "peso" correlati all'utente
$queryDeleteWeight = $conn->prepare("DELETE FROM peso WHERE username = ?");
$queryDeleteWeight->bind_param("s", $username);
$queryDeleteWeight->execute();

// Elimina i dati della tabella "users" relativi all'utente
$queryDeleteUser = $conn->prepare("DELETE FROM users WHERE username = ?");
$queryDeleteUser->bind_param("s", $username);
$queryDeleteUser->execute();

// Esegui il commit della transazione se tutte le query hanno avuto successo
if ($queryDeleteWorkouts && $queryDeleteWeight && $queryDeleteUser) {
    $conn->commit();
    echo "Dati eliminati con successo.";
    //vado nella pagina di logout per distruggere la sessione
    header('Location: logout.php');
} else {
    // Se c'è un errore in una delle query, esegui il rollback della transazione
    $conn->rollback();
}
// Chiudi la connessione al database
$conn->close();
