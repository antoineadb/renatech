<?php
include_once 'class/Manager.php';
$db = BD::connecter();
unset($_SESSION['anneeprojetautres']);
$manager = new Manager($db);
$libelleCentrale = $manager->getSingle2("select libellecentrale from centrale where idcentrale=?", IDCENTRALEUSER);
$centrales = $manager->getList2("select libellecentrale,idcentrale from centrale where idcentrale!=? and masquecentrale!=TRUE order by idcentrale asc", IDAUTRECENTRALE);
$years = $manager->getList("select distinct EXTRACT(YEAR from dateprojet)as year from projet where   EXTRACT(YEAR from dateprojet)>2012order by year asc");
$now = date('Y');
 $title = TXT_REPARTITIONPROJETENCOURS .' '.TXT_POURLANNEE.' '.date('Y');
if (IDTYPEUSER == ADMINNATIONNAL) {
    $xasisTitle = "";
    $nbtotalprojet = $manager->getSinglebyArray("select count(idprojet_projet) from concerne,projet  where idprojet_projet=idprojet and idstatutprojet_statutprojet=? ",array(ENCOURSREALISATION));
    $subtitle = TXT_NBPROJET . ': ' .$nbtotalprojet;
    $nbprojetExogeneExterne = $manager->getSingle2("SELECT count(distinct co.idprojet_projet) FROM creer cr,projet p,utilisateur u,concerne co WHERE  cr.idprojet_projet = p.idprojet 
        AND  u.idutilisateur = cr.idutilisateur_utilisateur AND  u.idcentrale_centrale is null and  co.idprojet_projet = p.idprojet and p.idprojet not in(select idprojet_projet 
        from projetpartenaire )and idstatutprojet_statutprojet=?  ", ENCOURSREALISATION);

    $nbprojetExogeneCollaboratif = $manager->getSingle2("SELECT count(distinct co.idprojet_projet) FROM  projet p, projetpartenaire pr, utilisateur u, creer c,concerne co WHERE p.idprojet = c.idprojet_projet 
        AND pr.idprojet_projet = p.idprojet AND c.idutilisateur_utilisateur = u.idutilisateur and  co.idprojet_projet = p.idprojet And u.idcentrale_centrale IS NOT NULL and idstatutprojet_statutprojet=? 
        ", ENCOURSREALISATION);
    $nbprojetInterne = $nbtotalprojet - $nbprojetExogeneExterne - $nbprojetExogeneCollaboratif;
    $serieX = '{name: "' . TXT_PROJETINTERNE . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbprojetInterne . ',drilldown: "' . TXT_PROJETINTERNE . '"}]},';
    $serieX .= '{name: "' . TXT_PROJETEXOEXTERNE . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbprojetExogeneExterne . ',drilldown: "' . "externe" . '"}]},';
    $serieX .= '{name: "' . TXT_PROJETEXOCOLLABORATIF . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbprojetExogeneCollaboratif . ',drilldown: "' . 'collaboratif' . '"}]}';

    $serieInterneCentrale = "";
   
        $serieInterneCentrale .= "{id: '" . TXT_PROJETINTERNE . "',name: '" . TXT_PROJETINTERNE . "',data: [";
        foreach ($centrales as $key => $centrale) {
            $nbtotalprojet = $manager->getSinglebyArray("select count(distinct idprojet_projet) from concerne,projet where idprojet_projet=idprojet and extract(year from dateprojet)<=?  "
                    . "and idstatutprojet_statutprojet=? and idcentrale_centrale=?", array($now, ENCOURSREALISATION, $centrale[1]));
            $nbprojetExogeneExterne = $manager->getSinglebyArray("SELECT count(distinct co.idprojet_projet) FROM creer cr,projet p,utilisateur u,concerne co WHERE cr.idprojet_projet = p.idprojet 
                    AND  u.idutilisateur = cr.idutilisateur_utilisateur  AND  u.idcentrale_centrale is null and  co.idprojet_projet = p.idprojet and p.idprojet not in(select idprojet_projet from projetpartenaire ) 
                    and extract(year from dateprojet)<=?  and idstatutprojet_statutprojet=? and co.idcentrale_centrale=?", array($now, ENCOURSREALISATION, $centrale[1]));
            $nbprojetExogeneCollaboratif = $manager->getSinglebyArray("SELECT count(distinct co.idprojet_projet) FROM  projet p, projetpartenaire pr, utilisateur u, creer c,concerne co WHERE p.idprojet = c.idprojet_projet 
                    AND pr.idprojet_projet = p.idprojet AND c.idutilisateur_utilisateur = u.idutilisateur and  co.idprojet_projet = p.idprojet And u.idcentrale_centrale IS NOT NULL and extract(year from dateprojet)<=? 
                     and idstatutprojet_statutprojet=? and co.idcentrale_centrale=?", array($now, ENCOURSREALISATION, $centrale[1]));
            $nbprojetInterne = $nbtotalprojet - ($nbprojetExogeneExterne + $nbprojetExogeneCollaboratif);
            $serieInterneCentrale .="{name: '" . $centrale[0] . "',color:'". couleurGraphLib($centrale[0])."', y: " . $nbprojetInterne . " , drilldown: '" . TXT_PROJETINTERNE . $centrale[0] . $now . "'},";
        }$serieInterneCentrale .="]},";
    $serieY = substr(str_replace("],]", "]]", $serieInterneCentrale), 0, -1);
} 

if (IDTYPEUSER == ADMINLOCAL) {
    $nbtotalprojet = $manager->getSinglebyArray("select count(idprojet_projet) from concerne,projet  where idprojet_projet=idprojet and idstatutprojet_statutprojet=?  "
            . "and idcentrale_centrale=?", array(ENCOURSREALISATION,IDCENTRALEUSER));    
    $subtitle = TXT_NBPROJET . ': ' .$nbtotalprojet;
    $xasisTitle = "";

    $nbprojetExogeneExterne = $manager->getSinglebyArray("SELECT count(distinct co.idprojet_projet) FROM creer cr,projet p,utilisateur u,concerne co WHERE  cr.idprojet_projet = p.idprojet 
        AND  u.idutilisateur = cr.idutilisateur_utilisateur AND  u.idcentrale_centrale is null and  co.idprojet_projet = p.idprojet and p.idprojet not in(select idprojet_projet from projetpartenaire )and idstatutprojet_statutprojet=? 
         and co.idcentrale_centrale=?",array(ENCOURSREALISATION,IDCENTRALEUSER));
    $nbprojetExogeneCollaboratif = $manager->getSinglebyArray("SELECT count(distinct co.idprojet_projet) FROM  projet p, projetpartenaire pr, utilisateur u, creer c,concerne co WHERE p.idprojet = c.idprojet_projet AND pr.idprojet_projet = p.idprojet
        AND c.idutilisateur_utilisateur = u.idutilisateur and  co.idprojet_projet = p.idprojet And u.idcentrale_centrale IS NOT NULL and idstatutprojet_statutprojet=?  
        and co.idcentrale_centrale=?",array(ENCOURSREALISATION,IDCENTRALEUSER));
    $nbprojetInterne = $nbtotalprojet - $nbprojetExogeneExterne - $nbprojetExogeneCollaboratif;    
    $serieX = '{name: "' . TXT_PROJETINTERNE . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbprojetInterne . ',drilldown: "' . TXT_PROJETINTERNE . '"}]},';
    $serieX .= '{name: "' . TXT_PROJETEXOEXTERNE . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbprojetExogeneExterne . ',drilldown: "' . "externe" . '"}]},';
    $serieX .= '{name: "' . TXT_PROJETEXOCOLLABORATIF . '", data: [{name: "' . TXT_DETAILS . '",y: ' . $nbprojetExogeneCollaboratif . ',drilldown: "' . 'collaboratif' . '"}]}';}
    $serieY="";
include_once 'commun/scriptBar.php';
