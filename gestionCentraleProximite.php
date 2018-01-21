<?php
session_start();
include_once 'outils/constantes.php';
include_once 'outils/toolBox.php';

if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/'.$lang);
}
include 'outils/parser.php';
include_once 'class/Manager.php';
include 'class/Securite.php';
include 'html/header.html';
include_once  'class/Cache.php';
define('ROOT',  dirname(__FILE__));
$cache = new Cache(ROOT.'/cache', 60);
?>
<div id="global">
    
    <?php include 'html/entete.html';?>
       <div style="margin-top: 70px;">           
           <?php  include 'outils/bandeaucentrale.php';?>    
           
           <?php         
$idcentrale =$manager->getSingle2("SELECT idcentrale_centrale FROM  utilisateur,loginpassword  WHERE idlogin = idlogin_loginpassword and pseudo=?",$_SESSION['pseudo']);
$row = $manager->getList("select nom,prenom,mail,idutilisateur from utilisateur,loginpassword where idlogin = idlogin_loginpassword AND idqualitedemandeurindust_qualitedemandeurindust is null ORDER BY nom ASC");

$fprow = fopen('tmp/compteUtilisateur'.IDCENTRALEUSER.'.json', 'w');
$datausercompte = "";
for ($i = 0; $i < count($row); $i++) {
    $datausercompte = "" . '{"nom":' . '"' . ucfirst(strtolower($row[$i]['nom'])) . '"' . "," .
            '"prenom":' . '"' . ucfirst(strtolower($row[$i]['prenom'])) . '"' . "," .
            '"id":' . '"' . $row[$i]['idutilisateur'] . '"' . "," .
            '"mail":' . '"' . $row[$i]['mail'] . '"'  . "},";
    fputs($fprow, $datausercompte);
    fwrite($fprow, '');
}
$json_filecompteUtilisateur = 'tmp/compteUtilisateur'.IDCENTRALEUSER.'.json';
$jsonusercompte1 = file_get_contents($json_filecompteUtilisateur);
$jsonUsercompte = substr($jsonusercompte1,0,-1 );
file_put_contents($json_filecompteUtilisateur, $jsonUsercompte);
fclose($fprow);
chmod('tmp/compteUtilisateur'.IDCENTRALEUSER.'.json', 0777);
?>
<form id='administ2' name='administ2' method="post" action ="<?php echo '/' . REPERTOIRE . '/'; ?>controler/ctrladminLocalCP.php?lang=<?php echo $lang; ?>" data-dojo-type="dijit/form/Form" >
    <input name="page_precedente" type="hidden" value="<?php echo basename(__FILE__); ?>">
    <script type="dojo/method" data-dojo-event="onSubmit">
        if(this.validate()){
        return true;
        }else{
        alert('<?php echo TXT_MESSAGEERREURCONTACT; ?>');
        return false;
        exit();
        }
    </script>
    <?php  if(isset($_GET['cpupdate'])){?>
    <div class='msginfo'><?php echo TXT_MESSAGESERVEURUPDATECENTRALEP;  ?></div>
    <?php } ?>
    <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------
    CENTRALE DE PROXIMITE
----------------------------------------------------------------------------------------------------------------------------------------------------------------------      -->
    <fieldset id="identcentraleProximite"  class="centraleProximite">
        <legend><?php echo TXT_CENTRALEPROXIMITE; ?></legend>
<!-- ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------            
<!---                                                                           MODIFIER UNE CENTRALE DE PROXIMITE                                                                              -->            
<!-- --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->                
            <table>
                <tr>
                    <th>
                        <input type="text"  style="height:20px;margin-left:10px;background-color: white; width: 360px;vertical-align:middle;border: 0px" data-dojo-type="dijit/form/ValidationTextBox" readonly="true" ></th>
                <input type="text" name="idlibellecentraleProximiteactuel" style="display: none" data-dojo-type="dijit/form/ValidationTextBox" readonly="true"
                       value="<?php if (isset($_GET['idcentraleproximitemodif'])) {
                echo $_GET['idcentraleproximitemodif'];
            } ?>" />
                </tr>
                <tr>
                    <th>
                        <?php
                        $rowcentraleProximite = $manager->getList2("SELECT cp.libellecentraleproximite,cp.idcentraleproximite from centraleProximite cp"
                                . " LEFT JOIN centraleregion cr ON cr.idregion  =cp.idregion WHERE cr.idcentrale =? order by libellecentraleProximite asc;",IDCENTRALEUSER);
                        ?>
                        <select  id="libellecentraleProximite" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'libellecentraleProximite',value: '',
                                 required:false,placeHolder: '<?php echo TXT_SELECTCENTRALEPROXIMITE; ?>'" style="width: 330px;margin-left:20px;" 
                                 onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/admin_centrale_proximite/<?php echo $lang ?>/' + this.value)" >
                                     <?php
                                     for ($i = 0; $i < count($rowcentraleProximite); $i++) {
                                         echo '<option value="' . ($rowcentraleProximite[$i]['idcentraleproximite']) . '">' . removeDoubleQuote($rowcentraleProximite[$i]['libellecentraleproximite']) . '</option>';
                                     }
                                     ?>
                        </select>
                    </th>
                    <th>
                        <input id="modifcentraleProximite" type="text" name="modifcentraleProximite" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_CENTRALEPROXIMITE; ?>" 
                               style="height:24px;width: 380px;vertical-align:middle;" onfocus="enabledmodif('modifCentraleProximite')"
                               value="<?php
                               if (isset($_GET['idcentraleproximitemodif'])) {
                                   echo removeDoubleQuote($manager->getSingle2("select libellecentraleproximite from centraleproximite where idcentraleproximite=?", $_GET['idcentraleproximitemodif']));
                               }
                               ?>"
                               data-dojo-props="<?php echo REGEX_TYPESTRINGLISTE ?>" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >
                    </th>
                    <th>
                        <input id="regioncorrespondante" type="text" name="regioncorrespondante" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_REGION; ?>" 
                               style="height:24px;margin-left:20px; width: 200px;vertical-align:middle;background: lightgoldenrodyellow" readonly="true"
                               value="<?php
                               if (isset($_GET['idcentraleproximitemodif'])) {
                                   echo removeDoubleQuote($manager->getSingle2("SELECT libelleregion FROM centraleproximite c,region r WHERE c.idregion = r.idregion and idcentraleproximite=?", $_GET['idcentraleproximitemodif']));
                               }
                               ?>"
                               data-dojo-props="<?php echo REGEX_TYPESTRING ?>" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >
                    </th>
                </tr>
                </table>
                    <fieldset style="margin-top: 20px" class="flstbrd">
                        <legendt style="margin-left:20px;" >Responsable de la centrale de proximité</legendt>
                        <table> 
                            <tr>
                                <th>
                                    <input id="emailCentraleP" type="text" name="emailCentraleP" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_MAIL; ?>"
                                           style='height:24px;margin-left:20px; width: 300px;vertical-align:middle;margin-top: 20px;background: lightgoldenrodyellow' readonly="true" regExpGen="dojox.validate.regexp.emailAddress"
                                            onclick="dijit.byId('idResponsableCentraleProximite').show()" value="<?php
                                            if (isset($_GET['idcentraleproximitemodif'])) {
                                                $idutilisateur =$manager->getSingle2("SELECT id_responsable_centrale_proximite FROM centraleproximite WHERE idcentraleproximite =?",$_GET['idcentraleproximitemodif']);
                                                echo removeDoubleQuote($manager->getSingle2("SELECT mail FROM utilisateur,loginpassword WHERE idlogin_loginpassword = idlogin AND idutilisateur=?", $idutilisateur));
                                            }
                                            ?>" />
                                </th> 
                                   <th>
                                    <input id="nomCentraleP" type="text" name="nomCentraleP" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_NOM; ?>"
                                            style='height:24px;margin-left:20px; width: 300px;vertical-align:middle;margin-top: 20px;background: lightgoldenrodyellow' readonly="true"  
                                            value="<?php
                                                    if (isset($_GET['idcentraleproximitemodif'])) {
                                                        $idutilisateur =$manager->getSingle2("SELECT id_responsable_centrale_proximite FROM centraleproximite WHERE idcentraleproximite =?",$_GET['idcentraleproximitemodif']);
                                                        echo removeDoubleQuote($manager->getSingle2("SELECT nom FROM utilisateur WHERE idutilisateur=?", $idutilisateur));
                                                    }
                                                    ?>"
                                                     />
                                </th> 
                                <th>                    
                                    <button type="button" data-dojo-type="dijit/form/Button" style="margin-left:20px;margin-top:20px"  id='ajouteResponsabelCentraleProximite' 
                                            name="ajouteResponsabelCentraleProximite"  data-dojo-props="onClick:function(){affecteResponsable()}"><?php echo "Modifier"; ?></button>

                                </th>
                            </tr>
                            <tr>
                                <th>                    
                                    <button type="submit" data-dojo-type="dijit/form/Button" style="margin-left:20px;margin-top:20px;" disabled="true" id='modifCentraleProximite' name="modifCentraleProximite" ><?php echo TXT_VALIDER; ?></button>
                                </th>
                            </tr>
                        </table>
                            <input type="hidden" id="idutilisateur" name='idutilisateur' value="non">
                    </fieldset>
                <table>                

               
        </table>
    </fieldset>
        <script>
    dojo.require("dijit.Dialog");
    dojo.require("dojox.grid.DataGrid");
    dojo.require("dojo.store.Memory");
    dojo.require("dojo.data.ObjectStore");
    
    
    function hideDialog() {
        dijit.byId("myDialogCP").hide();
    }
</script>
        <div id='divNodeID' style="display:none;padding: 10px;margin-left:10px">
    <script>
        function filtreprenom(grid,param,id){
            dijit.byId('nom').set('value','');
            dijit.byId(grid).filter({param: ''+id.value+''+'*'});
            document.getElementById('filtrePrenom').value='oui';
            document.getElementById('filtreNom').value='non';
        }
        function filtrenom(grid,param,id){
            dijit.byId('prenom').set('value','');
            dijit.byId(grid).filter({param: ''+id.value+''+'*'});
            document.getElementById('filtrePrenom').value='nom';
            document.getElementById('filtreNom').value='oui';
        
        }
    </script>
    <input type="hidden"  id="filtreNom" value="non">
    <input type="hidden"  id="filtrePrenom" value="non">
    
        <table>
            <tr>
                <td><input data-dojo-type='dijit/form/ValidationTextBox' name='nom' id="nom" style="width:150px;margin-left:5px" onfocus="dijit.byId('prenom').set('value','');"  onchange="filtrenom('myGrid','nom','nom')" placeholder="<?php echo TXT_NOM; ?>"></td>
                <td><div style="margin-left: 10px;margin-right: 10px;"><?php echo TXT_OU;?></div></td>
                <td>
                    <input data-dojo-type='dijit/form/ValidationTextBox' name='prenom' id='prenom' style="width:150px;margin-right: 5px;margin-left:5px" onfocus="dijit.byId('nom').set('value','');"  onchange="filtreprenom('myGrid','prenom','prenom')" 
                           placeholder="<?php echo TXT_PRENOM; ?>">
                </td>
                <td>
                    <div data-dojo-type="dijit.form.Button" style="margin-right: 10px;margin-left: 10px;"><?php echo TXT_FILTER;  ?>
                    <script type="dojo/method" data-dojo-event="onClick" data-dojo-args="evt">                                              
                            if(document.getElementById('filtreNom').value=='oui'){
                                dijit.byId('prenom').set('value','');
                                dijit.byId('myGrid').filter({nom: ''+(dijit.byId('nom').value)+''+'*'});
                            }else if(document.getElementById('filtrePrenom').value=='oui'){
                                dijit.byId('nom').set('value','');
                                dijit.byId('myGrid').filter({prenom: ''+dijit.byId('prenom').value+''+'*'});
                            }
                    </script>           
                    </div>
                </td>
                <td>
                    <div data-dojo-type="dijit.form.Button" ><?php echo TXT_ANNULE;?>
                    <script type="dojo/method" data-dojo-event="onClick" data-dojo-args="evt" >
                        dijit.byId("nom").set('value','');
                        dijit.byId("prenom").set('value','');
                    </script>
                    </div>
                </td>
                <td>
                    <div data-dojo-type="dijit.form.Button" style="margin-left: 10px;"><?php echo TXT_FERMER;?>
                    <script type="dojo/method" data-dojo-event="onClick" data-dojo-args="evt" >
                        hideDialog();
                    </script>
                    </div>
                </td>
            </tr>
        </table>
        </div>
    </fieldset>
    <input type="hidden" id="input_affecte_responsable" value="non">
    <input style="display:none" type="submit" value="" name="submit" id="submitId"/>
</form>

<script>
    function enabledmodif(id) {
        dijit.byId(id).setAttribute('disabled', false);
    }

</script>
<script>
//-----------------------------------------------------------------------------------------------------------------------
//                  FIN SELECTION DE L'AFFECTATION D'UN PORTEUR OU D'UN ADMINISTRATEUR
//---------------------------------------------------------------------------------------------------------------------------------------
    function affecteResponsable(){
        document.getElementById('divNodeID').style.display='block';
        document.getElementById('input_affecte_responsable').value='oui';
        if( dijit.byId("modifCentraleProximite")){
            enabledmodif('modifCentraleProximite');
        }
        var myDialogCP = dijit.byId('myDialogCP');
        if(!myDialogCP){
            myDialogCP = new dijit.Dialog({
                id:'myDialogCP',
                title:"<?php echo TXT_VALIDEUSER;?>",
                style:'width:650px;height:560px;font-size:1.2em;',
                content:dojo.byId("divNodeID")
            });
        }
        myDialogCP.show();
        var myMemoryStore = new dojo.store.Memory(
            {data:[
               <?php include 'tmp/compteUtilisateur'.IDCENTRALEUSER.'.json'   ;?>
            ]});
        var myObjectStore = new dojo.data.ObjectStore({objectStore:myMemoryStore });
        var myStructure = [           
            {name: "<?php echo 'Id';?>", field: "id", width: "50px"},
            {name: "<?php echo TXT_NOM;?>", field: "nom", width: "auto"},
            {name: "<?php echo TXT_PRENOM;?>", field: "prenom", width: "auto"}
        ]
        var myGrid = dijit.byId('myGrid');
        if(!myGrid){
            myGrid= new dojox.grid.DataGrid({
                id:'myGrid',
                store:myObjectStore ,
                structure:myStructure,
                style:'width:610px;height:450px;margin-left:20px'
            });
        }
//---------------------------------------------------------------------------------------------------------------------------------------
//                    EVENEMENT SUR LE LOAD DE LA BOITE DE DIALOGUE
//---------------------------------------------------------------------------------------------------------------------------------------
            dijit.byId("myGrid").filter({nom: "*"});
            dijit.byId('nom').set('value','');
            dijit.byId('prenom').set('value','');
            document.getElementById('filtrePrenom').value='non';
            document.getElementById('filtreNom').value='non';
//---------------------------------------------------------------------------------------------------------------------------------------
//                    SELECTION DE L'AFFECTATION D'UN PORTEUR OU D'UN ADMINISTRATEUR
//                    Gestion de l'évenement click sur le menu
//---------------------------------------------------------------------------------------------------------------------------------------
         dojo.connect(myGrid, 'onRowClick', function(e){
            var rowIndex = e.rowIndex;
            var item = myGrid.getItem(rowIndex);
            var idutilisateur = item.id;
            var nom = item.nom;
            var email = item.mail;
            dijit.byId('nomCentraleP').set('value',nom);
            dijit.byId('emailCentraleP').set('value',email);
            document.getElementById('idutilisateur').value=idutilisateur;
            if(document.getElementById('input_affecte_responsable').value==='oui'){
                reponseresponsable.show();
            }
            
            
        })
            
        dojo.place(myGrid.domNode, myDialogCP.containerNode, 'last');
            myGrid.startup();
}
</script>
<div data-dojo-type="dijit/Dialog" data-dojo-id="reponseresponsable" id ='reponseresponsable'
     title="<?php echo TXT_CONFIRMCENTRALPRESP; ?>" style="width: 380px;margin-left: 20px;display:none"  >
    <table class="dijitDialogPaneContentArea">
        <tr>
            <td><button type="submit" data-dojo-type="dijit/form/Button"  id="submitOui" data-dojo-props="onClick:function(){dojo.byId('submitId').click();}" > <?php echo TXT_OUI; ?></button></td>
            <td><button type="submit" data-dojo-type="dijit/form/Button"  id="submitNon" data-dojo-props="onClick:function(){reponseresponsable.hide();}">
                    <?php echo TXT_NON; ?></button></td>
            <td><button data-dojo-type="dijit/form/Button" type="submit" data-dojo-props="onClick:function(){reponseresponsable.hide();}" id="cancel"><?php echo TXT_ANNULE; ?></button></td>
        </tr>
    </table>
</div>
           
    </div>


<?php include 'html/footer.html'; ?>
</body>
</div>
</html>
