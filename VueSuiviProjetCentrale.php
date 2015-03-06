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
$nbProjetEncours = $_SESSION['nbprojetencours'];
$nbProjetRefusee = $_SESSION['nbProjetRefusee'];
$nbProjetFini = $_SESSION['nbFini'];
$nbProjetCloturer = $_SESSION['nbprojetCloturer'];
$nbProjetSoustraitance = $_SESSION['nbProjetSoustraitance'];
?>


<div id="global" >
    <?php include 'html/entete.html'; ?>
    <div style="padding-top: 75px;">
        <?php
        if (internetExplorer() == 'false') {
            $Cache->inc(ROOT . '/outils/bandeaucentrale.php'); //RECUPERATION DU BANDEAU DEFILANT DANS LE CACHE CACHE
        } else {
            include 'outils/bandeaucentrale.php'; //RECUPERATION DU BANDEAU DEFILANT DANS LE CAS D'INTERNET EXPLORER
        }
        ?><form action="#" method="POST" id="vueprojetcentrale">
            <div style='width:100px;margin-left:10px;margin-bottom: -20px'><a class="infoBulle" href="<?php echo '/' . REPERTOIRE . '/exportjson.php?lang=' . $lang ?>">&nbsp;
                    <img    src='<?php echo "/" . REPERTOIRE; ?>/styles/img/export.png' ><span style="width: 260px"><?php echo TXT_EXPORTPROJETCENTRALE; ?></span></a>
            </div>
            <div data-dojo-type="dijit/layout/TabContainer" style="margin-top:25px;width: 1050px;font-size: 1.2em;" doLayout="false" selected="true" >
                <?php
                $perPage = 50;
                $nbTotalProjet = $manager->getSingle("select count(idprojet) from tmptous");
                $nbPage = ceil($nbTotalProjet / $perPage);
                if (isset($_GET['page']) && $_GET['page'] > 0 && $_GET['page'] <= $nbPage) {
                    $cPage = $_GET['page'];
                } else {
                    $cPage = 1;
                }?>
                <link rel="stylesheet" href="<?php echo '/' . REPERTOIRE ?>/styles/pagination.css" media="screen" />                
                <ul class="pagination style1 clearfix">
                    <li class="article">Page <?php echo $cPage; ?> de <?php echo $nbPage; ?></li>
                    <?php for ($i = 1; $i <= $nbPage; $i++) { ?>  
                        <li><a href="<?php echo '/' . REPERTOIRE . '/controler/controleSuiviProjetRespCentrale.php?lang=' . $lang . '&page=' . $i; ?>"><?php echo $i ?></a></li>
                    <?php } ?></ul>
                <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo "<div title='" . TXT_NBPROJET . ': ' . $nbprojet . "'>" . TXT_PROJETMACENTRALE . "</div>"; ?>" style="height:550px;white-space: normal" >
                    <?php include_once 'html/vueSuiviProjetCentrale.html'; ?>
                </div>
                <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo "<div title='" . TXT_NBPROJET . ': ' . $nbProjetEncours . "'>" . TXT_CONSULTPROJET . "</div>"; ?>" style="height:500px;" >
                    <?php include_once 'html/vueSuiviProjetCoursCentrale.html'; ?>
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
    </div>
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
</div>
</body>
</html>