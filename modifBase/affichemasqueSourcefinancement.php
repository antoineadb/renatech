<?php

session_start();
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include '../decide-lang.php';
include_once '../class/Securite.php';
include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);
if (empty($_POST['idsourcefinancementactuel'])) {
    header('location:/'.REPERTOIRE.'/hide_sourcefinancementErr1/' . '/TXT_MESSAGEERREURSOURCEFINANCEMENTSELECT');
    exit();
} else {
    $idsourcefinancementactuel = $_POST['idsourcefinancementactuel'];
}
if (empty($_POST['modifsourcefinancement'])) {
    header('location:/'.REPERTOIRE.'/hide_sourcefinancementErr2/' . $lang . '/TXT_MESSAGEERREURSOURCEFINANCEMENTNONSAISIE');
    exit();
}
if (empty($_POST['modifsourcefinancement'])) {
    header('location:/'.REPERTOIRE.'/hide_sourcefinancementErr2/' . $lang . '/TXT_MESSAGEERREURSOURCEFINANCEMENTNONSAISIE');
    exit();
} else {
    $modifsourcefinancement = stripslashes(Securite::bdd($_POST['modifsourcefinancement']));
    $modifsourcefinancementen = stripslashes(Securite::bdd($_POST['modifsourcefinancementen']));
    if ($lang == 'fr') {
        $idsourcefinancementactuel1 = $manager->getSingle2("SELECT  idsourcefinancement FROM sourcefinancement where libellesourcefinancement =?", $modifsourcefinancement);
    } elseif ($lang == 'en') {
        $idsourcefinancementactuel1 = $manager->getSingle2("SELECT  idsourcefinancement FROM sourcefinancement where libellesourcefinancementen =?", $modifsourcefinancementen);
    }
    
    if (empty($idsourcefinancementactuel1)) {
        header('location:/'.REPERTOIRE.'/hide_sourcefinancementErr2/' . $lang . '/TXT_MESSAGEERREURSOURCEFINANCEMENTNONSAISIE');
        exit();
    } else {
        if (isset($_POST['masquesourcefinancement']) && $_POST['masquesourcefinancement'] == TXT_MASQUER) {
            if ($lang == 'fr') {
                $sourcefinancement = new Sourcefinancement($idsourcefinancementactuel, $modifsourcefinancement, TRUE, $modifsourcefinancementen);
            } elseif ($lang == 'en') {
                $sourcefinancement = new Sourcefinancement($idsourcefinancementactuel, $modifsourcefinancementen, TRUE, $modifsourcefinancement);
            }
            $manager->afficheHidesourcefinancement($sourcefinancement, $idsourcefinancementactuel);
            header('location:/'.REPERTOIRE.'/hide_sourcefinancement/' . $lang . '/TXT_MESSAGESERVEURSOURCEFINANCEMENTMASQUER');
            exit();
        } elseif (isset($_POST['affichesourcefinancement']) && $_POST['affichesourcefinancement'] == TXT_REAFFICHER) {
            if ($lang == 'fr') {
                $sourcefinancement = new Sourcefinancement($idsourcefinancementactuel, $modifsourcefinancement, FALSE, $modifsourcefinancementen);
            } elseif ($lang == 'en') {
                $sourcefinancement = new Sourcefinancement($idsourcefinancementactuel, $modifsourcefinancementen, FALSE, $modifsourcefinancement);
            }
            $manager->afficheHidesourcefinancement($sourcefinancement, $idsourcefinancementactuel);
            header('location:/'.REPERTOIRE.'/show_sourcefinancement/' . $lang . '/TXT_MESSAGESERVEURSOURCEFINANCEMENTAFFICHE');
            exit();
        }
    }
}
BD::deconnecter();
?>