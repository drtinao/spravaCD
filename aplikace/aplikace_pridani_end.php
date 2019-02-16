<?php
include_once 'includes/databaze_pripojeni.inc.php';

if(!isset($_SESSION)){
    session_start();
}

if($_SESSION['stav_prihlaseni'] == false){//pokud není přihlášen, pak přesměruji
    header('Location: index.php');
}

//nejprve uložím data z postu do db
$databaze = new databaze_pripojeni;
$databaze -> addAlbum($_POST['nazev'], $_POST['autor'], $databaze -> convertDateToDB($_POST['datum_vydani']));
?>

<!DOCTYPE html>
<html>
<head>
    <script type="text/javascript" src="validace.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <title>Přidání alba - 2/2</title>
</head>
<body>

<div class="jumbotron">
    <div class="container">
        <div class="text-center">
            <form method="post" action='aplikace_main.php' onsubmit="return <?php for ($i = 0; $i < $_POST['pocet_stop']; $i++): ?>kontrolujDelkaStopy('delka_skladby<?php echo $i ?>')<?php if($i != ($_POST['pocet_stop'] - 1)){
                echo " && ";
            }
                ?>
            <?php endfor; ?>">
                <h2 class="display-4">Přidání skladeb do alba - krok 2/2</h2>

                <?php for ($i = 0; $i < $_POST['pocet_stop']; $i++): ?>
                    <h3>Údaje pro skladbu č. <?php echo ($i + 1) ?></h3>
                    <input type="hidden" name="poradi_album<?php echo $i ?>" value="<?php echo $i+1 ?>"><br>
                    <input type="text" class="form-control" id="nazev_skladby<?php echo $i ?>"  name="nazev_skladby<?php echo $i ?>" placeholder="název skladby" required autofocus><br>
                    <input type="text" class="form-control" id="delka_skladby<?php echo $i ?>" name="delka_skladby<?php echo $i ?>" placeholder="délka skladby (minuty:sekundy)" required><br>
                <?php endfor; ?>
                <input type="hidden" name="albumID" value=<?php
                //do postu si uložím id přidaného alba (potřeba pro následné přidání skladeb)
                $id_album = $databaze -> getAlbumIDByParams($_POST['nazev'], $_POST['autor']);
                echo $id_album ?>>
                <button type="button" class="btn btn-danger" onClick="parent.location='aplikace_main.php'">zrušit přidání skladeb</button>
                <input type="submit" class="btn btn-primary" value="dokončit">
            </form>
        </div>
    </div>
</div>

<!-- Plovoucí okno + js pro jeho zobrazení -->
<div class="modal fade" id="modalinfo" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Album vytvořeno</h4>
            </div>
            <div class="modal-body">
                <p>Album bylo úspěšně vytvořeno, nyní pojďme přidat skladby.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-success" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>
<script> $('#modalinfo').modal('show'); </script>

</body>
</html>
