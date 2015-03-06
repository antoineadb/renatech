<?php
include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include '../decide-lang.php';
include '../class/Securite.php';

if (empty($_POST['modifressource'])) {
    header('location:/' . REPERTOIRE . '/insert_ressourceErr1/' . $lang . '/TXT_MESSAGEERREURRESSOURCENONSAISIE');
    exit;
}
if (empty($_POST['modifressourceen'])) {
    header('location:/' . REPERTOIRE . '/insert_ressourceErr1/' . $lang . '/TXT_MESSAGEERREURRESSOURCENONSAISIE');
    exit;
} else {
    $modifRessource = stripslashes(Securite::bdd($_POST['modifressource']));
    $modifRessourceen = stripslashes(Securite::bdd($_POST['modifressourceen']));
    $idressource = $manager->getSingle2("SELECT libelleressource FROM ressource Where libelleressource =?", $modifRessource);
    if (!empty($idressource)) {
        header('location:/' . REPERTOIRE . '/insert_ressourceErr2/' . $lang . '/TXT_MESSAGESERVEURRESSOURCEEXISTE');
        exit;
    } else {
        $idnewressource = $manager->getSingle("SELECT max(idressource) FROM ressource") + 1;
        if ($lang == 'fr') {
            $ressource = new Ressource($idnewressource, $modifRessource, FALSE, $modifRessourceen);
        } elseif ($lang == 'en') {
            $ressource = new Ressource($idnewressource, $modifRessourceen, FALSE, $modifRessource);
        }
        $manager->addressource($ressource);
        header('location:/' . REPERTOIRE . '/insert_ressource/' . $lang . '/TXT_MESSAGESERVEURRESSOURCE');
        exit;
    }
}
BD::deconnecter();
?>