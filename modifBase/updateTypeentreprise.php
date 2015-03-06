<?php

session_start();
include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);;
include_once '../class/Manager.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include '../decide-lang.php';
include_once '../class/Securite.php';
include_once '../outils/constantes.php';

if (empty($_POST['idtypeentrepriseactuel'])) {
    header('location:/'.REPERTOIRE.'/update_typeentErr1/' . $lang . '/TXT_MESSAGEERREURTYPEENTRESELECT');
    exit;
} else {
    $idtypeentreprise = $_POST['idtypeentrepriseactuel'];
}
if (empty($_POST['modiftypeentreprise'])) {
    header('location:/'.REPERTOIRE.'/update_typeentErr2/' . $lang . '/TXT_MESSAGEERREURTYPEENTREPRISENONSAISIE');
    exit;
}if (empty($_POST['modiftypeentrepriseen'])) {
    header('location:/'.REPERTOIRE.'/update_typeentErr2/' . $lang . '/TXT_MESSAGEERREURTYPEENTREPRISENONSAISIE');
    exit;
} else {
    $modiftypeentreprise = stripslashes(Securite::bdd($_POST['modiftypeentreprise']));
    $modiftypeentrepriseen = stripslashes(Securite::bdd($_POST['modiftypeentrepriseen']));

    $booltypeentreprisemasquesecteur = $manager->getSingle2("select masquetypeentreprise from typeentreprise where idtypeentreprise=? ", $idtypeentreprise);
    if ($booltypeentreprisemasquesecteur == 1) {
        $booltypeentreprise = 'TRUE';
    } else {
        $booltypeentreprise = 'FALSE';
    }
    if ($lang == 'fr') {
        $typeentreprise = new Typeentreprise($idtypeentreprise, $modiftypeentreprise, $booltypeentreprise, $modiftypeentrepriseen);
    } elseif ($lang == 'en') {
        $typeentreprise = new Typeentreprise($idtypeentreprise, $modiftypeentrepriseen, $booltypeentreprise, $modiftypeentreprise);
    }

    $manager->updateTypeentreprise($typeentreprise, $idtypeentreprise);
    header('location:/'.REPERTOIRE.'/update_typeent/' . $lang . '/TXT_MESSAGESERVEURTYPEENTREPRISEUPDATE');
    exit;
}



BD::deconnecter();
?>