<?php
session_start();
include('decide-lang.php');
include_once 'class/Manager.php';
include_once 'class/Securite.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include_once 'outils/toolBox.php';
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
include 'html/header.html';
?>
<script src="<?php echo '/'.REPERTOIRE ?>/js/ajax.js"></script>
<div id="global">
    <?php
    include 'html/entete.html';
    ?>
    <div style="padding-top: 75px;">
        <?php include'outils/bandeaucentrale.php'; ?>
    </div>
    <?php
        if($_SESSION['idTypeUser']==ADMINLOCAL){
            $logs = $manager->getList2("select id,dateheure,infos,nomprenom,statutprojet  from logs where idcentrale=? order by dateheure desc",IDCENTRALEUSER);    
        }elseif($_SESSION['idTypeUser']==ADMINNATIONNAL){
            $logs = $manager->getList("select id,dateheure,infos,nomprenom,statutprojet  from logs order by dateheure desc");
        }
    ?>
    <div style="margin-top:50px;width:1050px" >
        <form  method="post" action="#"  name='logs' >            
            <fieldset id="ident">
                <legend>Fenêtre de log</legend>
            <table style="float: right;margin-top:-20px">
                <tr>                  
                    <td><a href="#"><img src="<?php echo '/'.REPERTOIRE.'/'; ?>styles/img/refresh.png" onclick="refresh();"></a></td>
                </tr>
            </table>    
            <table>
                <tr>
                    <td>
                        
                        <div  class='zoneText'   id="zoneText">
                            <?php
                            for ($i = 0; $i < count($logs); $i++) {
                                $date = new DateTime();
                                $date->setTimestamp($logs[$i]['dateheure']);
                                if($logs[$i][4]==''){
                                    echo $date->format('d-m-Y, H:i:s') . ' : ' . $logs[$i][2] . ' : ' . $logs[$i][3] . '<hr>';
                                }else{
                                    echo $date->format('d-m-Y, H:i:s') . ' : ' . $logs[$i][2] . ' : ' . $logs[$i][3] . ': statut : ' . $logs[$i][4] . '<hr>';
                                }
                            }
                            ?>
                        </div>
                    </td>
                </tr>
            </table>         
            
        </fieldset>
        </form>
    </div>
    <?php
    include 'html/footer.html';
    BD::deconnecter();
    ?>


<script>        
    function refresh(){
        refreshLogs('/<?php echo REPERTOIRE; ?>/modifBase/refreshLogs.php?lang=<?php echo $lang; ?>&action=true');
    }
</script>
</div>
</body>
</html>