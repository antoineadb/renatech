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
    header('Location: /' . REPERTOIRE . '/Login_Error/'.$lang);
}
//RECUPERATION DES EMAIL DES CENTRALES DONT LE PROJET EST TOUJOURTS EN COURS D'ANALYSE
if (!empty($_GET['idprojet'])) {
    $arrayemail = $manager->getListbyArray("SELECT ce.email1, ce.email2, ce.email3, ce.email4, ce.email5 FROM concerne co, centrale ce, projet p WHERE co.idprojet_projet = p.idprojet AND ce.idcentrale = co.idcentrale_centrale AND co.idstatutprojet_statutprojet!=?  AND p.idprojet=?", array(ENCOURSANALYSE, $_GET['idprojet']));
    $numero = $manager->getSingle2("select numero from projet where idprojet=?", $_GET['idprojet']);
}
$nbemail = count($arrayemail);
$arrayemailenvoi = array();
for ($i = 0; $i < $nbemail; $i++) {
    for ($j = 1; $j < 5; $j++) {
        if (!empty($arrayemail[$i]['email' . $j . ''])) {
            array_push($arrayemailenvoi, $arrayemail[$i]['email' . $j . '']);
        }
    }
}

$arrayEmail = array_unique($arrayemailenvoi);
$arraycentraleprojet = $manager->getListbyArray("SELECT ce.libellecentrale FROM concerne co, centrale ce, projet p WHERE co.idprojet_projet = p.idprojet AND ce.idcentrale = co.idcentrale_centrale AND co.idstatutprojet_statutprojet=?  AND p.idprojet=?", array(ENCOURSREALISATION, $_GET['idprojet']));
$nbarraycentraleprojet = count($arraycentraleprojet);
$_libellecentrale = "";
for ($i = 0; $i < $nbarraycentraleprojet; $i++) {
    $_libellecentrale.=$arraycentraleprojet[$i]['libellecentrale'] . ' - ';
}
$libellecentrale = substr($_libellecentrale, 0, -2);

//TRAITEMENT DU PROJET
$sujet = TXT_INFOSUR . utf8_decode(TXT_PROJETNUM) . ' ' . $numero;
$body = htmlentities(str_replace("''","'",stripslashes(affiche('TXT_PROJETNUM1'))), ENT_QUOTES, 'UTF-8') . $numero . '  ' . htmlentities(str_replace("''","'",stripslashes(affiche('TXT_EMAILINFOCENTRALEENCOURS'))), ENT_QUOTES, 'UTF-8') . '  ' . $libellecentrale . '<br><br>' .
        htmlentities(str_replace("''","'",stripslashes(affiche('TXT_SINCERESALUTATION'))), ENT_QUOTES, 'UTF-8') . '<br><br>' . htmlentities(str_replace("''","'",stripslashes(affiche('TXT_RESEAURENATECH'))), ENT_QUOTES, 'UTF-8') . '<br><br>'
        . '<a href="https://www.renatech.org/projet">' . htmlentities(TXT_RETOUR, ENT_QUOTES, 'UTF-8') . '<a>' . '<br><br>' .
        htmlentities(str_replace("''","'",stripslashes(affiche('TXT_DONOTREPLY'))), ENT_QUOTES, 'UTF-8');
envoieEmail($body, $sujet, $arrayEmail, '');
//------------------------------------------------------------------------------------------------------------------------------------------------
//		ENVOI DE L'INFORMATION DE L'ACCEPTATION DU PROJET PAR LA CENTRALE AU DEMANDEUR
//------------------------------------------------------------------------------------------------------------------------------------------------

$infocentrale = $manager->getListbyArray("SELECT email1,email2,email3,email4,email5,libellecentrale FROM concerne,centrale
					WHERE idcentrale_centrale = idcentrale and idprojet_projet=? and idstatutprojet_statutprojet=?", array($idprojet, ENCOURSREALISATION));
$emailCentrale = '';
$centrale = $infocentrale[0]['libellecentrale'];
$nbinfocentrale = count($infocentrale);
for ($i = 0; $i < $nbinfocentrale; $i++) {
    for ($j = 0; $j < 4; $j++) {
        if (!empty($infocentrale[$i]['email' . $j])) {
            $emailCentrale .=$infocentrale[$i]['email' . $j] . '<br>';
        }
    }
}

$numprojet = $manager->getSingle2("select numero from projet where idprojet=?", $idprojet);
$titre = $manager->getSingle2("select titre from projet where idprojet=?", $idprojet);
$infodemandeur = array($manager->getList2("SELECT mail, mailresponsable FROM creer,loginpassword,utilisateur WHERE idutilisateur_utilisateur = idutilisateur
            AND idlogin_loginpassword = idlogin and idprojet_projet=?", $idprojet));
$maildemandeur = array($infodemandeur[0][0]['mail']); //EMAIL DU DEMANDEUR NE PEUT PAS NE PAS EXISTER

$mailCC = array();
if (!empty($infodemandeur[0][0]['mailresponsable'])) {
    array_push($mailCC, $infodemandeur[0][0]['mailresponsable']); //EMAIL DU RESPONSABLE SI IL EXISTE
} else {
    $mailCC = '';
}

$txtbodyref = html_entity_decode((htmlentities(str_replace("''","'",stripslashes(affiche('TXT_BOBYREF'))), ENT_QUOTES, 'UTF-8')));
$sujet1 = TXT_DEMANDEPROJET . ' : ' . $titre . ' ' . $txtbodyref . ' ' . $numprojet;
$body1 = htmlentities(str_replace("''","'",stripslashes(affiche('TXT_MRSMR'))), ENT_QUOTES, 'UTF-8') . '<br><br>' . htmlentities(str_replace("''","'",stripslashes(affiche('TXT_BODYEMAILREALISATION4'))), ENT_QUOTES, 'UTF-8') . '<br><br>' .
        htmlentities(str_replace("''","'",stripslashes(affiche('TXT_BODYEMAILREALISATION1'))), ENT_QUOTES, 'UTF-8') . '<br><br>' . htmlentities(str_replace("''","'",stripslashes(affiche('TXT_BODYEMAILREALISATION2'))), ENT_QUOTES, 'UTF-8') . '<br><br>' .
        htmlentities(str_replace("''","'",stripslashes(affiche('TXT_RAPPEL'))), ENT_QUOTES, 'UTF-8') . '<br><br>' . htmlentities(str_replace("''","'",stripslashes(affiche('TXT_SINCERESALUTATION'))), ENT_QUOTES, 'UTF-8') . '<br><br>' . htmlentities(str_replace("''","'",stripslashes(affiche('TXT_RESEAURENATECH'))), ENT_QUOTES, 'UTF-8') .
        '<br><br>' . htmlentities(str_replace("''","'",stripslashes(affiche('TXT_EMAILADDRESSCENTRAL'))), ENT_QUOTES, 'UTF-8') . ' ' . htmlentities($centrale, ENT_QUOTES, 'UTF-8') . ' <br> ' . $emailCentrale . '<br>' .
        htmlentities(str_replace("''","'",stripslashes(affiche('TXT_RESEAURENATECH'))), ENT_QUOTES, 'UTF-8') . "<a href='https://www.renatech.org/projet' >" . TXT_RETOUR . '</a><br><br>' .
        htmlentities(str_replace("''","'",stripslashes(affiche('TXT_DONOTREPLY'))), ENT_QUOTES, 'UTF-8');

envoieEmail($body1, $sujet1, $maildemandeur, $mailCC); //envoie de l'email au responsable centrale et au copiste
//------------------------------------------------------------------------------------------------------------------------------------------------
//			FIN
//------------------------------------------------------------------------------------------------------------------------------------------------
header('Location: /' . REPERTOIRE . '/modifProjetRespCentralevalide.php?lang=' . $lang . '&idprojet=' . $idprojet . 'statut=' . ENCOURSREALISATION . '&numprojet=' . $numprojet . '&idcentrale=' . $idcentrale);

