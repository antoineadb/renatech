<?php

session_start();
include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);;
include '../decide-lang.php';
include '../class/Manager.php';
include '../outils/constantes.php';
if (isset($_POST['page_precedente']) && $_POST['page_precedente'] == 'gestionlibelleHome.html') {
    $db = BD::connecter();
    $manager = new Manager($db);
    include_once '../class/Securite.php';

    $arraylibelle = array(
        array("libellefrancais" => $_POST['bienvenuefr'], "libelleanglais" => $_POST['bienvenueen'], "reflibelle" => 'TXT_BIENVENUE'),
        array("libellefrancais" => $_POST['acceuil1fr'], "libelleanglais" => $_POST['acceuil1en'], "reflibelle" => 'TXT_ACCUEIL1'),
        array("libellefrancais" => $_POST['acceuil2fr'], "libelleanglais" => $_POST['acceuil2en'], "reflibelle" => 'TXT_ACCUEIL2'),
        array("libellefrancais" => $_POST['acceuil3fr'], "libelleanglais" => $_POST['acceuil3en'], "reflibelle" => 'TXT_ACCUEIL3'),
        array("libellefrancais" => $_POST['aideCentralePfrValeur'], "libelleanglais" => $_POST['aideCentralePenValeur'], "reflibelle" => 'TXT_AIDECENTRALEPROXIMITE')
    );
        
    

    for ($i = 0; $i < count($arraylibelle); $i++) {
        $libellefrancais = Securite::bdd($arraylibelle[$i]['libellefrancais']);
        $libelleanglais = Securite::bdd($arraylibelle[$i]['libelleanglais']);
        $ancienlibellefr = $manager->getSingle2("select libellefrancais from libelleapplication where reflibelle=?", $arraylibelle[$i]['reflibelle']);
        $ancienlibelleen = $manager->getSingle2("select libelleanglais from libelleapplication where reflibelle=?", $arraylibelle[$i]['reflibelle']);
        if ($ancienlibellefr != $libellefrancais) {
            $libelleapplication = new Libelleapplication($libellefrancais, $libelleanglais, $arraylibelle[$i]['reflibelle']);
            $manager->updateLibelleApplication($libelleapplication, $arraylibelle[$i]['reflibelle']);
        }
        if ($ancienlibelleen != $libelleanglais) {
            $libelleapplication = new Libelleapplication($libellefrancais, $libelleanglais, $arraylibelle[$i]['reflibelle']);
            $manager->updateLibelleApplication($libelleapplication, $arraylibelle[$i]['reflibelle']);
        }
    }
    header('location: /' . REPERTOIRE . '/Manage_label1/' . $lang . '/msgupdateaccueil');
} else {
    header('location: /' . REPERTOIRE . '/Login_Error/' . $lang);
    exit();        
}
BD::deconnecter();