<?php

$ancienNom = $manager->getSingle2("select nom from utilisateur where idutilisateur = ?", $idutilisateur);
if (isset($_GET['nomuser']) && $_GET['nomuser'] != $ancienNom) {
    $nom = stripslashes((Securite::bdd($_GET['nomuser'])));
    $nomUser = new NomUtilisateur($nom);
    $manager->updateNomMoncompte($nomUser, $idutilisateur);
}
$ancienPrenom = stripslashes($manager->getSingle2("select prenom from utilisateur where idutilisateur = ?", $idutilisateur));
if (isset($_GET['prenomuser']) && $_GET['prenomuser'] != $ancienPrenom) {
    $prenom = stripslashes(Securite::bdd($_GET['prenomuser']));
    $prenomUser = new PrenomUtilisateur($prenom);
    $manager->updatePrenomMoncompte($prenomUser, $idutilisateur);
}
$ancienPays = $manager->getSingle2("select idpays_pays from utilisateur where idutilisateur = ?", $idutilisateur);

if (isset($_GET['pays']) && $_GET['pays'] != $ancienPays) {
    $paysUser = new PaysUser($_GET['pays']);
    $manager->updatePaysMoncompte($paysUser, $idutilisateur);
}
$ancienneAdresse = stripslashes($manager->getSingle2("select adresse from utilisateur where idutilisateur = ?", $idutilisateur));
if (isset($_GET['adresseuser']) && $_GET['adresseuser'] != $ancienneAdresse) {
    $adresse =stripslashes(Securite::bdd($_GET['adresseuser']));
    $adresseUser = new AdresseUser($adresse);
    $manager->updateAdresseMoncompte($adresseUser, $idutilisateur);
}
$ancienCp = $manager->getSingle2("select codepostal from utilisateur where idutilisateur = ?", $idutilisateur);
if (isset($_GET['codepostal']) && !empty($_GET['codepostal'])) {
    if ($_GET['codepostal'] != $ancienCp || strlen($_GET['codepostal']) != strlen($ancienCp)) {
        $cp = stripslashes($_GET['codepostal']);
        $cpUser = new CodepostalUser($cp);
        $manager->updateCodepostalMoncompte($cpUser, $idutilisateur);
    }
}

$ancienneVille = stripslashes($manager->getSingle2("select ville from utilisateur where idutilisateur = ?", $idutilisateur));
if (isset($_GET['ville']) && $_GET['ville'] != $ancienneVille) {
    $ville = stripslashes(Securite::bdd(($_GET['ville'])));
    $villeUser = new VilleUser($ville);
    $manager->updateVilleMoncompte($villeUser, $idutilisateur);
}

$ancienTel = $manager->getSingle2("select telephone  from utilisateur where idutilisateur = ?", $idutilisateur);
if (isset($_GET['teluser'])&&!empty($_GET['teluser'])){    
    if($_GET['teluser'] != $ancienTel || strlen($_GET['teluser']) != strlen($ancienTel) ) {    
        $teluser = new TelephoneUser(stripslashes($_GET['teluser']));
        $manager->updatetelephoneMoncompte($teluser, $idutilisateur);
    }
}
$ancienFax = $manager->getSingle2("select fax  from utilisateur where idutilisateur = ?", $idutilisateur);
if (isset($_GET['faxuser'])&&!empty($_GET['faxuser'])){    
    if($_GET['faxuser'] != $ancienTel || strlen($_GET['faxuser']) != strlen($ancienFax) ) {    
        $fax = $_GET['faxuser'];
        $faxUser = new FaxUser(stripslashes(Securite::BDD($fax)));
        $manager->updateFaxMoncompte($faxUser, $idutilisateur);
    }
} else {
    $fax = '';
}
$ancienPseudo = $manager->getSingle2("SELECT pseudo FROM loginpassword, utilisateur WHERE  loginpassword.idlogin = utilisateur.idlogin_loginpassword and idutilisateur=?", $idutilisateur);

if (isset($_GET['pseudo']) && $_GET['pseudo'] != $ancienPseudo) {
    $pseudo = $_GET['pseudo'];
    //VERIFICATION QUE LE PSEUDO N'EXISTE PAS DEJA POUR CELA ON VERIFIE QUE LE PSEUDO AVEC TOUS LES AUTRES PARAMETRE  N'EXISTE PAS
    $idnewlogin = $manager->getSingle2("select idlogin from loginpassword where pseudo=?", $pseudo);

    if (!empty($idnewlogin)) {// LE PSEUDO EXISTE
        header('location: /' . REPERTOIRE . '/updatemoncompteErr/' . $lang . '/ok');
        exit();
    } else {// LE PSEUDO N'EXISTE PAS MISE A JOUR
        $loginuser = new LoginUtilisateur($pseudo);
        $idlogin = $manager->getSingle2("SELECT idlogin FROM loginpassword, utilisateur WHERE  loginpassword.idlogin = utilisateur.idlogin_loginpassword and idutilisateur=?", $idutilisateur);
        $manager->updateLoginMoncompte($loginuser, $idlogin);
    }
}
$ancienMail = $manager->getSingle2("SELECT mail FROM loginpassword, utilisateur WHERE  loginpassword.idlogin = utilisateur.idlogin_loginpassword and idutilisateur=?", $idutilisateur);
if (isset($_GET['mail']) && $_GET['mail'] != $ancienMail) {    
    $mailuser = new MailUtilisateur($_GET['mail']);
    $idlogin = $manager->getSingle2("SELECT idlogin FROM loginpassword, utilisateur WHERE  loginpassword.idlogin = utilisateur.idlogin_loginpassword and idutilisateur=?", $idutilisateur);
    $manager->updateMailMoncompte($mailuser, $idlogin);
}
