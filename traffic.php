<?php
session_start();
include_once 'decide-lang.php';
include_once 'outils/toolBox.php';
include_once 'outils/constantes.php';
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
include 'html/header.html';
$typeUser = $manager->getSingle2("SELECT idtypeutilisateur_typeutilisateur FROM  loginpassword,utilisateur WHERE idlogin = idlogin_loginpassword and pseudo=?", $_SESSION['pseudo']);
?>
<div id="global">
    <?php include "html/entete.html"; ?>
    <div style="padding-top: 65px;">
        <?php include'outils/bandeaucentrale.php'; ?>
    </div>    
    <fieldset id='stat'><legend><?php echo TXT_TRAFFIC; ?></legend>
        <?php include './statistiques/projetTrafic.php'; ?>
    </fieldset>
    <?php include 'html/footer.html'; ?>
</body>
</html>