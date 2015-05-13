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
                        </td>
                        <td>
                            <div id="messageInfo" style="display: none;margin-top:33px;margin-left:20px;margin-left:20px"><?php echo affiche('TXT_AIDEINTERNEEXTERNE'); ?></div>
                        </td>
                    </tr>
                <?php } else if ($interneexterne == 'E') { ?>
                    <tr>
                        <td>
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
                        </td>
                        <td>
                            <div id="messageInfo" style="display: none;margin-top:33px;margin-left:20px"><?php echo affiche('TXT_AIDEINTERNEEXTERNE'); ?></div>
                        </td>
                    </tr>
                <?php } else { ?>
                    <tr>
                        <td>
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
                        </td>
                        <td>
                            <div id="messageInfo" style="display: none;margin-top:33px;margin-left:20px"><?php echo affiche('TXT_AIDEINTERNEEXTERNE'); ?></div>
                        </td>
                    </tr>
                <?php } ?>
                <? }else{?>
                <tr>
                    <td>
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
                    </td>
                    <td>
                        <div id="messageInfo" style="display: none;margin-top:33px;margin-left:20px"><?php echo affiche('TXT_AIDEINTERNEEXTERNE'); ?></div>
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
                        </td>
                        <td>
                            <div id="messageInfo2" style="display: none;margin-top:33px;margin-left:20px"><?php echo affiche('TXT_AIDEINTERNEEXTERNE'); ?></div>
                        </td>
                    </tr>
        <?php } elseif ($internationalNational == 'I') { ?>
                    <tr>
                        <td>
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
                        </td>
                        <td>
                            <div id="messageInfo2" style="display: none;margin-top:33px;margin-left:20px"><?php echo affiche('TXT_AIDEINTERNEEXTERNE'); ?></div>
                        </td>
                    </tr>
        <?php } else { ?>
                    <tr>
                        <td>
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
                        </td>
                        <td>
                            <div id="messageInfo2" style="display: none;margin-top:33px;margin-left:20px"><?php echo affiche('TXT_AIDEINTERNEEXTERNE'); ?></div>
                        </td>
                    </tr>
                <?php }
            } else {
                ?>
                <tr>
                    <td>
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
                            <option  value="in0" selected="selected" ><?php echo TXT_SELECTTYPE; ?></option>;
                            <option value="in1" ><?php echo 'National'; ?></option>;
                            <option value="in2"  ><?php echo 'International'; ?></option>;
                        </select>                    
                    </td>
                    <td>
                        <div id="messageInfo2" style="display: none;margin-top:33px;margin-left:20px"><?php echo affiche('TXT_AIDEINTERNEEXTERNE'); ?></div>
                    </td>
                </tr>
    <?php } ?>

        </table>
    </fieldset>
<?php } ?>