<?php

session_start();
include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);;
include '../decide-lang.php';
include '../class/Manager.php';
include_once '../class/Securite.php';
include_once	'../outils/constantes.php';
if (isset($_POST['page_precedente']) && $_POST['page_precedente'] == 'gestionlibellemoncompte.html') {
    $db = BD::connecter();
    $manager = new Manager($db);

    $arraylibelle = array(
        array("libellefrancais" => $_POST['pseudolibedit'], "libelleanglais" => $_POST['pseudoenlibedit'], "reflibelle" => "TXT_PSEUDO"),
        array("libellefrancais" => $_POST['maillibedit'], "libelleanglais" => $_POST['mailenlibedit'], "reflibelle" => "TXT_MAIL"),
        array("libellefrancais" => $_POST['nomlibedit'], "libelleanglais" => $_POST['nomenlibedit'], "reflibelle" => "TXT_NOM"),
        array("libellefrancais" => $_POST['prenomlibedit'], "libelleanglais" => $_POST['prenomenlibedit'], "reflibelle" => "TXT_PRENOM"),
        array("libellefrancais" => $_POST['adresselibedit'], "libelleanglais" => $_POST['adresseenlibedit'], "reflibelle" => "TXT_ADRESSE"),
        array("libellefrancais" => $_POST['cplibedit'], "libelleanglais" => $_POST['cpenlibedit'], "reflibelle" => "TXT_CP"),
        array("libellefrancais" => $_POST['villelibedit'], "libelleanglais" => $_POST['villeenlibedit'], "reflibelle" => "TXT_VILLE"),
        array("libellefrancais" => $_POST['payslibedit'], "libelleanglais" => $_POST['paysenlibedit'], "reflibelle" => "TXT_PAYS"),
        array("libellefrancais" => $_POST['tellibedit'], "libelleanglais" => $_POST['telenlibedit'], "reflibelle" => "TXT_TELEPHONE"),
        array("libellefrancais" => $_POST['faxlibedit'], "libelleanglais" => $_POST['faxenlibedit'], "reflibelle" => "TXT_FAX"),
        array("libellefrancais" => $_POST['nomentlibedit'], "libelleanglais" => $_POST['nomentenlibedit'], "reflibelle" => "TXT_NOMENTELABO"),
        array("libellefrancais" => $_POST['qualitelibedit'], "libelleanglais" => $_POST['qualiteenlibedit'], "reflibelle" => "TXT_QUALITE"),
        array("libellefrancais" => $_POST['nomresplibedit'], "libelleanglais" => $_POST['nomrespenlibedit'], "reflibelle" => "TXT_NOMRESPONSABLE"),
        array("libellefrancais" => $_POST['mailresplibedit'], "libelleanglais" => $_POST['mailrespenlibedit'], "reflibelle" => "TXT_RESPMAILMAIL"),
        array("libellefrancais" => $_POST['tutellelibedit'], "libelleanglais" => $_POST['tutelleenlibedit'], "reflibelle" => "TXT_TUTELLE"),
        array("libellefrancais" => $_POST['autretutellelibedit'], "libelleanglais" => $_POST['autretutelleenlibedit'], "reflibelle" => "TXT_AUTRETUTELLE"),
        array("libellefrancais" => $_POST['codeunitelibedit'], "libelleanglais" => $_POST['codeuniteenlibedit'], "reflibelle" => "TXT_CODEUNITE"),
        array("libellefrancais" => $_POST['autrecodeunitelibedit'], "libelleanglais" => $_POST['autrecodeuniteenlibedit'], "reflibelle" => "TXT_AUTRECODEUNITE"),
        array("libellefrancais" => $_POST['disciplinelibedit'], "libelleanglais" => $_POST['disciplineenlibedit'], "reflibelle" => "TXT_DISCIPLINESCIENTIFIQUE"),
        array("libellefrancais" => $_POST['autredisciplinelibedit'], "libelleanglais" => $_POST['autredisciplineenlibedit'], "reflibelle" => "TXT_AUTREDISCIPLINE"),
        array("libellefrancais" => $_POST['nomresponsablelibedit'], "libelleanglais" => $_POST['nomresponsableenlibedit'], "reflibelle" => "TXT_NOMRESPONSABLE"),
        array("libellefrancais" => $_POST['mailresponsablelibeedit'], "libelleanglais" => $_POST['mailresponsableenlibeedit'], "reflibelle" => "TXT_RESPMAILMAIL"),
        array("libellefrancais" => $_POST['nomemployeurlibedit'], "libelleanglais" => $_POST['nomemployeurenlibedit'], "reflibelle" => "TXT_NOMEMPLOYEUR"),
        array("libellefrancais" => $_POST['autreemployeurlibedit'], "libelleanglais" => $_POST['autreemployeurenlibedit'], "reflibelle" => "TXT_AUTREEMPLOYEUR"),
        array("libellefrancais" => $_POST['nomentrepriselibedit'], "libelleanglais" => $_POST['nomentrepriseenlibedit'], "reflibelle" => "TXT_NOMENTREPRISE"),
        array("libellefrancais" => $_POST['typeentrepriselibedit'], "libelleanglais" => $_POST['typeentrepriseenlibedit'], "reflibelle" => "TXT_TYPEENTREPRISE"),
        array("libellefrancais" => $_POST['secteuractivitelibedit'], "libelleanglais" => $_POST['secteuractiviteenlibedit'], "reflibelle" => "TXT_SECTEURACTIVITE")
    );

    for ($i = 0; $i < count($arraylibelle); $i++) {
        if (!empty($arraylibelle[$i]['libellefrancais'])) {
            $libellefrancais = Securite::bdd($arraylibelle[$i]['libellefrancais']);
            $ancienlibelle = $manager->getSingle2("select libellefrancais from libelleapplication where reflibelle=?", $arraylibelle[$i]['reflibelle']);
            $libelleanglais = $manager->getSingle2("select libelleanglais from libelleapplication where reflibelle=?", $arraylibelle[$i]['reflibelle']);
            if ($ancienlibelle != $libellefrancais) {
                $libelleapplication = new Libelleapplication($libellefrancais, $libelleanglais, $arraylibelle[$i]['reflibelle']);
                $manager->updateLibelleApplication($libelleapplication, $arraylibelle[$i]['reflibelle']);
            }
        }
    }
    header('location: ../gestionlibelle.php?lang=' . $lang . '&page_precedente=' . basename(__FILE__) . '&msgupdate');
} else {
					header('location: /'	.	REPERTOIRE	.	'/Login_Error/'	.	$lang);
    exit();
}

BD::deconnecter();
?>