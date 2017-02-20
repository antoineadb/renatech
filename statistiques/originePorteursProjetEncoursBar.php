<?php
include_once 'class/Manager.php';
$db = BD::connecter();
$manager = new Manager($db);
$years = $manager->getList("select distinct EXTRACT(YEAR from dateprojet)as year from projet where   EXTRACT(YEAR from dateprojet)>2012 order by year asc");
$centrale = $manager->getList2("select libellecentrale,idcentrale from centrale where idcentrale!=? and masquecentrale!=TRUE order by idcentrale asc", IDAUTRECENTRALE);
if (IDTYPEUSER == ADMINNATIONNAL) {
    ?>
    <table>
        <tr>
            <td>
                <select  id="anneeOriginePorteurProjetEncours" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'anneeOriginePorteurProjetEncours',value: '',required:false,placeHolder: '<?php echo TXT_SELECTYEAR; ?>'" 
                         style="width: 250px;margin-left:35px" 
                         onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/statOriginePorteurProjetEncours/<?php echo $lang . '/'; ?>' + this.value)" >
                             <?php
                             for ($i = 0; $i < count($years); $i++) {
                                 echo '<option value="' . $years[$i]['year'] . '">' . $years[$i]['year'] . '</option>';
                             }
                             ?>
                </select>
            </td>
        </tr>
    </table>
    <?php
}
if (IDTYPEUSER == ADMINNATIONNAL && !isset($_GET['anneeOriginePorteurProjetEncours'])) {
    $title = TXT_ORIGINEPORTEURPROJETENCOURS;
    
    $nbPermanentInterne = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
    . "AND cr.idprojet_projet = co.idprojet_projet and idqualitedemandeuraca_qualitedemandeuraca=?  and u.idcentrale_centrale is not null and co.idstatutprojet_statutprojet=?",array(PERMANENT,ENCOURSREALISATION));
    $s_PermanentInterne = '{name: "' . TXT_PERMANENTINTERNE . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbPermanentInterne . ',drilldown: "' . 'pInterne' . '"}]},';    
    
    $nbPermanentExterne = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
    . "AND cr.idprojet_projet = co.idprojet_projet and idqualitedemandeuraca_qualitedemandeuraca = ? and u.idcentrale_centrale is null and co.idstatutprojet_statutprojet=?", array(PERMANENT,ENCOURSREALISATION));
    $s_PermanentExterne = '{name: "' . TXT_PERMANENTEXTERNE . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbPermanentExterne . ',drilldown: "' . 'pExterne' . '"}]},';
    
    $nbPermanentIndustriel = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
    . "AND cr.idprojet_projet = co.idprojet_projet and idqualitedemandeurindust_qualitedemandeurindust=?  and u.idcentrale_centrale is null and co.idstatutprojet_statutprojet=?", array(PERMANENTINDUST,ENCOURSREALISATION));
    $s_PermanentIndustriel = '{name: "' . TXT_PERMANENTINDUSTRIEL . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbPermanentIndustriel . ',drilldown: "' . 'pIndustriel' . '"}]},';
    
    $nbNonPermanentIndustriel = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
    . "AND cr.idprojet_projet = co.idprojet_projet and u.idcentrale_centrale is null and idqualitedemandeurindust_qualitedemandeurindust = ? and co.idstatutprojet_statutprojet=?",array(NONPERMANENTINDUST,ENCOURSREALISATION));
    $s_nonPermanentIndustriel = '{name: "' . TXT_NONPERMANENTINDUSTRIEL . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbNonPermanentIndustriel . ',drilldown: "' . 'npIndustriel' . '"}]},';
    
    $nbNonPermanentExterne = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
    . "AND cr.idprojet_projet = co.idprojet_projet and u.idcentrale_centrale is null and idqualitedemandeuraca_qualitedemandeuraca = ? and co.idstatutprojet_statutprojet=?", array(NONPERMANENT,ENCOURSREALISATION));
    $s_nonPermanentExterne = '{name: "' . TXT_NONPERMANENTEXTERNE . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbNonPermanentExterne . ',drilldown: "' . 'npExterne' . '"}]},';
    
    $nbNonPermanentInterne = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
    . "AND cr.idprojet_projet = co.idprojet_projet and idqualitedemandeuraca_qualitedemandeuraca=?  and u.idcentrale_centrale is not null and co.idstatutprojet_statutprojet=?",array(NONPERMANENT,ENCOURSREALISATION));
    $s_nonPermanentInterne = '{name: "' . TXT_NONPERMANENTINTERNE . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbNonPermanentInterne . ',drilldown: "' . 'npInterne' . '"}]},';
    
    $serie = $s_PermanentInterne . $s_PermanentExterne . $s_PermanentIndustriel . $s_nonPermanentInterne . $s_nonPermanentExterne . $s_nonPermanentIndustriel;
    $serieX = substr($serie, 0, -1);
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              PERMANENT  INTERNE
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------    
    $_pInterne = "{id: " . "'pInterne',name: " . "'Permanent interne'," . "data: [";
    foreach ($years as $key => $value) {
        $nbByYearPermanentInterne = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
    . "AND cr.idprojet_projet = co.idprojet_projet and idqualitedemandeuraca_qualitedemandeuraca=?  and u.idcentrale_centrale is not null and co.idstatutprojet_statutprojet=? and EXTRACT(YEAR from u.datecreation)<=?",
                array(PERMANENT,ENCOURSREALISATION, $value[0]));
        $_pInterne .="{name: '" . $value[0] . "', y: " . $nbByYearPermanentInterne . " , drilldown: '" . 'intper' . $value[0] . "'},";
    }$_pInterne .="]},";
    foreach ($years as $key => $year) {
        $_pInterne .= "{id: 'intper" . $year[0] . "',name: 'Permanent interne  $year[0]" . "',data: [";
        foreach ($centrale as $key => $cent) {
            $nbByYearPermanentInterneCent = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
            . "AND cr.idprojet_projet = co.idprojet_projet and idqualitedemandeuraca_qualitedemandeuraca=?  and u.idcentrale_centrale is not null and co.idstatutprojet_statutprojet=? and EXTRACT(YEAR from u.datecreation)<=?"
            . "and u.idcentrale_centrale=?",array(PERMANENT,ENCOURSREALISATION, $year[0],$cent[1]));
            $_pInterne .="{name: '" . $cent[0] . "', y: " . $nbByYearPermanentInterneCent . " , drilldown: '" . $cent[0] . 'int' . $year[0] . "'},";
        }$_pInterne .="]},";
    }
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------        
//                                                                              PERMANENT EXTERNE
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------                
    $_pExterne ="{id: " . "'pExterne',name: " . "'Permanent Externe'," . "data: [";
    foreach ($years as $key => $value) {
        $nbByYearPermanentExterne = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
        . "AND cr.idprojet_projet = co.idprojet_projet  and idqualitedemandeuraca_qualitedemandeuraca = ? and u.idcentrale_centrale is null and extract(year from datecreation)<=? and co.idstatutprojet_statutprojet=?",
                array(PERMANENT, $value[0],ENCOURSREALISATION));
        if (empty($nbByYearPermanentExterne)) {
            $nbByYearPermanentExterne = 0;
        }
        $_pExterne .="{name: '" . $value[0] . "', y: " . $nbByYearPermanentExterne . " , drilldown: '" . 'ext' . $value[0] . "'},";
    }$_pExterne .="]},";
    foreach ($years as $key => $year) {
        $_pExterne .= "{id: " . "'ext$year[0]',name: " . "'Permanent Externe $year[0]'," . "data: [";
        foreach ($centrale as $key => $cent) {            
            $nbByYearPermanentExterneCent = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur AND cr.idprojet_projet = co.idprojet_projet "
                 . "and idqualitedemandeuraca_qualitedemandeuraca = ? and u.idcentrale_centrale is null and extract(year from datecreation)<=? and co.idcentrale_centrale=? and co.idstatutprojet_statutprojet=?",
                    array(PERMANENT, $year[0],$cent[1],ENCOURSREALISATION));
            if (empty($nbByYearPermanentExterneCent)) {
                $nbByYearPermanentExterneCent = 0;
            }
            $_pExterne .="{name: '" . $cent[0] . "', y: " . $nbByYearPermanentExterneCent . " , drilldown: '" . 'ext' . $year[0] . $cent[0] . "'},";
        }$_pExterne .="]},";
    }
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------        
//                                                                              PERMANENT INDUSTRIEL
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------                    
   
     $_pIndustriel = "{id: " . "'pIndustriel',name: " . "'Permanent Industriel'," . "data: [";
    foreach ($years as $key => $value) {
        $nbByYearPermanentIndustriel = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
        . "AND cr.idprojet_projet = co.idprojet_projet and idqualitedemandeurindust_qualitedemandeurindust=?  and u.idcentrale_centrale is null and EXTRACT(YEAR from datecreation)<=? and co.idstatutprojet_statutprojet=?"
        , array(PERMANENT, $value[0],ENCOURSREALISATION));
        if (empty($nbByYearPermanentIndustriel)) {
            $nbByYearPermanentIndustriel = 0;
        }
        $_pIndustriel .="{name: '" . $value[0] . "', y: " . $nbByYearPermanentIndustriel . " , drilldown: '" . 'ind' . $value[0] . "'},";
    }$_pIndustriel .="]},";
    foreach ($years as $key => $value) {
        $_pIndustriel .= "{id: " . "'ind$value[0]',name: " . "'Permanent Industriel $value[0]'," . "data: [";
        
        foreach ($centrale as $key => $cent) {            
            $nbByYearPermanentIndustrielCent = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
                . "AND cr.idprojet_projet = co.idprojet_projet and idqualitedemandeurindust_qualitedemandeurindust=?  and u.idcentrale_centrale is null and EXTRACT(YEAR from datecreation)<=? and co.idcentrale_centrale=?"
                    . "and co.idstatutprojet_statutprojet=?",array(PERMANENT, $value[0],$cent[1],ENCOURSREALISATION));    
            if (empty($nbByYearPermanentIndustrielCent)) {
                $nbByYearPermanentIndustrielCent = 0;
            }
            $_pIndustriel .="{name: '" . $cent[0] . "', y: " . $nbByYearPermanentIndustrielCent . " , drilldown: '" . 'indnp' . $value[0] . $cent[0] . "'},";
        }$_pIndustriel .="]},";       
    }    
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------        
//                                                                              NON PERMANENT INTERNE
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------                
    $_npInterne = "";
    $_npInterne = "{id: " . "'npInterne',name: " . "'Non Permanent Interne'," . "data: [";
    foreach ($years as $key => $value) {
        $nbByYearNonPermanentInterne = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
        . "AND cr.idprojet_projet = co.idprojet_projet and idqualitedemandeuraca_qualitedemandeuraca=?  and u.idcentrale_centrale is not null and EXTRACT(YEAR from datecreation)<=? "
        . "and co.idstatutprojet_statutprojet=?", array(NONPERMANENT, $value[0],ENCOURSREALISATION));
        $_npInterne .="{name: '" . $value[0] . "', y: " . $nbByYearNonPermanentInterne . " , drilldown: '" . 'int' . $value[0] . "'},";
    }$_npInterne .="]},";
    foreach ($years as $key => $value) {
        $_npInterne .= "{id: 'int" . $value[0] . "',name: 'Non Permanent Interne  $value[0]" . "',data: [";
        foreach ($centrale as $key => $cent) {
            $nbByYearNonPermanentInterne1 = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
            . "AND cr.idprojet_projet = co.idprojet_projet and idqualitedemandeuraca_qualitedemandeuraca=? and u.idcentrale_centrale is not null and EXTRACT(YEAR from datecreation)<=? and co.idstatutprojet_statutprojet=?"
            . "and u.idcentrale_centrale=?", array(NONPERMANENT,$value[0],ENCOURSREALISATION,$cent[1]));
            $_npInterne .="{name: '" . $cent[0] . "', y: " . $nbByYearNonPermanentInterne1 . " , drilldown: '" . $cent[0] . 'int' . $value[0] . "'},";
        }$_npInterne .="]},";
    }
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------        
//                                                                              NON PERMANENT EXTERNE
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------                    
    $_npExterne = "{id: " . "'npExterne',name: " . "'Non Permanent Externe'," . "data: [";
    foreach ($years as $key => $value) {
        $nbByYearNonPermanentExterne = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
        . "AND cr.idprojet_projet = co.idprojet_projet and u.idcentrale_centrale is null and idqualitedemandeuraca_qualitedemandeuraca = ? and EXTRACT(YEAR from datecreation)<=? and co.idstatutprojet_statutprojet=?", 
        array(NONPERMANENT, $value[0],ENCOURSREALISATION));
        if (empty($nbByYearNonPermanentExterne)) {
            $nbByYearNonPermanentExterne = 0;
        }
        $_npExterne .="{name: '" . $value[0] . "', y: " . $nbByYearNonPermanentExterne . " , drilldown: '" . 'npext' . $value[0] . "'},";
    }$_npExterne .="]},";
    foreach ($years as $key => $value) {
        $_npExterne .= "{id: " . "'npext$value[0]',name: " . "'Non Permanent Externe $value[0]'," . "data: [";
        foreach ($centrale as $key => $cent) {
            $nbByYearNonPermanentExterneCent = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
                    . "AND cr.idprojet_projet = co.idprojet_projet and u.idcentrale_centrale is null and idqualitedemandeuraca_qualitedemandeuraca = ? and EXTRACT(YEAR from datecreation)<=? and co.idcentrale_centrale=? "
                    . "and co.idstatutprojet_statutprojet=?",array(NONPERMANENT, $value[0],$cent[1],ENCOURSREALISATION));
            if (empty($nbByYearNonPermanentExterneCent)) {
                $nbByYearNonPermanentExterneCent = 0;
            }
            $_npExterne .="{name: '" . $cent[0] . "', y: " . $nbByYearNonPermanentExterneCent . " , drilldown: '" . 'indnp' . $value[0] . $cent[0] . "'},";
        }$_npExterne .="]},";
    }
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------        
//                                                                              NON PERMANENT INDUSTRIEL
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------                        
    $_npIndustriel = "{id: " . "'npIndustriel',name: " . "'Non Permanent Industriel'," . "data: [";
    foreach ($years as $key => $valueYear) {
        $nbByYearNonPermanentIndustriel = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
        . "AND cr.idprojet_projet = co.idprojet_projet and u.idcentrale_centrale is null and idqualitedemandeurindust_qualitedemandeurindust = ? and EXTRACT(YEAR from datecreation)<=? and co.idstatutprojet_statutprojet=?",
                array(NONPERMANENTINDUST, $valueYear[0],ENCOURSREALISATION));
        if (empty($nbByYearNonPermanentIndustriel)) {
            $nbByYearNonPermanentIndustriel = 0;
        }
        $_npIndustriel .="{name: '" . $valueYear[0] . "', y: " . $nbByYearNonPermanentIndustriel . " , drilldown: '" . 'npIndustriel' . $valueYear[0] . "'},";
    }$_npIndustriel .="]},";

    foreach ($years as $key => $valueYear) {
        $_npIndustriel .= "{id: " . "'npIndustriel$valueYear[0]',name: " . "'Non Permanent Industriel $valueYear[0]'," . "data: [";
        foreach ($centrale as $key => $cent) {
            $nbByYearNonPermanentIndustrielCent = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
            . "AND cr.idprojet_projet = co.idprojet_projet and u.idcentrale_centrale is null and idqualitedemandeurindust_qualitedemandeurindust = ? and EXTRACT(YEAR from datecreation)<=? and co.idstatutprojet_statutprojet=? "
            . "and co.idcentrale_centrale=?",array(NONPERMANENTINDUST, $valueYear[0],ENCOURSREALISATION,$cent[1]));
            if (empty($nbByYearNonPermanentIndustrielCent)) {
                $nbByYearNonPermanentIndustrielCent = 0;
            }
            $_npIndustriel .="{name: '" . $cent[0] . "', y: " . $nbByYearNonPermanentIndustrielCent . " , drilldown: '" . 'indnp' . $valueYear[0] . $cent[0] . "'},";
        }$_npIndustriel .="]},";
    }
    $resultPermanent =  $_pInterne . $_pExterne. $_pIndustriel;
    $resultNonPermanent = $_npInterne . $_npExterne . $_npIndustriel;
   
    $result0 = $resultPermanent . $resultNonPermanent;
    $serieY = str_replace("},]}", "}]}", $result0);
    
} elseif (IDTYPEUSER == ADMINNATIONNAL && isset($_GET['anneeOriginePorteurProjetEncours'])) {
    ?>
    <table>
        <tr>
            <td><input class="admin" type="button" onclick="window.location.replace('/<?php echo REPERTOIRE; ?>/chxStatistique/<?php echo $lang . '/' . IDORIGINEPORTPORTEURPROJETENCOURS; ?>')"  value= "<?php echo TXT_EFFACESELECTION; ?>" >
            </td>
        </tr>
    </table> 
    <?php
    $anneeSelectionne = $_GET['anneeOriginePorteurProjetEncours'];
    
    $nbPermanentInterne = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
    . "AND cr.idprojet_projet = co.idprojet_projet and idqualitedemandeuraca_qualitedemandeuraca=?  and u.idcentrale_centrale is not null and co.idstatutprojet_statutprojet=? and extract(year from datecreation)<=?",
            array(PERMANENT,ENCOURSREALISATION,$_GET['anneeOriginePorteurProjetEncours']));
    $s_PermanentInterne = '{name: "' . TXT_PERMANENTINTERNE . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbPermanentInterne . ',drilldown: "' . 'pInterne' . '"}]},';
    
    $nbPermanentExterne = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
    . "AND cr.idprojet_projet = co.idprojet_projet and idqualitedemandeuraca_qualitedemandeuraca = ? and u.idcentrale_centrale is null and co.idstatutprojet_statutprojet=? and extract(year from datecreation)<=?", 
            array(PERMANENT,ENCOURSREALISATION,$_GET['anneeOriginePorteurProjetEncours']));
    $s_PermanentExterne = '{name: "' . TXT_PERMANENTEXTERNE . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbPermanentExterne . ',drilldown: "' . 'pExterne' . '"}]},';    
    
    $nbPermanentIndustriel = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
    . "AND cr.idprojet_projet = co.idprojet_projet and idqualitedemandeurindust_qualitedemandeurindust=?  and u.idcentrale_centrale is null and extract(year from datecreation)<=? and co.idstatutprojet_statutprojet=?",
            array(PERMANENTINDUST,$_GET['anneeOriginePorteurProjetEncours'],ENCOURSREALISATION));
    $s_PermanentIndustriel = '{name: "' . TXT_PERMANENTINDUSTRIEL . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbPermanentIndustriel . ',drilldown: "' . 'pIndustriel' . '"}]},';
    
    $nbNonPermanentInterne = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
    . "AND cr.idprojet_projet = co.idprojet_projet and idqualitedemandeuraca_qualitedemandeuraca=?  and u.idcentrale_centrale is not null and co.idstatutprojet_statutprojet=? and extract(year from datecreation)<=?",
            array(NONPERMANENT,ENCOURSREALISATION,$_GET['anneeOriginePorteurProjetEncours']));
    $s_nonPermanentInterne = '{name: "' . TXT_NONPERMANENTINTERNE . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbNonPermanentInterne . ',drilldown: "' . 'npInterne' . '"}]},';
    
    $nbNonPermanentExterne = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
    . "AND cr.idprojet_projet = co.idprojet_projet and u.idcentrale_centrale is null and idqualitedemandeuraca_qualitedemandeuraca = ? and co.idstatutprojet_statutprojet=? and extract(year from datecreation)<=?", 
            array(NONPERMANENT,ENCOURSREALISATION,$_GET['anneeOriginePorteurProjetEncours']));
    $s_nonPermanentExterne = '{name: "' . TXT_NONPERMANENTEXTERNE . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbNonPermanentExterne . ',drilldown: "' . 'npExterne' . '"}]},';
    
    $nbNonPermanentIndustriel = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
    . "AND cr.idprojet_projet = co.idprojet_projet and u.idcentrale_centrale is null and idqualitedemandeurindust_qualitedemandeurindust = ? and co.idstatutprojet_statutprojet=? and extract(year from datecreation)<=?",
            array(NONPERMANENTINDUST,ENCOURSREALISATION,$_GET['anneeOriginePorteurProjetEncours']));
    $s_nonPermanentIndustriel = '{name: "' . TXT_NONPERMANENTINDUSTRIEL . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbNonPermanentIndustriel . ',drilldown: "' . 'npIndustriel' . '"}]},';
    
    $serie = $s_PermanentInterne . $s_PermanentExterne . $s_PermanentIndustriel . $s_nonPermanentInterne . $s_nonPermanentExterne . $s_nonPermanentIndustriel;
    $serieX = substr($serie, 0, -1);
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              PERMANENT INTERNE
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------    
    $_pInterne = "{id: " . "'pInterne',name: " . "'Permanent interne $anneeSelectionne'," . "data: [";    
    foreach ($centrale as $key => $cent) {
        $nbByYearPermanentInterne1 = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
        . "AND cr.idprojet_projet = co.idprojet_projet and idqualitedemandeuraca_qualitedemandeuraca=?  and u.idcentrale_centrale is not null and co.idstatutprojet_statutprojet=? and extract(year from datecreation)<=?"
        . "and u.idcentrale_centrale=?",array(PERMANENT,ENCOURSREALISATION,$_GET['anneeOriginePorteurProjetEncours'],$cent[1]));
        $_pInterne .="{name: '" . $cent[0] . "', y: " . $nbByYearPermanentInterne1 . " , drilldown: '" . $cent[0] . 'int' . $_GET['anneeOriginePorteurProjetEncours'] . "'},";
    }$_pInterne .="]},";
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                               PERMANENT  EXTERNE
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------    
    $_pExterne ="{id: " . "'pExterne',name: " . "'Permanent Externe $anneeSelectionne' ," . "data: [";  
    foreach ($centrale as $key => $cent) {             
        $nbByYearPermanentExterneCent = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
        . "AND cr.idprojet_projet = co.idprojet_projet and idqualitedemandeuraca_qualitedemandeuraca = ? and u.idcentrale_centrale is null and co.idstatutprojet_statutprojet=? and extract(year from datecreation)<=?"
                . "and co.idcentrale_centrale=?",array(PERMANENT,ENCOURSREALISATION,$_GET['anneeOriginePorteurProjetEncours'],$cent[1]));
        if (empty($nbByYearPermanentExterneCent)) {
            $nbByYearPermanentExterneCent = 0;
        }
        $_pExterne .="{name: '" . $cent[0] . "', y: " . $nbByYearPermanentExterneCent . " , drilldown: '" . 'ext' . $anneeSelectionne . $cent[0] . "'},";
    }$_pExterne .="]},";
    $_pExterne = str_replace("},]}", "}]}", $_pExterne);    
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                               PERMANENT INDUSTRIEL
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    $_pIndustriel = "{id: " . "'pIndustriel',name: " . "'Permanent Industriel $anneeSelectionne'," . "data: [";    
    foreach ($centrale as $key => $cent) {
        $nbByYearPermanentIndustrielCent = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
        . "AND cr.idprojet_projet = co.idprojet_projet and idqualitedemandeurindust_qualitedemandeurindust=?  and u.idcentrale_centrale is null and extract(year from datecreation)<=? and co.idstatutprojet_statutprojet=?"
        . "and co.idcentrale_centrale=?",array(PERMANENTINDUST,$_GET['anneeOriginePorteurProjetEncours'],ENCOURSREALISATION, $cent[1]));        
        if (empty($nbByYearPermanentIndustrielCent)) {
            $nbByYearPermanentIndustrielCent = 0;
        }
        $_pIndustriel .="{name: '" . $cent[0] . "', y: " . $nbByYearPermanentIndustrielCent . " , drilldown: '" . 'ind' . $anneeSelectionne . $cent[0] . "'},";
    }$_pIndustriel .="]},";    
    $_pIndustriel = str_replace("},]}", "}]}", $_pIndustriel);    
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              NON PERMANENT  INTERNE
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    $_npInterne = "{id: " . "'npInterne',name: " . "'Non Permanent Interne $anneeSelectionne'," . "data: [";    
    foreach ($centrale as $key => $cent) {        
        $nbNonPermanentInterne = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
        . "AND cr.idprojet_projet = co.idprojet_projet and idqualitedemandeuraca_qualitedemandeuraca=?  and u.idcentrale_centrale is not null and co.idstatutprojet_statutprojet=? and extract(year from datecreation)<=?"
        . "and u.idcentrale_centrale=?",array(NONPERMANENT,ENCOURSREALISATION,$_GET['anneeOriginePorteurProjetEncours'],$cent[1]));
        $_npInterne .="{name: '" . $cent[0] . "', y: " . $nbNonPermanentInterne . " , drilldown: '" . $cent[0] . 'int' . $_GET['anneeOriginePorteurProjetEncours'] . "'},";
    }$_npInterne .="]},";


//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                               NON PERMANENT EXTERNE
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    $_npExterne = "{id: " . "'npExterne',name: " . "'Non Permanent Externe $anneeSelectionne'," . "data: [";    
    foreach ($centrale as $key => $cent) {
        $nbNonPermanentExterne = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
        . "AND cr.idprojet_projet = co.idprojet_projet and u.idcentrale_centrale is null and idqualitedemandeuraca_qualitedemandeuraca = ? and co.idstatutprojet_statutprojet=? and extract(year from datecreation)<=?"
        . "and co.idcentrale_centrale=?", array(NONPERMANENT,ENCOURSREALISATION,$_GET['anneeOriginePorteurProjetEncours'], $cent[1]));        
        if (empty($nbNonPermanentExterne)) {
            $nbNonPermanentExterne = 0;
        }
        $_npExterne .="{name: '" . $cent[0] . "', y: " . $nbNonPermanentExterne . " , drilldown: '" . 'indnp' . $anneeSelectionne . $cent[0] . "'},";
    }$_npExterne .="]},";
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                               NON PERMANENT INDUSTRIEL
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------    
    $_npIndustriel = "{id: " . "'npIndustriel',name: " . "'Non Permanent Industriel $anneeSelectionne'," . "data: [";   
    foreach ($centrale as $key => $cent) {
        $nbNonPermanentIndustrielCent = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
        . "AND cr.idprojet_projet = co.idprojet_projet and u.idcentrale_centrale is null and idqualitedemandeurindust_qualitedemandeurindust = ? and co.idstatutprojet_statutprojet=? and extract(year from datecreation)<=?"
        . "and co.idcentrale_centrale=?",array(NONPERMANENTINDUST,ENCOURSREALISATION,$_GET['anneeOriginePorteurProjetEncours'],$cent[1]));        
        if (empty($nbNonPermanentIndustrielCent)) {
            $nbNonPermanentIndustrielCent = 0;
        }
        $_npIndustriel .="{name: '" . $cent[0] . "', y: " . $nbNonPermanentIndustrielCent . " , drilldown: '" . 'indnp' . $anneeSelectionne . $cent[0] . "'},";
    }$_npIndustriel .="]},";
    $title = TXT_ORIGINEPORTEURPROJETENCOURSDATE.$_GET['anneeOriginePorteurProjetEncours'];
    $resultPermanent = $_pInterne . $_pExterne . $_pIndustriel;
    $resultNP0 = $_npInterne . $_npExterne . $_npIndustriel;
    $resultNonPermanent = substr($resultNP0, 0, -1);
    $result0 = $resultPermanent . $resultNonPermanent;
    $serieY = str_replace("},]}", "}]}", $result0);
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                  ADMIN LOCAL
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
if (IDTYPEUSER == ADMINLOCAL) {    
    $nbPermanentInterne = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
    . "AND cr.idprojet_projet = co.idprojet_projet and idqualitedemandeuraca_qualitedemandeuraca=?  and u.idcentrale_centrale is not null and co.idstatutprojet_statutprojet=? and u.idcentrale_centrale=?",
            array(PERMANENT,ENCOURSREALISATION,IDCENTRALEUSER));
    $s_PermanentInterne = '{name: "' . TXT_PERMANENTINTERNE . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbPermanentInterne . ',drilldown: "' . 'pInterne' . '"}]},';    
    $nbPermanentExterne = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
    . "AND cr.idprojet_projet = co.idprojet_projet and idqualitedemandeuraca_qualitedemandeuraca = ? and u.idcentrale_centrale is null and co.idstatutprojet_statutprojet=? and co.idcentrale_centrale=?",
            array(PERMANENT,ENCOURSREALISATION,IDCENTRALEUSER));
    $s_PermanentExterne = '{name: "' . TXT_PERMANENTEXTERNE . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbPermanentExterne . ',drilldown: "' . 'pExterne' . '"}]},';
    $nbPermanentIndustriel = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
    . "AND cr.idprojet_projet = co.idprojet_projet and idqualitedemandeurindust_qualitedemandeurindust=?  and u.idcentrale_centrale is null and co.idstatutprojet_statutprojet=?  and co.idcentrale_centrale=?", 
            array(PERMANENTINDUST,ENCOURSREALISATION,IDCENTRALEUSER));
    $s_PermanentIndustriel = '{name: "' . TXT_PERMANENTINDUSTRIEL . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbPermanentIndustriel . ',drilldown: "' . 'pIndustriel' . '"}]},';
    $nbNonPermanentIndustriel = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
    . "AND cr.idprojet_projet = co.idprojet_projet and u.idcentrale_centrale is null and idqualitedemandeurindust_qualitedemandeurindust = ? and co.idstatutprojet_statutprojet=? and co.idcentrale_centrale=?",
            array(NONPERMANENTINDUST,ENCOURSREALISATION,IDCENTRALEUSER));
    $s_nonPermanentIndustriel = '{name: "' . TXT_NONPERMANENTINDUSTRIEL . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbNonPermanentIndustriel . ',drilldown: "' . 'npIndustriel' . '"}]},';
    
    $nbNonPermanentExterne = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
    . "AND cr.idprojet_projet = co.idprojet_projet and u.idcentrale_centrale is null and idqualitedemandeuraca_qualitedemandeuraca = ? and co.idstatutprojet_statutprojet=? and co.idcentrale_centrale=?", 
            array(NONPERMANENT,ENCOURSREALISATION,IDCENTRALEUSER));
    $s_nonPermanentExterne = '{name: "' . TXT_NONPERMANENTEXTERNE . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbNonPermanentExterne . ',drilldown: "' . 'npExterne' . '"}]},';
    
    $nbNonPermanentInterne = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
    . "AND cr.idprojet_projet = co.idprojet_projet and idqualitedemandeuraca_qualitedemandeuraca=?  and u.idcentrale_centrale is not null and co.idstatutprojet_statutprojet=? and u.idcentrale_centrale=?",
            array(NONPERMANENT,ENCOURSREALISATION,IDCENTRALEUSER));
    $s_nonPermanentInterne = '{name: "' . TXT_NONPERMANENTINTERNE . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbNonPermanentInterne . ',drilldown: "' . 'npInterne' . '"}]},';
    $serie = $s_PermanentInterne . $s_PermanentExterne . $s_PermanentIndustriel . $s_nonPermanentInterne . $s_nonPermanentExterne . $s_nonPermanentIndustriel;
    $serieX = substr($serie, 0, -1);    
//                                                                               FIN DE L'ABCISSE X
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              PERMANENT INTERNE
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------       
    $_pInterne = "{id: " . "'pInterne',name: " . "'Permanent interne'," . "data: [";
    foreach ($years as $key => $value) {
        
        $nbByYearPermanentInterne = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
    . "AND cr.idprojet_projet = co.idprojet_projet and idqualitedemandeuraca_qualitedemandeuraca=?  and u.idcentrale_centrale is not null and co.idstatutprojet_statutprojet=? and u.idcentrale_centrale=?"
                . "and EXTRACT(YEAR from datecreation)<=?",array(PERMANENT,ENCOURSREALISATION,IDCENTRALEUSER,$value[0]));
        $_pInterne .="{name: '" . $value[0] . "', y: " . $nbByYearPermanentInterne . " , drilldown: '" . 'intper' . $value[0] . "'},";
    }$_pInterne .="]},";
    
    foreach ($years as $key => $year) {
        $_pInterne .= "{id: " . "'intper$year[0]',name: " . "'Permanent interne $year[0]'," . "data: [";
        for ($mois = 1; $mois < 13; $mois++) {            
            $nbByYearPermanentExterneMois = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
            . "AND cr.idprojet_projet = co.idprojet_projet and idqualitedemandeuraca_qualitedemandeuraca=?  and u.idcentrale_centrale is not null and co.idstatutprojet_statutprojet=? and u.idcentrale_centrale=?"
                . "and EXTRACT(YEAR from datecreation)=? and EXTRACT(MONTH from datecreation)=?",array(PERMANENT,ENCOURSREALISATION,IDCENTRALEUSER,$year[0],$mois));            
            if (empty($nbByYearPermanentExterneMois)) {
                $nbByYearPermanentExterneMois = 0;
            }
            $_pInterne .="{name: '" . showMonth($mois,$lang) . "', y: " . $nbByYearPermanentExterneMois . " , drilldown: '" . 'ext' . $year[0] . $mois . "'},";
        }$_pInterne .="]},";
    }$_pInterne = str_replace("},]}", "}]}", $_pInterne);
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              PERMANENT EXTERNE
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    $_pExterne ="{id: " . "'pExterne',name: " . "'Permanent Externe'," . "data: [";
    foreach ($years as $key => $value) {
        $nbByYearPermanentExterne = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
        . "AND cr.idprojet_projet = co.idprojet_projet and idqualitedemandeuraca_qualitedemandeuraca = ? and u.idcentrale_centrale is null and co.idstatutprojet_statutprojet=? and co.idcentrale_centrale=? "
        . "and EXTRACT(YEAR from datecreation)=? ",array(PERMANENT,ENCOURSREALISATION,IDCENTRALEUSER,$value[0]));
        
        if (empty($nbByYearPermanentExterne)) {
            $nbByYearPermanentExterne = 0;
        }
        $_pExterne .="{name: '" . $value[0] . "', y: " . $nbByYearPermanentExterne . " , drilldown: '" . 'ext' . $value[0] . "'},";
    }$_pExterne .="]},";
    foreach ($years as $key => $year) {
        $_pExterne .= "{id: " . "'ext$year[0]',name: " . "'Permanent Externe $year[0]'," . "data: [";
        for ($mois = 1; $mois < 13; $mois++) {            
            $nbByYearPermanentExterneMois = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
            . "AND cr.idprojet_projet = co.idprojet_projet and idqualitedemandeuraca_qualitedemandeuraca = ? and u.idcentrale_centrale is null and co.idstatutprojet_statutprojet=? and co.idcentrale_centrale=? "
            . "and EXTRACT(YEAR from datecreation)=?  and EXTRACT(MONTH from datecreation)=? ",array(PERMANENT,ENCOURSREALISATION,IDCENTRALEUSER,$year[0],$mois));
            if (empty($nbByYearPermanentExterneMois)) {
                $nbByYearPermanentExterneMois = 0;
            }
            $_pExterne .="{name: '" . showMonth($mois,$lang) . "', y: " . $nbByYearPermanentExterneMois . " , drilldown: '" . 'ext' . $year[0] . $mois . "'},";
        }$_pExterne .="]},";
    }
    $_pExterne = str_replace("},]}", "}]}", $_pExterne);   
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------        
//                                                                              PERMANENT INDUSTRIEL
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------                    
    $_pIndustriel = "{id: " . "'pIndustriel',name: " . "'Permanent Industriel'," . "data: [";
    foreach ($years as $key => $value) {        
        $nbByYearPermanentIndustriel = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
        . "AND cr.idprojet_projet = co.idprojet_projet and idqualitedemandeurindust_qualitedemandeurindust=?  and u.idcentrale_centrale is null and co.idstatutprojet_statutprojet=?  and co.idcentrale_centrale=?"
                . "and EXTRACT(YEAR from datecreation)<=?", array(PERMANENTINDUST,ENCOURSREALISATION,IDCENTRALEUSER,$value[0]));
        if (empty($nbByYearPermanentIndustriel)) {
            $nbByYearPermanentIndustriel = 0;
        }
        $_pIndustriel .="{name: '" . $value[0] . "', y: " . $nbByYearPermanentIndustriel . " , drilldown: '" . $value[0] . 'ind' . "'},";
    }$_pIndustriel .="]},";
    foreach ($years as $key => $year) {
        $_pIndustriel .= "{id: " . "'$year[0]ind',name: " . "'Permanent Industriel $year[0]'," . "data: [";
        for ($mois = 1; $mois < 13; $mois++) {
            $nbByYearPermanentIndustrielMois = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
            . "AND cr.idprojet_projet = co.idprojet_projet and idqualitedemandeurindust_qualitedemandeurindust=?  and u.idcentrale_centrale is null and co.idstatutprojet_statutprojet=?  and co.idcentrale_centrale=?"
            . "and EXTRACT(YEAR from datecreation)=? and EXTRACT(MONTH from datecreation)=?", array(PERMANENTINDUST,ENCOURSREALISATION,IDCENTRALEUSER,$year[0],$mois));
            if (empty($nbByYearPermanentIndustrielMois)) {
                $nbByYearPermanentIndustrielMois = 0;
            }
            $_pIndustriel .="{name: '" . showMonth($mois,$lang) . "', y: " . $nbByYearPermanentIndustrielMois . " , drilldown: '" . 'ind' . $year[0] . $mois . "'},";
        }$_pIndustriel .="]},";
    }
    $_pIndustriel = str_replace("},]}", "}]}", $_pIndustriel);    
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              NON PERMANENT  INTERNE
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    $_npInterne = "{id: " . "'npInterne',name: " . "'Non Permanent Interne'," . "data: [";
    foreach ($years as $key => $year) {
        $nbByYearNonPermanentInterne = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
        . "AND cr.idprojet_projet = co.idprojet_projet and idqualitedemandeuraca_qualitedemandeuraca=?  and u.idcentrale_centrale is not null and co.idstatutprojet_statutprojet=? and u.idcentrale_centrale=?"
        . "and EXTRACT(YEAR from datecreation)<=?",array(NONPERMANENT,ENCOURSREALISATION,IDCENTRALEUSER,$year[0]));
        $_npInterne .="{name: '" . $year[0] . "', y: " . $nbByYearNonPermanentInterne . " , drilldown: '" . 'npInterne' . $year[0] . "'},";
    }$_npInterne .="]},";
    $_npInterne = str_replace("},]}", "}]}", $_npInterne);
    
    foreach ($years as $key => $year) {
        $_npInterne .= "{id: " . "'npInterne$year[0]',name: " . "'Non Permanent Interne $year[0]'," . "data: [";                       
        for ($mois = 1; $mois < 13; $mois++) {            
            $nbByYearNonPermanentInterne1 = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
            . "AND cr.idprojet_projet = co.idprojet_projet and idqualitedemandeuraca_qualitedemandeuraca=?  and u.idcentrale_centrale is not null and co.idstatutprojet_statutprojet=? and u.idcentrale_centrale=?"
            . "and EXTRACT(YEAR from datecreation)=? and EXTRACT(MONTH from datecreation)=?",array(NONPERMANENT,ENCOURSREALISATION,IDCENTRALEUSER,$year[0],$mois));
              if (empty($nbByYearNonPermanentInterne1)) {
                $nbByYearNonPermanentInterne1 = 0;
            }
            $_npInterne .="{name: '" . $mois . "', y: " . $nbByYearNonPermanentInterne1 . " , drilldown: '" . $mois . 'npInterne' . $year[0] . "'},";
        }$_npInterne .="]},";
    }

//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------        
//                                                                              NON PERMANENT EXTERNE
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------                    
    $_npExterne = "{id: " . "'npExterne',name: " . "'Non Permanent Externe'," . "data: [";
    foreach ($years as $key => $value) {
        $nbByYearNonPermanentExterne = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
        . "AND cr.idprojet_projet = co.idprojet_projet and u.idcentrale_centrale is null and idqualitedemandeuraca_qualitedemandeuraca = ? and co.idstatutprojet_statutprojet=? and co.idcentrale_centrale=? "
        . "and EXTRACT(YEAR from datecreation)<=?",array(NONPERMANENT,ENCOURSREALISATION,IDCENTRALEUSER, $value[0]));
        if (empty($nbByYearNonPermanentExterne)) {
            $nbByYearNonPermanentExterne = 0;
        }
        $_npExterne .="{name: '" . $value[0] . "', y: " . $nbByYearNonPermanentExterne . " , drilldown: '" . 'npext' . $value[0] . "'},";
    }$_npExterne .="]},";
    foreach ($years as $key => $value) {
        $_npExterne .= "{id: " . "'npext$value[0]',name: " . "'Non Permanent Externe $value[0]'," . "data: [";
        for ($mois = 1; $mois < 13; $mois++) {
            $nbByYearNonPermanentExterneMois = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
            . "AND cr.idprojet_projet = co.idprojet_projet and u.idcentrale_centrale is null and idqualitedemandeuraca_qualitedemandeuraca = ? and co.idstatutprojet_statutprojet=? and co.idcentrale_centrale=? "
            . "and EXTRACT(YEAR from datecreation)=? and EXTRACT(MONTH from datecreation)=?",array(NONPERMANENT,ENCOURSREALISATION,IDCENTRALEUSER, $value[0], $mois));
            if (empty($nbByYearNonPermanentExterneMois)) {
                $nbByYearNonPermanentExterneMois = 0;
            }
            $_npExterne .="{name: '" . showMonth($mois,$lang) . "', y: " . $nbByYearNonPermanentExterneMois . " , drilldown: '" . 'indnp' . $value[0] . $mois . "'},";
        }$_npExterne .="]},";
    }
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------        
//                                                                              NON PERMANENT INDUSTRIEL
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------                        
    $_npIndustriel = "{id: " . "'npIndustriel',name: " . "'Non Permanent Industriel'," . "data: [";
    foreach ($years as $key => $valueYear) {
        $nbByYearNonPermanentIndustriel = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
        . "AND cr.idprojet_projet = co.idprojet_projet and u.idcentrale_centrale is null and idqualitedemandeurindust_qualitedemandeurindust = ? and co.idstatutprojet_statutprojet=? and co.idcentrale_centrale=?"
                . "and EXTRACT(YEAR from datecreation)<=?",array(NONPERMANENTINDUST,ENCOURSREALISATION,IDCENTRALEUSER, $valueYear[0]));
        if (empty($nbByYearNonPermanentIndustriel)) {
            $nbByYearNonPermanentIndustriel = 0;
        }
        $_npIndustriel .="{name: '" . $valueYear[0] . "', y: " . $nbByYearNonPermanentIndustriel . " , drilldown: '" . 'npIndustriel' . $valueYear[0] . "'},";
    }$_npIndustriel .="]},";

    foreach ($years as $key => $valueYear) {
        $_npIndustriel .= "{id: " . "'npIndustriel$valueYear[0]',name: " . "'Non Permanent Industriel $valueYear[0]'," . "data: [";
        for ($mois = 1; $mois < 13; $mois++) {            
            $nbByYearNonPermanentIndustrielMois = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
            . "AND cr.idprojet_projet = co.idprojet_projet and u.idcentrale_centrale is null and idqualitedemandeurindust_qualitedemandeurindust = ? and co.idstatutprojet_statutprojet=? and co.idcentrale_centrale=?"
                . "and EXTRACT(YEAR from datecreation)=? and EXTRACT(MONTH from datecreation)=?",array(NONPERMANENTINDUST,ENCOURSREALISATION,IDCENTRALEUSER, $valueYear[0],$mois));            
            if (empty($nbByYearNonPermanentIndustrielMois)) {
                $nbByYearNonPermanentIndustrielMois = 0;
            }
            $_npIndustriel .="{name: '" . showMonth($mois,$lang) . "', y: " . $nbByYearNonPermanentIndustrielMois . " , drilldown: '" . 'indnp' . $valueYear[0] . $mois . "'},";
        }$_npIndustriel .="]},";
    }

    $title = TXT_ORIGINEPORTEURPROJETENCOURS;
    $resultPermanent = $_pInterne . $_pExterne . $_pIndustriel;
    $resultNP0 = $_npInterne . $_npExterne . $_npIndustriel;
    $resultNonPermanent = substr($resultNP0, 0, -1);
    $result0 = $resultPermanent . $resultNonPermanent;
    $serieY = str_replace("},]}", "}]}", $result0);
}
$subtitle = TXT_CLICDETAIL;
$xasisTitle = TXT_NOMBREOCCURRENCE;
include_once 'commun/scriptBar.php';

BD::deconnecter();
