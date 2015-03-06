<?php

include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);
include_once '../class/Manager.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include '../decide-lang.php';
include_once '../class/Securite.php';

if (empty($_POST['modiftypeentreprise'])) {
    header('location:/'.REPERTOIRE.'/insert_typeentErr1/' . $lang . '/TXT_MESSAGEERREURTYPEENTREPRISENONSAISIE');
    exit;
}
if (empty($_POST['modiftypeentrepriseen'])) {
    header('location:/'.REPERTOIRE.'/insert_typeentErr2/' . $lang . '/TXT_MESSAGEERREURTYPEENTREPRISENONSAISIE');
    exit;
} else {
    $modiftypeentreprise = stripslashes(Securite::bdd($_POST['modiftypeentreprise']));
    $modiftypeentrepriseen = stripslashes(Securite::bdd($_POST['modiftypeentrepriseen']));
    $idtypeentreprise = $manager->getSingle2("SELECT libelletypeentreprise FROM typeentreprise Where libelletypeentreprise =?", $modiftypeentreprise);
    if (!empty($idtypeentreprise)) {
        header('location:/'.REPERTOIRE.'/insert_typeentErr3/' . $lang . '/TXT_MESSAGESERVEURTYPEENTREPRISEEXISTE');
        exit;
    } else {
        $idnewtypeentreprise = $manager->getSingle("SELECT max(idtypeentreprise) FROM typeentreprise;") + 1;
        if ($lang == 'fr') {
            $typeentreprise = new Typeentreprise($idnewtypeentreprise, $modiftypeentreprise, FALSE, $modiftypeentrepriseen);
        } elseif ($lang == 'en') {
            $typeentreprise = new Typeentreprise($idnewtypeentreprise, $modiftypeentrepriseen, FALSE, $modiftypeentreprise);
        }
        $manager->addTypeentreprise($typeentreprise);
        header('location:/'.REPERTOIRE.'/insert_typeent/' . $lang . '/TXT_MESSAGESERVEURTYPEENTREPRISE');
        exit;
    }
}
BD::deconnecter();
?>