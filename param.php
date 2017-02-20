<?php
session_start();
include_once 'outils/toolBox.php';
include_once 'outils/constantes.php';
include 'decide-lang.php';
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
include 'html/header.html';
include_once 'class/Manager.php';
$db = BD::connecter();
$manager = new Manager($db);
?>
<div id="global">
    <?php
    include 'html/entete.html';
    ?>
    <div style="margin-top: 70px;">
        <?php include_once 'outils/bandeaucentrale.php'; ?>
    </div> 
    <form data-dojo-type="dijit/form/Form" name="exportProjet" id="exportProjet" method="post"  action="<?php echo '/' . REPERTOIRE; ?>/modifBase/updateTmpConnexion.php?lang=<?php echo $lang; ?>"  >
        <fieldset id="param" style=""  >
            <legend style="color: #5D8BA2;font-size: 1.2em"><b><?php echo TXT_PARAMTSCX; ?></b></legend>
            <table>
                <?php if (isset($_GET['update'])&& $_GET['update']!='e') { ?><tr><td><div style="margin-left:300px;margin-top:-45px;text-align: center;color: red"><?php echo TXT_TXUPDATE; ?></div></td></tr><?php } ?>                
                <tr>
                <script>
                    require(["dijit/form/HorizontalSlider", "dijit/form/HorizontalRuleLabels", "dijit/form/HorizontalRule", "dijit/form/Button", "dojo/dom-construct", "dojo/aspect", "dijit/registry", "dojo/parser", "dojo/dom", "dojo/domReady!"],
                            function (HorizontalSlider, HorizontalRuleLabels, HorizontalRule, Button, domConstruct, aspect, registry, parser, dom) {
                                parser.parse();
                                var hSlider = registry.byId("hslider"), label = dom.byId("decValue");
                                function updateHorizontalLabel() {
                                    label.innerHTML = Math.round(hSlider.get("value"));
                                    document.getElementById('valeur').value = Math.round(hSlider.get("value"));
                                    if (Math.round(hSlider.get("value")) <= 60) {
                                        document.getElementById('test').style.backgroundColor = 'green';
                                    } else if (Math.round(hSlider.get("value")) > 60 && Math.round(hSlider.get("value")) < 130) {
                                        document.getElementById('test').style.backgroundColor = 'yellow';
                                    } else if (Math.round(hSlider.get("value")) >= 130 && Math.round(hSlider.get("value")) < 180) {
                                        document.getElementById('test').style.backgroundColor = 'orange';
                                    } else {
                                        document.getElementById('test').style.backgroundColor = 'red';
                                    }
                                }
                                aspect.after(hSlider, "onChange", updateHorizontalLabel, true);
                                updateHorizontalLabel();

                            });
                </script>
                <input type="text" style="display: none" id="valeur" name="valeur" />
                <?php $valeurTmp = $manager->getSingle2("select tmpcx from loginpassword where pseudo=?", $_SESSION['pseudo']); ?>
                <td>
                    <div style="width: 420px;margin-left: 300px;margin-top:-20px;font-weight: bold;color:darkblue;font-size: 1.1em"><?php echo 'Sélectionnez le temps de connexion maxi en minutes' . ':' ?></div>
                    <div style="width: 420px;margin-left: 300px;margin-top:30px">
                        <input id="hslider" type="range" value="<?php echo $valeurTmp; ?>" id="toto"  data-dojo-type="dijit/form/HorizontalSlider" data-dojo-props="minimum: 30,maximum: 240,showButtons: false,discreteValues: 30">                            
                        <div data-dojo-type="dijit/form/HorizontalRule" id='test' data-dojo-props="container: 'bottomDecoration',count: 3" style="height: 5px; margin: 0 12px;"></div>
                        <ol data-dojo-type="dijit/form/HorizontalRuleLabels"  data-dojo-props="container: 'bottomDecoration'" style="height: 1.2em; font-weight: bold;color:darkblue"><li>Normal</li><li>Elevé</li><li>Trés élevé</li></ol>
                        <div style="padding-top: 10px; text-align: center;"><?php echo TXT_VALUETCX; ?><strong id="decValue"></strong></div>
                    </div>
                </td>
                <td><input type="submit"   label="<?php echo TXT_VALIDER; ?>" data-dojo-Type="dijit.form.Button" data-dojo-type="dijit/form/Button" style="margin-top: -38px; margin-left: 70px; height: 28px; text-align: center; font-size: 1.2em;" /></td>
                </tr>
            </table>
        </fieldset>
    </form>
    <?php if(IDTYPEUSER == ADMINNATIONNAL){ ?>
    <form data-dojo-type="dijit/form/Form" name="emailFaisabilite" id="emailFaisabilite" method="post"  action="<?php echo '/' . REPERTOIRE; ?>/modifBase/updateEmailFaisabilite.php?lang=<?php echo $lang; ?>"  >
        <fieldset id="emailfaisabilite" >            
             <legend style="color: #5D8BA2;font-size: 1.2em"><b><?php echo TXT_EMAILFAISABILITE; ?></b></legend>
            <?php if (isset($_GET['update'])&& $_GET['update']=='e') { ?><tr><td><div style="color: red;margin-left: 580px;text-align: center;width: 150px;"><?php echo TXT_TXEMAILUPDATE; ?></div></td></tr><?php } ?>                
                 <?php
            $idemail = $manager->getSingle("select max(idemail) from emailrenatech");
            $emailfaisabilite = $manager->getSingle2("select email from emailrenatech where idemail=?", $idemail);
            ?>
             <input data-dojo-type="dijit/form/ValidationTextBox" value="<?php echo $emailfaisabilite; ?>" style="color: #5D8BA2;text-align: left;width:480px;height:30px;margin-left:10px"  id="emailfaisabilite" 
                    name="emailfaisabilite" regExpGen="dojox.validate.regexp.emailAddress" required="required" invalidMessage="<?php echo TXT_EMAILNONVALIDE ?>" autocomplete="on"  placeHolder ="<?php echo TXT_EMAIL; ?>" >               
                <input type="submit"   label="<?php echo TXT_VALIDER; ?>" data-dojo-Type="dijit.form.Button" data-dojo-type="dijit/form/Button" 
                           style="font-size: 1.2em;height: 28px;margin-left: 100px;text-align: center;" /></td>
                
        </fieldset>
    </form>
    <?php } ?>
<?php include 'html/footer.html'; ?>
</div>

</body>
</html>
