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
    <fieldset id="ident" style="border-color: #5D8BA2;width: 1008px;padding-bottom:30px;padding-top:10px;font-size:1.2em" >
                <legend><?php echo 'Personnalisation E-Mail relance';?></legend>
                <?php 
                    if(isset($_GET['msgupdate'])){
                        echo "le mail à été mise à jour!";
                    } 
                    ?>
                <table style="float: left">
                    <tr>
                        <td>
                        <fieldset id="gestionCentraleProximite" style="margin-top: 25px">
                        <legend><?php echo "Email de relance";?></legend>
                        <div><u><?php echo TXT_FR_RELEASE ?></u></div><br>
                        <div id="customEmailFr" name="customEmailFr" >
                        <?php 
                            $labelfr =  $manager->getSingle2("SELECT libelleFrancais from libelleapplication where reflibelle=?","TXT_RELANCEEMAIL_".IDCENTRALEUSER."");
                            
                            if($labelfr!=null){
                                echo removeDoubleQuote($labelfr);
                            }else{
                              echo $manager->getSingle2("SELECT libelleFrancais from libelleapplication where reflibelle=?","TXT_RELANCEEMAIL");  
                            }
                        ?>                        
                        </div>
                        <br>
                        <div><u><?php echo TXT_EN_RELEASE ?></u></div><br>
                        <div id="customEmailEn" name="customEmailEn" >
                            <?php 
                            $labelen =  $manager->getSingle2("SELECT libelleAnglais from libelleapplication where reflibelle=?","TXT_RELANCEEMAIL_".IDCENTRALEUSER."");
                            if($labelen!=null){
                                echo removeDoubleQuote($labelen);
                            }else{
                              echo $manager->getSingle2("SELECT libelleAnglais from libelleapplication where reflibelle=?","TXT_RELANCEEMAIL");  
                            } ?>
                        </div>
                        <form data-dojo-type="dijit/form/Form" method="post" name="EmailRelance" id="EmailRelance" action="<?php echo '/'.REPERTOIRE.'/'; ?>modifBase/updateCentraleEmailCustom.php">
                            <input name="page_precedente" type="hidden" value="<?php echo basename(__FILE__); ?>">
                            <input type="hidden" name="modifEmailRelanceFR" id="modifEmailRelanceFR" />
                            <input type="hidden" name="modifEmailRelanceEN" id="modifEmailRelanceEN" />
                            <input type="hidden" name="idcentrale" id="idcentrale" value="<?php echo IDCENTRALEUSER;?>" />
                            <table>
                                <tr>
                                    <td>
                                        <input style="text-align: left;margin-left: 10px"  type="submit" id ="modifEmailRelance" name="modifEmailRelance"  label="<?php echo TXT_MODIFIER; ?>"   
                                               data-dojo-type="dijit/form/Button" disabled="disabled">                    
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </fieldset>   
                        </td>
                    </tr>
                    <tr>
                        <td>                                    
                         
                        </td>                      
                    </tr>
                </table>

<?php include 'html/footer.html'; ?>
    </div>
<script>
    function uploadFichier(id) {
        var file = document.getElementById(id).files[0];
        var data = new FormData();
        data.append('file', file);
        var ajax = new XMLHttpRequest();
        ajax.upload.addEventListener("progress", progressHandler, false);
        ajax.addEventListener("load", completeHandler, false);
        ajax.addEventListener("error", errorHandler, false);
        ajax.addEventListener("abort", abortHandler, false);
        ajax.open("POST", "<?php echo '/'.REPERTOIRE; ?>/outils/uploadLogoCentrales.php");
        ajax.send(data);
        enablemodifacceuil("modifsiteweb");
    }
    function progressHandler(event) {
        document.getElementById('progressBar').style.visibility = 'visible';
        document.getElementById('status_bytes').innerHTML = event.loaded + ' bytes uploaded on ' + event.total;
        var pourcentage = (event.loaded / event.total) * 100;
        document.getElementById('progressBar').value = Math.round(pourcentage);
        document.getElementById('status').innerHTML = Math.round(pourcentage) + '% uploaded... Wait';
        require(["dojo/dom-construct"], function (domConstruct) {
            domConstruct.destroy("cibleFigure");
        });
    }
    function completeHandler(event) {
        document.getElementById('status').innerHTML = event.target.responseText;
        document.getElementById('progressBar').value = 0;
        document.getElementById('progressBar').style.visibility = 'hidden';
        document.getElementById('status_bytes').innerHTML = '';
    }
    function errorHandler() {
        document.getElementById('status').innerHTML = "The upload failed !";
    }
    function abortHandler() {
        document.getElementById('status').innerHTML = "The upload was canceled !";
    }
    
    function enablemodifacceuil(id) {
        dijit.byId(id).setAttribute('disabled', false);
    }
    require(["dijit/form/ValidationTextBox", "dojo/parser", "dojox/validate/regexp","dijit/form/Form","dijit/form/Button"]);
    
    
    require(["dojo/parser", "dijit/Editor"]);
    require(["dijit/Editor", "dojo/ready"], function(Editor, ready) {
        ready(function() {    
            new Editor({
                plugins: ["undo", "redo", "|", "bold", "italic", "|", "underline", "strikethrough", "|", "indent", "outdent", "|", "justifyRight", "justifyLeft", "justifyCenter", "justifyFull" ],
                height: "300px",
                width: "500px"
            }, "customEmailFr");
            new Editor({
                plugins: ["undo", "redo", "|", "bold", "italic", "|", "underline", "strikethrough", "|", "indent", "outdent", "|", "justifyRight", "justifyLeft", "justifyCenter", "justifyFull" ],
                height: "300px",
                width: "500px"
            }, "customEmailEn");
        });
    });
    dojo.addOnLoad(function() {
        var editor1 = dijit.byId("customEmailFr");
        var editor2 = dijit.byId("customEmailEn");
        dojo.connect(editor1, "onClick", this, function(event) {
            enablemodifacceuil("modifEmailRelance");
            dojo.byId("modifEmailRelanceFR").value = editor1.get("value");            
        });
        dojo.connect(editor1, "onChange", this, function(event) {
            dojo.byId("modifEmailRelanceFR").value = editor1.get("value");        
        });
        dojo.connect(editor2, "onClick", this, function(event) {
            enablemodifacceuil("modifEmailRelance");            
            dojo.byId("modifEmailRelanceEN").value = editor2.get("value");
        });
        dojo.connect(editor2, "onChange", this, function(event) {
            dojo.byId("modifEmailRelanceEN").value = editor2.get("value");
        });
        
    });
</script>

