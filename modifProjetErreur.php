<?php
include_once 'outils/toolBox.php';
include_once 'class/Manager.php';
include_once 'decide-lang.php';
include_once 'outils/constantes.php';
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
include 'html/header.html';
?>
<div id="global">
    <?php include 'html/entete.html'; ?>
    <div id="contenu">
        <?php
        include 'html/modifProjetErreur.html';
        ?>
    </div>
    <?php include 'html/footer.html'; ?>
</div>
</body>
</html>

