<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Errore</title>
</head>

<body>
    <h1>Errore</h1>
    <p>Si è verificato un errore durante l'elaborazione della richiesta.</p>
    <script>
        // Rendi automatico il reindirizzamento dopo 5 secondi
        setTimeout(function() {
            window.location.href = 'dashboard.php';
        }, 5000); // 5000 millisecondi = 5 secondi
    </script>
    <p>Se non vieni reindirizzato automaticamente, <a href="dashboard.php">clicca qui</a> per tornare alla home.</p>
</body>

</html>
