<?php

function nomEntete($mail, $pseudo) {
    //FERMETURE DE LA CONNEXION
    if(is_file('../class/Manager.php')){
        include_once '../class/Manager.php';
    }else{
        include_once 'class/Manager.php';
    }
    $db = BD::connecter();
    $manager = new Manager($db);
    $param = array($mail, $pseudo);
    $array = $manager->getListbyArray("SELECT u.nom,u.prenom FROM loginpassword l, utilisateur u  WHERE l.idlogin = u.idlogin_loginpassword AND l.mail=? and l.pseudo=?", $param);    
    $_SESSION['nomConnect'] = str_replace("''", "'", $array[0][0]);
    $_SESSION['prenomConnect'] = str_replace("''", "'", $array[0][1]);
    //FERMETURE DE LA CONNEXION
    BD::deconnecter();
}

function createJsonCentrale($requete, $chemin, $nomselect, $libelleattribut1, $libelleattribut2) {

    $db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
    $manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
    $row = $manager->getList($requete);
    //$fp = fopen($chemin . '/' . $nomselect, 'w');
    if (file_exists($chemin . '/' . $nomselect)) {
        $fp = fopen($chemin . '/' . $nomselect, 'w');
    } else {
        $fp = fopen($chemin . '/' . $nomselect, 'a+');
    }
    fwrite($fp, 'data: [');
    fwrite($fp, '{label:"<?php echo TXT_CHOIXCODEUNITE; ?>",id:" * "},');
    for ($i = 0; $i < count($row); $i++) {
        fputs($fp, json_encode($row[$i]));
        fwrite($fp, '');
        fwrite($fp, ',');
    }
    fwrite($fp, '{label:"<?php echo TXT_AUTRES; ?>",id:" "}');
    fwrite($fp, ',{label:"<?php echo TXT_AUCUN; ?>",id:"  "}');
    fwrite($fp, ']');
    $json_file = $chemin . '/' . $nomselect;
    $json1 = file_get_contents($json_file);
    $json2 = str_replace(',]', ']', $json1);
    $json3 = str_replace($libelleattribut1, 'label', $json2);
    $json4 = str_replace($libelleattribut2, 'id', $json3);
    $json5 = str_replace('"label"', 'label', $json4);
    $json = str_replace('"id"', 'id', $json5);
    file_put_contents($json_file, $json);
    fclose($fp);
    $data = file_get_contents($chemin . '/' . $nomselect);
    BD::deconnecter();
}

function pageEncours($url) {
    //if(	$url == $_SERVER['REQUEST_URI']){
    $reg = '#^(.+[\\\/])*([^\\\/]+)$#';
    $pageencours = preg_replace($reg, '$2', $url);
    $pageencours1 = str_replace("/", "", $pageencours);
    return $pageencours1;
    //}
}

function modifDroit($mail, $passe, $idUser, $droit) {
    // récupération du role de l'utilisateur
    $reqidTyte = "
    SELECT t.idtypeutilisateur FROM loginpassword l, typeutilisateur t, utilisateur u
    WHERE
    l.idlogin = u.idlogin_loginpassword AND
    u.idtypeutilisateur_typeutilisateur = t.idtypeutilisateur
    AND l.mail ='" . $mail . "' and l.motpasse='" . $passe . "'";
    $residTyte = pg_query($reqidTyte);
    if (!$residTyte) {
        echo "Erreur durant la requête.\n";
        exit;
    } else {
        $rowidTyte = pg_fetch_row($residTyte);
        $idTyte = $rowidTyte[0];
    }
    if ($idTyte == '1') {
        $requpdateUser = "
            UPDATE utilisateur SET idtypeutilisateur_typeutilisateur='" . $droit . "'
            WHERE idutilisateur='" . $idUser . "'";
        $resupdateUser = pg_query($requpdateUser);
        if (!$resupdateUser) {
            pg_query("ROLLBACK");
        } else {
            pg_query("COMMIT");
        }
    }
}

function createNumProjet($n) {
    $numeroextraite = explode("-", $n);
    date_default_timezone_set('Europe/London');
    $tab = substr($numeroextraite[2], 0);
    $numero = $tab[0] . $tab[1] . $tab[2] . $tab[3] . $tab[4];
    $numero = intval($numero) . '<br>';
    $numero = $numero + 1;
    if ($numero < 10) {
        $taille = 1;
    } elseif ($numero >= 10 && $numero < 100) {
        $taille = 2;
    } elseif ($numero >= 100 && $numero < 1000) {
        $taille = 3;
    } elseif ($numero >= 1000 && $numero < 10000) {
        $taille = 4;
    } elseif ($numero >= 10000 && $numero < 99999) {
        $taille = 5;
    }
    if ($taille == 1) {//0-9
        $numero = intval($numero);
        $numProjet = 'P-' . date("y") . '-' . '0000' . $numero;
    } elseif ($taille == 2) {//10-99
        $numero = intval($numero);
        $numProjet = 'P-' . date("y") . '-' . '000' . $numero;
    } elseif ($taille == 3) {//100-999
        $numero = intval($numero);
        $numProjet = 'P-' . date("y") . '-' . '00' . $numero;
    } elseif ($taille == 4) {//1000-9999
        $numero = intval($numero);
        $numProjet = 'P-' . date("y") . '-' . '0' . $numero;
    } elseif ($taille == 5) {//1000-9999
        $numero = intval($numero);
        $numProjet = 'P-' . date("y") . '-' . $numero;
    }
    return $numProjet;
}

function stripAccents($string) {
    return strtr($string, 'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ', 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
}

function getDirectoryList($directory) {// fonction qui permet de lister les fichiers d'un répertoire distant
    $results = array();
    $handler = opendir($directory);
    while ($file = readdir($handler)) {
        if ($file != "." && $file != "..") {
            $results[] = $file;
        }
    }
    closedir($handler);
    return $results;
}

function check_authent($pseudo) {
    if (isset($_GET['lang'])) {
        $lang = $_GET['lang'];
    } else {// si aucune langue n'est déclarée on tente de reconnaitre la langue par défaut du navigateur
        $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
    }
    $db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
    $manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
    $idloginpassword = $manager->getSingle2("select idlogin from loginpassword where pseudo = ?", $pseudo);
    if (empty($idloginpassword)) {
        header('location:/' . REPERTOIRE . '/Login_Error/' . $lang);
    }
    BD::deconnecter();
}

function checktimeconnect($limitInactif) {
    if (isset($_GET['lang'])) {
        $lang = $_GET['lang'];
    } else {// si aucune langue n'est déclarée on tente de reconnaitre la langue par défaut du navigateur
        $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
    }
    if (isset($_SESSION['lastLoad']) && $_SESSION['lastLoad'] < time() - $limitInactif) {
        session_unset();
        echo '<script>window.location.replace("/' . REPERTOIRE . '/Login_Timeout/' . $lang . '")</script>';
    }
    $_SESSION['lastLoad'] = time();
}

function genPasse($car) {
    $string = "";
    $chaine1 = "abcdefghijklmnpqrstuvwxy";
    $chaine2 = "0123456789";
    $chaine3 = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    srand((double) microtime() * 1000000);
    for ($i = 0; $i < $car; $i++) {
        $string .= $chaine1[rand() % strlen($chaine1)] . $chaine2[rand() % strlen($chaine2)] . $chaine3[rand() % strlen($chaine3)];
    }
    return $string;
}

function creerJson($requete, $chemin, $nomselect, $libelleattribut1, $libelleattribut2) {
    $db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
    $manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
    $row = $manager->getList($requete);
    if (file_exists($chemin . '/' . $nomselect)) {
        $fp = fopen($chemin . '/' . $nomselect, 'w');
    } else {
        $fp = fopen($chemin . '/' . $nomselect, 'a+');
    }
    fwrite($fp, ' [');

    for ($i = 0; $i < count($row); $i++) {
        fputs($fp, json_encode($row[$i]));
        fwrite($fp, '');
        fwrite($fp, ',');
    }

    fwrite($fp, ']');
    $json_file = $chemin . '/' . $nomselect;
    $json1 = file_get_contents($json_file);
    $json2 = str_replace(',]', ']', $json1);
    $json3 = str_replace($libelleattribut1, 'label', $json2);
    $json4 = str_replace($libelleattribut2, 'id', $json3);
    $json5 = str_replace('"label"', 'label', $json4);
    $json6 = str_replace('"id"', 'id', $json5);
    $json = str_replace("''", "'", $json6);
    file_put_contents($json_file, $json);
    fclose($fp);
    BD::deconnecter();
}

function filtredonnee($param) {
    $string = trim(stripslashes(str_replace("''", "'", str_replace('"', '’’', $param))));
    return $string;
}

function infoLibelle($masque) {
    if (!empty($masque)) {
        echo '<div style="color:midnight-blue;font-size:12px" >' . '<i>' . TXT_LIBELLEHIDE . '</i>' . '</div>';
    } else {
        echo '<div style="color:midnight-blue;font-size:12px" >' . '<i>' . TXT_LIBELLESHOW . '</i>' . '</div>';
    }
}

//function filterEditor($string) {
//    $nb = mb_substr_count($string, "<!--");
//    //Compte le nombre d'occurrences de la sous-chaîne <!--
//    if ($nb > 0) {
//        for ($i = 0; $i <= $nb; $i++) {
//            $val0 = stripos($string, '<!');
//            $val1 = stripos($string, '->');
//            $string = (substr_replace($string, "", $val0 + 1, $val1 - $val0 + 2));           
//        }
//    }return $string;
//}
function stripTaggsbr($string) {
    $str1 = strip_tags($string, '<br>');    
    $str2 = str_replace("’","\''",$str1);    
    return $str2;
}
function filterEditor2($string) {
    $nb2 = mb_substr_count($string, "<style");
    if ($nb2 > 0) {
        for ($i = 0; $i <= $nb2; $i++) {
            $val0 = stripos($string, '<style');
            $val1 = stripos($string, '/style>');
            $string1 = (substr_replace($string, "", $val0 + 1, $val1 - $val0 + 2));
            $string=  str_replace("&; ", "", $string1);
        }
    }return $string;
}

function filterEditor($string) {
    if (mb_substr_count($string, "<!--") > 0) {
        $nb = mb_substr_count($string, "<!--");
        //Compte le nombre d'occurrences de la sous-chaîne <!--
        for ($i = 0; $i <= $nb; $i++) {
            $val0 = stripos($string, '<!');
            $val1 = stripos($string, '->');
            $string1 = (substr_replace($string, "", $val0 + 1, $val1 - $val0 + 2));
            $string = str_replace("&; ", "", $string1);
        }
        return $string;
    } elseif (mb_substr_count($string, "<style") > 0) {
        $nb = mb_substr_count($string, "<style");
        for ($i = 0; $i <= $nb; $i++) {
            $val0 = stripos($string, '<style');
            $val1 = stripos($string, '/style>');
            $string1 = (substr_replace($string, "", $val0 + 1, $val1 - $val0 + 2));
            $string = str_replace("&; ", "", $string1);
        }
        return $string;
    } elseif (mb_substr_count($string, "&lt;!-- /") > 0) {
        $nb = mb_substr_count($string, "&lt;!-- /");
        for ($i = 0; $i <= $nb; $i++) {
            $val0 = stripos($string, "&lt;!-- /");
            $val1 = stripos($string, '--&gt;');
            $string1 = (substr_replace($string, "", $val0 + 1, $val1 - $val0 + 2));
            $string = str_replace("&; ", "", $string1);
        }
        return $string;
    }else{
        return $string;
    }
}

function noreturn($chaine) {
    $search = array("\t", "\n", "\r");
    $chaine = str_replace($search, '', $chaine);
    return $chaine;
}

function checkConcerne($idprojet, $idcentrale, $idstatutprojet) {
    $db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
    $manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
    $nbcentrale = $manager->getSinglebyArray("select count(idprojet_projet) from concerne where idprojet_projet=? and idcentrale_centrale=?", array($idprojet, $idcentrale));
    if ($nbcentrale > 1) {
        $commentaireProjet = $manager->getSinglebyArray("select commentaireprojet from concerne where idprojet_projet=? and idcentrale_centrale=?", array($idprojet, $idcentrale));
        $manager->deleteConcerneProjetCentrale($idprojet, $idcentrale); //SUPPRESSION DES
        $concerne = new Concerne($idcentrale, $idprojet, $idstatutprojet, $commentaireProjet);
        $manager->addConcerne($concerne);
    }
    BD::deconnecter(); //CONNEXION A LA BASE DE DONNEE
}

function supprDouble() {//SUPPRESSION DES DOUBLONS
    $db = BD::connecter();
    $manager = new Manager($db);
    $arraydoublon = $manager->getList("SELECT distinct(idprojet) FROM projet, creer where idprojet not in (select idprojet_projet from creer)");
    $nbarraydoublon = count($arraydoublon);
    for ($i = 0; $i < $nbarraydoublon; $i++) {
        $manager->deleteprojetsf($arraydoublon[$i][0]);
        $manager->deleteressourceprojet($arraydoublon[$i][0]);
        $manager->deleteprojetpersonneaccueilcentrale($arraydoublon[$i][0]);
        $manager->deleteprojetpartenaireenattente($arraydoublon[$i][0]);
        $manager->deleteConcerne($arraydoublon[$i][0]);
        $manager->deleteprojetenattente($arraydoublon[$i][0]);
    }
    BD::deconnecter(); //CONNEXION A LA BASE DE DONNEE
}

function internetExplorer() {
    $ie = false;
    for ($i = 9; $i < 100; $i++) {
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident/7.0; rv:' . $i . '.0') !== false) {
            $ie = 'true';
        } elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE ' . $i . '.0') !== false) {
            $ie = 'true';
        } else {
            $ie = 'false';
        }
    }
    for ($i = 5; $i < 9; $i++) {
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE ' . $i . '.0') !== false) {
            $ie='incompatible';
        }
    }
    
    return $ie;
}

function effaceRepertoire($dossier) {
    $repertoire = opendir($dossier); // On définit le répertoire dans lequel on souhaite travailler.
    while (false !== ($fichier = readdir($repertoire))) { // On lit chaque fichier du répertoire dans la boucle.
        $chemin = $dossier . "/" . $fichier; // On définit le chemin du fichier à effacer.
        if ($fichier != ".." AND $fichier != "." AND ! is_dir($fichier)) {// Si le fichier n'est pas un répertoire…
            unlink($chemin); // On efface.
        }
    }
    closedir($repertoire);
}

function joinFiles(array $files, $result) {
    if(!is_array($files)) {
        throw new Exception(''.$files.' must be an array');
    }
    $wH = fopen($result, "w+");
    foreach($files as $file) {
        $fh = fopen($file, "r");
        while(!feof($fh)) {
            fwrite($wH, fgets($fh));
        }
        fclose($fh);
        unset($fh);
        fwrite($wH, "\n"); //usually last line doesn't have a newline
    }
    fclose($wH);
    unset($wH);
}