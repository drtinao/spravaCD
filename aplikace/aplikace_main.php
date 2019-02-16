<?php
    include_once 'includes/databaze_pripojeni.inc.php';
    $databaze = new databaze_pripojeni;
    $data_alba = $databaze -> getAlbumAll();

    if(isset($_POST['albumID'])){ //pokud nebylo ukládání skladeb přerušeno, pak je uložím do DB
        $pruchod_skladby = 0;
        while(true){
            if(isset($_POST['poradi_album'.$pruchod_skladby])){
                $databaze -> addStopa($_POST['poradi_album'.$pruchod_skladby], $_POST['nazev_skladby'.$pruchod_skladby], $databaze -> minSecToSec($_POST['delka_skladby'.$pruchod_skladby]), $_POST['albumID']);
                $pruchod_skladby++;
            }else{
                break;
            }
        }
    }

    if(!isset($_SESSION)){
        session_start();
    }

    if($_SESSION['stav_prihlaseni'] == false){//pokud není přihlášen, pak přesměruji
        header('Location: index.php');
    }

    if(isset($_GET['album_id_smazat'])){//pokud se má smazat album
        $databaze -> removeAlbum($_GET['album_id_smazat']);
        header('Location: aplikace_main.php');
        $_SESSION['album_smazano'] = true;
    }
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>
<body>

<button type="button" class="btn btn-secondary" onClick="parent.location='index.php'">odhlásit se</button>
<button type="button" class="btn btn-primary" onClick="parent.location='aplikace_pridani_start.php'" style="float: right;">přidat album</button>

<div class="container">
    <?php
        if(isset($_GET['stranka'])){
            $zobrazena_stranka = $_GET['stranka'];
        }else{
            $zobrazena_stranka = 0;
        }

    for ($i = ($zobrazena_stranka * 5); $i < ($zobrazena_stranka * 5 + 5); $i++):
        if($i >= count($data_alba)){
            break;
        }
        $album_skladby = $databaze -> getStopyByAlbumID($data_alba[$i]['id']);
        $celkova_delka = 0;
        foreach($album_skladby as $skladba){
            $celkova_delka += $skladba['delka'];
        }?>
        <table class="table">
            <div class="text-center">
                <h2><u><?php echo $data_alba[$i]['autor'].' - '.$data_alba[$i]['nazev'] ?></u></h2>
                <h3>datum vydání: <?php echo $databaze -> convertDateToNorm($data_alba[$i]['datum_vydani']) ?></h3>
                <h3>celková délka: <?php echo $databaze -> secToMinSec($celkova_delka) ?></h3>
                <button type="button" class="btn btn-info" onclick="parent.location='aplikace_edit.php?id_album=<?php echo $data_alba[$i]['id'] ?>'">editovat</button>
            </div>
                <tr>
                   <th>pořadí v albu</th>
                    <th>název</th>
                    <th>délka [m:s]</th>
                </tr>
    <?php foreach ($album_skladby as $skladba): ?>
        <tr>
            <td><?php echo $skladba['poradi_album'] ?></td>
            <td><?php echo $skladba['nazev'] ?></td>
            <td><?php echo $databaze -> secToMinSec($skladba['delka']) ?></td>
        </tr>
    <?php endforeach; ?>
    <?php endfor; ?>
        </table>
</div>

<div class="text-center">
<?php
//stránkování
    $pocet_alb = count($data_alba);
    $pocet_stranek = $pocet_alb / 5;

    if($pocet_alb > 5){ ?>
        <h4>Přejít na stránku</h4>
    <?php } ?>
    <?php
    for ($i = 0; $i < $pocet_stranek; $i++):
        if($pocet_alb <= 5){
            break;
        }
?>

<?php
    if($i == $zobrazena_stranka){?>
        <button type="button" class="btn btn-success" onclick="parent.location='aplikace_main?stranka=<?php echo $i; ?>'"><?php echo ($i + 1) ?></button>
    <?php }else{ ?>
        <button type="button" class="btn btn-info" onclick="parent.location='aplikace_main?stranka=<?php echo $i; ?>'"><?php echo ($i + 1) ?></button>
    <?php } ?>
<?php endfor; ?>
</div>

<!-- Plovoucí okno + js pro zobrazení úspěšného přidání -->
<div class="modal fade" id="info" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Skladby uloženy</h4>
            </div>
            <div class="modal-body">
                <p>Přidání alba a skladeb bylo úspěšně dokončeno.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-success" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<!-- Plovoucí okno + js pro jeho zobrazení úspěšného přihlášení -->
<div class="modal fade" id="info_login" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Vítejte!</h4>
            </div>
            <div class="modal-body">
                <p>Přihlášení proběhlo úspěšně, vítejte v aplikaci pro správu CD.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-success" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<!-- Plovoucí okno + js pro jeho zobrazení úspěšného odebrání alba -->
<div class="modal fade" id="info_album_del" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Odstraněno</h4>
            </div>
            <div class="modal-body">
                <p>Album bylo úspěšně odstraněno.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-success" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<?php
    if(isset($_POST['albumID'])){
        echo "<script>$('#info').modal('show');</script>";
    }


    if(isset($_SESSION['welcome_zobrazeno']) && $_SESSION['welcome_zobrazeno'] == false){
        echo "<script>$('#info_login').modal('show');</script>";
        $_SESSION['welcome_zobrazeno'] = true;
    }

    if(isset($_SESSION['album_smazano']) && $_SESSION['album_smazano']){
        echo "<script>$('#info_album_del').modal('show');</script>";
        if(!isset($_GET['album_id_smazat'])){
            $_SESSION['album_smazano'] = false;
        }
    }
?>

</body>
</html>