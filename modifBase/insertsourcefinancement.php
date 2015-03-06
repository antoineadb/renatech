<?php

include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include '../decide-lang.php';
include_once '../class/Securite.php';

if (empty($_POST['modifsourcefinancement'])) {
    header('location:/'.REPERTOIRE.'/insert_sourcefinancementErr1/' . $lang . '/TXT_MESSAGEERREURSOURCEFINANCEMENTNONSAISIE');
    exit;
}
if (empty($_POST['modifsourcefinancementen'])) {
    header('location:/'.REPERTOIRE.'/insert_sourcefinancementErr1/' . $lang . '/TXT_MESSAGEERREURSOURCEFINANCEMENTNONSAISIE');
    exit;
}else {
    $modifsourcefinancement = stripslashes(Securite::bdd($_POST['modifsourcefinancement']));
				$modifsourcefinancementen = stripslashes(Securite::bdd($_POST['modifsourcefinancementen']));
    $idsourcefinancement = $manager->getSingle2("SELECT idsourcefinancement FROM sourcefinancement Where libellesourcefinancement =?",trim($modifsourcefinancement));
    if (!empty($idsourcefinancement)) {
        header('location:/'.REPERTOIRE.'/insert_sourcefinancementErr2/' . $lang . '/TXT_MESSAGESERVEURSOURCEFINANCEMENTEXISTE');
        exit;
    } else {
        $idnewsourcefinancement = $manager->getSingle("SELECT max(idsourcefinancement) FROM sourcefinancement;") + 1;
								 if ($lang == 'fr') {
            $sourcefinancement	=	new	Sourcefinancement($idnewsourcefinancement,	$modifsourcefinancement,	FALSE,	$modifsourcefinancementen);
        } elseif ($lang == 'en') {
            $sourcefinancement	=	new	Sourcefinancement($idnewsourcefinancement,	$modifsourcefinancementen,	FALSE,	$modifsourcefinancement);
        }								
        $manager->addSourcefinancement($sourcefinancement);
        header('location:/'.REPERTOIRE.'/insert_sourcefinancement/' . $lang . '/TXT_MESSAGESERVEURSOURCEFINANCEMENT');
        exit;
    }
}
BD::deconnecter();
