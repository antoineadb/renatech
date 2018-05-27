<?php

include '../decide-lang.php';
include '../outils/toolBox.php';
include '../class/Manager.php';
include '../class/Securite.php';
echo '<pre>';print_r($_POST);echo '</pre>';exit();
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER

if (isset($_POST['page_precedente']) && $_POST['page_precedente'] == 'vueModifProjet.html') {

    $idprojet = $_GET['idprojet'];
    $statut = $manager->getSingle2("select idstatutprojet_statutprojet from concerne where idprojet_projet=?", $idprojet);
    $msgerrdescontexte = '';
//------------------------------------------------------------------------------------------------------------------
//																																																CONTEXTE
//------------------------------------------------------------------------------------------------------------------
    $context = str_replace('<br />', '', $manager->getSingle2("select contexte from projet where idprojet=?", $idprojet));
    if (!empty($_POST['contextValeur'])) {
        $contexte = filterEditor(Securite::bdd(trim($_POST['contextValeur'])));
        if (strlen($contexte) > 8000) {
            $msgerrdescontexte .= TXT_LIMITEDITEURCONTEXTE;
        }
    } elseif (!empty($context)) {
        $contexte = $manager->getSingle2("select contexte from projet where idprojet=?", $idprojet);
    } else {
        $msgerrdescontexte .= TXT_ERRCONTEXTE;
    }
//------------------------------------------------------------------------------------------------------------------
//																																																DESCRIPTIF
//------------------------------------------------------------------------------------------------------------------
    $descr = str_replace('<br />', '', $manager->getSingle2("select description from projet where idprojet=? ", $idprojet));
    if (!empty($_POST['descriptifValeur'])) {
        $descriptif = filterEditor(Securite::bdd(trim($_POST['descriptifValeur'])));
        if (strlen($descriptif) > 8000) {
            $msgerrdescontexte .= TXT_LIMITEDITEURDESCRIPTION;
        }
    } else {
        if (!empty($descr)) {
            $descriptif = $manager->getSingle2("select description from projet where idprojet=? ", $idprojet);
        } else {
            $msgerrdescontexte .= TXT_ERRDESCRIPTIF;
        }
    }
//------------------------------------------------------------------------------------------------------------------
    if (!empty($msgerrdescontexte)) {
        echo '<script>window.location.replace("../phase2.php?lang=' . $lang . '&msgerrdescontexte=' . ($msgerrdescontexte) . '&idprojet=' . $idprojet . '&statut=' . $statut . '")</script>';
        exit();
    } else {
        $projetcontextedescriptif = new Projetcontextedescriptif($idprojet, $description, $contexte, $confidentiel, $titre, $acronyme);
        $manager->updateprojetcontextedescriptif($projetcontextedescriptif, $idprojet);
        echo '<script>window.location.replace("../phase2.php?lang=' . $lang . '&idprojet=' . $_GET['idprojet'] . '&statut=' . $statut . '")</script>';
    }
    BD::deconnecter(); //DECONNEXION A LA BASE DE DONNEE
} else {
    include_once '../decide-lang.php';
    echo '<script>window.location.replace("../erreurlogin.php?lang=' . $lang . '")</script>';
}
