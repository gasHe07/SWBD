<?php
session_start();

include "database.php";

// Verifica se l'utente è loggato
if (!isset($_SESSION['username'])) {
    header("Location: PhysiMonitor.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="logo.png" type="image/png">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <title>Allenamenti</title>
    <style>
        body {
            background-color: #eef5fb;
            margin: 0;
            display: flex;
            flex-direction: column;
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

        th,
        td {
            padding: 12px;
            text-align: center;
            border: 1px solid #a9d9f0;
            min-width: 250px; /* Ampiezza minima delle colonne */
        }

        th:first-child {
            min-width: 100px; /* Ampiezza minima per la colonna del pulsante di selezione */
        }

        tr:nth-child(even) {
            background-color: #f5f5f5;
        }

        tr:hover {
            background-color: #e1e1e1;
        }

        form {
            text-align: center;
        }
    </style>
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
    <form method="POST">
        <label>Quale Giorno vuoi vedere?<br>
            <select name="giorni">
                <option value="Tutti">Tutti</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
            </select>
            <input type="submit" value="Visualizza">
        </label>
    </form>
    <button id="select-all-button" onclick="toggleSelectAll()" data-select-all="true">Seleziona tutto</button>
    <form method="POST" action="deleteTraining.php">
        <table>
            <thead>
                <tr>
                    <th>Seleziona</th>
                    <th>Gruppo:</th>
                    <th>Nome:</th>
                    <th>Giorno:</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    // Recupera i valori degli input
                    $giorni = $_POST["giorni"];

                    if ($giorni == "Tutti") {
                        $queryTraining = $conn->prepare("SELECT id, gruppo, nome, giorno FROM allenamenti WHERE username=? ORDER BY giorno, gruppo ASC");
                        $queryTraining->bind_param("s", $_SESSION['username']);
                    } else {
                        $queryTraining = $conn->prepare("SELECT id, gruppo, nome, giorno FROM allenamenti WHERE username=? AND giorno=? ORDER BY gruppo ASC");
                        $queryTraining->bind_param("si", $_SESSION['username'], $giorni);
                    }
                    $queryTraining->execute();
                    $result = $queryTraining->get_result();

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td><input type="checkbox" name="selezionate[]" value="' . $row['id'] . '"></td>'; // Aggiunta casella di controllo per selezionare
                            echo '<td>' . $row['nome'] . '</td>';
                            echo '<td>' . $row['gruppo'] . '</td>';
                            echo '<td>' . $row['giorno'] . '</td>';
                            echo '</tr>';
                        }
                    } else {
                        if ($giorni == "Tutti") {
                            echo '<div style="color: black;">Non ci sono allenamenti nella tua scheda</div>';
                            echo '<p id="redirect-text" style="cursor: pointer; color: blue; text-decoration: underline;">Vuoi Aggiungere degli Allenamenti? <a href="addTraining.php">Aggiungili qui</a></p>';
                        } else {
                            echo '<div style="color: black;">Non ci sono allenamenti nella tua scheda nel giorno ' . $giorni . '</div>';
                            echo '<p id="redirect-text" style="cursor: pointer; color: blue; text-decoration: underline;">Vuoi Aggiungere degli Allenamenti al giorno ' . $giorni . '? <a href="addTraining.php">Aggiungili qui</a></p>';
                        }
                    }
                }
                ?>
            </tbody>
        </table>
        <input type="submit" value="Elimina selezionati"> <!-- Pulsante per eliminare gli allenamenti selezionati -->
    </form>
</body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Funzione per selezionare e deselezionare tutte le caselle di controllo
    function toggleSelectAll() {
        var checkboxes = document.getElementsByName('selezionate[]');
        var selectAllButton = document.getElementById('select-all-button');
        var isSelectAll = selectAllButton.getAttribute('data-select-all') === 'true';

        for (var i = 0; i < checkboxes.length; i++) {
            checkboxes[i].checked = !isSelectAll;
        }

        selectAllButton.setAttribute('data-select-all', isSelectAll ? 'false' : 'true');
        selectAllButton.textContent = isSelectAll ? 'Deseleziona tutto' : 'Seleziona tutto';
    }
</script>

</html>
