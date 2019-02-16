<?php
    include_once 'includes/databaze_pripojeni.inc.php';
    if(!isset($_SESSION)){
        session_start();
    }

    if($_SESSION['stav_prihlaseni'] == false){//pokud není přihlášen, pak přesměruji
        header('Location: index.php');
    }

    //nejprve z db vytáhnu informace o dané stopě
    $databaze = new databaze_pripojeni;
    if(isset($_GET['id_stopa'])){
        $data = $databaze -> getStopaById($_GET['id_stopa']);
    }
?>

<!DOCTYPE html>
<html>
<head>
    <script type="text/javascript" src="validace.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <title>Editace stopy č. <?php echo $data['poradi_album']; ?></title>
</head>
<body>

<div class="jumbotron">
    <div class="container">
        <div class="text-center">
            <form method="get" action="aplikace_edit.php" onsubmit="return kontrolujDelkaStopy('skladba_delka_edit')">
                <h2 class="display-4">Editace stopy č. <?php echo $data['poradi_album']; ?></h2>
                <input type="hidden" value="<?php echo $data['poradi_album']; ?>" class="form-control" name="skladba_poradi_edit"><br>
                <label for="skladba_nazev_edit">Název skladby</label>
                <input type="textarea" value="<?php echo $data['nazev']; ?>" class="form-control" id="skladba_nazev_edit" name="skladba_nazev_edit" required autofocus><br>
                <label for="skladba_delka_edit">Délka skladby</label>
                <input type="textarea" value="<?php echo $databaze -> secToMinSec($data['delka']); ?>" class="form-control" id="skladba_delka_edit" name="skladba_delka_edit" required><br>
                <input type="hidden" name="skladba_id_edit" value="<?php echo $data['id']; ?>">
                <input type="hidden" name="id_album" value="<?php echo $data['album_id']; ?>">
                <button type="button" class="btn btn-danger" onClick="parent.location='aplikace_edit.php?id_album=<?php echo $data['album_id']; ?>'">zrušit</button>
                <input type="submit" class="btn btn-primary" value="uložit">
            </form>
        </div>
    </div>
</div>

</body>
</html>