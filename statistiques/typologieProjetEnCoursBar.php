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
        . " AND idstatutprojet_statutprojet=? AND EXTRACT(YEAR from dateprojet)>2012 ";
$touscentraleunedate = "SELECT count(idprojet) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet and idtypeprojet=?  AND idstatutprojet_statutprojet=?"
        . " AND EXTRACT(YEAR from dateprojet)>2012";
$toutesdateCentrale = "SELECT count(idprojet) FROM concerne,projet,typeprojet WHERE idprojet_projet = idprojet and idtypeprojet_typeprojet = idtypeprojet and idtypeprojet=?  "
        . " AND idstatutprojet_statutprojet=?  and idcentrale_centrale=?  AND trashed!= ? AND EXTRACT(YEAR from dateprojet)>2012 ";
function reqAdminNationnal($typeprojet,$manager){
	return $manager->getSinglebyArray("
	SELECT count(idprojet) 
	FROM projet
	LEFT JOIN concerne ON idprojet_projet = idprojet
	LEFT JOIN typeprojet  ON idtypeprojet_typeprojet = idtypeprojet 
	WHERE idstatutprojet_statutprojet=?  AND EXTRACT(YEAR from dateprojet)>?
	and idtypeprojet=?",array(ENCOURSREALISATION,2012,$typeprojet));
}	
    if (IDTYPEUSER == ADMINNATIONNAL) {
        $serieX = "";
        $nbProjetacademique = reqAdminNationnal(ACADEMIC,$manager);
        $nbProjetAcademiquepartenariat = reqAdminNationnal(ACADEMICPARTENARIAT,$manager);
        $nbProjetIndustriel = reqAdminNationnal(INDUSTRIEL,$manager);
        $nbProjetFormation = reqAdminNationnal(FORMATION,$manager);
        $nbProjetMaintenance= reqAdminNationnal(MAINTENANCE,$manager);
        $service= reqAdminNationnal(SERVICE,$manager);

        $serieX .= '{name: "' . ucfirst(TXT_ACADEMIQUE) . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbProjetacademique . ',drilldown: "' . "academic"  . '"}]},';
        $serieX .= '{name: "' . TXT_ACADEMICPARTENARIAT . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbProjetAcademiquepartenariat . ',drilldown: "' . 'academicPartenariat'  . '"}]},';
        $serieX .= '{name: "' . TXT_INDUSTRIEL . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbProjetIndustriel . ',drilldown: "' . 'industriel'  . '"}]},';
        $serieX .= '{name: "' . TXT_FORMATION . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbProjetFormation . ',drilldown: "' . 'formation'  . '"}]},';
        $serieX .= '{name: "' . TXT_MAINTENANCE . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbProjetMaintenance . ',drilldown: "' . 'maintenance'  . '"}]},';
        $serieX .= '{name: "' . TXT_SERVICE . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $service . ',drilldown: "' . 'service'. '"}]}';

        $serieAcademique = "{id: '" . 'academic'  . "',name: '" . ucfirst(TXT_ACADEMIQUE) . "',data: [";
        $serieAcademiquePartenariat = "{id: '" . 'academicPartenariat'  . "',name: '" . TXT_ACADEMICPARTENARIAT . "',data: [";
        $serieIndustriel = "{id: '" . 'industriel'  . "',name: '" . TXT_INDUSTRIEL . "',data: [";
        $serieFormation = "{id: '" . 'formation'  . "',name: '" . TXT_FORMATION . "',data: [";
        $serieMaintenance = "{id: '" . 'maintenance' .  "',name: '" . TXT_MAINTENANCE . "',data: [";
        $serieService = "{id: '" . 'service' .  "',name: '" . TXT_SERVICE . "',data: [";

        $nbProjetAcademique = $manager->getSinglebyArray($touscentraleunedate, array( ACADEMIC, ENCOURSREALISATION));
        $nbProjetAcademiquePartenariat = $manager->getSinglebyArray($touscentraleunedate, array( ACADEMICPARTENARIAT, ENCOURSREALISATION));
        $nbProjetIndustriel = $manager->getSinglebyArray($touscentraleunedate, array( INDUSTRIEL, ENCOURSREALISATION));
        $nbProjetFormation = $manager->getSinglebyArray($touscentraleunedate, array( FORMATION, ENCOURSREALISATION));
        $nbProjetMaintenance = $manager->getSinglebyArray($touscentraleunedate, array( MAINTENANCE, ENCOURSREALISATION));
        $nbProjetService = $manager->getSinglebyArray($touscentraleunedate, array( SERVICE, ENCOURSREALISATION));

        $serieAcademique .= "{name: '"  . "', y: " . $nbProjetAcademique . " , drilldown: '" . 'academic'  . "'},";
        $serieAcademiquePartenariat .= "{name: '"  . "', y: " . $nbProjetAcademiquePartenariat . " , drilldown: '" . 'academicPartenariat'  . "'},";
        $serieIndustriel .= "{name: '"  . "', y: " . $nbProjetIndustriel . " , drilldown: '" . 'industriel'  . "'},";
        $serieFormation .= "{name: '"  . "', y: " . $nbProjetFormation . " , drilldown: '" . 'formation'  . "'},";
        $serieMaintenance .= "{name: '"  . "', y: " . $nbProjetMaintenance . " , drilldown: '" . 'maintenance'  . "'},";
        $serieService .= "{name: '"  . "', y: " . $nbProjetService . " , drilldown: '" . 'service'  . "'},";

        $serieAcademique .= "]},";
        $serieAcademiquePartenariat .= "]},";
        $serieIndustriel .= "]},";
        $serieFormation .= "]},";
        $serieMaintenance .= "]},";
        $serieService .= "]},";

        $serieAcademique .= "{id: '" . 'academic'  . "',name: '" . ucfirst(TXT_ACADEMIQUE) . "',data: [";
        $serieAcademiquePartenariat .= "{id: '" . 'academicPartenariat'  . "',name: '" . TXT_ACADEMICPARTENARIAT . "',data: [";
        $serieIndustriel .= "{id: '" . 'industriel'  . "',name: '" . TXT_INDUSTRIEL . "',data: [";
        $serieFormation .= "{id: '" . 'formation'  . "',name: '" . TXT_FORMATION . "',data: [";
        $serieMaintenance .= "{id: '" . 'maintenance'  . "',name: '" . TXT_MAINTENANCE . "',data: [";
        $serieService .= "{id: '" . 'service'  . "',name: '" . TXT_SERVICE . "',data: [";
		
        foreach ($centrales as $key => $centrale) {
            $nbProjetAcademique = $manager->getSinglebyArray($uneDateUneCentrale, array(ACADEMIC, $centrale[1],  ENCOURSREALISATION));  
            $nbProjetAcademiquePartenariat = $manager->getSinglebyArray($uneDateUneCentrale, array(ACADEMICPARTENARIAT, $centrale[1],  ENCOURSREALISATION));
            $nbProjetIndustriel = $manager->getSinglebyArray($uneDateUneCentrale, array(INDUSTRIEL, $centrale[1],  ENCOURSREALISATION));
            $nbProjetFormation = $manager->getSinglebyArray($uneDateUneCentrale, array(FORMATION, $centrale[1],  ENCOURSREALISATION));
            $nbProjetMaintenance = $manager->getSinglebyArray($uneDateUneCentrale, array(MAINTENANCE, $centrale[1],  ENCOURSREALISATION));
            $nbProjetService = $manager->getSinglebyArray($uneDateUneCentrale, array(SERVICE, $centrale[1],  ENCOURSREALISATION));
			
            $serieAcademique .=             "{name: '" . $centrale[0] . "',color:'" . couleurGraphLib($centrale[0]) . "', y: " . $nbProjetAcademique . " ,              drilldown: '" . 'academic' . $centrale[0]  . "'},";
            $serieAcademiquePartenariat .=  "{name: '" . $centrale[0] . "',color:'" . couleurGraphLib($centrale[0]) . "', y: " . $nbProjetAcademiquePartenariat . " ,   drilldown: '" . 'academicPartenariat' . $centrale[0]  . "'},";
            $serieIndustriel .=             "{name: '" . $centrale[0] . "',color:'" . couleurGraphLib($centrale[0]) . "', y: " . $nbProjetIndustriel . " ,              drilldown: '" . 'industriel' . $centrale[0]  . "'},";
            $serieFormation .=              "{name: '" . $centrale[0] . "',color:'" . couleurGraphLib($centrale[0]) . "', y: " . $nbProjetFormation . " ,               drilldown: '" . 'formation' . $centrale[0]  . "'},";
            $serieMaintenance .=            "{name: '" . $centrale[0] . "',color:'" . couleurGraphLib($centrale[0]) . "', y: " . $nbProjetMaintenance . " ,             drilldown: '" . 'maintenance' . $centrale[0]  . "'},";
            $serieService .=                "{name: '" . $centrale[0] . "',color:'" . couleurGraphLib($centrale[0]) . "', y: " . $nbProjetService . " ,                 drilldown: '" . 'service' . $centrale[0]  . "'},";
        }
        $serieAcademique .= "]},";
        $serieAcademiquePartenariat .= "]},";
        $serieIndustriel .= "]},";
        $serieFormation .= "]},";
        $serieMaintenance .= "]},";
        $serieService .= "]},";

        $serie0 = $serieAcademique . $serieAcademiquePartenariat . $serieIndustriel . $serieFormation. $serieMaintenance. $serieService;
        $serie = str_replace("},]", "}]", $serie0);
        $serieY = substr(str_replace("],]", "]]", $serie), 0, -1);
       
        $subtitle = TXT_CLICDETAIL;
        $xasisTitle = "";
    }
    if (IDTYPEUSER == ADMINLOCAL) {

        $nbProjetAcademique = $manager->getSinglebyArray($toutesdateCentrale, array(ACADEMIC, ENCOURSREALISATION, IDCENTRALEUSER,TRUE));
        $nbProjetAcademiquepartenariat = $manager->getSinglebyArray($toutesdateCentrale, array(ACADEMICPARTENARIAT,ENCOURSREALISATION,IDCENTRALEUSER,TRUE));
        $nbProjetIndustriel = $manager->getSinglebyArray($toutesdateCentrale, array(INDUSTRIEL, ENCOURSREALISATION,IDCENTRALEUSER,  TRUE));
        $nbProjetFormation = $manager->getSinglebyArray($toutesdateCentrale, array(FORMATION, ENCOURSREALISATION, IDCENTRALEUSER,TRUE));		
        $nbProjetMaintenance = $manager->getSinglebyArray($toutesdateCentrale, array(MAINTENANCE, ENCOURSREALISATION, IDCENTRALEUSER,TRUE));
        $service = $manager->getSinglebyArray($toutesdateCentrale, array(SERVICE, ENCOURSREALISATION, IDCENTRALEUSER,TRUE));
				
        $serieX = "";
        $serieX .= '{name: "' . ucfirst(TXT_ACADEMIQUE) . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbProjetAcademique . ',drilldown: "' . "academic" . '"}]},';
        $serieX .= '{name: "' . TXT_ACADEMICPARTENARIAT . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbProjetAcademiquepartenariat . ',drilldown: "' . 'academicPartenariat' . '"}]},';
        $serieX .= '{name: "' . TXT_INDUSTRIEL . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbProjetIndustriel . ',drilldown: "' . 'industriel' . '"}]},';
        $serieX .= '{name: "' . TXT_FORMATION . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbProjetFormation . ',drilldown: "' . 'formation' . '"}]},';
		
        $serieX .= '{name: "' . TXT_MAINTENANCE . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbProjetMaintenance . ',drilldown: "' . 'maintenance' . '"}]},';
        $serieX .= '{name: "' . TXT_SERVICE . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbProjetFormation . ',drilldown: "' . 'service' . '"}]}';
        
//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- 
        $serieAcademique = "{id: '" . '' . "',name: '" . '' . "',data: [";
        $serieAcademiquePartenariat = "{id: '" . '' . "',name: '" . '' . "',data: [";
        $serieIndustriel = "{id: '" . '' . "',name: '" . '' . "',data: [";
        $serieFormation = "{id: '" . '' . "',name: '" . '' . "',data: [";
        $serieMaintenance = "{id: '" . '' . "',name: '" . '' . "',data: [";
        $serieService = "{id: '" . '' . "',name: '" . '' . "',data: [";
        
        $serieAcademique .= "]},";
        $serieAcademiquePartenariat .= "]},";
        $serieIndustriel .= "]},";
        $serieFormation .= "]},";
        $serieMaintenance.= "]},";
        $serieService.= "]},";
//---------------------------------------------------------------------------------------   -----------------------------------------------------------------------------------------------------------------------------------        
        $serie0 = $serieAcademique . $serieAcademiquePartenariat . $serieIndustriel . $serieFormation. $serieMaintenance. $serieService;
        
        $serie = str_replace("},]", "}]", $serie0);
        $serieY = substr(str_replace("],]", "]]", $serie), 0, -1);
        $subtitle = '';
        $xasisTitle = "";
    }
   
   $title = TXT_TYPOLOGIEPROJETENCOURS;
   
    include_once 'commun/scriptBar.php';
    ?>
</div>
