<?php

session_start();
include_once '../class/email.php';
include_once '../class/Securite.php';
include_once '../decide-lang.php';
include_once '../class/Manager.php';
include_once '../outils/constantes.php';
$db = BD::connecter();
$manager = new Manager($db);

$idemail = $manager->getSingle("select max(idemail) from emailRenatech");
$emailDemande = array($manager->getSingle2("select email from emailRenatech where idemail = ?", $idemail));
$cc = array($_POST['emailDemande']);

$nomDemande = $_POST['nomDemande'];
$arrayDefaultValues = $manager->getList2("select nom,mail,entrepriselaboratoire	 from loginpassword,utilisateur where idlogin_loginpassword=idlogin and pseudo=?", $_SESSION['pseudo']);
if (!empty($arrayDefaultValues)) {
    $nom = $arrayDefaultValues[0]['nom'];
    $mail = $arrayDefaultValues[0]['mail'];
    $entrepriselaboratoire = $arrayDefaultValues[0]['entrepriselaboratoire'];
}

if (isset($_POST['objetDemande']) && !empty($_POST['objetDemande'])) {
    $objet = utf8_decode($_POST['objetDemande']);
    $_SESSION['objet'] = $_POST['objetDemande'];
} else {
    $objet = utf8_decode(TXT_NONSPECIFIE);
}

$objet = utf8_decode(affiche('TXT_DEMANDEFAISABILITERMAILENATECH'));


if (isset($_POST['messageValeur']) && !empty($_POST['messageValeur'])) {
    $message = utf8_decode(clean($_POST['messageValeur']));
    $_SESSION['message'] = $message;
} elseif (empty($_POST['messageValeur'])) {
    $message = utf8_decode($_SESSION['message']);
}
$body = affiche('TXT_BODYEMAILPROJET0') . '<br>' . utf8_decode(affiche('TXT_DEMANDEFAISABILITE')) . '<br><br>' . affiche('TXT_NOMDEMANDEUR') . ':  ' . $nom . '<br>' .
        affiche('TXT_MAILDEMANDEUR') . ':</u>  ' . $mail . '<br>' . TXT_NOMLABOENTREPRISE . ':  ' . $entrepriselaboratoire .
        '<br><br><u>Objet: </u><br>' . $objet . '<br><br>' . '<u>' . TXT_MESS . ': </u><br>' . $message . '<br><br>' . utf8_decode(affiche('TXT_SINCERESALUTATION')) . '<br><br>'
        . utf8_decode(affiche('TXT_RESEAURENATECH')) . '<br><br>' . utf8_decode(affiche('TXT_DONOTREPLY'));

$id = $manager->getSingle("select max(id_demande) from demande_faisabilite") + 1;
if (isset($erreur)) {
    header('Location: /' . REPERTOIRE . '/new_request/' . $lang . '/' . "Err01");
    exit();
} elseif (isset($erreur1)) {
    header('Location: /' . REPERTOIRE . '/new_request/' . $lang . '/' . "Err02");
    exit();
} elseif (!empty($_FILES['fichierProjet']['name'])) {
    $attachement = stripslashes(Securite::bdd($_FILES['fichierProjet']['name']));
    $fichierPhase1 = basename($_FILES['fichierProjet']['name']);
    $taille_maxi1 = 1048576;
    $taille1 = filesize($_FILES['fichierProjet']['tmp_name']);
    $extensions = array('.pdf', '.PDF');
    $extension1 = strrchr($_FILES['fichierProjet']['name'], '.');
    if (!empty($_FILES['fichierProjet']['name'])) {
        if (!in_array($extension1, $extensions)) {//VERIFICATION DU FORMAT SI IL N'EST PAS BON ON SORT                
            $erreur = TXT_ERREURUPLOAD;
            $attachement = "";
        } elseif ($taille1 > $taille_maxi1) {//VERIFICATION DE LA TAILLE SI ELLE EST >1mo ON SORT
            $erreur1 = TXT_ERREURTAILLEFICHIER;
            $attachement = "";
        } elseif (!isset($erreur) && !isset($erreur1)) {//S'il n'y a pas d'erreur, on upload
            $path = $_FILES['fichierProjet']['tmp_name'];
            $fileName = $_FILES['fichierProjet']['name'];
            move_uploaded_file($path,"../tmp_file/".$fileName);
        }
    }
    if ($_FILES["fichierProjet"]["error"] > 0) {
        header('Location: /' . REPERTOIRE . '/new_request/' . $lang . '/' . "Err01");
    } else {
        if ($_FILES['fichierProjet']['size'] > $taille_maxi1) {
            header('Location: /' . REPERTOIRE . '/new_request/' . $lang . '/' . "Err02");
        } else {
            $extensions_valides = array('pdf');
            $extension_upload = strtolower(substr(strrchr($_FILES['fichierProjet']['name'], '.'), 1));
            $ccs='';
            if (in_array($extension_upload, $extensions_valides)) {
                foreach ($cc as $key => $value) {
                    $ccs .= $value . ',';
                }
                if(!envoieEmailAttachement($body, $_POST['objetDemande'], $emailDemande, $cc[0], "../tmp_file",$fileName)) {                
                    header('Location: /' . REPERTOIRE . '/new_request/' . $lang . '/' . "Err99");
                } else {
                    unlink("../tmp_file/".$fileName);
                    header('Location: /' . REPERTOIRE . '/home/' . $lang . '/ok');
                }
            } else {
                echo 'Erreur : Sélectionnez un fichier de type pdf';
            }
        }
    }
} else {
    envoieEmail($body, $objet, $emailDemande, $cc);
    $dateDemande = date('Y-m-d');

    $demande = new DemandeFaisabilite($id, $_POST['nomDemande'], $_POST['emailDemande'], $_POST['objetDemande'], $dateDemande);
    $manager->addDemande($demande);
    header('Location: /' . REPERTOIRE . '/home/' . $lang);
    exit();
}
BD::deconnecter();


