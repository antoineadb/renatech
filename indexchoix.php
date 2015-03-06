<?php
session_start();
include_once 'class/Cache.php';
include_once 'decide-lang.php';
include_once 'outils/toolBox.php';
include_once 'outils/constantes.php';
include_once 'class/Manager.php';
define('ROOT',  dirname(__FILE__));
$Cache = new Cache(ROOT.'/cache', 60);
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
supprDouble();//VERIFICATION A CHAQUE CONNEXION QU'IL N'Y A PAS DE DOUBLONS DANS LA BDD ET SUPPRESSION DANS LE CAS CONTRAIRE
effaceRepertoire(REP_ROOT.'/tmp');//VIDAGE DU REPERTOIRE tmp
include 'html/header.html';
?>
<div id="global">
    <?php include "html/entete.html"; ?>
    <div id="Contenuindexchoix">
        <div style="padding-top: 75px;">
    <?php 
    if(internetExplorer()=='false'){
        $Cache->inc(ROOT.'/outils/bandeaucentrale.php'); //RECUPERATION DU BANDEAU DEFILANT DANS LE CACHE CACHE
    }else{
        include_once 'outils/bandeaucentrale.php'; //RECUPERATION DU BANDEAU DEFILANT DANS LE CAS D'INTERNET EXPLORER
    }
    ?>
            
</div>
<br>
    <?php
        include "html/indexchoix.html";
        include "html/footer.html";
        ?>
    </div>
</div>
</body>
</html>
