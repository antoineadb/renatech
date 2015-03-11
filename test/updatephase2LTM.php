<?php

include '../class/Manager.php';
//FERMETURE DE LA CONNEXION
$db = BD::connecter();
$manager = new Manager($db);
include_once '../outils/toolBox.php';
include '../class/email.php';

$arrayprojetphase2 = 
array(
	array("idresponsable"=>721,"idprojet"=>969),
	array("idresponsable"=>399,"idprojet"=>712),
	array("idresponsable"=>288,"idprojet"=>1443),
	array("idresponsable"=>584,"idprojet"=>742),
	array("idresponsable"=>941,"idprojet"=>1207),
	array("idresponsable"=>807,"idprojet"=>1230),
	array("idresponsable"=>875,"idprojet"=>1121),
	//array("idresponsable"=>796,"idprojet"=>471),
	array("idresponsable"=>160,"idprojet"=>1151),
	array("idresponsable"=>121,"idprojet"=>145),
	array("idresponsable"=>847,"idprojet"=>1186),
	array("idresponsable"=>608,"idprojet"=>760),
	array("idresponsable"=>695,"idprojet"=>789),
	array("idresponsable"=>1153,"idprojet"=>1450),
	array("idresponsable"=>1002,"idprojet"=>954),
	array("idresponsable"=>408,"idprojet"=>380),
	array("idresponsable"=>120,"idprojet"=>1138),
	array("idresponsable"=>380,"idprojet"=>1276),
	array("idresponsable"=>117,"idprojet"=>182),
	array("idresponsable"=>721,"idprojet"=>139),
	array("idresponsable"=>955,"idprojet"=>1118)
);
$dateaffectation = date("m,d,Y");
for ($i = 0; $i < count($arrayprojetphase2); $i++) {
    $porteur =new UtilisateurPorteurProjet($arrayprojetphase2[$i]['idresponsable'], $arrayprojetphase2[$i]['idprojet'], $dateaffectation);
    $manager->addUtilisateurPorteurProjet($porteur);
}
BD::deconnecter();
