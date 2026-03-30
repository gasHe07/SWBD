<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="logo.png" type="image/png">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <title>Aggiungi Allenamenti</title>
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

    <div class="container mt-5">
        <br><br>
        <h2 class="mb-4">Aggiungi Allenamenti</h2>
        <form method="POST" action="processTraining.php" id="trainingForm">
            <div id="trainingInputs">
                <div class="mb-3">
                    <label for="nome1" class="form-label">Nome</label>
                    <input type="text" class="form-control" id="nome1" name="nome[]" required>
                </div>
                <div class="mb-3">
                    <label for="gruppo1" class="form-label">Gruppo</label>
                    <input type="text" class="form-control" id="gruppo1" name="gruppo[]" required>
                </div>
                <div class="mb-3">
                    <label for="giorno1" class="form-label">Giorno</label>
                    <input type="number" class="form-control" min="1" max="7" id="giorno1" name="giorno[]" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <button type="button" class="btn btn-success btn-block" onclick="addTraining()">Aggiungi Altro Allenamento</button>
                </div>
                <div class="col-md-6 mb-3">
                    <button type="submit" class="btn btn-primary btn-block">Aggiungi</button>
                </div>
            </div>
        </form>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <script>
        function addTraining() {
            var count = $("#trainingInputs").children().length / 3 + 1;
            var newTrainingInputs = `
                <div class="mb-3">
                    <label for="nome${count}" class="form-label">Nome</label>
                    <input type="text" class="form-control" id="nome${count}" name="nome[]" required>
                </div>
                <div class="mb-3">
                    <label for="gruppo${count}" class="form-label">Gruppo</label>
                    <input type="text" class="form-control" id="gruppo${count}" name="gruppo[]" required>
                </div>
                <div class="mb-3">
                    <label for="giorno${count}" class="form-label">Giorno</label>
                    <input type="number" class="form-control" min="1" max="7" id="giorno${count}" name="giorno[]" required>
                </div>
            `;
            $("#trainingInputs").append(newTrainingInputs);
        }
    </script>
</body>

</html>
