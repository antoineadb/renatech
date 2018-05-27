<?php

session_start();
include_once 'class/Cache.php';
include_once 'outils/toolBox.php';
include_once 'class/Manager.php';
include_once 'outils/constantes.php';
include_once 'decide-lang.php';
define('ROOT',  dirname(__FILE__));
$Cache = new Cache(ROOT.'/cache', 60);
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
if (!isset($_SESSION['nom'])) {
    $_SESSION['nom'] = $_SESSION['nomConnect'];
}
include 'html/header.html';
?>
<div id="global">
    <?php include "html/entete.html"; ?>
    <div id="Contenuindexchoix">
        <div style="padding-top: 75px;">
    <?php $Cache->inc(ROOT.'/outils/bandeaucentrale.php'); //RECUPERATION DU BANDEAU DEFILANT DANS LE CACHE CACHE?>
</div>
<br>
    <?php
        include 'html/modifProjetRespCentralevalide.html';
    $_SESSION['admin'] = '';
        include "html/footer.html";
        ?>
    </div>
</div>
</body>
</html>