<?php


session_start();
include_once '../class/Manager.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include '../decide-lang.php';
include_once '../class/Securite.php';
include_once '../outils/constantes.php';
include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);
if (empty($_POST['iddisciplineactuel'])) {
    header('location:/' . REPERTOIRE . '/hide_disciplineErr1/' . $lang . '/TXT_MESSAGEERREURDISCIPLINESELECT');
    exit;
} else {
    $iddisciplineactuel = $_POST['iddisciplineactuel'];
}
if (empty($_POST['modifdiscipline'])) {
    header('location:/' . REPERTOIRE . '/hide_disciplineErr2/' . $lang . '/TXT_MESSAGEERREURDISCIPLINENONSAISIE');
    exit;
} else if (empty($_POST['modifdisciplineen'])) {
    header('location:/' . REPERTOIRE . '/hide_disciplineErr2/' . $lang . '/TXT_MESSAGEERREURDISCIPLINENONSAISIE');
    exit;
} else {
    $modifdiscipline = stripslashes(Securite::bdd($_POST['modifdiscipline']));
    $modifdisciplineen = stripslashes(Securite::bdd($_POST['modifdisciplineen']));

    if (isset($_POST['masquediscipline']) && $_POST['masquediscipline'] == TXT_MASQUER) {
        if ($lang == 'fr') {
            $discipline = new Disciplinescientifique($iddisciplineactuel, $modifdiscipline, TRUE, $modifdisciplineen);
        } elseif ($lang == 'en') {
            $discipline = new Disciplinescientifique($iddisciplineactuel, $modifdisciplineen, TRUE, $modifdiscipline);
        }        
        $manager->afficheHideDiscipline($discipline, $iddisciplineactuel);
        header('location:/' . REPERTOIRE . '/hide_discipline/' . $lang . '/TXT_MESSAGESERVEURDISCIPLINEMASQUER');
        exit;
    } elseif (isset($_POST['affichediscipline']) && $_POST['affichediscipline'] == TXT_REAFFICHER) {
        if ($lang == 'fr') {
            $discipline = new Disciplinescientifique($iddisciplineactuel, $modifdiscipline, FALSE, $modifdisciplineen);
        } elseif ($lang == 'en') {
            $discipline = new Disciplinescientifique($iddisciplineactuel, $modifdisciplineen, FALSE, $modifdiscipline);
        }
        $manager->afficheHideDiscipline($discipline, $iddisciplineactuel);
        header('location:/' . REPERTOIRE . '/show_discipline/' . $lang . '/TXT_MESSAGESERVEURDISCIPLINAFFICHE');
        exit;
    }
}
BD::deconnecter();