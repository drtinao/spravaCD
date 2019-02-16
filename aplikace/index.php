<?php
    if(!isset($_SESSION)){
        session_start();
    }
    include_once 'includes/databaze_pripojeni.inc.php';
    $_SESSION['obnoveni_prihlaseni'] = false;
    $databaze = new databaze_pripojeni;
    if(!$databaze -> isUzivatelPrihlasen()){//pokud uživatel doposud není přihlášen, pak kontrola zadaných údajů
        if(isset($_POST['login']) && isset($_POST['heslo']) && $databaze -> zkontrolujPrihlaseni($_POST['login'], $_POST['heslo']) != -1){ //pokud zadány správné přihlaš. údaje
            $databaze -> setUzivatelPrihlasen(true);
        }else if(isset($_POST['login'])){
            $_SESSION['spatny_login'] = true;
        }
    }else{
        $databaze -> setUzivatelPrihlasen(false);
        header('Location: index.php');
        $_SESSION['obnoveni_prihlaseni'] = true;
    }
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <title>Přihlášení</title>
</head>
<body>
<div class="jumbotron">
    <div class="container">
        <div class="text-center">
        <form method="post">
            <h2 class="display-4">Přihlášení do aplikace</h2>
            <input type="text" class="form-control" name="login" placeholder="login" required autofocus><br>
                <input type="password" class="form-control" name="heslo" placeholder="heslo" required><br>
                <input type="hidden" name="prihlaseni_ok" value="true">
            <input type="submit" class="btn btn-primary" value="Přihlásit">
        </form>
        </div>
    </div>
</div>

<!-- Plovoucí okno + js pro zobrazení úspěšného odhlášení -->
<div class="modal fade" id="info_logout" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Odhlášení</h4>
            </div>
            <div class="modal-body">
                <p>Odhlášení proběhlo úspěšně, nashledanou.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-success" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<!-- Plovoucí okno + js pro zobrazení neúspěšného přihlášení -->
<div class="modal fade" id="info_bad_log" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Přihlášení</h4>
            </div>
            <div class="modal-body">
                <p>Bylo zadané špatné jméno nebo heslo, zkuste to znovu.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-success" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>
<?php
    if(isset($_SESSION['drive_prihlasen']) && $_SESSION['drive_prihlasen'] && !$_SESSION['obnoveni_prihlaseni'] && isset($_SESSION['obnoveni_prihlaseni'])){
        echo "<script>$('#info_logout').modal('show');</script>";
        $_SESSION['drive_prihlasen'] = false;
        $_SESSION['obnoveni_prihlaseni'] = false;
    }else if(isset($_SESSION['spatny_login']) && $_SESSION['spatny_login']){
        echo "<script>$('#info_bad_log').modal('show');</script>";
        $_SESSION['spatny_login'] = false;
    }
?>

</body>
</html>