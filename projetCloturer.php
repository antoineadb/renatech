<?php
session_start();
include_once 'outils/constantes.php';
include_once 'outils/toolBox.php';
include_once 'decide-lang.php';
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
include 'html/header.html';
?>
<div id="global">
    <?php
    include 'html/entete.html';
    ?>
    <div data-dojo-type="dijit/layout/TabContainer" style="width: 1050px;" doLayout="false">
        <div data-dojo-type="dijit/layout/ContentPane" title="phase 1" style="width: 570px; height: 930px;" >
            <?php include 'html/vueModifProjet.html'; ?>
        </div>
        <div data-dojo-type="dijit/layout/ContentPane" title="Phase 2" style="width: 570px; height: auto;" selected="true" >
            <?php include 'html/phase2.html'; ?>
        </div>
    </div>
</div><div id="Contenuindexchoix">
    <?php include 'html/footer.html'; ?>
</div>

</body>
</html>