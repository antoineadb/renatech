<?php
include_once '../class/Manager.php';
include_once '../outils/constantes.php';
if (isset($_POST['login']) && isset($_POST['pass'])) {
    $pseudo = $_POST['login'];
    $idlogin = $manager->getSingle2("select idlogin from loginpassword where pseudo=?", $pseudo);
    if (!empty($idlogin)) {// LE LOGIN EXISTE
        $passe = $manager->getSingle2("select motdepasse from loginpassword where pseudo=?", $pseudo); //RECUPERATION DU MOT DE PASSE
        if (sha1($_POST['pass']) == $passe) {//CONTROLE QUE LE MOT DE PASSE EST IDENTIQUE
            //VERIFICATION SI LE COMPTE N'EST PAS DESACTIVE
            $actif = $manager->getSingle2("select actif from loginpassword where pseudo=?", $pseudo);
            if (empty($actif)) {
                $actif = 'FALSE';
                 echo 'Ce compte est désactivé' ;
                exit();
            } else {
                $actif = 'TRUE';
            }
            if ($actif == 'FALSE') {
                echo 'Ce compte est désactivé' ;
                exit();
            }
        } else {//MOT DE PASSE ERRONNE        
            echo 'Erreur de mot de passe' ;
            exit();
        }
    } else {
        echo "VOUS N'ETES PAS ENCORE INSCRIT";
        exit();
    }
}else{
    echo "L'authentification ne fonctionne pas!";
    exit();
}

//API Url
$url="https://www.renatech.org/projet-dev/webService/processJson1.php";
 
//Initiate cURL.
$ch = curl_init($url);
 
//The JSON data.
$jsonData = $manager->getList("SELECT acronyme,idprojet,"
        . " idutilisateur_utilisateur as iddemandeur,"
        . " numero as numero_projet,dateprojet as date_demande,libellecentrale as centrale ,datedebutprojet,idstatutprojet as statut, dureeprojet as duree_projet_mois,"
        . " datedebutprojet + interval  '1 month' * dureeprojet as date_fin_projet, datestatutfini as date_statut_fini"
        . " FROM PROJET "
        . " LEFT JOIN concerne co on co.idprojet_projet=idprojet "
        . " LEFT JOIN creer c on c.idprojet_projet=idprojet "
        . " LEFT JOIN centrale on idcentrale_centrale =idcentrale "
        . " LEFT JOIN statutprojet on idstatutprojet_statutprojet =idstatutprojet "
        . " WHERE datedebutprojet BETWEEN '2017-01-01' AND '2018-12-31' AND confidentiel is not TRUE");
for($i=0;$i<count($jsonData);$i++){
    foreach($jsonData[$i] as $key=>$value){
        if(is_int($key)){            
            unset($jsonData[$i][$key]);            
        }                
    }
} 
header("Content-type: application/json");
//Encode the array into JSON.
$jsonDataEncoded = json_encode($jsonData); 
//Tell cURL that we want to send a POST request.
curl_setopt($ch, CURLOPT_POST, 1); 
//Attach our encoded JSON string to the POST fields.
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded); 
//Set the content type to application/json
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
//Execute the request
$result = curl_exec($ch);

