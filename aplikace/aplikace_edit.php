<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>
<body>

<button type="button" class="btn btn-primary" onClick="parent.location='aplikace_main.php'">zpět na prohlížení</button>
<div class="container">
    <?php
    include_once 'includes/databaze_pripojeni.inc.php';
    if(!isset($_SESSION)){
        session_start();
    }

    if($_SESSION['stav_prihlaseni'] == false){//pokud není přihlášen, pak přesměruji
        header('Location: index.php');
    }
    $databaze = new databaze_pripojeni;
    if(isset($_GET['skladba_id_smazat'])){
       $databaze -> removeSkladba($_GET['skladba_id_smazat']);
        header('Location: aplikace_edit.php?id_album='.$_GET['id_album']);
        $_SESSION['skladba_smazana'] = true;
    }

    if(isset($_GET['skladba_nazev_edit'])){
        $databaze -> updateSkladba($_GET['skladba_id_edit'], $_GET['skladba_poradi_edit'], $_GET['skladba_nazev_edit'], $_GET['skladba_delka_edit'], $_GET['id_album']);
        header('Location: aplikace_edit.php?id_album='.$_GET['id_album']);
        $_SESSION['skladba_editovana'] = true;
    }

    if(isset($_GET['datum_vydani_edit'])){
        $databaze -> updateAlbum($_GET['id_album'], $_GET['nazev_edit'], $_GET['autor_edit'], $_GET['datum_vydani_edit']);
        header('Location: aplikace_edit.php?id_album='.$_GET['id_album']);
        $_SESSION['album_editovano'] = true;
    }

    if(isset($_GET['nova_skladba_poradi'])){
        $databaze -> addStopa($_GET['nova_skladba_poradi'], $_GET['nova_skladba_nazev'], $databaze -> minSecToSec($_GET['nova_skladba_delka']), $_GET['id_album']);
        header('Location: aplikace_edit.php?id_album='.$_GET['id_album']);
        $_SESSION['nova_stopa_pridana'] = true;
    }

    $album = $databaze -> getAlbumByID($_GET['id_album']);
    $album_skladby = $databaze -> getStopyByAlbumID($_GET['id_album']);
    $celkova_delka = 0;
    foreach($album_skladby as $skladba){
        $celkova_delka += $skladba['delka'];
    }?>
    <table class="table">
        <div class="text-center">
            <h2><u><?php echo $album['autor'].' - '.$album['nazev'] ?></u></h2>
            <h3>datum vydání: <?php echo $databaze -> convertDateToNorm($album['datum_vydani']) ?></h3>
            <h3>celková délka: <?php echo $databaze -> secToMinSec($celkova_delka) ?></h3>
            <button type="button" class="btn btn-outline-primary" onclick="parent.location='aplikace_edit_album?id_album=<?php echo $_GET['id_album']; ?>'">editovat údaje o albu</button>
            <button type="button" class="btn btn-primary" onclick="parent.location='aplikace_add_stopa?id_album=<?php echo $_GET['id_album']; ?>'">přidat další skladbu do alba</button>
            <button type="button" class="btn btn-danger" onclick="parent.location='aplikace_main.php?album_id_smazat=<?php echo $_GET['id_album']; ?>'">odstranit celé album</button>
        </div>
        <tr>
            <th>pořadí v albu</th>
            <th>název</th>
            <th>délka [m:s]</th>
            <th>editovat skladbu</th>
            <th>odstranit skladbu</th>
        </tr>
        <?php foreach ($album_skladby as $skladba): ?>
            <tr>
                <td><?php echo $skladba['poradi_album'] ?></td>
                <td><?php echo $skladba['nazev'] ?></td>
                <td><?php echo $databaze -> secToMinSec($skladba['delka']) ?></td>
                <td><button type="button" class="btn btn-outline-primary btn-sm" onclick="parent.location='aplikace_edit_stopa.php?id_stopa=<?php echo $skladba['id']; ?>'">editovat skladbu</button></td>
                <td><button type="button" class="btn btn-danger btn-sm" onclick="parent.location='aplikace_edit.php?id_album=<?php echo $_GET['id_album'].'&'; ?>skladba_id_smazat=<?php echo $skladba['id']; ?>'">odstranit skladbu</button></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>

<!-- Plovoucí okno + js pro zobrazení úspěšného odebrání skladby -->
<div class="modal fade" id="info_skladba_smazana" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Skladba smazána</h4>
            </div>
            <div class="modal-body">
                <p>Odebrání skladby proběhlo úspěšně.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-success" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<!-- Plovoucí okno + js pro zobrazení úspěšné editace skladby -->
<div class="modal fade" id="info_skladba_editovana" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Skladba upravena</h4>
            </div>
            <div class="modal-body">
                <p>Údaje o skladbě byly úspěšně aktualizovány.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-success" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<!-- Plovoucí okno + js pro zobrazení úspěšné aktualizace alba -->
<div class="modal fade" id="info_album_editovano" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Album aktualizováno</h4>
            </div>
            <div class="modal-body">
                <p>Základní informace o albu byly úspěšně aktualizovány.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-success" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<!-- Plovoucí okno + js pro zobrazení úspěšného přidání skladby -->
<div class="modal fade" id="info_skladba_pridana" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Skladba přidána</h4>
            </div>
            <div class="modal-body">
                <p>Nová skladba byla úspěšně přidána do alba.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-success" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<?php
    if(isset($_SESSION['skladba_smazana']) && $_SESSION['skladba_smazana']){
        echo "<script>$('#info_skladba_smazana').modal('show');</script>";
        if(!isset($_GET['skladba_id_smazat'])){
            $_SESSION['skladba_smazana'] = false;
        }
    }

if(isset($_SESSION['skladba_editovana']) && $_SESSION['skladba_editovana']){
    echo "<script>$('#info_skladba_editovana').modal('show');</script>";
    if(!isset($_GET['skladba_id_edit'])){
        $_SESSION['skladba_editovana'] = false;
    }
}

if(isset($_SESSION['album_editovano']) && $_SESSION['album_editovano']){
    echo "<script>$('#info_album_editovano').modal('show');</script>";
    if(!isset($_GET['datum_vydani_edit'])){
        $_SESSION['album_editovano'] = false;
    }
}

if(isset($_SESSION['nova_stopa_pridana']) && $_SESSION['nova_stopa_pridana']){
    echo "<script>$('#info_skladba_pridana').modal('show');</script>";
    if(!isset($_GET['nova_skladba_poradi'])){
        $_SESSION['nova_stopa_pridana'] = false;
    }
}
?>

</html>