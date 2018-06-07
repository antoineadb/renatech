<?php
session_start();
include 'decide-lang.php';
include_once 'outils/toolBox.php';
include_once 'outils/constantes.php';
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
include 'html/header.html';
?>
<script src="<?php echo '/' . REPERTOIRE ?>/js/ajax.js"></script>
<script src="<?php echo '/' . REPERTOIRE ?>/js/jquery-1.11.3.min.js"></script>
<div id="global">        
    <?php include 'html/entete.html'; ?>       
    <form data-dojo-type="dijit/form/Form" name="adminAppli" style="font-size:1.2em;" id="exportProjet" method="post" 
          action="#"  >   
        <script type="dojo/method" data-dojo-event="onSubmit">
            if(this.validate()){
            return true;
            }else{
            alert('<?php echo TXT_MESSAGEERREURCONTACT; ?>');
            return false;
            exit();
            }

        </script>
        <div style="margin-top: 69px;">
            <?php include_once 'outils/bandeaucentrale.php'; ?>
        </div>   

        <fieldset  style="border-color: #5D8BA2;width: 1017px;margin-top: 24px;height:250px;padding-top:25px;padding-bottom: 0px" id="msg" >
            <!--<div style="margin-bottom: 50px">
                <input type= "radio" data-dojo-type="dijit/form/RadioButton" name="parametres" id="base_de_donnee"  value="bdd"  checked="checked" class="btRadio" style="margin-left: 20px;" onclick="change(this.id)">
                <?php //echo 'Base de donnée'; ?>
                <input type= "radio" data-dojo-type="dijit/form/RadioButton" name="parametres" id="messagerie"  value="msgs"  class="btRadio" style="margin-left: 20px;" onclick="change(this.id)">
                <?php //echo 'messagerie'; ?>
            </div>-->
            <div id="bd" >BASE DE DONNEE</div>
            <div id="mssg" style="display: none" >MESSAGERIES</div>
            <hr style="color: #5D8BA2">
            <br>
            <table>
                <tr>
                    <td valign="top" style="text-align: left;width: 200px;"><?php echo TXT_LOGIN; ?></td>
                    <td>
                        <input  style="width: 318px;margin-left: 50px" type="text" name="login" 
                                onfocus="masqueBtn()" id="login" data-dojo-type="dijit/form/ValidationTextBox"  />
                    </td>
                </tr>
                <tr>
                    <td valign="top" style="text-align: left;width: 200px;"><?php echo TXT_MOTPASSE; ?></td>
                    <td>
                        <input  style="width: 318px;margin-left: 50px" type="text" name="password" id="password" onfocus="masqueBtn()" 
                                data-dojo-type="dijit/form/ValidationTextBox" />
                    </td>
                </tr>
                <tr>
                    <td valign="top"><?php echo "HOST"; ?></td>
                    <td>
                        <input type="text" required="required" style="width: 318px;margin-left: 50px" name="host" onfocus="masqueBtn()"
                               id="host" data-dojo-type="dijit/form/ValidationTextBox"  />
                    </td>
                </tr>     
                <tr>
                    <td valign="top"><?php echo "PORT"; ?> </td>
                    <td>
                        <input type="text" required="required" style="width: 318px;margin-left: 50px" id="port" name="port" onfocus="masqueBtn()"
                               data-dojo-type="dijit/form/ValidationTextBox"  />
                    </td>
                </tr>
            </table>            
            <script>
            function verifResultat(){
                $.ajax({
                   url : '../class/secure/verifDonneeConfig.php',
                   type : 'POST', 
                   data : 'login=' + $("#login").val() + '&password=' + $("#password").val()+'&host='+$("#host").val()+"&port="+$("#port").val(), 
                   dataType : 'html',
                   success : function(code_html, statut){
                       if(code_html==true){
                           $('#resultatModifOk').hide(); 
                           $('#resultatModifKo').hide(); 
                           $('#resultatVerifKo').hide();
                           $('#resultatVerifOk').show();
                           $('#subButton').show();
                        }else{
                            $('#resultatModifOk').hide(); 
                            $('#resultatModifKo').hide(); 
                            $('#resultatVerifOk').hide();
                            $('#resultatVerifKo').show();
                            $('#subButton').hide();
                            $('#resultatVerifKo').text(code_html);
                        }
                    },
                });
            }
            function modifDonneeConfig(){
                $.ajax({
                   url : '../modifBase/adminAppli.php',
                   type : 'POST', 
                   data : 'login=' + $("#login").val() + '&password=' + $("#password").val()+'&host='+$("#host").val()+"&port="+$("#port").val(), 
                   dataType : 'html',
                   success : function(code_html, statut){
                       if(code_html==true){
                           $('#subButton').hide();
                           $('#resultatVerifOk').hide();
                           $('#resultatModifKo').hide();
                           $('#resultatModifOk').show();
                           $("#login").val('');
                           $("#password").val('');
                           $("#host").val('');
                           $("#port").val('');
                           
                        }else{
                           $('#subButton').hide();
                           $('#resultatVerifOk').hide();
                           $('#resultatModifKo').show();
                           $('#resultatModifOk').hide();                           
                        }
                    },
                });
            }
            function masqueBtn(){
                $('#submitButton').hide();
            }
            </script>
            <button data-dojo-type="dijit/form/Button"  style="margin-top: 25px"  name="verifButton" id="verifButton" onclick="verifResultat()"><?php echo TXT_VERIFIER; ?></button>
            <div id="subButton" style="display: none;margin-top: -57px;margin-left: 100px;">
                <button data-dojo-type="dijit/form/Button" style="margin-top: 25px;" type="button" name="submitButton" onclick="modifDonneeConfig()" >
                    <?php echo TXT_MODIFIER; ?>
                </button></div>            
            <div id="resultatVerifOk" style="display: none;margin-top: -28px;margin-left: 259px;color: green;">Connexion établie</div>
            <div id="resultatVerifKo" style="display: none;margin-top: -28px;margin-left: 259px;color: red;"></div>
            <div id="resultatModifOk"  style="display: none;margin-top: -28px;margin-left: 259px;color: green;"><?php echo 'Les données de configuration de la base de donnée ont été mise à jour';?></div>
            <div id="resultatModifKo"  style="display: none;margin-top: -28px;margin-left: 259px;color: red;"><?php echo "Les données de configuration de la base de donnée n'ont pas été mise à jour!";?></div>
        </fieldset>
        <script>
            function change(id) {
                if (id == 'base_de_donnee') {
                    $('#bd').show();
                    $('#mssg').hide();
                } else if (id == 'messagerie') {
                    $('#bd').hide();
                    $('#mssg').show();
                }
            }
            
        </script> <?php include 'html/footer.html'; ?>
       
    </form>
    
</div>
<div>

</div>
</div>
</body>
</html>

