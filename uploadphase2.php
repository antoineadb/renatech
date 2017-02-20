<?php

include_once 'outils/constantes.php';
include_once 'decide-lang.php';
include_once 'outils/toolBox.php';
include_once 'class/Manager.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
if (isset($_GET['idprojet'])) {
    $idprojet = $_GET['idprojet'];
} else {
    $idprojet = $_SESSION['idprojet'];
}

$idcentrale = $manager->getSingle2("SELECT idcentrale_centrale	FROM utilisateur,loginpassword WHERE idlogin = idlogin_loginpassword and pseudo =?", $_SESSION['pseudo']);
$numero = $manager->getSingle2('select numero from projet where idprojet=?', $idprojet);

if ($_POST['save'] == 'non') {//SAUVEGARDE ON NE TIENT PAS COMPTE DU STATUT
    if (isset($_POST['statutProjet'])) {
        $idstatutprojet = (int) substr($_POST['statutProjet'], 2, 1);
    } elseif (isset($_SESSION['idstatutprojet'])) {
        $idstatutprojet = (int) $_SESSION['idstatutprojet'];
    }
}elseif($_POST['save'] == 'oui'){
    if(!empty($_POST['idstatut'])){
        $idstatutprojet =$_POST['idstatut'];
    }else{
        $idstatutprojet =$_SESSION['idstatutprojet'];
    }
}
if (isset($_POST['etautrecentrale']) && !empty($_POST['etautrecentrale'])) {
    $etautrecentrale = Securite::bdd(trim($_POST['etautrecentrale']));
} else {
    $etautrecentrale = '';
}
$idautrecentrale = '';
if (isset($_POST['autrecentrale']) && !empty($_POST['autrecentrale'])) {
    for ($i = 0; $i < count($_POST['autrecentrale']); $i++) {
        $idautrecentrale.=$manager->getSingle2("select idcentrale from centrale where libellecentrale=?", $_POST['autrecentrale'][$i]) . ',';
    }
}

$idAutrecentrale = substr($idautrecentrale, 0, -1);
if ($cas == 'miseAJourEmail') {
    header('Location: /' . REPERTOIRE . '/EmailProjephase2tMAJ.php?lang=' . $lang . '&idprojet=' . $idprojet . '&statut=' . $idstatutprojet . '&nbpersonne=' . $_POST['nombrePersonneCentrale']);
    exit();
}elseif($cas=='creationprojetphase2etape' || $cas=='creerprojetphase2'){
    header('Location: /' . REPERTOIRE . '/EmailProjetphase2Session.php?lang=' . $lang . '&idprojet=' . $idprojet . '&idautrecentrale=' . $idAutrecentrale . '&statut=' . $idstatutprojet . '&nbpersonne=' 
            . $_POST['nombrePersonneCentrale'] . '&etautrecentrale=' . $etautrecentrale);
}elseif ($cas == 'chgstatut'|| $cas == 'chgstatutAutCentraleEmailJammaisEnvoye'|| $cas == 'chgstatutAutCentraleEmailDejaEnvoye' ) {
    if ($idstatutprojet != REFUSE) {//email?
        header('Location: /' . REPERTOIRE . '/update_project2/' . $lang . '/' . $idprojet . '/' . $idstatutprojet . '/' . $_POST['nombrePersonneCentrale']);
        exit();
    }
}elseif($cas=='miseAJourEmailAutreCentrale'){
    header('Location: /' . REPERTOIRE . '/EmailProjetphase2Session.php?lang=' . $lang . '&idprojet=' . $idprojet . '&idautrecentrale=' . $idAutrecentrale . '&statut=' . $idstatutprojet 
            . '&nbpersonne=' . $_POST['nombrePersonneCentrale'] . '&etautrecentrale=' . $etautrecentrale.'&majcentrale=oui');
}elseif($cas=='miseAJourEmailautreEmailpremierefois'){
     include 'outils/envoiEmailAutreCentrale.php';
    header('Location: /' . REPERTOIRE . '/EmailProjephase2tMAJ.php?lang=' . $lang . '&idprojet=' . $idprojet . '&statut=' . $idstatutprojet . '&nbpersonne=' . $_POST['nombrePersonneCentrale']);
}else {
    $sCentrale ="";
    $aCentrale = $manager->getList2("select libellecentrale from centrale,concerne,projet where idcentrale_centrale=idcentrale and idprojet=idprojet_projet and idprojet=?", $idprojet);
    foreach ($aCentrale as $key => $centrales) {
        $sCentrale .=$centrales[0].',';    
    }
    $centrale =  substr($sCentrale,0,-1);
    $libelleStatut = $manager->getSingle2("select libellestatutprojet from statutprojet where idstatutprojet=?", $idstatutprojet);
    $nomPrenomDemandeur = $manager->getList2("SELECT nom, prenom FROM creer,utilisateur WHERE idutilisateur_utilisateur = idutilisateur and idprojet_projet = ?", $idprojet);
    $idcentrale = $manager->getsingle2('select idcentrale_centrale from concerne where idprojet_projet=?',$idprojet);
    createLogInfo(NOW, 'Sauvegarde du projet n° '.$numero.' :  Centrale: '. $centrale, 'Demandeur: '.$nomPrenomDemandeur[0]['nom'] .
        ' ' .$nomPrenomDemandeur[0]['prenom'] , removeDoubleQuote($libelleStatut), $manager,$idcentrale);
    header('Location: /' . REPERTOIRE . '/update_project2/' . $lang . '/' . $idprojet . '/' . $idstatutprojet . '/' . $_POST['nombrePersonneCentrale']);
}