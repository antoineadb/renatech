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
                                 echo '<option value=c0>' . TXT_ADDCP . '</option>';
                                 echo '<option value=c1>' . TXT_MODIFCP . '</option>';
                                 echo '<option value=c2>' . TXT_SHOWHIDECP . '</option>';
                                 echo '<option value=c3>' . TXT_ADDREGION . '</option>';
                                 echo '<option value=c4>' . TXT_MODIFREGION . '</option>';
                                 echo '<option value=c5>' . TXT_SHOWHIDEREGION . '</option>';
                                 ?>
                    </select>
                </th>
            </tr>
            <?php if (isset($_GET['choixcentraleproximite']) && $_GET['choixcentraleproximite'] == 'c0' || isset($_GET['idcentraleproximite']) || isset($_GET['rens']) || isset($_GET['cpns'])|| isset($_GET['cpexist'])
                    || isset($_GET['cpadd'])) { ?>
                <!--- AJOUTER UNE CENTRALE DE PROXIMITE-->
                <tr>
                    <th>
                        <input id="addCentraleProximite" type="text" name="addCentraleProximite" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_CENTRALEPROXIMITE; ?>" 
                               style="height:25px;margin-left:20px; width: 300px;vertical-align:middle;margin-top: 10px"
                               value="" data-dojo-props="<?php echo REGEX_TYPESTRINGLISTE ?>,required:true" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" onfocus="enabledmodif('ajouteCentraleProximite')">
                    </th>
                    <th>
                        <?php $rowRegion = $manager->getList("select libelleregion,idregion from region order by idregion asc"); ?>
                        <select  id="regionCorrespondante" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'regionCorrespondante',value: '',required:true,
                                 placeHolder: '<?php echo "Sélectionnez une région"; ?>'" style="width: 360px;margin-left:20px;margin-top:10px"  >
                                     <?php
                                     for ($i = 0; $i < count($rowRegion); $i++) {
                                         echo '<option value="' . ($rowRegion[$i]['idregion']) . '">' . removeDoubleQuote($rowRegion[$i]['libelleregion']) . '</option>';
                                     }
                                     ?>
                        </select>
                    </th>

                    <th>                    
                        <button type="submit" data-dojo-type="dijit/form/Button" style="margin-left:20px;margin-top:25px;margin-bottom: 15px" disabled="true" id='ajouteCentraleProximite' 
                                name="ajouteCentraleProximite" ><?php echo TXT_VALIDER; ?></button>
                    </th>

                </tr>

            <?php } ?>
            <?php if (isset($_GET['choixcentraleproximite']) && $_GET['choixcentraleproximite'] == 'c1' || isset($_GET['idcentraleproximitemodif'])) { ?>
                <!--- MODIFIER UNE CENTRALE DE PROXIMITE-->
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
                                 required:false,placeHolder: '<?php echo TXT_SELECTCENTRALEPROXIMITE; ?>'" style="width: 300px;margin-left:20px;" 
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
                               style="height:20px;width: 380px;vertical-align:middle;" onfocus="enabledmodif('modifCentraleProximite')"
                               value="<?php
                               if (isset($_GET['idcentraleproximitemodif'])) {
                                   echo removeDoubleQuote($manager->getSingle2("select libellecentraleproximite from centraleproximite where idcentraleproximite=?", $_GET['idcentraleproximitemodif']));
                               }
                               ?>"
                               data-dojo-props="<?php echo REGEX_TYPESTRINGLISTE ?>" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >
                    </th>
                    <th>
                        <input id="regioncorrespondante" type="text" name="regioncorrespondante" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_REGION; ?>" 
                               style="height:20px;margin-left:20px; width: 220px;vertical-align:middle;background: lightgoldenrodyellow" readonly="true"
                               value="<?php
                               if (isset($_GET['idcentraleproximitemodif'])) {
                                   echo removeDoubleQuote($manager->getSingle2("SELECT libelleregion FROM centraleproximite c,region r WHERE c.idregion = r.idregion and idcentraleproximite=?", $_GET['idcentraleproximitemodif']));
                               }
                               ?>"
                               data-dojo-props="<?php echo REGEX_TYPESTRING ?>" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >
                    </th>
                </tr>
                <tr>
                    <th>                    
                        <button type="submit" data-dojo-type="dijit/form/Button" style="margin-left:20px;margin-top:25px;margin-bottom: 15px" disabled="true" id='modifCentraleProximite' name="modifCentraleProximite" ><?php echo TXT_VALIDER; ?></button>
                    </th>
                </tr>
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
                            //require(["dojo/parser", "dijit/Editor"]);
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
</form>

<script>
    function enabledmodif(id) {
        dijit.byId(id).setAttribute('disabled', false);
    }
</script>