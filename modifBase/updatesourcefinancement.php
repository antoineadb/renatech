<?php

session_start();
include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);;
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include '../decide-lang.php';
include_once '../class/Securite.php';
if (empty($_POST['idsourcefinancementactuel'])) {
    header('location:/'.REPERTOIRE.'/update_sourcefinancementErr1/' . $lang . '/TXT_MESSAGEERREURSOURCEFINANCEMENTSELECT');
    exit;
} else {
    $idsourcefinancement = $_POST['idsourcefinancementactuel'];
}

if (empty($_POST['modifsourcefinancement'])) {
    header('location:/'.REPERTOIRE.'/update_sourcefinancementErr2/' . $lang . '/TXT_MESSAGEERREURSOURCEFINANCEMENTNONSAISIE');
    exit;
}
if (empty($_POST['modifsourcefinancementen'])) {
    header('location:/'.REPERTOIRE.'/update_sourcefinancementErr2/' . $lang . '/TXT_MESSAGEERREURSOURCEFINANCEMENTNONSAISIE');
    exit;
} else {
    $modifsourcefinancement = stripslashes(Securite::bdd($_POST['modifsourcefinancement']));
    $modifsourcefinancementen = stripslashes(Securite::bdd($_POST['modifsourcefinancementen']));
    $masquesourcefinancement = $manager->getSingle2("select masquesourcefinancement from nomemployeur where idemployeur=? ", $_POST['idsourcefinancementactuel']);
    if ($masquesourcefinancement == 1) {
        $masquesourcefinancement = 'TRUE';
    } else {
        $masquesourcefinancement = 'FALSE';
    }

    if ($lang == 'fr') {
        $sourcefinancement = new Sourcefinancement($idsourcefinancement, $modifsourcefinancement, $masquesourcefinancement, $modifsourcefinancementen);
    } elseif ($lang == 'en') {
        $sourcefinancement = new Sourcefinancement($idsourcefinancement, $modifsourcefinancementen, $masquesourcefinancement, $modifsourcefinancement);
    }
    $manager->updateSourcefinancement($sourcefinancement, $idsourcefinancement);
    header('location:/'.REPERTOIRE.'/update_sourcefinancement/' . $lang . '/TXT_MESSAGESOURCEFINANCEMENTUPDATE');
    exit;
}
BD::deconnecter();
