<?php
session_start();
include('decide-lang.php');
include 'html/header.html';
if (!isset($_SESSION['nom'])) {
    $_SESSION['nom'] = $_SESSION['nomConnect'];
}
include 'outils/toolBox.php';
include_once 'class/Manager.php';
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    echo '<script>window.location.replace("erreurlogin.php")</script>';
}
?>
<div id="global">
    <?php include 'html/entete.html'; ?>
    <div style="width: 752px; height: 300px">
        <div data-dojo-type="dijit/layout/AccordionContainer"   style="height: 350px;">
            <div data-dojo-type="dijit/layout/ContentPane"  title="<?php echo TXT_PROJETSCENTRALES; ?>">
                <?php include 'html/vueSuiviProjetTousCentrales.html'; ?>

            </div>
        </div>
    </div>
    <br><br><br><br><?php include 'html/footer.html'; ?>
</div>
</body>
</html>