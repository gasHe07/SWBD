<?php
session_start();

// Include il file di connessione al database
include 'database.php';

// Verifica se l'utente è autenticato
if (!isset($_SESSION['username'])) {
    header("Location: PhysiMonitor.php");
    exit;
}

// Recupera il peso desiderato dell'utente dalla tabella "users"
$queryPesoDesiderato = $conn->prepare("SELECT pesoDesiderato FROM users WHERE username = ?");
$queryPesoDesiderato->bind_param("s", $_SESSION['username']);
$queryPesoDesiderato->execute();
$queryPesoDesiderato->store_result();

if ($queryPesoDesiderato->num_rows > 0) {
    $queryPesoDesiderato->bind_result($pesoDesiderato);
    $queryPesoDesiderato->fetch();
} else {
    $pesoDesiderato = null; // Nessun peso desiderato trovato, dovrebbe essere impossibile visto che è dichiarato "required"
}

// Esegui la query per ottenere i dati del peso
$query = "SELECT data, peso FROM peso WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$result = $stmt->get_result();

$data = array();
$data['dates'] = array();
$data['peso'] = array();
$data['pesoDesiderato'] = $pesoDesiderato; // Aggiungi il peso desiderato ai dati

while ($row = $result->fetch_assoc()) {
    $data['dates'][] = $row['data'];
    $data['peso'][] = (float)$row['peso'];
}

// Restituisci i dati in formato JSON
echo json_encode($data);

// Chiudi la connessione al database
$conn->close();
?>
