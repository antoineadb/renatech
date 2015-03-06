<?php
session_start();
include_once 'outils/constantes.php';
include_once 'outils/toolBox.php';
include_once 'decide-lang.php';
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
    <fieldset id="ident" style="border-color: #5D8BA2;margin-top: 25px;"  >        
        <legend style="font-size: 1.2em"><?php echo TXT_DELETEPROJET.' ' ;?><a class="infoBulle" href="#"><img src='<?php echo "/".REPERTOIRE; ?>/styles/img/help.gif' height="13px" width="13px"/>
                                        <span style="text-align: left;padding:10px;width: 600px;border-radius:5px" >
                                            <?php echo TXT_SUPPRESSIONPROJET;?></span></a> </legend>
        <div style="   height: 30px;margin-top: -5px;text-align: center;width: auto;" ><?php echo TXT_PROJETSENATTENTE. ' - '.TXT_PROJETSACCEPTE.' - '.TXT_CONSULTPROJET ;?></div>    
        <div data-dojo-type="dijit/layout/TabContainer" style="width: 1009px;margin-top:5px" doLayout="false">
            <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo TXT_PROJETENATTENTE; ?>" style="width: auto; height: auto;" selected="true" >
                <?php include 'findprojecttodelete.php'; ?>
            </div>
            <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo TXT_PROJETMEMETITRE; ?>" style="width: auto; height: auto;" >
                <?php include 'findduplicateproject.php'; ?>
            </div>    
    </div><input type="text" id="results" style="display: none" >
    </fieldset>
    <?php include 'html/footer.html'; ?>
</div>
</body>
</html>