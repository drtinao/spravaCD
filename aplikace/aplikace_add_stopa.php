<?php
    include_once 'includes/databaze_pripojeni.inc.php';
    $databaze = new databaze_pripojeni;
    if(isset($_GET['id_album'])){
        $data_album = $databaze -> getAlbumByID($_GET['id_album']);
        $data_stopy = $databaze -> getStopyByAlbumID($_GET['id_album']);
    }

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
    <title>Přidání nové stopy do alba <?php echo $data_album['nazev']; ?></title>
</head>
<body>

<div class="jumbotron">
    <div class="container">
        <div class="text-center">
            <form method="get" action="aplikace_edit.php" onsubmit="return kontrolujDelkaStopy('nova_skladba_delka')">
                <h2 class="display-4">Přidání nové stopy do alba <?php echo $data_album['nazev']; ?></h2>
                <input type="hidden" value="<?php
                if(count($data_stopy) != 0){
                    echo $data_stopy[count($data_stopy) - 1]['poradi_album'] + 1;
                }else{
                    echo 1;
                }
                ?>" class="form-control" name="nova_skladba_poradi"><br>
                <input type="text" placeholder="název skladby" class="form-control" id="nova_skladba_nazev" name="nova_skladba_nazev" required autofocus><br>
                <input type="text" placeholder="délka skladby (minuty:sekundy)" class="form-control" id="nova_skladba_delka" name="nova_skladba_delka" required><br>
                <input type="hidden" name="id_album" value="<?php echo $data_album['id']; ?>">
                <button type="button" class="btn btn-danger" onClick="parent.location='aplikace_edit.php?id_album=<?php echo $data_album['id']; ?>'">zrušit</button>
                <input type="submit" class="btn btn-primary" value="uložit">
            </form>
        </div>
    </div>
</div>

</body>
</html>