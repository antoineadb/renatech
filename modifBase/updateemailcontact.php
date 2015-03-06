<?php

session_start();
include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);;
include '../decide-lang.php';
include '../class/Manager.php';
include '../outils/constantes.php';
if (isset($_POST['page_precedente']) && $_POST['page_precedente'] == 'gestionemailcontact.html') {
    $db = BD::connecter();
    $manager = new Manager($db);
    include_once '../class/Securite.php';

    $arraylibellecontact = array(
        array("libellefrancais" => $_POST['emailcontactfr'], "libelleanglais" => $_POST['emailcontacten'], "reflibelle" => 'TXT_MRSMR0'),
        array("libellefrancais" => $_POST['emailcontact1fr'], "libelleanglais" => $_POST['emailcontact1en'], "reflibelle" => 'TXT_MAILCONTACTMILIEU'),
        array("libellefrancais" => $_POST['emailcontact2fr'], "libelleanglais" => $_POST['emailcontact2en'], "reflibelle" => 'TXT_MAILCONTACTMILIEU1'),
        array("libellefrancais" => $_POST['emailcontact3fr'], "libelleanglais" => $_POST['emailcontact3en'], "reflibelle" => 'TXT_RESEAURENATECH'),
        array("libellefrancais" => $_POST['emailcontact4fr'], "libelleanglais" => $_POST['emailcontact4en'], "reflibelle" => 'TXT_NB'),
        array("libellefrancais" => $_POST['emailcontact5fr'], "libelleanglais" => $_POST['emailcontact5en'], "reflibelle" => 'TXT_NB1'),
        array("libellefrancais" => $_POST['emailcontact6fr'], "libelleanglais" => $_POST['emailcontact6en'], "reflibelle" => 'TXT_DONOTREPLY')
    );

    for ($i = 0; $i < count($arraylibellecontact); $i++) {
        $libellefrancais = Securite::bdd($arraylibellecontact[$i]['libellefrancais']);
        $libelleanglais = Securite::bdd($arraylibellecontact[$i]['libelleanglais']);
        $ancienlibellefr = $manager->getSingle2("select libellefrancais from libelleapplication where reflibelle=?", $arraylibellecontact[$i]['reflibelle']);
        $ancienlibelleen = $manager->getSingle2("select libelleanglais from libelleapplication where reflibelle=?", $arraylibellecontact[$i]['reflibelle']);
        if ($ancienlibellefr != $libellefrancais) {
            $libelleapplication = new Libelleapplication($libellefrancais, $libelleanglais, $arraylibellecontact[$i]['reflibelle']);
            $manager->updateLibelleApplication($libelleapplication, $arraylibellecontact[$i]['reflibelle']);
        }
        if ($ancienlibelleen != $libelleanglais) {
            $libelleapplication = new Libelleapplication($libellefrancais, $libelleanglais, $arraylibellecontact[$i]['reflibelle']);
            $manager->updateLibelleApplication($libelleapplication, $arraylibellecontact[$i]['reflibelle']);
        }
    }

				$arraylibelleresp = array(
        array("libellefrancais" => $_POST['emailresp0fr'], "libelleanglais" => $_POST['emailresp0en'], "reflibelle" => 'TXT_RESPONSABLESUJETEMAIL'),
        array("libellefrancais" => $_POST['emailresp1fr'], "libelleanglais" => $_POST['emailresp1en'], "reflibelle" => 'TXT_MRSMR'),
        array("libellefrancais" => $_POST['emailresp2fr'], "libelleanglais" => $_POST['emailresp2en'], "reflibelle" => 'TXT_RESPONSABLEBODYTEMAIL1'),
        array("libellefrancais" => $_POST['emailresp3fr'], "libelleanglais" => $_POST['emailresp3en'], "reflibelle" => 'TXT_RESPONSABLEBODYTEMAIL2'),
        array("libellefrancais" => $_POST['emailresp4fr'], "libelleanglais" => $_POST['emailresp4en'], "reflibelle" => 'TXT_RESPONSABLEBODYTEMAIL3'),
        array("libellefrancais" => $_POST['emailresp5fr'], "libelleanglais" => $_POST['emailresp5en'], "reflibelle" => 'TXT_RESPONSABLEBODYTEMAIL4'),
        array("libellefrancais" => $_POST['emailresp6fr'], "libelleanglais" => $_POST['emailresp6en'], "reflibelle" => 'TXT_RESPONSABLEBODYTEMAIL5'),
								array("libellefrancais" => $_POST['emailresp7fr'], "libelleanglais" => $_POST['emailresp7en'], "reflibelle" => 'TXT_RESPONSABLEBODYTEMAIL6'),
								array("libellefrancais" => $_POST['emailresp8fr'], "libelleanglais" => $_POST['emailresp8en'], "reflibelle" => 'TXT_INFOQUESTION')
								
    );
				for ($i = 0; $i < count($arraylibelleresp); $i++) {
        $libellefrancais = Securite::bdd($arraylibelleresp[$i]['libellefrancais']);
        $libelleanglais = Securite::bdd($arraylibelleresp[$i]['libelleanglais']);
        $ancienlibellefr = $manager->getSingle2("select libellefrancais from libelleapplication where reflibelle=?", $arraylibelleresp[$i]['reflibelle']);
        $ancienlibelleen = $manager->getSingle2("select libelleanglais from libelleapplication where reflibelle=?", $arraylibelleresp[$i]['reflibelle']);
        if ($ancienlibellefr != $libellefrancais) {
            $libelleapplication = new Libelleapplication($libellefrancais, $libelleanglais, $arraylibelleresp[$i]['reflibelle']);
            $manager->updateLibelleApplication($libelleapplication, $arraylibelleresp[$i]['reflibelle']);
        }
        if ($ancienlibelleen != $libelleanglais) {
            $libelleapplication = new Libelleapplication($libellefrancais, $libelleanglais, $arraylibelleresp[$i]['reflibelle']);
            $manager->updateLibelleApplication($libelleapplication, $arraylibelleresp[$i]['reflibelle']);
        }
    }

    header('location: /' . REPERTOIRE . '/Manage_label4/' . $lang . '/msgupdateemailcontact');
} else {
    header('location: /' . REPERTOIRE . '/Login_Error/' . $lang);
    exit();
}
BD::deconnecter();