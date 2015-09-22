<?php
session_start();
include_once '../outils/toolBox.php';
include_once '../class/Chiffrement.php';
include '../decide-lang.php';
include '../class/Manager.php';
include '../outils/constantes.php';
showError($_SERVER['PHP_SELF']);
if (isset($_POST['pseudo'])) {
    $pseudo=$_POST['pseudo'];
    check_authent($pseudo);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
if (empty($_SESSION['nomConnect'])) {
    $db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
    $manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
//VERIFICATION QUE LE LOGIN EXISTE
    $pseudo = $_POST['pseudo'];
    $idlogin = $manager->getSingle2("select idlogin from loginpassword where pseudo=?", $pseudo);

    if (!empty($idlogin)) {// LE LOGIN EXISTE
        $_SESSION['pseudo'] = $pseudo;
        $passe = $manager->getSingle2("select motdepasse from loginpassword where pseudo=?", $pseudo); //RECUPERATION DU MOT DE PASSE
        if (sha1($_POST['motPasse']) == $passe) {//CONTROLE QUE LE MOT DE PASSE EST IDENTIQUE
            //VERIFICATION SI LE COMPTE N'EST PAS DESACTIVE
            $actif = $manager->getSingle2("select actif from loginpassword where pseudo=?", $pseudo);
            if (empty($actif)) {
                $actif = 'FALSE';
            } else {
                $actif = 'TRUE';
            }

            if ($actif == 'FALSE') {
                header('Location: /' . REPERTOIRE . '/comptedesactive/' . $lang); //CE COMPTE A ETE DESACTIVE
                exit();
            } else {
                $mail = $manager->getSingle2("select mail from loginpassword where pseudo=?", $pseudo);
                $_SESSION['email'] = $mail;
                unset($_SESSION['mail']);
                //CONTROLE SI DEMANDE DE NOUVEAU MOT DE PASSE
                $motpasseenvoye = $manager->getSingle2("SELECT motpasseenvoye  FROM loginpassword where pseudo=?", $pseudo);

                if ($motpasseenvoye == TRUE || $motpasseenvoye == 1 || $motpasseenvoye == 'TRUE' || $motpasseenvoye == 't' || !empty($motpasseenvoye)) {//DEMANDE DE CHANGEMENT DE MOT DE PASSE
                    header('Location: /' . REPERTOIRE . '/change_password/' . $lang);
                    exit();
                }
            }
            $idUtilisateur = $manager->getSingle2("SELECT idutilisateur FROM utilisateur where idlogin_loginpassword=?", $idlogin);
            if (!empty($idUtilisateur)) {//L'UTILISATEUR A BIEN ETE RENSEIGNE
                $_SESSION['idutilisateur'] = $idUtilisateur;
                // SAUVEGARDE DU NOM ET DU PRENOM EN VARIABLE DE SESSION ET ON PERMET L'AFFICHAGE DU MENU
                nomEntete($mail, $pseudo);
                //SAUVEGARDE DES NOMS,PRENOMS ET ADRESSE DU DEMANDEUR EN VARIABLE DE SESSION
                $row = $manager->getListbyArray("SELECT nom,prenom,adresse,idtypeutilisateur_typeutilisateur FROM utilisateur where idutilisateur=?", array($idUtilisateur));
                $nom = $row[0]['nom'];
                $prenom = $row[0]['prenom'];
                $adresse = $row[0]['adresse'];
                $idTypeUser = $row[0]['idtypeutilisateur_typeutilisateur'];
                $_SESSION['nom'] = $nom;
                $_SESSION['prenom'] = $prenom;
                $_SESSION['adresse'] = $adresse;
                $_SESSION['idTypeUser'] = $idTypeUser;
                $_SESSION['lastLoad'] = time(); //MISE A JOUR DE L'HEURE DE CONNEXION
                include_once '../compteur.php';
                
                include_once '../class/Cache.php';
                $libelleCentrale = $manager->getSingle2("select libellecentrale from centrale,utilisateur, loginpassword where idlogin_loginpassword=idlogin and idcentrale_centrale=idcentrale and pseudo=? ", $_SESSION['pseudo']);
                $videCache = new Cache(REP_ROOT . '/cache/' . $libelleCentrale . '/', 1);
                $videCache->clear();
                
                header('Location: /' . REPERTOIRE . '/home/' . $lang); //REDIRECTION VERS LA PAGE CONNECTE
            } else {
                header('Location: /' . REPERTOIRE . '/Login_Erreur/' . $lang); //REDIRECTION VERS LA PAGE INSERCONTACT
            }
        } else {//MOT DE PASSE ERRONNE        
            header('Location: /' . REPERTOIRE . '/Erreur_Mot_de_passe/' . $lang . '/' . $pseudo . '');
        }
    } else {
        header('Location: /' . REPERTOIRE . '/index_Erreur/' . $lang); //VOUS N'ETES PAS ENCORE INSCRIT
    }
} else {
    header('Location: /' . REPERTOIRE . '/Session_Error/' . $lang);
    exit();
}
BD::deconnecter();
