<?php

include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);
include_once '../class/Manager.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include '../decide-lang.php';
include_once '../class/Securite.php';
include_once '../outils/constantes.php';

if (empty($_POST['modifdiscipline'])) {
    header('location:/'.REPERTOIRE.'/insert_disciplineErr1/' . $lang . '/TXT_MESSAGEERREURDISCIPLINENONSAISIE');
    exit;
}
if (empty($_POST['modifdisciplineen'])) {
    header('location:/'.REPERTOIRE.'/insert_disciplineErr1/' . $lang . '/TXT_MESSAGEERREURDISCIPLINENONSAISIE');
    exit;
} else {
    $modifdiscipline = stripslashes(Securite::bdd($_POST['modifdiscipline']));
    $modifdisciplineen = stripslashes(Securite::bdd($_POST['modifdisciplineen']));
    $iddiscipline = $manager->getSingle2("SELECT libellediscipline FROM disciplinescientifique Where libellediscipline =?", $modifdiscipline);
    if (!empty($iddiscipline)) {
        header('location:/'.REPERTOIRE.'/insert_disciplineErr2/' . $lang . '/TXT_MESSAGESERVEURDISCIPLINEEXISTE');
        exit;
    } else {
        $idnewdiscipline = $manager->getSingle("SELECT max(iddiscipline) FROM disciplinescientifique;") + 1;
        if ($lang == 'fr') {
            $discipline = new Disciplinescientifique($idnewdiscipline, $modifdiscipline, FALSE, $modifdisciplineen);
        } elseif ($lang == 'en') {
            $discipline = new Disciplinescientifique($idnewdiscipline, $modifdisciplineen, FALSE, $modifdiscipline);
        }
        $manager->addDiscipline($discipline);
        header('location:/'.REPERTOIRE.'/insert_discipline/' . $lang . '/TXT_MESSAGESERVEURDISCIPLINE');
        exit;
    }
}
BD::deconnecter();