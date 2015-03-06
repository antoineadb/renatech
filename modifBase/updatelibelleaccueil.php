<?php

session_start();
include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);;
include '../decide-lang.php';
include '../class/Manager.php';
include_once '../outils/constantes.php';
if (isset($_POST['page_precedente']) && $_POST['page_precedente'] == 'gestionlibelleaccueil.html') {
    $db = BD::connecter();
    $manager = new Manager($db);
    include_once '../class/Securite.php';

    $arraylibelle = array(
        array("libellefrancais" => $_POST['accueillibedit'], "libelleanglais" => $_POST['accueilenlibedit'], "reflibelle" => 'TXT_ACCUEILPROJETADMIN'),
        array("libellefrancais" => $_POST['projetadminfr'], "libelleanglais" => $_POST['projetadminen'], "reflibelle" => 'TXT_PROJETADMIN'),
        array("libellefrancais" => $_POST['accueil0libedit'], "libelleanglais" => $_POST['accueil0enlibedit'], "reflibelle" => 'TXT_ACCUEILPROJETADMIN0'),
        array("libellefrancais" => $_POST['accueillibedit1'], "libelleanglais" => $_POST['accueilenlibedit1'], "reflibelle" => 'TXT_ACCUEILPROJETADMIN1'),
        array("libellefrancais" => $_POST['accueillibedit2'], "libelleanglais" => $_POST['accueilenlibedit2'], "reflibelle" => 'TXT_ACCUEILPROJETADMIN2'),
        array("libellefrancais" => $_POST['accueillibedit3'], "libelleanglais" => $_POST['accueilenlibedit3'], "reflibelle" => 'TXT_ACCUEILPROJETADMIN3'),
        array("libellefrancais" => $_POST['accueilprofillibedit'], "libelleanglais" => $_POST['accueilprofilenlibedit'], "reflibelle" => 'TXT_MODIFPROFILADMIN'),
        array("libellefrancais" => $_POST['accueiluser0libedit'], "libelleanglais" => $_POST['accueiluser0enlibedit'], "reflibelle" => 'TXT_ACCUEILPROJET0'),
        array("libellefrancais" => $_POST['accueiluser1libedit'], "libelleanglais" => $_POST['accueiluser1enlibedit'], "reflibelle" => 'TXT_ACCUEILPROJET1'),
        array("libellefrancais" => $_POST['accueilprojetsoumis0fr'], "libelleanglais" => $_POST['accueilprojetsoumis0en'], "reflibelle" => 'TXT_ACCUEILPROJETSOUMIS0'),
        array("libellefrancais" => $_POST['accueilprojetsoumis1fr'], "libelleanglais" => $_POST['accueilprojetsoumis1en'], "reflibelle" => 'TXT_ACCUEILPROJETSOUMIS1'),
        array("libellefrancais" => $_POST['accueilprojetuser0fr'], "libelleanglais" => $_POST['accueilprojetuser0en'], "reflibelle" => 'TXT_ACCUEILPROJETUSER0'),
        array("libellefrancais" => $_POST['accueilprojetuser1fr'], "libelleanglais" => $_POST['accueilprojetuser1en'], "reflibelle" => 'TXT_ACCUEILPROJETUSER1'),
        array("libellefrancais" => $_POST['modifprofilfr'], "libelleanglais" => $_POST['modifprofilen'], "reflibelle" => 'TXT_MODIFPROFIL'));
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
    header('location: /' . REPERTOIRE . '/Manage_label2/' . $lang . '/msgupdateconnexion');
} else {
    header('location: /' . REPERTOIRE . '/Login_Error/' . $lang);
    exit();
}
BD::deconnecter();
