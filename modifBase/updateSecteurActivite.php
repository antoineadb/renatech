<?php

session_start();
include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);
include_once '../class/Manager.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include '../decide-lang.php';
include_once '../class/Securite.php';

if (empty($_POST['idlibellesecteuractiviteactuel'])) {
    header('location:/'.REPERTOIRE.'/update_sectorErr1/' . $lang . '/TXT_MESSAGEERREURGESECTEURONSELECT');
    exit;
} else {
    $idsecteuractivite = $_POST['idlibellesecteuractiviteactuel'];
}

if (empty($_POST['modifsecteuractivite'])) {
    header('location:/'.REPERTOIRE.'/update_sectorErr2/' . $lang . '/TXT_MESSAGEERREURSECTEURNONSAISIE');
    exit;
}if (empty($_POST['modifsecteuractiviteen'])) {
    header('location:/'.REPERTOIRE.'/update_sectorErr3/' . $lang . '/TXT_MESSAGEERREURSECTEURNONSAISIE');
    exit;
} else {
    $modifsecteuractivite = stripslashes(Securite::bdd($_POST['modifsecteuractivite']));
    $modifsecteuractiviteen = stripslashes(Securite::bdd($_POST['modifsecteuractiviteen']));
    $boolmasquesecteurmasquesecteur = $manager->getSingle2("select masquesecteuractivite from secteuractivite where idsecteuractivite=? ", $idsecteuractivite);
    if ($boolmasquesecteurmasquesecteur == 1) {
        $boolmasquesecteur = 'TRUE';
    } else {
        $boolmasquesecteur = 'FALSE';
    }
    if ($lang == 'fr') {
        $secteuractivite = new Secteuractivite($idsecteuractivite, $modifsecteuractivite, $modifsecteuractiviteen, $boolmasquesecteur);
    } elseif ($lang == 'en') {
        $secteuractivite = new Secteuractivite($idsecteuractivite, $modifsecteuractiviteen, $modifsecteuractivite, $boolmasquesecteur);
    }
    $manager->updateSecteuractivite($secteuractivite, $idsecteuractivite);
    header('location:/'.REPERTOIRE.'/update_sector/' . $lang . '/TXT_MESSAGESEECTEURUPDATE');
    exit;
}
BD::deconnecter();
?>