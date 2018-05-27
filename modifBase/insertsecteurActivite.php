<?php
include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);
include_once '../class/Manager.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include_once '../decide-lang.php';
include_once '../class/Securite.php';
include_once '../outils/constantes.php';

if (empty($_POST['modifsecteuractivite'])) {
    header('location:/'.REPERTOIRE.'/insert_sectorErr1/' . $lang . '/TXT_MESSAGEERREURSECTEURNONSAISIE');
    exit;
} elseif (empty($_POST['modifsecteuractiviteen'])) {
    header('location:/'.REPERTOIRE.'/insert_sectorErr2/' . $lang . '/TXT_MESSAGEERREURSECTEURNONSAISIE');
    exit;
} else {
    $modifsecteuractivite = stripslashes(Securite::bdd($_POST['modifsecteuractivite']));
    $modifsecteuractiviteen = stripslashes(Securite::bdd($_POST['modifsecteuractiviteen']));
    $idsecteur = $manager->getSingle2("SELECT libellesecteuractivite FROM secteuractivite Where libellesecteuractivite like ?", trim($modifsecteuractivite));
    if (!empty($idsecteur)) {
        header('location:/'.REPERTOIRE.'/insert_sectorErr3/' . $lang . '/TXT_MESSAGESERVEURSECTEUREXISTE');
        exit;
    } else {
        $idnewsecteur = $manager->getSingle("SELECT max(idsecteuractivite) FROM secteuractivite;") + 1;
        if ($lang == 'fr') {
            $secteur = new Secteuractivite($idnewsecteur, $modifsecteuractivite, $modifsecteuractiviteen, FALSE);
        } elseif ($lang == 'en') {
            $secteur = new Secteuractivite($idnewsecteur, $modifsecteuractiviteen, $modifsecteuractivite, FALSE);
        }
        $manager->addSecteuractivite($secteur);
        header('location:/'.REPERTOIRE.'/insert_sector/' . $lang . '/TXT_MESSAGESERVEURSETCEURACTIVITE');
        exit;
    }
}
BD::deconnecter();