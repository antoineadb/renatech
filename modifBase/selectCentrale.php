
<?php
include_once '../class/Manager.php';
include_once '../outils/constantes.php';
$db = BD::connecter();
$manager = new Manager($db);
if (isset($_GET['centrale']) && !empty($_GET['centrale'])) {
    $arrayCentrale = $manager->getList2("select adressesitewebcentrale,adresselogcentrale from sitewebapplication where refsiteweb=?", $_GET['centrale']);
    $centrale = $arrayCentrale[0]['adressesitewebcentrale'];
    $logocentrale = $arrayCentrale[0]['adresselogcentrale'];
    //CALCUL DES DIMENSIONS DE L'IMAGE
    $figure = nomFichierValidesansAccent($logocentrale);
    $arrayInfoImg = getimagesize('../'.$figure);
    $v = sizeLogo($arrayInfoImg, 75);
    $width = $v[0];
    $height = $v[1];
    /*     
    $jsonData = array();    
    $jsonData["adressesitewebcentrale"]  = $centrale;
    $jsonData["adresselogcentrale"] = $logocentrale;
    $jsonData["centrale"] = $_GET['centrale'];
    $json_fp =  json_encode($jsonData );*/
    $fp = fopen('../tmp/jsonDataCentrale.json', 'w');    
    fwrite($fp,"{'adressesitewebcentrale':'".$centrale."','adresselogcentrale':'".$logocentrale."','centrale':'".$_GET['centrale']."'}" );
    $jsonEncours = file_get_contents('../tmp/jsonDataCentrale.json');
    
    file_put_contents('../tmp/jsonDataCentrale.json', $jsonEncours);
    fclose($fp);
    chmod('../tmp/jsonDataCentrale.json', 0777);
}


BD::deconnecter();
