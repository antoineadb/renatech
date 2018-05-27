<?php
session_start();
include_once 'decide-lang.php';
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
     <div style='width:100px;margin-left:10px;'><a class="infoBulle" href="<?php echo '/' . REPERTOIRE . '/exportRefusJson.php?lang=' . $lang ?>">&nbsp;
                    <img    src='<?php echo "/" . REPERTOIRE; ?>/styles/img/export.png' ><span style="width: 260px"><?php echo TXT_EXPORTPROJETREFUSE; ?></span></a>
            </div>
    <div data-dojo-type="dijit/layout/TabContainer" style="margin-top:6px;width: 1050px;font-size: 1.2em;" doLayout="false" selected="true" >
         <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo TXT_PROJETREFUSE; ?>" style="width: auto; height:500px;" >
            <?php require_once 'html/vueProjetRefuse.html'; ?>
        </div>
           
        <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo TXT_PRJTRSOTHCENT ?>" style="width: auto; height:500px;" >
            <?php  require_once 'html/vueTousProjetRefuse.html'; ?>
        </div>      
       
    </div>
    <?php include 'html/footer.html'; ?>
</div>
</body>
</html>