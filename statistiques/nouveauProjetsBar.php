<?php
include_once 'class/Manager.php';
$db = BD::connecter();
unset($_SESSION['anneeprojet']);
$manager = new Manager($db);
$years = $manager->getList("select distinct EXTRACT(YEAR from dateprojet)as year from projet where   EXTRACT(YEAR from dateprojet)>2012 order by year asc");
$yearMax = $manager->getSingle("select max(EXTRACT(YEAR from dateprojet)) as year from projet");
$yearMin = "2014";
$statutProjets = $manager->getList2("select libellestatutprojet,idstatutprojet from statutprojet where idstatutprojet!=? order by idstatutprojet  asc", TRANSFERERCENTRALE);
$arraydate = $manager->getList("select distinct EXTRACT(YEAR from datecreation) as anneedatecreation from utilisateur order by anneedatecreation asc");
$centrales = $manager->getList2("select libellecentrale,idcentrale from centrale where idcentrale!=? and masquecentrale!= TRUE order by idcentrale asc", IDAUTRECENTRALE);
?>        
<div  method="post" action="<?php echo '/' . REPERTOIRE; ?>/chxStatistique/<?php echo $lang . '/' . IDSTATNOUVEAUPROJET; ?>" id='filtreDuAu' name='filtreDuAu'   data-dojo-type="dijit/form/Form"  >
<script type="dojo/on" data-dojo-event="submit">
    var dateDebut =parseInt(dijit.byId("anneeDu").value);
    var dateFin =parseInt(dijit.byId("anneeAu").value);
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
    if (isset($_POST['anneeDu'])) {
        $anneeDepart = $_POST['anneeDu'];
    } else {
        $anneeDepart = $yearMin;
    }
    if (isset($_POST['anneeAu'])) {
        $anneeFin = $_POST['anneeAu'];
    } else {
        $anneeFin = $yearMax;
    }

    $annee = anneeStatistique($anneeDepart, $anneeFin);
    if ($lang == 'fr') {
        if ($anneeDepart == $anneeFin) {
            $title = TXT_PROJETDATESTATUTANNEE . ' ' . $anneeDepart;
        } else {
            $title = TXT_PROJETDATESTATUTANNEE . ' ' . $anneeDepart . " à " . $anneeFin;
        }
    } else {
        if ($anneeDepart == $anneeFin) {
            $title = TXT_PROJETDATESTATUTANNEE . ' ' . $anneeDepart;
        } else {
            $title = TXT_PROJETDATESTATUTANNEE . ' ' . $anneeDepart . " to " . $anneeFin;
        }
    }
    ?>
    <table class="filterDate">    
        <tr>
            <td valign="middle" style="text-align: left;padding-left:5px;;width:65px"><?php echo "Du" . ':  ' ?></td>
            <td><input id="anneeDu" style="width:135px;height:25px;padding-left: 5px;margin-right: 18px;"  type="text" name="anneeDu"   data-dojo-type="dijit/form/NumberTextBox" 
                       data-dojo-props="constraints:{min:1980,max:2099,places:0},  invalidMessage:'<?php echo TXT_INT; ?>',placeHolder: '<?php echo "Année de début"; ?>'" value="<?php echo $anneeDepart; ?>"

            </td>
            <td valign="middle" style="text-align: left;padding-left:5px;;width:65px"><?php echo "Au" . ':  ' ?></td>
            <td>
                <input id="anneeAu" style="width:135px;height:25px;padding-left: 5px;margin-right: 18px;"  type="text" name="anneeAu"   data-dojo-type="dijit/form/NumberTextBox"
                       data-dojo-props="constraints:{min:1980,max:2099,places:0}, invalidMessage:'<?php echo TXT_INT; ?>',placeHolder: '<?php echo "Année de fin"; ?>'" value="<?php echo $anneeFin; ?>" />
            </td>
            <td>
                <button data-dojo-type="dijit/form/Button" type="submit" style="padding-left: 22px" ><?php echo TXT_ENVOYER; ?></button>
            </td>            
        </tr>       
    </table>
<?php
if (IDTYPEUSER == ADMINNATIONNAL) {
    $serie = "";
    foreach ($centrales as $key => $centrale) {
        $nbProjet = $manager->getSinglebyArray("SELECT count(distinct idprojet) FROM projet,concerne WHERE idprojet_projet = idprojet AND idcentrale_centrale=? and  trashed != ?
			and EXTRACT(YEAR from dateprojet)<=? and EXTRACT(YEAR from dateprojet)>=?", array($centrale[1], TRUE, $anneeFin, $anneeDepart));
        $serie .= '{name: "' . $centrale[0] . '", data: [{name: "' . TXT_DETAILS .
                '",y: ' . $nbProjet . ',drilldown: "' . $centrale[0] . '"}]},';

        $serie1 = str_replace("},]}", "}]}", $serie);
        $serie01 = str_replace("},]", "}]", $serie1);
        $serieX = substr($serie01, 0, -1);
    }
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------    
    $serie02 = '';
    foreach ($centrales as $key => $centrale) {
        $serie02 .= "{id: '" . $centrale[0] . "',name: '" . $centrale[0] . "',data: [";
        foreach ($annee as $key => $year) {
            $nbByYear = $manager->getSinglebyArray("SELECT count(distinct idprojet) FROM projet,concerne WHERE idprojet_projet = idprojet AND idcentrale_centrale=? "
                    . " and EXTRACT(YEAR from dateprojet)<=? and EXTRACT(YEAR from dateprojet)>=?  and  trashed != ?", array($centrale[1], $year,$anneeDepart, TRUE));
            if (empty($nbByYear)) {
                $nbByYear = 0;
            }
            $serie02 .= "{name: '" . $year . "', y: " . $nbByYear . " , drilldown: '" . $centrale[0] . $year . "'},";
           
        }$serie02 .= "]},";
    }
    $serie2 = str_replace("},]}", "}]}", $serie02);
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    $serie3 = "";

    foreach ($centrales as $key => $centrale) {
        foreach ($annee as $key => $year) {
            if (empty($nbByYear)) {
                $nbByYear = 0;
            }
                $nbByYear = $manager->getSinglebyArray("SELECT count(distinct idprojet) FROM projet,concerne WHERE idprojet_projet = idprojet AND idcentrale_centrale=? "
                        . "and extract(year from dateprojet)<=? and  trashed != ?", array($centrale[1], $year, TRUE));
                $serie3 .= "{id: '" . $centrale[0] . $year . "',name: '" . $centrale[0] . ' ' . $year . "'" . ',data: [';
            
            for ($i = 0; $i < count($statutProjets); $i++) {
                $nbByYearByStatut = $manager->getSinglebyArray("SELECT count(distinct idprojet) FROM concerne,projet WHERE idprojet_projet = idprojet  AND idcentrale_centrale = ? AND  extract(year from dateprojet) =? "
                        . "and idstatutprojet_statutprojet=? and  trashed != ?", array($centrale[1], $year, $statutProjets[$i]['idstatutprojet'], TRUE));
                if (empty($nbByYearByStatut)) {
                    $nbByYearByStatut = 0;
                }
                $serie3 .= '["' . $statutProjets[$i]['libellestatutprojet'] . '",' . $nbByYearByStatut . '],';
            }
            $serie3 .= ']},';
        }
    }

    $serie03 = substr($serie2 . $serie3, 0, -1);
    $serieY = str_replace("],]}", "]]}", $serie03);
    $subtitle = TXT_CLICDETAIL;
    
    $xasisTitle = TXT_NOMBREOCCURRENCE;
} elseif (IDTYPEUSER == ADMINLOCAL) {
    $serie = "";
    foreach ($annee as $key => $year) {
        $nbProjet = $manager->getSinglebyArray("SELECT count(idprojet) FROM projet,concerne WHERE idprojet_projet = idprojet AND idcentrale_centrale=?  "
                . "and EXTRACT(YEAR from dateprojet)<=? and  trashed != ? and EXTRACT(YEAR from dateprojet)>=?", array(IDCENTRALEUSER, $year, TRUE,$anneeDepart));
        if ($nbProjet == 0) {
            $nbProjet = 0;
        }
        if ($year == '2013') {
            $serie .= '{name: "' . TXT_INFERIEUR2013 . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbProjet . ',drilldown: "' . $year . '"}]},';
        } else {
            $serie .= '{name: "' . $year . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbProjet . ',drilldown: "' . $year . '"}]},';
        }

        $serie1 = str_replace("},]}", "}]}", $serie);
        $serie01 = str_replace("},]", "}]", $serie1);
        $serieX = substr($serie01, 0, -1);
    }

//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------    
    $serie3 = '';
    foreach ($annee as $key => $year) {
        $nbByYear = $manager->getSinglebyArray("SELECT count(idprojet) FROM projet,concerne WHERE idprojet_projet = idprojet AND idcentrale_centrale=? "
                . "and extract(year from dateprojet)<=? and  trashed != ? and EXTRACT(YEAR from dateprojet)>=?" , array(IDCENTRALEUSER, $year, TRUE,$anneeDepart));
        if (empty($nbByYear)) {
            $nbByYear = 0;
        }
        if ($year == '2013') {
            $serie3 .= "{id: '" . $year . "',name: '" . TXT_INFERIEUR2013 . "'" . ',data: [';
        } else {
            $serie3 .= "{id: '" . $year . "',name: '" . $year . "'" . ',data: [';
        }
        for ($i = 0; $i < count($statutProjets); $i++) {
            $nbByYearByMonth = $manager->getSinglebyArray("SELECT count(idprojet) FROM concerne,projet WHERE idprojet_projet = idprojet  AND idcentrale_centrale = ? "
                    . "AND  extract(year from dateprojet)=? AND idstatutprojet_statutprojet=? and  trashed != ?", array(IDCENTRALEUSER, $year, $statutProjets[$i]['idstatutprojet'], TRUE));
            if (empty($nbByYearByMonth)) {
                $nbByYearByMonth = 0;
            }
            $serie3 .= '["' . $statutProjets[$i]['libellestatutprojet'] . '",' . $nbByYearByMonth . '],';
        }$serie3 .= ']},';
    }
    $serieY0 = str_replace("},]}", "}]}", $serie3);
    $serieY1 = str_replace("],]", "]]", $serieY0);
    $serieY = substr($serieY1, 0, -1);
    $subtitle = TXT_CLICDETAIL;
    
    $xasisTitle = TXT_PROJETDATESTATUT;
}

include_once 'commun/scriptBar.php';
?>
</div>        
