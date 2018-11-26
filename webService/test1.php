<?php


$ch = curl_init();
$url="https://www.renatech.org/projet-dev/webService/processJson_1.php";
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch,CURLOPT_HEADER,0);
$json = curl_exec($ch);
curl_close($ch);
$json  = json_decode($json,true);
for($i=0;$i>$json['Metadata']['TotalResults'];$i++){
    echo "JSON: <b>idprojet<b/>".$json['Result'][$i]['idprojet'];
}
?>
