<?php

session_start();

include_once '../class/Manager.php';
include_once '../outils/constantes.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
if (isset($_GET['page_precedente']) && $_GET['page_precedente'] == 'resultProjet.php') {
if (!empty($_POST['titreProjet'])) {
    $titreProjet = pg_escape_string($_POST['titreProjet']);
    $_SESSION['titreProjet'] = $titreProjet;
}
if (!empty($_POST['typeProjet'])) {
    $typeProjet = $_POST['typeProjet'];
    $_SESSION['typeProjet'] = $typeProjet;
}
if(isset($_GET['lang'])){
    $lang=$_GET['lang'];
}

$idprojet = $_GET['idprojet'];
$_SESSION['idprojet'] = $idprojet;
$concernephase1 = new ConcernePhase1($idprojet, ENCOURSANALYSE);
$manager->updateConcernePhase1($concernephase1, $idprojet);
//IL FAUT VERIFIER SI L'UTILISATEUR EST ACADEMIQUE OU INDUSTRIEL SI IL EST PERMANANT OU NON PERMANENT, SI IL A UN RESPONSABLE ET SI CE RESPONSABLE EST BIEN INSCRIT DANS L'APPLICATION
$pseudo = $_SESSION['pseudo'];
$arraytypeutilisateur = $manager->getList2("select idqualitedemandeuraca_qualitedemandeuraca,idqualitedemandeurindust_qualitedemandeurindust from utilisateur,loginpassword where idlogin_loginpassword = idlogin  "
        . "and pseudo=?", $pseudo);
$dateaffectation = date("m,d,Y");
if(!empty($arraytypeutilisateur[0]['idqualitedemandeuraca_qualitedemandeuraca'])){   //CAS ACADEMIQUE
    $typeutilisateur = $manager->getSingle2("SELECT idqualitedemandeuraca_qualitedemandeuraca FROM utilisateur,loginpassword WHERE idlogin_loginpassword = idlogin and pseudo=?", $pseudo);
    if($typeutilisateur==NONPERMANENT){//CAS NON PERMANENT
        $mailresponsable = $manager->getSingle2("SELECT mailresponsable FROM utilisateur,loginpassword WHERE idlogin_loginpassword = idlogin and pseudo=?",$pseudo);
        $arrayidresponsable = $manager->getList2("SELECT  idutilisateur FROM utilisateur,loginpassword WHERE  idlogin = idlogin_loginpassword and mail=?", $mailresponsable);
        $nbarrayidresponsable = count($arrayidresponsable);
        if($nbarrayidresponsable>0){
            for ($i = 0; $i < $nbarrayidresponsable; $i++) {
                $porteur = new UtilisateurPorteurProjet($arrayidresponsable[$i]['idutilisateur'], $idprojet, $dateaffectation);
                $manager->addUtilisateurPorteurProjet($porteur);
            }
        }
    }
}elseif(!empty($arraytypeutilisateur[0]['idqualitedemandeurindust_qualitedemandeurindust'])){//CAS INDUSTRIEL
    $typeutilisateur = $manager->getSingle2("SELECT  idqualitedemandeurindust_qualitedemandeurindust FROM utilisateur,loginpassword WHERE idlogin_loginpassword = idlogin and pseudo=?", $pseudo);
    if($typeutilisateur==NONPERMANENTINDUST){
        $mailresponsable = $manager->getSingle2("SELECT mailresponsable FROM utilisateur,loginpassword WHERE idlogin_loginpassword = idlogin and pseudo=?",$pseudo);
        $arrayidresponsable = $manager->getList2("SELECT  idutilisateur FROM utilisateur,loginpassword WHERE  idlogin = idlogin_loginpassword and mail=?", $mailresponsable);
        $nbarrayidresponsable = count($arrayidresponsable);
        if($nbarrayidresponsable>0){
            for ($i = 0; $i < $nbarrayidresponsable; $i++) {
                $porteur = new UtilisateurPorteurProjet($arrayidresponsable[$i]['idutilisateur'], $idprojet, $dateaffectation);
                $manager->addUtilisateurPorteurProjet($porteur);
            }
        }
    }
}



//création de la variable de session pour bloquer l'éventuel double soumission du  formulaire
$_SESSION['soumission'] = 'soumis';
// envoie de l'email
include '../EmailProjet.php';
//----------------------------------------
BD::deconnecter();
} else {
    include_once '../decide-lang.php';
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}