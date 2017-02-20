<?php if ($_SESSION['idTypeUser'] == ADMINLOCAL) { ?>            
    <fieldset id="identsf" style="border-color: blue;width:952px; margin-left: 20px;padding-left: 10px;margin-top:20px;padding-top: 5px">                
        <legend style="font-style: italic;color: blue;"><?php echo TXT_PARTIEEXPORT; ?></legend>
        <table>
            <?php
            $interneexterne = $manager->getSingle2("select interneexterne from projet where idprojet=?", $idprojet);
            if (!empty($interneexterne)) {
                if ($interneexterne == 'I') {
                    ?>
                    <tr>
                        <td>
                            <div style="float: left">
                                <label for="interneExterne" style="width: 260px;margin-left: 20px;margin-top:15px;font-style: italic;color: blue;" ><?php echo TXT_INTERNE . '/' . TXT_EXTERNE . ' :'; ?>
                                    <a class="infoBulle" href="#"><img src='<?php echo "/" . REPERTOIRE; ?>/styles/img/help.gif' height="13px" width="13px"/>
                                        <span style="text-align: left;padding:10px;width: 310px;border-radius:5px" ><?php echo affiche('TXT_AIDEINTERNEEXTERNE'); ?></span>
                                    </a>
                                </label>
                                <select name="interneExterne" id="interneExterne" data-dojo-type="dijit/form/Select" style="width: 220px;margin-left: 20px;font-style: italic;color: blue;" 
                                        onchange="if (this.value !== 'ie0') {
                                                    document.getElementById('messageInfo').style.display = 'block'
                                                } else {
                                                    document.getElementById('messageInfo').style.display = 'none';
                                                }" >
                                    <option value="ie0" ><?php echo TXT_SELECTTYPE; ?></option>;
                                    <option value="ie1" selected="selected" ><?php echo TXT_INTERNE ?></option>;
                                    <option value="ie2" ><?php echo TXT_EXTERNE ?></option>;
                                </select>
                            </div>
                            <div id="messageInfo" style="display: none;margin-top:33px;margin-left:20px;margin-left:20px;float: left"><?php echo affiche('TXT_AIDEINTERNEEXTERNE'); ?></div>
                        </td>
                    </tr>
        <?php } else if ($interneexterne == 'E') { ?>
                    <tr>
                        <td>
                            <div style="float: left">
                            <label for="interneExterne" style="width: 260px;margin-left: 20px;margin-top:15px;font-style: italic;color: blue;" ><?php echo TXT_INTERNE . '/' . TXT_EXTERNE . ' :'; ?>
                                <a class="infoBulle" href="#"><img src='<?php echo "/" . REPERTOIRE; ?>/styles/img/help.gif' height="13px" width="13px"/>
                                    <span style="text-align: left;padding:10px;width: 310px;border-radius:5px" ><?php echo affiche('TXT_AIDEINTERNEEXTERNE'); ?></span>
                                </a>
                            </label>
                            <select name="interneExterne" id="interneExterne" data-dojo-type="dijit/form/Select" style="width: 220px;margin-left: 20px;font-style: italic;color: blue;" 
                                    onchange="if (this.value !== 'ie0') {
                                                document.getElementById('messageInfo').style.display = 'block'
                                            } else {
                                                document.getElementById('messageInfo').style.display = 'none';
                                            }" >
                                <option value="ie0" ><?php echo TXT_SELECTTYPE; ?></option>;
                                <option value="ie1"  ><?php echo TXT_INTERNE ?></option>;
                                <option value="ie2" selected="selected"><?php echo TXT_EXTERNE ?></option>;
                            </select>                    
                        </div>
                            <div id="messageInfo" style="display: none;margin-top:33px;margin-left:20px;float: left"><?php echo affiche('TXT_AIDEINTERNEEXTERNE'); ?></div>
                        </td>
                    </tr>
        <?php } else { ?>
                    <tr>
                        <td>
                            <div style="float: left">
                            <label for="interneExterne" style="width: 260px;margin-left: 20px;margin-top:15px;font-style: italic;color: blue;" ><?php echo TXT_INTERNE . '/' . TXT_EXTERNE . ' :'; ?>
                                <a class="infoBulle" href="#"><img src='<?php echo "/" . REPERTOIRE; ?>/styles/img/help.gif' height="13px" width="13px"/>
                                    <span style="text-align: left;padding:10px;width: 310px;border-radius:5px" ><?php echo affiche('TXT_AIDEINTERNEEXTERNE'); ?></span>
                                </a>
                            </label>
                            <select name="interneExterne" id="interneExterne" data-dojo-type="dijit/form/Select" style="width: 220px;margin-left: 20px;font-style: italic;color: blue;" 
                                    onchange="if (this.value !== 'ie0') {
                                                document.getElementById('messageInfo').style.display = 'block'
                                            } else {
                                                document.getElementById('messageInfo').style.display = 'none';
                                            }" >
                                <option value="ie0" selected="selected"><?php echo TXT_SELECTTYPE; ?></option>;
                                <option value="ie1" ><?php echo TXT_INTERNE ?></option>;
                                <option value="ie2" ><?php echo TXT_EXTERNE ?></option>;
                            </select>  
                                </div>                            
                            <div id="messageInfo" style="display: none;margin-top:33px;margin-left:20px;float: left"><?php echo affiche('TXT_AIDEINTERNEEXTERNE'); ?></div>
                        </td>
                    </tr>
                                <?php } ?>
                <?php }else{
                $infos = $manager->getList2("SELECT u.idcentrale_centrale,p.porteurprojet FROM  utilisateur u,creer cr,projet p WHERE cr.idprojet_projet=p.idprojet and  cr.idutilisateur_utilisateur = u.idutilisateur and idprojet_projet = ?", $idprojet);
                if (!empty($infos[0]['idcentrale_centrale']) && $infos[0]['porteurprojet'] == TRUE) {//Si l'utilisateur est académique interne et si il est porteur
                    $interneExterne =   TXT_PROJETINTERNE;
                } else {
                    $interneExterne =   TXT_EXTERNE;
                }
                ?>
                <tr>
                    <td>
                        <div style="float: left" >
                        <label for="interneExterne" style="width: 260px;margin-left: 20px;margin-top:15px;font-style: italic;color: blue;" ><?php echo TXT_INTERNE . '/' . TXT_EXTERNE . ' :'; ?>
                            <a class="infoBulle" href="#"><img src='<?php echo "/" . REPERTOIRE; ?>/styles/img/help.gif' height="13px" width="13px"/>
                                <span style="text-align: left;padding:10px;width: 310px;border-radius:5px" ><?php echo affiche('TXT_AIDEINTERNEEXTERNE'); ?></span>
                            </a>
                        </label>                        
                        <select name="interneExterne" id="interneExterne" data-dojo-type="dijit/form/Select" style="width: 220px;margin-left: 20px;font-style: italic;color: blue;margin-right: 20px" 
                                onchange="if (this.value !== 'ie0') {
                                            document.getElementById('divInterneExterne').style.display = 'none';
                                            document.getElementById('messageInfo').style.display = 'block';
                                        } else {
                                            document.getElementById('divInterneExterne').style.display = 'block';
                                            document.getElementById('messageInfo').style.display = 'none';
                                        }" >
                            <option value="ie0" selected="selected"><?php echo TXT_SELECTTYPE; ?></option>;
                            <option value="ie1" ><?php echo TXT_INTERNE ?></option>;
                            <option value="ie2" ><?php echo TXT_EXTERNE ?></option>;
                        </select>
                            </div>
                         <div style="float: left;margin-left: 25px;margin-top: 15px" id="divInterneExterne">
                            <label for='defaultValue' style="width: 250px;font-style: italic;color: blue;" ><?php echo TXT_DEFAULTVALUE; ?></label>                       
                            <input  data-dojo-type="dijit.form.ValidationTextBox" type="text" style="width: 135px;" readonly="true" name="defaultValue" id="defaultValue" value='<?php echo $interneExterne; ?>'/> 
                        </div>
                        <div id="messageInfo" style="display: none;margin-top: 33px;width: 900px;"><?php echo affiche('TXT_AIDEINTERNEEXTERNE'); ?></div>
                    </td>
                </tr>
    <?php } ?>
    <?php
    $internationalNational = $manager->getSingle2("select internationalnational from projet where idprojet=?", $idprojet);
    if (!empty($internationalNational)) {
        if ($internationalNational == 'N') {
            ?>
                    <tr>
                        <td>
                            <div style="float: left">
                            <label for="internationnalNationnal" style="width: 260px;margin-left: 20px;margin-top:15px;font-style: italic;color: blue;" ><?php echo 'National/International :'; ?>
                                <a class="infoBulle" href="#"><img src='<?php echo "/" . REPERTOIRE; ?>/styles/img/help.gif' height="13px" width="13px"/>
                                    <span style="text-align: left;padding:10px;width: 310px;border-radius:5px" ><?php echo affiche('TXT_AIDEINTERNEEXTERNE'); ?></span>
                                </a>
                            </label>
                            <select name="internationnalNationnal" id="internationnalNationnal" data-dojo-type="dijit/form/Select" style="width: 220px;margin-left: 20px;font-style: italic;color: blue;" 
                                    onchange="if (this.value !== 'in0') {
                                                document.getElementById('messageInfo2').style.display = 'block'
                                            } else {
                                                document.getElementById('messageInfo2').style.display = 'none';
                                            }" >
                                <option value="in0" ><?php echo TXT_SELECTTYPE; ?></option>;
                                <option value="in1" selected="selected" ><?php echo 'National'; ?></option>;
                                <option value="in2" ><?php echo 'International'; ?></option>;
                            </select>                    
                        </div>
                            <div id="messageInfo2" style="display: none;margin-top:33px;margin-left:20px;float: left"><?php echo affiche('TXT_AIDEINTERNEEXTERNE'); ?></div>
                        </td>
                    </tr>
    <?php } elseif ($internationalNational == 'I') {?>
                    <tr>
                        <td>
                            <div style="float: left">
                            <label for="internationnalNationnal" style="width: 260px;margin-left: 20px;margin-top:15px;font-style: italic;color: blue;" ><?php echo 'National/International :'; ?>
                                <a class="infoBulle" href="#"><img src='<?php echo "/" . REPERTOIRE; ?>/styles/img/help.gif' height="13px" width="13px"/>
                                    <span style="text-align: left;padding:10px;width: 310px;border-radius:5px" ><?php echo affiche('TXT_AIDEINTERNEEXTERNE'); ?></span>
                                </a>
                            </label>
                            <select name="internationnalNationnal" id="internationnalNationnal" data-dojo-type="dijit/form/Select" style="width: 220px;margin-left: 20px;font-style: italic;color: blue;" 
                                    onchange="if (this.value !== 'in0') {
                                                document.getElementById('messageInfo2').style.display = 'block'
                                            } else {
                                                document.getElementById('messageInfo2').style.display = 'none';
                                            }" >
                                <option value="in0" ><?php echo TXT_SELECTTYPE; ?></option>;
                                <option value="in1" ><?php echo 'National'; ?></option>;
                                <option value="in2"  selected="selected" ><?php echo 'International'; ?></option>;
                            </select>                    
                        </div>
                            <div id="messageInfo2" style="display: none;margin-top:33px;margin-left:20px;float: left"><?php echo affiche('TXT_AIDEINTERNEEXTERNE'); ?></div>
                        </td>
                    </tr>
                                <?php } else { ?>
                    <tr>
                        <td><div style="float: left">
                            <label for="internationnalNationnal" style="width: 260px;margin-left: 20px;margin-top:15px;font-style: italic;color: blue;" ><?php echo 'National/International :'; ?>
                                <a class="infoBulle" href="#"><img src='<?php echo "/" . REPERTOIRE; ?>/styles/img/help.gif' height="13px" width="13px"/>
                                    <span style="text-align: left;padding:10px;width: 310px;border-radius:5px" ><?php echo affiche('TXT_AIDEINTERNEEXTERNE'); ?></span>
                                </a>
                            </label>
                            <select name="internationnalNationnal" id="internationnalNationnal" data-dojo-type="dijit/form/Select" style="width: 220px;margin-left: 20px;font-style: italic;color: blue;" 
                                    onchange="if (this.value !== 'in0') {                                                
                                                document.getElementById('messageInfo2').style.display = 'block'
                                            } else {
                                                document.getElementById('messageInfo2').style.display = 'none';
                                            }" >
                                <option value="in0" selected="selected" ><?php echo TXT_SELECTTYPE; ?></option>;
                                <option value="in1" ><?php echo 'National'; ?></option>;
                                <option value="in2"  ><?php echo 'International'; ?></option>;
                            </select>                    
                        </div>
                            <div id="messageInfo2" style="display: none;margin-top:33px;margin-left:20px;float: left"><?php echo affiche('TXT_AIDEINTERNEEXTERNE'); ?></div>
                        </td>
                    </tr>
                                <?php
                                }
                            } else { 
                                
                                $arraySF = array();
                                $arraysourcefinancement = $manager->getList2("SELECT libellesourcefinancement FROM sourcefinancement,projetsourcefinancement WHERE "
                                        . "idsourcefinancement_sourcefinancement = idsourcefinancement AND idprojet_projet =?", $idprojet);
                                $nbarraysf = count($arraysourcefinancement);
                                for ($k = 0; $k < $nbarraysf; $k++) {
                                    array_push($arraySF, $arraysourcefinancement[$k]['libellesourcefinancement']);
                                }
                                
                                $nompays = $manager->getSingle2("SELECT nompays FROM pays,utilisateur WHERE idpays_pays = idpays and idutilisateur=(select idutilisateur_utilisateur from creer,projet "
                                        . "where idprojet_projet=idprojet and idprojet=?)", $idprojet);
                                if ($nompays == 'France') {
                                    $sit = 'National';
                                } else {
                                    $idsituation = $manager->getSingle2("select idsituation_situationgeographique from pays where nompays=?", $nompays);
                                    $sit = $manager->getSingle2("select libellesituationgeo from situationgeographique where idsituation = ?", $idsituation);
                                    if ($sit == 'Europe') {
                                        $sit = 'International';
                                    }
                                }
                                $sf = $manager->getList2("SELECT idsourcefinancement FROM projetsourcefinancement,sourcefinancement WHERE idsourcefinancement = idsourcefinancement_sourcefinancement and idprojet_projet=?",$idprojet);
                                $ideurope = $manager->getSingle2("select idsourcefinancement from sourcefinancement where libellesourcefinancement=?",'Europe');
                                foreach ($sf as $sourcefinancement) {
                                    if($sourcefinancement[0]==$ideurope){
                                         $sit = 'International';
                                    }
                                }
                                ?>
                <tr>
                    <td>
                        <div style="float: left">
                        <label for="internationnalNationnal" style="width: 260px;margin-left: 20px;margin-top:15px;font-style: italic;color: blue;" ><?php echo 'National/International :'; ?>
                            <a class="infoBulle" href="#"><img src='<?php echo "/" . REPERTOIRE; ?>/styles/img/help.gif' height="13px" width="13px"/>
                                <span style="text-align: left;padding:10px;width: 310px;border-radius:5px" ><?php echo affiche('TXT_AIDEINTERNEEXTERNE'); ?></span>
                            </a>
                        </label>
                        <select name="internationnalNationnal" id="internationnalNationnal" data-dojo-type="dijit/form/Select" style="width: 220px;margin-left: 20px;font-style: italic;color: blue;margin-right: 20px" 
                                onchange="if (this.value !== 'in0') {
                                            document.getElementById('divdefaultValueNatInt').style.display = 'none'
                                            document.getElementById('messageInfo2').style.display = 'block'
                                        } else {
                                            document.getElementById('divdefaultValueNatInt').style.display = 'block'
                                            document.getElementById('messageInfo2').style.display = 'none';
                                        }" >
                            <option  value="in0" selected="selected" ><?php echo TXT_SELECTTYPE; ?></option>;
                            <option value="in1" ><?php echo 'National'; ?></option>;
                            <option value="in2"  ><?php echo 'International'; ?></option>;
                        </select>
                        </div>
                        <div style="float: left;margin-left: 25px;margin-top: 15px" id="divdefaultValueNatInt">
                            <label for='defaultValueNatInt' style="width: 250px;font-style: italic;color: blue;" ><?php echo TXT_DEFAULTVALUE; ?></label>                       
                            <input  data-dojo-type="dijit.form.ValidationTextBox" type="text" style="width: 135px;" readonly="true" name="defaultValueNatInt" id="defaultValueNatInt" value='<?php echo $sit; ?>'/> 
                        </div>                                       
                        <div id="messageInfo2" style="display: none;margin-top: 33px;width: 900px;"><?php echo affiche('TXT_AIDEINTERNEEXTERNE'); ?></div>
                    </td>
                </tr>
    <?php } ?>

        </table>
    </fieldset>
<?php } ?>