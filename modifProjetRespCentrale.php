<?php
session_start();
include_once 'outils/toolBox.php';
include_once 'outils/constantes.php';
include_once 'class/Manager.php';
include_once 'decide-lang.php';

if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
include 'html/header.html';
if (!isset($_SESSION['nom'])) {
    $_SESSION['nom'] = $_SESSION['nomConnect'];
}
?>
<div id="global">
    <?php
    include 'html/entete.html';
    include 'html/modifProjetRespCentrale.html';
    $_SESSION['admin'] = '';
    ?>
    <div id="Contenuindexchoix">
        <?php include 'html/footer.html'; ?>
    </div>
</body>
</html>