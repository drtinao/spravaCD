/**
 * Zkontroluje zadaný datum, pokud zadán neplatný, pak je vstup vymazán.
 * @param id id vstupního pole
 */
function kontrolujDatum(id){
    var zadany_datum = document.getElementById(id).value;
    var rozdeleny_datum = zadany_datum.split("."); //rozdělím datum dle tečky

    if(rozdeleny_datum.length != 3){ //pokud po rozdělení nedostaneme 3 části.. (chceme den, měsíc a rok)
        alert("Datum\nZadejte prosím datum ve formátu běžném pro ČR (den.měsíc.rok)!");
        document.getElementById(id).value = "";
        return false;
    }else{
         //mám 3 části, kontroluji smysluplnost jednotlivých částí
            if(!isCislo(rozdeleny_datum[0]) || !isCislo(rozdeleny_datum[1]) || !isCislo(rozdeleny_datum[2])){
                alert("Datum\nDatum nemůže obsahovat písmena, zkuste to znovu.");
                document.getElementById(id).value = "";
                return false;
            }else if(rozdeleny_datum[2] < 1){
                alert("Datum\nNeplatný rok, zadejte rok > 0.");
                document.getElementById(id).value = "";
                return false;
            }else if(rozdeleny_datum[1] > 12 || rozdeleny_datum[1] < 1){
                alert("Datum\nNeplatný měsíc, pořadí měsíce může nabývat pouze hodnot 1-12.");
                document.getElementById(id).value = "";
                return false;
            }else{//měsíc OK
                switch(rozdeleny_datum[1]){
                    case '1':
                    case '3':
                    case '5':
                    case '7':
                    case '8':
                    case '10':
                    case '12':
                        if(rozdeleny_datum[0] < 1 || rozdeleny_datum[0] > 31){
                            alert("Datum\nNeplatný den, zadaný měsíc má pouze 31 dnů.");
                            document.getElementById(id).value = "";
                            return false;
                        }
                        break;

                    case '4':
                    case '6':
                    case '9':
                    case '11':
                        if(rozdeleny_datum[0] < 1 || rozdeleny_datum[0] > 30){
                            alert("Datum\nNeplatný den, zadaný měsíc má pouze 30 dnů.");
                            document.getElementById(id).value = "";
                            return false;
                        }
                        break;

                    case '2':
                        //jedná se o přestupný rok či ne
                        if(rozdeleny_datum[2] % 4 == 0){ //přestupný
                            if(rozdeleny_datum[0] < 1 || rozdeleny_datum[0] > 29){
                                alert("Datum\nNeplatný den, zadaný měsíc má pouze 29 dnů (přestupný rok).");
                                document.getElementById(id).value = "";
                                return false;
                            }
                        }else{
                            if(rozdeleny_datum[0] < 1 || rozdeleny_datum[0] > 28){
                                alert("Datum\nNeplatný den, zadaný měsíc má pouze 28 dnů.");
                                document.getElementById(id).value = "";
                                return false;
                            }
                        }
                }

                if(rozdeleny_datum[2].length < 5) {
                    return true;
                }else{
                    alert("Datum\nZadejte prosím pouze čtyřcifernou číslici pro rok.");
                    document.getElementById(id).value = "";
                    return false;
                }
            }
    }
}

/**
 * Zkontroluje zadaný počet stop, požadováno > 0. Pokud zadán neplatný počet, pak je vymazán obsah vstupního pole.
 * @param id id vstupního pole
 */
function kontrolujPocetStop(id){
    var zadany_pocet = document.getElementById(id).value;

    if(!isCislo(zadany_pocet)){
        alert("Počet stop\nZadejte prosím celé číslo, které je > 0.");
        document.getElementById(id).value = "";
        return false;
    }else if(zadany_pocet < 1){
        alert("Počet stop\nPočet stop musí být > 0, zkuste to prosím znovu.");
        document.getElementById(id).value = "";
        return false;
    }else{
        return true;
    }
}

/**
 * Zkontroluje zadanou délku stopy. Je požadována délka ve formátu minuty:sekundy.
 * @param id id vstupního pole
 */
function kontrolujDelkaStopy(id){
    var zadana_delka = document.getElementById(id).value;
    var rozdelena_delka = zadana_delka.split(":"); //rozdělím čas dle dvojtečky

    if(rozdelena_delka.length != 2 || rozdelena_delka[1] > 59){//pokud nemáme 2 části (očekáván formát min:s)
        alert("Délka stopy\nDélka stopy musí být zadána ve formátu minuty:sekundy, zkuste to znovu.");
        document.getElementById(id).value = "";
        return false;
    }else if(!isCislo(rozdelena_delka[0]) || !isCislo(rozdelena_delka[1])){
        alert("Délka stopy\nDélka stopy musí obsahovat pouze celá čísla, zkuste to znovu.");
        document.getElementById(id).value = "";
        return false;
    }else if(rozdelena_delka[0] == 0 && rozdelena_delka[1] == 0){
        alert("Délka stopy\nStopa nemůže mít délku 0.");
        document.getElementById(id).value = "";
        return false;
    }else{
        return true;
    }
}

/**
 * Zkontroluje pořadí skladby v albu, pořadí musí být >= 1 - jinak vymazáno vstupní pole.
 * @param id id vstupního pole
 */
function kontrolujPoradiSkladbyAlbum(id){
    var zadane_poradi = document.getElementById(id).value;

    if(!isCislo(zadane_poradi)){
        alert("Pořadí skladby v albu\nZadejte prosím celé číslo, které je > 0.");
        document.getElementById(id).value = "";
        return false;
    }else if(zadane_poradi < 1){
        alert("Pořadí skladby v albu\nPočet stop musí být > 0, zkuste to prosím znovu.");
        document.getElementById(id).value = "";
        return false;
    }else{
        return true;
    }
}

/**
 * Zkontroluje, zda řetězec reprezantuje platné číslo.
 * @param vstup řetězec k testování
 * @returns {boolean} true, pokud vstupní řetězec reprezentuje číslo - jinak false
 */
function isCislo(vstup){
    var reg_vyraz = new RegExp('^[0-9]+$');
    return reg_vyraz.test(vstup);
}