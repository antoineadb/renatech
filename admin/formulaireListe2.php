<?php
$idcentrale =$manager->getSingle2("SELECT idcentrale_centrale FROM  utilisateur,loginpassword  WHERE idlogin = idlogin_loginpassword and pseudo=?",$_SESSION['pseudo']);
$row = $manager->getList2("select nom,prenom,mail,idutilisateur from utilisateur,loginpassword where idlogin = idlogin_loginpassword AND idqualitedemandeuraca_qualitedemandeuraca= ? ORDER BY nom ASC", NONPERMANENT);
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
<form id='administ2' name='administ2' method="post" action ="<?php echo '/' . REPERTOIRE . '/'; ?>controler/ctrladmin.php?lang=<?php echo $lang; ?>" data-dojo-type="dijit/form/Form" >
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
    <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------
    CENTRALE DE PROXIMITE
----------------------------------------------------------------------------------------------------------------------------------------------------------------------      -->
    <fieldset id="identcentraleProximite" style="border-color: #5D8BA2; margin-right: 20px;margin-left: 6px;padding-left: 20px;padding-top: 5px">
        <legend><?php echo TXT_CENTRALEPROXIMITE; ?></legend>          
        <table>
            <tr>
                <th>                    
                    <select  id="choixActionCentraleProximite" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'libellecentraleProximite',value: '',placeHolder: '<?php echo TXT_SELECTVALUE; ?>',
                             required:false" style="width: 300px;margin-left:20px;" onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/ManageCentraleProximite/<?php echo $lang ?>/' + this.value)" >
                                 <?php
                                 echo '<option value="c0">' . TXT_ADDCP . '</option>';
                                 echo '<option value="c1">' . TXT_MODIFCP . '</option>';
                                 echo '<option value="c2">' . TXT_SHOWHIDECP . '</option>';
                                 echo '<option value="c3">' . TXT_ADDREGION . '</option>';
                                 echo '<option value="c4">' . TXT_MODIFREGION . '</option>';
                                 echo '<option value="c5">' . TXT_SHOWHIDEREGION . '</option>';
                                 ?>
                    </select>
                </th>
            </tr>
<!-- ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------            
<!---                                                                           AJOUTER UNE CENTRALE DE PROXIMITE                                                                              -->            
<!-- --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->
            <?php if (isset($_GET['choixcentraleproximite']) && $_GET['choixcentraleproximite'] == 'c0' || isset($_GET['idcentraleproximite']) || isset($_GET['rens']) || isset($_GET['cpns'])|| isset($_GET['cpexist'])
                    || isset($_GET['cpadd'])) { ?>
                
                <tr>
                    <th>
                        <input id="addCentraleProximite" type="text" name="addCentraleProximite" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_CENTRALEPROXIMITE; ?>" 
                               style="height:25px;margin-left:20px; width: 300px;vertical-align:middle;margin-top: 10px"
                               value="" data-dojo-props="<?php echo REGEX_TYPESTRINGLISTE ?>,required:true" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" onfocus="enabledmodif('ajouteCentraleProximite')">
                    </th>
                    <th>
                        <?php $rowRegion = $manager->getList("select libelleregion,idregion from region order by idregion asc"); ?>
                        <select  id="regionCorrespondante" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'regionCorrespondante',value: '',required:true,
                                 placeHolder: '<?php echo "Sélectionnez une région"; ?>'" style="width: 360px;margin-left:20px;margin-top:10px;height:24px"  >
                                     <?php
                                     for ($i = 0; $i < count($rowRegion); $i++) {
                                         echo '<option value="' . ($rowRegion[$i]['idregion']) . '">' . removeDoubleQuote($rowRegion[$i]['libelleregion']) . '</option>';
                                     }
                                     ?>
                        </select>
                    </th>
                <tr></table>
        <fieldset style="margin-top: 20px" class="flstbrd">
            <legendt style="margin-left:20px;" >Responsable de la centrale de proximité</legendt>
            <table> 
                <tr>
                    <th>
                        <input id="emailCentraleP" type="text" name="emailCentraleP" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_MAIL; ?>"
                               style='height:24px;margin-left:20px; width: 300px;vertical-align:middle;margin-top: 20px;background: lightgoldenrodyellow' readonly="true" regExpGen="dojox.validate.regexp.emailAddress"
                                onclick="dijit.byId('idResponsableCentraleProximite').show()" />
                    </th> 
                       <th>
                        <input id="nomCentraleP" type="text" name="nomCentraleP" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_NOM; ?>"
                                style='height:24px;margin-left:20px; width: 300px;vertical-align:middle;margin-top: 20px;background: lightgoldenrodyellow' readonly="true"  />
                    </th> 
                    <th>                    
                        <button type="button" data-dojo-type="dijit/form/Button" style="margin-left:20px;margin-top:25px;margin-bottom: 15px"  id='ajouteResponsabelCentraleProximite' 
                                name="ajouteResponsabelCentraleProximite"  data-dojo-props="onClick:function(){affecteResponsable()}"><?php echo "Ajouter"; ?></button>
                        
                    </th>
                </tr>
            </table>
                <input type="hidden" id="idutilisateur" name='idutilisateur' value="non">
        </fieldset>
        <table>
            <tr>
                    <th>                    
                        <button type="submit" data-dojo-type="dijit/form/Button" style="margin-left:20px;margin-top:25px;margin-bottom: 15px" disabled="true" id='ajouteCentraleProximite' 
                                name="ajouteCentraleProximite" ><?php echo TXT_VALIDER; ?></button>
                    </th>
                </tr>    
            <?php } ?>
<!-- ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------            
<!---                                                                           MODIFIER UNE CENTRALE DE PROXIMITE                                                                              -->            
<!-- --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->                
            <?php if (isset($_GET['choixcentraleproximite']) && $_GET['choixcentraleproximite'] == 'c1' || isset($_GET['idcentraleproximitemodif'])) { ?>
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
                        $rowcentraleProximite = $manager->getList("SELECT libellecentraleproximite,idcentraleproximite from centraleProximite  order by libellecentraleProximite asc;");
                        ?>
                        <select  id="libellecentraleProximite" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'libellecentraleProximite',value: '',
                                 required:false,placeHolder: '<?php echo TXT_SELECTCENTRALEPROXIMITE; ?>'" style="width: 330px;margin-left:20px;" 
                                 onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/ModifCentraleProximite/<?php echo $lang ?>/' + this.value)" >
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
                <?php } elseif (isset($_GET['choixcentraleproximite']) && $_GET['choixcentraleproximite'] == 'c2' || isset($_GET['idcentraleproximitemasque'])) { ?>
                <!--- MASQUER UNE CENTRALE DE PROXIMITE-->
                <tr>
                    <th>
                        <input type="text"  style="height:20px;margin-left:10px;background-color: white; width: 360px;vertical-align:middle;border: 0px" data-dojo-type="dijit/form/ValidationTextBox" readonly="true" ></th>
                <input type="text" name="idlibellecentraleProximiteactuel" style="display: none" data-dojo-type="dijit/form/ValidationTextBox" readonly="true"
                       value="<?php if (isset($_GET['idcentraleproximitemasque'])) {
                echo $_GET['idcentraleproximitemasque'];
            } ?>" />
                </tr>
                <tr>
                    <th>
                        <?php
                        $rowcentraleProximite = $manager->getList("SELECT libellecentraleproximite,idcentraleproximite from centraleProximite  order by libellecentraleProximite asc;");
                        ?>
                        <select  id="libellecentraleProximite" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'libellecentraleProximite',value: '',required:false,placeHolder: '<?php echo TXT_SELECTCENTRALEPROXIMITE; ?>'"
                                 style="width: 300px;margin-left:20px;" onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/MasqueCentraleProximite/<?php echo $lang ?>/' + this.value)" >
                                     <?php
                                     for ($i = 0; $i < count($rowcentraleProximite); $i++) {
                                         echo '<option value="' . ($rowcentraleProximite[$i]['idcentraleproximite']) . '">' . removeDoubleQuote($rowcentraleProximite[$i]['libellecentraleproximite']) . '</option>';
                                     }
                                     ?>
                        </select>
                    </th>
                    <th>
                        <input id="masquecentraleproximite" type="text" name="masquecentraleproximite" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_CENTRALEPROXIMITE; ?>" 
                               style="height:20px;width: 380px;vertical-align:middle;" 
                               value="<?php
                               if (isset($_GET['idcentraleproximitemasque'])) {
                                   echo removeDoubleQuote($manager->getSingle2("select libellecentraleproximite from centraleproximite where idcentraleproximite=?", $_GET['idcentraleproximitemasque']));
                               }
                               ?>"
                               data-dojo-props="<?php echo REGEX_TYPESTRINGLISTE ?>" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >
                    </th>
                    <th>
                        <input id="regioncorrespondante" type="text" name="regioncorrespondante" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_REGION; ?>" 
                               style="height:20px;margin-left:20px; width: 220px;vertical-align:middle;background: lightgoldenrodyellow" readonly="true"
                               value="<?php
                               if (isset($_GET['idcentraleproximitemasque'])) {
                                   echo removeDoubleQuote($manager->getSingle2("SELECT libelleregion FROM centraleproximite c,region r WHERE c.idregion = r.idregion and idcentraleproximite=?", $_GET['idcentraleproximitemasque']));
                               }
                               ?>"
                               data-dojo-props="<?php echo REGEX_TYPESTRING ?>" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >
                    </th>
                </tr>
                <?php if (isset($_GET['idcentraleproximitemasque'])) { ?>
                    <?php $bool = $manager->getSingle2("select masquecentraleproximite from centraleproximite where idcentraleproximite=?", $_GET['idcentraleproximitemasque']); ?>
        <?php if ($bool) { ?>            
                        <tr>
                            <th>                    
                                <button type="submit" data-dojo-type="dijit/form/Button" style="margin-left:20px;margin-top:25px;margin-bottom: 15px" disabled="true" id='masqueCentraleProximite' name="masqueCentraleProximite" ><?php echo TXT_MASQUER; ?></button>
                                <button type="submit" data-dojo-type="dijit/form/Button" style="margin-left:20px;margin-top:25px;margin-bottom: 15px" id='afficheCentraleProximite' name="afficheCentraleProximite" ><?php echo TXT_REAFFICHER; ?></button>
                        <div style="margin-left: 20px;color:midnight-blue;font-size:12px"><?php echo TXT_CPMASQUE; ?></div>
                        </th>
                        </tr>
        <?php } else { ?>
                        <tr>
                            <th>                    
                                <button type="submit" data-dojo-type="dijit/form/Button" style="margin-left:20px;margin-top:25px;margin-bottom: 15px" id='masqueCentraleProximite' name="masqueCentraleProximite" ><?php echo TXT_MASQUER; ?></button>
                                <button type="submit" data-dojo-type="dijit/form/Button" style="margin-left:20px;margin-top:25px;margin-bottom: 15px" disabled="true" id='afficheCentraleProximite' name="afficheCentraleProximite" ><?php echo TXT_REAFFICHER; ?></button>
                            </th>
                        </tr>
                    <?php } ?>
    <?php } else { ?>
                    <tr>
                        <th>                    
                            <button type="submit" data-dojo-type="dijit/form/Button" style="margin-left:20px;margin-top:25px;margin-bottom: 15px" disabled="true" id='masqueCentraleProximite' name="masqueCentraleProximite" ><?php echo TXT_MASQUER; ?></button>
                            <button type="submit" data-dojo-type="dijit/form/Button" style="margin-left:20px;margin-top:25px;margin-bottom: 15px" disabled="true" id='afficheCentraleProximite' name="afficheCentraleProximite" ><?php echo TXT_REAFFICHER; ?></button>
                        </th>
                    </tr>
                <?php } ?>
<?php } elseif (isset($_GET['choixcentraleproximite']) && $_GET['choixcentraleproximite'] == 'c3' || isset($_GET['idregion'])) { ?>
                <!--- AJOUTER UNE REGION -->
                <tr>
                    <th>
                        <input id="region" type="text" name="region" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo 'Région'; ?>" 
                               style="height:25px;margin-left:20px; width: 300px;vertical-align:middle;margin-top: 10px"
                               value="" data-dojo-props="<?php echo REGEX_TYPESTRINGLISTE ?>,required:true" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" onfocus="enabledmodif('ajouteRegion')">
                    </th>
                    <th>                    
                        <button type="submit" data-dojo-type="dijit/form/Button" style="margin-left:20px;margin-top:25px;margin-bottom: 15px" disabled="true" id='ajouteRegion' name="ajouteRegion" ><?php echo TXT_VALIDER; ?></button>
                    </th>

                </tr>


<?php } elseif (isset($_GET['choixcentraleproximite']) && $_GET['choixcentraleproximite'] == 'c4' || isset($_GET['idregionmodif'])) { ?>
                <!--- MODIFIER UNE REGION-->          
                <tr>               
                    <th>
                        <input type="text"  style="height:20px;margin-left:10px;background-color: white; width: 360px;vertical-align:middle;border: 0px" data-dojo-type="dijit/form/ValidationTextBox" readonly="true" >
                    </th>
                <input type="text" name="idregionActuel" style="display: none" data-dojo-type="dijit/form/ValidationTextBox" readonly="true"
                       value="<?php if (isset($_GET['idregionmodif'])) {
        echo $_GET['idregionmodif'];
    } ?>" />
                </tr>
                <tr>
                    <th>
    <?php 
    $rowRegion = $manager->getList("SELECT libelleregion,idregion from region  order by libelleregion asc;");
    ?>
                        <select  id="libellecentraleRgion" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'libellecentraleRgion',value: '',required:false,placeHolder: '<?php echo "Sélectionnez une région"; ?>'"
                                 style="width: 300px;margin-left:20px;" onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/ModifRegion/<?php echo $lang ?>/' + this.value)" >
                                     <?php
                                     for ($i = 0; $i < count($rowRegion); $i++) {
                                         echo '<option value="' . ($rowRegion[$i]['idregion']) . '">' . removeDoubleQuote($rowRegion[$i]['libelleregion']) . '</option>';
                                     }
                                     ?>
                        </select>
                    </th>
                    <th>
                        <input id="modifregion" type="text" name="modifregion" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo "Région"; ?>" 
                               style="height:20px;width: 380px;vertical-align:middle;" onfocus="enabledmodif('modifRegion')"
                               value="<?php
                               if (isset($_GET['idregionmodif'])) {
                                   echo removeDoubleQuote($manager->getSingle2("select libelleregion from region where idregion=?", $_GET['idregionmodif']));
                               }
                               ?>"
                               data-dojo-props="<?php echo REGEX_TYPESTRINGLISTE ?>" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >
                    </th>                
                </tr>
                <tr>
                    <th>                    
                        <button type="submit" data-dojo-type="dijit/form/Button" style="margin-left:20px;margin-top:25px;margin-bottom: 15px" disabled="true" id='modifRegion' name="modifRegion" ><?php echo TXT_VALIDER; ?></button>
                    </th>
                    <th>
                         <label style="margin-top:20px;color: lightslategrey">Centrales associées</label>
                <div id="centraleassociee" style="width: 600px;"
                     data-dojo-props="extraPlugins:[{readOnly: true}, 'prettyprint']">
                    <?php
                               $sRegion="";
                               if (isset($_GET['idregionmodif'])) {
                                   $arrayregion = removeDoubleQuote($manager->getList2("select libellecentraleproximite from centraleproximite where idregion =?", $_GET['idregionmodif']));
                                   for ($i = 0; $i < count($arrayregion); $i++) {
                                       $sRegion .= $arrayregion[$i]['libellecentraleproximite'].", ";
                                   }
                               }                               
                               echo ltrim(substr($sRegion,0,-2));
                               ?>
                </div>
                        
                    </th>
                      <script>                            
                            require(["dojo/parser", "dijit/Editor", "dijit/_editor/plugins/ViewSource", "dojox/editor/plugins/PrettyPrint"]);
                            require(["dojo"], function(dojo) {
                            });
                            require(["dijit/Editor", "dojo/ready"], function(Editor, ready) {
                                ready(function() {
                                    new Editor({plugins: ["undo","redo"], height: "100px",disabled: 'true',}, "centraleassociee");

                                });
                            });
                          </script>                   
                </tr>
<?php } elseif (isset($_GET['choixcentraleproximite']) && $_GET['choixcentraleproximite'] == 'c5' || isset($_GET['idregionmasque'])) { ?>
                <!-- MASQUE ET AFFICHE UNE REGION   -->             
                <tr>
                    <th>
                        <input type="text"  style="height:20px;margin-left:10px;background-color: white; width: 360px;vertical-align:middle;border: 0px" data-dojo-type="dijit/form/ValidationTextBox" readonly="true" ></th>
                <input type="text" name="idlibelleregionactuel" style="display: none" data-dojo-type="dijit/form/ValidationTextBox" readonly="true"
                       value="<?php if (isset($_GET['idregionmasque'])) {
        echo $_GET['idregionmasque'];
    } ?>" />
                </tr>
                <tr>
                    <th>
                                     <?php
                                     $rowregion = $manager->getList("SELECT libelleregion,idregion from region  order by libelleregion asc;");
                                     ?>
                        <select  id="libelleregion" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'libelleregion',value: '',required:false,placeHolder: '<?php echo "Selectionnez une région"; ?>'"
                                 style="width: 300px;margin-left:20px;" onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/MasqueRegion/<?php echo $lang ?>/' + this.value)" >
                                     <?php
                                     for ($i = 0; $i < count($rowregion); $i++) {
                                         echo '<option value="' . ($rowregion[$i]['idregion']) . '">' . removeDoubleQuote($rowregion[$i]['libelleregion']) . '</option>';
                                     }
                                     ?>
                        </select>
                    </th>
                    <th>
                        <input id="masqueregion" type="text" name="masqueregion" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_CENTRALEPROXIMITE; ?>" 
                               style="height:20px;width: 380px;vertical-align:middle;" 
                               value="<?php
                               if (isset($_GET['idregionmasque'])) {
                                   echo removeDoubleQuote($manager->getSingle2("select libelleregion from region where idregion=?", $_GET['idregionmasque']));
                               }
                               ?>"
                               data-dojo-props="<?php echo REGEX_TYPESTRINGLISTE ?>" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >
                    </th>                
                </tr>
    <?php if (isset($_GET['idregionmasque'])) { ?>
        <?php $bool = $manager->getSingle2("select masqueregion from region where idregion=?", $_GET['idregionmasque']); ?>
        <?php if ($bool) { ?>            
                        <tr>
                            <th>                    
                                <button type="submit" data-dojo-type="dijit/form/Button" style="margin-left:20px;margin-top:25px;margin-bottom: 15px" disabled="true" id='masqueRegion' name="masqueRegion" ><?php echo TXT_MASQUER; ?></button>
                                <button type="submit" data-dojo-type="dijit/form/Button" style="margin-left:20px;margin-top:25px;margin-bottom: 15px" id='afficheRegion' name="afficheRegion" ><?php echo TXT_REAFFICHER; ?></button>
                        <div style="margin-left: 20px;color:midnight-blue;font-size:12px"><?php echo TXT_REGIONMASQUE; ?></div>
                        </th>
                        </tr>
        <?php } else { ?>
                        <tr>
                            <th>                    
                                <button type="submit" data-dojo-type="dijit/form/Button" style="margin-left:20px;margin-top:25px;margin-bottom: 15px" id='masqueRegion' name="masqueRegion" ><?php echo TXT_MASQUER; ?></button>
                                <button type="submit" data-dojo-type="dijit/form/Button" style="margin-left:20px;margin-top:25px;margin-bottom: 15px" disabled="true" id='afficheRegion' name="afficheRegion" ><?php echo TXT_REAFFICHER; ?></button>
                            </th>
                        </tr>
        <?php } ?>
    <?php } ?>
<?php } ?>
        </table>
    </fieldset>
        
<!--------------------------------------------------------------------------------------------------------------------------------------------------------------------
TYPE PARTENAIRE
----------------------------------------------------------------------------------------------------------------------------------------------------------------------      -->

    <fieldset id="identte" style="border-color: #5D8BA2; margin-right: 20px;margin-left: 6px;padding-left: 20px;padding-top: 5px">
        <legend><?php echo TXT_TYPEPARTENAIRE ?></legend>
        <table>
            <tr>
                <th><input type="text"  style="height:20px;margin-left:20px;background-color: white; width: 360px;vertical-align:middle;border: 0px" data-dojo-type="dijit/form/ValidationTextBox" readonly="true" ></th>
            <input type="text" name="idtypepartenaireactuel" style="display: none" data-dojo-type="dijit/form/ValidationTextBox" readonly="true"
                   value="<?php
                   if (isset($_GET['idtypepartenaire'])) {
                       echo $_GET['idtypepartenaire'];
                   }
                   ?>" />
            </tr>
            <tr>
                <th>
                    <?php
                    if ($lang == 'fr') {
                        $rowtypepartenaire = $manager->getList("SELECT libelletypepartenairefr,idtypepartenaire from typepartenaire  order by idtypepartenaire asc;");
                    } elseif ($lang == 'en') {
                        $rowtypepartenaire = $manager->getList("SELECT libelletypepartenaireen,idtypepartenaire from typepartenaire  order by idtypepartenaire asc;");
                    }
                    ?>
                    <select  id="libelletypepartenairefr" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'libelletypepartenairefr',value: '',required:false,placeHolder: '<?php echo TXT_SELECTTYPEPARTENAIRE; ?>'"
                             style="width: 360px;margin-left:20px;"    onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/Manage_typepart/<?php echo $lang ?>/' + this.value)" >
                                 <?php
                                 if ($lang == 'fr') {
                                     for ($i = 0; $i < count($rowtypepartenaire); $i++) {
                                         echo '<option value="' . ($rowtypepartenaire[$i]['idtypepartenaire']) . '">' . removeDoubleQuote($rowtypepartenaire[$i]['libelletypepartenairefr']) . '</option>';
                                     }
                                 } elseif ($lang == 'en') {
                                     for ($i = 0; $i < count($rowtypepartenaire); $i++) {
                                         echo '<option value="' . ($rowtypepartenaire[$i]['idtypepartenaire']) . '">' . removeDoubleQuote($rowtypepartenaire[$i]['libelletypepartenaireen']) . '</option>';
                                     }
                                 }
                                 ?>
                    </select>

                </th>
                <th>
                    <input id="modiftypepartenaire" type="text" name="modiftypePartenaire" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_TYPEPARTENAIRE ?>"
                           style="height:20px;margin-left:20px; width: 360px;vertical-align:middle;"
                           value="<?php
                           if (isset($_GET['idtypepartenaire'])) {
                               echo removeDoubleQuote($manager->getSingle2("select libelletypepartenairefr from typepartenaire where idtypepartenaire=?", $_GET['idtypepartenaire']));
                           }
                           ?>" 
                           data-dojo-props="<?php echo REGEX_TYPESTRINGLISTE; ?>" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >
                           <!--data-dojo-props="<?php echo REGEX_TYPESTRINGLISTE; ?>" -->
                </th>
            </tr>
            <tr>
                <td style="width: 360px;"></td>
                <th>
                    <input id="modiftypepartenaireen" type="text" name="modiftypepartenaireen" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_TYPEPARTENAIRE; ?>"
                           style="height:20px;margin-left:20px; width: 360px;vertical-align:middle;" value="<?php
                           if (isset($_GET['idtypepartenaire'])) {
                               echo $manager->getSingle2("select libelletypepartenaireen from typepartenaire where idtypepartenaire=?", $_GET['idtypepartenaire']);
                           }
                           ?>" data-dojo-props="<?php echo REGEX_TYPESTRINGLISTE; ?>" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >
                </th>
            </tr>
            <tr>
                <th>
                    <input class="admin" style="padding-left: 21px" type="submit" name="modiftypepartenaire"  value= "<?php echo TXT_MODIFIER; ?>" >
                    <input class="admin" style="padding-left: 21px" type="submit" name="ajoutetypepartenaire" value="<?php echo TXT_AJOUTER; ?>" />
                </th>
                <th>
                    <input class="admin" style="padding-left: 20px" type="submit" name="masquetypepartenaire" value="<?php echo TXT_MASQUER; ?>"/>
                    <input class="admin" style="padding-left: 20px" type="submit" name="affichetypepartenaire" value="<?php echo TXT_REAFFICHER; ?>"/>
                </th>
            </tr>
            <tr>
                <th style="padding-left:22px;">
                    <?php
                    if (isset($_GET['idtypepartenaire'])) {
                        $masque = $manager->getSingle2("select masquetypepartenaire from typepartenaire where idtypepartenaire=?", $_GET['idtypepartenaire']);
                        infoLibelle($masque);
                    }
                    ?>
                </th>
            </tr>
        </table>
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