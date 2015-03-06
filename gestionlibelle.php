<?php
session_start();
include_once 'outils/constantes.php';
include_once 'outils/toolBox.php';

if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/'.$lang);
}
include 'outils/parser.php';
include_once 'class/Manager.php';
include 'class/Securite.php';
include 'html/header.html';
?>

<?php
$ongletsite = false;
$ongletconnexion = false;
$ongletmail = false;
$ongletemailcontact = false;
$ongletemailprojetphase1 = false;
$ongletmsgupdateemailprojetphase1=false;
$ongletmsgupdateemailprojetphase2=false;
if (isset($_GET['msgupdatesiteweb'])) {
    $ongletsite = true;
} elseif (isset($_GET['msgupdateconnexion'])) {
    $ongletconnexion = true;
} elseif (isset($_GET['msgupdatemail'])) {
    $ongletmail = true;
} elseif (isset($_GET['msgupdateemailcontact'])) {
    $ongletemailcontact = true;
} elseif (isset($_GET['msgupdateemailprojetphase1'])) {
    $ongletemailprojetphase1 = true;
}elseif(isset($_GET['msgupdateemailprojetphase2'])){
    $ongletmsgupdateemailprojetphase2=true;
}
?>
<div id="global">
    <?php include 'html/entete.html'; ?>
       <div style="margin-top: 70px;">
        <?php include_once 'outils/bandeaucentrale.php'; ?>
    </div>
    <div data-dojo-type="dijit/layout/TabContainer" style="margin-top:50px;width: 1050px;font-size: 1.2em;" doLayout="false">
        <?php if ($ongletsite === true) { ?>
            <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo TXT_PAGEHOME; ?>" >
                <?php include 'html/gestionlibelleHome.html'; ?>
            </div>
            <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo TXT_PAGEACCUEIL; ?>" >
                <?php include 'html/gestionlibelleaccueil.html'; ?>
            </div>
            <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo TXT_ADRESSESITE; ?>" style="height:auto" data-dojo-props="selected:true">
                <table><tr><td><?php include 'html/gestionsiteweb.html'; ?></td></tr></table>
            </div>										
            <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo TXT_EMAILCONTACT; ?>" >
                <table><tr><td><?php include 'html/gestionemailcontact.html'; ?></td></tr></table>
            </div>
            <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo TXT_EMAILPHASE1; ?>" >
                <table><tr><td><?php include 'html/gestionemailprojetphase1.html'; ?></td></tr></table>
            </div>
            <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo TXT_EMAILPHASE2; ?>" >
                <table><tr><td><?php include 'html/gestionemailprojetphase2.html'; ?></td></tr></table>
            </div>           
        <?php } elseif ($ongletconnexion == true) { ?>
            <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo TXT_PAGEHOME; ?>" >
                <?php include 'html/gestionlibelleHome.html'; ?>
            </div>
            <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo TXT_PAGEACCUEIL; ?>" data-dojo-props="selected:true" >
                <?php include 'html/gestionlibelleaccueil.html'; ?>
            </div>										
            <div data-dojo-type="dijit/layout/ContentPane"  title="<?php echo TXT_EMAILCONTACT; ?>" >
                <table><tr><td><?php include 'html/gestionemailcontact.html'; ?></td></tr></table>
            </div>
            <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo TXT_EMAILPHASE1; ?>" >
                <table><tr><td><?php include 'html/gestionemailprojetphase1.html'; ?></td></tr></table>
            </div>
            <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo TXT_EMAILPHASE2; ?>" >
                <table><tr><td><?php include 'html/gestionemailprojetphase2.html'; ?></td></tr></table>
            </div>           
        <?php } elseif ($ongletemailcontact == true) { ?>
            <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo TXT_PAGEHOME; ?>" >
                <?php include 'html/gestionlibelleHome.html'; ?>
            </div>
            <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo TXT_PAGEACCUEIL; ?>" >
                <?php include 'html/gestionlibelleaccueil.html'; ?>
            </div>
            <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo TXT_ADRESSESITE; ?>" style="height:auto">
                <table><tr><td><?php include 'html/gestionsiteweb.html'; ?></td></tr></table>
            </div>
            <div data-dojo-type="dijit/layout/ContentPane"  title="<?php echo TXT_EMAILCONTACT; ?>"  data-dojo-props="selected:true">
                <table><tr><td><?php include 'html/gestionemailcontact.html'; ?></td></tr></table>
            </div>
            <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo TXT_EMAILPHASE1; ?>" >
                <table><tr><td><?php include 'html/gestionemailprojetphase1.html'; ?></td></tr></table>
            </div>
            <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo TXT_EMAILPHASE2; ?>" >
                <table><tr><td><?php include 'html/gestionemailprojetphase2.html'; ?></td></tr></table>
            </div>            
        <?php } elseif ($ongletemailprojetphase1 == true) { ?>
            <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo TXT_PAGEHOME; ?>" >
                <?php include 'html/gestionlibelleHome.html'; ?>
            </div>
            <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo TXT_PAGEACCUEIL; ?>" >
                <?php include 'html/gestionlibelleaccueil.html'; ?>
            </div>
            <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo TXT_ADRESSESITE; ?>" style="height:auto">
                <table><tr><td><?php include 'html/gestionsiteweb.html'; ?></td></tr></table>
            </div>
            <div data-dojo-type="dijit/layout/ContentPane"  title="<?php echo TXT_EMAILCONTACT; ?>" >
                <table><tr><td><?php include 'html/gestionemailcontact.html'; ?></td></tr></table>
            </div>
        <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo TXT_EMAILPHASE1; ?>"  data-dojo-props="selected:true">            
                <table><tr><td><?php include 'html/gestionemailprojetphase1.html'; ?></td></tr></table>
            </div>
            <div data-dojo-type="dijit/layout/ContentPane"title="<?php echo TXT_EMAILPHASE2; ?>" >
                <table><tr><td><?php include 'html/gestionemailprojetphase2.html'; ?></td></tr></table>
            </div>            
        <?php } elseif ($ongletmsgupdateemailprojetphase2 == true) { ?>
            <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo TXT_PAGEHOME; ?>" >
                <?php include 'html/gestionlibelleHome.html'; ?>
            </div>
            <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo TXT_PAGEACCUEIL; ?>" >
                <?php include 'html/gestionlibelleaccueil.html'; ?>
            </div>
            <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo TXT_ADRESSESITE; ?>" style="height:auto">
                <table><tr><td><?php include 'html/gestionsiteweb.html'; ?></td></tr></table>
            </div>
            <div data-dojo-type="dijit/layout/ContentPane"  title="<?php echo TXT_EMAILCONTACT; ?>" >
                <table><tr><td><?php include 'html/gestionemailcontact.html'; ?></td></tr></table>
            </div>
            <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo TXT_EMAILPHASE1; ?>" >
                <table><tr><td><?php include 'html/gestionemailprojetphase1.html'; ?></td></tr></table>
            </div>
            <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo TXT_EMAILPHASE2; ?>"  data-dojo-props="selected:true">
                <table><tr><td><?php include 'html/gestionemailprojetphase2.html'; ?></td></tr></table>
            </div>            
        <?php } else { ?>
            <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo TXT_PAGEHOME; ?>" data-dojo-props="selected:true">
                <?php include 'html/gestionlibelleHome.html'; ?>
            </div>
            <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo TXT_PAGEACCUEIL; ?>" >
                <?php include 'html/gestionlibelleaccueil.html'; ?>
            </div>
            <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo TXT_ADRESSESITE; ?>" style="height:auto">
                <table><tr><td><?php include 'html/gestionsiteweb.html'; ?></td></tr></table>
            </div>										
            <div data-dojo-type="dijit/layout/ContentPane"  title="<?php echo TXT_EMAILCONTACT; ?>" >
                <table><tr><td><?php include 'html/gestionemailcontact.html'; ?></td></tr></table>
            </div>
            <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo TXT_EMAILPHASE1; ?>" >
                <table><tr><td><?php include 'html/gestionemailprojetphase1.html'; ?></td></tr></table>
            </div>
            <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo TXT_EMAILPHASE2; ?>" >
                <table><tr><td><?php include 'html/gestionemailprojetphase2.html'; ?></td></tr></table>
            </div>           
        <?php } ?>
    </div><div style="margin-left: 0px"><?php include 'html/footer.html'; ?></div>
</div>
</body>
</html>