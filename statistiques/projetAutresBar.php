<?php
include_once 'class/Manager.php';
$db = BD::connecter();
unset($_SESSION['anneeprojetautres']);
$manager = new Manager($db);
$libelleCentrale = $manager->getSingle2("select libellecentrale from centrale where idcentrale=?", IDCENTRALEUSER);
$centrales = $manager->getList2("select libellecentrale,idcentrale from centrale where idcentrale!=? and masquecentrale!=TRUE order by idcentrale asc", IDAUTRECENTRALE);
$years = $manager->getList("select distinct EXTRACT(YEAR from dateprojet)as year from projet where   EXTRACT(YEAR from dateprojet)>2012 order by year asc");
$yearMax = $manager->getSingle("select max(EXTRACT(YEAR from dateprojet)) as year from projet");
$yearMin = "2014";
?>
<div  method="post" action="<?php echo '/' . REPERTOIRE; ?>/chxStatistique/<?php echo $lang . '/' . IDSTATPROJETDATETYPE; ?>" id='filtreDuAu1' name='filtreDuAu1'   data-dojo-type="dijit/form/Form"  >
    <script type="dojo/on" data-dojo-event="submit">
        var dateDebut =parseInt(dijit.byId("anneeDepart").value);
        var dateFin =parseInt(dijit.byId("anneeFin").value);
        if (isNaN(dateDebut)) {
        alert('Le format du champ "Date du:" est incorrecte');
        return false;  
        exit();
        }else if(dateDebut<1980 && dateDebut>2099){
        alert("L'année saisie n'est pas dans la plage autorisée ");
        return false;  
        exit();        
        }else if (this.validate()){
        return true;
        }else{
        alert("<?php echo "Valeur non autorisé!"; ?>");
        return false;
        }
    </script>    
    <?php
    if (isset($_POST['anneeDepart'])) {
        $anneeDepart = $_POST['anneeDepart'];
    } else {
        $anneeDepart = $yearMin;
    }
    if (isset($_POST['anneeFin'])) {
        $anneeFin = $_POST['anneeFin'];
    } else {
        $anneeFin = $yearMax;
    }

    $annee = anneeStatistique($anneeDepart, $anneeFin);
    ?>
    <table class="filterDate">    
        <tr>
            <td valign="middle" style="text-align: left;padding-left:5px;;width:65px"><?php echo "Du" . ':  ' ?></td>
            <td><input id="anneeDepart" style="width:135px;height:25px;padding-left: 5px;margin-right: 18px;"  type="text" name="anneeDepart"   data-dojo-type="dijit/form/NumberTextBox" 
                       data-dojo-props="constraints:{min:1980,max:2099,places:0},  invalidMessage:'<?php echo TXT_INT; ?>',placeHolder: '<?php echo "Année de début"; ?>'" value="<?php echo $anneeDepart; ?>"

            </td>
            <td valign="middle" style="text-align: left;padding-left:5px;;width:65px"><?php echo "Au" . ':  ' ?></td>
            <td>
                <input id="anneeFin" style="width:135px;height:25px;padding-left: 5px;margin-right: 18px;"  type="text" name="anneeFin"   data-dojo-type="dijit/form/NumberTextBox"
                       data-dojo-props="constraints:{min:1980,max:2099,places:0}, invalidMessage:'<?php echo TXT_INT; ?>',placeHolder: '<?php echo "Année de fin"; ?>'" value="<?php echo $anneeFin; ?>" />
            </td>
            <td>
                <button data-dojo-type="dijit/form/Button" type="submit" style="padding-left: 22px" ><?php echo TXT_ENVOYER; ?></button>
            </td>            
        </tr>       
    </table>
    <?php
    if (IDTYPEUSER == ADMINNATIONNAL) {
        $subtitle = "";
        $xasisTitle = "";
        $nbtotalprojet = $manager->getSinglebyArray("
            SELECT COUNT(idprojet_projet) 
            FROM projet
            LEFT JOIN concerne ON idprojet=idprojet_projet
            WHERE  trashed !=? 
            AND EXTRACT(YEAR from dateprojet)<=? 
            AND EXTRACT(YEAR from dateprojet)>=? 
            AND idcentrale_centrale !=? ", array(TRUE, $anneeFin, $anneeDepart,IDCENTRALEAUTRE));       
        $nbprojetExogeneExterne = $manager->getSinglebyArray("
            SELECT count(distinct co.idprojet_projet) 
            FROM projet p
            LEFT JOIN creer cr ON cr.idprojet_projet = p.idprojet 
            LEFT JOIN utilisateur u ON u.idutilisateur = cr.idutilisateur_utilisateur 
            LEFT JOIN concerne co ON co.idprojet_projet = p.idprojet 
            WHERE u.idcentrale_centrale IS NULL         
            AND p.idprojet not in(select idprojet_projet from projetpartenaire)  
            AND trashed !=?
            AND EXTRACT(YEAR from dateprojet)<=? 
            and EXTRACT(YEAR from dateprojet)>=? 
            AND co.idcentrale_centrale!=?", array(TRUE, $anneeFin, $anneeDepart, IDCENTRALEAUTRE));
        $nbprojetExogeneCollaboratif = $manager->getSinglebyArray("
            SELECT count(distinct co.idprojet_projet) 
            FROM  projet p
            LEFT JOIN projetpartenaire pr ON pr.idprojet_projet = p.idprojet
            LEFT JOIN creer cr ON cr.idprojet_projet = p.idprojet 
            LEFT JOIN concerne co ON co.idprojet_projet = p.idprojet 
            LEFT JOIN utilisateur u ON u.idutilisateur = cr.idutilisateur_utilisateur 
            WHERE u.idcentrale_centrale IS NOT NULL 
            AND p.trashed !=? 
            AND EXTRACT(YEAR from dateprojet)<=? 
            AND EXTRACT(YEAR from dateprojet)>=? 
            AND co.idcentrale_centrale!=?", array(TRUE, $anneeFin, $anneeDepart, IDCENTRALEAUTRE));
        $nbprojetInterne = $nbtotalprojet - ($nbprojetExogeneExterne + $nbprojetExogeneCollaboratif);
        $serieX = '{name: "' . TXT_PROJETINTERNE . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbprojetInterne . ',drilldown: "' . TXT_PROJETINTERNE . '"}]},';
        $serieX .= '{name: "' . TXT_PROJETEXOEXTERNE . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbprojetExogeneExterne . ',drilldown: "' . "externe" . '"}]},';
        $serieX .= '{name: "' . TXT_PROJETEXOCOLLABORATIF . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbprojetExogeneCollaboratif . ',drilldown: "' . 'collaboratif' . '"}]}';
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------    

        $serieExterneCentrale = "{id: '" . 'externe' . "',name: '" . TXT_PROJETEXOEXTERNE . "',data: [";
        foreach ($centrales as $key => $centrale) {
            $nbprojetExogeneExterne = $manager->getSinglebyArray("SELECT count(distinct co.idprojet_projet) FROM creer cr,projet p,utilisateur u,concerne co WHERE cr.idprojet_projet = p.idprojet 
                        AND u.idutilisateur = cr.idutilisateur_utilisateur  
                        AND  u.idcentrale_centrale is null and  co.idprojet_projet = p.idprojet                        
                        AND p.idprojet not in(select idprojet_projet from projetpartenaire ) 
                        AND co.idcentrale_centrale=? and trashed !=? and EXTRACT(YEAR from dateprojet)<=? and EXTRACT(YEAR from dateprojet)>=?
                         ", array( $centrale[1],TRUE,$anneeFin,$anneeDepart));
            $serieExterneCentrale .="{name: '" . $centrale[0] .  "',color:'". couleurGraphLib($centrale[0])."', y: " . $nbprojetExogeneExterne . " , drilldown: '" . 'externe' . $libelleCentrale ."'},";
        }$serieExterneCentrale .="]},";

        //--------------------------------------------------------------------

        $serieCollaboratifCentrale = "";

        $serieCollaboratifCentrale .= "{id: '" . 'collaboratif' . "',name: '" . TXT_PROJETEXOCOLLABORATIF . "',data: [";
        foreach ($centrales as $key => $centrale) {
            $nbprojetExogeneCollaboratif = $manager->getSinglebyArray("SELECT count(distinct co.idprojet_projet) 
            FROM  projet p, projetpartenaire pr, utilisateur u, creer c,concerne co
            WHERE p.idprojet = c.idprojet_projet 
            AND pr.idprojet_projet = p.idprojet 
            AND c.idutilisateur_utilisateur = u.idutilisateur 
            AND co.idprojet_projet = p.idprojet 
            AND u.idcentrale_centrale IS NOT NULL             
            AND co.idcentrale_centrale=? and trashed !=? 
            AND  EXTRACT(YEAR from dateprojet)<=? and EXTRACT(YEAR from dateprojet)>=?", array($centrale[1], TRUE, $anneeFin, $anneeDepart));
            $serieCollaboratifCentrale .= "{name: '" . $centrale[0] . "',color:'" . couleurGraphLib($centrale[0]) . "', y: " . $nbprojetExogeneCollaboratif . " , drilldown: '" . 'collaboratif' . $centrale[0] . "'},";
        }$serieCollaboratifCentrale .= "]},";


        //--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
        $serieInterneCentrale = "";

        $serieInterneCentrale .= "{id: '" . TXT_PROJETINTERNE . "',name: '" . TXT_PROJETINTERNE . "',data: [";
        foreach ($centrales as $key => $centrale) {
            $nbprojetInterne = $manager->getSinglebyArray("
            SELECT COUNT(*) 
            FROM projet p
            LEFT JOIN creer cr ON p.idprojet= cr.idprojet_projet
            LEFT JOIN concerne co ON p.idprojet= co.idprojet_projet
            LEFT JOIN utilisateur u ON u.idutilisateur = cr.idutilisateur_utilisateur 
            WHERE co.idcentrale_centrale = ?
            AND u.idcentrale_centrale = ?
            AND EXTRACT (YEAR FROM dateprojet) between ? AND ?
            and p.trashed != ?", array($centrale[1],$centrale[1],$anneeDepart,$anneeFin,TRUE));
            $nbtotalprojet = $manager->getSinglebyArray("
            SELECT COUNT(distinct idprojet_projet) 
            FROM concerne,projet where idprojet_projet=idprojet 
            AND idcentrale_centrale=? and trashed !=? 
            AND EXTRACT(YEAR from dateprojet)<=? 
            AND EXTRACT(YEAR from dateprojet)>=?", array($centrale[1], TRUE, $anneeFin, $anneeDepart));
            $nbprojetExogeneExterne = $manager->getSinglebyArray("
            SELECT count(distinct co.idprojet_projet) 
            FROM creer cr,projet p,utilisateur u,concerne co 
            WHERE cr.idprojet_projet = p.idprojet 
            AND  u.idutilisateur = cr.idutilisateur_utilisateur 
            AND  u.idcentrale_centrale is null 
            AND  co.idprojet_projet = p.idprojet 
            AND p.idprojet not in(select idprojet_projet from projetpartenaire )
            AND co.idcentrale_centrale=? 
            AND trashed !=? 
            AND EXTRACT(YEAR from dateprojet)<=? 
            AND EXTRACT(YEAR from dateprojet)>=?", array($centrale[1], TRUE, $anneeFin, $anneeDepart));
            $nbprojetExogeneCollaboratif = $manager->getSinglebyArray("SELECT count(distinct co.idprojet_projet)
            FROM  projet p, projetpartenaire pr, utilisateur u, creer c,concerne co 
            WHERE p.idprojet = c.idprojet_projet 
            AND pr.idprojet_projet = p.idprojet
            AND c.idutilisateur_utilisateur = u.idutilisateur 
            AND  co.idprojet_projet = p.idprojet 
            And u.idcentrale_centrale IS NOT NULL                 
            AND co.idcentrale_centrale=? 
            AND trashed !=? 
            AND EXTRACT(YEAR from dateprojet)<=? 
            AND EXTRACT(YEAR from dateprojet)>=?", array($centrale[1], TRUE, $anneeFin, $anneeDepart));         
            $serieInterneCentrale .= "{name: '" . $centrale[0] . "',color:'" . couleurGraphLib($centrale[0]) . "', y: " . $nbprojetInterne . " , drilldown: '" . TXT_PROJETINTERNE . $centrale[0] . "'},";
        }$serieInterneCentrale .= "]},";

        $serieY0 = $serieExterneCentrale . $serieCollaboratifCentrale . $serieInterneCentrale;
        $serieY = substr(str_replace("],]", "]]", $serieY0), 0, -1);
        if ($lang == 'fr') {
            if ($anneeDepart == $anneeFin) {
                $title = TXT_PROJETPARDATETYPE . ' en ' . $anneeDepart;
            } else {
                $title = TXT_PROJETPARDATETYPE . ' entre ' . $anneeDepart . " et " . $anneeFin;
            }
        } else {
            if ($anneeDepart == $anneeFin) {
                $title = TXT_PROJETPARDATETYPE . ' in' . $anneeDepart;
            } else {
                $title = TXT_PROJETPARDATETYPE . ' between' . $anneeDepart . " and " . $anneeFin;
            }
        }
    }

    if (IDTYPEUSER == ADMINLOCAL) {
        $nbtotalprojet = $manager->getSinglebyArray(
                " SELECT COUNT(idprojet) 
                  FROM projet  
                  LEFT JOIN concerne ON idprojet=idprojet_projet 
                  WHERE idcentrale_centrale=? 
                  AND trashed !=? 
                  AND EXTRACT(YEAR from dateprojet)<=? 
                  AND EXTRACT(YEAR from dateprojet)>=?", array(IDCENTRALEUSER, TRUE, $anneeFin, $anneeDepart));
        if ($lang == 'fr') {
            if ($anneeDepart == $anneeFin) {
                $title = TXT_PROJETPARDATETYPE . ' en ' . $anneeDepart;
            } else {
                $title = TXT_PROJETPARDATETYPE . ' entre ' . $anneeDepart . " et " . $anneeFin;
            }
        } else {
            if ($anneeDepart == $anneeFin) {
                $title = TXT_PROJETPARDATETYPE . ' in' . $anneeDepart;
            } else {
                $title = TXT_PROJETDATESTATUTANNEE . ' between' . $anneeDepart . " and " . $anneeFin;
            }
        }
        $subtitle = "";
        $xasisTitle = "";

        $nbprojetExogeneExterne = $manager->getSinglebyArray("
            SELECT count(distinct p.idprojet) 
            FROM projet p
            LEFT JOIN creer cr ON cr.idprojet_projet = p.idprojet 
            LEFT JOIN concerne co ON co.idprojet_projet = p.idprojet
            LEFT JOIN utilisateur u ON u.idutilisateur = cr.idutilisateur_utilisateur 
            WHERE   u.idcentrale_centrale is null             
            AND p.idprojet not in(select idprojet_projet from projetpartenaire ) 
            AND co.idcentrale_centrale=? 
            AND trashed !=? 
            AND EXTRACT(YEAR from dateprojet)<=? 
            AND EXTRACT(YEAR from dateprojet)>=?"
                , array(IDCENTRALEUSER, TRUE, $anneeFin, $anneeDepart));
        
        $nbprojetExogeneCollaboratif = $manager->getSinglebyArray(
                " SELECT count(distinct co.idprojet_projet) 
                  FROM  projet p, projetpartenaire pr, utilisateur u, creer c,concerne co 
                  WHERE p.idprojet = c.idprojet_projet 
                  AND pr.idprojet_projet = p.idprojet 
                  AND c.idutilisateur_utilisateur = u.idutilisateur 
                  AND  co.idprojet_projet = p.idprojet 
                  And u.idcentrale_centrale IS NOT NULL 
                  AND co.idcentrale_centrale=? 
                  AND trashed !=? AND extract(year from dateprojet)>=? AND extract(year from dateprojet)<=?",array(IDCENTRALEUSER,TRUE,$anneeDepart,$anneeFin));
        $nbprojetInterne = $manager->getSinglebyArray("
                  SELECT COUNT(*) 
                  FROM projet p
                  LEFT JOIN creer cr ON p.idprojet= cr.idprojet_projet
                  LEFT JOIN concerne co ON p.idprojet= co.idprojet_projet
                  LEFT JOIN utilisateur u ON u.idutilisateur = cr.idutilisateur_utilisateur 
                  WHERE co.idcentrale_centrale = ?
                  AND u.idcentrale_centrale = ?
                  AND EXTRACT (YEAR FROM dateprojet) between ? AND ?
                  and p.trashed != ?", array(IDCENTRALEUSER,IDCENTRALEUSER,$anneeDepart,$anneeFin,TRUE));
        $serieX = '{name: "' . TXT_PROJETINTERNE . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbprojetInterne . ',drilldown: "' . TXT_PROJETINTERNE . '"}]},';
        $serieX .= '{name: "' . TXT_PROJETEXOEXTERNE . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbprojetExogeneExterne . ',drilldown: "' . "externe" . '"}]},';
        $serieX .= '{name: "' . TXT_PROJETEXOCOLLABORATIF . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbprojetExogeneCollaboratif . ',drilldown: "' . 'collaboratif' . '"}]}';
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------    
        //--------------------------------------------------------------------------------------------------------------------------------------------------------------------------
        //--------------------------------------------------------------------------------------------------------------------------------------------------------------------------    

        $serieY0 = '';
        $serieY = substr(str_replace("],]", "]]", $serieY0), 0, -1);
    }
    include_once 'commun/scriptBar.php';
    ?>
</div>
