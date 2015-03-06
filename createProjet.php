<?php
session_start();
include_once 'outils/toolBox.php';
include_once 'class/Manager.php';
include_once 'class/Cache.php';
include_once 'outils/constantes.php';
include 'decide-lang.php';
define('ROOT',  dirname(__FILE__));
$Cache = new Cache(ROOT.'/cache', 60);
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
if (!isset($_GET['msgerr'])) {
    unset($_SESSION['contextValeur']);
    unset($_SESSION['descriptifValeur']);
    unset($_SESSION['libellecentrale']);
    unset($_SESSION['idcentrale']);
    unset($_SESSION['titreProjet']);
    unset($_SESSION['acronyme']);
    unset($_SESSION['confid']);
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