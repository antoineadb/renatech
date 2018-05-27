<?php

session_start();
include_once '../outils/toolBox.php';
showError($_SERVER['PHP_SELF']);;
include '../class/Manager.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include '../decide-lang.php';
include_once '../outils/constantes.php';
//récupération du nouveau mots de passe saisie
if (isset($_POST['motPasse'])) {
    $motpasse = sha1($_POST['motPasse']);
}
if (isset($_SESSION['pseudo'])) {
    $pseudo = $_SESSION['pseudo'];
}
if (isset($_POST['page_precedente']) && $_POST['page_precedente'] == 'changermotpasse.php') {
//vérification que le mot de passe est différent du précédent
$idlogin = $manager->getSinglebyArray("select idlogin  from loginpassword where pseudo = ?", array($pseudo,$motpasse));
if (!empty($idlogin)) {
    header('location: /' . REPERTOIRE . '/change_messagepassword/' . $lang);
    exit;
} else {
    $loginpassword = new LoginPassword($motpasse, $pseudo);
    $manager->updateloginpassword($loginpassword);
//mise à jour de la valeur motpasseenvoye table loginpassword
    $motpasseenvoye = new LoginMotpasseEnvoye('FALSE', $pseudo);
    $manager->updateMotpasseenvoye($motpasseenvoye);
    header('location: /' . REPERTOIRE . '/indexmessage/' . $lang . '');
}
}else{
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
BD::deconnecter();