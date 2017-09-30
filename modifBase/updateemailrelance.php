<?php

session_start();
include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);;
include '../decide-lang.php';
include '../class/Manager.php';
include '../outils/constantes.php';
if (isset($_POST['page_precedente']) && $_POST['page_precedente'] == 'gestionemailrelance.html') {
    $db = BD::connecter();
    $manager = new Manager($db);
    include_once '../class/Securite.php';
    
    $arraylibellevalide = array(
        array("libellefrancais" => $_POST['emailrelancevalide1fr'], "libelleanglais" => $_POST['emailrelancevalide1en'], "reflibelle" => 'TXT_RELANCE1'),
        array("libellefrancais" => $_POST['emailrelancevalide2fr'], "libelleanglais" => $_POST['emailrelancevalide2en'], "reflibelle" => 'TXT_RELANCE2'),
        array("libellefrancais" => $_POST['emailrelancevalide3fr'], "libelleanglais" => $_POST['emailrelancevalide3en'], "reflibelle" => 'TXT_RELANCE3'),
        array("libellefrancais" => $_POST['emailrelancevalide4fr'], "libelleanglais" => $_POST['emailrelancevalide4en'], "reflibelle" => 'TXT_RELANCE4'),
        array("libellefrancais" => $_POST['emailrelancevalide5fr'], "libelleanglais" => $_POST['emailrelancevalide5en'], "reflibelle" => 'TXT_RELANCE5'),
        array("libellefrancais" => $_POST['emailrelancevalide6fr'], "libelleanglais" => $_POST['emailrelancevalide6en'], "reflibelle" => 'TXT_RELANCE6'),
        array("libellefrancais" => $_POST['emailrelancevalide7fr'], "libelleanglais" => $_POST['emailrelancevalide7en'], "reflibelle" => 'TXT_RELANCE7'),
        array("libellefrancais" => $_POST['emailrelancevalide8fr'], "libelleanglais" => $_POST['emailrelancevalide8en'], "reflibelle" => 'TXT_RELANCE8'),
        array("libellefrancais" => $_POST['emailrelancevalide9fr'], "libelleanglais" => $_POST['emailrelancevalide9en'], "reflibelle" => 'TXT_RELANCE9'),
    );

    for ($i = 0; $i < count($arraylibellevalide); $i++) {
        $libellefrancais = Securite::bdd($arraylibellevalide[$i]['libellefrancais']);
        $libelleanglais = Securite::bdd($arraylibellevalide[$i]['libelleanglais']);
        $ancienlibellefr = $manager->getSingle2("select libellefrancais from libelleapplication where reflibelle=?", $arraylibellevalide[$i]['reflibelle']);
        $ancienlibelleen = $manager->getSingle2("select libelleanglais from libelleapplication where reflibelle=?", $arraylibellevalide[$i]['reflibelle']);

        if ($ancienlibellefr != $libellefrancais) {
            $libelleapplication = new Libelleapplication($libellefrancais, $libelleanglais, $arraylibellevalide[$i]['reflibelle']);
            $manager->updateLibelleApplication($libelleapplication, $arraylibellevalide[$i]['reflibelle']);
        }        
        if ($ancienlibelleen != $libelleanglais) {
            $libelleapplication = new Libelleapplication($libellefrancais, $libelleanglais, $arraylibellevalide[$i]['reflibelle']);
            $manager->updateLibelleApplication($libelleapplication, $arraylibellevalide[$i]['reflibelle']);
        }
    }

    header('location: /' . REPERTOIRE . '/Manage_label7/' . $lang . '/msgupdateemailrelance');
} else {
    header('location: /' . REPERTOIRE . '/Login_Error/' . $lang);
    exit();
}
BD::deconnecter();
