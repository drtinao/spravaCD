<?php
include_once 'includes/databaze_pripojeni.inc.php';

if(!isset($_SESSION)){
    session_start();
}

if($_SESSION['stav_prihlaseni'] == false){//pokud není přihlášen, pak přesměruji
    header('Location: index.php');
}

$databaze = new databaze_pripojeni; //z db zjistím informace o albu
if(isset($_GET['id_album'])){
    $data = $databaze -> getAlbumByID($_GET['id_album']);
}
?>

<!DOCTYPE html>
<html>
<head>
    <script type="text/javascript" src="validace.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <title>Editace alba</title>
</head>
<body>

<div class="jumbotron">
    <div class="container">
        <div class="text-center">
            <form method="get" action="aplikace_edit.php" onsubmit="return kontrolujDatum('datum_vydani_edit')">
                <h2 class="display-4">Editace alba</h2>
                <label for="nazev_edit">Název alba</label>
                <input type="textarea" value="<?php echo $data['nazev']; ?>" class="form-control" id="nazev_edit" name="nazev_edit" required autofocus><br>
                <label for="autor_edit">Jméno autora</label>
                <input type="textarea" value="<?php echo $data['autor']; ?>" class="form-control" id="autor_edit" name="autor_edit" required><br>
                <label for="datum_vydani_edit">Datum vydání</label>
                <input type="textarea" value="<?php echo $databaze -> convertDateToNorm($data['datum_vydani']); ?>" class="form-control" id="datum_vydani_edit" name="datum_vydani_edit" required><br>
                <input type="hidden" name="id_album" value="<?php echo $data['id']; ?>">
                <button type="button" class="btn btn-danger" onClick="parent.location='aplikace_edit.php?id_album=<?php echo $data['id']; ?>'">zrušit</button>
                <input type="submit" class="btn btn-primary" value="uložit">
            </form>
        </div>
    </div>
</div>

</body>
</html>