<?php

session_start();
include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);;
include '../decide-lang.php';
include '../class/Manager.php';
include '../outils/constantes.php';
if (isset($_POST['page_precedente']) && $_POST['page_precedente'] == 'gestionemailprojetphase1.html') {
    $db = BD::connecter();
    $manager = new Manager($db);
    include_once '../class/Securite.php';
    
    $arraylibellevalide = array(
        array("libellefrancais" => $_POST['emailprojetvalide0fr'], "libelleanglais" => $_POST['emailprojetvalide0en'], "reflibelle" => 'TXT_MRSMR6'),
        array("libellefrancais" => $_POST['emailprojetvalide1fr'], "libelleanglais" => $_POST['emailprojetvalide1en'], "reflibelle" => 'TXT_DEMANDEPROJETPHASE2'),
        array("libellefrancais" => $_POST['emailprojetvalide2fr'], "libelleanglais" => $_POST['emailprojetvalide2en'], "reflibelle" => 'TXT_BODYEMAILPROJET3'),
        array("libellefrancais" => $_POST['emailprojetvalide3fr'], "libelleanglais" => $_POST['emailprojetvalide3en'], "reflibelle" => 'TXT_SINCERESALUTATION'),
        array("libellefrancais" => $_POST['emailprojetvalide4fr'], "libelleanglais" => $_POST['emailprojetvalide4en'], "reflibelle" => 'TXT_RESEAURENATECH'),
        array("libellefrancais" => $_POST['emailprojetvalide5fr'], "libelleanglais" => $_POST['emailprojetvalide5en'], "reflibelle" => 'TXT_DONOTREPLY')
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
    
    
    
    

    $arraylibelleacc = array(
        array("libellefrancais" => $_POST['emailprojetacc0fr'], "libelleanglais" => $_POST['emailprojetacc0en'], "reflibelle" => 'TXT_MRSMR'),
        array("libellefrancais" => $_POST['emailprojetacc1fr'], "libelleanglais" => $_POST['emailprojetacc1en'], "reflibelle" => 'TXT_BODYEMAILPHASE200'),
        array("libellefrancais" => $_POST['emailprojetacc2fr'], "libelleanglais" => $_POST['emailprojetacc2en'], "reflibelle" => 'TXT_BODYEMAILPHASE221'),
        array("libellefrancais" => $_POST['emailprojetacc3fr'], "libelleanglais" => $_POST['emailprojetacc3en'], "reflibelle" => 'TXT_BODYEMAILPHASE220'),
        array("libellefrancais" => $_POST['emailprojetacc4fr'], "libelleanglais" => $_POST['emailprojetacc4en'], "reflibelle" => 'TXT_BODYEMAILPHASE230'),
        array("libellefrancais" => $_POST['emailprojetacc5fr'], "libelleanglais" => $_POST['emailprojetacc5en'], "reflibelle" => 'TXT_RAPPELINSERTLOGO0'),
        array("libellefrancais" => $_POST['emailprojetacc6fr'], "libelleanglais" => $_POST['emailprojetacc6en'], "reflibelle" => 'TXT_SINCERESALUTATION'),
        array("libellefrancais" => $_POST['emailprojetacc7fr'], "libelleanglais" => $_POST['emailprojetacc7en'], "reflibelle" => 'TXT_RESEAURENATECH')
    );

    for ($i = 0; $i < count($arraylibelleacc); $i++) {
        $libellefrancais = Securite::bdd($arraylibelleacc[$i]['libellefrancais']);
        $libelleanglais = Securite::bdd($arraylibelleacc[$i]['libelleanglais']);
        $ancienlibellefr = $manager->getSingle2("select libellefrancais from libelleapplication where reflibelle=?", $arraylibelleacc[$i]['reflibelle']);
        $ancienlibelleen = $manager->getSingle2("select libelleanglais from libelleapplication where reflibelle=?", $arraylibelleacc[$i]['reflibelle']);

        if ($ancienlibellefr != $libellefrancais) {
            $libelleapplication = new Libelleapplication($libellefrancais, $libelleanglais, $arraylibelleacc[$i]['reflibelle']);
            $manager->updateLibelleApplication($libelleapplication, $arraylibelleacc[$i]['reflibelle']);
        }        
        if ($ancienlibelleen != $libelleanglais) {
            $libelleapplication = new Libelleapplication($libellefrancais, $libelleanglais, $arraylibelleacc[$i]['reflibelle']);
            $manager->updateLibelleApplication($libelleapplication, $arraylibelleacc[$i]['reflibelle']);
        }
    }
    $arraylibelleTrs = array(
        array("libellefrancais" => $_POST['emailprojettrs0fr'], "libelleanglais" => $_POST['emailprojettrs0en'], "reflibelle" => 'TXT_MRSMR7'),
        array("libellefrancais" => $_POST['emailprojettrs1fr'], "libelleanglais" => $_POST['emailprojettrs1en'], "reflibelle" => 'TXT_BODYEMAILTRSFPHASE20'),
        array("libellefrancais" => $_POST['emailprojettrs2fr'], "libelleanglais" => $_POST['emailprojettrs2en'], "reflibelle" => 'TXT_BODYEMAILPHASE21'),
        array("libellefrancais" => $_POST['emailprojettrs3fr'], "libelleanglais" => $_POST['emailprojettrs3en'], "reflibelle" => 'TXT_FINSUJETMAILRESPONSABLE'),
        array("libellefrancais" => $_POST['emailprojettrs4fr'], "libelleanglais" => $_POST['emailprojettrs4en'], "reflibelle" => 'TXT_BODYEMAILTRSFPHASE22'),
        array("libellefrancais" => $_POST['emailprojettrs5fr'], "libelleanglais" => $_POST['emailprojettrs5en'], "reflibelle" => 'TXT_RAPPELINSERTLOGO1'),
        array("libellefrancais" => $_POST['emailprojettrs6fr'], "libelleanglais" => $_POST['emailprojettrs6en'], "reflibelle" => 'TXT_SINCERESALUTATION'),
        array("libellefrancais" => $_POST['emailprojettrs7fr'], "libelleanglais" => $_POST['emailprojettrs7en'], "reflibelle" => 'TXT_RESEAURENATECH'),
        array("libellefrancais" => $_POST['emailprojettrs8fr'], "libelleanglais" => $_POST['emailprojettrs8en'], "reflibelle" => 'TXT_EMAILADDRESSCENTRAL')
    );
    for ($i = 0; $i < count($arraylibelleTrs); $i++) {
        $libellefrancais = Securite::bdd($arraylibelleTrs[$i]['libellefrancais']);
        $libelleanglais = Securite::bdd($arraylibelleTrs[$i]['libelleanglais']);
        $ancienlibellefr = $manager->getSingle2("select libellefrancais from libelleapplication where reflibelle=?", $arraylibelleTrs[$i]['reflibelle']);
        $ancienlibelleen = $manager->getSingle2("select libelleanglais from libelleapplication where reflibelle=?", $arraylibelleTrs[$i]['reflibelle']);
        if ($ancienlibellefr != $libellefrancais) {
            $libelleapplication = new Libelleapplication($libellefrancais, $libelleanglais, $arraylibelleTrs[$i]['reflibelle']);
            $manager->updateLibelleApplication($libelleapplication, $arraylibelleTrs[$i]['reflibelle']);
        }
        if ($ancienlibelleen != $libelleanglais) {
            $libelleapplication = new Libelleapplication($libellefrancais, $libelleanglais, $arraylibelleTrs[$i]['reflibelle']);
            $manager->updateLibelleApplication($libelleapplication, $arraylibelleTrs[$i]['reflibelle']);
        }
    }

    $arraylibellerefuse = array(
        array("libellefrancais" => $_POST['emailprojetref0fr'], "libelleanglais" => $_POST['emailprojetref0en'], "reflibelle" => 'TXT_MRSMR8'),
        array("libellefrancais" => $_POST['emailprojetref1fr'], "libelleanglais" => $_POST['emailprojetref1en'], "reflibelle" => 'TXT_BODYEMAILREFUSEPHASE20'),
        array("libellefrancais" => $_POST['emailprojetref2fr'], "libelleanglais" => $_POST['emailprojetref2en'], "reflibelle" => 'TXT_BODYEMAILREFUSEPHASE21'),
        array("libellefrancais" => $_POST['emailprojetref3fr'], "libelleanglais" => $_POST['emailprojetref3en'], "reflibelle" => 'TXT_SINCERESALUTATION'),
        array("libellefrancais" => $_POST['emailprojetref4fr'], "libelleanglais" => $_POST['emailprojetref4en'], "reflibelle" => 'TXT_RESPONSABLEBODYTEMAIL10'),
        array("libellefrancais" => $_POST['emailprojetref5fr'], "libelleanglais" => $_POST['emailprojetref5en'], "reflibelle" => 'TXT_RESEAURENATECH')
    );
    for ($i = 0; $i < count($arraylibellerefuse); $i++) {
        $libellefrancais = Securite::bdd($arraylibellerefuse[$i]['libellefrancais']);
        $libelleanglais = Securite::bdd($arraylibellerefuse[$i]['libelleanglais']);
        $ancienlibellefr = $manager->getSingle2("select libellefrancais from libelleapplication where reflibelle=?", $arraylibellerefuse[$i]['reflibelle']);
        $ancienlibelleen = $manager->getSingle2("select libelleanglais from libelleapplication where reflibelle=?", $arraylibellerefuse[$i]['reflibelle']);
        if ($ancienlibellefr != $libellefrancais) {
            $libelleapplication = new Libelleapplication($libellefrancais, $libelleanglais, $arraylibellerefuse[$i]['reflibelle']);
            $manager->updateLibelleApplication($libelleapplication, $arraylibellerefuse[$i]['reflibelle']);
        }
        if ($ancienlibelleen != $libelleanglais) {
            $libelleapplication = new Libelleapplication($libellefrancais, $libelleanglais, $arraylibellerefuse[$i]['reflibelle']);
            $manager->updateLibelleApplication($libelleapplication, $arraylibellerefuse[$i]['reflibelle']);
        }
    }
   
    $arraylibelleattente = array(
        array("libellefrancais" => $_POST['emailprojetattente0fr'], "libelleanglais" => $_POST['emailprojetattente0en'], "reflibelle" => 'TXT_MRSMR9'),
        array("libellefrancais" => $_POST['emailprojetattente1fr'], "libelleanglais" => $_POST['emailprojetattente1en'], "reflibelle" => 'TXT_BODYEMAILENATTENTEPHASE01'),
        array("libellefrancais" => $_POST['emailprojetattente2fr'], "libelleanglais" => $_POST['emailprojetattente2en'], "reflibelle" => 'TXT_BODYEMAILENATTENTEPHASE02'),
        array("libellefrancais" => $_POST['emailprojetattente3fr'], "libelleanglais" => $_POST['emailprojetattente3en'], "reflibelle" => 'TXT_RESEAURENATECH'),
        array("libellefrancais" => $_POST['emailprojetattente4fr'], "libelleanglais" => $_POST['emailprojetattente4en'], "reflibelle" => 'TXT_SINCERESALUTATION'),
        array("libellefrancais" => $_POST['emailprojetattente5fr'], "libelleanglais" => $_POST['emailprojetattente5en'], "reflibelle" => 'TXT_DONOTREPLY')
    );

    for ($i = 0; $i < count($arraylibelleattente); $i++) {
        $libellefrancais = Securite::bdd($arraylibelleattente[$i]['libellefrancais']);
        $libelleanglais = Securite::bdd($arraylibelleattente[$i]['libelleanglais']);
        $ancienlibellefr = $manager->getSingle2("select libellefrancais from libelleapplication where reflibelle=?", $arraylibelleattente[$i]['reflibelle']);
        $ancienlibelleen = $manager->getSingle2("select libelleanglais from libelleapplication where reflibelle=?", $arraylibelleattente[$i]['reflibelle']);
        if ($ancienlibellefr != $libellefrancais) {
            $libelleapplication = new Libelleapplication($libellefrancais, $libelleanglais, $arraylibelleattente[$i]['reflibelle']);
            $manager->updateLibelleApplication($libelleapplication, $arraylibelleattente[$i]['reflibelle']);
        }
        if ($ancienlibelleen != $libelleanglais) {
            $libelleapplication = new Libelleapplication($libellefrancais, $libelleanglais, $arraylibelleattente[$i]['reflibelle']);
            $manager->updateLibelleApplication($libelleapplication, $arraylibelleattente[$i]['reflibelle']);
        }
    }

    

    header('location: /' . REPERTOIRE . '/Manage_label5/' . $lang . '/msgupdateemailprojetphase1');
} else {
    header('location: /' . REPERTOIRE . '/Login_Error/' . $lang);
    exit();
}
BD::deconnecter();
