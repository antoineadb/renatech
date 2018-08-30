<?php
include_once 'class/Manager.php';
include_once 'outils/constantes.php';
$db = BD::connecter();
$manager = new Manager($db);
$arraydate = $manager->getList("select distinct EXTRACT(YEAR from datecreation) as anneedatecreation from utilisateur order by anneedatecreation asc");
$years = $manager->getList("select distinct EXTRACT(YEAR from datecreation)as year from utilisateur order by year asc");
$centrales = $manager->getList2("select libellecentrale from centrale where idcentrale!=? and masquecentrale!=TRUE  order by idcentrale asc", IDAUTRECENTRALE);
$currentyear = date('Y');
if (IDTYPEUSER == ADMINNATIONNAL && isset($_GET['anneeNewUserHolder'])) {
    ?>
    <table>
        <tr>
            <td>
                <select  id="anneeNewUserHolder" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'anneeNewUserHolder',value: '',required:false,placeHolder: '<?php echo TXT_SELECTYEAR; ?>'" 
                         style="width: 250px;margin-left:35px" 
                         onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/statistiqueNouveauPorteurProjet/<?php echo $lang . '/'; ?>' + this.value)" >
                             <?php
                             for ($i = 0; $i < count($arraydate); $i++) {
                                 echo '<option value="' . ($arraydate[$i]['anneedatecreation']) . '">' . $arraydate[$i]['anneedatecreation'] . '</option>';
                             }
                             ?>
                </select>
            </td>
        </tr>
    </table>
    <table>
        <tr>
            <td><input class="admin" type="button" onclick="window.location.replace('/<?php echo REPERTOIRE; ?>/chxStatistique/<?php echo $lang . '/' . STATUSERDATE; ?>')"  value= "<?php echo TXT_EFFACESELECTION; ?>" >
            </td>
        </tr>
    </table>    
    <?php 
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------        
//                                                                              INDUSTRIEL
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    $manager->exeRequete("drop table if exists tmpnbnewUserIndust");
    $manager->getRequete("create table tmpnbnewUserIndust as (SELECT distinct idutilisateur as id,libellecentrale, extract (year from datecreation) as datecreation,extract (month from datecreation) as mois  "
            . "FROM utilisateur,creer c, concerne co, centrale ce WHERE idutilisateur = idutilisateur_utilisateur AND co.idprojet_projet = c.idprojet_projet AND  ce.idcentrale= co.idcentrale_centrale "
            . "and idqualitedemandeurindust_qualitedemandeurindust is not null AND extract(year from datecreation)<=? group by libellecentrale,idutilisateur ,datecreation,mois)", array($_GET['anneeNewUserHolder']));
    $nbIndustriel = $manager->getSingle("SELECT count(distinct id) as nb FROM tmpnbnewUserIndust");
    $industriel = '{name: "' . TXT_INDUSTRIEL . '", data: [{name: "' . TXT_DETAILS . '",y:' . $nbIndustriel . ',drilldown: "' . TXT_INDUSTRIEL . $_GET['anneeNewUserHolder'] . '"}]},';
    $serieIndustriel = "{id: '" . TXT_INDUSTRIEL . "',name: '" . TXT_INDUSTRIEL . "',data: [";
    $serieIndustriel .= "{name: '" . $_GET['anneeNewUserHolder'] . "', y: " . $nbIndustriel . " , drilldown: '" . TXT_INDUSTRIEL . $_GET['anneeNewUserHolder'] . "'},";
    $serieIndustriel .= "]},";
    if ($_GET['anneeNewUserHolder']) {
        $serie_0Industriel = "{id: '" . TXT_INDUSTRIEL . $_GET['anneeNewUserHolder'] . "',name: '" . TXT_INDUSTRIEL . ' ' . TXT_INFERIEUR2013 . "'" . ',data: [';
    } else {
        $serie_0Industriel = "{id: '" . TXT_INDUSTRIEL . $_GET['anneeNewUserHolder'] . "',name: '" . TXT_INDUSTRIEL . ' ' . $_GET['anneeNewUserHolder'] . "'" . ',data: [';
    }
    foreach ($centrales as $key => $centrale) {
        $nbUserCentrale = $manager->getSinglebyArray("SELECT   count(distinct idutilisateur) as nb FROM tmpnbnewUserInterne WHERE libellecentrale=? and datecreation<=?", array($centrale[0], $_GET['anneeNewUserHolder']));
        if (empty($nbUserCentrale)) {
            $nbUserCentrale = 0;
        }        
        $serie_0Industriel .= "{name: '" . $centrale[0]. "',color:'". couleurGraphLib($centrale[0]) . "', y: " . $nbUserCentrale . " , drilldown: '" . TXT_INDUSTRIEL . $centrale[0] . "'},";
        
        
    }$serie_0Industriel .= ']},';

    /* ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- */
    /*                                                                              ACADEMIQUE EXTERNE                                                                                          */
    /* ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- */
    $manager->exeRequete("drop table if exists tmpnbnewUserExterne");
    $manager->getRequete("create table tmpnbnewUserExterne as (SELECT distinct u.idutilisateur as id,libellecentrale,idcentrale, extract (year from datecreation) as datecreation,extract (month from datecreation) as mois "
            . "FROM utilisateur u,creer c,concerne co, centrale ce WHERE idutilisateur = idutilisateur_utilisateur AND co.idprojet_projet = c.idprojet_projet AND  ce.idcentrale= co.idcentrale_centrale "
            . "and idqualitedemandeuraca_qualitedemandeuraca is not null and u.idcentrale_centrale is null AND co.idcentrale_centrale!=? AND extract(year from datecreation)<=?  "
            . "and ce.masquecentrale!=TRUE group by idcentrale,libellecentrale,datecreation,mois,id "
            . "union "
            . "SELECT distinct u.idutilisateur as id,libellecentrale, extract (year from datecreation) as datecreation,idcentrale,extract (month from datecreation) as mois FROM utilisateur u,utilisateuradministrateur ua,creer c,"
            . "concerne co, centrale ce WHERE u.idutilisateur = idutilisateur_utilisateur AND co.idprojet_projet = c.idprojet_projet AND  ce.idcentrale= co.idcentrale_centrale AND ua.idutilisateur = u.idutilisateur "
            . "AND idqualitedemandeuraca_qualitedemandeuraca is not null and u.idcentrale_centrale is null AND co.idcentrale_centrale!=? AND extract(year from datecreation)<=?  "
            . "and ce.masquecentrale!=TRUE group by idcentrale,libellecentrale,datecreation,mois,id "
            . "union "
            . "SELECT distinct u.idutilisateur as id,libellecentrale, extract (year from datecreation) as datecreation,idcentrale,extract (month from datecreation) as mois FROM utilisateur u,utilisateurporteurprojet up,creer c,"
            . "concerne co, centrale ce  WHERE u.idutilisateur = up.idutilisateur_utilisateur AND  ce.idcentrale= co.idcentrale_centrale AND   up.idutilisateur_utilisateur = co.idprojet_projet "
            . "AND u.idqualitedemandeuraca_qualitedemandeuraca is not null and u.idcentrale_centrale is null AND co.idcentrale_centrale!=? AND extract(year from datecreation)<=?  "
            . "and ce.masquecentrale!=TRUE group by idcentrale,libellecentrale,datecreation,mois,id order by idcentrale,"
            . "datecreation,mois,id )", array(IDAUTRECENTRALE, $_GET['anneeNewUserHolder'], IDAUTRECENTRALE, $_GET['anneeNewUserHolder'], IDAUTRECENTRALE, $_GET['anneeNewUserHolder']));
    $nbAcaexterne = $manager->getSingle("SELECT count(distinct id) FROM tmpnbnewUserExterne");

    $academiqueExterne = '{name: "' . TXT_ACADEMIQUEEXTERNE . '", data: [{name: "' . TXT_DETAILS . '",y:' . $nbAcaexterne . ',drilldown: "' . TXT_ACADEMIQUEEXTERNE . $_GET['anneeNewUserHolder'] . '"}]},';
    $serieAcademiqueExterne = "{id: '" . TXT_ACADEMIQUEEXTERNE . "',name: '" . TXT_ACADEMIQUEEXTERNE . "',data: [";
    $serieAcademiqueExterne .= "{name: '" . $_GET['anneeNewUserHolder'] . "', y: " . $nbAcaexterne . " , drilldown: '" . TXT_ACADEMIQUEEXTERNE . $_GET['anneeNewUserHolder'] . "'},";
    $serieAcademiqueExterne .= "]},";
    if ($_GET['anneeNewUserHolder'] == 2013) {
        $serie_0AcademiqueExterne = "{id: '" . TXT_ACADEMIQUEEXTERNE . $_GET['anneeNewUserHolder'] . "',name: '" . TXT_ACADEMIQUEEXTERNE . ' ' . TXT_INFERIEUR2013 . "'" . ',data: [';
    } else {
        $serie_0AcademiqueExterne = "{id: '" . TXT_ACADEMIQUEEXTERNE . $_GET['anneeNewUserHolder'] . "',name: '" . TXT_ACADEMIQUEEXTERNE . ' ' . $_GET['anneeNewUserHolder'] . "'" . ',data: [';
    }
    foreach ($centrales as $key => $centrale) {
        $nbUserCentrale = $manager->getSinglebyArray("SELECT   count(distinct id) as nb FROM tmpnbnewUserExterne WHERE libellecentrale=? and datecreation=?", array($centrale[0], $_GET['anneeNewUserHolder']));
        if (empty($nbUserCentrale)) {
            $nbUserCentrale = 0;
        }
        $serie_0AcademiqueExterne .= "{name: '" . $centrale[0]. "',color:'". couleurGraphLib($centrale[0]) . "', y: " . $nbUserCentrale . " , drilldown: '" . TXT_ACADEMIQUEEXTERNE . $centrale[0] . "'},";
    }$serie_0AcademiqueExterne .= ']},';
    /* ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- */
    /*                                                                              ACADEMIQUE INTERNE                                                                                                           */
    /* ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- */
    $manager->exeRequete("drop table if exists tmpnbnewUser");
    $manager->getRequete("create table tmpnbnewUser as (SELECT distinct idutilisateur ,libellecentrale,idcentrale,extract (year from datecreation) as datecreation,"
            . "extract (month from datecreation) as mois  FROM utilisateur,creer,centrale WHERE idutilisateur = idutilisateur_utilisateur AND idcentrale_centrale = idcentrale "
            . "and idqualitedemandeuraca_qualitedemandeuraca  is not null and idcentrale_centrale is not null AND idcentrale_centrale!=? and extract (year from datecreation)<=? "
            . " and masquecentrale!=TRUE group by idutilisateur,datecreation,libellecentrale,idcentrale,mois "
            . " union "
            . "SELECT distinct  u.idutilisateur ,libellecentrale,idcentrale,extract (year from datecreation) as datecreation,extract (month from datecreation) as mois  "
            . "FROM utilisateur u,utilisateuradministrateur ua,centrale WHERE ua.idutilisateur = u.idutilisateur AND idcentrale_centrale = idcentrale AND idqualitedemandeuraca_qualitedemandeuraca is not null "
            . "and idcentrale_centrale is not null AND idcentrale_centrale!=? and extract (year from datecreation) <=? and masquecentrale!=TRUE group by u.idutilisateur,datecreation,libellecentrale,idcentrale,mois"
            . " union "
            . "SELECT distinct  idutilisateur ,libellecentrale,idcentrale,extract (year from datecreation) as datecreation,extract (month from datecreation) as mois FROM utilisateur,utilisateurporteurprojet,centrale "
            . "WHERE idutilisateur = idutilisateur_utilisateur AND idcentrale_centrale = idcentrale AND idqualitedemandeuraca_qualitedemandeuraca is not null and idcentrale_centrale is not null AND idcentrale_centrale!=? "
            . "and extract (year from datecreation) <=? and masquecentrale!=TRUE group by idutilisateur,datecreation,libellecentrale,idcentrale order by datecreation asc)", array(IDAUTRECENTRALE, $_GET['anneeNewUserHolder'], IDAUTRECENTRALE, $_GET['anneeNewUserHolder'], IDAUTRECENTRALE, $_GET['anneeNewUserHolder']));


    $totalUser = $manager->getList("select libellecentrale,count(idutilisateur)as nb from tmpnbnewUser group by idcentrale,libellecentrale order by idcentrale asc;");
    $nbInterne = $manager->getSingle("select count(idutilisateur) from tmpnbnewUser");
    $serie = "";
    for ($i = 0; $i < count($totalUser); $i++) {
        if (empty($totalUser[$i]['nb'])) {
            $totalUser[$i]['nb'] = 0;
        }
        $serie .= '{name: "' . $totalUser[$i]['libellecentrale'] . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $totalUser[$i]['nb'] . ',' . 'drilldown: "' . $totalUser[$i]['libellecentrale'] . $_GET['anneeNewUserHolder'] . '"}]},';
    } $serie_01 = $serie . $industriel . $academiqueExterne;
    $serie_1 = str_replace("},]}", "}]}", $serie_01);
    $serieX = substr($serie_1, 0, -1);
    //----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------   
    $serie_01 = "";
    foreach ($centrales as $key => $centrale) {
        if ($_GET['anneeNewUserHolder']==2013) {
            $serie_01 .= "{id: '" . $centrale[0] . $_GET['anneeNewUserHolder'] . "',name: '" . $centrale[0] . ' ' . TXT_INFERIEUR2013 . "'" . ',data: [';
        } else {
            $serie_01 .= "{id: '" . $centrale[0] . $_GET['anneeNewUserHolder'] . "',name: '" . $centrale[0] . ' ' . $_GET['anneeNewUserHolder'] . "'" . ',data: [';
        }

        for ($mois = 1; $mois < 13; $mois++) {                
            $nbUsercentrale = $manager->getSinglebyArray("select count(idutilisateur) from tmpnbnewUser  WHERE libellecentrale=? and datecreation=? and mois=?", array($centrale[0], $_GET['anneeNewUserHolder'], $mois));
            if (empty($nbUsercentrale)) {
                $nbUsercentrale = 0;
            }
            $serie_01 .= "['" . showMonth($mois, $lang) . "'," . $nbUsercentrale . "],";
        }
        $serie_01 .= ']},';
    }
    $serie11 = str_replace("},]}", "}]}", $serieAcademiqueExterne . $serie_01 . $serieIndustriel . $serie_0AcademiqueExterne . $serie_0Industriel);
    $serieY = substr($serie11, 0, -1);
    if ($_GET['anneeNewUserHolder'] == 2013) {
        $title = TXT_NEWUSERBYDATEYEAR . ' ' . TXT_INFERIEUR2013;
    } else {
        $title = TXT_NEWUSERBYDATEYEAR . $_GET['anneeNewUserHolder'];
    }
} elseif (IDTYPEUSER == ADMINNATIONNAL && !isset($_GET['anneeNewUserHolder'])) {
    ?>
    <table>
        <tr>
            <td>
                <select  id="anneeNewUserHolder" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'anneeNewUserHolder',value: '',required:false,placeHolder: '<?php echo TXT_SELECTYEAR; ?>'" 
                         style="width: 250px;margin-left:35px" 
                         onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/statistiqueNouveauPorteurProjet/<?php echo $lang . '/'; ?>' + this.value)" >
    <?php
    for ($i = 0; $i < count($arraydate); $i++) {
        echo '<option value="' . ($arraydate[$i]['anneedatecreation']) . '">' . $arraydate[$i]['anneedatecreation'] . '</option>';
    }
    ?>
                </select>
            </td>
        </tr>
    </table>

    <?php
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------        
//                                                                              ACADEMIQUE EXTERNE
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    $manager->exeRequete("drop table if exists tmpnbnewUserExterne");
    $manager->getRequete("create table tmpnbnewUserExterne as (SELECT distinct u.idutilisateur as id,libellecentrale, extract (year from datecreation) as datecreation,extract (month from datecreation) as mois "
            . "FROM utilisateur u,creer c,concerne co, centrale ce WHERE idutilisateur = idutilisateur_utilisateur AND co.idprojet_projet = c.idprojet_projet AND  ce.idcentrale= co.idcentrale_centrale "
            . "and idqualitedemandeuraca_qualitedemandeuraca is not null and u.idcentrale_centrale is null AND co.idcentrale_centrale!=? and masquecentrale!=TRUE group by libellecentrale,datecreation,mois,id "
            . " union "
            . "SELECT distinct u.idutilisateur as id,"
            . "libellecentrale, extract (year from datecreation) as datecreation,extract (month from datecreation) as mois FROM utilisateur u,utilisateuradministrateur ua,creer c,concerne co, centrale ce "
            . "WHERE u.idutilisateur = idutilisateur_utilisateur AND co.idprojet_projet = c.idprojet_projet AND  ce.idcentrale= co.idcentrale_centrale AND ua.idutilisateur = u.idutilisateur "
            . "AND idqualitedemandeuraca_qualitedemandeuraca is not null and u.idcentrale_centrale is null AND co.idcentrale_centrale!=? and ce.masquecentrale!=TRUE  group by libellecentrale,datecreation,mois,id "
            . " union "
            . "SELECT distinct u.idutilisateur as id,libellecentrale, extract (year from datecreation) as datecreation,extract (month from datecreation) as mois FROM utilisateur u,"
            . "utilisateurporteurprojet up,creer c,concerne co, centrale ce "
            . "WHERE u.idutilisateur = up.idutilisateur_utilisateur AND  ce.idcentrale= co.idcentrale_centrale AND   up.idutilisateur_utilisateur = co.idprojet_projet "
            . "AND u.idqualitedemandeuraca_qualitedemandeuraca is not null "
            . "and u.idcentrale_centrale is null AND co.idcentrale_centrale!=? and ce.masquecentrale!=TRUE  group by libellecentrale,datecreation,mois,id order by libellecentrale,datecreation,mois,id )", 
            array(IDAUTRECENTRALE, IDAUTRECENTRALE, IDAUTRECENTRALE));
    $nbAcaexterne = $manager->getSingle("SELECT count(distinct id) FROM tmpnbnewUserExterne");
    $academiqueExterne = '{name: "' . TXT_ACADEMIQUEEXTERNE . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbAcaexterne . ',drilldown: "' . TXT_ACADEMIQUEEXTERNE . '"}]},';
    $serieAcademiqueExterne = "";
    foreach ($years as $key => $year) {
        $serieAcademiqueExterne .= "{id: '" . TXT_ACADEMIQUEEXTERNE . "',name: '" . TXT_ACADEMIQUEEXTERNE . "',data: [";
        foreach ($years as $key => $year) {
            $NbAcademiqueExterne = $manager->getSingle2("SELECT count(distinct id) as nb FROM tmpnbnewUserExterne WHERE datecreation =?", $year[0]);
            $serieAcademiqueExterne .= "{name: '" . $year[0] . "', y: " . $NbAcademiqueExterne . " , drilldown: '" . TXT_ACADEMIQUEEXTERNE . $year[0] . "'},";
        }$serieAcademiqueExterne .= "]},";
    }
    $serie_0AcademiqueExterne = "";
    foreach ($years as $key => $year) {
        if ($year[0] == 2013) {
            $serie_0AcademiqueExterne .= "{id: '" . TXT_ACADEMIQUEEXTERNE . $year[0] . "',name: '" . TXT_ACADEMIQUEEXTERNE . ' ' . TXT_INFERIEUR2013 . "'" . ',data: [';
        } else {
            $serie_0AcademiqueExterne .= "{id: '" . TXT_ACADEMIQUEEXTERNE . $year[0] . "',name: '" . TXT_ACADEMIQUEEXTERNE . ' ' . $year[0] . "'" . ',data: [';
        }
        foreach ($centrales as $key => $centrale) {
            if ($year[0] == 2013) {
                $nbUser = $manager->getSinglebyArray("SELECT count(distinct id) as nb FROM tmpnbnewUserExterne WHERE datecreation=? and libellecentrale<=?", array($year[0], $centrale[0]));
            } else {
                $nbUser = $manager->getSinglebyArray("SELECT count(distinct id) as nb FROM tmpnbnewUserExterne WHERE datecreation=? and libellecentrale=?", array($year[0], $centrale[0]));
            }
            if (empty($nbUser)) {
                $nbUser = 0;
            }
            $serie_0AcademiqueExterne .= "{name: '" . $centrale[0]. "',color:'". couleurGraphLib($centrale[0]) . "', y: " . $nbUser . " , drilldown: '" . TXT_ACADEMIQUEEXTERNE . $centrale[0] . "'},";
        }$serie_0AcademiqueExterne .= ']},';
    }
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------        
//                                                                              INDUSTRIEL
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    $manager->exeRequete("drop table if exists tmpnbnewUserIndust");
    $manager->exeRequete("create table tmpnbnewUserIndust as (SELECT distinct idutilisateur as id,libellecentrale, extract (year from datecreation) as datecreation,extract (month from datecreation) as mois  "
            . "FROM utilisateur,creer c, concerne co, centrale ce WHERE idutilisateur = idutilisateur_utilisateur AND co.idprojet_projet = c.idprojet_projet AND  ce.idcentrale= co.idcentrale_centrale "
            . "and idqualitedemandeurindust_qualitedemandeurindust is not null and ce.masquecentrale!=TRUE group by libellecentrale,idutilisateur ,datecreation,mois);");
    $Nbindustriel = $manager->getSingle("SELECT count(distinct id) as nb FROM tmpnbnewUserIndust");
    
    $industriel = '{name: "' . TXT_INDUSTRIEL . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $Nbindustriel . ',drilldown: "' . TXT_INDUSTRIEL . '"}]},';
    
    $serieIndustriel = "";
    
        $serieIndustriel .= "{id: '" . TXT_INDUSTRIEL . "',name: '" . TXT_INDUSTRIEL . "',data: [";        
        foreach ($years as $key => $year) {
            $Nbindustriel = $manager->getSingle2("SELECT count(distinct id) FROM tmpnbnewUserIndust where datecreation =?", $year[0]);
            if ($year[0] == 2013) {
                $serieIndustriel .= "{name: '" . TXT_INFERIEUR2013 . "', y: " . $Nbindustriel . " , drilldown: '" . TXT_INDUSTRIEL . $year[0] . "'},";
            } else {
                $serieIndustriel .= "{name: '" . $year[0] . "', y: " . $Nbindustriel . " , drilldown: '" . TXT_INDUSTRIEL . $year[0] . "'},";
            }
        }$serieIndustriel .= "]},";
    $serie_0Industriel = "";
    foreach ($years as $key => $year) {
        if ($year[0] == 2013) {
            $serie_0Industriel .= "{id: '" . TXT_INDUSTRIEL . $year[0] . "',name: '" . TXT_INDUSTRIEL . ' ' . TXT_INFERIEUR2013 . "'" . ',data: [';
        } else {
            $serie_0Industriel .= "{id: '" . TXT_INDUSTRIEL . $year[0] . "',name: '" . TXT_INDUSTRIEL . ' ' . $year[0] . "'" . ',data: [';
        }
        foreach ($centrales as $key => $centrale) {
            if ($year[0] == 2013) {
                $nbUser = $manager->getSinglebyArray("SELECT count(distinct id) as nb FROM tmpnbnewUserIndust where datecreation=? and libellecentrale=?", array($year[0], $centrale[0]));
            } else {
                $nbUser = $manager->getSinglebyArray("SELECT count(distinct id) as nb FROM tmpnbnewUserIndust where datecreation=? and libellecentrale=?", array($year[0], $centrale[0]));
            }
            if (empty($nbUser)) {
                $nbUser = 0;
            }
            $serie_0Industriel .= "{name: '" . $centrale[0]. "',color:'". couleurGraphLib($centrale[0]) . "', y: " . $nbUser . " , drilldown: '" . TXT_INDUSTRIEL . $centrale[0] . "'},";
            
        }$serie_0Industriel .= ']},';
    }
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------        
//                                                                              ACADEMIQUE INTERNE
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    $serie = "";
    $manager->exeRequete("drop table if exists tmpnbnewUserInterne");
    $manager->getRequete("create table tmpnbnewUserInterne as (SELECT distinct idutilisateur ,libellecentrale,idcentrale,extract (year from datecreation) as datecreation,extract (month from datecreation) as mois,masquecentrale  "
            . "FROM utilisateur,creer,centrale WHERE idutilisateur = idutilisateur_utilisateur AND idcentrale_centrale = idcentrale and idqualitedemandeuraca_qualitedemandeuraca  is not null "
            . "and idcentrale_centrale is not null AND idcentrale_centrale!=? and masquecentrale!=TRUE group by idcentrale,idutilisateur,datecreation,libellecentrale,mois,masquecentrale "
            . " union "
            . "SELECT distinct  u.idutilisateur ,libellecentrale,idcentrale,"
            . "extract (year from datecreation) as datecreation,extract (month from datecreation) as mois,masquecentrale  FROM utilisateur u,utilisateuradministrateur ua,centrale WHERE ua.idutilisateur = u.idutilisateur "
            . "AND idcentrale_centrale = idcentrale AND idqualitedemandeuraca_qualitedemandeuraca is not null and idcentrale_centrale is not null AND idcentrale_centrale!=? and masquecentrale!=TRUE "
            . "group by idcentrale,u.idutilisateur,datecreation,libellecentrale,mois,masquecentrale "
            . " union "
            . "SELECT distinct  idutilisateur ,libellecentrale,idcentrale,extract (year from datecreation) as datecreation,extract (month from datecreation) as mois,masquecentrale "
            . "FROM utilisateur,utilisateurporteurprojet,centrale WHERE idutilisateur = idutilisateur_utilisateur AND idcentrale_centrale = idcentrale AND idqualitedemandeuraca_qualitedemandeuraca is not null "
            . "and idcentrale_centrale is not null AND idcentrale_centrale!=? group by idcentrale,idutilisateur,datecreation,libellecentrale,masquecentrale order by datecreation asc);", 
            array(IDAUTRECENTRALE, IDAUTRECENTRALE, IDAUTRECENTRALE));
    $totalUser = $manager->getList("select libellecentrale,count(idutilisateur)as nb from tmpnbnewUserInterne group by idcentrale,libellecentrale order by idcentrale asc;");


    $nbInterne = $manager->getSingle("select count(idutilisateur) from tmpnbnewUserInterne");
    for ($i = 0; $i < count($totalUser); $i++) {
        if (empty($totalUser[$i]['nb'])) {
            $totalUser[$i]['nb'] = 0;
        }//
        $serie .= '{name: "' . $totalUser[$i]['libellecentrale'] . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $totalUser[$i]['nb'] . ',drilldown: "' . $totalUser[$i]['libellecentrale'] . '"}]},';
    }$serie_01 = $serie . $industriel . $academiqueExterne;
    $serie_1 = str_replace("},]}", "}]}", $serie_01);
    $serieX = substr($serie_1, 0, -1);

    //------------------------------------------------------------------------------------------------------------------------------------------------------------------------       
    $serie0 = "";
    foreach ($centrales as $key => $centrale) {
        $serie0 .= "{id: '" . $centrale[0] . "',name: '" . $centrale[0] . "',data: [";
        foreach ($years as $key => $year) {
            $nbByYear = $manager->getSinglebyArray("SELECT count(idutilisateur) FROM  tmpnbnewUserInterne  WHERE libellecentrale=? and datecreation=?", array($centrale[0], $year[0]));
            if (empty($nbByYear)) {
                $nbByYear = 0;
            }
            if ($year[0] == 2013) {
                $serie0 .= "{name: '" . TXT_INFERIEUR2013 . "', y: " . $nbByYear . " , drilldown: '" . $centrale[0] . $year[0] . "'},";
            } else {
                $serie0 .= "{name: '" . $year[0] . "', y: " . $nbByYear . " , drilldown: '" . $centrale[0] . $year[0] . "'},";
            }
        }$serie0 .= "]},";
    }
    $serie_01 = "";
    foreach ($centrales as $key => $centrale) {
        foreach ($years as $key => $year) {
            $nbByYear = $manager->getSinglebyArray("SELECT count(idutilisateur) FROM  tmpnbnewUserInterne WHERE  libellecentrale=? and datecreation<=?", array($centrale[0], $year[0]));
            if ($year[0] == 2013) {
                $serie_01 .= "{id: '" . $centrale[0] . $year[0] . "',name: '" . $centrale[0] . '  ' . TXT_INFERIEUR2013 . "'" . ',data: [';
            } else {
                $serie_01 .= "{id: '" . $centrale[0] . $year[0] . "',name: '" . $centrale[0] . '  ' . $year[0] . "'" . ',data: [';
            }
            for ($mois = 1; $mois < 13; $mois++) {
                $nbUsercentrale = $manager->getSinglebyArray("SELECT count(idutilisateur) FROM  tmpnbnewUserInterne  WHERE libellecentrale=? and datecreation=? and mois=?", array($centrale[0], $year[0], $mois));
                if (empty($nbUsercentrale)) {
                    $nbUsercentrale = 0;
                }
                $serie_01 .= "['" . showMonth($mois, $lang) . "'," . $nbUsercentrale . "],";
            }
            $serie_01 .= ']},';
        }
    }
    $serie11 = str_replace("},]}", "}]}", $serie0 . $serieIndustriel . $serieAcademiqueExterne . $serie_01 . $serie_0Industriel . $serie_0AcademiqueExterne);
    $serieY = substr($serie11, 0, -1);
    $title = TXT_NEWUSERBYDATEYEAR .$currentyear;
    
}


if (IDTYPEUSER == ADMINLOCAL) {
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------        
//                                                                              ACADEMIQUE EXTERNE
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    $manager->exeRequete("drop table if exists tmpnbnewUserExterne");
    $manager->getRequete("create table tmpnbnewUserExterne as (SELECT distinct u.idutilisateur as id,libellecentrale, extract (year from datecreation) as datecreation,extract (month from datecreation) as mois "
            . "FROM utilisateur u,creer c,concerne co, centrale ce WHERE idutilisateur = idutilisateur_utilisateur AND co.idprojet_projet = c.idprojet_projet AND  ce.idcentrale= co.idcentrale_centrale "
            . "and idqualitedemandeuraca_qualitedemandeuraca is not null and u.idcentrale_centrale is null AND co.idcentrale_centrale!=? and co.idcentrale_centrale=? and ce.masquecentrale!=TRUE group by libellecentrale,datecreation,mois,id "
            . " union "
            . "SELECT distinct u.idutilisateur as id,libellecentrale, extract (year from datecreation) as datecreation,extract (month from datecreation) as mois FROM utilisateur u,"
            . "utilisateuradministrateur ua,creer c,"
            . "concerne co, centrale ce WHERE u.idutilisateur = idutilisateur_utilisateur AND co.idprojet_projet = c.idprojet_projet AND  ce.idcentrale= co.idcentrale_centrale AND ua.idutilisateur = u.idutilisateur "
            . "AND idqualitedemandeuraca_qualitedemandeuraca is not null and u.idcentrale_centrale is null AND co.idcentrale_centrale!=? and co.idcentrale_centrale=?  group by libellecentrale,datecreation,mois,id "
            . " union "
            . "SELECT distinct u.idutilisateur as id,libellecentrale, extract (year from datecreation) as datecreation,extract (month from datecreation) as mois FROM utilisateur u,utilisateurporteurprojet up,creer c,"
            . "concerne co, centrale ce WHERE u.idutilisateur = up.idutilisateur_utilisateur AND  ce.idcentrale= co.idcentrale_centrale AND   up.idutilisateur_utilisateur = co.idprojet_projet "
            . "AND u.idqualitedemandeuraca_qualitedemandeuraca is not null  and u.idcentrale_centrale is null AND co.idcentrale_centrale!=? and co.idcentrale_centrale=? group by libellecentrale,datecreation,mois,id order by libellecentrale,"
            . "datecreation,mois,id )", array(IDAUTRECENTRALE, IDCENTRALEUSER, IDAUTRECENTRALE, IDCENTRALEUSER, IDAUTRECENTRALE, IDCENTRALEUSER));
    $nbAcaexterne = $manager->getSingle("SELECT count(distinct id) FROM tmpnbnewUserExterne");
    $academiqueExterne = '{name: "' . TXT_ACADEMIQUEEXTERNE . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbAcaexterne . ',drilldown: "' . TXT_ACADEMIQUEEXTERNE . '"}]},';
    $serieAcademiqueExterne = "";
    foreach ($years as $key => $year) {
        $serieAcademiqueExterne .= "{id: '" . TXT_ACADEMIQUEEXTERNE . "',name: '" . TXT_ACADEMIQUEEXTERNE . "',data: [";
        foreach ($years as $key => $year) {
            $NbAcademiqueExterne = $manager->getSingle2("SELECT count(distinct id) as nb FROM tmpnbnewUserExterne WHERE datecreation=?", $year[0]);
            if ($year[0] == 2013) {
                $serieAcademiqueExterne .= "{name: '" . TXT_INFERIEUR2013 . "', y: " . $NbAcademiqueExterne . " , drilldown: '" . TXT_ACADEMIQUEEXTERNE . $year[0] . "'},";
            } else {
                $serieAcademiqueExterne .= "{name: '" . $year[0] . "', y: " . $NbAcademiqueExterne . " , drilldown: '" . TXT_ACADEMIQUEEXTERNE . $year[0] . "'},";
            }
        }$serieAcademiqueExterne .= "]},";
    }
    $serie_0AcademiqueExterne = "";
    foreach ($years as $key => $year) {
        if ($year[0] == 2013) {
            $serie_0AcademiqueExterne .= "{id: '" . TXT_ACADEMIQUEEXTERNE . $year[0] . "',name: '" . TXT_ACADEMIQUEEXTERNE . ' ' . TXT_INFERIEUR2013 . "'" . ',data: [';
        } else {
            $serie_0AcademiqueExterne .= "{id: '" . TXT_ACADEMIQUEEXTERNE . $year[0] . "',name: '" . TXT_ACADEMIQUEEXTERNE . ' ' . $year[0] . "'" . ',data: [';
        }
        for ($mois = 1; $mois < 13; $mois++) {
            $nbUser = $manager->getSinglebyArray("SELECT count(distinct id) as nb FROM tmpnbnewUserExterne WHERE datecreation=? and mois=?", array($year[0], $mois));
            if (empty($nbUser)) {
                $nbUser = 0;
            }
            $serie_0AcademiqueExterne .= "['" . showMonth($mois, $lang) . "'," . $nbUser . "],";
        }
        $serie_0AcademiqueExterne .= ']},';
    }
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------        
//                                                                              INDUSTRIEL
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    $manager->exeRequete("drop table if exists tmpnbnewUserIndust");
    $manager->getRequete("create table tmpnbnewUserIndust as (SELECT distinct idutilisateur as id,libellecentrale, extract (year from datecreation) as datecreation,extract (month from datecreation) as mois  "
            . "FROM utilisateur,creer c, concerne co, centrale ce WHERE idutilisateur = idutilisateur_utilisateur AND co.idprojet_projet = c.idprojet_projet AND  ce.idcentrale= co.idcentrale_centrale "
            . "and idqualitedemandeurindust_qualitedemandeurindust is not null AND idcentrale=? and ce.masquecentrale!=TRUE group by libellecentrale,idutilisateur ,datecreation,mois);", array(IDCENTRALEUSER));
    $Nbindustriel = $manager->getSingle("SELECT count(distinct id) as nb FROM tmpnbnewUserIndust");
    $industriel = '{name: "' . TXT_INDUSTRIEL . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $Nbindustriel . ',drilldown: "' . TXT_INDUSTRIEL . '"}]},';
    $serieIndustriel = "";
    foreach ($years as $key => $year) {
        $serieIndustriel .= "{id: '" . TXT_INDUSTRIEL . "',name: '" . TXT_INDUSTRIEL . "',data: [";
        foreach ($years as $key => $year) {
            $Nbindustriel = $manager->getSingle2("SELECT count(distinct id) FROM tmpnbnewUserIndust where datecreation =?", $year[0]);
            if ($year[0] == 2013) {
                $serieIndustriel .= "{name: '" . TXT_INFERIEUR2013 . "', y: " . $Nbindustriel . " , drilldown: '" . TXT_INDUSTRIEL . $year[0] . "'},";
            } else {
                $serieIndustriel .= "{name: '" . $year[0] . "', y: " . $Nbindustriel . " , drilldown: '" . TXT_INDUSTRIEL . $year[0] . "'},";
            }
        }$serieIndustriel .= "]},";
    }
    $serie_0Industriel = "";
    foreach ($years as $key => $year) {
        if ($year[0] == 2013) {
            $serie_0Industriel .= "{id: '" . TXT_INDUSTRIEL . $year[0] . "',name: '" . TXT_INDUSTRIEL . ' ' . TXT_INFERIEUR2013 . "'" . ',data: [';
        }else{
            $serie_0Industriel .= "{id: '" . TXT_INDUSTRIEL . $year[0] . "',name: '" . TXT_INDUSTRIEL . ' ' . $year[0] . "'" . ',data: [';
        }
        for ($mois = 1; $mois < 13; $mois++) {
            $nbUser = $manager->getSinglebyArray("SELECT count(distinct id) as nb FROM tmpnbnewUserIndust where datecreation=? and mois=?", array($year[0], $mois));
            if (empty($nbUser)) {
                $nbUser = 0;
            }
            $serie_0Industriel .= "['" . showMonth($mois, $lang) . "'," . $nbUser . "],";
        }
        $serie_0Industriel .= ']},';
    }
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------        
//                                                                              ACADEMIQUE INTERNE
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------    
    $manager->exeRequete("drop table if exists tmpnbnewUserInterne");
    $manager->getRequete("create table tmpnbnewUserInterne as (SELECT distinct idutilisateur ,libellecentrale,extract (year from datecreation) as datecreation,extract (month from datecreation) as mois  "
            . "FROM utilisateur,creer,centrale WHERE idutilisateur = idutilisateur_utilisateur AND idcentrale_centrale = idcentrale and idqualitedemandeuraca_qualitedemandeuraca  is not null "
            . "and idcentrale_centrale is not null AND idcentrale_centrale!=? and masquecentrale!=TRUE group by idutilisateur,datecreation,libellecentrale,mois "
            . " union "
            . "SELECT distinct  u.idutilisateur ,libellecentrale,"
            . "extract (year from datecreation) as datecreation,extract (month from datecreation) as mois  FROM utilisateur u,utilisateuradministrateur ua,centrale WHERE ua.idutilisateur = u.idutilisateur "
            . "AND idcentrale_centrale = idcentrale AND idqualitedemandeuraca_qualitedemandeuraca is not null and idcentrale_centrale is not null AND idcentrale_centrale!=? "
            . "and masquecentrale!=TRUE group by u.idutilisateur,datecreation,libellecentrale,mois "
            . " union "
            . "SELECT distinct  idutilisateur ,libellecentrale,extract (year from datecreation) as datecreation,extract (month from datecreation) as mois "
            . "FROM utilisateur,utilisateurporteurprojet,centrale WHERE idutilisateur = idutilisateur_utilisateur AND idcentrale_centrale = idcentrale AND idqualitedemandeuraca_qualitedemandeuraca is not null "
            . "and idcentrale_centrale is not null AND idcentrale_centrale=? and masquecentrale!=TRUE group by idutilisateur,datecreation,libellecentrale order by datecreation asc);", array(IDAUTRECENTRALE, IDAUTRECENTRALE, IDCENTRALEUSER));
    $nbInterne = $manager->getSingle2("select count(distinct idutilisateur) from tmpnbnewUserinterne where libellecentrale=?", LIBELLECENTRALEUSER);
    $totalUser = $manager->getList2("select libellecentrale,count(idutilisateur)as nb from tmpnbnewUserInterne where libellecentrale=? group by libellecentrale order by libellecentrale asc;", LIBELLECENTRALEUSER);
    $libellecentrale = $manager->getSingle2("select libellecentrale from centrale where idcentrale=? ", IDCENTRALEUSER);
    $serie = "";
    for ($i = 0; $i < count($totalUser); $i++) {
        if (empty($totalUser[$i]['nb'])) {
            $totalUser[$i]['nb'] = 0;
        }
        $serie .= '{name: "' . TXT_ACADEMIQUEINTERNE . '", data: [{name: "' . TXT_DETAILS .
                '",y: ' . $totalUser[$i]['nb'] . ',drilldown: "' . TXT_ACADEMIQUEINTERNE . '"}]},';
    }
    $serie_01 = $serie . $industriel . $academiqueExterne;
    $serie_1 = str_replace("},]}", "}]}", $serie_01);
    $serieX = substr($serie_1, 0, -1);
    //------------------------------------------------------------------------------------------------------------------------------------------------------------------------   
    $serie0 = "{id: '" . TXT_ACADEMIQUEINTERNE . "',name: '" . TXT_ACADEMIQUEINTERNE . "',data: [";
    foreach ($years as $key => $year) {
        $nbByYear = $manager->getSinglebyArray("select count(distinct idutilisateur) from tmpnbnewUserinterne where libellecentrale=? and datecreation =?", array($libellecentrale, $year[0]));
        if (empty($nbByYear)) {
            $nbByYear = 0;
        }
        if($year[0]==2013){
            $serie0 .= "{name: '" . TXT_INFERIEUR2013 . "', y: " . $nbByYear . " , drilldown: '" . TXT_ACADEMIQUEINTERNE . $year[0] . "'},";
        }else{
            $serie0 .= "{name: '" . $year[0] . "', y: " . $nbByYear . " , drilldown: '" . TXT_ACADEMIQUEINTERNE . $year[0] . "'},";
        }
    }$serie0 .= "]},";


    $serie_01 = "";
    foreach ($years as $key => $year) {
        if($year[0]==2013){
            $serie_01 .= "{id: '" . TXT_ACADEMIQUEINTERNE . $year[0] . "',name: '" . TXT_ACADEMIQUEINTERNE . ' ' . TXT_INFERIEUR2013 . "'" . ',data: [';
        }else{
            $serie_01 .= "{id: '" . TXT_ACADEMIQUEINTERNE . $year[0] . "',name: '" . TXT_ACADEMIQUEINTERNE . ' ' . $year[0] . "'" . ',data: [';
        }
        
        for ($mois = 1; $mois < 13; $mois++) {
            $nbUsercentrale = $manager->getSinglebyArray("select count(distinct idutilisateur) from tmpnbnewUserinterne where libellecentrale=? and datecreation=? and mois=?", array($libellecentrale, $year[0], $mois));
            if (empty($nbUsercentrale)) {
                $nbUsercentrale = 0;
            }
            $serie_01 .= "['" . showMonth($mois, $lang) . "'," . $nbUsercentrale . "],";
        }
        $serie_01 .= ']},';
    }
    $serie11 = str_replace("},]}", "}]}", $serie0 . $serieIndustriel . $serieAcademiqueExterne . $serie_01 . $serie_0Industriel . $serie_0AcademiqueExterne);
    $serieY = substr($serie11, 0, -1);

    $title = TXT_NEWUSERBYDATEYEAR .    $currentyear ;   
}

$subtitle = TXT_CLICDETAIL;
$xasisTitle = TXT_NOMBREOCCURRENCE;
include_once 'commun/scriptBar.php';
