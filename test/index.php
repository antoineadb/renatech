<?php
session_start();
if (!empty($_GET['action']) && ($_GET['action'] == 'logout')) {
    $_SESSION = array();
    session_destroy();
    $_SESSION['pseudo'] = '';
}
include_once '../outils/constantes.php';
include_once '../decide-lang.php';
if (!empty($_SESSION['pseudo'])) {
    header('Location: /' . REPERTOIRE . '/Session_Error/' . $lang);
}
include '../outils/toolBox.php';
if(internetExplorer()=='incompatible'){
    header('Location: /' . REPERTOIRE . '/VersionIE_Error/' . $lang);
}
include '../html/header.html';
?><div id="global">
<?php
include 'html/entete.html';
include_once 'include/connexion.html';
?>
    <div id="messageupdatemotpasse" style="display: none;color: red;margin-top: 50px;"><?php echo TXT_MISEAJOURMOTPASSE; ?></div>
    <br>
    <?php
    if (!empty($_SESSION['admin'])) {
        unset($_SESSION['admin']);
    }
    include 'identification.html';
    include '../html/footer.html';
    ?>
</div>
</body>
</html>