<?php

include_once '../decide-lang.php';
include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);
include_once '../class/Manager.php';
include_once '../class/Securite.php';
include_once '../outils/constantes.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
//RECUPERATION DES DONNEES DE LA BASE DE DONNEES POUR  COMPARER AUX DONNEES SAISIES
$arraydonneeBDD = $manager->getList2("select titre,acronyme,confidentiel,contexte,description,attachement from projet where idprojet=?", $idprojet);
$titreBDD =  str_replace("– "," - ",$arraydonneeBDD[0]['titre']);
if (!empty($arraydonneeBDD[0]['acronyme'])) {
    $acronymeBDD = $arraydonneeBDD[0]['acronyme']; //CHAMP FACULTATIF
}else{
    $acronymeBDD ='';
}
if($arraydonneeBDD[0]['confidentiel']){
    $confidentielBDD = 'TRUE';
}  else {
    $confidentielBDD = 'FALSE';
}

$contexteBDD = $arraydonneeBDD[0]['contexte'];
$descriptionBDD = $arraydonneeBDD[0]['description'];
if (!empty($arraydonneeBDD[0]['attachement'])) {
    $attachementBDD = $arraydonneeBDD[0]['attachement']; //CHAMP FACULTATIF
}

$idprojet = $_GET['idprojet'];
$statut = $manager->getSingle2("select idstatutprojet_statutprojet from concerne where idprojet_projet=?", $idprojet);
if (!empty($_POST['contextValeur'])) {
    $contexte = clean($_POST['contextValeur']);
    if ($contexteBDD != $contexte) {
        $_SESSION['contextemodif'] = $contexte;
    } else {
        $_SESSION['contextemodif'] = '';
    }
}
if (!empty($_POST['descriptifValeur'])) {
    $descriptif = clean($_POST['descriptifValeur']);
    if ($descriptionBDD != $descriptif) {
        $_SESSION['descriptionmodif'] = $descriptif;
    } else {
        $_SESSION['descriptionmodif'] = '';
    }
}
if (!empty($_POST['acronyme'])) {
    $acronyme = $_POST['acronyme'];
    if ($acronymeBDD != $acronyme) {
        $_SESSION['acronymemodif'] = $acronyme;
    } else {
        $_SESSION['acronymemodif'] = '';
    }
} else {
    $acronyme = $manager->getSingle2("select acronyme from projet where idprojet=? ", $idprojet);
    $_SESSION['acronymemodif'] = '';
}
if (!empty($_POST['titreProjet'])) {
    $titre = $_POST['titreProjet'];
    if ($titreBDD != $titre) {
        $_SESSION['titremodif'] = $titre;
    } else {
        $_SESSION['titremodif'] = '';
    }
} else {
    $titre = $manager->getSingle2("select titre from projet where idprojet=? ", $idprojet);
    $_SESSION['titremodif'] = '';
}

if (!empty($_FILES['fichierProjet']['name'])) {
    $attachement = stripslashes(Securite::bdd($_FILES['fichierProjet']['name']));
        if ($arraydonneeBDD[0]['attachement'] != $attachement) {
            $_SESSION['attachementmodif'] = $attachement;
        } else {
            $_SESSION['attachementmodif'] = '';
        }
} else {
    $attachement = $manager->getSingle2("select attachement from projet where idprojet = ?", $idprojet);
    if (empty($attachement)) {
        $attachement = '';
    }
    $_SESSION['attachementmodif'] = '';
}

if (!empty($_POST['centrale'])) {
    $idcentrale = substr($_POST['centrale'], -1);
    $idstatutprojet = $manager->getSingle2("select idstatutprojet_statutprojet from concerne where idprojet_projet=?", $idprojet);
    $manager->deleteconcerneprojet($idprojet);
    $concerne = new Concerne($idcentrale, $idprojet, $idstatutprojet, '');
    $manager->addConcerne($concerne);
}

$confidentiel = $_POST['confid'];
if ($confidentielBDD != $confidentiel) {    
        $_SESSION['confidentielmodif'] = $confidentiel;
}else{    
    $_SESSION['confidentielmodif'] = '';
}
$projetcontextedescriptif = new Projetcontextedescriptif($idprojet, $descriptif, $contexte, $confidentiel, $titre, $acronyme, $attachement);
$manager->updateprojetcontextedescriptif($projetcontextedescriptif, $idprojet);
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              CENTRALE DE PROXIMITE
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------        
    $centrale_proximiteBDD = $manager->getList2("SELECT idcentraleproximite FROM centraleproximiteprojet where idprojet=?", $idprojet);
    $centraleProximiteBDD = array();
    for ($i = 0; $i < count($centrale_proximiteBDD); $i++) {
        array_push($centraleProximiteBDD, 'cp' . $centrale_proximiteBDD[$i]['idcentraleproximite']);
    }
    $centrale_proximite = stripslashes(Securite::bdd($_POST['centrale_proximite']));
    if (!empty($_POST['descriptioncentraleproximite'])) {
        $descriptioncentraleproximiteBDD = $_POST['descriptioncentraleproximite'];
    }else{
        $descriptioncentraleproximiteBDD = '';
    }

    if ($_POST['centrale_proximite'] == 'TRUE') {
        if (!empty($_POST['centrale_Proximite'])) {
            if ($centraleProximiteBDD != $_POST['centrale_Proximite']) {
                $_SESSION['centraleproximitemodif'] = $_POST['centrale_Proximite'];
            } else {
                $_SESSION['centraleproximitemodif'] = '';
            }
            
            $manager->deletecentraleproximiteprojet($idprojet);
            
            for ($i = 0; $i < count($_POST['centrale_Proximite']); $i++) {
                $idcentrale_proximite = substr($_POST['centrale_Proximite'][$i], 2);
                $centraleproximite = new CentraleProximiteProjet($idcentrale_proximite, $idprojet);
                $manager->addCentraleProximiteProjet($centraleproximite);
            }
        } else {
            $_SESSION['centraleproximitemodif'] = '';
             
        }
        if (!empty($_POST['centraleproximitevaleur'])) {
            $descriptioncentraleproximite = clean($_POST['centraleproximitevaleur']);
            if ($descriptioncentraleproximiteBDD != $descriptioncentraleproximite) {
                $_SESSION['descriptioncentraleproximitemodif'] = $descriptioncentraleproximite;
            } else {
                $_SESSION['descriptioncentraleproximitemodif'] = '';
                $descriptioncentraleproximite = $descriptioncentraleproximiteBDD;
            }
        } else {
            $_SESSION['descriptioncentraleproximitemodif'] = '';
            $descriptioncentraleproximite = $descriptioncentraleproximiteBDD;
        }
    }else{        
            $descriptioncentraleproximite='';
            $manager->deletecentraleproximiteprojet($idprojet);
    }
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              FIN DES CENTRALES DE PROXIMITE
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------        



//MISE A JOUR DE LA PIECE JOINTE
$dossier1 = '../upload/';
$fichierPhase1 = basename($_FILES['fichierProjet']['name']);
$taille_maxi1 = 1048576;
$taille1 = filesize($_FILES['fichierProjet']['tmp_name']);
$extensions = array('.pdf', '.PDF');
$extension1 = strrchr($_FILES['fichierProjet']['name'], '.');
if (!empty($_FILES['fichierProjet']['name'])) {
    if (!in_array($extension1, $extensions)) {//VERIFICATION DU FORMAT SI IL N'EST PAS BON ON SORT
        $erreur1 = TXT_ERREURUPLOAD;
        header('Location: /' . REPERTOIRE . '/Upload_Errorphase1/' . $lang . '/' . rand(0, 10000) . '/' . $idprojet);
        exit();
    } elseif ($taille1 > $taille_maxi1) {//VERIFICATION DE LA TAILLE SI ELLE EST >1mo ON SORT
        $erreur1 = TXT_ERREURTAILLEFICHIER;
        header('Location: /' . REPERTOIRE . '/Upload_Errorsizephase1/' . $lang . '/' . rand(0, 10000) . '/' . $idprojet);
        exit();
    } elseif (!isset($erreur1)) {//S'il n'y a pas d'erreur, on upload
        if (move_uploaded_file($_FILES['fichierProjet']['tmp_name'], $dossier1 . $fichierPhase1)) {
            chmod($dossier1 . $fichierPhase1, 0777);
        }
    }
}
BD::deconnecter(); //DECONNEXION A LA BASE DE DONNEE