<?php
include_once 'class/Manager.php';
$db = BD::connecter();
unset($_SESSION['anneeprojet']);
$manager = new Manager($db);
$years = $manager->getList("select distinct EXTRACT(YEAR from dateprojet)as year from projet where   EXTRACT(YEAR from dateprojet)>2012 order by year asc");
$statutProjets = $manager->getList2("select libellestatutprojet,idstatutprojet from statutprojet where idstatutprojet!=? order by idstatutprojet  asc",TRANSFERERCENTRALE);
$arraydate = $manager->getList("select distinct EXTRACT(YEAR from datecreation) as anneedatecreation from utilisateur order by anneedatecreation asc");
$centrales = $manager->getList2("select libellecentrale,idcentrale from centrale where idcentrale!=? and masquecentrale!=TRUE order by idcentrale asc", IDAUTRECENTRALE);
if (IDTYPEUSER == ADMINNATIONNAL) {
    $serie = "";
    foreach ($centrales as $key => $centrale) {
            $nbProjet = $manager->getSinglebyArray("SELECT count(idprojet) FROM projet,centrale,concerne WHERE idcentrale_centrale = idcentrale AND idprojet_projet = idprojet AND idcentrale=? and  trashed != ?"
                    . "and idstatutprojet_statutprojet=? and  EXTRACT(YEAR from dateprojet)>2012   AND idcentrale!=?",array($centrale[1],TRUE,ENCOURSREALISATION,IDCENTRALEAUTRE));
            $serie .= '{name: "' . $centrale[0] . '", data: [{name: "' . TXT_DETAILS .
                    '",y: ' . $nbProjet . ',drilldown: "' . $centrale[0] . '"}]},';

        $serie1 = str_replace("},]}", "}]}", $serie);
        $serie01 = str_replace("},]", "}]", $serie1);
        $serieX = substr($serie01, 0, -1);
    }
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------    
    $serieY="";
    $serieY = str_replace("},]}", "}]}", $serieY);
    $subtitle = TXT_CLICDETAIL;
    $title = TXT_NBRUNNINGPROJET;
    $xasisTitle = TXT_NOMBREOCCURRENCE;
} 

if (IDTYPEUSER == ADMINLOCAL) {
    $serie = "";
    foreach ($years as $key => $year) {
            $nbProjet = $manager->getSinglebyArray("SELECT count(idprojet) FROM projet,centrale,concerne WHERE idcentrale_centrale = idcentrale AND idprojet_projet = idprojet AND idcentrale=? "
                    . "and extract(year from dateprojet)=? and  trashed != ? and idstatutprojet_statutprojet=?", array(IDCENTRALEUSER,$year[0],TRUE,ENCOURSREALISATION));            
            if($nbProjet==0){$nbProjet=0;}
            $serie .= '{name: "' . $year[0] . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbProjet . ',drilldown: "' . $year[0] . '"}]},';
            

        $serie1 = str_replace("},]}", "}]}", $serie);
        $serie01 = str_replace("},]", "}]", $serie1);
        $serieX = substr($serie01, 0, -1);
    }
        $serieY = "";
    
$subtitle = TXT_CLICDETAIL;
$title = TXT_NBRUNNINGPROJET;
$xasisTitle = TXT_PROJETDATESTATUT;
}
include_once 'commun/scriptBar.php';
       