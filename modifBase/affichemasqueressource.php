<?php

session_start();

$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include '../decide-lang.php';
include_once '../class/Securite.php';
include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);
if (empty($_POST['idressourceactuel'])) {
    header('location:/'.REPERTOIRE.'/hide_ressourceErr1/' . $lang . '/TXT_MESSAGEERREURRESSOURCESELECT');
    exit();
} else {
    $idressourceactuel = $_POST['idressourceactuel'];
}
if (empty($_POST['modifressource'])) {
    header('location:/'.REPERTOIRE.'/hide_ressourceErr2/' . $lang . '/TXT_MESSAGEERREURRESSOURCENONSAISIE');
    exit();
}else if (empty($_POST['modifressourceen'])) {
    header('location:/'.REPERTOIRE.'/hide_ressourceErr2/' . $lang . '/TXT_MESSAGEERREURRESSOURCENONSAISIE');
    exit();
} else {
    $modifressource = stripslashes(Securite::bdd($_POST['modifressource']));
    $modifressourceen = stripslashes(Securite::bdd($_POST['modifressourceen']));
    if ($lang == 'fr') {
        $idressourceactuel1 = $manager->getSingle2("SELECT  idressource FROM ressource where libelleressource =?", $modifressource);
    } elseif ($lang == 'en') {
        $idressourceactuel1 = $manager->getSingle2("SELECT  idressource FROM ressource where libelleressourceen =?", $modifressourceen);
    }
    if (empty($idressourceactuel1)) {
        header('location:/'.REPERTOIRE.'/hide_ressourceErr2/' . $lang . '/TXT_MESSAGEERREURRESSOURCENONSAISIE');
        exit;
    } else {
        if (isset($_POST['masqueressource']) && $_POST['masqueressource'] == TXT_MASQUER) {
            if ($lang == 'fr') {
                $ressource = new Ressource($idressourceactuel, $modifressource, TRUE, $modifressourceen);
            } elseif ($lang == 'en') {
                $ressource = new Ressource($idressourceactuel, $modifressourceen, TRUE, $modifressource);
            }
            $manager->afficheHideRessource($ressource, $idressourceactuel);
            header('location:/'.REPERTOIRE.'/hide_ressource/' . $lang . '/TXT_MESSAGESERVEURRESSOURCEMASQUER');
            exit;
        } elseif (isset($_POST['afficheressource']) && $_POST['afficheressource'] == TXT_REAFFICHER) {
            if ($lang == 'fr') {
                $ressource = new Ressource($idressourceactuel, $modifressource, FALSE, $modifressourceen);
            } elseif ($lang == 'en') {
                $ressource = new Ressource($idressourceactuel, $modifressourceen, FALSE, $modifressource);
            }
            $manager->afficheHideRessource($ressource, $idressourceactuel);
            header('location:/'.REPERTOIRE.'/show_ressource/' . $lang . '/TXT_MESSAGESERVEURRESSOURCEAFFICHE');
            exit;
        }
    }
}
BD::deconnecter();