<?php

session_start();
include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);;
include '../decide-lang.php';
include '../class/Manager.php';
include_once '../class/Securite.php';
include_once '../outils/constantes.php';

if (isset($_POST['page_precedente']) && $_POST['page_precedente'] == 'moncompte.html') {
    $db = BD::connecter();
    $manager = new Manager($db);

    if (isset($_SESSION['pseudo'])) {
        $iduser = $manager->getSingle2("SELECT idutilisateur FROM utilisateur,loginpassword WHERE idlogin = idlogin_loginpassword and pseudo =?", $_SESSION['pseudo']);
        $idlogin = $manager->getSingle2("select idlogin_loginpassword from utilisateur where idutilisateur =? ", $iduser);
    }
//IL FAUT CONTROLER QUE L'EMAIL A BIEN CHANGE
    $ancienEmail = $manager->getsingle2("SELECT  mail FROM utilisateur,loginpassword WHERE idlogin = idlogin_loginpassword and idutilisateur =? ", $iduser);

    if (isset($_POST['email']) && $_POST['email'] != $ancienEmail) { //NOUVEL EMAIL
        $mail = $_POST['email'];
        $mailUser = new MailUtilisateur($mail);
        $manager->updateMailMoncompte($mailUser, $idlogin);
        $_SESSION['email'] = $mail;
    }
//IDEM POUR LES AUTRES PARAMETRE
    $ancienNom = $manager->getSingle2("select nom from utilisateur where idutilisateur = ?", $iduser);
    if (isset($_POST['nom']) && $_POST['nom'] != $ancienNom) {
        $nom = stripslashes((Securite::bdd($_POST['nom'])));
        $nomUser = new NomUtilisateur($nom);
        $manager->updateNomMoncompte($nomUser, $iduser);
        $_SESSION['nom'] = $nom;
    }
    $ancienPrenom = stripslashes($manager->getSingle2("select prenom from utilisateur where idutilisateur = ?", $iduser));
    if (isset($_POST['prenom']) && $_POST['prenom'] != $ancienPrenom) {
        $prenom = stripslashes(Securite::bdd($_POST['prenom']));
        $prenomUser = new PrenomUtilisateur($prenom);
        $manager->updatePrenomMoncompte($prenomUser, $iduser);
        $_SESSION['prenom'] = $prenom;
    }
    $ancienneAdresse = stripslashes($manager->getSingle2("select adresse from utilisateur where idutilisateur = ?", $iduser));
    if (isset($_POST['adresse']) && $_POST['adresse'] != $ancienneAdresse) {
        $adresse = stripslashes(Securite::bdd($_POST['adresse']));
        $adresseUser = new AdresseUser($adresse);
        $manager->updateAdresseMoncompte($adresseUser, $iduser);
        $_SESSION['adresse'] = $adresse;
    }
    $ancienCp = $manager->getSingle2("select codepostal from utilisateur where idutilisateur = ?", $iduser);
    if (isset($_POST['cp']) && $_POST['cp'] != $ancienCp) {
        $cp = Securite::bdd($_POST['cp']);
        $cpUser = new CodepostalUser($cp);
        $manager->updateCodepostalMoncompte($cpUser, $iduser);
        $_SESSION['cp'] = $cp;
    }
    $ancienneVille = stripslashes($manager->getSingle2("select ville from utilisateur where idutilisateur = ?", $iduser));
    if (isset($_POST['ville']) && $_POST['ville'] != $ancienneVille) {
        $ville = stripslashes(Securite::bdd(($_POST['ville'])));
        $villeUser = new VilleUser($ville);
        $manager->updateVilleMoncompte($villeUser, $iduser);
        $_SESSION['ville'] = $ville;
    }
    $ancienPays = $manager->getSingle2("select idpays_pays from utilisateur where idutilisateur = ?", $iduser);
    $idpays = $manager->getSingle2("SELECT idpays FROM pays where nompays=?",$_POST['pays']);
    if (isset($_POST['pays']) && $idpays != $ancienPays) {
        $pays = $_POST['pays'];
        $idpays = $manager->getSingle2("SELECT idpays FROM pays where nompays=?", $pays);
        $paysUser = new PaysUser($idpays);
        $manager->updatePaysMoncompte($paysUser, $iduser);
        $_SESSION['pays'] = $pays;
    }
    $ancienTel = $manager->getSingle2("select telephone  from utilisateur where idutilisateur = ?", $iduser);
    if (!empty($_POST['tel']) && $_POST['tel'] != $ancienTel) {
        $telUser = new TelephoneUser($_POST['tel']);
        $manager->updatetelephoneMoncompte($telUser, $iduser);
        $_SESSION['tel'] = $_POST['tel'];
    }
    $ancienFax = $manager->getSingle2("select fax  from utilisateur where idutilisateur = ?", $iduser);
    if (isset($_POST['fax']) && $_POST['fax'] != $ancienFax) {
        $fax = $_POST['fax'];
        $faxUser = new FaxUser($fax);
        $manager->updateFaxMoncompte($faxUser, $iduser);
        $_SESSION['fax'] = $fax;
    } else {
        $fax = '';
    }
    $ancienPseudo = $manager->getSingle2("SELECT pseudo FROM loginpassword, utilisateur WHERE
  loginpassword.idlogin = utilisateur.idlogin_loginpassword and idutilisateur=?", $iduser);

    if (isset($_POST['pseudo']) && $_POST['pseudo'] != $ancienPseudo) {
        $pseudo = $_POST['pseudo'];
        //VERIFICATION QUE LE PSEUDO N'EXISTE PAS DEJA POUR CELA ON VERIFIE QUE LE PSEUDO AVEC TOUS LES AUTRES PARAMETRE  N'EXISTE PAS
        $idnewlogin = $manager->getSingle2("select idlogin from loginpassword where pseudo=?", $pseudo);

        if (!empty($idnewlogin)) {// LE PSEUDO EXISTE
            header('location: /'.REPERTOIRE.'/updatemoncompteErr/' . $lang . '/ok');
            exit();
        } else {// LE PSEUDO N'EXISTE PAS MISE A JOUR
            $loginuser = new LoginUtilisateur($pseudo);
            $manager->updateLoginMoncompte($loginuser, $idlogin);
        }
        $_SESSION['pseudo'] = $pseudo;
    }

    $ancienNomresponsable = $manager->getSingle2("select nomresponsable  from utilisateur where idutilisateur = ?", $iduser);
    if (isset($_POST['nomresponsable']) && $_POST['nomresponsable'] != $ancienNomresponsable) {
        $nomresponsable = $_POST['nomresponsable'];
        $nomResponsable = new UtilisateurNomresponsable($iduser, $nomresponsable);
        $manager->updateUtilisateurNomresponsable($nomResponsable, $iduser);
        $_SESSION['nomresponsable'] = $nomresponsable;
    } else {
        $nomresponsable = '';
    }

    $ancienMailresponsable = $manager->getSingle2("select mailresponsable  from utilisateur where idutilisateur = ?", $iduser);
    if (isset($_POST['mailresponsable']) && $_POST['mailresponsable'] != $ancienMailresponsable) {
        $mailresponsable = $_POST['mailresponsable'];
        $mailResponsable = new UtilisateurMailresponsable($iduser, $mailresponsable);
        $manager->updateUtilisateurMailResponsable($mailResponsable, $iduser);
        $_SESSION['mailresponsable'] = $mailresponsable;
    } else {
        $mailresponsable = '';
    }
    
    $ancienNomlabo = $manager->getSingle2("select entrepriselaboratoire  from utilisateur where idutilisateur = ?", $iduser);
    if (isset($_POST['entrepriselaboratoire']) && $_POST['entrepriselaboratoire'] != $ancienNomlabo) {
        $NomLabo = new UtilisateurNomlabo($iduser, $_POST['entrepriselaboratoire']);
        $manager->updateUtilisateurNomlabo($NomLabo, $iduser);
        $_SESSION['Nomlabo'] = $_POST['entrepriselaboratoire'];
    }     

    header('location:/'.REPERTOIRE.'/updateMonCompte/' . $lang .'/ok');
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);

    exit();
}
BD::deconnecter();