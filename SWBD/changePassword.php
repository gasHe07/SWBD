<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="logo.png" type="image/png">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <title>Cambia Password</title>
</head>
<style>
    body {
        background-color: #f2f2f2;
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
    }

    .container {
        max-width: 400px;
        padding: 20px;
        background-color: #fff;
        border: 1px solid #ccc;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    }

    h1 {
        text-align: center;
        font-size: 20px;
        color: #333;
    }

    form {
        display: flex;
        flex-direction: column;
    }

    label {
        font-size: 16px;
        margin-bottom: 5px;
    }

    input[type="password"] {
        font-size: 14px;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        margin-bottom: 10px;
    }

    input[type="submit"] {
        font-size: 16px;
        background-color: #007BFF;
        color: #fff;
        border: none;
        border-radius: 5px;
        padding: 10px;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    input[type="submit"]:hover {
        background-color: #0056b3;
    }

    #message {
        text-align: center;
        margin-top: 10px;
        font-size: 14px;
        color: #ff0000;
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

    <div class="container">
        <h1>Cambia Password</h1>

        <?php
        session_start();
        include "database.php";

        // Verifica se l'utente è loggato
        if (!isset($_SESSION['username'])) {
            header("Location: PhysiMonitor.php");
            exit;
        }

        if (isset($_POST['changePassword'])) {
            $actualPassword = $_POST['actualPassword'];
            $newPassword = $_POST['newPassword'];
            $confirmPassword = $_POST['confirmPassword'];

            if ($newPassword == $confirmPassword) {
                $username = $_SESSION['username'];

                // Contatore dei tentativi di accesso errati
                if (!isset($_SESSION['attempts'])) {
                    $_SESSION['attempts'] = 0;
                }

                $queryActualPass = $conn->prepare("SELECT password FROM users WHERE username = ?");
                $queryActualPass->bind_param("s", $username);
                $queryActualPass->execute();
                $queryActualPass->store_result();

                if ($queryActualPass->num_rows == 1) {
                    $queryActualPass->bind_result($passwordDB);
                    $queryActualPass->fetch();

                    if (password_verify($actualPassword, $passwordDB)) {
                        $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
                        $updatePasswordQuery = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
                        $updatePasswordQuery->bind_param("ss", $newPasswordHash, $username);
                        $updatePasswordQuery->execute();

                        if ($updatePasswordQuery) {
                            echo '<div id="message" style="color: green;">Aggiornamento riuscito</div>';
                        } else {
                            echo '<div id="message" style="color: red;">Errore nell\'aggiornamento della password: ' . $conn->error . '</div>';
                        }
                    } else {
                        $_SESSION['attempts']++;
                        echo '<div id="message" style="color: red;">La password attuale non è corretta. Tentativo ' . $_SESSION['attempts'] . ' di 3.</div>';
                        if ($_SESSION['attempts'] >= 3) {
                            echo '<div id="message" style="color: red;">Hai superato il numero massimo di tentativi. Riprova più tardi.</div>';
                            header("Location: dashboard.php");
                        }
                    }
                } else {
                    echo '<div id="message" style="color: red;">Utente non trovato.</div>';
                }
            } else {
                echo '<div id="message" style="color: red;">La nuova password e la conferma non coincidono.</div>';
            }
        }
        //chiudo la connessione col database
        $conn->close();
        ?>

        <form method="post">
            <label for="actualPassword">Password Attuale:</label>
            <input type="password" id="actualPassword" name="actualPassword" required>

            <label for="newPassword">Nuova Password:</label>
            <input type="password" id="newPassword" name="newPassword" required>

            <label for="confirmPassword">Conferma Nuova Password:</label>
            <input type="password" id="confirmPassword" name="confirmPassword" required>

            <input type="submit" name="changePassword" value="Cambia Password">
        </form>
    </div>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>

</html>
