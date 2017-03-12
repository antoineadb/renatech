<?php

include_once 'decide-lang.php';
include 'class/email.php';
include_once 'class/Manager.php';
include_once 'outils/constantes.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include_once 'outils/toolBox.php';
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
//RECUPERATION DES EMAIL DES CENTRALES DONT LE PROJET EST TOUJOURTS EN COURS D'ANALYSE
if (!empty($_GET['idprojet'])) {
    $arrayemail = $manager->getListbyArray("SELECT ce.email1, ce.email2, ce.email3, ce.email4, ce.email5 FROM concerne co, centrale ce, projet p	"
            . "WHERE co.idprojet_projet = p.idprojet AND ce.idcentrale = co.idcentrale_centrale AND co.idstatutprojet_statutprojet=?  AND p.idprojet=?", array(ENCOURSANALYSE, $_GET['idprojet']));
    $numero = $manager->getSingle2("select numero from projet where idprojet=?", $_GET['idprojet']);
}
$nbemail = count($arrayemail);
if (!empty($nbemail)) {
    $arrayemailenvoi = array();
    for ($i = 0; $i < $nbemail; $i++) {
        for ($j = 1; $j < 5; $j++) {
            if (!empty($arrayemail[$i]['email' . $j . ''])) {
                array_push($arrayemailenvoi, $arrayemail[$i]['email' . $j . '']);
            }
        }
    }

    $arrayEmail = array_unique($arrayemailenvoi);
    
    $arraycentraleprojet = $manager->getListbyArray("SELECT ce.libellecentrale FROM concerne co, centrale ce, projet p WHERE co.idprojet_projet = p.idprojet AND ce.idcentrale = co.idcentrale_centrale "
            . "AND co.idstatutprojet_statutprojet=?  AND p.idprojet=?", array(ACCEPTE, $_GET['idprojet']));
    $nbarraycentraleprojet = count($arraycentraleprojet);
    $_libellecentrale = "";
    for ($i = 0; $i < $nbarraycentraleprojet; $i++) {
        $_libellecentrale.=$arraycentraleprojet[$i]['libellecentrale'] . ' - ';
    }
    $libellecentrale = substr($_libellecentrale, 0, -2);

//TRAITEMENT DU PROJET
//------------------------------------------------------------------------------------------------------------------------------------------------
//		ENVOI DE L'INFORMATION DE L'ACCEPTATION DU PROJET PAR LA CENTRALE AU DEMANDEUR
//------------------------------------------------------------------------------------------------------------------------------------------------
    $sujet = TXT_INFOSUR . utf8_decode(affiche('TXT_PROJETNUM0')) . ' ' . $numero;
    $body = htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_MRSMR'))), ENT_QUOTES, 'UTF-8') . '<br><br>' .htmlentities(affiche('TXT_PROJETNUM0'), ENT_QUOTES, 'UTF-8') . $numero . '  ' . htmlentities(affiche('TXT_PROJETACCEPTECENTRALE'), ENT_QUOTES, 'UTF-8') . '  ' . $libellecentrale . '<br><br>' .
            htmlentities(affiche('TXT_SINCERESALUTATION'), ENT_QUOTES, 'UTF-8') . '<br><br>' . htmlentities(affiche('TXT_RESEAURENATECH'), ENT_QUOTES, 'UTF-8') . '<br><br>'
            . '<a href="https://www.renatech.org/projet">' . htmlentities(TXT_RETOUR, ENT_QUOTES, 'UTF-8') . '<a>' . '<br><br>' .
            htmlentities(affiche('TXT_DONOTREPLY'), ENT_QUOTES, 'UTF-8');
    envoieEmail($body, $sujet, $arrayEmail, null);
}
//------------------------------------------------------------------------------------------------------------------------------------------------
//		FIN DE L'ENVOI DE L'INFORMATION 
//------------------------------------------------------------------------------------------------------------------------------------------------
//RECUPERATION DES EMAILS DE LA CENTRALE SELECTIONNEE ET DU LIBELLE DE LA CENTRALE
$infocentrale = $manager->getListbyArray("SELECT email1,email2,email3,email4,email5,libellecentrale FROM concerne,centrale
					WHERE idcentrale_centrale = idcentrale and idprojet_projet=? and idstatutprojet_statutprojet=?", array($idprojet, ACCEPTE));

$emailCentrale = '';
$nbinfocentrale = count($infocentrale);
for ($i = 0; $i < $nbinfocentrale; $i++) {
    for ($j = 0; $j < 4; $j++) {
        if (!empty($infocentrale[$i]['email' . $j])) {
            $emailCentrale .=$infocentrale[$i]['email' . $j] . '<br>';
        }
    }
}
$mailCC=array();
for ($i = 0; $i < $nbinfocentrale; $i++) {
    for ($j = 0; $j < 4; $j++) {
        if (!empty($infocentrale[$i]['email' . $j])) {
            array_push($mailCC, $infocentrale[$i]['email' . $j]);
        }
    }
}


$arraycentrale = $manager->getListbyArray("SELECT libellecentrale FROM concerne,centrale WHERE idcentrale_centrale = idcentrale and idprojet_projet=? and idstatutprojet_statutprojet=?",array($idprojet, ACCEPTE));
$nbarraycentrale = count($arraycentrale);
$Centrale = "";
for ($i = 0; $i < $nbarraycentrale; $i++) {
    $Centrale .=$arraycentrale[$i]['libellecentrale'] . " - ";
}
$centrale = substr($Centrale, 0, -2);

$numprojet = $manager->getSingle2("select numero from projet where idprojet=?", $idprojet);
$titre = $manager->getSingle2("select titre from projet where idprojet=?", $idprojet);
$infodemandeur = array($manager->getList2("SELECT mail, mailresponsable FROM creer,loginpassword,utilisateur WHERE idutilisateur_utilisateur = idutilisateur
            AND idlogin_loginpassword = idlogin and idprojet_projet=?", $idprojet));


$maildemandeur = array($infodemandeur[0][0]['mail']); //EMAIL DU DEMANDEUR NE PEUT PAS NE PAS EXISTER

if (!empty($infodemandeur[0][0]['mailresponsable'])) {
    array_push($mailCC, $infodemandeur[0][0]['mailresponsable']); //EMAIL DU RESPONSABLE SI IL EXISTE
} 

$txtbodyref = utf8_decode(affiche('TXT_BOBYREF'));
$sujet1 = TXT_DEMANDEPROJET . ' : ' . $titre . ' ' . $txtbodyref . ' ' . $numprojet;
$body1 = htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_MRSMR'))), ENT_QUOTES, 'UTF-8') . '<br><br>' . 
        htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_BODYEMAILPHASE20'))), ENT_QUOTES, 'UTF-8') . '<br>' .
        htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_BODYEMAILPHASE21'))), ENT_QUOTES, 'UTF-8') .
        "<a href='https://www.renatech.org/projet'>" . str_replace("''", "'", stripslashes(affiche('TXT_BODYEMAILPHASE22'))) . "</a>" . '<br><br>' .
        htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_BODYEMAILPHASE23'))), ENT_QUOTES, 'UTF-8') . '<br><br>' .
        htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_RAPPELINSERTLOGO'))), ENT_QUOTES, 'UTF-8') . '<br><br>' . 
        htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_SINCERESALUTATION'))), ENT_QUOTES, 'UTF-8') . '<br><br>' . 
        htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_RESEAURENATECH'))), ENT_QUOTES, 'UTF-8') . '<br><br>' .
        htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_RESPONSABLEBODYTEMAIL10'))), ENT_QUOTES, 'UTF-8') . ' ' .
        htmlentities($centrale, ENT_QUOTES, 'UTF-8') . '<br>' . $emailCentrale . '<br><br>' .
        '<a href="https://www.renatech.org/projet">' . htmlentities(TXT_RETOUR, ENT_QUOTES, 'UTF-8') . '<a>' . '<br><br>      ' .
        htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_DONOTREPLY'))), ENT_QUOTES, 'UTF-8') . '<br><br>';
envoieEmail($body1, $sujet1, $maildemandeur, $mailCC); //envoie de l'email au responsable centrale et au copiste
//------------------------------------------------------------------------------------------------------------------------------------------------
//			FIN
//------------------------------------------------------------------------------------------------------------------------------------------------
header('Location: /' . REPERTOIRE . '/modifProjetRespCentralevalide.php?lang=' . $lang . '&idprojet=' . $idprojet . 'statut=' . ACCEPTE . '&numprojet=' . $numprojet . '&idcentrale=' . $idcentrale);

