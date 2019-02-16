<?php

class databaze_pripojeni{
    private $db_server;
    private $db_login;
    private $db_heslo;
    private $db_nazevdb;
    private $znak_sada;

    private $pdo_ready;

    public function __construct(){
        $this -> pripojDB();
    }

    /**
     * Funkce zajistí základní nastavení a připojení k databázi.
     * @return PDO
     */
    public function pripojDB(){
        $this -> db_server = "localhost";
        $this -> db_login = "root";
        $this -> db_heslo = "";
        $this -> db_nazevdb = "spravacd_databaze";
        $this -> znak_sada = "utf8mb4";

        $data_zdroj = "mysql:host=".$this -> db_server.";dbname=".$this -> db_nazevdb.";charset=".$this -> znak_sada;
        $this -> pdo_ready = new PDO($data_zdroj, $this -> db_login, $this -> db_heslo);
    }

    /**
     * Vrátí id uživatele, kterému odpovídá předané jméno a heslo.
     * @param $login login požadovaného uživatele
     * @param $heslo heslo požadovaného uživatele
     * @return int id daného uživatele (-1, pokud neexistuje)
     */
    public function zkontrolujPrihlaseni($login, $heslo){
        $query = $this -> pdo_ready -> prepare("SELECT id FROM uzivatel WHERE login = ? AND heslo = ?");
        $query -> execute([$login, $heslo]);
        $id_uzivatele = $query -> fetch();

        if(!empty($id_uzivatele)){
            return $id_uzivatele['id'];
        }else{
            return -1;
        }
    }

    /**
     * Vrátí pole obsahující informace o nesmazaných albech.
     * @return array pole obsahující data o albech
     */
    public function getAlbumAll(){
        $alba_pole = array();

        $query = $this -> pdo_ready -> query("SELECT * FROM album WHERE smazano = 0");
        while($radek_db = $query -> fetch()){
            array_push($alba_pole, $radek_db);
        }

        return $alba_pole;
    }

    /**
     * Vrátí stopy, které obsahuje album specifikované pomocí id.
     * @param $album_id id alba, jehož stopy chceme vrátit
     * @return array pole obsahující informace o stopách z daného alba
     */
    public function getStopyByAlbumID($album_id){
        $skladby_pole = array();

        $query = $this -> pdo_ready -> prepare("SELECT * FROM stopa WHERE album_id = ? AND smazano = 0");
        $query -> execute([$album_id]);
        while($radek_db = $query -> fetch()){
            array_push($skladby_pole, $radek_db);
        }

        return $skladby_pole;
    }

    /**
     * Vrátí id alba, které má požadované parametry (název, autor).
     * @param $nazev_alba název požadovaného alba
     * @param $autor autor požadovaného alba
     * @return mixed id alba (-1, pokud nenalezeno)
     */
    public function getAlbumIDByParams($nazev_alba, $autor){
        $query = $this -> pdo_ready -> prepare("SELECT id FROM album WHERE nazev = ? AND autor = ? AND smazano = 0");
        $query -> execute([$nazev_alba, $autor]);
        $id_alba = $query -> fetch();

        if(!empty($id_alba)){
            return $id_alba['id'];
        }else{
            return -1;
        }
    }

    /**
     * Vrátí informace o albu s daným id z db.
     * @param $id_album id alba, o kterém chceme zjistit informace
     * @return mixed data o vybraném albu
     */
    public function getAlbumByID($id_album){
        $query = $this -> pdo_ready -> prepare("SELECT * FROM album WHERE id = ? AND smazano = 0");
        $query -> execute([$id_album]);
        $album = $query -> fetch();
        return $album;
    }

    /**
     * Přidá do DB tabulky "album" záznam o novém albu.
     * @param $nazev název alba
     * @param $autor autor alba
     * @param $datum_vydani datum vydání alba
     */
    public function addAlbum($nazev, $autor, $datum_vydani){
        $query = $this -> pdo_ready -> prepare("INSERT INTO album (nazev, autor, datum_vydani, smazano) VALUES (?, ?, ?, 0)");
        $query -> execute([$nazev, $autor, $datum_vydani]);
    }

    /**
     * Přidá do DB tabulky "stopa" záznam o nové stopě.
     * @param $poradi_album pořadí dané stopy v albu
     * @param $nazev název stopy
     * @param $delka_s délka stopy v sekundách
     * @param $id_album id alba, do kterého skladba náleží
     */
    public function addStopa($poradi_album, $nazev, $delka_s, $id_album){
        $query = $this -> pdo_ready -> prepare("INSERT INTO stopa (poradi_album, nazev, delka, smazano, album_id) VALUES (?, ?, ?, 0, ?)");
        $query -> execute([$poradi_album, $nazev, $delka_s, $id_album]);
    }

    /**
     * Odstraní z db informace o albu (a o skladbách v něm obsažených).
     * @param $id_album id alba, které chce uživatel smazat
     */
    public function removeAlbum($id_album){
        $query = $this -> pdo_ready -> prepare("DELETE FROM album WHERE id = ? AND smazano = 0");
        $query -> execute([$id_album]);
    }

    /**
     * Odstraní z db stopu, která má specifikované id.
     * @param $id_skladba id skladby, kterou chceme odstranit
     */
    public function removeSkladba($id_skladba){
        $query = $this -> pdo_ready -> prepare("DELETE FROM stopa WHERE id = ? AND smazano = 0");
        $query -> execute([$id_skladba]);
    }

    /**
     * Provede aktualizaci dat o skladbě uvnitř db.
     * @param $id_skladba id skladby
     * @param $poradi_album NOVÉ pořadí skladby v albu
     * @param $nazev NOVÝ název skladby
     * @param $delka NOVÁ délka skladby
     */
    public function updateSkladba($id_skladba, $poradi_album, $nazev, $delka){
        $query = $this -> pdo_ready -> prepare("UPDATE stopa SET poradi_album = ?, nazev = ?, delka = ? WHERE id = ? AND smazano = 0");
        $query -> execute([$poradi_album, $nazev, $this->minSecToSec($delka), $id_skladba]);
    }

    /**
     * Provede aktualizaci dat o albu uvnitř db.
     * @param $id_album id alba
     * @param $nazev NOVÝ název alba
     * @param $autor NOVÝ autor alba
     * @param $datum_vydani datum vydání alba (běžný formát pro ČR)
     */
    public function updateAlbum($id_album, $nazev, $autor, $datum_vydani){
        $query = $this -> pdo_ready -> prepare("UPDATE album SET nazev = ?, autor = ?, datum_vydani = ? WHERE id = ? AND smazano = 0");
        $query -> execute([$nazev, $autor, $this->convertDateToDB($datum_vydani), $id_album]);
    }

    /**
     * Z db zjistí informace o skladbě s daným id.
     * @param $id_stopa id stopy, o které chceme zjistit info
     * @return mixed informace o dané stopě
     */
    public function getStopaById($id_stopa){
        $query = $this -> pdo_ready -> prepare("SELECT * FROM stopa WHERE id = ? AND smazano = 0");
        $query -> execute([$id_stopa]);
        $stopa = $query -> fetch();
        return $stopa;
    }

    /**
     * Funkce převede sekundy na řetězec reprezentující čas ve formátu min:s
     * @param $sekundy počet sekund
     * @return int|string textový řetězec reprezentující čas
     */
    public function secToMinSec($sekundy){
            $minuty = (int)($sekundy / 60);
            $sekundy = $sekundy % 60;

            if($sekundy >= 10) {
                return $minuty . ':' . $sekundy;
            }else{
                return $minuty . ':0' . $sekundy;
            }
    }

    /**
     * Převede čas ve formátu m:s na sekundy.
     * @param $cas textový řetězec reprezentující čas (m:s)
     * @return float|int čas v sekundách
     */
    public function minSecToSec($cas){
        if(!strpos($cas, ":")){//máme jen sekundy
            return $cas;
        }else{
            $rozdelene_min_sec = explode(":", $cas); //minuty:sekundy
            $cas_sekundy = $rozdelene_min_sec[0] * 60;
            $cas_sekundy += $rozdelene_min_sec[1];
            return $cas_sekundy;
        }
    }
    /**
     * Převede datum z databáze na datum běžné v ČR (den.měsíc.rok).
     * @param $datum_sql datum z db ve formátu rok-měsíc-den
     * @return string textový řetězec reprezentující datum
     */
    public function convertDateToNorm($datum_sql){
        $rozdeleny_datum_sql = explode("-", $datum_sql); //rok-mesic-den
        $pruchod_pole = 0;
        foreach($rozdeleny_datum_sql as $datum_cast){ //odstraním případné 0
            if($datum_cast[0] == 0){ //odstraním přebytečnou počáteční nulu
                $rozdeleny_datum_sql[$pruchod_pole] = substr($datum_cast,1, strlen($datum_cast));
            }
            $pruchod_pole++;
        }
        return $rozdeleny_datum_sql[2].'.'.$rozdeleny_datum_sql[1].'.'.$rozdeleny_datum_sql[0];
    }

    public function convertDateToDB($datum_uzivatel){
        $rozdeleny_datum_uzivatel = explode(".", $datum_uzivatel); //den.mesic.rok
        return $rozdeleny_datum_uzivatel[2].'-'.$rozdeleny_datum_uzivatel[1].'-'.$rozdeleny_datum_uzivatel[0];
    }

    /**
     * Na základě údajů v session zjistí, jestli se uživatel již úspěšně přihlásil nebo ne. Pokud ano, pak ho přesměruje do aplikace.
     * @return bool true, pokud uživatel přihlášen - jinak false
     */
    public function isUzivatelPrihlasen(){
        if(!isset($_SESSION)){
            session_start();
        }
        if(isset($_SESSION['stav_prihlaseni']) && $_SESSION['stav_prihlaseni'] == true){
            header('Location: aplikace_main.php');
            return true;
        }else{
            return false;
        }
    }

    /**
     * Provede nastavení hodnoty proměnné v session, která značí stav přihlášení uživatele.
     * @param $hodnota true, pokud má být uživatel přihlášen - jinak false
     */
    public function setUzivatelPrihlasen($hodnota){
        if(!isset($_SESSION)){
            session_start();
        }
        $_SESSION['stav_prihlaseni'] = $hodnota;
        if($hodnota == true){
            $_SESSION['welcome_zobrazeno'] = false;
            $_SESSION['drive_prihlasen'] = true;
            header('Location: aplikace_main.php');
            exit;
        }
    }
}

