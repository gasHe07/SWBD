<?php
// Include the database connection file
include 'database.php';


// Contatore dei tentativi di accesso errati
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}

// Verifica se l'utente è già autenticato
if (isset($_SESSION['user_id'])) {
    // Utente già autenticato, reindirizzalo alla pagina di accesso
    header('Location: dashboard.html');
    exit();
}

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Controlla i tentativi di accesso errati
    if ($_SESSION['login_attempts'] >= 3) {
        echo '<div style="color: red;">Hai superato il numero massimo di tentativi di accesso falliti. Riprova più tardi.</div>';
    } else {
        // Query per selezionare l'utente dal database
        $query = "SELECT username, password FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) == 1) {
                mysqli_stmt_bind_result($stmt, $db_username, $db_password);
                mysqli_stmt_fetch($stmt);

                // Verifica la password
                if (password_verify($password, $db_password)) {
                    //avvio la sessione 
                    session_start();
                    // Imposta il timeout a 0 per sessioni persistenti
                    session_set_cookie_params(0, '/', '', false, true);
                    // Accesso riuscito, imposta la sessione e il session ID
                    session_regenerate_id(); // Genera un nuovo session ID
                    $_SESSION['username'] = $db_username;
                    $_SESSION['login_attempts'] = 0; // Reimposta i tentativi di accesso

                    // Reindirizza l'utente alla dashboard o alla pagina successiva
                    header('Location: dashboard.php');
                } else {
                    // Password errata, incrementa il conteggio dei tentativi di accesso errati
                    $_SESSION['login_attempts']++;
                    echo '<div style="color: red;">Password errata. Tentativo ' . $_SESSION['login_attempts'] . '</div>';
                }
            } else {
                // Username non trovato, incrementa il conteggio dei tentativi di accesso errati
                $_SESSION['login_attempts']++;
                
            }
        } else {
            // Annulla le operazioni precedenti in caso di errore nella query SQL
            echo '<div style="color: red;">Errore nella richiesta al Database, fai da capo.</div>';
            exit(); // Stop execution
        }
    }
}
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="start.css">
    <title>PhysiMonitor - Login</title>
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
    <form method="post" action="PhysiMonitor.php">
        <h2>Login</h2>
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required><br><br>

        <input type="submit" name="login" value="Login">
        <input type="reset" name="cancella" value="Annulla" style="color :darkred" />

        <p id="redirect-text" style="cursor: pointer; color: blue; text-decoration: underline;">Non sei registrato?
            <a href="register.php">Registrati qui</a>
        </p>
    </form>
</body>

</html>