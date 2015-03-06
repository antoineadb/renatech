<?php
session_start();
$_SESSION['lastLoad']=time()-30;
include_once '../outils/constantes.php';
?>
<script>
function ctrltempsconnexion(limite){
    <?php 
        $lastload=(int) $_SESSION['lastLoad']; 
        if (isset($_GET['lang'])) {
        $lang = $_GET['lang'];
        } else {//Si aucune langue n'est déclarée on tente de reconnaitre la langue par défaut du navigateur
            $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        }
    ?>    
     d = new Date();
     heure =parseInt(Math.floor(d.getTime()/1000));
     session = parseInt("<?php echo $lastload; ?>");
    if(session < heure-limite){
        window.location.replace("/<?php echo  REPERTOIRE ?>/Login_Timeout/<?php echo  $lang ?>");
    }
}
ctrltempsconnexion(300);
</script>
