<?php
session_start();

include "database.php";

// Verifica se l'utente è loggato
if (!isset($_SESSION['username'])) {
    header("Location: PhysiMonitor.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['selezionate'])) {
    // Recupera gli allenamenti selezionati dall'array POST
    $allenamentiSelezionati = $_POST['selezionate'];

    // Prepara la query per eliminare gli allenamenti selezionati
    $queryElimina = $conn->prepare("DELETE FROM allenamenti WHERE id=? AND username=?");
    $queryElimina->bind_param("is", $idAllenamento, $_SESSION['username']);

    // Ciclo per eseguire l'eliminazione di ogni allenamento selezionato
    foreach ($allenamentiSelezionati as $idAllenamento) {
        $queryElimina->execute();
    }

    // Chiudi la connessione al database
    $conn->close();

    // Reindirizza alla pagina dei tuoi allenamenti dopo l'eliminazione
    header("Location: showMyTraining.php");
    exit;
} else {
    // Se non sono stati selezionati allenamenti da eliminare, reindirizza alla pagina dei tuoi allenamenti
    header("Location: showMyTraining.php");
    exit;
}
?>
