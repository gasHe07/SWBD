<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="logo.png" type="image/png">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        .centered-form {
            display: flex;
            flex-direction: column;
            align-items: center;
            max-width: 300px;
            /* Larghezza massima per una migliore leggibilità */
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #f8f8f8;
            /* Colore di sfondo */
        }


        .centered-form label {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .centered-form input[type="number"] {
            font-size: 16px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
            margin-bottom: 10px;
        }

        .centered-form input[type="submit"] {
            font-size: 18px;
            background-color: #007BFF;
            /* Colore di sfondo del pulsante */
            color: #fff;
            /* Colore del testo del pulsante */
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
        }

        .centered-form input[type="submit"]:hover {
            background-color: #0056b3;
            /* Cambia il colore di sfondo al passaggio del mouse */
        }


        .data-container {
            max-width: 600px;
            margin: 0 auto;
            font-size: 18px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
        }

        .data-label {
            font-size: 28px;
            font-weight: bold;
        }
    </style>
    <title>I Miei Dati</title>
</head>

<body>
    <nav class="navbar bg-body-tertiary fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">PhysiMonitor
                <img src="logo.png" alt="PhysiMonitor Logo" style="height: 40px;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Menù</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                        <li class="nav-item">
                            <a class="nav-link" href="changePassword.php">Cambia Password</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="myData.php">I Miei Dati</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="myWeight.php">Storico Pesi</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="showMyTraining.php">I Miei Allenamenti</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <br><br><br>
    <h1 style="color: red; text-align: center;">I Miei Dati:</h1>
    <?php
    session_start();

    // Include the database connection file
    include 'database.php';

    // Verifico che l'utente risulti loggato
    if (!isset($_SESSION['username'])) {
        header("Location: PhysiMonitor.php");
        exit;
    }
    if (isset($_POST['submit'])) {
        // Recupera il nome utente dalla sessione
        $sessionid = $_SESSION['username'];
        $nuovoPeso = $_POST['nuovoPeso'];

        $queryAggiornaPesoDes = $conn->prepare("UPDATE users SET pesoDesiderato = ? WHERE username = ?");
        $queryAggiornaPesoDes->bind_param("ds", $nuovoPeso, $sessionid);

        if ($queryAggiornaPesoDes->execute()) {
            echo '<div id="message" style="color: green;">Aggiornamento riuscito</div>';
        } else {
            echo '<div id="message" style="color: red;">Errore nell\'aggiornamento: ' . $queryAggiornaPesoDes->error . '</div>';
        }
    }

    $queryData = $conn->prepare("SELECT nome, cognome, altezza, pesoAttuale, pesoDesiderato FROM users WHERE username = ?");
    $queryData->bind_param("s", $_SESSION['username']);
    $queryData->execute(); // Eseguo la query

    if ($queryData) {
        $queryData->bind_result($nome, $cognome, $altezza, $pesoAttuale, $pesoDesiderato);

        echo '<div class="data-container">';
        while ($queryData->fetch()) {
            echo '<span class="data-label">Nome:</span> <span style="font-size: 24px;">' . $nome . '</span><br>';
            echo '<span class="data-label">Cognome:</span> <span style="font-size: 24px;">' . $cognome . '</span><br>';
            echo '<span class="data-label">Username:</span> <span style="font-size: 24px;">' . $_SESSION['username'] . '</span><br>';
            echo '<span class="data-label">Altezza:</span> <span style="font-size: 24px;">' . $altezza . ' cm</span><br>';
            echo '<span class="data-label">BMI:</span> <span style="font-size: 24px;">' . round($pesoAttuale/pow($altezza/100,2),2) . ' kg/m^2</span><br>';
            echo '<span class="data-label">Peso Attuale:</span> <span style="font-size: 24px;">' . $pesoAttuale . ' kg</span><br>';
            echo '<span class="data-label">Peso Desiderato:</span> <span style="font-size: 24px;">' . $pesoDesiderato . ' kg</span><br>';
        }
        echo '</div>';
    } else {
        echo '<div id="message" style="color: red;">Errore nella comunicazione col Database</div>';
    }
    // Chiudo la connessione col database
    $conn->close();
    ?>
    <br>
    <form action="myData.php" method="post" class="centered-form">
        <label for="aggiorna">Cambia il peso desiderato:</label>
        <input type="number" id="nuovoPeso" name="nuovoPeso" step="0.1" min="30" max="110" required><br>
        <input type="submit" name="submit" value="Cambia">
        <a href="javascript:void(0);" onclick="confermaEliminazione()" style="color: blue; text-decoration: underline;">Elimina Account</a>
    </form>


</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script>
    // Funzione per nascondere il messaggio dopo un certo periodo
    function hideMessage() {
        var messageDiv = document.getElementById('message');
        if (messageDiv) {
            messageDiv.style.display = 'none';
        }
    }
    // Nascondi il messaggio dopo 5 secondi
    setTimeout(hideMessage, 5000);

    //Funzine per conferma dell'eliminazione dei daiti
    function confermaEliminazione() {
        // Mostra una finestra di conferma all'utente
        var conferma = confirm("Sei sicuro di voler eliminare il tuo account? Questa azione è irreversibile.");

        // Se l'utente conferma, procedi con l'eliminazione
        if (conferma) {
            window.location.href = "eliminaDati.php";
        }
    }
</script>

</html>