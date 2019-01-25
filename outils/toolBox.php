<?php

/**
 * This function enabled the login at the application
 * @param type string $mail is the mail of the connected user
 * @param type $pseudo is the login of the connected user
 */
function nomEntete($mail, $pseudo) {
    //FERMETURE DE LA CONNEXION
    if (is_file('../class/Manager.php')) {
        include_once '../class/Manager.php';
    } else {
        include_once 'class/Manager.php';
    }
    $db = BD::connecter();
    $manager = new Manager($db);
    $array = $manager->getListbyArray("SELECT u.nom,u.prenom FROM loginpassword l, utilisateur u  WHERE l.idlogin = u.idlogin_loginpassword AND l.mail=? and l.pseudo=?", array($mail, $pseudo));
    $_SESSION['nomConnect'] = str_replace("''", "'", $array[0][0]);
    $_SESSION['prenomConnect'] = str_replace("''", "'", $array[0][1]);
    //FERMETURE DE LA CONNEXION
    BD::deconnecter();
}
/**
 * 
 * @param type string $requete define the request
 * @param type string $chemin define the path
 * @param type string $nomselect define the name
 * @param type string $libelleattribut1
 * @param type string $libelleattribut2
 * Create a json file 
 */
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

/**
 * this function return the current page
 * @param type string $url
 * @return type
 */
function pageEncours($url) {
    $reg = '#^(.+[\\\/])*([^\\\/]+)$#';
    $pageencours = preg_replace($reg, '$2', $url);
    $pageencours1 = str_replace("/", "", $pageencours);
    return $pageencours1;
}

/**
 * 
 * @param type string $mail
 * @param type string $passe
 * @param type integer $idUser
 * @param type integer $droit
 * 
 */
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

/**
 * This function create a number for the project
 * @param type $n
 * @return string
 */
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

/**
 * This function remove accent to a string
 * @param type $string
 * @return type
 */
function stripAccents($string) {
    return strtr($string, 'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ', 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
}

/**
 * This function return the list of the file in a directory
 * @param type $directory
 * @return type
 */
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

/**
 * this function check if the login and password have access to the application.
 * @param type $pseudo
 */
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

/**
 * This function calculate the connextion time, if the connection time is above 30 minutes without any action, the session are closed
 * @param type $limitInactif
 */
function checktimeconnect($pseudo, $manager) {
    $limitInactif = (int) $manager->getSingle2("select tmpcx from loginpassword where pseudo=?", $pseudo) * 60;
    if ($limitInactif == 0) {
        $limitInactif = 30;
    }
    if (isset($_GET['lang'])) {
        $lang = $_GET['lang'];
    } else {//Si aucune langue n'est déclarée on tente de reconnaitre la langue par défaut du navigateur
        $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
    }


    if (isset($_SESSION['lastLoad']) && $_SESSION['lastLoad'] < time() - $limitInactif) {
        createLogInfo(NOW, TXT_ACCESLIMIT, NOMUSER . ' ' . PRENOMUSER, '', $manager, IDCENTRALEUSER);
        session_unset();
        echo '<script>window.location.replace("/' . REPERTOIRE . '/Login_Timeout/' . $lang . '")</script>';
    }
    /* if ($adresse == 'phase2.php' || $adresse == 'creerprojetphase2.php') {//Je suis soit en train de créeer un projet en phase 2 soir en train de modificer un projet
      //SAUVEGARDE DU PROJET EN FIN DE SESSION
      echo "<HTML><HEAD>";
      echo "<TITLE>Demande d'intervention non envoyée!</TITLE>";
      echo "<script> setTimeout('window.close()', 0); </script>";
      echo "</HEAD>";
      //   } */
    $_SESSION['lastLoad'] = time();
    $_SESSION['creationencours'] = 'non';


    BD::deconnecter(); //CONNEXION A LA BASE DE DONNEE
}

/**
 * This function generate a password respecting the rules of the application
 * @param type $car
 * @return string
 */
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

/**
 * This function create a json file
 * @param type $requete
 * @param type $chemin
 * @param type $nomselect
 * @param type $libelleattribut1
 * @param type $libelleattribut2
 */
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

/**
 * This function replace '' by ' and " by ’’
 * @param type $param
 * @return type
 */
function filtredonnee($param) {
    $string = trim(stripslashes(str_replace("''", "'", str_replace('"', '’’', $param))));
    return $string;
}

/**
 * this function show if the label is shown or hidden
 * @param type $masque
 */
function infoLibelle($masque) {
    if (!empty($masque)) {
        echo '<div style="color:midnight-blue;font-size:12px" >' . '<i>' . TXT_LIBELLEHIDE . '</i>' . '</div>';
    } else {
        echo '<div style="color:midnight-blue;font-size:12px" >' . '<i>' . TXT_LIBELLESHOW . '</i>' . '</div>';
    }
}

/**
 * this function remove tags and replace ’ by \''
 * @param type $string
 * @return type
 */
function stripTaggsbr($string) {
    $str1 = strip_tags($string, '<br>');
    $str2 = str_replace("’", "\''", $str1);
    
    return $str2;
}

/**
 * This function filter the editor by removing all the tag between <style and </style
 * @param type $string
 * @return type
 */
function filterEditor2($string0) {
    $string = strip_tags($string0);   
    $nb2 = mb_substr_count($string, "<style");
    if ($nb2 > 0) {
        for ($i = 0; $i <= $nb2; $i++) {
            $val0 = stripos($string, '<style');
            $val1 = stripos($string, '/style>');
            $string1 = (substr_replace($string, "", $val0 + 1, $val1 - $val0 + 2));
            $string = str_replace("&; ", "", $string1);
        }
    }


    return $string;
}

/**
 * This function filter the editor by removing all the tag between <!-- and ->
 * @param type $string
 * @return type
 */
function filterEditor($string0) {
    $string = strip_tags($string0,'<br>');
    if (mb_substr_count($string0, "<!--") > 0) {
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
        //return $string;
        if (substr($string, 0, 1) == "<") {
            return substr(str_replace("&lt;", "", $string), 1);
        } else {
            return str_replace("&lt;", "", $string);
        }
    } else {
        //return $string;
        if (substr($string, 0, 1) == "<") {
            return substr(str_replace("&lt;", "", $string), 1);
        } else {
            return str_replace("&lt;", "", $string);
        }
    }
}

/**
 * 
 * @param type $string
 * @return type
 */
function cleanRapportTPDF($string) {
    $arrayCar = array('à', 'û', '–', 'è');
    $arrayCarcorrige = array('à', 'û', '-', 'è');
    $string0 = filterEditorWithoutStripTags(str_replace("é", "é", $string));
    $string1 = str_replace("ç", "ç", $string0);
    //$string2 = str_replace(array(chr(13)), '', $string1);
    $string2 = str_replace($arrayCar, $arrayCarcorrige, $string1);
    $string3 = str_replace('€', utf8_encode(chr(128)), $string2);
    $string4 = (stripslashes(str_replace("''", "'", $string3)));
    return $string4;
}

/**
 * This function filter the editor by removing all the tag between <!-- and ->
 * @param type $string
 * @return type
 */
function filterEditorWithoutStripTags($string) {
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
        //return $string;
        if (substr($string, 0, 1) == "<") {
            return substr(str_replace("&lt;", "", $string), 1);
        } else {
            return str_replace("&lt;", "", $string);
        }
    } else {
        //return $string;
        if (substr($string, 0, 1) == "<") {
            return substr(str_replace("&lt;", "", $string), 1);
        } else {
            return str_replace("&lt;", "", $string);
        }
    }
}

/**
 * clean all the tags of a string
 * @param type $string
 * @return type
 */
function clean($string) {
    if (is_file('../class/Securite.php')) {
        include_once '../class/Securite.php';
    } else{
        include_once 'class/Securite.php';
    }
    $str =filterEditor(filterEditor2(Securite::bdd(trim(ltrim(rtrim(str_replace(array(chr(13)), '', removeDoubleQuote($string))))))));
    return str_replace("br />", "",$str);
}

function cleanREPORTPDF($string) {
    $arrayCar = array('à', 'û', '–', 'è');
    $arrayCarcorrige = array('à', 'û', '-', 'è');
    $tabCar = array("\r");
    $string0 = filterEditor(stripTaggsbr(str_replace("é", "é", $string)));
    $string1 = str_replace("ç", "ç", $string0);
    $string2 = str_replace($tabCar, array(), $string1);
    $string3 = ltrim(rtrim(str_replace(array(chr(13)), '', $string2)));
    $string4 = str_replace($arrayCar, $arrayCarcorrige, $string3);
    $string5 = str_replace('€', utf8_encode(chr(128)), $string4);
    $string6 = (stripslashes(str_replace("''", "'", $string5)));
    return strip_tags(filterEditor(Securite::bdd(trim($string6))));
}

/**
 * This function remove \t, \n, \r
 * @param type $chaine
 * @return type
 */
function noreturn($chaine) {
    $search = array("\t", "\n", "\r");
    $chaine = str_replace($search, '', $chaine);
    return $chaine;
}

/**
 * Fonction qui supprime les éventuels doublons dans la table concerne
 * @param type $idprojet
 * @param type $idcentrale
 * @param type $idstatutprojet
 */
function checkConcerne($idprojet, $idcentrale, $idstatutprojet) {
    $db = BD::connecter();
    $manager = new Manager($db);
    $nbcentrale = $manager->getSinglebyArray("select count(idprojet_projet) from concerne where idprojet_projet=? and idcentrale_centrale=?", array($idprojet, $idcentrale));
    if ($nbcentrale > 1) {
        $commentaireProjet = $manager->getSinglebyArray("select commentaireprojet from concerne where idprojet_projet=? and idcentrale_centrale=?", array($idprojet, $idcentrale));
        $manager->deleteConcerneProjetCentrale($idprojet, $idcentrale); //SUPPRESSION DES AFFECTATIONS DANS LA TABLE CONCERNE
        $concerne = new Concerne($idcentrale, $idprojet, $idstatutprojet, $commentaireProjet);
        $manager->addConcerne($concerne);
    }
    BD::deconnecter();
}

/**
 * This function delete project from table project where idproject are in creer table 
 */
function supprDouble() {//SUPPRESSION DES DOUBLONS
    $db = BD::connecter();
    $manager = new Manager($db);
    $arraydoublon = $manager->getList("SELECT distinct(idprojet) FROM projet, creer where idprojet not in (select idprojet_projet from creer)");
    $nbarraydoublon = count($arraydoublon);
    for ($i = 0; $i < $nbarraydoublon; $i++) {
        $manager->deleterapport($arraydoublon[$i][0]);
        $manager->deleteprojetautrecentrale($arraydoublon[$i][0]);
        $manager->deleteprojetsf($arraydoublon[$i][0]);
        $manager->deleteressourceprojet($arraydoublon[$i][0]);
        $manager->deleteprojetpersonneaccueilcentrale($arraydoublon[$i][0]);
        $manager->deleteprojetpartenaire($arraydoublon[$i][0]); //ok
        $manager->deleteConcerne($arraydoublon[$i][0]);
        $manager->deleteUtilisateurPorteur($arraydoublon[$i][0]);
        $manager->deleteUtilisateurAdministrateur($arraydoublon[$i][0]);
        $manager->deletecentraleproximiteprojet($arraydoublon[$i][0]);
        $manager->deleteProjetTypePartenaire($arraydoublon[$i][0]);
        $manager->deleteprojettypeprojet($arraydoublon[$i][0]);
        $manager->deleteprojet($arraydoublon[$i][0]);
    }
    //Suppression des personneaccueilcentrale orpheline
    $manager->exeRequete("delete from personneaccueilcentrale WHERE  idpersonneaccueilcentrale not in (select idpersonneaccueilcentrale_personneaccueilcentrale from projetpersonneaccueilcentrale);");
    //suppression des partenaireprojet orphelin
    $manager->exeRequete("delete from partenaireprojet  WHERE  idpartenaire not in (select idpartenaire_partenaireprojet from projetpartenaire);");
    BD::deconnecter(); //CONNEXION A LA BASE DE DONNEE
}

/**
 * This function check if you are using internet explorer
 * @return string 
 */
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
            $ie = 'incompatible';
        }
    }

    return $ie;
}

/**
 * This function delete folder
 * @param type $dossier
 */
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

/**
 * 
 * @param array $files
 * @param type $result
 * @throws Exception
 */
function joinFiles(array $files, $result) {
    if (!is_array($files)) {
        throw new Exception('' . $files . ' must be an array');
    }
    $wH = fopen($result, "w+");
    foreach ($files as $file) {
        $fh = fopen($file, "r");
        while (!feof($fh)) {
            fwrite($wH, fgets($fh));
        }
        fclose($fh);
        unset($fh);
        fwrite($wH, "\n"); //usually last line doesn't have a newline
    }
    fclose($wH);
    unset($wH);
}

/**
 * 
 * @param type $string
 * Remove the <br /> and change with ' - ' in a string used from the email, and remove also the tags
 */
function removeBrEmail($string) {
    return strip_tags(str_replace('<br />', ' - ', $string));
}

/**
 * 
 * @param type $string
 * remove "''" by "'"
 */
function removeDoubleQuote($string) {
    $str0 = str_replace("’", "'", $string);
    $str1 = str_replace("''", "'", $str0);
    $str2 = str_replace("&lt;", "", $str1);
    $str = str_replace("&gt;", "", $str2);
    return $str;
}

/**
 * Function qui supprime les doubles quote decode en utf8 et fait un stripslashes
 * @param type $string
 * @return type
 */
function cleanString($string) {
    return removeDoubleQuote(stripslashes(utf8_decode($string)));
}

/**
 * This function Show Error on dev and testonly
 * @param type $dir
 */
function showError($dir) {
    $repertoire = explode('/', $dir);
    if ($repertoire[1] == 'projet-dev') {
        error_reporting(E_ALL);
    } elseif ($repertoire[1] == 'projet-test') {
        error_reporting(E_ALL);
    } elseif ($repertoire[1] == 'projet') {
        error_reporting(0);
    } elseif ($repertoire[1] == 'renatech') {
        error_reporting(E_ALL);
    }
}

/**
 * This function clean the editor for the export only
 * @param type $string
 * @return type
 */
function cleanForExport($string) {
    $str0 = preg_replace("/(\r\n|\n|\r)/", " ", $string);
    $str1 = str_replace("’", "'", $str0);
    $str2 = str_replace("''", "'", $str1);
    $str3 = str_replace("-", "_", $str2);
    $str4 = str_replace("+", "_", $str3);
    $str5 = str_replace(";", "", $str4);
    $str6 = stripslashes(trim(html_entity_decode(strip_tags($str5))));
    return $str6;
}

function cleanForExportOther($string) {
    $str0 = preg_replace("/(\r\n|\n|\r)/", " ", $string);
    $str1 = str_replace("’", "'", $str0);
    $str2 = str_replace("''", "'", $str1);
    $str3 = str_replace("-", "_", $str2);
    $str4 = str_replace("+", "_", $str3);
    $str5 = str_replace(";", "", $str4);
    $str6 = stripslashes(trim(utf8_decode(strip_tags($str5))));
    return $str6;
}

/**
 * Cette fonction affecte le responsable du demandeur de projet comme porteur des projets du demandeur
 * @param type $pseudo
 * @param type $idprojet
 */
function responsablePorteur($pseudo, $idprojet) {
    if (is_file('../class/Manager.php')) {
        include_once '../class/Manager.php';
    } else {
        include_once 'class/Manager.php';
    }
    $db = BD::connecter();
    $manager = new Manager($db);
    $arraytypeutilisateur = $manager->getList2("select idqualitedemandeuraca_qualitedemandeuraca,idqualitedemandeurindust_qualitedemandeurindust from utilisateur,loginpassword where idlogin_loginpassword = idlogin  "
            . "and pseudo=?", $pseudo);
    $dateaffectation = date("m,d,Y");
    if (!empty($arraytypeutilisateur[0]['idqualitedemandeuraca_qualitedemandeuraca'])) {   //CAS ACADEMIQUE
        $typeutilisateur = $manager->getSingle2("SELECT idqualitedemandeuraca_qualitedemandeuraca FROM utilisateur,loginpassword WHERE idlogin_loginpassword = idlogin and pseudo=?", $pseudo);
        if ($typeutilisateur == NONPERMANENT) {//CAS NON PERMANENT
            $mailresponsable = $manager->getSingle2("SELECT mailresponsable FROM utilisateur,loginpassword WHERE idlogin_loginpassword = idlogin and pseudo=?", $pseudo);
            $arrayidresponsable = $manager->getList2("SELECT  idutilisateur FROM utilisateur,loginpassword WHERE  idlogin = idlogin_loginpassword and mail=?", $mailresponsable);
            $nbarrayidresponsable = count($arrayidresponsable);
            if ($nbarrayidresponsable > 0) {
                for ($i = 0; $i < $nbarrayidresponsable; $i++) {
                    $porteur = new UtilisateurPorteurProjet($arrayidresponsable[$i]['idutilisateur'], $idprojet, $dateaffectation);
                    $manager->addUtilisateurPorteurProjet($porteur);
                }
            }
        }
    } elseif (!empty($arraytypeutilisateur[0]['idqualitedemandeurindust_qualitedemandeurindust'])) {//CAS INDUSTRIEL
        $typeutilisateur = $manager->getSingle2("SELECT  idqualitedemandeurindust_qualitedemandeurindust FROM utilisateur,loginpassword WHERE idlogin_loginpassword = idlogin and pseudo=?", $pseudo);
        if ($typeutilisateur == NONPERMANENTINDUST) {
            $mailresponsable = $manager->getSingle2("SELECT mailresponsable FROM utilisateur,loginpassword WHERE idlogin_loginpassword = idlogin and pseudo=?", $pseudo);
            $arrayidresponsable = $manager->getList2("SELECT  idutilisateur FROM utilisateur,loginpassword WHERE  idlogin = idlogin_loginpassword and mail=?", $mailresponsable);
            $nbarrayidresponsable = count($arrayidresponsable);
            if ($nbarrayidresponsable > 0) {
                for ($i = 0; $i < $nbarrayidresponsable; $i++) {
                    $porteur = new UtilisateurPorteurProjet($arrayidresponsable[$i]['idutilisateur'], $idprojet, $dateaffectation);
                    $manager->addUtilisateurPorteurProjet($porteur);
                }
            }
        }
    }
    $db = BD::deconnecter();
}

/**
 * Modifie le nom d'un chaine de caractère en mettant des _ a la place de blancs
 * @param type $chaineNonValide
 * @return type
 */
function nomFichierValide($chaineNonValide) {
    $chaineNonValide0 = preg_replace('`\s+`', '_', trim($chaineNonValide));
    $chaineNonValide1 = str_replace("'", "_", $chaineNonValide0);
    $chaineNonValide2 = preg_replace('`_+`', '_', trim($chaineNonValide1));
    $chaineValide = strtr($chaineNonValide2, "ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ", "aaaaaaaaaaaaooooooooooooeeeeeeeecciiiiiiiiuuuuuuuuynn");
    return ($chaineValide);
}

function supprLogoFigure() {
    if (is_file('../class/Manager.php')) {
        include_once '../class/Manager.php';
    } else {
        include_once 'class/Manager.php';
    }
    $db = BD::connecter();
    $manager = new Manager($db);
    $dir = '../uploadlogo/';
    $arrayImages = scandir($dir);
    $nbarrayImg = count($arrayImages);
    for ($i = 0; $i < $nbarrayImg; $i++) {
        $idrapportLogo = $manager->getSinglebyArray("select idrapport from rapport where logo = ? or logocentrale =? or figure=?", array($arrayImages[$i], $arrayImages[$i], $arrayImages[$i]));
        if (empty($idrapportLogo) && $arrayImages[$i] != '.' && $arrayImages[$i] != '..') {
            if (!empty($arrayImages[$i])) {
                unlink('../uploadlogo/' . $arrayImages[$i]); //EFFACE LE FICHIER SUR LE SERVEUR
            }
        }
    }
    BD::deconnecter();
}

/**
 * 
 * @param type $array
 * Fonction qui redimensionne des image
 */
function sizeLogo($array, $t) {
    if ($array[0] > $t) {//si width>$t px
        $f = $t / $array[0];
        $w = $t;
        $h = $f * $array[1];
        if ($h > $t) {//si le height est >$t px
            $f = $t / $h;
            $w = $f * $w;
            $h = $t;
        }
    } elseif ($array[1] > $t) {
        $f = $t / $array[1];
        $h = $t;
        $w = $f * $array[1];
        if ($w > $t) {
            $f = $t / $w;
            $w = $t;
            $h = $f * $h;
        }
    } else {
        $w = $array[0];
        $h = $array[1];
    }

    if ($h < $t) {
        if(isset($h)){
            $f = $t / $h;
            $h = $f * $h;
            $w = $f * $w;
            if ($w > 560) {
                $f = 560 / $w;
                $w = 560;
                $h = $f * $h;
            }
        }
    }

    return array($w, $h);
}

/**
 * Fonction qui enlève les accents dans une chaine de caratère
 * @param type $str
 * @param type $charset
 * @return type
 */
function wd_remove_accents($str, $charset = 'utf-8') {
    $str = htmlentities($str, ENT_NOQUOTES, $charset);
    $str = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
    $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
    $str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractères    
    return $str;
}

/**
 * Fonction qui remome tous les noms de fichier
 * @param string $filemane
 * @return type
 */
function renameFile($filename) {
    $ext = strrchr($filename, '.');
    return basename($filename, $ext) . time() . $ext;
}

/**
 * Fonction qui enlève les accents dans une chaine de caratère et remplace les espaces par _
 * @param type $chaineNonValide
 * @return type
 */
function nomFichierValidesansAccent($chaineNonValide) {
    $chaineNonValide0 = wd_remove_accents($chaineNonValide, $charset = 'utf-8');
    $chaineNonValide1 = preg_replace('`\s+`', '_', trim($chaineNonValide0));
    $chaineNonValide2 = str_replace("'", "_", $chaineNonValide1);
    $chaineNonValide3 = preg_replace('`_+`', '_', trim($chaineNonValide2));
    $chaineValide = strtr($chaineNonValide3, "ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñé", "aaaaaaaaaaaaooooooooooooeeeeeeeecciiiiiiiiuuuuuuuuynne");    
    return $chaineValide;
}

/**
 * 
 * @param type $idutilisateur
 */
function ajouteAdministrationProjet($idutilisateur) {
    if (is_file('../class/Manager.php')) {
        include_once '../class/Manager.php';
    } else {
        include_once 'class/Manager.php';
    }
    $db = BD::connecter();
    $manager = new Manager($db);
    //AJOUT DE LA FONCTION ADMINISTRATEUR DE PROJET
    $arrayIdprojet = $manager->getList2("select idprojet_projet from creer where idutilisateur_utilisateur=?", $idutilisateur); //ON RECUPERE SES PROJETS DANS UN TABLEAU D'ID        
    $arrayidprojet = array();
    for ($i = 0; $i < count($arrayIdprojet); $i++) {
        array_push($arrayidprojet, $arrayIdprojet[$i]['idprojet_projet']);
    }
    $arrayidprojetadmin = $manager->getList2("select idprojet from utilisateuradministrateur where idutilisateur=?", $idutilisateur);
    $arrayIdProjetAdmin = array();
    for ($i = 0; $i < count($arrayidprojetadmin); $i++) {
        array_push($arrayIdProjetAdmin, $arrayidprojetadmin[$i]['idprojet']);
    }
    //CONSTRUCTION D4UN TABLEAU DES ECARTS DES 2 TABLEAUX
    $array = array_slice(array_diff($arrayidprojet, $arrayIdProjetAdmin), 0); //REMISE DES INDEX A ZERO
    if (!empty($array)) {
        for ($i = 0; $i < count($array); $i++) {
            $dateaffectation = date('Y-m-d');
            if (!empty($array[$i])) {
                $utilisateurAdmin = new UtilisateurAdmin($idutilisateur, $array[$i], $dateaffectation);
                $manager->addUtilisateurAdmin($utilisateurAdmin);
            }
        }
    }
    BD::deconnecter();
}

/**
 * 
 * @param type $idutilisateur
 */
function retireAdministrationProjet($idutilisateur) {
    if (is_file('../class/Manager.php')) {
        include_once '../class/Manager.php';
    } else {
        include_once 'class/Manager.php';
    }
    $db = BD::connecter();
    $manager = new Manager($db);
    //AJOUT DE LA FONCTION ADMINISTRATEUR DE PROJET
    $arrayIdprojet = $manager->getList2("select idprojet_projet from creer where idutilisateur_utilisateur=?", $idutilisateur); //ON RECUPERE SES PROJETS DANS UN TABLEAU D'ID
    for ($i = 0; $i < count($arrayIdprojet); $i++) {
        $manager->deleteUtilisateurAdmin($arrayIdprojet[$i]['idprojet_projet'], $idutilisateur);
    }

    BD::deconnecter();
}

function ajouteResponsableAdministrationProjet($idutilisateur, $idresponsable) {
    if (is_file('../class/Manager.php')) {
        include_once '../class/Manager.php';
    } else {
        include_once 'class/Manager.php';
    }
    $db = BD::connecter();
    $manager = new Manager($db);
    $arrayIdprojet = $manager->getList2("select idprojet_projet from creer where idutilisateur_utilisateur=?", $idutilisateur); //ON RECUPERE SES PROJETS DANS UN TABLEAU D'ID        
    $arrayidprojet = array();
    for ($i = 0; $i < count($arrayIdprojet); $i++) {
        array_push($arrayidprojet, $arrayIdprojet[$i]['idprojet_projet']);
    }

    $arrayidprojetadmin = $manager->getList2("select idprojet from utilisateuradministrateur where idutilisateur=?", $idresponsable);
    $arrayIdProjetAdmin = array();
    for ($i = 0; $i < count($arrayidprojetadmin); $i++) {
        array_push($arrayIdProjetAdmin, $arrayidprojetadmin[$i]['idprojet']);
    }
    //CONSTRUCTION D'UN TABLEAU DES ECARTS DES 2 TABLEAUX
    $array = array_slice(array_diff($arrayidprojet, $arrayIdProjetAdmin), 0); //REMISE DES INDEX A ZERO
    if (!empty($array)) {
        for ($i = 0; $i < count($array); $i++) {
            $dateaffectation = date('Y-m-d');
            if (!empty($array[$i])) {
                $utilisateurAdmin = new UtilisateurAdmin($idresponsable, $array[$i], $dateaffectation);
                $manager->addUtilisateurAdmin($utilisateurAdmin);
            }
        }
    }
    BD::deconnecter();
}

function showMonth($id, $lang) {
    $mois = "";
    if ($id == 1) {
        if ($lang == 'fr') {
            $mois = 'janvier';
        } else {
            $mois = 'January';
        }
    } elseif ($id == 2) {
        if ($lang == 'fr') {
            $mois = 'février';
        } else {
            $mois = 'February';
        }
    } elseif ($id == 3) {
        if ($lang == 'fr') {
            $mois = 'mars';
        } else {
            $mois = 'March';
        }
    } elseif ($id == 4) {
        if ($lang == 'fr') {
            $mois = 'avril';
        } else {
            $mois = 'April';
        }
    } elseif ($id == 5) {
        if ($lang == 'fr') {
            $mois = 'mai';
        } else {
            $mois = 'May';
        }
    } elseif ($id == 6) {
        if ($lang == 'fr') {
            $mois = 'juin';
        } else {
            $mois = 'June';
        }
    } elseif ($id == 7) {
        if ($lang == 'fr') {
            $mois = 'juillet';
        } else {
            $mois = 'July';
        }
    } elseif ($id == 8) {
        if ($lang == 'fr') {
            $mois = 'août';
        } else {
            $mois = 'Agust';
        }
    } elseif ($id == 9) {
        if ($lang == 'fr') {
            $mois = 'septembre';
        } else {
            $mois = 'September';
        }
    } elseif ($id == 10) {
        if ($lang == 'fr') {
            $mois = 'octobre';
        } else {
            $mois = 'October';
        }
    } elseif ($id == 11) {
        if ($lang == 'fr') {
            $mois = 'novembre';
        } else {
            $mois = 'November';
        }
    } elseif ($id == 12) {
        if ($lang == 'fr') {
            $mois = 'décembre';
        } else {
            $mois = 'December';
        }
    }
    return $mois;
}

function createLogInfo($dateHeure, $infos, $nomPrenom, $statutProjet, $manager, $idcentrale) {
    if (is_file('../class/Manager.php')) {
        include_once '../class/Manager.php';
    } else {
        include_once 'class/Manager.php';
    }
    $db = BD::connecter();
    $manager = new Manager($db);    
    $id = $manager->getSingle("select max(id) from logs") + 1;
    $logs = new Logs($id, $dateHeure, $infos, $nomPrenom, $statutProjet, $idcentrale);
    $manager->addlogs($logs);
}

function getCentrale($numero, $manager) {
    $centrales = $manager->getList2("SELECT libellecentrale FROM concerne, projet,centrale WHERE idprojet_projet =  idprojet AND idcentrale = idcentrale_centrale AND   numero = ?", $numero);
    $nomCentrale = "";
    if ($centrales[0][0] == TXT_AUTRES) {
        $nomCentrale = TXT_AUCUNE;
        return 'Aucune centrale sélectionnée';
    } elseif ($centrales[0][0] != TXT_AUTRES) {
        foreach ($centrales as $centrale) {
            $nomCentrale.=$centrale[0] . ', ';
        }
        if (count($centrales) > 1) {
            return ' Centrales: ' . substr($nomCentrale, 0, -2);
        } else {
            return ' Centrale: ' . substr($nomCentrale, 0, -2);
        }
    } else {
        return '';
    }
}

function purgeTableLogs($manager) {
    $manager->delLogs();
}

/**
 * Function qui simplie les includes
 * @param type $class
 */
function fileExist($class) {
    if (is_file($class)) {
        include_once $class;
    } elseif (is_file('../' . $class)) {
        include_once '../' . $class;
    }
}

function requeteCloture($manager, $mois, $idcentrale) {
    return $manager->getListbyArray("SELECT idprojet,numero,titre,refinterneprojet,datestatutfini,libellecentrale,(SELECT (DATE_PART('year', current_date::date) - DATE_PART('year', (datestatutfini)::date)) * 12 + 
        (DATE_PART('month', current_date::date) - DATE_PART('month', (datestatutfini)))) as ecart  FROM concerne,projet,statutprojet,centrale WHERE idcentrale_centrale =  idcentrale 
        AND idprojet_projet = idprojet AND idstatutprojet_statutprojet = idstatutprojet  AND idstatutprojet=? AND (SELECT (DATE_PART('year', current_date::date) - DATE_PART('year', (datestatutfini)::date)) * 12 + 
        (DATE_PART('month', current_date::date) - DATE_PART('month', (datestatutfini))))>? AND idcentrale=? order by datestatutfini asc", array(FINI, $mois, $idcentrale));
}

/**
 * 
 * @param type $manager
 * @param type $idcentrale
 * @param type $mois
 */
function clotureProjet($manager, $idcentrale, $mois) {
    fileExist('decide-lang.php');
    fileExist('class/email.php');
    $body = utf8_decode(affiche('TXT_MRSMR') . '<br><br><br>' . affiche('TXT_LISTEPROJETECHEANCE') . ', ' . affiche('TXT_SIVOUSNEFAITERIEN') . '.<br><br><br>'
            . affiche('TXT_SINCERESALUTATION') . '<br><br>' . affiche('TXT_RESEAURENATECH') . '<br><br><br>');
    $tableau = requeteCloture($manager, $mois, $idcentrale);
    /*
     * Madame, Monsieur,
      Voici la liste des projets qui vont arrivés à échéance(date de statut fini +18 mois), vous pouvez changer cette état de fait en repassant le projet au statut "En cours de réalisation",
     * si vous faites rien ces projets passeront automatiquement au statut clôturé.
      Sincères salutations,
      Le réseau Renatech.
     */
    $body .= "
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
        <link rel='stylesheet' href='" . '/' . REPERTOIRE . "/styles/style.css' media='screen' />
    </head>
<body>
        <table>
        <tr>
            <th style='background-color:lightgrey;border: 1px solid black;text-align: center;;width: 90px;padding:5px'>" . utf8_decode(TXT_NUMERO) . "</th>
            <th style='background-color:lightgrey;border: 1px solid black;padding:5px'>" . utf8_decode(TXT_TITREPROJET) . "</th>
            <th style='background-color:lightgrey;border: 1px solid black;padding:5px'>" . utf8_decode(TXT_REFINTERNE) . "</th>
            <th style='background-color:lightgrey;border: 1px solid black;padding:5px'>" . utf8_decode(TXT_DATESTATUTFIN) . "</th>                        
            <th style='background-color:lightgrey;border: 1px solid black;padding:5px'>" . utf8_decode(TXT_DATEPASSAGECLOTURE) . "</th>
                <th style='background-color:lightgrey;border: 1px solid black;padding:5px'>" . utf8_decode(TXT_CENTRALE) . "</th>
         </tr>";
    $DateFin = 18;
    for ($i = 0; $i < count($tableau); $i++) {
        $DateDebut = $tableau[$i]['datestatutfini'];
        $body.= "
         <tr>
            <td style='border: 1px solid black;width: 90px;;padding:5px'>" . $tableau[$i]['numero'] . "</td>
            <td style='padding:5px;border: 1px solid black;padding:5px'>" . utf8_decode($tableau[$i]['titre']) . "</td>
            <td style='padding:5px;border: 1px solid black;padding:5px'>" . $tableau[$i]['refinterneprojet'] . "</td>
            <td style='padding:5px;border: 1px solid black;padding:5px'>" . date('d-m-Y', strtotime($tableau[$i]['datestatutfini'])) . "</td>
            <td style='padding:5px;border: 1px solid black;padding:5px'>" . date('d-m-Y', strtotime($DateDebut . ' +' . $DateFin . ' month')) . "</td>
             <td style='padding:5px;border: 1px solid black;padding:5px'>" . $tableau[$i]['libellecentrale'] . "</td>
        </tr>
        ";
    }
    $body.= "</table></body>";
    $body.= utf8_decode('<br><br>' . affiche('TXT_DONOTREPLY') . '<br><br>');
    if (count($tableau) > 0) {
        envoieEmail($body, affiche('TXT_NOTIFICATION'), array("antoineadb@gmail.com"), array('antoineadb@orange.fr'));
    }
}

function emailclotureProjet($manager, $idprojet, $idcentrale) {
    fileExist('decide-lang.php');
    fileExist('class/email.php');
    fileExist('outils/constantes.php');
//JE PASSE LE PROJET AU STATUT CLOTURE
    try {
        $concerne = new Concerne($idcentrale, $idprojet, CLOTURE, "Clôture automatique du projet le " . date('d-m-Y'));
        $manager->updateConcerne($concerne, $idprojet);
        $dateCloture = new DateStatutCloturerProjet($idprojet, date('Y-m-d'));
        $manager->updateDateStatutCloturer($dateCloture, $idprojet);
    } catch (Exception $exc) {
        echo $exc->getMessage() . '<hr>' . 'Problème de mise à jour du statut du projet<hr>' . 'Mise à jour automatique du projet abandonnée!';
        exit(); // je n'envoi pas d'email en cas d'echec
    }

//J'ENVOI l'EMAIL DE NOTIFICATION
    $body = utf8_decode(affiche('TXT_MRSMR') . '<br><br>' . affiche('TXT_CLOTUREAUTO')
            . '<br>' . affiche('TXT_NOINFOPROJECT') . "<br><br>" . affiche('TXT_REACTPROJECT') . "<br><br>"
            . '' . affiche('TXT_REMERCIEMENT') . '.<br><br>'
            . '' . affiche('TXT_SINCERESALUTATION') . '<br><br>' . affiche('TXT_RESEAURENATECH') . '<br><br><br><a href='.ADRESSESITE.' >' . TXT_RETOUR . '</a>'
            . '<br><br>' . affiche('TXT_DONOTREPLY') . '<br><br>');
    /*
     *  Madame, Monsieur,
      Suite à l'aboutissement de votre projet en référence ci-dessus et après un delai supérieur à 18 mois au dela du passage au statut «Fini» , nous vous informons que votre projet est passé au statut «Cloturé».
      Vous ne pouvez donc plus accéder aux informations concernant ce projet.
      Si vous souhaitez continuer votre projet, merci d'en faire la demande auprès de l'administrateur local de la centrale.
      En vous remerciant pour votre confiance au réseau RENATECH. En cas de nouveau projet, merci de bien vouloir remplir une nouvelle demande.
      Sincères salutations,
      Le réseau Renatech.
      Retour sur la plateforme Renatech
      Merci de ne pas répondre à cette adresse.
     */

    $infodemandeur = array($manager->getList2("SELECT mail, mailresponsable FROM creer,loginpassword,utilisateur WHERE idutilisateur_utilisateur = idutilisateur
            AND idlogin_loginpassword = idlogin and idprojet_projet=?", $idprojet));
    $maildemandeur = array($infodemandeur[0][0]['mail']); //EMAIL DU DEMANDEUR NE PEUT PAS NE PAS EXISTER
    $mailCC = array();
    if (!empty($infodemandeur[0][0]['mailresponsable'])) {
        array_push($mailCC, $infodemandeur[0][0]['mailresponsable']); //EMAIL DU RESPONSABLE SI IL EXISTE
    }
    $infoProjet = $manager->getList2("select titre,numero from projet where idprojet=?", $idprojet);
    envoieEmail($body, "Cloture du projet " . '"' . utf8_decode($infoProjet[0]['titre']) . '"' . utf8_decode(" numéro ") . $infoProjet[0]['numero'] . "", $maildemandeur, $mailCC);
}

function clotureProjetAuto($manager) {
    $centrales = $manager->getList2("select idcentrale from centrale where idcentrale!=? order by idcentrale asc", IDCENTRALEAUTRE);
    foreach ($centrales as $centrale) {
        $cloture = requeteCloture($manager, 18, $centrale[0]);
        if (count($cloture) > 0) {
            for ($i = 0; $i < count($cloture); $i++) {
                emailclotureProjet($manager, $cloture[$i]['idprojet'], $centrale[0]);
            }
        }
    }
}

function startClosure($manager) {
    try {//Je met à jour la table cloture projet pour éviter d'avoir à renvoyer 2 fois l'email
        $date = date('Y-M');
        $date_explosee = explode("-", $date);
        $moisannee = $date_explosee[1] . '-' . $date_explosee[0];
        $nbcentrale = $manager->getSingle2("select count(idcentrale) from centrale where idcentrale !=? ", IDCENTRALEAUTRE);
        $test = $manager->getList("select datecloture,idcentrale from clotureprojet");
        for ($i = 0; $i < $nbcentrale; $i++) {
            if ($test[$i]['datecloture'] != $moisannee) {
                $tab = requeteCloture($manager, 15, $test[$i]['idcentrale']);
                if (!empty($tab[$i]['libellecentrale'])) {
                    $clotureprojet = new ClotureProjet($moisannee, $test[$i]['idcentrale']);
                    $manager->updateClotureProjet($clotureprojet, $test[$i]['idcentrale']);
                    clotureProjet($manager, $test[$i]['idcentrale'], 15);
                }
            }
        }
    } catch (Exception $exc) {
        echo $exc->getTraceAsString();
    }
}

/**
 * Fonction qui retourne un tableau de différence entre 2 tableaux à 2 dimensions
 * @param type $array1
 * @param type $array2
 * @return array
 */
function arrayDiff2dim($array1, $array2) {
    $array3 = array();
    for ($i = 0; $i < count($array1); $i++) {
        array_push($array3, serialize($array1[$i]));
    }
    $array4 = array();
    for ($i = 0; $i < count($array2); $i++) {
        array_push($array4, serialize($array2[$i]));
    }
    if (count($array1) >= count($array2)) {
        $array5 = array_values(array_diff($array3, $array4));
    } else {
        $array5 = array_values(array_diff($array4, $array3));
    }
    $array6 = array();
    for ($i = 0; $i < count($array5); $i++) {
        array_push($array6, unserialize($array5[$i]));
    }
    return $array6;
}

/**
 * Fonction qui découpe une boucle if en portion de $freq
 * si freq = 40  alors la fonction retourne array(10,20,30,40)
 * @param type $entier
 * @param type $freq
 * @return array
 */
function boucleTempo($entier, $freq) {
    $var = array();
    if ($entier > 20) {
        $nb = ceil($entier / 20);
        for ($i = 1; $i <= $nb; $i++) {
            array_push($var, $i * $freq);
        }
    } else {
        array_push($var, $entier);
    }
    return $var;
}

function check_URL($pseudo, $idprojet) {
    $db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
    $manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
    $iduser = $manager->getSingle2("select idutilisateur  from utilisateur,loginpassword where idlogin= idlogin_loginpassword  and pseudo = ?", $pseudo);
    /* VERIFICATION QUE L'UTILISATEUR A LE DROIR D'ACCEDER AU PROJET */
    /* VERIFICATION QUE L'UTILISATEUR A CREER LE PROJET */
    $creer = $manager->getSingle2("select idutilisateur_utilisateur from creer where idprojet_projet=?", $idprojet);
    /* VERIFICATION QUE L'UTILISATEUR EST PORTEUR DU PROJET */
    $porteur = $manager->getSingle2("select idutilisateur_utilisateur from utilisateurporteurprojet where idprojet_projet=?", $idprojet);
    /* VERIFICATION QUE L'UTILISATEUR EST ADMINISTRATEUR DU PROJET */
    $administrateurProjet = $manager->getSingle2("select idutilisateur from utilisateuradministrateur where idprojet=?", $idprojet);
    /* VERIFICATION DE LA CENTRALE DE DEPOT DU PROJET */
    $arrayidcentrale = $manager->getList2("select idcentrale_centrale  from concerne  where idprojet_projet = ?", $idprojet);
    //$arrayUser = $manager->getList2("select idtypeutilisateur_typeutilisateur,idcentrale_centrale from utilisateur where idutilisateur=?", $iduser);    
    foreach ($arrayidcentrale as $key => $idcentrale) {
        $arrayTypeUser = $manager->getSinglebyArray("select idtypeutilisateur_typeutilisateur from utilisateur where idcentrale_centrale=? and idutilisateur=?", array($idcentrale[0], $iduser));
        if ($arrayTypeUser == ADMINLOCAL) {
            return true;
        }
    }
    if ($iduser == $creer || $iduser == $porteur || $iduser == $administrateurProjet || $iduser=ADMINNATIONNAL) {//  ||  $arrayUser[0]['idcentrale_centrale']==$idcentrale){
        return true;
    } else {
        return false;
    }
    BD::deconnecter();
}

function effaceCache($libelleCentraleUser){
    if(is_file('../class/Cache.php')){
        include_once '../class/Cache.php';
    }else{
        include_once 'class/Cache.php';
    }
    $videCache = new Cache(REP_ROOT . '/cache' . $libelleCentraleUser , 1);
    $videCache->delete('tous_'.$libelleCentraleUser);
    $videCache->delete('soustraitance_'.$libelleCentraleUser);
    $videCache->delete('refuse_'.$libelleCentraleUser);
    $videCache->delete('encours_'.$libelleCentraleUser);
    $videCache->delete('rapport_'.$libelleCentraleUser);
    $videCache->delete('accepte_'.$libelleCentraleUser);
    $videCache->delete('analyse_'.$libelleCentraleUser);
    $videCache->delete('attente_'.$libelleCentraleUser);
    $videCache->delete('cloture_'.$libelleCentraleUser);
    $videCache->delete('finis_'.$libelleCentraleUser);
}

function miseAnullStatutcloturerFin($idprojet,$manager,$datemodifstatut){
    //Vérification que le projet n'est pas un projet qui a été réactivé
    $dateStatutFini = $manager->getSingle2("SELECT datestatutfini FROM projet WHERE idprojet=? ", $idprojet);
    if($dateStatutFini <=  $datemodifstatut){// la date du statut fini est antérieur à la date du statut en cours
        $manager->updateNullStatutFini($idprojet);
    }
    $dateStatutCloturer = $manager->getSingle2("SELECT datestatutcloturer FROM projet WHERE idprojet=? ", $idprojet);
    if($dateStatutCloturer <=  $datemodifstatut){// la date du statut fini est antérieur à la date du statut en cours
        $manager->updateNullStatutCloturer($idprojet);
    }
}

function recupMailAdminProjet($idprojet) {
    if (is_file('../class/Manager.php')) {
        include_once '../class/Manager.php';
    } else {
        include_once 'class/Manager.php';
    }
    $db = BD::connecter();
    $manager = new Manager($db);
    $nbAdminProjet = $manager->getSingle2("SELECT count(idutilisateur) FROM utilisateuradministrateur WHERE idprojet=? ", $idprojet);
    if ($nbAdminProjet > 0) {
        if ($nbAdminProjet == 1) {
            $idAdminProjet = $manager->getSingle2("SELECT idutilisateur FROM utilisateuradministrateur WHERE idprojet=? ", $idprojet);
            if ($idAdminProjet != null) {
                $emailAdminProjet = $manager->getSinglebyArray("SELECT l.mail FROM loginpassword l "
                        . "LEFT JOIN utilisateur u ON  u.idlogin_loginpassword = l.idlogin "
                        . "LEFT JOIN utilisateuradministrateur ua ON  ua.idutilisateur = u.idutilisateur "
                        . "WHERE u.idutilisateur = ? AND ua.idprojet=?", array($idAdminProjet, $idprojet));
                return array($emailAdminProjet);
            }
        }else{// traitement du  cas ou il y a plusieur admùinistrateur de projet
                $emailAdminProjet = $manager->getList2("SELECT l.mail FROM loginpassword l "
                        . "LEFT JOIN utilisateur u ON  u.idlogin_loginpassword = l.idlogin "
                        . "LEFT JOIN utilisateuradministrateur ua ON  ua.idutilisateur = u.idutilisateur "
                        . "WHERE  ua.idprojet=?",$idprojet);
                $arrayMailAdmin = array();
                foreach ($emailAdminProjet as $key=>$value) {
                    array_push($arrayMailAdmin,$value['mail']);
                }
                return $arrayMailAdmin;
        }
    }else{
        return null;
    }
    $db = BD::deconnecter();
}

function mailAutresCentrale($manager,$idprojet){
    //Récupération des autres centrale si elles existent
    $autresCentralesMail = $manager->getList2("SELECT  email1,email2,email3,email4,email5 from projetautrecentrale pa LEFT JOIN centrale c ON c.idcentrale=pa.idcentrale WHERE pa.idprojet=?", $idprojet);
    $emailAutresCentrales = array();
    for ($i = 0; $i <= 5; $i++) {
        if (!empty($autresCentralesMail[0][$i])) {
            array_push($emailAutresCentrales, $autresCentralesMail[0][$i]); //construction d'un tableau d'email des responsable de la centrale
        }
    }
    return $emailAutresCentrales;
}

function removeDoubleApp($str){
    return str_replace("''","'",str_replace("’’","’",$str));
}