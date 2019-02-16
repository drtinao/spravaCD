<?php
    if(!isset($_SESSION)){
        session_start();
    }

    if($_SESSION['stav_prihlaseni'] == false){//pokud není přihlášen, pak přesměruji
        header('Location: index.php');
    }
?>

<!DOCTYPE html>
<html>
<head>
    <script type="text/javascript" src="validace.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <title>Přidání alba - 1/2</title>
</head>
<body>

<div class="jumbotron">
    <div class="container">
        <div class="text-center">
            <form method="post" action='aplikace_pridani_end.php' onsubmit="return kontrolujDatum('datum_vydani') && kontrolujPocetStop('pocet_stop')">
                <h2 class="display-4">Přidání alba - krok 1/2</h2>
                <input type="text" class="form-control" id="nazev" name="nazev" placeholder="název alba" required autofocus><br>
                <input type="text" class="form-control" id="autor" name="autor" placeholder="autor alba" required><br>
                <input type="text" class="form-control" id="datum_vydani" name="datum_vydani" placeholder="datum vydání" required><br>
                <input type="number" class="form-control" id="pocet_stop" name="pocet_stop" placeholder="počet stop" required><br>

                <button type="button" class="btn btn-danger" onClick="parent.location='aplikace_main.php'">zpět</button>
                <input type="submit" class="btn btn-primary" value="pokračovat">
            </form>
        </div>
    </div>
</div>

</body>
</html>