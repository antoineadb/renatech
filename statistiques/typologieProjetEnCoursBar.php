<?php
include_once 'class/Manager.php';
include_once 'outils/constantes.php';
$db = BD::connecter();
unset($_SESSION['anneeprojettypeprojet']);
$manager = new Manager($db);
$arraylibellecentrale = $manager->getList("select libellecentrale from centrale where libellecentrale!='Autres' order by idcentrale asc");
$datay = array();
$arraylibelle = array();
$string0 = '';
$string1 = '';
$centrales = $manager->getList2("select libellecentrale,idcentrale from centrale where idcentrale!=?  and masquecentrale!=TRUE  order by idcentrale asc", IDAUTRECENTRALE);
$uneDateUneCentrale = "SELECT count(idprojet) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet  and idtypeprojet=? and idcentrale_centrale=?"
        . " AND idstatutprojet_statutprojet=?";
$touscentraleunedate = "SELECT count(idprojet) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet and idtypeprojet=?  AND idstatutprojet_statutprojet=?";
$toutesdateCentrale = "SELECT count(idprojet) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet and idtypeprojet=?  "
        . " AND idstatutprojet_statutprojet=?  and idcentrale_centrale=?  AND trashed!= ?";
?>

    <?php
    if (IDTYPEUSER == ADMINNATIONNAL) {
        $serieX = "";
        $nbprojetacademique = $manager->getSinglebyArray("SELECT count(idprojet) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet and idtypeprojet=? "
                . " AND idstatutprojet_statutprojet=?", array(ACADEMIC,  ENCOURSREALISATION));
        $nbprojetAcademiquepartenariat = $manager->getSinglebyArray("SELECT count(idprojet) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet and idtypeprojet=? "
                . " AND idstatutprojet_statutprojet=?", array(ACADEMICPARTENARIAT,  ENCOURSREALISATION));
      
        $nbprojetindustriel = $manager->getSinglebyArray("SELECT count(idprojet) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet and idtypeprojet=? "
                . " AND idstatutprojet_statutprojet=?", array(INDUSTRIEL,  ENCOURSREALISATION));
        $formation = $manager->getSinglebyArray("SELECT count(idprojet) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet and idtypeprojet=? "
                . " AND idstatutprojet_statutprojet=?", array(FORMATION,  ENCOURSREALISATION));

        $serieX .= '{name: "' . ucfirst(TXT_ACADEMIQUE) . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbprojetacademique . ',drilldown: "' . "academic" . 'EnCours' . '"}]},';
        $serieX .= '{name: "' . TXT_ACADEMICPARTENARIAT . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbprojetAcademiquepartenariat . ',drilldown: "' . 'academicPartenariat' . 'EnCours' . '"}]},';
        $serieX .= '{name: "' . TXT_INDUSTRIEL . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbprojetindustriel . ',drilldown: "' . 'industriel' . 'EnCours' . '"}]},';
        $serieX .= '{name: "' . TXT_FORMATION . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $formation . ',drilldown: "' . 'formation' . 'EnCours' . '"}]}';

        $serieAcademique = "{id: '" . 'academic' . 'EnCours' . "',name: '" . ucfirst(TXT_ACADEMIQUE) . "',data: [";
        $serieAcademiquePartenariat = "{id: '" . 'academicPartenariat' . 'EnCours' . "',name: '" . TXT_ACADEMICPARTENARIAT . "',data: [";
        $serieIndustriel = "{id: '" . 'industriel' . 'EnCours' . "',name: '" . TXT_INDUSTRIEL . "',data: [";
        $serieFormation = "{id: '" . 'formation' . 'EnCours' . "',name: '" . TXT_FORMATION . "',data: [";

        $nbprojetAcademique = $manager->getSinglebyArray($touscentraleunedate, array( ACADEMIC, ENCOURSREALISATION));
        $nbprojetAcademiquePartenariat = $manager->getSinglebyArray($touscentraleunedate, array( ACADEMICPARTENARIAT, ENCOURSREALISATION));
        $nbprojetIndustriel = $manager->getSinglebyArray($touscentraleunedate, array( INDUSTRIEL, ENCOURSREALISATION));
        $nbprojetFormation = $manager->getSinglebyArray($touscentraleunedate, array( FORMATION, ENCOURSREALISATION));

        $serieAcademique .= "{name: '" . 'EnCours' . "', y: " . $nbprojetAcademique . " , drilldown: '" . 'academic' . 'EnCours' . "'},";
        $serieAcademiquePartenariat .= "{name: '" . 'EnCours' . "', y: " . $nbprojetAcademiquePartenariat . " , drilldown: '" . 'academicPartenariat' . 'EnCours' . "'},";
        $serieIndustriel .= "{name: '" . 'EnCours' . "', y: " . $nbprojetIndustriel . " , drilldown: '" . 'industriel' . 'EnCours' . "'},";
        $serieFormation .= "{name: '" . 'EnCours' . "', y: " . $nbprojetFormation . " , drilldown: '" . 'formation' . 'EnCours' . "'},";

        $serieAcademique .= "]},";
        $serieAcademiquePartenariat .= "]},";
        $serieIndustriel .= "]},";
        $serieFormation .= "]},";

        $serieAcademique .= "{id: '" . 'academic' . 'EnCours' . "',name: '" . ucfirst(TXT_ACADEMIQUE) . "',data: [";
        $serieAcademiquePartenariat .= "{id: '" . 'academicPartenariat' . 'EnCours' . "',name: '" . TXT_ACADEMICPARTENARIAT . "',data: [";
        $serieIndustriel .= "{id: '" . 'industriel' . 'EnCours' . "',name: '" . TXT_INDUSTRIEL . "',data: [";
        $serieFormation .= "{id: '" . 'formation' . 'EnCours' . "',name: '" . TXT_FORMATION . "',data: [";
        foreach ($centrales as $key => $centrale) {
            $nbprojetAcademique = $manager->getSinglebyArray($uneDateUneCentrale, array(ACADEMIC, $centrale[1],  ENCOURSREALISATION));            
            $nbprojetAcademiquePartenariat = $manager->getSinglebyArray($uneDateUneCentrale, array(ACADEMICPARTENARIAT, $centrale[1],  ENCOURSREALISATION));
            $nbprojetIndustriel = $manager->getSinglebyArray($uneDateUneCentrale, array(INDUSTRIEL, $centrale[1],  ENCOURSREALISATION));
            $nbprojetFormation = $manager->getSinglebyArray($uneDateUneCentrale, array(FORMATION, $centrale[1],  ENCOURSREALISATION));
            $serieAcademique .= "{name: '" . $centrale[0] . "',color:'" . couleurGraphLib($centrale[0]) . "', y: " . $nbprojetAcademique . " , drilldown: '" . 'academic' . $centrale[0] . 'EnCours' . "'},";
            $serieAcademiquePartenariat .= "{name: '" . $centrale[0] . "',color:'" . couleurGraphLib($centrale[0]) . "', y: " . $nbprojetAcademiquePartenariat . " , drilldown: '" . 'academicPartenariat' . $centrale[0] . 'EnCours' . "'},";
            $serieIndustriel .= "{name: '" . $centrale[0] . "',color:'" . couleurGraphLib($centrale[0]) . "', y: " . $nbprojetIndustriel . " , drilldown: '" . 'industriel' . $centrale[0] . 'EnCours' . "'},";
            $serieFormation .= "{name: '" . $centrale[0] . "',color:'" . couleurGraphLib($centrale[0]) . "', y: " . $nbprojetFormation . " , drilldown: '" . 'formation' . $centrale[0] . 'EnCours' . "'},";
        }
        $serieAcademique .= "]},";
        $serieAcademiquePartenariat .= "]},";
        $serieIndustriel .= "]},";
        $serieFormation .= "]},";

        $serie0 = $serieAcademique . $serieAcademiquePartenariat . $serieIndustriel . $serieFormation;
        $serie = str_replace("},]", "}]", $serie0);
        $serieY = substr(str_replace("],]", "]]", $serie), 0, -1);
       
        $subtitle = TXT_CLICDETAIL;
        $xasisTitle = "";
    }
    if (IDTYPEUSER == ADMINLOCAL) {

        $nbprojetAcademique = $manager->getSinglebyArray($toutesdateCentrale, array(ACADEMIC, ENCOURSREALISATION, IDCENTRALEUSER,  TRUE));
        $nbprojetAcademiquepartenariat = $manager->getSinglebyArray($toutesdateCentrale, array(ACADEMICPARTENARIAT, ENCOURSREALISATION, IDCENTRALEUSER,  TRUE));
        $nbprojetindustriel = $manager->getSinglebyArray($toutesdateCentrale, array(INDUSTRIEL, ENCOURSREALISATION, IDCENTRALEUSER,  TRUE));
        $formation = $manager->getSinglebyArray($toutesdateCentrale, array(FORMATION, ENCOURSREALISATION, IDCENTRALEUSER,  TRUE));
        $serieX = "";
        $serieX .= '{name: "' . ucfirst(TXT_ACADEMIQUE) . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbprojetAcademique . ',drilldown: "' . "academic" . '"}]},';
        $serieX .= '{name: "' . TXT_ACADEMICPARTENARIAT . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbprojetAcademiquepartenariat . ',drilldown: "' . 'academicPartenariat' . '"}]},';
        $serieX .= '{name: "' . TXT_INDUSTRIEL . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbprojetindustriel . ',drilldown: "' . 'industriel' . '"}]},';
        $serieX .= '{name: "' . TXT_FORMATION . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $formation . ',drilldown: "' . 'formation' . '"}]}';
        
//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- 
        $serieAcademique = "{id: '" . '' . "',name: '" . '' . "',data: [";
        $serieAcademiquePartenariat = "{id: '" . '' . "',name: '" . '' . "',data: [";
        $serieIndustriel = "{id: '" . '' . "',name: '" . '' . "',data: [";
        $serieFormation = "{id: '" . '' . "',name: '" . '' . "',data: [";
        
        $serieAcademique .= "]},";
        $serieAcademiquePartenariat .= "]},";
        $serieIndustriel .= "]},";
        $serieFormation .= "]},";

//---------------------------------------------------------------------------------------   -----------------------------------------------------------------------------------------------------------------------------------        
        $serie0 = $serieAcademique . $serieAcademiquePartenariat . $serieIndustriel . $serieFormation;
        $serie = str_replace("},]", "}]", $serie0);
        $serieY = substr(str_replace("],]", "]]", $serie), 0, -1);
        $subtitle = '';
        $xasisTitle = "";
    }
   
   $title = TXT_TYPOLOGIEPROJETENCOURS;
   
    include_once 'commun/scriptBar.php';
    ?>
</div>
