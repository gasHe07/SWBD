<?php
session_start();

// Include the database connection file
include 'database.php';

//verifico che l'utente risulta
if (!isset($_SESSION['username'])) {
    header("Location: PhysiMonitor.php");
    exit;
}

// verifico se è stato dato l'input
if (isset($_POST['submit'])) {
    // Recupera il nome utente dalla sessione
    $sessionid = $_SESSION['username'];
    $nuovoPeso = $_POST['nuovoPeso'];

    // Inserimento del nuovo peso nella tabella "peso" utilizzando una query parametrica
    $queryPeso = $conn->prepare("INSERT INTO peso (username, data, peso) VALUES (?, NOW(), ?)");
    $queryPeso->bind_param("sd", $sessionid, $nuovoPeso);

    if ($queryPeso->execute()) {
        // Inserimento riuscito, ora puoi aggiornare il campo "pesoAttuale" nella tabella "users"
        $queryUpdatePesoAttuale = $conn->prepare("UPDATE users SET pesoAttuale = ? WHERE username = ?");
        $queryUpdatePesoAttuale->bind_param("ds", $nuovoPeso, $sessionid);

        if ($queryUpdatePesoAttuale->execute()) {
            echo '<div id="message" style="color: green;">Aggiornamento riuscito</div>';
        } else {
            echo '<div id="message" style="color: red;">Errore nell\'aggiornamento di pesoAttuale: ' . $queryUpdatePesoAttuale->error . '</div>';
        }
    } else {
        echo '<div id="message" style="color: red;">Errore nell\'inserimento del peso: ' . $queryPeso->error . '</div>';
    }


    // Chiudi la connessione al database
    $conn->close();
}

?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="logo.png" type="image/png">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <title>Dashboard</title>
</head>
<style>
    /* styles.css */
.centered-form {
    display: flex;
    flex-direction: column;
    align-items: center;
    max-width: 300px; /* Larghezza massima per una migliore leggibilità */
    margin: 0 auto;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 10px;
    background-color: #f8f8f8; /* Colore di sfondo */
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
    background-color: #007BFF; /* Colore di sfondo del pulsante */
    color: #fff; /* Colore del testo del pulsante */
    border: none;
    border-radius: 5px;
    padding: 10px 20px;
    cursor: pointer;
}

.centered-form input[type="submit"]:hover {
    background-color: #0056b3; /* Cambia il colore di sfondo al passaggio del mouse */
}

</style>
<script src="https://www.gstatic.com/charts/loader.js"></script>

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
    <br><br>
    <div id="pesoChart" style="width: 100%; height: 350px;"></div>
    <br>
    <form action="dashboard.php" method="post" class="centered-form">
        <label for="aggiorna">Aggiorna il tuo peso:</label><br>
        <input type="number" id="nuovoPeso" name="nuovoPeso" step="0.1" min="30" max="110" required><br>
        <input type="submit" name="submit" value="Aggiorna">
    </form>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        google.charts.load('current', {
            'packages': ['corechart']
        });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            // Recupera i dati dal server tramite AJAX
            $.ajax({
                url: 'getPesoData.php',
                method: 'POST',
                success: function(data) {
                    var dataObj = JSON.parse(data);
                    var dateLabels = dataObj.dates;
                    var pesoData = dataObj.peso;
                    var pesoDesiderato = dataObj.pesoDesiderato; // Recupera il peso desiderato dai dati

                    // Crea un oggetto DataTable per il grafico
                    var chartData = new google.visualization.DataTable();
                    chartData.addColumn('string', 'Data');
                    chartData.addColumn('number', 'Peso');
                    chartData.addColumn('number', 'Peso Desiderato'); // Aggiungi una nuova colonna per il peso desiderato

                    // Aggiungi i dati dal database
                    for (var i = 0; i < dateLabels.length; i++) {
                        chartData.addRow([dateLabels[i], pesoData[i], pesoDesiderato]); // Aggiungi il peso desiderato come terza colonna
                    }

                    var options = {
                        title: 'Grafico Peso-Data',
                        curveType: 'function',
                        legend: {
                            position: 'bottom'
                        }
                    };

                    var chart = new google.visualization.LineChart(document.getElementById('pesoChart'));
                    chart.draw(chartData, options);
                },
                error: function(xhr, status, error) {
                    console.error("Errore nella richiesta AJAX: " + error);
                }
            });
        }

    });
</script>





</html>