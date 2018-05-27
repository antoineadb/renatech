<?php

include_once 'decide-lang.php';
include_once 'class/email.php';
include_once 'class/Manager.php';
include_once 'outils/constantes.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include_once 'outils/toolBox.php';
if (!empty($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
if (!empty($_GET['idprojet'])) {
    $idprojet = $_GET['idprojet'];
} elseif (!empty($_SESSION['idprojet'])) {
    $idprojet = $_SESSION['idprojet'];
}

$numprojet = $manager->getSingle2("select numero from projet where idprojet=?", $idprojet);
//récupération des adresses mail et nom des centrale
//CENTRALE SOURCE      
include_once 'emailprojetphase2/emailCentrale.php'; //ON RECUPËRE LES DONNE DE LA CENTRALE EMAIL...
if (!empty($_POST['comment'])) {
    $commentaires = str_replace("\\", "", $_POST['comment']);
    $commentaire = htmlentities(affiche('TXT_COMMENTSTATUT'), ENT_QUOTES, 'UTF-8') . ' ' . htmlentities(strip_tags(removeDoubleQuote($commentaires)), ENT_QUOTES, 'UTF-8');
} elseif (!empty($_POST['commentairephase2Valeur'])) {
    $commentaires = str_replace("\\", "", $_POST['commentairephase2Valeur']);
    $commentaire = htmlentities(affiche('TXT_COMMENTSTATUT'), ENT_QUOTES, 'UTF-8') . ' ' . htmlentities(strip_tags(removeDoubleQuote($commentaires)), ENT_QUOTES, 'UTF-8');
} else {
    $commentaire = '';
}
$titre = $manager->getSingle2("select titre from projet where numero=?", $numprojet);
if (!empty($_POST['statutProjet'])) {
    $idStatut = (int) substr($_POST['statutProjet'], -1);
} elseif (!empty($_SESSION['idstatutprojet'])) {
    $idStatut = (int) substr($_SESSION['idstatutprojet'], -1);
} else {
    $idStatut = $manager->getSingle2("select idstatutprojet_statutprojet from concerne where idprojet_projet=?", $idprojet);
}

$nbpersonnecentrale = $manager->getSingle2("select count(idpersonneaccueilcentrale_personneaccueilcentrale) from projetpersonneaccueilcentrale where idprojet_projet=?", $idprojet);

$txtbodyref = utf8_decode(affiche('TXT_BOBYREF'));
$sujet = utf8_decode(TXT_DEMANDEPROJET) . ' : ' . utf8_decode($titre) . ' ' . $txtbodyref . ' ' . $numprojet;

//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              CAS DE LA CREATION D'UN PROJET EN PHASE2
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
if (isset($cas) && $cas == 'creationprojetphase2') {
    include 'emailprojetphase2/creerphase2.php';
    header('Location: /' . REPERTOIRE . '/project/' . $lang . '/' . $idprojet . '/' . $nbpersonnecentrale . '/' . $idcentrale);
    exit();
}

if (isset($_POST['creerprojetphase2']) && $_POST['creerprojetphase2'] == 'oui' || isset($cas) && $cas == 'creationprojetphase2etape') {//EMAILPROJETPHASE2;
    include 'emailprojetphase2/creerphase2.php';
    exit();
} elseif($idStatut === ACCEPTE && isset($_POST['creer_phase2'])) {//PROJET PROVENENANT DE LA CREATION D'UNE DEMANDE DE PROJET PHASE1+PHASE2    
    include 'emailprojetphase2/emailAccepte.php';   //-->OK
    //test si autre centrale    
    if (!empty($_POST['etapeautrecentrale']) && $_POST['etapeautrecentrale'] == 'TRUE') {
        include 'emailprojetphase2/emaiAutrecentrale.php'; //-->OK
    }
    header('Location: /' . REPERTOIRE . '/project/' . $lang . '/' . $idprojet . '/' . $nbpersonnecentrale . '/' . $idcentrale);
    exit();
} elseif (isset($_GET['majcentrale']) && $_GET['majcentrale'] == 'oui') {//MISE A JOUR AVEC ENVOI EMAIL AUTRE CENTRALE DEJA ENVOYE            
    include 'emailAutreCentralesMAJ.php'; //-->OK
    session_abort();
    include 'EmailProjephase2tMAJ.php';    //--> OK
} elseif (isset($_GET['majcentrale']) && $_GET['majcentrale'] == 'non') {//MISE A JOUR SANS ENVOI EMAIL AUTRE CENTRALE DEJA ENVOYE    
    include 'emailprojetphase2/emailAutrecentralesecondEnvoiAccepte.php';
    exit();
} elseif ($idStatut == ENATTENTEPHASE2) {
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              TRAITEMENT DU STATUT PROJET EN ATTENTE  
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    include 'emailprojetphase2/emailenattente.php';
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              TRAITEMENT DU STATUT TRANSFERER A UNE AUTRE CENTRALE
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
} elseif ($idStatut == TRANSFERERCENTRALE) {
    include 'emailprojetphase2/emailTransfert.php';
} elseif ($idStatut == REFUSE) {
    include 'emailprojetphase2/emailRefuse.php';
}elseif ($idStatut == ACCEPTE) {
    include 'emailprojetphase2/emailaccepteseul.php';/// avoir
}
