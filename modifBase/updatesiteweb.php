<?php

session_start();
include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);;
include '../decide-lang.php';
include '../class/Manager.php';
include_once '../outils/constantes.php';
if (isset($_POST['page_precedente']) && $_POST['page_precedente'] == 'gestionsiteweb.html') {
    $db = BD::connecter();
    $manager = new Manager($db);
    include_once '../class/Securite.php';

    $arraysite = array(
        array("adressesitewebcentrale" => $_POST['sitefemto'], "refsiteweb" => "FEMTO"),
        array("adressesitewebcentrale" => $_POST['siteief'], "refsiteweb" => "IEF"),
        array("adressesitewebcentrale" => $_POST['siteiemn'], "refsiteweb" => "IEMN"),
        array("adressesitewebcentrale" => $_POST['sitelaas'], "refsiteweb" => "LAAS"),
        array("adressesitewebcentrale" => $_POST['sitelpn'], "refsiteweb" => "LPN"),
        array("adressesitewebcentrale" => $_POST['siteltm'], "refsiteweb" => "LTM")
    );

    for ($j = 0; $j < count($arraysite); $j++) {
        if (!empty($arraysite[$j]['adressesitewebcentrale'])) {
            $anciensite = $manager->getSingle2("select adressesitewebcentrale from sitewebapplication where refsiteweb=?", $arraysite[$j]['refsiteweb']);
            if ($anciensite != $arraysite[$j]['adressesitewebcentrale']) {
                $sitewebapplication = new Sitewebapplication($arraysite[$j]['refsiteweb'], $arraysite[$j]['adressesitewebcentrale']);
                $manager->updatesitewebApplication($sitewebapplication, $arraysite[$j]['refsiteweb']);
            }
        }
    }
    header('location: /' . REPERTOIRE . '/Manage_label3/' . $lang . '/msgupdatesiteweb');
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
    exit();
}

BD::deconnecter();
