<?php
session_start();
include('decide-lang.php');
include_once 'outils/constantes.php';
include_once 'outils/toolBox.php';
include_once 'class/Manager.php';
include_once 'class/Cache.php';
define('ROOT',  dirname(__FILE__));
$Cache = new Cache(ROOT.'/cache', 60);
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
    if (!isset($_SESSION['nom'])) {
        $_SESSION['nom'] = $_SESSION['nomConnect'];
    }
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
include 'html/header.html';
?>
<div id="global">
    <?php include 'html/entete.html'; ?>
    <div style="padding-top: 75px;">
        <?php 
         if(internetExplorer()=='false'){
            $Cache->inc(ROOT.'/outils/bandeaucentrale.php'); //RECUPERATION DU BANDEAU DEFILANT DANS LE CACHE CACHE            
        }else{
            include 'outils/bandeaucentrale.php'; //RECUPERATION DU BANDEAU DEFILANT DANS LE CAS D'INTERNET EXPLORER
        }
       ?>
    </div>
    <div data-dojo-type="dijit/layout/TabContainer" style="margin-top:25px;width: 1050px;font-size: 1.2em;" doLayout="false" selected="true" >
         <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo TXT_SUIVIPROJET; ?>" style="width: auto; height:500px;" >
            <?php require_once 'html/vueSuiviProjet.html'; ?>
        </div>
        <?php 
            $vueCentrale = $manager->getSingle2("select vueprojetcentrale from utilisateur,loginpassword where idlogin_loginpassword=idlogin and pseudo=?", $_SESSION['pseudo']);
            if($vueCentrale==TRUE){
        ?>
        <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo TXT_VUESUIVIPROJETCENTRALE ?>" style="width: auto; height:500px;" >
            <?php require_once 'html/vueSuiviProjetCentraleUser.html'; ?>
        </div>
            <?php }?>
        <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo TXT_CONSULTPROJET ?>" style="width: auto; height:500px;" >
            <?php require_once 'html/vueSuiviProjetEnCours.html'; ?>
        </div>
        <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo TXT_PROJETSENATTENTE ?>" style="width: auto; height:500px;" >
            <?php require_once 'html/vueSuiviProjetEnAttente.html'; ?>
        </div>
        <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo TXT_PROJETACCEPTE ?>" style="width: auto; height:500px;" >
            <?php require_once 'html/vueSuiviProjetAccepte.html'; ?>
        </div>
        <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo TXT_PROJETENCOURSREALISATION ?>" style="width: auto; height:500px;" >
            <?php include 'html/vueProjetencoursrealisation.html'; ?>
        </div>
        <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo TXT_PROJETFINI ?>" style="width: auto; height:500px;" >
            <?php include 'html/vueSuiviProjetFini.html'; ?>
        </div>
        <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo TXT_PROJETREFUSE ?>" style="width: auto; height:500px;" >
            <?php require_once 'html/vueSuiviProjetRefuse.html'; ?>
        </div>
        <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo TXT_PROJETCLOTURE ?>" style="width: auto; height:500px;" >
            <?php include 'html/vueSuiviProjetCloture.html'; ?>
        </div>
       
    </div>
    <?php include 'html/footer.html'; ?>
</div>
</body>
</html>