<?php
session_start();
include_once 'outils/toolBox.php';
include_once 'class/Manager.php';
include_once 'class/Cache.php';
include_once 'outils/constantes.php';
include 'decide-lang.php';
$db = BD::connecter();
$manager = new Manager($db);

define('ROOT', dirname(__FILE__));
$Cache = new Cache(ROOT . '/cache', 60);
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
$arrayDefaultValues = $manager->getList2("select nom,mail from loginpassword,utilisateur where idlogin_loginpassword=idlogin and pseudo=?", $_SESSION['pseudo']);
if (!empty($arrayDefaultValues)) {
    $defaulNom = $arrayDefaultValues[0]['nom'];
    $defaultMail = $arrayDefaultValues[0]['mail'];
}

if (!isset($_GET['err'])) {
    $_SESSION['descriptif'] = '';
    $_SESSION['contexte'] = '';
    $_SESSION['objet'] = '';
    $_SESSION['message'] = '';
}
include 'html/header.html';
?>
<div id="global">
    <?php
    $_SESSION['admin'] = '';
    include 'html/entete.html';
    include 'html/createProjet.html';
    ?>
    <div id="Contenuindexchoix">
        <?php include 'html/footer.html'; ?>
    </div>
</div>
</body>
</html>