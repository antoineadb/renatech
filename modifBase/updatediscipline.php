<?php

session_start();
include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);;
include '../decide-lang.php';
include_once '../class/Manager.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include_once '../class/Securite.php';

if (empty($_POST['iddisciplineactuel'])) {
    header('location:/'.REPERTOIRE.'/update_disciplineErr1/' . $lang . '/TXT_MESSAGEERREURDISCPLINESELECT');
    exit;
} else {
    $iddiscipline = $_POST['iddisciplineactuel'];
}

if (empty($_POST['modifdiscipline'])) {
    header('location:/'.REPERTOIRE.'/update_disciplineErr2/' . $lang . '/TXT_MESSAGEERREURDISCPLINENONSAISIE');
    exit;
}
if (empty($_POST['modifdisciplineen'])) {
    header('location:/'.REPERTOIRE.'/update_disciplineErr2/' . $lang . '/TXT_MESSAGEERREURDISCPLINENONSAISIE');
    exit;
} else {
    $modifdiscipline = stripslashes(Securite::bdd($_POST['modifdiscipline']));
    $modifdisciplineen = stripslashes(Securite::bdd($_POST['modifdisciplineen']));
    $boolmasquediscipline = $manager->getSingle2("select masquediscipline from disciplinescientifique where iddiscipline=? ", $iddiscipline);
    if ($boolmasquediscipline == 1) {
        $boolmasquediscipline = 'TRUE';
    } else {
        $boolmasquediscipline = 'FALSE';
    }
    if($lang=='fr'){
        $discipline = new Disciplinescientifique($iddiscipline, $modifdiscipline, $boolmasquediscipline, $modifdisciplineen);
    }elseif($lang=='en'){    
        $discipline = new Disciplinescientifique($iddiscipline, $modifdisciplineen, $boolmasquediscipline, $modifdiscipline);
    }
    $manager->updateDiscipline($discipline, $iddiscipline);
    header('location:/'.REPERTOIRE.'/update_discipline/' . $lang . '/TXT_MESSAGEDISCIPLINEUPDATE');
    exit;
}

BD::deconnecter();
?>