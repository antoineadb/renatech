<?php

include_once '../class/Manager.php';
$db = BD::connecter();
$manager = new Manager($db);
/*$array = $manager->getList("select distinct(idutilisateur), nom,prenom from utilisateur,utilisateurporteurprojet where idutilisateur_utilisateur=idutilisateur order by idutilisateur asc");
$string='';
for ($i = 0; $i < count($array); $i++) {
    $string.= 'AU=('.$array[$i]['nom'].' '.substr($array[$i]['prenom'],0,1).') OR ';    
}
echo substr($string,0,-3);*/


$array2 = $manager->getList("select distinct(idutilisateur), nom,prenom from utilisateur,creer where idutilisateur_utilisateur=idutilisateur order by idutilisateur asc");
$string2='';
for ($i = 0; $i < count($array2); $i++) {
    $string2.= 'AU=('.$array2[$i]['nom'].' '.substr($array2[$i]['prenom'],0,1).') OR ';    
}
echo substr($string2,0,-3);
