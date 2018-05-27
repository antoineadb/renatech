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
    
    $arraystat = $manager->getList("select * from statistique");
    $nbstat = count($arraystat);
    ?>    
<script src='<?php echo '/'.REPERTOIRE; ?>/js/jquery-1.8.0.min.js'></script>
<script src="<?php echo '/'.REPERTOIRE; ?>/js/highcharts.js"></script>
<script src="<?php echo '/'.REPERTOIRE; ?>/js/exporting.js"></script>
<script src="<?php echo '/'.REPERTOIRE; ?>/js/rgbcolor.js"></script>
<script src="<?php echo '/'.REPERTOIRE; ?>/js/StackBlur.js"></script>
<script src="<?php echo '/'.REPERTOIRE; ?>/js/canvg.js"></script>
<script src="<?php echo '/'.REPERTOIRE; ?>/js/highcharts-3d.js"></script>
<script src="<?php echo '/'.REPERTOIRE; ?>/js/drilldown.js"></script>
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
         <?php if(IDTYPEUSER == ADMINNATIONNAL){?>
            <a id='aideStatNouveauProjet' href="<?php echo '/'.REPERTOIRE.'/downloadManual/aideContextuelleNouveauProjeDeposes.pdf' ?>" target="_blank">
                <img src="<?php echo '/'.REPERTOIRE; ?>/styles/img/infoStat.png" title="<?php echo TXT_AIDESTAT; ?>"  >
            </a>
            <?php }else{ ?>
            <a id='aideStatNouveauProjet' href="<?php echo '/'.REPERTOIRE.'/downloadManual/aideContextuelleNouveauProjeDeposeslocal.pdf' ?>" target="_blank">
                <img src="<?php echo '/'.REPERTOIRE; ?>/styles/img/infoStat.png" title="<?php echo TXT_AIDESTAT; ?>"  >
            </a>
            <?php } ?>
    </fieldset>
    <?php if (isset($_GET['statistique']) && $_GET['statistique'] == 'ok') {?>
        <fieldset id ="statprojet"><legend><?php echo TXT_PROJET; ?><a class="infoBulle" href="#">&nbsp;<img src='<?php echo "/" . REPERTOIRE; ?>/styles/img/help.gif' ><span style="width: 445px;">
            <?php echo affiche('TXT_AIDENOUVEAUPROJETDEPOSE'); ?></span></a></legend>           
            <table>
                <tr>
                    <td>
                        <?php include 'statistiques/nouveauProjetsBar.php'; ?>
                        <?php include 'statistiques/nouveauProjetsPie.php'; ?>
                    </td>
                </tr>
            </table>
        </fieldset>
    <?php } ?>
    <?php if (isset($_GET['statistique']) && $_GET['statistique'] == IDSTATNOUVEAUPROJET || isset($_GET['anneeNouveauProjet']) ) { ?>        
        <fieldset id ="statprojet"><legend><?php echo TXT_PROJET; ?><a class="infoBulle" href="#">&nbsp;<img src='<?php echo "/" . REPERTOIRE; ?>/styles/img/help.gif' ><span style="width: 445px;">
            <?php echo affiche('TXT_AIDENOUVEAUPROJETDEPOSE'); ?></span></a></legend>            
            <table>
                <tr>
                    <td>
                        <?php include 'statistiques/nouveauProjetsBar.php'; ?>
                        <?php include 'statistiques/nouveauProjetsPie.php'; ?>
                    </td>
                </tr>
            </table>
        </fieldset>
    <?php } elseif (isset($_GET['statistique']) && $_GET['statistique'] == STATUSERDATE || isset($_GET['anneeNewUserHolder']))  { ?>
        <fieldset id ="statprojet"><legend><?php echo TXT_INFO; ?><a class="infoBulle" href="#">&nbsp;<img src='<?php echo "/" . REPERTOIRE; ?>/styles/img/help.gif' >
                    <span style="width: 800px"><?php if (IDTYPEUSER==ADMINNATIONNAL){echo TXT_AIDENBUSER;}else{echo TXT_AIDENBUSERLOCAL;} ?></span></a></legend>
            <table>
                <tr>
                    <td>
                        <?php include 'statistiques/newUserBar.php'; ?>																									
                        <?php include 'statistiques/newUserPie.php'; ?>
                    </td>
                </tr>
            </table>
        </fieldset>
    <?php } elseif (isset($_GET['statistique'])&& $_GET['statistique'] == IDSTATPROJETDATETYPE){ ?>
        <fieldset id ="statprojet"><legend><?php echo TXT_PROJET; ?><a class="infoBulle" href="#">&nbsp;<img src='<?php echo "/" . REPERTOIRE; ?>/styles/img/help.gif' ><span style="width: 800px;">
            <?php echo TXT_AIDECALCULTYPEPROJET; ?></span></a></legend>
            <table>
                <tr>
                    <td>
                        <?php include 'statistiques/projetAutresBar.php'; ?>
                        <?php include 'statistiques/projetAutresPIE.php'; ?>
                    </td>
                </tr>
            </table>
        </fieldset>
    <?php } elseif (isset($_GET['statistique']) && $_GET['statistique'] == IDSTATSF ) { ?>        
        <?php if(IDTYPEUSER == ADMINNATIONNAL){?>
        <fieldset id ="statprojet"><legend><?php echo TXT_ORIGINEFINANCEMENT; ?><a class="infoBulle" href="#">&nbsp;<img src='<?php echo "/" . REPERTOIRE; ?>/styles/img/help.gif' ><span style="width: 630px;">        
        <?php echo affiche('TXT_AIDESF'); ?></span></a></legend>        
        <?php }else{ ?>
        <fieldset id ="statprojet"><legend><?php echo TXT_ORIGINEFINANCEMENT; ?><a class="infoBulle" href="#">&nbsp;<img src='<?php echo "/" . REPERTOIRE; ?>/styles/img/help.gif' ><span style="width: 620px;">
        <?php echo affiche('TXT_AIDESFLOCAL'); ?></span></a></legend>        
        <?php } ?>
            <table>
                <tr>
                    <td>
                        <?php include 'statistiques/projetSFBar.php'; ?>
                        <?php include 'statistiques/projetSFPIE.php'; ?>
                    </td>
                </tr>
            </table>
        </fieldset>
    <?php } elseif (isset($_GET['statistique']) && $_GET['statistique'] == IDSTATRESSOURCE ) { ?>        
        <?php if(IDTYPEUSER == ADMINNATIONNAL){?>
        <fieldset id ="statprojet"><legend><?php echo TXT_RESSOURCE.'s'; ?><a class="infoBulle" href="#">&nbsp;<img src='<?php echo "/" . REPERTOIRE; ?>/styles/img/help.gif' ><span style="width: 840px;">        
        <?php echo affiche('TXT_AIDERESSOURCE'); ?></span></a></legend>       
        <?php }else{ ?>
        <fieldset id ="statprojet"><legend><?php echo TXT_RESSOURCE.'s'; ?><a class="infoBulle" href="#">&nbsp;<img src='<?php echo "/" . REPERTOIRE; ?>/styles/img/help.gif' ><span style="width: 735px;">
        <?php echo affiche('TXT_AIDERESSOURCELOCAL'); ?></span></a></legend>
        
        <?php } ?>
            <table>
                <tr>
                    <td>
                        <?php include 'statistiques/projetRessourceBar.php'; ?>
                        <?php include 'statistiques/projetRessourcePIE.php'; ?>
                    </td>
                </tr>
            </table>
        </fieldset>
<?php } elseif (isset($_GET['statistique']) && $_GET['statistique'] == IDSTATPARTINDUSENCOURS ) { ?>        
        
        <fieldset id ="statprojet">
                <legend>
                    <?php echo TXT_INFO; ?><a class="infoBulle" href="#">&nbsp;<img src='<?php echo "/" . REPERTOIRE; ?>/styles/img/help.gif' ><span style="width: 280px;">        
                    <?php echo TXT_AIDEORIGINEPARTEAIREINDUSTRIEL; ?></span></a>
                </legend>       
        
            <table>
                <tr>
                    <td>
                        <?php include 'statistiques/partenaireIndustProjetEncoursBar.php'; ?>
                        <?php include 'statistiques/partenaireIndustProjetEncoursPIE.php'; ?>
                    </td>
                </tr>
            </table>
        </fieldset>
        <?php } elseif (isset($_GET['statistique']) && $_GET['statistique'] == IDSTATTYPOLOGIENOUVEAUPROJET || isset($_GET['anneeTypo']) ) { ?>
        <fieldset id ="statprojet"><legend><?php echo TXT_PROJET; ?><a class="infoBulle" href="#">&nbsp;<img src='<?php echo "/" . REPERTOIRE; ?>/styles/img/help.gif' >
                    <span style="width: 550px;"><?php if(IDTYPEUSER == ADMINNATIONNAL){echo affiche('TXT_AIDETYPOLOGIENOUVAUPROJET');}else{echo affiche('TXT_AIDETYPOLOGIENOUVEAUPROJETLOCAL');} ?></span></a></legend>
            <table>
                <tr>
                    <td>
                        <?php include 'statistiques/typologieNouveauProjetBar.php'; ?>																									
                        <?php include 'statistiques/typologieNouveauProjetPie.php'; ?>
                    </td>
                </tr>
            </table>
        </fieldset>
    <?php } elseif (isset($_GET['statistique']) && $_GET['statistique'] == IDDUREEPROJETENCOURS || isset($_GET['anneeDureeeProjet'])) { ?>
        <fieldset id ="statprojet"><legend><?php echo TXT_DUREEPROJETS; ?><a class="infoBulle" href="#">&nbsp;<img src='<?php echo "/" . REPERTOIRE; ?>/styles/img/help.gif' >
                    <span style="width: 685px;"><?php echo TXT_AIDEDUREEPROJET; ?></span></a></legend>
            <table>
                <tr>
                    <td>
                        <?php include 'statistiques/dureeProjetEncoursBar.php'; ?>																									
                        <?php include 'statistiques/dureeProjetEncoursPie.php'; ?>
                    </td>
                </tr>
            </table>
        </fieldset>    
<?php }elseif (isset($_GET['statistique']) && $_GET['statistique'] == IDNBUSERCLEANROOMNEWPROJET || isset($_GET['anneeUserCleanRoom'])) { ?>
        <?php if(IDTYPEUSER == ADMINNATIONNAL){?>
        <fieldset id ="statprojet"><legend><?php echo TXT_USER.'s'; ?><a class="infoBulle" href="#">&nbsp;<img src='<?php echo "/" . REPERTOIRE; ?>/styles/img/help.gif' ><span style="width: 750px;">        
        <?php echo affiche('TXT_AIDE_PERSONNECENTRALE'); ?></span></a></legend>            
        <?php }else{ ?>
            <fieldset id ="statprojet"><legend><?php echo TXT_USER.'s'; ?><a class="infoBulle" href="#">&nbsp;<img src='<?php echo "/" . REPERTOIRE; ?>/styles/img/help.gif' ><span style="width: 580px;">
            <?php echo affiche('TXT_AIDE_PERSONNECENTRALELOCAL'); ?></span></a></legend>            
            <?php } ?>
            <table>
                <tr>
                    <td>
                        <?php include 'statistiques/nbUserCleanRoomNewProjetBar.php'; ?>
                        <?php include 'statistiques/nbUserCleanRoomNewProjetPie.php'; ?>
                    </td>
                </tr>
            </table>
        </fieldset>
<?php }elseif (isset($_GET['statistique']) && $_GET['statistique'] == IDNBUSERCLEANROOMRUNNINGPROJET || isset($_GET['anneeUserCleanRoomRunninProject'])) { ?>
        <?php if(IDTYPEUSER == ADMINNATIONNAL){?>
        <fieldset id ="statprojet"><legend><?php echo TXT_USER.'s'; ?><a class="infoBulle" href="#">&nbsp;<img src='<?php echo "/" . REPERTOIRE; ?>/styles/img/help.gif' ><span style="width: 750px;">        
        <?php echo affiche('TXT_AIDE_PERSONNECENTRALEENCOURS'); ?></span></a></legend>            
        <?php }else{ ?>
            <fieldset id ="statprojet"><legend><?php echo TXT_USER.'s'; ?><a class="infoBulle" href="#">&nbsp;<img src='<?php echo "/" . REPERTOIRE; ?>/styles/img/help.gif' ><span style="width: 580px;">
            <?php echo affiche('TXT_AIDE_PERSONNECENTRALEENCOURSLOCAL'); ?></span></a></legend>            
            <?php } ?>
            <table>
                <tr>
                    <td>
                        <?php include 'statistiques/nbUserCleanRoomRunningProjetBar.php'; ?>
                        <?php include 'statistiques/nbUserCleanRoomRunningProjetPie.php'; ?>
                    </td>
                </tr>
            </table>
        </fieldset>
<?php }elseif (isset($_GET['statistique']) && $_GET['statistique'] == IDNBRUNNINGPROJECT || isset($_GET['anneeProjetEncours']) ) { ?>
    <fieldset id ="statprojet"><legend><?php echo TXT_PROJET; ?><a class="infoBulle" href="#">&nbsp;<img src='<?php echo "/" . REPERTOIRE; ?>/styles/img/help.gif' ><span style="width: 750px;">
        <?php if(IDTYPEUSER==ADMINNATIONNAL){echo affiche('TXT_AIDENBPROJETENCOURS');}  else {echo affiche('TXT_AIDENBPROJETENCOURSLOCAL');} ?></span></a></legend>
        <table>
                <tr>
                    <td>
                        <?php include 'statistiques/projetCentraleBarEncours.php'; ?>
                        <?php include 'statistiques/projetCentralePieEncours.php'; ?>
                    </td>
                </tr>
            </table>
        </fieldset>
<?php }elseif (isset($_GET['statistique']) && $_GET['statistique'] == IDNBPORTEURRUNNINGPROJECT || isset($_GET['anneePorteurProjetEncours']) ) { ?>
        <fieldset id ="statprojet"><legend><?php echo TXT_PROJET; ?><a class="infoBulle" href="#">&nbsp;<img src='<?php echo "/" . REPERTOIRE; ?>/styles/img/help.gif' ><span style="width: 750px;">
        <?php if(IDTYPEUSER==ADMINNATIONNAL){echo TXT_AIDENBUSERENCOURS;}  else {echo TXT_AIDENBUSERENCOURSLOCAL;} ?></span></a></legend>
            <table>
                <tr>
                    <td>
                        <?php include 'statistiques/nbProjetPorteurBarEncours.php'; ?>
                        <?php  include 'statistiques/nbProjetPorteurPieEncours.php'; ?>
                    </td>
                </tr>
            </table>
        </fieldset>
<?php }elseif (isset($_GET['statistique']) && $_GET['statistique'] == IDSTATTYPOLOGIEPROJETENCOURS ) { ?>        
            <?php if(IDTYPEUSER == ADMINNATIONNAL){?>
            <fieldset id ="statprojet"><legend><?php echo TXT_PROJET; ?><a class="infoBulle" href="#">&nbsp;<img src='<?php echo "/" . REPERTOIRE; ?>/styles/img/help.gif' ><span style="width: 590px;">        
            <?php echo affiche('TXT_AIDETYPOLOGIEPROJETENCOURS'); ?></span></a></legend>            
            <?php }else{ ?>
            <fieldset id ="statprojet"><legend><?php echo TXT_PROJET; ?><a class="infoBulle" href="#">&nbsp;<img src='<?php echo "/" . REPERTOIRE; ?>/styles/img/help.gif' ><span style="width: 580px;">
            <?php echo affiche('TXT_AIDETYPOLOGIEPROJETENCOURSLOCAL'); ?></span></a></legend>            
            <?php } ?>
            <table>
                <tr>
                    <td>
                        <?php include 'statistiques/typologieProjetEnCoursBar.php'; ?>
                        <?php include 'statistiques/typologieProjetEnCoursPie.php'; ?>
                    </td>
                </tr>
            </table>
        </fieldset>
<?php }elseif (isset($_GET['statistique']) && $_GET['statistique'] == IDREPARTIONPROJETENCOURSTYPE || isset($_GET['anneerepartitionProjetEncoursParType']) ) { ?>
        <fieldset id ="statprojet"><legend><?php echo TXT_PROJET; ?><a class="infoBulle" href="#">&nbsp;<img src='<?php echo "/" . REPERTOIRE; ?>/styles/img/help.gif' ><span style="width: 600px;">
            <?php echo TXT_AIDECALCULTYPEPROJETENCOURS; ?></span></a></legend>
            <table>
                <tr>
                    <td>
                        <?php include 'statistiques/projetAutresEnCoursBar.php'; ?>
                        <?php include 'statistiques/projetAutresEnCoursPIE.php'; ?>
                    </td>
                </tr>
            </table>
        </fieldset>
<?php }include 'html/footer.html'; ?>
</div>
</body>
</html>