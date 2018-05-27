<?php

session_start();
include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);;
include '../decide-lang.php';
include '../class/Manager.php';
include '../outils/constantes.php';

if (isset($_POST['page_precedente']) && $_POST['page_precedente'] == 'gestionemailprojetphase2.html') {
    $db = BD::connecter();
    $manager = new Manager($db);
    include_once '../class/Securite.php';

    $arraylibelle = array(
        array("libellefrancais" => $_POST['emailprojetencours0fr'], "libelleanglais" => $_POST['emailprojetencours0en'], "reflibelle" => 'TXT_MRSMR'),
        array("libellefrancais" => $_POST['emailprojetencours1fr'], "libelleanglais" => $_POST['emailprojetencours1en'], "reflibelle" => 'TXT_BODYEMAILREALISATION4'),
        array("libellefrancais" => $_POST['emailprojetencours2fr'], "libelleanglais" => $_POST['emailprojetencours2en'], "reflibelle" => 'TXT_BODYEMAILREALISATION1'),
        array("libellefrancais" => $_POST['emailprojetencours3fr'], "libelleanglais" => $_POST['emailprojetencours3en'], "reflibelle" => 'TXT_BODYEMAILREALISATION2'),
        array("libellefrancais" => $_POST['emailprojetencours4fr'], "libelleanglais" => $_POST['emailprojetencours4en'], "reflibelle" => 'TXT_RAPPEL'),
        array("libellefrancais" => $_POST['emailprojetencours5fr'], "libelleanglais" => $_POST['emailprojetencours5en'], "reflibelle" => 'TXT_SINCERESALUTATION'),
        array("libellefrancais" => $_POST['emailprojetencours6fr'], "libelleanglais" => $_POST['emailprojetencours6en'], "reflibelle" => 'TXT_RESEAURENATECH')
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
    $arraylibelleFini = array(
        array("libellefrancais" => $_POST['emailprojetfini0fr'], "libelleanglais" => $_POST['emailprojetfini0en'], "reflibelle" => 'TXT_MRSMR'),
        array("libellefrancais" => $_POST['emailprojetfini1fr'], "libelleanglais" => $_POST['emailprojetfini1en'], "reflibelle" => 'TXT_BODYEMAILFINI'),
        array("libellefrancais" => $_POST['emailprojetfini2fr'], "libelleanglais" => $_POST['emailprojetfini2en'], "reflibelle" => 'TXT_RAPPEL'),
        array("libellefrancais" => $_POST['emailprojetfini3fr'], "libelleanglais" => $_POST['emailprojetfini3en'], "reflibelle" => 'TXT_SINCERESALUTATION'),
        array("libellefrancais" => $_POST['emailprojetfini4fr'], "libelleanglais" => $_POST['emailprojetfini4en'], "reflibelle" => 'TXT_RESEAURENATECH'),
        array("libellefrancais" => $_POST['emailprojetfini5fr'], "libelleanglais" => $_POST['emailprojetfini5en'], "reflibelle" => 'TXT_EMAILADDRESSCENTRAL')
    );
    
    for ($i = 0; $i < count($arraylibelleFini); $i++) {
        $libellefrancais = Securite::bdd($arraylibelleFini[$i]['libellefrancais']);
        $libelleanglais = Securite::bdd($arraylibelleFini[$i]['libelleanglais']);
        $ancienlibellefr = $manager->getSingle2("select libellefrancais from libelleapplication where reflibelle=?", $arraylibelleFini[$i]['reflibelle']);
        $ancienlibelleen = $manager->getSingle2("select libelleanglais from libelleapplication where reflibelle=?", $arraylibelleFini[$i]['reflibelle']);
        if ($ancienlibellefr != $libellefrancais) {
            $libelleapplication = new Libelleapplication($libellefrancais, $libelleanglais, $arraylibelleFini[$i]['reflibelle']);
            $manager->updateLibelleApplication($libelleapplication, $arraylibelleFini[$i]['reflibelle']);
        }
        if ($ancienlibelleen != $libelleanglais) {
            $libelleapplication = new Libelleapplication($libellefrancais, $libelleanglais, $arraylibelleFini[$i]['reflibelle']);
            $manager->updateLibelleApplication($libelleapplication, $arraylibelleFini[$i]['reflibelle']);
        }
    }
    
    $arraylibelleCloture = array(
        array("libellefrancais" => $_POST['emailprojetcloture0fr'], "libelleanglais" => $_POST['emailprojetcloture0en'], "reflibelle" => 'TXT_MRSMR8'),
        array("libellefrancais" => $_POST['emailprojetcloture1fr'], "libelleanglais" => $_POST['emailprojetcloture1en'], "reflibelle" => 'TXT_BODYEMAILCLOTURE'),
        array("libellefrancais" => $_POST['emailprojetcloture2fr'], "libelleanglais" => $_POST['emailprojetcloture2en'], "reflibelle" => 'TXT_RAPPEL'),
        array("libellefrancais" => $_POST['emailprojetcloture3fr'], "libelleanglais" => $_POST['emailprojetcloture3en'], "reflibelle" => 'TXT_SINCERESALUTATION'),
        array("libellefrancais" => $_POST['emailprojetcloture4fr'], "libelleanglais" => $_POST['emailprojetcloture4en'], "reflibelle" => 'TXT_RESEAURENATECH'),
        array("libellefrancais" => $_POST['emailprojetcloture5fr'], "libelleanglais" => $_POST['emailprojetcloture5en'], "reflibelle" => 'TXT_EMAILADDRESSCENTRAL')
    );
    for ($i = 0; $i < count($arraylibelleCloture); $i++) {
        $libellefrancais = Securite::bdd($arraylibelleCloture[$i]['libellefrancais']);
        $libelleanglais = Securite::bdd($arraylibelleCloture[$i]['libelleanglais']);
        $ancienlibellefr = $manager->getSingle2("select libellefrancais from libelleapplication where reflibelle=?", $arraylibelleCloture[$i]['reflibelle']);
        $ancienlibelleen = $manager->getSingle2("select libelleanglais from libelleapplication where reflibelle=?", $arraylibelleCloture[$i]['reflibelle']);
        if ($ancienlibellefr != $libellefrancais) {
            $libelleapplication = new Libelleapplication($libellefrancais, $libelleanglais, $arraylibelleCloture[$i]['reflibelle']);
            $manager->updateLibelleApplication($libelleapplication, $arraylibelleCloture[$i]['reflibelle']);
        }
        if ($ancienlibelleen != $libelleanglais) {
            $libelleapplication = new Libelleapplication($libellefrancais, $libelleanglais, $arraylibelleCloture[$i]['reflibelle']);
            $manager->updateLibelleApplication($libelleapplication, $arraylibelleCloture[$i]['reflibelle']);
        }
    }
    
     $arraylibelleaffecte = array(
        array("libellefrancais" => $_POST['emailprojetaffecte0fr'], "libelleanglais" => $_POST['emailprojetaffecte0en'], "reflibelle" => 'TXT_PROJETNUM'),
        array("libellefrancais" => $_POST['emailprojetaffecte1fr'], "libelleanglais" => $_POST['emailprojetaffecte1en'], "reflibelle" => 'TXT_AFFECTPROJET1'),
        array("libellefrancais" => $_POST['emailprojetaffecte2fr'], "libelleanglais" => $_POST['emailprojetaffecte2en'], "reflibelle" => 'TXT_BODYEMAILPHASE231'),
        array("libellefrancais" => $_POST['emailprojetaffecte3fr'], "libelleanglais" => $_POST['emailprojetaffecte3en'], "reflibelle" => 'TXT_SINCERESALUTATION'),
        array("libellefrancais" => $_POST['emailprojetaffecte4fr'], "libelleanglais" => $_POST['emailprojetaffecte4en'], "reflibelle" => 'TXT_RESEAURENATECH'),
        array("libellefrancais" => $_POST['emailprojetaffecte5fr'], "libelleanglais" => $_POST['emailprojetaffecte5en'], "reflibelle" => 'TXT_DONOTREPLY')
    );
    for ($i = 0; $i < count($arraylibelleaffecte); $i++) {
        $libellefrancais = Securite::bdd($arraylibelleaffecte[$i]['libellefrancais']);
        $libelleanglais = Securite::bdd($arraylibelleaffecte[$i]['libelleanglais']);
        $ancienlibellefr = $manager->getSingle2("select libellefrancais from libelleapplication where reflibelle=?", $arraylibelleaffecte[$i]['reflibelle']);
        $ancienlibelleen = $manager->getSingle2("select libelleanglais from libelleapplication where reflibelle=?", $arraylibelleaffecte[$i]['reflibelle']);
        if ($ancienlibellefr != $libellefrancais) {
            $libelleapplication = new Libelleapplication($libellefrancais, $libelleanglais, $arraylibelleaffecte[$i]['reflibelle']);
            $manager->updateLibelleApplication($libelleapplication, $arraylibelleaffecte[$i]['reflibelle']);
        }
        if ($ancienlibelleen != $libelleanglais) {
            $libelleapplication = new Libelleapplication($libellefrancais, $libelleanglais, $arraylibelleaffecte[$i]['reflibelle']);
            $manager->updateLibelleApplication($libelleapplication, $arraylibelleaffecte[$i]['reflibelle']);
        }
    }
      $arraylibellemaj = array(
        array("libellefrancais" => $_POST['emailprojetmaj0fr'], "libelleanglais" => $_POST['emailprojetmaj0en'], "reflibelle" => 'TXT_MRSMR0'),
        array("libellefrancais" => $_POST['emailprojetmaj1fr'], "libelleanglais" => $_POST['emailprojetmaj1en'], "reflibelle" => 'TXT_BODYEMAILMODIF0'),
        array("libellefrancais" => $_POST['emailprojetmaj2fr'], "libelleanglais" => $_POST['emailprojetmaj2en'], "reflibelle" => 'TXT_BODYEMAILMODIF1'),
        array("libellefrancais" => $_POST['emailprojetmaj3fr'], "libelleanglais" => $_POST['emailprojetmaj3en'], "reflibelle" => 'TXT_TITREPROJETEMAIL'),
        array("libellefrancais" => $_POST['emailprojetmaj4fr'], "libelleanglais" => $_POST['emailprojetmaj4en'], "reflibelle" => 'TXT_SINCERESALUTATION'),
        array("libellefrancais" => $_POST['emailprojetmaj5fr'], "libelleanglais" => $_POST['emailprojetmaj5en'], "reflibelle" => 'TXT_RESEAURENATECH'),
        array("libellefrancais" => $_POST['emailprojetmaj6fr'], "libelleanglais" => $_POST['emailprojetmaj6en'], "reflibelle" => 'TXT_DONOTREPLY')          
    );
     for ($i = 0; $i < count($arraylibellemaj); $i++) {
        $libellefrancais = Securite::bdd($arraylibellemaj[$i]['libellefrancais']);
        $libelleanglais = Securite::bdd($arraylibellemaj[$i]['libelleanglais']);
        $ancienlibellefr = $manager->getSingle2("select libellefrancais from libelleapplication where reflibelle=?", $arraylibellemaj[$i]['reflibelle']);
        $ancienlibelleen = $manager->getSingle2("select libelleanglais from libelleapplication where reflibelle=?", $arraylibellemaj[$i]['reflibelle']);
        if ($ancienlibellefr != $libellefrancais) {
            $libelleapplication = new Libelleapplication($libellefrancais, $libelleanglais, $arraylibellemaj[$i]['reflibelle']);
            $manager->updateLibelleApplication($libelleapplication, $arraylibellemaj[$i]['reflibelle']);
        }
        if ($ancienlibelleen != $libelleanglais) {
            $libelleapplication = new Libelleapplication($libellefrancais, $libelleanglais, $arraylibellemaj[$i]['reflibelle']);
            $manager->updateLibelleApplication($libelleapplication, $arraylibellemaj[$i]['reflibelle']);
        }
    }  
      
    header('location: /' . REPERTOIRE . '/Manage_label6/' . $lang . '/msgupdateemailprojetphase2');
} else {
    header('location: /' . REPERTOIRE . '/Login_Error/' . $lang);
    exit();
}
BD::deconnecter();