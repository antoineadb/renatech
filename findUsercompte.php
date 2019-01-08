<?php

include_once 'decide-lang.php';
include_once 'outils/constantes.php';
if (!empty($_SESSION['page_precedente']) && $_SESSION['page_precedente'] == 'gestioncompte.php') {
    if (!empty($_SESSION['pseudo'])) {
        check_authent($_SESSION['pseudo']);
    } else {
        header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
    }
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
} 
include_once 'class/Manager.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
//academiqueinterne, academiqueexterne, industriel
if (!empty($_SESSION['pseudo'])) {
    $pseudo = $_SESSION['pseudo'];
    $typeUser = IDTYPEUSER;
    if ($typeUser == ADMINLOCAL) {
        $idcentrale_centrale = IDCENTRALEUSER;
        $localinterne = "
            SELECT
                count(c.idprojet_projet) as nb,
                l.pseudo,u.idcentrale_centrale,u.idtypeutilisateur_typeutilisateur,u.idqualitedemandeurindust_qualitedemandeurindust,u.idqualitedemandeuraca_qualitedemandeuraca,u.idutilisateur,u.prenom,u.nom,
                u.datecreation,l.actif 
            FROM 
                loginpassword l
                LEFT JOIN utilisateur u ON l.idlogin = u.idlogin_loginpassword
                LEFT JOIN creer c ON c.idutilisateur_utilisateur = u.idutilisateur
            WHERE 
                l.idlogin = u.idlogin_loginpassword 
                AND idtypeutilisateur_typeutilisateur <> ? 
                AND u.idqualitedemandeuraca_qualitedemandeuraca is not null 
                AND u.idcentrale_centrale is not null 
                AND idtypeutilisateur_typeutilisateur <>? 
                AND idcentrale_centrale=? 
                GROUP BY u.idutilisateur,l.pseudo,u.idcentrale_centrale,
                u.idtypeutilisateur_typeutilisateur,
                u.idqualitedemandeurindust_qualitedemandeurindust,
                u.idqualitedemandeuraca_qualitedemandeuraca,
                u.idutilisateur,
                u.prenom,
                u.nom, 
                u.datecreation,l.actif  
                ";
        $localexterne = "
            SELECT
                count(c.idprojet_projet) as nb,
                l.pseudo,u.idcentrale_centrale,u.idtypeutilisateur_typeutilisateur ,u.idqualitedemandeurindust_qualitedemandeurindust,u.idqualitedemandeuraca_qualitedemandeuraca,u.idutilisateur,u.prenom,u.nom,
                u.datecreation,l.actif
            FROM 
                loginpassword l
                LEFT JOIN utilisateur u ON l.idlogin = u.idlogin_loginpassword
                LEFT JOIN creer c ON c.idutilisateur_utilisateur = u.idutilisateur
            WHERE 
                l.idlogin = u.idlogin_loginpassword 
            AND idtypeutilisateur_typeutilisateur <> ? 
            AND idcentrale_centrale is null
            AND u.idqualitedemandeurindust_qualitedemandeurindust is null  
            AND idtypeutilisateur_typeutilisateur <>?
            GROUP BY u.idutilisateur,l.pseudo,u.idcentrale_centrale,
                u.idtypeutilisateur_typeutilisateur,
                u.idqualitedemandeurindust_qualitedemandeurindust,
                u.idqualitedemandeuraca_qualitedemandeuraca,
                u.idutilisateur,
                u.prenom,
                u.nom, 
                u.datecreation,l.actif  ";
        $localindustriel = "
            SELECT  
                count(c.idprojet_projet) as nb,
                l.pseudo,u.idcentrale_centrale,u.idtypeutilisateur_typeutilisateur,u.idqualitedemandeurindust_qualitedemandeurindust,u.idqualitedemandeuraca_qualitedemandeuraca,u.idutilisateur,u.prenom,u.nom,
                u.datecreation,l.actif
            FROM 
            loginpassword l
                LEFT JOIN utilisateur u ON l.idlogin = u.idlogin_loginpassword
                LEFT JOIN creer c ON c.idutilisateur_utilisateur = u.idutilisateur            
            WHERE l.idlogin = u.idlogin_loginpassword 
            AND idqualitedemandeurindust_qualitedemandeurindust IS NOT NULL
            GROUP BY u.idutilisateur,l.pseudo,u.idcentrale_centrale,
                u.idtypeutilisateur_typeutilisateur,
                u.idqualitedemandeurindust_qualitedemandeurindust,
                u.idqualitedemandeuraca_qualitedemandeuraca,
                u.idutilisateur,
                u.prenom,
                u.nom, 
                u.datecreation,l.actif ";
        $localinternenom = "
            SELECT 
                count(c.idprojet_projet) as nb,
                l.pseudo,u.idcentrale_centrale,u.idtypeutilisateur_typeutilisateur,u.idqualitedemandeurindust_qualitedemandeurindust,u.idqualitedemandeuraca_qualitedemandeuraca,u.idutilisateur,u.prenom,u.nom,
                u.datecreation,l.actif 
            FROM loginpassword l
            	LEFT JOIN utilisateur u ON l.idlogin = u.idlogin_loginpassword
                LEFT JOIN creer c ON c.idutilisateur_utilisateur = u.idutilisateur	
            WHERE idtypeutilisateur_typeutilisateur <> ? 
                AND u.idqualitedemandeuraca_qualitedemandeuraca is not null 
                AND u.idcentrale_centrale is not null and lower(nom) like lower(?) 
                AND idtypeutilisateur_typeutilisateur <>? 
                AND idcentrale_centrale=? 
                GROUP BY u.idutilisateur,l.pseudo,u.idcentrale_centrale,
                u.idtypeutilisateur_typeutilisateur,
                u.idqualitedemandeurindust_qualitedemandeurindust,
                u.idqualitedemandeuraca_qualitedemandeuraca,
                u.idutilisateur,
                u.prenom,
                u.nom, 
                u.datecreation,l.actif                 ";
        
        
        
        $localexternenom = "
            SELECT
                count(c.idprojet_projet) as nb,
                l.pseudo,u.idcentrale_centrale,u.idtypeutilisateur_typeutilisateur ,u.idqualitedemandeurindust_qualitedemandeurindust,u.idqualitedemandeuraca_qualitedemandeuraca,u.idutilisateur,u.prenom,u.nom,
                u.datecreation,l.actif
            FROM 
                loginpassword l
                LEFT JOIN utilisateur u ON l.idlogin = u.idlogin_loginpassword
                LEFT JOIN creer c ON c.idutilisateur_utilisateur = u.idutilisateur
            WHERE 
                l.idlogin = u.idlogin_loginpassword 
                AND idtypeutilisateur_typeutilisateur <> ? 
                AND idcentrale_centrale is null
                AND u.idqualitedemandeurindust_qualitedemandeurindust is null 
                AND lower(nom) like lower(?) 
                AND idtypeutilisateur_typeutilisateur <>?
                GROUP BY u.idutilisateur,l.pseudo,u.idcentrale_centrale,
                u.idtypeutilisateur_typeutilisateur,
                u.idqualitedemandeurindust_qualitedemandeurindust,
                u.idqualitedemandeuraca_qualitedemandeuraca,
                u.idutilisateur,
                u.prenom,
                u.nom, 
                u.datecreation,l.actif ";
        $localindustrielnom = "
            SELECT
                count(c.idprojet_projet) as nb,
                l.pseudo,u.idcentrale_centrale,u.idtypeutilisateur_typeutilisateur,u.idqualitedemandeurindust_qualitedemandeurindust,u.idqualitedemandeuraca_qualitedemandeuraca,u.idutilisateur,u.prenom,u.nom,
                u.datecreation,l.actif
            FROM 
                loginpassword l
                LEFT JOIN utilisateur u ON l.idlogin = u.idlogin_loginpassword
                LEFT JOIN creer c ON c.idutilisateur_utilisateur = u.idutilisateur
            WHERE 
                l.idlogin = u.idlogin_loginpassword 
                AND idqualitedemandeurindust_qualitedemandeurindust IS NOT NULL
                AND lower(nom) like lower(?) 
                GROUP BY u.idutilisateur,l.pseudo,u.idcentrale_centrale,
                u.idtypeutilisateur_typeutilisateur,
                u.idqualitedemandeurindust_qualitedemandeurindust,
                u.idqualitedemandeuraca_qualitedemandeuraca,
                u.idutilisateur,
                u.prenom,
                u.nom, 
                u.datecreation,l.actif ";
        
        if (!empty($_POST['nom']) && $_POST['nom'] != '*') {// CAS OU ON SAISIE UN NOM
            $nom = pg_escape_string($_POST['nom']); 
            if (!empty($_POST['academiqueinterne']) && !empty($_POST['academiqueexterne']) && !empty($_POST['industriel'])) {// CAS OU ON COCHE ACADEMIQUE INTERNE EXTERNE ET INDUSTRIEL
                $req = $localinternenom . " union " . $localexternenom . " union " . $localindustrielnom . " order by datecreation asc ";
                $param = array(ADMINNATIONNAL, $nom, ADMINLOCAL, $idcentrale_centrale, ADMINNATIONNAL, $nom, ADMINLOCAL, $nom);
            } elseif (!empty($_POST['academiqueinterne']) && !empty($_POST['academiqueexterne'])) {// CAS OU ON COCHE ACADEMIQUE INTERNE ET EXTERNE
                $req = $localinternenom . " union " . $localexternenom . " order by datecreation asc ";
                $param = array(ADMINNATIONNAL, $nom, ADMINLOCAL, $idcentrale_centrale, ADMINNATIONNAL, $nom, ADMINLOCAL);
            } elseif (!empty($_POST['academiqueinterne']) && !empty($_POST['industriel'])) {// CAS OU ON COCHE ACADEMIQUE INTERNE ET INDUSTRIEL
                $req = $localinternenom . " union " . $localindustrielnom . " order by datecreation asc ";
                $param = array(ADMINNATIONNAL, $nom, ADMINLOCAL, $idcentrale_centrale, $nom);
            } elseif (!empty($_POST['academiqueexterne']) && !empty($_POST['industriel'])) {// CAS OU ON COCHE ACADEMIQUE EXTERNE ET INDUSTRIEL
                $req = $localexternenom . " union " . $localindustrielnom . " order by datecreation asc ";
                $param = array(ADMINNATIONNAL, $nom, ADMINLOCAL, $nom);
            } elseif (!empty($_POST['academiqueexterne'])) {// CAS OU ON COCHE ACADEMIQUE EXTERNE
                $req = $localexternenom . " order by datecreation asc ";
                $param = array(ADMINNATIONNAL, $nom, ADMINLOCAL);
            } elseif (!empty($_POST['academiqueinterne'])) { //CAS OU ON COCHE ACADEMIQUE INTERNE
                $req = $localinternenom . " order by datecreation asc ";
                $param = array(ADMINNATIONNAL, $nom, ADMINLOCAL, $idcentrale_centrale);
            } elseif (!empty($_POST['industriel'])) { //CAS OU ON COCHE INDUSTRIEL
                $req = $localindustrielnom . " order by datecreation asc ";
                $param = array($nom);
            }
        } else {
            if (!empty($_POST['academiqueinterne']) && !empty($_POST['academiqueexterne']) && !empty($_POST['industriel'])) {// CAS OU ON COCHE ACADEMIQUE INTERNE EXTERNE ET INDUSTRIEL
                $req = $localinterne . " union " . $localexterne . " union " . $localindustriel . " order by datecreation asc ";
                $param = array(ADMINNATIONNAL, ADMINLOCAL, $idcentrale_centrale, ADMINNATIONNAL, ADMINLOCAL);
            } elseif (!empty($_POST['academiqueinterne']) && !empty($_POST['academiqueexterne'])) {// CAS OU ON COCHE ACADEMIQUE INTERNE ET EXTERNE
                $req = $localinterne . " union " . $localexterne . " order by datecreation asc ";
                $param = array(ADMINNATIONNAL, ADMINLOCAL, $idcentrale_centrale, ADMINNATIONNAL, ADMINLOCAL);
            } elseif (!empty($_POST['academiqueinterne']) && !empty($_POST['industriel'])) {// CAS OU ON COCHE ACADEMIQUE INTERNE ET INDUSTRIEL
                $req = $localinterne . " union " . $localindustriel . " order by datecreation asc ";
                $param = array(ADMINNATIONNAL, ADMINLOCAL, $idcentrale_centrale);
            } elseif (!empty($_POST['academiqueexterne']) && !empty($_POST['industriel'])) {// CAS OU ON COCHE ACADEMIQUE EXTERNE ET INDUSTRIEL
                $req = $localexterne . " union " . $localindustriel . " order by datecreation asc ";
                $param = array(ADMINNATIONNAL, ADMINLOCAL);
            } elseif (!empty($_POST['academiqueexterne'])) { //CAS OU ON COCHE ACADEMIQUE EXTERNE
                $req = $localexterne . " order by datecreation asc ";
                $param = array(ADMINNATIONNAL, ADMINLOCAL);
            } elseif (!empty($_POST['industriel'])) { // CAS OU ON COCHE ACADEMIQUE INDUSTRIEL
                $req = $localindustriel . " order by datecreation asc ";
                $param = array();
            } elseif (!empty($_POST['academiqueinterne'])) { //CAS OU ON COCHE ACADEMIQUE INTERNE
                $req = $localinterne . " order by datecreation asc ";
                $param = array(ADMINNATIONNAL, ADMINLOCAL, $idcentrale_centrale);
            } elseif (empty($_POST['academiqueexterne']) && empty($_POST['academiqueinterne']) && empty($_POST['industriel'])) {
                echo $msgerreur = TXT_TYPENONSELECTIONNE;
                exit();
            }
        }
    } elseif ($typeUser == ADMINNATIONNAL || $typeUser == ADMINSYSTEM) {
        $interne = "SELECT l.pseudo,u.idcentrale_centrale,u.idtypeutilisateur_typeutilisateur,u.idqualitedemandeurindust_qualitedemandeurindust,u.idqualitedemandeuraca_qualitedemandeuraca,u.idutilisateur,u.prenom,u.nom,u.datecreation,l.actif
FROM loginpassword l,utilisateur u WHERE l.idlogin = u.idlogin_loginpassword 
and u.idqualitedemandeuraca_qualitedemandeuraca is not null and u.idcentrale_centrale is not null ";
        $externe = "SELECT l.pseudo,u.idcentrale_centrale,u.idtypeutilisateur_typeutilisateur ,u.idqualitedemandeurindust_qualitedemandeurindust,u.idqualitedemandeuraca_qualitedemandeuraca,u.idutilisateur,u.prenom,u.nom,u.datecreation,l.actif
FROM loginpassword l,utilisateur u WHERE l.idlogin = u.idlogin_loginpassword 
and idcentrale_centrale is null and u.idqualitedemandeurindust_qualitedemandeurindust is null";
        $industriel = "SELECT l.pseudo,u.idcentrale_centrale,u.idtypeutilisateur_typeutilisateur,u.idqualitedemandeurindust_qualitedemandeurindust,u.idqualitedemandeuraca_qualitedemandeuraca,u.idutilisateur,u.prenom,u.nom,u.datecreation,l.actif
FROM loginpassword l,utilisateur u WHERE l.idlogin = u.idlogin_loginpassword  and idqualitedemandeurindust_qualitedemandeurindust IS NOT NULL ";
        $internenom = "SELECT l.pseudo,u.idcentrale_centrale,u.idtypeutilisateur_typeutilisateur,u.idqualitedemandeurindust_qualitedemandeurindust,u.idqualitedemandeuraca_qualitedemandeuraca,u.idutilisateur,u.prenom,u.nom,u.datecreation,l.actif
FROM loginpassword l,utilisateur u WHERE l.idlogin = u.idlogin_loginpassword 
and u.idqualitedemandeuraca_qualitedemandeuraca is not null and u.idcentrale_centrale is not null and lower(nom) like lower(?)";
        $externenom = "SELECT l.pseudo,u.idcentrale_centrale,u.idtypeutilisateur_typeutilisateur ,u.idqualitedemandeurindust_qualitedemandeurindust,u.idqualitedemandeuraca_qualitedemandeuraca,u.idutilisateur,u.prenom,u.nom,u.datecreation,l.actif
FROM loginpassword l,utilisateur u WHERE l.idlogin = u.idlogin_loginpassword 
and idcentrale_centrale is null and u.idqualitedemandeurindust_qualitedemandeurindust is null and lower(nom) like lower(?)";
        $industrielnom = "SELECT l.pseudo,u.idcentrale_centrale,u.idtypeutilisateur_typeutilisateur,u.idqualitedemandeurindust_qualitedemandeurindust,u.idqualitedemandeuraca_qualitedemandeuraca,u.idutilisateur,u.prenom,u.nom,u.datecreation,l.actif
FROM loginpassword l,utilisateur u WHERE l.idlogin = u.idlogin_loginpassword  and idqualitedemandeurindust_qualitedemandeurindust IS NOT NULL and lower(nom) like lower(?)";        
$admin_national_local_user ="SELECT l.pseudo,u.idcentrale_centrale,u.idtypeutilisateur_typeutilisateur ,u.idqualitedemandeurindust_qualitedemandeurindust,u.idqualitedemandeuraca_qualitedemandeuraca,u.idutilisateur,u.prenom,u.nom,u.datecreation,l.actif
FROM loginpassword l,utilisateur u WHERE l.idlogin = u.idlogin_loginpassword AND idtypeutilisateur_typeutilisateur=?
";
        if (!empty($_POST['nom']) && $_POST['nom'] != '*') {//CAS OU ON SAISIE UN NOM
            $nom = pg_escape_string($_POST['nom']);
            if (!empty($_POST['academiqueinterne']) && !empty($_POST['academiqueexterne']) && !empty($_POST['industriel'])) {//CAS OU ON A COCHE ACADEMIQUE INTERNE EXTERNE ET INDUSTRIEL
                $req = $internenom . " union " . $externenom . " union " . $industrielnom . " order by datecreation asc ";                
                $param = array($nom, $nom,$nom);
            } elseif (!empty($_POST['academiqueinterne']) && !empty($_POST['academiqueexterne'])) {//CAS OU ON A COCHE ACADEMIQUE INTERNE ET EXTERNE
                $req = $internenom . " union " . $externenom . " order by datecreation asc";
                //$param = array(ADMINNATIONNAL, $nom, ADMINNATIONNAL, $nom);
                $param = array($nom, $nom);
            } elseif (!empty($_POST['academiqueinterne']) && !empty($_POST['industriel'])) {//CAS OU ON A COCHE ACADEMIQUE INTERNE ET INDUSTRIEL
                $req = $internenom . " union " . $industrielnom . " order by datecreation asc";
                //$param = array(ADMINNATIONNAL, $nom, ADMINNATIONNAL, $nom);
                $param = array($nom, $nom);
            } elseif (!empty($_POST['academiqueexterne']) && !empty($_POST['industriel'])) {//CAS OU ON A COCHE ACADEMIQUE EXTERNE ET INDUSTRIEL
                $req = $externenom . " union " . $industrielnom . " order by datecreation asc";
                $param = array($nom, $nom);
            } elseif (!empty($_POST['academiqueexterne'])) {//CAS OU ON A COCHE ACADEMIQUE EXTERNE
                $req = $externenom . " order by datecreation asc";
                $param = array($nom);
            } elseif (!empty($_POST['academiqueinterne'])) {//CAS OU ON A COCHE ACADEMIQUE INTERNE
                $req = $internenom . " order by datecreation asc";
                $param = array(ADMINNATIONNAL, $nom);
            } elseif (!empty($_POST['industriel'])) {//CAS OU ON A COCHE ACADEMIQUE INDUSTRIEL
                $req = $industrielnom . " order by datecreation asc";            
                $param = array($nom);
            }
        } else {// RIEN DE SAISIE
            if (!empty($_POST['academiqueinterne']) && !empty($_POST['academiqueexterne']) && !empty($_POST['industriel'])) {//CAS OU ON A COCHE ACADEMIQUE INTERNE EXTERNE ET INDUSTRIEL
                $req = $interne . " union " . $externe . " union " . $industriel . " order by datecreation asc ";                
                $param = array();
            } elseif (!empty($_POST['academiqueinterne']) && !empty($_POST['academiqueexterne'])) {//CAS OU ON A COCHE ACADEMIQUE  EXTERNE ET EXTERNE
                $req = $interne . " union " . $externe . " order by datecreation asc ";
                $param = array('');
            } elseif (!empty($_POST['academiqueinterne']) && !empty($_POST['industriel'])) {//CAS OU ON A COCHE ACADEMIQUE INTERNE  ET INDUSTRIEL
                $req = $interne . " union " . $industriel . " order by datecreation asc ";
                $param = array('');
            } elseif (!empty($_POST['academiqueexterne']) && !empty($_POST['industriel'])) {//CAS OU ON A COCHE ACADEMIQUE  EXTERNE ET INDUSTRIEL
                $req = $externe . " union " . $industriel . " order by datecreation asc ";
                $param = array('');
            } elseif (!empty($_POST['academiqueexterne'])) {//CAS OU ON A COCHE ACADEMIQUE EXTERNE
                $req = $externe . " order by datecreation asc ";
                $param = array('');
            } elseif (!empty($_POST['industriel'])) {//CAS OU ON A COCHE INDUSTRIEL
                $req = $industriel . " order by datecreation asc ";
                $param = array('');
            } elseif (!empty($_POST['academiqueinterne'])) {//CAS OU ON A COCHE ACADEMIQUE INTERNE 
                $req = $interne . " order by datecreation asc ";
                //$param = array(ADMINNATIONNAL);
                $param = array('');
            } elseif (!empty($_POST['admin_national'])) {
                $req = $admin_national_local_user . " order by datecreation asc ";
                $param = array(ADMINNATIONNAL);
            }elseif (!empty($_POST['admin_local'])) {
                $req = $admin_national_local_user . " order by datecreation asc ";
                $param = array(ADMINLOCAL);
            }elseif (!empty($_POST['user'])) {
                $req = $admin_national_local_user . " order by datecreation asc ";
                $param = array(UTILISATEUR);
            }elseif (empty($_POST['academiqueinterne']) && empty($_POST['academiqueexterne']) && empty($_POST['industriel'])&&empty($_POST['admin_national']) && empty($_POST['admin_local']) && empty($_POST['user'])) {
                echo $msgerreur = TXT_TYPENONSELECTIONNE;
                exit();
            }
        }
    }
}
if (!empty($_GET['iduser'])) {
    $req = "select nom,prenom,idtypeutilisateur_typeutilisateur,idutilisateur,datecreation from utilisateur where idutilisateur =? "
            . "and idtypeutilisateur_typeutilisateur <>" . ADMINNATIONNAL . " and idtypeutilisateur_typeutilisateur <>" . ADMINLOCAL . " and idcentrale_centrale=?";
    $param = array($_GET['iduser'], $idcentrale_centrale);
}

if (empty($_POST['academiqueinterne']) && empty($_POST['academiqueexterne']) && empty($_POST['industriel'])&&empty($_POST['admin_national']) && empty($_POST['admin_local']) && empty($_POST['user'])) {
    echo TXT_TYPENONSELECTIONNE;
    exit();
}
//CREATION D'UN NOMBRE ALMEATOIRE
$aleatoire = rand(0,10000000);
if($param==array('')){
    $row = $manager->getList($req);
}else{
    $row = $manager->getListbyArray($req, $param);
}
$fprow = fopen('tmp/userCompte'.$aleatoire.'.json', 'w');
$datausercompte = "";
fwrite($fprow, '{"items": [');
for ($i = 0; $i < count($row); $i++) {
    if ($row[$i]['actif'] == TRUE) {
        $actif = TXT_ACTIF;
    } else {
        $actif = TXT_NONACTIF;
    }
    if (!empty($row[$i]['idqualitedemandeuraca_qualitedemandeuraca']) && !empty($row[$i]['idcentrale_centrale'])) {
        $libelletypeuser = TXT_ACADEMIQUEINTERNE;
    } elseif (!empty($row[$i]['idqualitedemandeurindust_qualitedemandeurindust'])) {
        $libelletypeuser = TXT_INDUSTRIEL;
    } else {
        $libelletypeuser = TXT_ACADEMIQUEEXTERNE;
    }
    $libellecentrale = $manager->getSingle2("SELECT libellecentrale from centrale where idcentrale=?",$row[$i]['idcentrale_centrale']);
    $nb_admin = $manager->getSingle2("SELECT count(idprojet) from utilisateuradministrateur where idutilisateur=?",$row[$i]['idutilisateur']);
    $typecompte = $manager->getSingle2("select libelletype from typeutilisateur where idtypeutilisateur =?", $row[$i]['idtypeutilisateur_typeutilisateur']);
    $datausercompte = "" . '{"pseudo":' . '"' . $row[$i]['pseudo'] . '"' . "," . '"datecreation":' . '"' . $row[$i]['datecreation'] . '"' . "," .
            '"idqualitedemandeurindust_qualitedemandeurindust":' . '"' . $row[$i]['idqualitedemandeurindust_qualitedemandeurindust'] . '"' . "," .
            '"idqualitedemandeuraca_qualitedemandeuraca":' . '"' . $row[$i]['idqualitedemandeuraca_qualitedemandeuraca'] . '"' . "," .
            '"idutilisateur":' . '"' . $row[$i]['idutilisateur'] . '"' . "," .
            '"nom":' . '"' . trim(stripslashes(str_replace("''", "'", trim($row[$i]['nom'])))) . '"' . "," .
            '"prenom":' . '"' . trim(stripslashes(str_replace("''", "'", $row[$i]['prenom']))) . '"' . "," .
            '"actif":' . '"' . $actif . '"' . "," .
            '"typecompte":' . '"' . $typecompte . '"' . "," .
            '"centrale":' . '"' . $libellecentrale . '"' . "," .
            '"libelletypeuser":' . '"' . $libelletypeuser . '"' . "," .
            '"nb_admin":' . '"' . $nb_admin . '"' . "," .
            '"nb":' . '"' . $row[$i]['nb'] . '"' . "},";
    fputs($fprow, $datausercompte);
    fwrite($fprow, '');
}
fwrite($fprow, ']}');
$json_fileuserCompte = "tmp/userCompte".$aleatoire.".json";
$jsonusercompte1 = file_get_contents($json_fileuserCompte);
$jsonUsercompte = str_replace('},]}', '}]}', $jsonusercompte1);
file_put_contents($json_fileuserCompte, $jsonUsercompte);
fclose($fprow);
chmod('tmp/userCompte'.$aleatoire.'.json', 0777);
$i = count($row);
if ($i == 0) {
    echo TXT_NORESULT;
} else {
    echo '<div style="text-align:center;width: auto;height:25px" >' . TXT_NBRESULT . ' :' . $i . '</div>';
}
?>
<div style="height: 350px;">
    <div id="grideusercompte" ></div>
    <script>
        var grideusercompte, dataStore, store;
        require([
            "dojox/grid/DataGrid",
            "dojo/store/Memory",
            "dojo/data/ObjectStore",
            "dojo/request",
            "dojo/domReady!"
        ], function(DataGrid, Memory, ObjectStore, request) {
            request.get("<?php echo '/' . REPERTOIRE .'/tmp/userCompte'.$aleatoire.'.json' ?>", {
                handleAs: "json"
            }).then(function(data) {
                store = new Memory({data: data.items});
                dataStore = new ObjectStore({objectStore: store});
                function hrefFormatterDate(date, idx) {
                    var item = grideusercompte.getItem(idx);
                    var idqualiteaca = item.idqualitedemandeuraca_qualitedemandeuraca;
                    var valeuidqualiteindust = item.idqualitedemandeurindust_qualitedemandeurindust;
                    var iduser = item.idutilisateur;
                    if (valeuidqualiteindust) {
                        return "<a  href=\"<?php echo '/' . REPERTOIRE; ?>/compteadminindust/<?php echo $lang; ?>/" + iduser + "/" + valeuidqualiteindust + " \">" + date + "</a>";
                    } else if (idqualiteaca) {
                        return "<a  href=\"<?php echo '/' . REPERTOIRE; ?>/compteadminaca/<?php echo $lang; ?>/" + iduser + "/" + idqualiteaca + " \">" + date + "</a>";
                    }
                }
                function hrefFormatterNom(nom, idx) {
                    var item = grideusercompte.getItem(idx);
                    var idqualiteaca = item.idqualitedemandeuraca_qualitedemandeuraca;
                    var valeuidqualiteindust = item.idqualitedemandeurindust_qualitedemandeurindust;
                    var iduser = item.idutilisateur;
                    if (valeuidqualiteindust) {
                        return "<a  href=\"<?php echo '/' . REPERTOIRE; ?>/compteadminindust/<?php echo $lang; ?>/" + iduser + "/" + valeuidqualiteindust + " \">" + nom + "</a>";
                    } else if (idqualiteaca) {
                        return "<a  href=\"<?php echo '/' . REPERTOIRE; ?>/compteadminaca/<?php echo $lang; ?>/" + iduser + "/" + idqualiteaca + " \">" + nom + "</a>";
                    }
                }
                function hrefFormatterPrenom(prenom, idx) {
                    var item = grideusercompte.getItem(idx);
                    var idqualiteaca = item.idqualitedemandeuraca_qualitedemandeuraca;
                    var valeuidqualiteindust = item.idqualitedemandeurindust_qualitedemandeurindust;
                    var iduser = item.idutilisateur;
                    if (valeuidqualiteindust) {
                        return "<a  href=\"<?php echo '/' . REPERTOIRE; ?>/compteadminindust/<?php echo $lang; ?>/" + iduser + "/" + valeuidqualiteindust + " \">" + prenom + "</a>";
                    } else if (idqualiteaca) {
                        return "<a  href=\"<?php echo '/' . REPERTOIRE; ?>/compteadminaca/<?php echo $lang; ?>/" + iduser + "/" + idqualiteaca + " \">" + prenom + "</a>";
                    }
                }
                grideusercompte = new DataGrid({
                    store: dataStore,
                    query: {id: "*"},
                    structure: [
                        {name: "<?php echo TXT_CREATEDATE; ?>", field: "datecreation", width: "100px", formatter: hrefFormatterDate},
                        {name: "<?php echo TXT_NOM; ?>", field: "nom", width: "auto", formatter: hrefFormatterNom},
                        {name: "<?php echo TXT_PRENOM; ?>", field: "prenom", width: "auto", formatter: hrefFormatterPrenom},
                        {name: "<?php echo TXT_TYPE_COMPTE; ?>", field: "typecompte", width: "auto"},
                        {name: "<?php echo TXT_COMPTE; ?>", field: "actif", width: "auto"},                        
                        {name: "<?php echo TXT_TYPEUTILISATEUR; ?>", field: "libelletypeuser", width: "auto"},
                        {name: "<?php echo TXT_CENTRALEASSOCIE; ?>", field: "centrale", width: "auto"},
                        {name: "<?php echo TXT_NOMBREPROJET; ?>", field: "nb", width: "auto"},
                        {name: "<?php echo TXT_NBPROJECT_AMINISTERED; ?>", field: "nb_admin", width: "115px"},
                    ]
                }, "grideusercompte");
                grideusercompte.startup();
            });
        });
    </script>
</div>
<?php
BD::deconnecter();
