<?php
include_once 'class/Manager.php';
$db = BD::connecter();
$manager = new Manager($db);


$string0 = '';
$string1 = '';

$centrales = $manager->getList2("select libellecentrale,idcentrale from centrale where idcentrale!=? and masquecentrale!=TRUE order by idcentrale asc", IDAUTRECENTRALE);
$manager->exeRequete("DROP TABLE  IF EXISTS tmporigine;");
$manager->exeRequete("DROP TABLE  IF EXISTS tmporiginecentrale;");
$manager->exeRequete("DROP TABLE  IF EXISTS tmpgrandcompte;");
$manager->exeRequete("DROP TABLE  IF EXISTS tmpporteur;");
$tableorigine_centrale = $manager->getRequete("
        CREATE TABLE tmporiginecentrale as (SELECT count(libelletypepartenairefr),tp.libelletypepartenairefr,idtypepartenaire,c.idcentrale_centrale
        FROM partenaireprojet p 
        LEFT JOIN projetpartenaire pp  ON pp.idpartenaire_partenaireprojet= p.idpartenaire
        LEFT JOIN concerne c ON c.idprojet_projet = pp.idprojet_projet 
        LEFT JOIN typepartenaire tp ON tp.idtypepartenaire =  pp.idtypepartenaire_typepartenaire	
        WHERE c.idstatutprojet_statutprojet = ? AND pp.idtypepartenaire_typepartenaire not in(?,?) AND libelletypepartenairefr is not null 
        GROUP BY c.idcentrale_centrale,tp.libelletypepartenairefr,tp.idtypepartenaire         
        Order by libelletypepartenairefr asc)",array(ENCOURSREALISATION,IDETI,IDGE));
$tablegrandcompte = $manager->getRequete("
        CREATE TABLE tmpgrandcompte AS(
        SELECT COUNT(libelletypeentreprise),c.idcentrale_centrale as idcentrale,t.libelletypeentreprise AS libelle,CASE t.idtypeentreprise WHEN 1 THEN 100 END AS typeentreprise
        FROM  utilisateurporteurprojet u
        JOIN concerne c ON c.idprojet_projet = u.idprojet_projet
        JOIN appartient a ON a.idutilisateur_utilisateur = u.idutilisateur_utilisateur
        JOIN typeentreprise t ON t.idtypeentreprise = a.idtypeentreprise_typeentreprise
        WHERE c.idstatutprojet_statutprojet = ? AND t.idtypeentreprise=?
        GROUP BY idcentrale,typeentreprise,libelle
        UNION
	SELECT count(libelletypepartenairefr),c.idcentrale_centrale as idcentrale,'GRAND COMPTE' as libelle, 100 AS  typeentreprise
	FROM partenaireprojet p 
	JOIN projetpartenaire pp  ON pp.idpartenaire_partenaireprojet= p.idpartenaire
	JOIN concerne c ON c.idprojet_projet = pp.idprojet_projet 
	JOIN typepartenaire tp ON tp.idtypepartenaire =  pp.idtypepartenaire_typepartenaire	
	WHERE c.idstatutprojet_statutprojet = ? AND pp.idtypepartenaire_typepartenaire IN(?,?)
	GROUP BY c.idcentrale_centrale,libelle,typeentreprise
	)",array(ENCOURSREALISATION,IDGRANDCOMPTE,ENCOURSREALISATION,IDETI,IDGE));

$tableorigine=$manager->getRequete("CREATE TABLE tmporigine as (
        SELECT count(libelletypepartenairefr),tp.libelletypepartenairefr as libelle,idtypepartenaire as typeentreprise
        FROM partenaireprojet p 
        LEFT JOIN projetpartenaire pp  ON pp.idpartenaire_partenaireprojet= p.idpartenaire
        LEFT JOIN concerne c ON c.idprojet_projet = pp.idprojet_projet 
        LEFT JOIN typepartenaire tp ON tp.idtypepartenaire =  pp.idtypepartenaire_typepartenaire	
        WHERE c.idstatutprojet_statutprojet = ? AND pp.idtypepartenaire_typepartenaire not in(?,?)
        GROUP BY libelle,typeentreprise
        UNION
	SELECT COUNT(libelletypeentreprise),
        CASE t.libelletypeentreprise
            WHEN 'START UP' THEN 'Start-up (entre 0 et 3 ans de création)'
            WHEN 'TPE' THEN 'TPE (<10 salariés)'
            WHEN 'PME' THEN ' PME (10 à 249 salariés)'
            WHEN 'GRAND COMPTE' THEN 'GRAND COMPTE'
        END AS libelle,			
        CASE t.idtypeentreprise 
            WHEN 1 THEN 100
            WHEN 2 THEN 4
            WHEN 3 THEN 3
            WHEN 4 THEN 2
        END AS typeentreprise
        FROM  utilisateurporteurprojet u
        JOIN concerne c ON c.idprojet_projet = u.idprojet_projet
        JOIN appartient a ON a.idutilisateur_utilisateur = u.idutilisateur_utilisateur
        JOIN typeentreprise t ON t.idtypeentreprise = a.idtypeentreprise_typeentreprise
        WHERE c.idstatutprojet_statutprojet = ?
        GROUP BY typeentreprise,libelle
        UNION
        SELECT COUNT(libelletypepartenairefr),'GRAND COMPTE' as libelle, 100 as typeentreprise
        FROM partenaireprojet p 
        LEFT JOIN projetpartenaire pp  ON pp.idpartenaire_partenaireprojet= p.idpartenaire
        LEFT JOIN concerne c ON c.idprojet_projet = pp.idprojet_projet 
        LEFT JOIN typepartenaire tp ON tp.idtypepartenaire =  pp.idtypepartenaire_typepartenaire	
        WHERE c.idstatutprojet_statutprojet = ? AND pp.idtypepartenaire_typepartenaire in(?,?)
        GROUP BY typeentreprise,libelle)", array(ENCOURSREALISATION,IDETI,IDGE,ENCOURSREALISATION,ENCOURSREALISATION,IDETI,IDGE));
$tablePorteur=$manager->getRequete("
        CREATE TABLE tmpporteur AS (
        SELECT COUNT(libelletypeentreprise),c.idcentrale_centrale,t.libelletypeentreprise,
        CASE t.idtypeentreprise 
            WHEN 1 THEN 100
            WHEN  2 THEN 4
            WHEN 3 THEN 3
            WHEN 4 THEN 2
        END AS idtypeentreprise
        FROM  utilisateurporteurprojet u
        JOIN concerne c ON c.idprojet_projet = u.idprojet_projet
        JOIN appartient a ON a.idutilisateur_utilisateur = u.idutilisateur_utilisateur
        JOIN typeentreprise t ON t.idtypeentreprise = a.idtypeentreprise_typeentreprise
        WHERE c.idstatutprojet_statutprojet = ?
        GROUP BY c.idcentrale_centrale,libelletypeentreprise,t.idtypeentreprise
        ORDER BY t.libelletypeentreprise ASC)",array(ENCOURSREALISATION));

$origines =$manager->getList("SELECT sum(count) AS count,libelle,typeentreprise FROM tmporigine GROUP BY typeentreprise,libelle");
$originecentrales =$manager->getList("SELECT * FROM tmporiginecentrale");
$originegrandcomptes =$manager->getList("SELECT * FROM tmpgrandcompte");

$title = TXT_ORIGINEPARTENAIREINDUSTRIEL;  
if (IDTYPEUSER == ADMINNATIONNAL) {
    $serie = "";
    foreach ($origines as $key => $origine) {
        if( $origine['typeentreprise']!=null){
            $serie .= '{name: "' . $origine['libelle'] . '", data: [{name: "' . TXT_DETAILS . '",y: '. $origine['count'] . ',drilldown: "' . $origine['typeentreprise']  . '"}]},';
        }
    }
    $serie1 = str_replace("},]}", "}]}", $serie);
    $serie01 = str_replace("},]", "}]", $serie1);
    $serieX = substr($serie01, 0, -1);
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------    
    $serie02 = '';
    foreach ($originecentrales as $key => $originecentrale) {
        $serie02 .="{id: '" . $originecentrale['idtypepartenaire'] . "',name: '" . $originecentrale['libelletypepartenairefr']." ',data: [";        
        foreach ($centrales as $key => $centrale) {
          $nb = (int)  $manager->getSinglebyArray("SELECT sum(count) FROM tmporiginecentrale WHERE idcentrale_centrale=? AND idtypepartenaire=? ",array($centrale[1],$originecentrale['idtypepartenaire']));
            
                $serie02 .="{name: '" . $centrale[0] .  "',color:'". couleurGraphLib($centrale[0])."', y: ". $nb . " , drilldown: '" . $centrale[0] . $originecentrale['idtypepartenaire']  . "'},";
        }$serie02 .="]},";
    }    
    foreach ($originegrandcomptes as $key => $originegrandcompte) {
        $serie02 .="{id: '" . $originegrandcompte['typeentreprise'] . "',name: '" . $originegrandcompte['libelle']." ',data: [";        
        foreach ($centrales as $key => $centrale) {
            $nb = (int)  $manager->getSinglebyArray("SELECT sum(count) FROM tmpgrandcompte WHERE idcentrale=? AND typeentreprise=? ",array($centrale[1],100));            
            $serie02 .="{name: '" . $centrale[0] .  "',color:'". couleurGraphLib($centrale[0])."', y: ". $nb . " , drilldown: '" . $centrale[0] . 100 . "'},";
        }$serie02 .="]},";
    }
   
        
    $serie2 = str_replace("},]}", "}]}", $serie02);

//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    $serie3 = "";
    $serie03 = substr($serie2 . $serie3, 0, -1);
    $serieY = str_replace("],]}", "]]}", $serie03);
    $subtitle = TXT_CLICDETAIL;
    
}
if (IDTYPEUSER == ADMINLOCAL) {    
    $originecentrales =$manager->getList2("SELECT * FROM tmporiginecentrale WHERE idcentrale_centrale=?",IDCENTRALEUSER);
    $originegrandcomptes =$manager->getList2("SELECT * FROM tmpgrandcompte WHERE idcentrale=?",IDCENTRALEUSER);
    
    $serie = "";
    foreach ($originecentrales as $key => $origine) {
        if( $origine['idtypepartenaire']!=null){
            $serie .= '{name: "' . $origine['libelletypepartenairefr'] . '", data: [{name: "' . TXT_DETAILS . '",y: '. $origine['count'] . ',drilldown: "' . $origine['idtypepartenaire']  . '"}]},';
        }
    }
    $nb = (int)  $manager->getSinglebyArray("SELECT sum(count) FROM tmpgrandcompte WHERE idcentrale=? AND typeentreprise=? ",array(IDCENTRALEUSER,100));        
    $serie .= '{name: "' . TXT_GRAND_COMPTE . '", data: [{name: "' . TXT_DETAILS . '",y: '. $nb . ',drilldown: "' . IDCENTRALEUSER  . '"}]},';        
    
    
    $serie1 = str_replace("},]}", "}]}", $serie);
    $serie01 = str_replace("},]", "}]", $serie1);
    $serieX = substr($serie01, 0, -1);
    $serieY = "";
    
    $subtitle = TXT_CLICDETAIL;
  
}$xasisTitle = TXT_NOMBREOCCURRENCE;
include_once 'commun/scriptBar.php';
