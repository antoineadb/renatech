<?php
session_start();

include('decide-lang.php');
include_once 'class/Cache.php';
include_once 'outils/constantes.php';
include_once 'outils/toolBox.php';
include_once 'class/Manager.php';
define('ROOT', dirname(__FILE__));
$Cache = new Cache(ROOT . '/cache', 60);
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
    if (!isset($_SESSION['nom'])) {
        $_SESSION['nom'] = $_SESSION['nomConnect'];
    }
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
include 'html/header.html';
$nbprojet = $_SESSION['nbprojet'];
$nbprojetEncoursRealisation = $_SESSION['nbprojetencoursrealisation'];
$nbprojetAccepte = $_SESSION['nbprojetaccepte'];
$nbprojetAttente = $_SESSION['nbprojetattente'];
$nbProjetRefusee = $_SESSION['nbProjetRefusee'];
$nbProjetFini = $_SESSION['nbFini'];
$nbProjetCloturer = $_SESSION['nbprojetCloturer'];
$nbProjetSoustraitance = $_SESSION['nbProjetSoustraitance'];
$nbRapportProjet = $_SESSION['nbProjetRapport'];

?>
<div id="global" >
    <?php include 'html/entete.html'; ?>        
    <div style="padding-top: 75px;">        
        <?php
        if (internetExplorer() == 'false') {
            $Cache->inc(ROOT . '/outils/bandeaucentrale.php'); //RECUPERATION DU BANDEAU DEFILANT DANS LE CACHE CACHE
        } else {
            include 'outils/bandeaucentrale.php'; //RECUPERATION DU BANDEAU DEFILANT DANS LE CAS D'INTERNET EXPLORER
        }?>
        <?php if(isset($_GET['err'])&&!empty($_GET['err'])){?><div style="padding-top: 15px;margin-bottom: -33px;text-align: center;font-size: 1.1em;color: #007A99; font-weight: bold;">
            <?php echo "Ce projet ne peux pas être dupliqué, il n'est pas \"En cours d'expertise\",\"En cours de réalisation\" ou \"Fini\" ";}?></div>
        <form action="#" method="POST" id="vueprojetcentrale">
            <?php if(IDTYPEUSER!=ADMINNATIONNAL){ ?>
            <div style="float: left"><a class="infoBulle" href="<?php echo '/' . REPERTOIRE . '/exportjson.php?lang=' . $lang ?>">&nbsp;<img    src='<?php echo "/" . REPERTOIRE; ?>/styles/img/export.png' ><span style="width: 260px"><?php echo TXT_EXPORTPROJETCENTRALE; ?></span></a></div>
            <div style="float: left;margin-top: 3px;margin-left:5px">                
                <a class="infoBulle" href="<?php echo '/' . REPERTOIRE . '/modifBase/viderCache.php?lang=' . $lang; ?>">&nbsp;<img    src='<?php echo "/" . REPERTOIRE; ?>/styles/img/refresh.png' ><span style="width: 200px;border-radius: 4px;text-align:center; ">
                <?php echo TXT_ACTUALDATA; ?></span></a>
            </div>
            <?php } ?>
            <div style='width:100px;'>
            </div>
            <div data-dojo-type="dijit/layout/TabContainer" style="margin-top:25px;width: 1050px;font-size: 1.2em;" doLayout="false" selected="true" >               
                <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo "<div title='" . TXT_NBPROJET . ': ' . $nbprojet . "'>" . TXT_PROJETMACENTRALE . "</div>"; ?>" style="height:550px;white-space: normal" >
                    <?php include_once 'html/vueSuiviProjetCentrale.html'; ?>
                </div>
                <div data-dojo-type="dijit/layout/ContentPane"  title="<?php echo "<div title='" . TXT_NBPROJET . ': ' . $nbprojetAttente . "'>" . TXT_PROJETSENATTENTE . "</div>"; ?>" style="height:500px;" >
                    <?php include_once 'html/vueSuiviProjetAttenteCentrale.html'; ?>
                </div>
                <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo "<div title='" . TXT_NBPROJET . ': ' . $nbprojetAccepte . "'>" . TXT_PROJETACCEPTE . "</div>"; ?>" style="height:500px;" >
                    <?php include_once 'html/vueSuiviProjetAccepteCentrale.html'; ?>
                </div>
                <div data-dojo-type="dijit/layout/ContentPane"  title="<?php echo "<div title='" . TXT_NBPROJET . ': ' . $nbprojetEncoursRealisation . "'>" . TXT_PROJETENCOURSREALISATION . "</div>"; ?>" style="height:500px;" >
                    <?php include_once 'html/vueSuiviProjetEncoursRealisation.html'; ?>
                </div>
                <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo "<div title='" . TXT_NBPROJET . ': ' . $nbProjetFini . "'>" . TXT_PROJETFINI . "</div>"; ?>" style="height:500px;" >
                    <?php include_once 'html/vueSuiviProjetFiniCentrale.html'; ?>
                </div>
                <div data-dojo-type="dijit/layout/ContentPane"  title="<?php echo "<div title='" . TXT_NBPROJET . ': ' . $nbRapportProjet . "'>" . TXT_REPORT . "</div>"; ?>" style="height:500px;" >
                    <?php include_once 'html/vueSuiviRapportProjet.html'; ?>
                </div>
                <?php if ($nbProjetSoustraitance > 0) { ?>
                    <div data-dojo-type="dijit/layout/ContentPane"  title="<?php echo "<div title='" . TXT_NBPROJET . ': ' . $nbProjetSoustraitance . "'>" . TXT_PROJETSOUSTRAITANCE . "</div>"; ?>" style="height:500px;" >
                        <?php include_once 'html/vueSuiviProjetensoustaitance.html'; ?>
                    </div>
                <?php } ?>
                <div data-dojo-type="dijit/layout/ContentPane"  title="<?php echo "<div title='" . TXT_NBPROJET . ': ' . $nbProjetRefusee . "'>" . TXT_PROJETREFUSE . "</div>"; ?>" style="height:500px;" >
                    <?php include_once 'html/vueSuiviProjetRefuseCentrale.html'; ?>
                </div>
                <div data-dojo-type="dijit/layout/ContentPane"  title="<?php echo "<div title='" . TXT_NBPROJET . ': ' . $nbProjetCloturer . "'>" . TXT_PROJETCLOTURE . "</div>"; ?>" style="height:500px;" >
                    <?php include_once 'html/vueSuiviProjetClotureCentrale.html'; ?>
                </div>   
            </div>
            <input type="button" id="surprise" value="<?php echo TXT_LASTACTION; ?>" style="display: none"/>
        </form>
    
    <table>
        <tr>
            <td><div style="background-color: darkgreen;height: 10px;width: 25px;border: 1px solid black; "></div></td>
            <td><label style="width:378px;margin-left: 5px"  ><?php echo TXT_DATENONDEPASSE; ?></label></td>
        </tr>
        <tr>
            <td><div style="background-color: darkgoldenrod;height: 10px;width: 25px;border: 1px solid black; "></div></td>
            <td><label style="width:378px;margin-left: 5px"  ><?php echo TXT_DATEPROCHE ?></label></td>
        </tr>
        <tr>
            <td><div style="background-color: red;height: 10px;width: 25px;border: 1px solid black; "></div></td>
            <td><label style="width:378px;margin-left: 5px"  ><?php echo TXT_DATEDEPASSE ?></label></td>
        </tr>
        <tr>
            <td><div style="background-color: black;height: 10px;width: 25px;border: 1px solid black; "></div></td>
            <td><label style="width:378px;margin-left: 5px"  ><?php echo TXT_NONGERER ?></label></td>
        </tr>
    </table>
    <?php include 'html/footer.html'; ?>
</div></div>

</body>
</html>