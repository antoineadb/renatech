<?php

session_start();
if (!empty($_GET['action']) && ($_GET['action'] == 'logout')) {
    $_SESSION = array();
    session_destroy();
    $_SESSION['pseudo'] = '';
}
include_once 'outils/constantes.php';
include_once 'decide-lang.php';
if (!empty($_SESSION['pseudo'])) {
    header('Location: /' . REPERTOIRE . '/Session_Error/' . $lang);
}
include_once 'outils/toolBox.php';
if (internetExplorer() == 'incompatible') {
    header('Location: /' . REPERTOIRE . '/VersionIE_Error/' . $lang);
}
include 'html/header.html';
?><div id="global">
<?php
include 'html/entete.html';
include_once 'include/connexion.html';
?><!--<fieldset style="border: 1px solid darkblue;border-radius: 5px;color: darkblue;font-family: verdana;font-size: 1.1em;margin-bottom: -15px;margin-top: 13px;padding: 12px;text-align: center;width: 1020px;">
    <?php /*if ($lang=='fr'){?>
    <b>L'application Renatech sera indisponible pour maintenance à partir du 20/01/2015 à 20h00 et jusqu'au 21 inclus</b>
    <?php }else{ ?>
    <b>The Renatech application will be unavailable for maintenance from 20/01/2015 at 8:00 pm and until 21 included</b>
    <?php } */?>
    </fieldset>-->
    <div id="messageupdatemotpasse" style="display: none;color: red;margin-top: 50px;"><?php echo TXT_MISEAJOURMOTPASSE; ?></div>
    <br>
    <?php
    if (!empty($_SESSION['admin'])) {
        unset($_SESSION['admin']);
    }
    include 'html/identification.html';
    include 'html/footer.html';
    ?>
</div>     
</body>
</html>