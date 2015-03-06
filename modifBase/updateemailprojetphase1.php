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

    $arraylibelle = array(
        array("libellefrancais" => $_POST['emailprojetphase10fr'], "libelleanglais" => $_POST['emailprojetphase10en'], "reflibelle" => 'TXT_BODYEMAILPROJET0'),
        array("libellefrancais" => $_POST['emailprojetphase11fr'], "libelleanglais" => $_POST['emailprojetphase11en'], "reflibelle" => 'TXT_BODYEMAILPROJET1'),
        array("libellefrancais" => $_POST['emailprojetphase12fr'], "libelleanglais" => $_POST['emailprojetphase12en'], "reflibelle" => 'TXT_BODYEMAILPROJET2'),
        array("libellefrancais" => $_POST['emailprojetphase13fr'], "libelleanglais" => $_POST['emailprojetphase13en'], "reflibelle" => 'TXT_BODYEMAILPROJET3'),
        array("libellefrancais" => $_POST['emailprojetphase14fr'], "libelleanglais" => $_POST['emailprojetphase14en'], "reflibelle" => 'TXT_INFOQUESTION'),
        array("libellefrancais" => $_POST['emailprojetphase15fr'], "libelleanglais" => $_POST['emailprojetphase15en'], "reflibelle" => 'TXT_SINCERESALUTATION'),
        array("libellefrancais" => $_POST['emailprojetphase16fr'], "libelleanglais" => $_POST['emailprojetphase16en'], "reflibelle" => 'TXT_RESEAURENATECH'),
        array("libellefrancais" => $_POST['emailprojetphase17fr'], "libelleanglais" => $_POST['emailprojetphase17en'], "reflibelle" => 'TXT_BODYEMAILPROJET4'),
        array("libellefrancais" => $_POST['emailprojetphase18fr'], "libelleanglais" => $_POST['emailprojetphase18en'], "reflibelle" => 'TXT_DONOTREPLY')
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
    $arraylibelleaccept = array(
        array("libellefrancais" => $_POST['emailprojetacce0fr'], "libelleanglais" => $_POST['emailprojetacce0en'], "reflibelle" => 'TXT_MRSMR'),
        array("libellefrancais" => $_POST['emailprojetacce1fr'], "libelleanglais" => $_POST['emailprojetacce1en'], "reflibelle" => 'TXT_BODYEMAILPHASE20'),
        array("libellefrancais" => $_POST['emailprojetacce2fr'], "libelleanglais" => $_POST['emailprojetacce2en'], "reflibelle" => 'TXT_BODYEMAILPHASE21'),
        array("libellefrancais" => $_POST['emailprojetacce3fr'], "libelleanglais" => $_POST['emailprojetacce3en'], "reflibelle" => 'TXT_BODYEMAILPHASE22'),
        array("libellefrancais" => $_POST['emailprojetacce4fr'], "libelleanglais" => $_POST['emailprojetacce4en'], "reflibelle" => 'TXT_BODYEMAILPHASE23'),
        array("libellefrancais" => $_POST['emailprojetacce5fr'], "libelleanglais" => $_POST['emailprojetacce5en'], "reflibelle" => 'TXT_RAPPELINSERTLOGO'),
        array("libellefrancais" => $_POST['emailprojetacce6fr'], "libelleanglais" => $_POST['emailprojetacce6en'], "reflibelle" => 'TXT_SINCERESALUTATION'),
        array("libellefrancais" => $_POST['emailprojetacce7fr'], "libelleanglais" => $_POST['emailprojetacce7en'], "reflibelle" => 'TXT_RESEAURENATECH')
    );
    for ($i = 0; $i < count($arraylibelleaccept); $i++) {
        $libellefrancais = Securite::bdd($arraylibelleaccept[$i]['libellefrancais']);
        $libelleanglais = Securite::bdd($arraylibelleaccept[$i]['libelleanglais']);
        $ancienlibellefr = $manager->getSingle2("select libellefrancais from libelleapplication where reflibelle=?", $arraylibelleaccept[$i]['reflibelle']);
        $ancienlibelleen = $manager->getSingle2("select libelleanglais from libelleapplication where reflibelle=?", $arraylibelleaccept[$i]['reflibelle']);
        if ($ancienlibellefr != $libellefrancais) {
            $libelleapplicationaccept = new Libelleapplication($libellefrancais, $libelleanglais, $arraylibelleaccept[$i]['reflibelle']);
            $manager->updateLibelleApplication($libelleapplicationaccept, $arraylibelleaccept[$i]['reflibelle']);
        }
        if ($ancienlibelleen != $libelleanglais) {
            $libelleapplicationaccept = new Libelleapplication($libellefrancais, $libelleanglais, $arraylibelleaccept[$i]['reflibelle']);
            $manager->updateLibelleApplication($libelleapplicationaccept, $arraylibelleaccept[$i]['reflibelle']);
        }
    }

    $arraylibelleacc = array(
        array("libellefrancais" => $_POST['emailprojetacc0fr'], "libelleanglais" => $_POST['emailprojetacc0en'], "reflibelle" => 'TXT_MRSMR1'),
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
        array("libellefrancais" => $_POST['emailprojettrs0fr'], "libelleanglais" => $_POST['emailprojettrs0en'], "reflibelle" => 'TXT_MRSMR'),
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
        array("libellefrancais" => $_POST['emailprojetref0fr'], "libelleanglais" => $_POST['emailprojetref0en'], "reflibelle" => 'TXT_MRSMR'),
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
    $arraylibelleinfo = array(
        array("libellefrancais" => $_POST['emailprojetinfo0fr'], "libelleanglais" => $_POST['emailprojetinfo0en'], "reflibelle" => 'TXT_PROJETNUM0'),
        array("libellefrancais" => $_POST['emailprojetinfo1fr'], "libelleanglais" => $_POST['emailprojetinfo1en'], "reflibelle" => 'TXT_PROJETACCEPTECENTRALE'),
        array("libellefrancais" => $_POST['emailprojetinfo2fr'], "libelleanglais" => $_POST['emailprojetinfo2en'], "reflibelle" => 'TXT_SINCERESALUTATION'),
        array("libellefrancais" => $_POST['emailprojetinfo3fr'], "libelleanglais" => $_POST['emailprojetinfo3en'], "reflibelle" => 'TXT_RESEAURENATECH'),
        array("libellefrancais" => $_POST['emailprojetinfo4fr'], "libelleanglais" => $_POST['emailprojetinfo4en'], "reflibelle" => 'TXT_DONOTREPLY')
    );

    for ($i = 0; $i < count($arraylibelleinfo); $i++) {
        $libellefrancais = Securite::bdd($arraylibelleinfo[$i]['libellefrancais']);
        $libelleanglais = Securite::bdd($arraylibelleinfo[$i]['libelleanglais']);
        $ancienlibellefr = $manager->getSingle2("select libellefrancais from libelleapplication where reflibelle=?", $arraylibelleinfo[$i]['reflibelle']);
        $ancienlibelleen = $manager->getSingle2("select libelleanglais from libelleapplication where reflibelle=?", $arraylibelleinfo[$i]['reflibelle']);
        if ($ancienlibellefr != $libellefrancais) {
            $libelleapplication = new Libelleapplication($libellefrancais, $libelleanglais, $arraylibelleinfo[$i]['reflibelle']);
            $manager->updateLibelleApplication($libelleapplication, $arraylibelleinfo[$i]['reflibelle']);
        }
        if ($ancienlibelleen != $libelleanglais) {
            $libelleapplication = new Libelleapplication($libellefrancais, $libelleanglais, $arraylibelleinfo[$i]['reflibelle']);
            $manager->updateLibelleApplication($libelleapplication, $arraylibelleinfo[$i]['reflibelle']);
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

    $arraylibelleinfoencours = array(
        array("libellefrancais" => $_POST['emailprojetinfoencours0fr'], "libelleanglais" => $_POST['emailprojetinfoencours0en'], "reflibelle" => 'TXT_PROJETNUM1'),
        array("libellefrancais" => $_POST['emailprojetinfoencours1fr'], "libelleanglais" => $_POST['emailprojetinfoencours1en'], "reflibelle" => 'TXT_EMAILINFOCENTRALEENCOURS'),
        array("libellefrancais" => $_POST['emailprojetinfoencours2fr'], "libelleanglais" => $_POST['emailprojetinfoencours2en'], "reflibelle" => 'TXT_SINCERESALUTATION'),
        array("libellefrancais" => $_POST['emailprojetinfoencours3fr'], "libelleanglais" => $_POST['emailprojetinfoencours3en'], "reflibelle" => 'TXT_RESEAURENATECH'),
        array("libellefrancais" => $_POST['emailprojetinfoencours4fr'], "libelleanglais" => $_POST['emailprojetinfoencours4en'], "reflibelle" => 'TXT_DONOTREPLY')
    );
    for ($i = 0; $i < count($arraylibelleinfoencours); $i++) {
        $libellefrancais = Securite::bdd($arraylibelleinfoencours[$i]['libellefrancais']);
        $libelleanglais = Securite::bdd($arraylibelleinfoencours[$i]['libelleanglais']);
        $ancienlibellefr = $manager->getSingle2("select libellefrancais from libelleapplication where reflibelle=?", $arraylibelleinfoencours[$i]['reflibelle']);
        $ancienlibelleen = $manager->getSingle2("select libelleanglais from libelleapplication where reflibelle=?", $arraylibelleinfoencours[$i]['reflibelle']);
        if ($ancienlibellefr != $libellefrancais) {
            $libelleapplication = new Libelleapplication($libellefrancais, $libelleanglais, $arraylibelleinfoencours[$i]['reflibelle']);
            $manager->updateLibelleApplication($libelleapplication, $arraylibelleinfoencours[$i]['reflibelle']);
        }
        if ($ancienlibelleen != $libelleanglais) {
            $libelleapplication = new Libelleapplication($libellefrancais, $libelleanglais, $arraylibelleinfoencours[$i]['reflibelle']);
            $manager->updateLibelleApplication($libelleapplication, $arraylibelleinfoencours[$i]['reflibelle']);
        }
    }

    header('location: /' . REPERTOIRE . '/Manage_label5/' . $lang . '/msgupdateemailprojetphase1');
} else {
    header('location: /' . REPERTOIRE . '/Login_Error/' . $lang);
    exit();
}
BD::deconnecter();
