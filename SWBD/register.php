<?php
// Include the database connection file
include 'database.php';

// Check if the form has been submitted
if (isset($_POST['submit'])) {
    // Sanitize the user inputs
    $nome = mysqli_real_escape_string($conn, $_POST['nome']);
    $cognome = mysqli_real_escape_string($conn, $_POST['cognome']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $altezza = intval($_POST['altezza']);
    $pesoattuale = floatval($_POST['pesoattuale']);
    $desiderato = floatval($_POST['desiderato']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Check if the username already exists in the database
    $checkUsernameQuery = "SELECT username FROM users WHERE username = ?";
    $checkStmt = mysqli_prepare($conn, $checkUsernameQuery);

    if ($checkStmt) {
        mysqli_stmt_bind_param($checkStmt, "s", $username);
        mysqli_stmt_execute($checkStmt);
        mysqli_stmt_store_result($checkStmt);

        if (mysqli_stmt_num_rows($checkStmt) > 0) {
            // Username already exists, display an error message
            echo '<div id="message" style="color: green;">Username già esistente. Scegli un altro nome utente.</div>';
        } else {
            // Username is unique, proceed with user registration
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            // Insert user data into the 'users' table
            $insertUserQuery = "INSERT INTO users (nome, cognome, username, altezza, pesoattuale, pesodesiderato, password) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $insertUserQuery);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "sssidds", $nome, $cognome, $username, $altezza, $pesoattuale, $desiderato, $passwordHash);
                mysqli_stmt_execute($stmt);

                // Insert user's current weight into the 'peso' table
                $insertPesoQuery = "INSERT INTO peso (username, data, peso) VALUES (?, NOW(), ?)";
                $stmtPeso = mysqli_prepare($conn, $insertPesoQuery);

                if ($stmtPeso) {
                    mysqli_stmt_bind_param($stmtPeso, "sd", $username, $pesoattuale);
                    mysqli_stmt_execute($stmtPeso);

                    // Redirect the user to the login page
                    header('Location: PhysiMonitor.php');
                } else {
                    // Handle the SQL error for weight insertion  
                    echo '<div id="message" style="color: red;">Errore di inserimento nella tabella peso.</div>';

                }
            } else {
                // Handle the SQL error for user registration
                echo '<div id="message" style="color: red;">Errore di inserimento nel Database.</div>';
            }
        }
    } else {
        // Handle the SQL error for username check 
        echo '<div id="message" style="color: red;">Errore nella verifica dello username.</div>';
    }
}

// Display the registration form
?>


<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="start.css">
    <title>PhysiMonitor-Register</title>
    <link rel="icon" href="logo.png" type="image/png">
</head>
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
<body>
    <form method="post" action="register.php">
        <h2 >Registrazione</h2>
        <label for="nome">Nome:</label>
        <input type="text" name="nome" id="nome" required minlength="4" maxlength="12"><br><br>

        <label for="cognome">Cognome:</label>
        <input type="text" name="cognome" id="cognome" required minlength="4" maxlength="12"><br><br>

        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required minlength="4" maxlength="15"><br><br>

        <label for="altezza">Altezza In cm:</label>
        <input type="number" id="altezza" name="altezza" step="1" min="100" max="245" required><br><br>

        <label for="pesoattuale">Peso Attuale:</label>
        <input type="number" id="pesoattuale" name="pesoattuale" step="0.1" min="30" required><br><br>

        <label for="desiderato">Peso Desiderato:</label>
        <input type="number" id="desiderato" name="desiderato" step="0.1" min="30" max="110" required><br><br>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required minlength="8"><br><br>

        <input type="submit" name="submit" value="Register">
        <input type="reset" name="cancella" value="Annulla" />
    </form>
</body>

</html>