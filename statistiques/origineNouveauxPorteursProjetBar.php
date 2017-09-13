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
                <select  id="anneeNouveauxPorteursProjet" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'anneeNouveauxPorteursProjet',value: '',required:false,placeHolder: '<?php echo TXT_SELECTYEAR; ?>'" 
                         style="width: 250px;margin-left:35px" 
                         onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/statOrigineNouveauxPorteurProjet/<?php echo $lang . '/'; ?>' + this.value)" >
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
if (IDTYPEUSER == ADMINNATIONNAL && !isset($_GET['anneeNouveauxPorteursProjet'])) {
    $title = TXT_ORIGINENOUVEAUPORTEURPROJET;
    $nbPermanentIndustriel = $manager->getSingle2("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
            . "AND cr.idprojet_projet = co.idprojet_projet and idqualitedemandeurindust_qualitedemandeurindust=?  and u.idcentrale_centrale is null", PERMANENTINDUST);
    $s_PermanentIndustriel = '{name: "' . TXT_PERMANENTINDUSTRIEL . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbPermanentIndustriel . ',drilldown: "' . 'pIndustriel' . '"}]},';
    $nbPermanentExterne = $manager->getSingle2("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
            . "AND cr.idprojet_projet = co.idprojet_projet and idqualitedemandeuraca_qualitedemandeuraca = ? and u.idcentrale_centrale is null", PERMANENT);
    $s_PermanentExterne = '{name: "' . TXT_PERMANENTEXTERNE . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbPermanentExterne . ',drilldown: "' . 'pExterne' . '"}]},';
    $nbNonPermanentIndustriel = $manager->getSingle2("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
                    . "AND cr.idprojet_projet = co.idprojet_projet and u.idcentrale_centrale is null and idqualitedemandeurindust_qualitedemandeurindust = ?", NONPERMANENTINDUST);
    $s_nonPermanentIndustriel = '{name: "' . TXT_NONPERMANENTINDUSTRIEL . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbNonPermanentIndustriel . ',drilldown: "' . 'npIndustriel' . '"}]},';
    $nbNonPermanentExterne = $manager->getSingle2("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
            . "AND cr.idprojet_projet = co.idprojet_projet and u.idcentrale_centrale is null and idqualitedemandeuraca_qualitedemandeuraca = ?", NONPERMANENT);
    $s_nonPermanentExterne = '{name: "' . TXT_NONPERMANENTEXTERNE . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbNonPermanentExterne . ',drilldown: "' . 'npExterne' . '"}]},';
    $nbPermanentInterne = $manager->getSingle("select count(idutilisateur) from utilisateur where idqualitedemandeuraca_qualitedemandeuraca = 2 and idcentrale_centrale is not null");
    $s_PermanentInterne = '{name: "' . TXT_PERMANENTINTERNE . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbPermanentInterne . ',drilldown: "' . 'pInterne' . '"}]},';
    $nbNonPermanentInterne = $manager->getSingle("select count(idutilisateur) from utilisateur where idqualitedemandeuraca_qualitedemandeuraca = 3 and idcentrale_centrale is not null");
    $s_nonPermanentInterne = '{name: "' . TXT_NONPERMANENTINTERNE . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbNonPermanentInterne . ',drilldown: "' . 'npInterne' . '"}]},';
    $serie = $s_PermanentInterne . $s_PermanentExterne . $s_PermanentIndustriel . $s_nonPermanentInterne . $s_nonPermanentExterne . $s_nonPermanentIndustriel;
    $serieX = substr($serie, 0, -1);    
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              PERMANENT  INTERNE
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------    
    
    $_pInterne = "{id: " . "'pInterne',name: " . "'Permanent interne'," . "data: [";
    foreach ($years as $key => $value) {
        $nbByYearPermanentInterne = $manager->getSinglebyArray("select count(idutilisateur) from utilisateur where idqualitedemandeuraca_qualitedemandeuraca = ? and idcentrale_centrale is not null "
                . "and EXTRACT(YEAR from datecreation)<=?  ", array(PERMANENT, $value[0]));
        if($value[0]==2013){            
            $_pInterne .="{name: '" . TXT_INFERIEUR2013 . "', y: " . $nbByYearPermanentInterne . " , drilldown: '" . 'intper' . $value[0] . "'},";
        }else{
            $_pInterne .="{name: '" . $value[0] . "', y: " . $nbByYearPermanentInterne . " , drilldown: '" . 'intper' . $value[0] . "'},";
        }
    }$_pInterne .="]},";
    //$_pInterne = str_replace("},]}", "}]}", $_pInterne);
    foreach ($years as $key => $annee) {        
        $_pInterne .= "{id: 'intper" . $annee[0] . "',name: 'Permanent interne  $annee[0]" . "',data: [";
        foreach ($centrale as $key => $cent) {
            $nbByYearPermanentInterne1 = $manager->getSinglebyArray("select count(idutilisateur) from utilisateur,centrale where idcentrale_centrale=idcentrale and idqualitedemandeuraca_qualitedemandeuraca = ? "
                    . "and libellecentrale=? and EXTRACT(YEAR from datecreation)<=?  ", array(PERMANENT, $cent[0], $annee[0]));
            $_pInterne .="{name: '" . $cent[0] . "', y: " . $nbByYearPermanentInterne1 . " , drilldown: '" . $cent[0] . 'int' . $annee[0] . "'},";
        }$_pInterne .="]},";
    }
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------        
//                                                                              NON PERMANENT ACADEMIQUE INTERNE
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------                
    $_npInterne = "";
    $_npInterne = "{id: " . "'npInterne',name: " . "'Non Permanent Interne'," . "data: [";
    foreach ($years as $key => $value) {
        $nbByYearNonPermanentInterne = $manager->getSinglebyArray("select count(idutilisateur) from utilisateur where idqualitedemandeuraca_qualitedemandeuraca = ? and idcentrale_centrale is not null "
                . "and EXTRACT(YEAR from datecreation)<=?  ", array(NONPERMANENT, $value[0]));
        if($value[0]==2013){
            $_npInterne .="{name: '" . TXT_INFERIEUR2013 . "', y: " . $nbByYearNonPermanentInterne . " , drilldown: '" . 'int' . $value[0] . "'},";
        }else{
            $_npInterne .="{name: '" . $value[0] . "', y: " . $nbByYearNonPermanentInterne . " , drilldown: '" . 'int' . $value[0] . "'},";
        }
        
    }$_npInterne .="]},";
    foreach ($years as $key => $value) {
        $_npInterne .= "{id: 'int" . $value[0] . "',name: 'Non Permanent Interne  $value[0]" . "',data: [";
        foreach ($centrale as $key => $cent) {
            $nbByYearNonPermanentInterne1 = $manager->getSinglebyArray("select count(idutilisateur) from utilisateur,centrale where idcentrale_centrale=idcentrale and idqualitedemandeuraca_qualitedemandeuraca = ? "
                    . "and libellecentrale=? and EXTRACT(YEAR from datecreation)<=?  ", array(NONPERMANENT, $cent[0], $value[0]));
            $_npInterne .="{name: '" . $cent[0] . "', y: " . $nbByYearNonPermanentInterne1 . " , drilldown: '" . $cent[0] . 'int' . $value[0] . "'},";
        }$_npInterne .="]},";
    }
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------        
//                                                                              PERMANENT EXTERNE
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------                
    $_pExterne ="{id: " . "'pExterne',name: " . "'Permanent Externe'," . "data: [";
    foreach ($years as $key => $value) {
         $nbByYearPermanentExterne = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur AND cr.idprojet_projet = co.idprojet_projet "
                 . "and idqualitedemandeuraca_qualitedemandeuraca = ? and u.idcentrale_centrale is null and extract(year from datecreation)<=? ",array(PERMANENT, $value[0]));
        if (empty($nbByYearPermanentExterne)) {
            $nbByYearPermanentExterne = 0;
        }
        if($value[0]==2013){
            $_pExterne .="{name: '" . TXT_INFERIEUR2013 . "', y: " . $nbByYearPermanentExterne . " , drilldown: '" . 'ext' . $value[0] . "'},";
        }else{
            $_pExterne .="{name: '" . $value[0] . "', y: " . $nbByYearPermanentExterne . " , drilldown: '" . 'ext' . $value[0] . "'},";
        }
        
    }$_pExterne .="]},";
    foreach ($years as $key => $year) {
        $_pExterne .= "{id: " . "'ext$year[0]',name: " . "'Permanent Externe $year[0]'," . "data: [";
        foreach ($centrale as $key => $cent) {            
            $nbByYearPermanentExterneCent = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur AND cr.idprojet_projet = co.idprojet_projet "
                 . "and idqualitedemandeuraca_qualitedemandeuraca = ? and u.idcentrale_centrale is null and extract(year from datecreation)<=? and co.idcentrale_centrale=?",array(PERMANENT, $year[0],$cent[1]));
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
                . "AND cr.idprojet_projet = co.idprojet_projet and idqualitedemandeurindust_qualitedemandeurindust=?  and u.idcentrale_centrale is null and EXTRACT(YEAR from datecreation)<=? ", array(PERMANENT, $value[0]));        
        if (empty($nbByYearPermanentIndustriel)) {
            $nbByYearPermanentIndustriel = 0;
        }
        if($value[0]==2013){
            $_pIndustriel .="{name: '" . TXT_INFERIEUR2013 . "', y: " . $nbByYearPermanentIndustriel . " , drilldown: '" . 'ind' . $value[0] . "'},";
        }else{
            $_pIndustriel .="{name: '" . $value[0] . "', y: " . $nbByYearPermanentIndustriel . " , drilldown: '" . 'ind' . $value[0] . "'},";
        }
        
    }$_pIndustriel .="]},";
    foreach ($years as $key => $value) {
        $_pIndustriel .= "{id: " . "'ind$value[0]',name: " . "'Permanent Industriel $value[0]'," . "data: [";
        
        foreach ($centrale as $key => $cent) {            
            $nbByYearPermanentIndustrielCent = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
                . "AND cr.idprojet_projet = co.idprojet_projet and idqualitedemandeurindust_qualitedemandeurindust=?  and u.idcentrale_centrale is null and EXTRACT(YEAR from datecreation)<=? and co.idcentrale_centrale=?",
                    array(PERMANENT, $value[0],$cent[1]));    
            if (empty($nbByYearPermanentIndustrielCent)) {
                $nbByYearPermanentIndustrielCent = 0;
            }
            $_pIndustriel .="{name: '" . $cent[0] . "', y: " . $nbByYearPermanentIndustrielCent . " , drilldown: '" . 'indnp' . $value[0] . $cent[0] . "'},";
        }$_pIndustriel .="]},";       
    }
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------        
//                                                                              NON PERMANENT EXTERNE
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------                    
    $_npExterne = "{id: " . "'npExterne',name: " . "'Non Permanent Externe'," . "data: [";
    foreach ($years as $key => $value) {
        $nbByYearNonPermanentExterne = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
                    . "AND cr.idprojet_projet = co.idprojet_projet and u.idcentrale_centrale is null and idqualitedemandeuraca_qualitedemandeuraca = ? and EXTRACT(YEAR from datecreation)<=? ", array(NONPERMANENT, $value[0]));
        if (empty($nbByYearNonPermanentExterne)) {
            $nbByYearNonPermanentExterne = 0;
        }
        if($value[0]==2013){
            $_npExterne .="{name: '" . TXT_INFERIEUR2013 . "', y: " . $nbByYearNonPermanentExterne . " , drilldown: '" . 'npext' . $value[0] . "'},";
        }else{
            $_npExterne .="{name: '" . $value[0] . "', y: " . $nbByYearNonPermanentExterne . " , drilldown: '" . 'npext' . $value[0] . "'},";
        }
    }$_npExterne .="]},";
    foreach ($years as $key => $value) {
        $_npExterne .= "{id: " . "'npext$value[0]',name: " . "'Non Permanent Externe $value[0]'," . "data: [";
        foreach ($centrale as $key => $cent) {
            $nbByYearNonPermanentExterneCent = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
                    . "AND cr.idprojet_projet = co.idprojet_projet and u.idcentrale_centrale is null and idqualitedemandeuraca_qualitedemandeuraca = ? and EXTRACT(YEAR from datecreation)<=? and co.idcentrale_centrale=? ",
                array(NONPERMANENT, $value[0],$cent[1]));
            
            
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
                    . "AND cr.idprojet_projet = co.idprojet_projet and u.idcentrale_centrale is null and idqualitedemandeurindust_qualitedemandeurindust = ? and EXTRACT(YEAR from datecreation)<=?",
                array(NONPERMANENTINDUST, $valueYear[0]));
        if (empty($nbByYearNonPermanentIndustriel)) {
            $nbByYearNonPermanentIndustriel = 0;
        }
        if($valueYear[0]==2013){
            $_npIndustriel .="{name: '" . TXT_INFERIEUR2013 . "', y: " . $nbByYearNonPermanentIndustriel . " , drilldown: '" . 'npIndustriel' . $valueYear[0] . "'},";
        }else{
            $_npIndustriel .="{name: '" . $valueYear[0] . "', y: " . $nbByYearNonPermanentIndustriel . " , drilldown: '" . 'npIndustriel' . $valueYear[0] . "'},";
        }
    }$_npIndustriel .="]},";

    foreach ($years as $key => $valueYear) {
        $_npIndustriel .= "{id: " . "'npIndustriel$valueYear[0]',name: " . "'Non Permanent Industriel $valueYear[0]'," . "data: [";
        foreach ($centrale as $key => $cent) {
             $nbByYearNonPermanentIndustrielCent = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
                    . "AND cr.idprojet_projet = co.idprojet_projet and u.idcentrale_centrale is null and idqualitedemandeurindust_qualitedemandeurindust = ? and EXTRACT(YEAR from datecreation)<=? "
                . "and co.idcentrale_centrale=?",array(NONPERMANENTINDUST, $valueYear[0],$cent[1]));
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
    
} elseif (IDTYPEUSER == ADMINNATIONNAL && isset($_GET['anneeNouveauxPorteursProjet'])) {
    ?>
    <table>
        <tr>
            <td><input class="admin" type="button" onclick="window.location.replace('/<?php echo REPERTOIRE; ?>/chxStatistique/<?php echo $lang . '/' . IDPERMANENTNONPERMANENTBYDATE; ?>')"  value= "<?php echo TXT_EFFACESELECTION; ?>" >
            </td>
        </tr>
    </table> 
    <?php
    $anneeSelectionne = $_GET['anneeNouveauxPorteursProjet'];    
    $nbPermanentIndustriel = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
            . "AND cr.idprojet_projet = co.idprojet_projet and idqualitedemandeurindust_qualitedemandeurindust=?  and u.idcentrale_centrale is null and extract(year from datecreation)<=?",
            array(PERMANENTINDUST,$_GET['anneeNouveauxPorteursProjet']));
    $s_PermanentIndustriel = '{name: "' . TXT_PERMANENTINDUSTRIEL . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbPermanentIndustriel . ',drilldown: "' . 'pIndustriel' . '"}]},';
    
    $nbPermanentExterne = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
            . "AND cr.idprojet_projet = co.idprojet_projet and idqualitedemandeuraca_qualitedemandeuraca = ? and u.idcentrale_centrale is null and extract(year from datecreation)<=?",
            array(PERMANENT,$_GET['anneeNouveauxPorteursProjet']));    
    $s_PermanentExterne = '{name: "' . TXT_PERMANENTEXTERNE . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbPermanentExterne . ',drilldown: "' . 'pExterne' . '"}]},';
    
    $nbNonPermanentIndustriel = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
                    . "AND cr.idprojet_projet = co.idprojet_projet and u.idcentrale_centrale is null and idqualitedemandeurindust_qualitedemandeurindust = ? and extract(year from datecreation)<=?", 
            array(NONPERMANENTINDUST,$_GET['anneeNouveauxPorteursProjet']));
    $s_nonPermanentIndustriel = '{name: "' . TXT_NONPERMANENTINDUSTRIEL . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbNonPermanentIndustriel . ',drilldown: "' . 'npIndustriel' . '"}]},';
    
    $nbNonPermanentExterne = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
            . "AND cr.idprojet_projet = co.idprojet_projet and u.idcentrale_centrale is null and idqualitedemandeuraca_qualitedemandeuraca = ? and extract(year from datecreation)<=?",
            array(NONPERMANENT,$_GET['anneeNouveauxPorteursProjet']));
    $s_nonPermanentExterne = '{name: "' . TXT_NONPERMANENTEXTERNE . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbNonPermanentExterne . ',drilldown: "' . 'npExterne' . '"}]},';

    $nbPermanentInterne = $manager->getSinglebyArray("select count(idutilisateur) from utilisateur where idqualitedemandeuraca_qualitedemandeuraca = ? and idcentrale_centrale is not null "
            . "and extract(year from datecreation)<=?",array(PERMANENT,$_GET['anneeNouveauxPorteursProjet']));
    $s_PermanentInterne = '{name: "' . TXT_PERMANENTINTERNE . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbPermanentInterne . ',drilldown: "' . 'pInterne' . '"}]},';
    
    $nbNonPermanentInterne = $manager->getSinglebyArray("select count(idutilisateur) from utilisateur where idqualitedemandeuraca_qualitedemandeuraca = ? and idcentrale_centrale is not null "
            . "and extract(year from datecreation)<=?",array(NONPERMANENT,$_GET['anneeNouveauxPorteursProjet']));
    $s_nonPermanentInterne = '{name: "' . TXT_NONPERMANENTINTERNE . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbNonPermanentInterne . ',drilldown: "' . 'npInterne' . '"}]},';
    
    $serie = $s_PermanentInterne . $s_PermanentExterne . $s_PermanentIndustriel . $s_nonPermanentInterne . $s_nonPermanentExterne . $s_nonPermanentIndustriel;
    $serieX = substr($serie, 0, -1);
//                                                                               FIN DE L'ABCISSE X
    
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              PERMANENT ACADEMIQUE INTERNE
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------    
    if($anneeSelectionne==2013){
        $_pInterne = "{id: " . "'pInterne',name: " . "'Permanent interne ".TXT_INFERIEUR2013."'," . "data: [";    
    }else{
        $_pInterne = "{id: " . "'pInterne',name: " . "'Permanent interne $anneeSelectionne'," . "data: [";    
    }
    foreach ($centrale as $key => $cent) {
        $nbByYearPermanentInterne1 = $manager->getSinglebyArray("select count(idutilisateur) from utilisateur,centrale where idcentrale_centrale=idcentrale and idqualitedemandeuraca_qualitedemandeuraca = ? "
                . "and libellecentrale=? and EXTRACT(YEAR from datecreation)<=?  ", array(PERMANENT, $cent[0], $_GET['anneeNouveauxPorteursProjet']));        
            $_pInterne .="{name: '" . $cent[0] . "', y: " . $nbByYearPermanentInterne1 . " , drilldown: '" . $cent[0] . 'int' . $_GET['anneeNouveauxPorteursProjet'] . "'},";
    }$_pInterne .="]},";
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              NON PERMANENT ACADEMIQUE INTERNE
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    if($anneeSelectionne==2013){
        $_npInterne = "{id: " . "'npInterne',name: " . "'Non Permanent Interne ". TXT_INFERIEUR2013."',data: [";    
    }else{
        $_npInterne = "{id: " . "'npInterne',name: " . "'Non Permanent Interne $anneeSelectionne'," . "data: [";    
    }
    foreach ($centrale as $key => $cent) {
        $nbByYearNonPermanentInterne1 = $manager->getSinglebyArray("select count(idutilisateur) from utilisateur,centrale where idcentrale_centrale=idcentrale and idqualitedemandeuraca_qualitedemandeuraca = ? "
                . "and libellecentrale=? and EXTRACT(YEAR from datecreation)<=?  ", array(NONPERMANENT, $cent[0], $_GET['anneeNouveauxPorteursProjet']));
        $_npInterne .="{name: '" . $cent[0] . "', y: " . $nbByYearNonPermanentInterne1 . " , drilldown: '" . $cent[0] . 'int' . $_GET['anneeNouveauxPorteursProjet'] . "'},";
    }$_npInterne .="]},";
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                               PERMANENT ACADEMIQUE EXTERNE
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------    
    if($anneeSelectionne==2013){
        $_pExterne ="{id: " . "'pExterne',name: " . "'Permanent Externe ".TXT_INFERIEUR2013."' ," . "data: [";
    }else{
        $_pExterne ="{id: " . "'pExterne',name: " . "'Permanent Externe $anneeSelectionne' ," . "data: [";
    }
    foreach ($centrale as $key => $cent) {
        $nbByYearPermanentExterneMois = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
                    . "AND cr.idprojet_projet = co.idprojet_projet and u.idcentrale_centrale is null and idqualitedemandeuraca_qualitedemandeuraca = ? and EXTRACT(YEAR from datecreation)<=? "
                . "and co.idcentrale_centrale=?",array(PERMANENT, $anneeSelectionne, $cent[1]));
        if (empty($nbByYearPermanentExterneMois)) {
            $nbByYearPermanentExterneMois = 0;
        }
        $_pExterne .="{name: '" . $cent[0] . "', y: " . $nbByYearPermanentExterneMois . " , drilldown: '" . 'ext' . $anneeSelectionne . $cent[0] . "'},";
    }$_pExterne .="]},";
    $_pExterne = str_replace("},]}", "}]}", $_pExterne);
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                               PERMANENT INDUSTRIEL
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    if($anneeSelectionne==2013){
        $_pIndustriel = "{id: " . "'pIndustriel',name: " . "'Permanent Industriel ".TXT_INFERIEUR2013."'," . "data: [";    
    }else{
        $_pIndustriel = "{id: " . "'pIndustriel',name: " . "'Permanent Industriel $anneeSelectionne'," . "data: [";    
    }
    foreach ($centrale as $key => $cent) {
        $nbByYearPermanentIndustrielMois = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
            . "AND cr.idprojet_projet = co.idprojet_projet and idqualitedemandeurindust_qualitedemandeurindust=?  and u.idcentrale_centrale is null and extract(year from datecreation)<=? and co.idcentrale_centrale=?",
                array(PERMANENT, $anneeSelectionne, $cent[1]));
        if (empty($nbByYearPermanentIndustrielMois)) {
            $nbByYearPermanentIndustrielMois = 0;
        }
        $_pIndustriel .="{name: '" . $cent[0] . "', y: " . $nbByYearPermanentIndustrielMois . " , drilldown: '" . 'ind' . $anneeSelectionne . $cent[0] . "'},";
    }$_pIndustriel .="]},";    
    $_pIndustriel = str_replace("},]}", "}]}", $_pIndustriel);
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                               NON PERMANENT EXTERNE
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    if($anneeSelectionne==2013){
        $_npExterne = "{id: " . "'npExterne',name: " . "'Non Permanent Externe ".TXT_INFERIEUR2013."'," . "data: [";    
    }else{
        $_npExterne = "{id: " . "'npExterne',name: " . "'Non Permanent Externe $anneeSelectionne'," . "data: [";    
    }
    foreach ($centrale as $key => $cent) {
        $nbByYearNonPermanentExterneMois = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
            . "AND cr.idprojet_projet = co.idprojet_projet and u.idcentrale_centrale is null and idqualitedemandeuraca_qualitedemandeuraca = ? and extract(year from datecreation)<=? and co.idcentrale_centrale=?",
                array(NONPERMANENT, $anneeSelectionne, $cent[1]));
        if (empty($nbByYearNonPermanentExterneMois)) {
            $nbByYearNonPermanentExterneMois = 0;
        }
        $_npExterne .="{name: '" . $cent[0] . "', y: " . $nbByYearNonPermanentExterneMois . " , drilldown: '" . 'indnp' . $anneeSelectionne . $cent[0] . "'},";
    }$_npExterne .="]},";
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                               NON PERMANENT INDUSTRIEL
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------    
    if($anneeSelectionne==2013){
        $_npIndustriel = "{id: " . "'npIndustriel',name: " . "'Non Permanent Industriel ".TXT_INFERIEUR2013."'," . "data: [";   
    }else{
        $_npIndustriel = "{id: " . "'npIndustriel',name: " . "'Non Permanent Industriel $anneeSelectionne'," . "data: [";   
    }
    foreach ($centrale as $key => $cent) {
        $nbByYearNonPermanentIndustrielMois = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
                    . "AND cr.idprojet_projet = co.idprojet_projet and u.idcentrale_centrale is null and idqualitedemandeurindust_qualitedemandeurindust = ? and co.idcentrale_centrale=? and extract(year from datecreation)<=?",
                array(NONPERMANENTINDUST, $cent[1],$anneeSelectionne));
        if (empty($nbByYearNonPermanentIndustrielMois)) {
            $nbByYearNonPermanentIndustrielMois = 0;
        }
        $_npIndustriel .="{name: '" . $cent[0] . "', y: " . $nbByYearNonPermanentIndustrielMois . " , drilldown: '" . 'indnp' . $anneeSelectionne . $cent[0] . "'},";
    }$_npIndustriel .="]},";
    if($anneeSelectionne==2013){
           $title = TXT_ORIGINENOUVEAUPORTEURPROJETDATE.' '.TXT_INFERIEUR2013;
    }else{
        $title = TXT_ORIGINENOUVEAUPORTEURPROJETDATE.$anneeSelectionne;
    }    
    $resultPermanent = $_pInterne . $_pExterne . $_pIndustriel;
    $resultNP0 = $_npInterne . $_npExterne . $_npIndustriel;
    $resultNonPermanent = substr($resultNP0, 0, -1);
    $result0 = $resultPermanent . $resultNonPermanent;
    $serieY = str_replace("},]}", "}]}", $result0);
}


if (IDTYPEUSER == ADMINLOCAL) {
    $nbPermanentIndustriel = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur AND cr.idprojet_projet = co.idprojet_projet"
            . " and idqualitedemandeurindust_qualitedemandeurindust=?  and co.idcentrale_centrale=?",array(PERMANENTINDUST,IDCENTRALEUSER));
    $s_PermanentIndustriel = '{name: "' . TXT_PERMANENTINDUSTRIEL . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbPermanentIndustriel . ',drilldown: "' . 'pIndustriel' . '"}]},';
    
    $nbPermanentExterne = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur AND cr.idprojet_projet = co.idprojet_projet "
            . "and idqualitedemandeuraca_qualitedemandeuraca = ? and u.idcentrale_centrale is null and co.idcentrale_centrale=?",array(PERMANENT,IDCENTRALEUSER));
    $s_PermanentExterne = '{name: "' . TXT_PERMANENTEXTERNE . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbPermanentExterne . ',drilldown: "' . 'pExterne' . '"}]},';
    
    $nbNonPermanentIndustriel = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur AND cr.idprojet_projet = co.idprojet_projet "
            . "and idqualitedemandeurindust_qualitedemandeurindust = ? and co.idcentrale_centrale=?",array(NONPERMANENTINDUST,IDCENTRALEUSER));
    $s_nonPermanentIndustriel = '{name: "' . TXT_NONPERMANENTINDUSTRIEL . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbNonPermanentIndustriel . ',drilldown: "' . 'npIndustriel' . '"}]},';
   
    $nbNonPermanentExterne = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur AND cr.idprojet_projet = co.idprojet_projet "
            . "and u.idcentrale_centrale is null and idqualitedemandeuraca_qualitedemandeuraca =? and co.idcentrale_centrale=?",array(NONPERMANENT,IDCENTRALEUSER));
    $s_nonPermanentExterne = '{name: "' . TXT_NONPERMANENTEXTERNE . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbNonPermanentExterne . ',drilldown: "' . 'npExterne' . '"}]},';

    $nbPermanentInterne = $manager->getSinglebyArray("select count(idutilisateur) from utilisateur  WHERE idqualitedemandeuraca_qualitedemandeuraca = ? and idcentrale_centrale is not null and idcentrale_centrale=?",
            array(PERMANENT,IDCENTRALEUSER));
    $s_PermanentInterne = '{name: "' . TXT_PERMANENTINTERNE . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbPermanentInterne . ',drilldown: "' . 'pInterne' . '"}]},';
    
    $nbNonPermanentInterne = $manager->getSinglebyArray("select count(idutilisateur) from utilisateur,centrale where idcentrale_centrale=idcentrale and idqualitedemandeuraca_qualitedemandeuraca = ? and idcentrale=?",
            array(NONPERMANENT,IDCENTRALEUSER));
    $s_nonPermanentInterne = '{name: "' . TXT_NONPERMANENTINTERNE . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbNonPermanentInterne . ',drilldown: "' . 'npInterne' . '"}]},';
   
    $serie = $s_PermanentInterne . $s_PermanentExterne . $s_PermanentIndustriel . $s_nonPermanentInterne . $s_nonPermanentExterne . $s_nonPermanentIndustriel;
    $serieX = substr($serie, 0, -1);
//                                                                               FIN DE L'ABCISSE X
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              PERMANENT INTERNE
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------       
    $_pInterne = "{id: " . "'pInterne',name: " . "'Permanent interne'," . "data: [";
    foreach ($years as $key => $value) {
        $nbByYearPermanentInterne = $manager->getSinglebyArray("select count(idutilisateur) from utilisateur ,centrale WHERE idcentrale_centrale=idcentrale and idqualitedemandeuraca_qualitedemandeuraca = ?"
                . " and idcentrale=?  and EXTRACT(YEAR from datecreation)<=?  ", 
                array(PERMANENT, IDCENTRALEUSER,$value[0],));
        if($value[0]==2013){
            $_pInterne .="{name: '" . TXT_INFERIEUR2013 . "', y: " . $nbByYearPermanentInterne . " , drilldown: '" . 'intper' . $value[0] . "'},";
        }else{
            $_pInterne .="{name: '" . $value[0] . "', y: " . $nbByYearPermanentInterne . " , drilldown: '" . 'intper' . $value[0] . "'},";
        }
    }$_pInterne .="]},";
    
    foreach ($years as $key => $year) {
        $_pInterne .= "{id: " . "'intper$year[0]',name: " . "'Permanent interne $year[0]'," . "data: [";
        for ($mois = 1; $mois < 13; $mois++) {
            $nbByYearPermanentExterneMois = $manager->getSinglebyArray("select count(idutilisateur) from utilisateur ,centrale WHERE idcentrale_centrale=idcentrale and idqualitedemandeuraca_qualitedemandeuraca = ?"
                . " and EXTRACT(YEAR from datecreation)=? and EXTRACT(MONTH from datecreation)=? and idcentrale=?",array(PERMANENT, $year[0], $mois,IDCENTRALEUSER));
            if (empty($nbByYearPermanentExterneMois)) {
                $nbByYearPermanentExterneMois = 0;
            }
            $_pInterne .="{name: '" . showMonth($mois,$lang) . "', y: " . $nbByYearPermanentExterneMois . " , drilldown: '" . 'ext' . $year[0] . $mois . "'},";
        }$_pInterne .="]},";
    }$_pInterne = str_replace("},]}", "}]}", $_pInterne);
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              NON PERMANENT ACADEMIQUE INTERNE
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    $_npInterne = "{id: " . "'npInterne',name: " . "'Non Permanent Interne'," . "data: [";
    foreach ($years as $key => $year) {
        $nbByYearNonPermanentInterne = $manager->getSinglebyArray("select count(idutilisateur) from utilisateur,centrale where idcentrale_centrale=idcentrale and idqualitedemandeuraca_qualitedemandeuraca = ? "
                    . "and idcentrale=? and EXTRACT(YEAR from datecreation)<=?  ", array(NONPERMANENT, IDCENTRALEUSER, $year[0]));
        if($year[0]==2013){
            $_npInterne .="{name: '" . TXT_INFERIEUR2013 . "', y: " . $nbByYearNonPermanentInterne . " , drilldown: '" . 'npInterne' . $year[0] . "'},";
        }else{
            $_npInterne .="{name: '" . $year[0] . "', y: " . $nbByYearNonPermanentInterne . " , drilldown: '" . 'npInterne' . $year[0] . "'},";
        }
    }$_npInterne .="]},";
    $_npInterne = str_replace("},]}", "}]}", $_npInterne);
    
    foreach ($years as $key => $year) {
        $_npInterne .= "{id: " . "'npInterne$year[0]',name: " . "'Non Permanent Interne $year[0]'," . "data: [";                       
        for ($mois = 1; $mois < 13; $mois++) {
            $nbByYearNonPermanentInterne1 = $manager->getSinglebyArray("select count(idutilisateur) from utilisateur ,centrale WHERE idcentrale_centrale=idcentrale and idqualitedemandeuraca_qualitedemandeuraca = ?"
                . " and idcentrale=?  and EXTRACT(YEAR from datecreation)=? and EXTRACT(MONTH from datecreation)=?  ", array(NONPERMANENT,IDCENTRALEUSER, $year[0], $mois));
              if (empty($nbByYearNonPermanentInterne1)) {
                $nbByYearNonPermanentInterne1 = 0;
            }
            $_npInterne .="{name: '" . $mois . "', y: " . $nbByYearNonPermanentInterne1 . " , drilldown: '" . $mois . 'npInterne' . $year[0] . "'},";
        }$_npInterne .="]},";
    }

//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              PERMANENT EXTERNE
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    $_pExterne ="{id: " . "'pExterne',name: " . "'Permanent Externe'," . "data: [";
    foreach ($years as $key => $value) {
        //select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur AND cr.idprojet_projet = co.idprojet_projet
         $nbByYearPermanentExterne = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur AND cr.idprojet_projet = co.idprojet_projet "
                 . "and idqualitedemandeuraca_qualitedemandeuraca = ? and u.idcentrale_centrale is null and extract(year from datecreation)<=? and co.idcentrale_centrale=?",array(PERMANENT, $value[0],IDCENTRALEUSER));
        if (empty($nbByYearPermanentExterne)) {
            $nbByYearPermanentExterne = 0;
        }
        if($value[0]==2013){
            $_pExterne .="{name: '" . TXT_INFERIEUR2013 . "', y: " . $nbByYearPermanentExterne . " , drilldown: '" . 'ext' . $value[0] . "'},";
        }else{
            $_pExterne .="{name: '" . $value[0] . "', y: " . $nbByYearPermanentExterne . " , drilldown: '" . 'ext' . $value[0] . "'},";
        }
    }$_pExterne .="]},";


    foreach ($years as $key => $year) {
        $_pExterne .= "{id: " . "'ext$year[0]',name: " . "'Permanent Externe $year[0]'," . "data: [";
        for ($mois = 1; $mois < 13; $mois++) {
            $nbByYearPermanentExterneMois = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
                    . "AND cr.idprojet_projet = co.idprojet_projet and u.idcentrale_centrale is null and idqualitedemandeuraca_qualitedemandeuraca = ? and EXTRACT(YEAR from datecreation)=? "
                    . "and EXTRACT(MONTH from datecreation)=? and co.idcentrale_centrale=?",array(PERMANENT, $year[0], $mois,IDCENTRALEUSER));
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
                    . "AND cr.idprojet_projet = co.idprojet_projet and u.idcentrale_centrale is null and idqualitedemandeurindust_qualitedemandeurindust = ? and EXTRACT(YEAR from datecreation)<=? "
                . "and co.idcentrale_centrale=?", array(PERMANENT, $value[0],IDCENTRALEUSER));
        if (empty($nbByYearPermanentIndustriel)) {
            $nbByYearPermanentIndustriel = 0;
        }
        if($value[0]==2013){
            $_pIndustriel .="{name: '" . TXT_INFERIEUR2013 . "', y: " . $nbByYearPermanentIndustriel . " , drilldown: '" . $value[0] . 'ind' . "'},";
        }else{
            $_pIndustriel .="{name: '" . $value[0] . "', y: " . $nbByYearPermanentIndustriel . " , drilldown: '" . $value[0] . 'ind' . "'},";
        }
    }$_pIndustriel .="]},";
    foreach ($years as $key => $year) {
        $_pIndustriel .= "{id: " . "'$year[0]ind',name: " . "'Permanent Industriel $year[0]'," . "data: [";
        for ($mois = 1; $mois < 13; $mois++) {
            $nbByYearPermanentIndustrielMois = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
                    . "AND cr.idprojet_projet = co.idprojet_projet and u.idcentrale_centrale is null and idqualitedemandeurindust_qualitedemandeurindust = ? and EXTRACT(YEAR from datecreation)=? "
                . "and co.idcentrale_centrale=? and EXTRACT(MONTH from datecreation)=?", array(PERMANENT, $year[0],IDCENTRALEUSER, $mois));
            if (empty($nbByYearPermanentIndustrielMois)) {
                $nbByYearPermanentIndustrielMois = 0;
            }
            $_pIndustriel .="{name: '" . showMonth($mois,$lang) . "', y: " . $nbByYearPermanentIndustrielMois . " , drilldown: '" . 'ind' . $year[0] . $mois . "'},";
        }$_pIndustriel .="]},";
    }
    $_pIndustriel = str_replace("},]}", "}]}", $_pIndustriel);
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------        
//                                                                              NON PERMANENT EXTERNE
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------                    
    $_npExterne = "{id: " . "'npExterne',name: " . "'Non Permanent Externe'," . "data: [";
    foreach ($years as $key => $value) {
        $nbByYearNonPermanentExterne = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
                    . "AND cr.idprojet_projet = co.idprojet_projet and u.idcentrale_centrale is null and idqualitedemandeuraca_qualitedemandeuraca = ? and EXTRACT(YEAR from datecreation)<=? and co.idcentrale_centrale=? ",
                array(NONPERMANENT, $value[0],IDCENTRALEUSER));
        if (empty($nbByYearNonPermanentExterne)) {
            $nbByYearNonPermanentExterne = 0;
        }
        if($value[0]==2013){
            $_npExterne .="{name: '" . TXT_INFERIEUR2013 . "', y: " . $nbByYearNonPermanentExterne . " , drilldown: '" . 'npext' . $value[0] . "'},";
        }else{
            $_npExterne .="{name: '" . $value[0] . "', y: " . $nbByYearNonPermanentExterne . " , drilldown: '" . 'npext' . $value[0] . "'},";
        }
    }$_npExterne .="]},";
    foreach ($years as $key => $value) {
        $_npExterne .= "{id: " . "'npext$value[0]',name: " . "'Non Permanent Externe $value[0]'," . "data: [";
        for ($mois = 1; $mois < 13; $mois++) {
            $nbByYearNonPermanentExterneMois = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
                    . "AND cr.idprojet_projet = co.idprojet_projet and u.idcentrale_centrale is null and idqualitedemandeuraca_qualitedemandeuraca = ? and EXTRACT(YEAR from datecreation)=? and co.idcentrale_centrale=? "
                    . "and EXTRACT(MONTH from datecreation)=?", array(NONPERMANENT, $value[0],IDCENTRALEUSER, $mois));
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
                    . "AND cr.idprojet_projet = co.idprojet_projet and u.idcentrale_centrale is null and idqualitedemandeurindust_qualitedemandeurindust = ? and EXTRACT(YEAR from datecreation)<=? "
                . "and co.idcentrale_centrale=?",array(NONPERMANENTINDUST, $valueYear[0],IDCENTRALEUSER));
        if (empty($nbByYearNonPermanentIndustriel)) {
            $nbByYearNonPermanentIndustriel = 0;
        }
        if($valueYear[0]==2013){
            $_npIndustriel .="{name: '" . TXT_INFERIEUR2013 . "', y: " . $nbByYearNonPermanentIndustriel . " , drilldown: '" . 'npIndustriel' . $valueYear[0] . "'},";
        }else{
            $_npIndustriel .="{name: '" . $valueYear[0] . "', y: " . $nbByYearNonPermanentIndustriel . " , drilldown: '" . 'npIndustriel' . $valueYear[0] . "'},";
        }
    }$_npIndustriel .="]},";

    foreach ($years as $key => $valueYear) {
        $_npIndustriel .= "{id: " . "'npIndustriel$valueYear[0]',name: " . "'Non Permanent Industriel $valueYear[0]'," . "data: [";
        for ($mois = 1; $mois < 13; $mois++) {
            $nbByYearNonPermanentIndustrielMois = $manager->getSinglebyArray("select count(distinct u.idutilisateur) from utilisateur u,concerne co,creer cr WHERE u.idutilisateur = cr.idutilisateur_utilisateur "
                    . "AND cr.idprojet_projet = co.idprojet_projet and u.idcentrale_centrale is null and idqualitedemandeurindust_qualitedemandeurindust = ? and EXTRACT(YEAR from datecreation)=? "
                . "and co.idcentrale_centrale=? and EXTRACT(MONTH from datecreation)=?", array(NONPERMANENTINDUST, $valueYear[0],IDCENTRALEUSER, $mois));
            if (empty($nbByYearNonPermanentIndustrielMois)) {
                $nbByYearNonPermanentIndustrielMois = 0;
            }
            $_npIndustriel .="{name: '" . showMonth($mois,$lang) . "', y: " . $nbByYearNonPermanentIndustrielMois . " , drilldown: '" . 'indnp' . $valueYear[0] . $mois . "'},";
        }$_npIndustriel .="]},";
    }

    $title = TXT_ORIGINENOUVEAUPORTEURPROJET;
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
