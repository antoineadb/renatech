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
$numprojet = $manager->getSingle2("select numero from projet where idprojet=?",$idprojet);
//récupération des adresses mail et nom des centrale
//CENTRALE SOURCE      
include_once 'emailprojetphase2/emailCentrale.php';//ON RECUPËRE LES DONNE DE LA CENTRALE EMAIL...
if (!empty($_POST['comment'])) {
    $commentaires = str_replace("\\", "", $_POST['comment']);
    $commentaire = htmlentities(affiche('TXT_COMMENTSTATUT'), ENT_QUOTES, 'UTF-8') . ' ' . htmlentities(strip_tags(str_replace("''", "'", $commentaires)), ENT_QUOTES, 'UTF-8');
} elseif (!empty($_POST['commentairephase2Valeur'])) {
    $commentaires = str_replace("\\", "", $_POST['commentairephase2Valeur']);
    $commentaire = htmlentities(affiche('TXT_COMMENTSTATUT'), ENT_QUOTES, 'UTF-8') . ' ' . htmlentities(strip_tags(str_replace("''", "'",$commentaires)), ENT_QUOTES, 'UTF-8');
} else {
    $commentaire = '';
}
$titreProjet = $manager->getSingle("select titre from projet where numero='" . $numprojet . "'");
$titre = removeDoubleQuote(utf8_decode($titreProjet));
if (!empty($_POST['statutProjet'])) {
    $idStatut = (int) substr($_POST['statutProjet'], -1);
} elseif (!empty($_SESSION['idstatutprojet'])) {
    $idStatut = (int) substr($_SESSION['idstatutprojet'], -1);
} else {
    $idStatut = $manager->getSingle2("select idstatutprojet_statutprojet from concerne where idprojet_projet=?", $idprojet);
}
$txtbodyref = utf8_decode(affiche('TXT_BOBYREF'));
$sujet =  utf8_decode(TXT_DEMANDEPROJET) . ' : ' . $titre . ' ' . $txtbodyref . ' ' . $numprojet;
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              CAS DE LA CREATION D'UN PROJET EN PHASE2
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
if(isset($_POST['creerprojetphase2'])&&$_POST['creerprojetphase2']=='oui' || isset($cas)&&$cas=='creationprojetphase2etape'){//EMAILPROJETPHASE2;       
    include_once 'emailprojetphase2/creerphase2.php';
}elseif
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              TRAITEMENT DU STATUT ACCEPTE
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
($idStatut === ACCEPTE && isset($_POST['creer_phase2'])) {//PROJET PROVENENANT DE LA CREATION D'UNE DEMANDE DE PROJET PHASE1+PHASE2    
    include_once 'emailprojetphase2/emailAccepte.php';   
    //test si autre centrale    
    if (!empty($_POST['etapeautrecentrale']) && $_POST['etapeautrecentrale'] == 'TRUE') {
        include_once 'emailprojetphase2/emaiAutrecentrale.php';
    }
    header('Location: /' . REPERTOIRE . '/project/' . $lang . '/' . $idprojet . '/' . $nbpersonnecentrale . '/' . $idcentrale);
    exit();
} elseif ($idStatut === ACCEPTE){
    include_once 'emailprojetphase2/emailaccepteseul.php';
            //test si autre centrale    
    if (!empty($_POST['etapeautrecentrale']) && $_POST['etapeautrecentrale'] == 'TRUE') {
        include_once 'emailprojetphase2/emailAutrecentraleSeul.php';
    }header('Location: /' . REPERTOIRE . '/accepted_project/' . $lang . '/' . $idprojet . '/' . ACCEPTE.'/'.$numprojet);
}elseif ($idStatut == REFUSE) {
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              TRAITEMENT DU STATUT PROJET REFUSEE
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    include_once 'emailprojetphase2/emailRefuse.php';
} elseif ($idStatut == ENATTENTE) {
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              TRAITEMENT DU STATUT PROJET EN ATTENTE  
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    include_once 'emailprojetphase2/emailenattente.php';
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              TRAITEMENT DU STATUT TRANSFERER A UNE AUTRE CENTRALE
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
}elseif ($idStatut == TRANSFERERCENTRALE) {
    include_once 'emailprojetphase2/emailTransfert.php';
}