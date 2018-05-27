<?php
session_start();
include_once 'class/Manager.php';
include_once 'outils/constantes.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include('decide-lang.php');
include_once 'outils/toolBox.php';
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
include 'html/header.html';
?>

<div id="global">
    <?php include 'html/entete.html'; ?>
    <div style="margin-top: 70px;">
        <?php include_once 'outils/bandeaucentrale.php'; ?>
    </div>    
    <div style="margin-top:40px;width:1050px" >
        <form  method="post" action="<?php echo '/' . REPERTOIRE ?>/modifBase/viderCache.php?lang=<?php echo $lang; ?>" id='videCache' name='videCache' onsubmit="">
            <div style="width:100%;text-align: center"> <?php if(isset($_GET['t'])&&$_GET['t']=='a'){ echo TXT_TOUTCACHEVIDE;}elseif(isset($_GET['t'])&&$_GET['t']=='d'){echo TXT_CACHEDONNEEVIDE;}?></div>
            <fieldset id="ident" style="border-color: #5D8BA2;width: 1008px;padding-bottom:30px;padding-top:10px;font-size:1.2em" >
                <legend><?php echo 'Cache';?></legend>                
                <table>
                    <tr>
                        <td>                                    
                            <input type= "radio" data-dojo-type="dijit/form/RadioButton" name="donnee"  value="TRUE" class="btRadio"  >
                            <?php echo TXT_VIDETOUSCACHE; ?>&nbsp;&nbsp;
                            <input type= "radio" data-dojo-type="dijit/form/RadioButton" name="donnee" value="FALSE" checked="checked" class="btRadio" > <?php echo TXT_VIDECACHEDONNEE; ?>
                            <input type="submit"   label="<?php echo TXT_VALIDER; ?>" data-dojo-Type="dijit.form.Button" data-dojo-props="" style="margin-left:20px">
                        </td>
                    </tr>
                </table>
            </fieldset>
<?php include 'html/footer.html'; ?>
        </form>
    </div>
</div>

