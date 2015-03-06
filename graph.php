<?php
session_start();
include_once 'decide-lang.php';
include_once 'outils/toolBox.php';
include_once 'outils/constantes.php';
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
include 'html/header.html';
$typeUser = $manager->getSingle2("SELECT idtypeutilisateur_typeutilisateur FROM  loginpassword,utilisateur WHERE idlogin = idlogin_loginpassword and pseudo=?", $_SESSION['pseudo']);

?>

<div id="global">
    <?php include "html/entete.html"; ?>
    <div style="padding-top: 65px;">
        <?php include'outils/bandeaucentrale.php'; ?>
    </div>
    <?php
    if (isset($_GET['anneeprojet'])) {
        $ongletprojet = true;
    } elseif (isset($_GET['anneeuser'])) {
        $ongletutilisateur = true;
    }
    if ($typeUser == ADMINNATIONNAL) {
        $arraystat = $manager->getList("select * from statistique");
    } elseif ($typeUser == ADMINLOCAL) {
        $arraystat = $manager->getList2("select * from statistique where idstatistique!=? ", STATCENTRALETYPE);
    }
    $nbstat = count($arraystat);
    ?>    
<script src='<?php echo '/'.REPERTOIRE; ?>/js/jquery-1.8.0.min.js'></script>    
<script src="<?php echo '/'.REPERTOIRE; ?>/js/highcharts.js"></script>
<script src="<?php echo '/'.REPERTOIRE; ?>/js/exporting.js"></script>
<script src="<?php echo '/'.REPERTOIRE; ?>/js/rgbcolor.js"></script>
<script src="<?php echo '/'.REPERTOIRE; ?>/js/StackBlur.js"></script>
<script src="<?php echo '/'.REPERTOIRE; ?>/js/canvg.js"></script>
<script src="<?php echo '/'.REPERTOIRE; ?>/js//highcharts-3d.js"></script>
    <fieldset id='stat'><legend><?php echo TXT_STATISTIQUE; ?></legend>
        <select  id="choixstat" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'libellechoix',value: '',required:false,placeHolder: '<?php echo TXT_SELECTTYPE; ?>'"
                 style="width: 300px;margin-top:25px;" onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/chxStatistique/<?php echo $lang ?>/' + this.value)" >
                     <?php
                     if ($lang == 'fr') {
                         for ($i = 0; $i < $nbstat; $i++) {
                             echo '<option value=' . $arraystat[$i]['idstatistique'] . '>' . $arraystat[$i]['libellestatistique'] . '</option>';
                         }
                     } elseif ($lang == 'en') {
                         for ($i = 0; $i < $nbstat; $i++) {
                             echo '<option value=' . $arraystat[$i]['idstatistique'] . '>' . $arraystat[$i]['libellestatistiqueen'] . '</option>';
                         }
                     }
                     ?>
        </select>        
    </fieldset>
    <?php if (isset($_GET['statistique']) && $_GET['statistique'] == 'ok') { ?>
        <fieldset id ="statprojet"><legend><?php echo TXT_PROJET; ?></legend>
            <table>
                <tr>
                    <td>
                        <?php include 'statistiques/projetCentraleBar.php'; ?>
                        <?php include 'statistiques/projetCentralePie.php'; ?>
                    </td>
                </tr>
            </table>
        </fieldset>
    <?php } ?>
    <?php if (isset($_GET['statistique']) && $_GET['statistique'] == IDSTATPROJETDATE || isset($_GET['anneeprojet']) && $_GET['anneeprojet'] != 99 || isset($_GET['statut']) && $_GET['statut'] != 99) { ?>
        <fieldset id ="statprojet"><legend><?php echo TXT_PROJET; ?></legend>
            <table>
                <tr>
                    <td>
                        <?php include 'statistiques/projetCentraleBar.php'; ?>
                        <?php include 'statistiques/projetCentralePie.php'; ?>
                    </td>
                </tr>
            </table>
        </fieldset>
    <?php } elseif (isset($_GET['statistique']) && $_GET['statistique'] == IDSTATPROJETDATE || isset($_GET['statut']) && $_GET['statut'] != 99) { ?>
        <fieldset id ="statprojet"><legend><?php echo TXT_PROJET; ?></legend>
            <table>
                <tr>
                    <td>
                        <?php include 'statistiques/projetCentraleBar.php'; ?>
                        <?php include 'statistiques/projetCentralePie.php'; ?>
                    </td>
                </tr>
            </table>
        </fieldset>
    <?php } elseif (isset($_GET['statistique']) && $_GET['statistique'] == STATUSERDATE || isset($_GET['anneeuser']) && $_GET['anneeuser'] != 99) { ?>
        <fieldset id ="statprojet"><legend><?php echo TXT_USER; ?><a class="infoBulle" href="#">&nbsp;<img src='<?php echo "/" . REPERTOIRE; ?>/styles/img/help.gif' ><span style="width: 800px"><?php echo TXT_AIDENBUSER; ?></span></a></legend>
            <table>
                <tr>
                    <td>
                        <?php include 'statistiques/userCentraleBar.php'; ?>																									
                        <?php include 'statistiques/userCentralePie.php'; ?>
                    </td>
                </tr>
            </table>
        </fieldset>
    <?php } elseif (isset($_GET['statistique']) && $_GET['statistique'] == IDSTATPROJETDATETYPE || isset($_GET['anneeprojetautres']) && $_GET['anneeprojetautres'] != 99) { ?>
        <fieldset id ="statprojet"><legend><?php echo TXT_PROJETPARDATETYPE; ?><a class="infoBulle" href="#">&nbsp;<img src='<?php echo "/" . REPERTOIRE; ?>/styles/img/help.gif' ><span style="width: 750px;"><?php echo TXT_AIDECALCULTYPEPROJET; ?></span></a></legend>
            <table>
                <tr>
                    <td>
                        <?php include 'statistiques/projetAutresBar.php'; ?>
                        <?php include 'statistiques/projetAutresPIE.php'; ?>
                    </td>
                </tr>
            </table>
        </fieldset>
    <?php } elseif (isset($_GET['statistique']) && $_GET['statistique'] == IDSTATPROJETCENTRALETYPE || !empty($_GET['idcentrale'])||isset($_GET['anneecentrale']) && !empty($_GET['anneecentrale'])) { ?>
        <fieldset id ="statprojet"><legend><?php echo TXT_PROJETPARCENTRALETYPE; ?></legend>
            <table>
                <tr>
                    <td>
                        <?php include 'statistiques/projetAutresCentraleBar.php'; ?>
                        <?php include 'statistiques/projetAutresCentralePIE.php'; ?>
                    </td>
                </tr>
            </table>
        </fieldset>
    <?php } elseif (isset($_GET['statistique']) && $_GET['statistique'] == IDSTATSF || !empty($_GET['anneeprojetsf'])) { ?>
        <fieldset id ="statprojet"><legend><?php echo TXT_SOURCEFINANCEMENT; ?></legend>
            <table>
                <tr>
                    <td>
                        <?php include 'statistiques/projetSFBar.php'; ?>
                        <?php include 'statistiques/projetSFPIE.php'; ?>
                    </td>
                </tr>
            </table>
        </fieldset>
    <?php } elseif (isset($_GET['statistique']) && $_GET['statistique'] == IDSTATRESSOURCE || !empty($_GET['anneeprojetRessource'])) { ?>
        <fieldset id ="statprojet"><legend><?php echo TXT_RESSOURCE; ?></legend>
            <table>
                <tr>
                    <td>
                        <?php include 'statistiques/projetRessourceBar.php'; ?>
                        <?php include 'statistiques/projetRessourcePIE.php'; ?>
                    </td>
                </tr>
            </table>
        </fieldset>
        <?php } elseif (isset($_GET['statistique']) && $_GET['statistique'] == IDSTATPROJETDATETYPEPROJET || isset($_GET['anneeprojettypeprojet']) && $_GET['anneeprojettypeprojet'] != 99  || (isset($_GET['centrale']) && isset($_GET['l']))) { ?>
        <fieldset id ="statprojet"><legend><?php echo TXT_PROJET; ?></legend>
            <table>
                <tr>
                    <td>
                        <?php include 'statistiques/projetDateTypeprojet.php'; ?>																									
                        <?php include 'statistiques/projetDateTypeprojetPie.php'; ?>
                    </td>
                </tr>
            </table>
        </fieldset>
    <?php } elseif (isset($_GET['statistique']) && $_GET['statistique'] == IDDUREEPROJETDATE || isset($_GET['annee_nbprojet']) && $_GET['annee_nbprojet'] != 99  || (isset($_GET['centrale']) && isset($_GET['l']))) { ?>
        <fieldset id ="statprojet"><legend><?php echo TXT_PROJET; ?></legend>
            <table>
                <tr>
                    <td>
                        <?php include 'statistiques/duree_projet_date.php'; ?>																									
                        <?php //include 'statistiques/duree_projet_pie.php'; ?>
                    </td>
                </tr>
            </table>
        </fieldset>
    <?php }
    include 'html/footer.html'; ?>
</div>
</body>
</html>