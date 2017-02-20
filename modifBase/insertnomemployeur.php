<?php

include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);
include_once '../class/Manager.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include '../decide-lang.php';
include_once '../class/Securite.php';

if (empty($_POST['modifnomemployeur'])) {
    header('location:/'.REPERTOIRE.'/insert_employeurErr1/' . $lang . '/TXT_MESSAGEERREURNOMEMPOYEURNONSAISIE');
    exit;
}
if (empty($_POST['modifnomemployeuren'])) {
    header('location:/'.REPERTOIRE.'/insert_employeurErr1/' . $lang . '/TXT_MESSAGEERREURNOMEMPOYEURNONSAISIE');
    exit;
} else {
    $modifnomemployeur = stripslashes(Securite::bdd($_POST['modifnomemployeur']));
    $modifnomemployeuren = stripslashes(Securite::bdd($_POST['modifnomemployeuren']));
    $idnomemployeur = $manager->getSingle2("SELECT libelleemployeur FROM nomemployeur Where libelleemployeur =?", $modifnomemployeur);
    if (!empty($idnomemployeur)) {
        header('location:/'.REPERTOIRE.'/insert_employeurErr2/' . $lang . '/TXT_MESSAGESERVEUREMPLOYEUREXISTE');
        exit;
    } else {
        $idnewnomemployeur = $manager->getSingle("SELECT max(idemployeur) FROM nomemployeur;") + 1;
        if ($lang == 'fr') {
            $nomemployeur = new Nomemployeur($idnewnomemployeur, $modifnomemployeur, FALSE, $modifnomemployeuren);
        } elseif ($lang == 'en') {
            $nomemployeur = new Nomemployeur($idnewnomemployeur, $modifnomemployeuren, FALSE, $modifnomemployeur);
        }
        $manager->addNomemployeur($nomemployeur);
        header('location:/'.REPERTOIRE.'/insert_employeur/' . $lang . '/TXT_MESSAGESERVEUREMPLOYEUR');

        exit;
    }
}
BD::deconnecter();
?>