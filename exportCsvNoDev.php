<?php

session_start();
include 'decide-lang.php';
include 'class/Manager.php';
include_once 'outils/toolBox.php';
include_once 'outils/constantes.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER

if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
    $pseudo = $_SESSION['pseudo'];
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
$arraycentrale = $manager->getList2("SELECT libellecentrale,idcentrale FROM centrale,loginpassword,utilisateur WHERE idlogin = idlogin_loginpassword AND  idcentrale_centrale = idcentrale AND pseudo=?", $_SESSION['pseudo']);
if (isset($_GET['annee']) && !empty($_GET['annee']) && $_GET['annee'] != -1) {
    $annee = $_GET['annee'];
    $param = array($arraycentrale[0]['idcentrale'], ENCOURSREALISATION, $annee, $arraycentrale[0]['idcentrale'], FINI, $annee, $arraycentrale[0]['idcentrale'], CLOTURE, $annee);
    $stringSQL = " and EXTRACT(YEAR from datedebutprojet)=? ";
} elseif (isset($_GET['annee']) && !empty($_GET['annee']) && $_GET['annee'] == -1) {
    $annee = "ALL";
    $stringSQL = "";
    $param = array($arraycentrale[0]['idcentrale'], ENCOURSREALISATION, $arraycentrale[0]['idcentrale'], FINI, $arraycentrale[0]['idcentrale'], CLOTURE);
} else {die;
     $annee = "ALL";
    $stringSQL = "";
    $param = array($arraycentrale[0]['idcentrale'], ENCOURSREALISATION, $arraycentrale[0]['idcentrale'], FINI, $arraycentrale[0]['idcentrale'], CLOTURE);
}

//------------------------------------------------------------------------
//-----RECUPERATION DU LIBELLE DE LA CENTRALE DU RESPONSABLE CENTRALE-----
//------------------------------------------------------------------------
$idcentrale = $manager->getSingle2("SELECT idcentrale FROM centrale , utilisateur ,loginpassword  WHERE idcentrale_centrale = idcentrale AND idlogin_loginpassword = idlogin AND pseudo=?", $pseudo);
$libellecentrale = $manager->getSingle2("SELECT libellecentrale FROM centrale , utilisateur ,loginpassword  WHERE idcentrale_centrale = idcentrale AND idlogin_loginpassword = idlogin AND pseudo=?", $pseudo);

$data = utf8_decode("Project;BTR Platform;Project leader /Entity / Town;Activities supported;Thematics");
$data .= "\n";
$sql="SELECT distinct idprojet,p.idautrethematique_autrethematique,titre,libellethematiqueen,libellethematique,prenom,idutilisateur,nom,numero   FROM projet p
    LEFT JOIN thematique on idthematique = p.idthematique_thematique  LEFT JOIN creer cr on cr.idprojet_projet = p.idprojet  LEFT JOIN utilisateur on idutilisateur_utilisateur = idutilisateur 
    LEFT JOIN concerne co on co.idprojet_projet = p.idprojet WHERE co.idcentrale_centrale =?  AND trashed =FALSE and co.idstatutprojet_statutprojet in(?,?,?)  AND p.idprojet not in (select idprojet from rapport)";
if(isset($_GET['annee']) && ($_GET['annee'])!=-1){
    $dateChx = (int) $_GET['annee'];
    $arrayNoDev =$manager->getListbyArray($sql." AND EXTRACT(YEAR from dateprojet)=? ", array(IDCENTRALEUSER,ENCOURSREALISATION,FINI,CLOTURE, $dateChx));
}elseif(isset($_GET['annee']) && ($_GET['annee'])==-1){
    $arrayNoDev =$manager->getListbyArray($sql, array(IDCENTRALEUSER,ENCOURSREALISATION,FINI,CLOTURE));
}else{
    $arrayNoDev =$manager->getListbyArray($sql, array(IDCENTRALEUSER,ENCOURSREALISATION,FINI,CLOTURE));
}


$nbarrayprojet = count($arrayNoDev);
$ressources = "";
if ($nbarrayprojet != 0) {
    for ($i = 0; $i < $nbarrayprojet; $i++) {
        $arrayRessources = $manager->getList2("SELECT  libelleressourceen FROM ressource,ressourceprojet WHERE idressource = idressource_ressource and idprojet_projet =?", $arrayNoDev[$i]['idprojet']);
        for ($j = 0; $j < count($arrayRessources); $j++) {
            $ressources.=$arrayRessources[$j]['libelleressourceen'] . ' / ';
        }
        $ressources = substr($ressources, 0, -2);
        //Entity
        $arrayEntity = $manager->getList2("SELECT codeunite,libelleautrecodeunite FROM utilisateur,centrale,autrecodeunite WHERE idcentrale_centrale = idcentrale AND idautrecodeunite = idautrecodeunite_autrecodeunite "
                . "and idutilisateur=?", $arrayNoDev[$i]['idutilisateur']);

        if ($arrayNoDev[$i]['libellethematiqueen'] != 'Others') {
            $thematic = $arrayNoDev[$i]['libellethematiqueen'];
        } else {
            $thematic = $manager->getSingle2("select libelleautrethematique from autrethematique where idautrethematique=?", $arrayNoDev[$i]['idautrethematique_autrethematique']);
        }
        if (isset($arrayEntity[0]['libelleautrecodeunite']) && $arrayEntity[0]['libelleautrecodeunite'] != 'n/a') {
            $libelleEntity = ' / ' . $arrayEntity[0]['libelleautrecodeunite'];
        } elseif (isset($arrayEntity[0]['codeunite']) && !empty($arrayEntity[0]['codeunite'])) {
            $libelleEntity = ' / ' . $arrayEntity[0]['codeunite'];
        } else {
            $libelleEntity = ' / ' . "No entity";
        }
        $originalDate = date('d-m-Y');
          $ville =$manager->getSingle2("SELECT ville FROM creer,utilisateur WHERE  idutilisateur_utilisateur = idutilisateur and idprojet_projet = ?", $arrayNoDev[$i]['idprojet']);

        $projetLeader = removeDoubleQuote(stripslashes(trim($arrayNoDev[$i]['nom']))) . ' - ' . removeDoubleQuote(stripslashes(trim($arrayNoDev[$i]['prenom']))) . ' - ' . removeDoubleQuote(stripslashes(trim($libelleEntity))) .
            ' / ' . $ville;
        
        $data .= "" .
                removeDoubleQuote(stripslashes(utf8_decode(trim($arrayNoDev[$i]['titre'])))) . ";" .
                $libellecentrale . ";" .
                removeDoubleQuote(stripslashes(utf8_decode(trim($projetLeader)))) . ";" .
                $ressources . ";" .
                 removeDoubleQuote(stripslashes(utf8_decode(trim($thematic)))) . "\n";
        $ressources = "";
    }

    $libcentrale = $manager->getSingle2("SELECT libellecentrale FROM loginpassword,centrale,utilisateur WHERE idlogin_loginpassword = idlogin AND idcentrale_centrale = idcentrale AND pseudo=?", $pseudo);
// Déclaration du type de contenu    
    header("Content-type: application/vnd.ms-excel;charset=UTF-8");
    header("Content-disposition: attachment; filename=exportprojetsansDev_" . $libcentrale . '_' . $originalDate . ".csv");
    print $data;
    exit;
} else {
    echo ' <script>alert("' . utf8_decode(TXT_PASDEPROJET) . '");window.location.replace("/' . REPERTOIRE . '/noDevProject/' . $lang . '")</script>';
    exit();
}
BD::deconnecter();
