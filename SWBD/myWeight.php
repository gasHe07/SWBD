<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="logo.png" type="image/png">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <title>Il mio Storico</title>
</head>
<style>
    body {
        background-color: #eef5fb;
        margin: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
    }

    table {
        width: 70%; 
        border-collapse: collapse;
        background-color: #ffffff; 
        margin: 20px auto; 
        border: 1px solid #a9d9f0; 
    }

    thead {
        background-color: #a9d9f0;
    }

    th, td {
        padding: 12px;
        text-align: center;
        border: 1px solid #a9d9f0; 
    }

    tr:nth-child(even) {
        background-color: #f5f5f5;
    }

    tr:hover {
        background-color: #e1e1e1;
    }
</style>

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
    <table>
        <thead>
            <tr>
                <th>Data d'aggiornamento (AAAA-MM-GG)</th> 
                <th>Peso (Kg)</th>
            </tr>
        </thead>
        <tbody>
            <?php
            session_start();

            // Include the database connection file
            include 'database.php';

            // Verifico che l'utente risulti loggato
            if (!isset($_SESSION['username'])) {
                header("Location: PhysiMonitor.php");
                exit;
            }

            // Query per selezionare la colonna dalla tabella e ordinare in base alla data d'inserimento
            $queryDateWeight = $conn->prepare("SELECT data, peso FROM peso WHERE username=? ORDER BY data ASC");
            $queryDateWeight->bind_param("s", $_SESSION['username']);
            $queryDateWeight->execute(); // Eseguo la query

            if ($queryDateWeight) {
                $result = $queryDateWeight->get_result();
                
                //verifico se ci sono colonne, devono esserci, altrimenti c'è un problema
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . $row['data'] . '</td>';
                        echo '<td>' . $row['peso'] . '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="2">Nessun dato disponibile</td></tr>';
                }
            } else {
                echo '<div id="message" style="color: red;">Errore nella preparazione della query: ' . $conn->error . '</div>';
            }
            //chiudo la connessione col DB
            $conn->close();
            ?>
        </tbody>
    </table>
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
</html>