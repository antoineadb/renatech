<?php 
session_start();
include('decide-lang.php');
include_once 'class/Cache.php';
include_once 'outils/constantes.php';
include_once 'outils/toolBox.php';
define('ROOT',  dirname(__FILE__));
$Cache = new Cache(ROOT.'/cache', 60);
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
    if (!isset($_SESSION['nom'])) {
        $_SESSION['nom'] = $_SESSION['nomConnect'];
    }
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
include 'html/header.html';
?>
<div id="global" >
    <?php include 'html/entete.html'; ?>
    <div style="padding-top: 75px;width:1050px">
        <?php 
        if(internetExplorer()=='false'){
            $Cache->inc(ROOT.'/outils/bandeaucentrale.php'); //RECUPERATION DU BANDEAU DEFILANT DANS LE CACHE CACHE
        }else{
            include 'outils/bandeaucentrale.php'; //RECUPERATION DU BANDEAU DEFILANT DANS LE CAS D'INTERNET EXPLORER
        }
        ?>       
        
            <div style="height:500px;margin-top: 25px" ><?php require_once 'html/vueSuiviProjetTousCentrales.html'; ?></div>
    </div>
    <table>
    <tr>
        <td><div style="background-color: darkgreen;height: 10px;width: 25px;border: 1px solid black; "></div></td>
        <td><label style="width:378px;margin-left: 5px"  ><?php echo TXT_DATENONDEPASSE; ?></label></td>
    </tr>
    <tr>
        <td><div style="background-color: darkgoldenrod;height: 10px;width: 25px;border: 1px solid black; "></div></td>
        <td><label style="width:378px;margin-left: 5px"  ><?php echo TXT_DATEPROCHE ?></label></td>
    </tr>
    <tr>
        <td><div style="background-color: red;height: 10px;width: 25px;border: 1px solid black; "></div></td>
        <td><label style="width:378px;margin-left: 5px"  ><?php echo TXT_DATEDEPASSE ?></label></td>
    </tr>
    <tr>
        <td><div style="background-color: black;height: 10px;width: 25px;border: 1px solid black; "></div></td>
        <td><label style="width:378px;margin-left: 5px"  ><?php echo TXT_NONGERER ?></label></td>
    </tr>
</table>
    <?php include 'html/footer.html'; ?>
</div>
</body>
</html>