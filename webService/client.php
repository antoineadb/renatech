<?php

session_start();
include_once '../outils/toolBox.php';
include_once '../class/Manager.php';
include_once '../outils/constantes.php';
include_once '../decide-lang.php';
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {  
    header('Location: /' . REPERTOIRE . '/Login_Error/'.$lang);
}
include '../outils/parser.php';
include '..//html/header.html';
$db = BD::connecter();
$manager = new Manager($db);
?>
<div id="global">
    <?php include 'html/entete.html'; ?>
    <div id="Contenuindexchoix">
        <?php                        
            $result =$manager->getListbyArray("SELECT * FROM PROJET WHERE datedebutprojet BETWEEN ? AND ?",array('2018-09-01','2018-10-10'));
            $jsonData = json_encode($result);
            echo '<pre>';print_r($jsonData);echo '</pre>';
            $nom =$number = mt_rand(1, 5000);
            $fpfile = fopen("../temp/test".$nom.".json", 'w');
            fwrite($fpfile, $jsonData);
            $json_file = "../temp/test".$nom.".json";
            $json = file_get_contents($json_file);
            file_put_contents($json_file, $json);
            fclose($fpfile);
            chmod("../temp/test".$nom.".json", 0777);
       
        ?>
    </div>
</div>
</body>
</html>
