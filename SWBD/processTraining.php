<?php
session_start(); // Inizializza la sessione

// Verifica se sono stati inviati dati dal form
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['username'])) {
    // Connette al database (assumendo che tu abbia un file per la connessione)
    include "database.php";

    // Prepara la query per l'inserimento dei dati
    $query = "INSERT INTO allenamenti (nome, gruppo, giorno, username) VALUES (?, ?, ?, ?)";

    // Prepara l'istruzione SQL per l'inserimento
    $stmt = $conn->prepare($query);

    // Verifica se la preparazione della query è avvenuta con successo
    if ($stmt) {
        // Associa i parametri
        $stmt->bind_param("ssis", $nome, $gruppo, $giorno, $_SESSION['username']); // Utilizza direttamente $_SESSION['username']

        // Esegui per ogni allenamento
        for ($i = 0; $i < count($_POST['nome']); $i++) {
            // Assegna i valori dei campi del form alle variabili
            $nome = $_POST['nome'][$i];
            $gruppo = $_POST['gruppo'][$i];
            $giorno = $_POST['giorno'][$i];

            // Esegui la query
            $stmt->execute();
        }

        // Chiudi la connessione
        $stmt->close();
        $conn->close();

        // Reindirizza alla pagina dei tuoi allenamenti o a una pagina di conferma
        header("Location: showMyTraining.php");
        exit;
    } else {
        // Se la preparazione della query ha fallito, mostra un errore
        header("Location: error.php");
        exit;
    }
} else {
    // Se i dati inviati non sono corretti o $_SESSION['username'] non è impostato, reindirizza a una pagina di errore
    header("Location: error.php");
    exit;
}
?>
