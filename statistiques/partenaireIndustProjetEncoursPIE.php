<?php

include_once 'class/Manager.php';
$db = BD::connecter();
$manager = new Manager($db);

if (IDTYPEUSER == ADMINNATIONNAL) {
   $string0="";
   $nb=0;
    foreach ($origines as $key => $origine) {
        if( $origine['typeentreprise']!=null){            
            $string0 .='["' . $origine['libelle'] . '",' . $origine['count'] . '],';
        }
        $nb+=$origine['count'];
    }
    $subtitle = TXT_NBRESULT . ': ' . $nb;    
}
if (IDTYPEUSER == ADMINLOCAL) {
    $manager->exeRequete("DROP TABLE  IF EXISTS tmpgrandcomptepie;");
    $manager->exeRequete("CREATE TABLE tmpgrandcomptepie as (select * from tmpgrandcompte)");
    $manager->exeRequete("ALTER TABLE tmpgrandcomptePie RENAME COLUMN count TO nb ");
    
    $originecentrales =$manager->getListbyArray("
        SELECT count as nb,libelletypepartenairefr as libelle, idtypepartenaire as typeentreprise FROM tmporiginecentrale WHERE idcentrale_centrale=?
         group by libelle,typeentreprise,nb
            UNION
        SELECT sum(nb) as nb,libelle,typeentreprise FROM tmpgrandcomptepie WHERE idcentrale=?
        group by libelle,typeentreprise",array(IDCENTRALEUSER,IDCENTRALEUSER));    
    $string0="";
   $nb=0;
    foreach ($originecentrales as $key => $origine) {
        if( $origine['typeentreprise']!=null){            
            $string0 .='["' . $origine['libelle'] . '",' . $origine['nb'] . '],';
        }
        $nb+=$origine['nb'];
    }
    $subtitle = TXT_NBRESULT . ': ' . $nb;
}
$string = substr($string0, 0, -1);
include_once 'commun/scriptPie.php';
