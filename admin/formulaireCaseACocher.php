<form id='admincase' name='admincase' method="post" action ="<?php echo '/' . REPERTOIRE . '/'; ?>controler/ctrladmin.php?lang=<?php echo $lang; ?>" data-dojo-type="dijit/form/Form" >
    <fieldset id="identac" style="border-color: #5D8BA2; margin-right: 20px;margin-left: 6px;padding-left: 20px;padding-top: 5px">
        <legend><?php echo TXT_NOM . ' - ' . TXT_CENTRALE . ''; ?></legend>
        <table>
            <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------
            NOM CENTRALE
------------------------------------------------------------------------------------------------------------------------------------------------------------------------>
            <tr>
                <th><input type="text"  style="height:20px;margin-left:20px;background-color: white; width: 360px;vertical-align:middle;border: 0px" 
                           data-dojo-type="dijit/form/ValidationTextBox" readonly="true" >
                </th>
            <input type="text" name="idcentralactuelnom" style="display: none" data-dojo-type="dijit/form/ValidationTextBox" readonly="true"
                   value="<?php
                   if (isset($_GET['libellecentralenom'])) {
                       echo $manager->getSingle2("select idcentrale from centrale where libellecentrale=?", stripslashes(pg_escape_string($_GET['libellecentralenom'])));
                   }
                   ?>" />
            </tr>
            <tr>
                <th>
                    <?php $rowlibellecentralenom = $manager->getList2("SELECT libellecentrale,idcentrale FROM centrale where idcentrale !=? order by idcentrale asc ;", IDCENTRALEAUTRE); ?>
                    <select  id="libecentralenom" data-dojo-type="dijit.form.ComboBox"  data-dojo-props="name: 'libcentralecodeunite',value: '',placeHolder: '<?php echo TXT_SELECTCENTRALE; ?>'"
                             style="width: 360px;margin-left:20px;"    onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/Manage_nom_centrale/<?php echo $lang ?>/' + this.value)" >
                                 <?php
                                 for ($i = 0; $i < count($rowlibellecentralenom); $i++) {
                                     echo '<option value="' . ($rowlibellecentralenom[$i]['idcentrale']) . '">' . removeDoubleQuote($rowlibellecentralenom[$i]['libellecentrale']) . '</option>';
                                 }
                                 ?>
                    </select>
                </th>
                <th>
                    <input id="modifcentralenom" type="text" name="modifcentralenom" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_NOM . ' - ' . TXT_CENTRALE; ?>"
                           style="height:20px;margin-left:20px; width: 360px;vertical-align:middle;"
                           value="<?php
                           if (isset($_GET['libellecentralenom'])) {
                               echo $_GET['libellecentralenom'];
                           }
                           ?>"
                           data-dojo-props="<?php echo REGEX_TYPE; ?>" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >
                </th>
            </tr>
            <tr>
                <th>
                    <input class="admin" style="padding-left: 22px" type="submit" name="modifnomcentrale" value="<?php echo TXT_MODIFIER; ?>"/>
                    <input class="admin" style="padding-left: 22px" type="submit" name="ajoutenomcentrale" value="<?php echo TXT_AJOUTER; ?>"/>
                </th>
                <th>
                    <input class="admin" style="padding-left: 20px" type="submit" name="masquenomcentrale" value="<?php echo TXT_MASQUER; ?>"/>
                    <input class="admin" style="padding-left: 20px" type="submit" name="affichenomcentrale" value="<?php echo TXT_REAFFICHER; ?>"/>
                </th>
            </tr>
            <tr>
                <th 	style="padding-left:22px;">
                    <?php
                    if (isset($_GET['libellecentralenom'])) {
                        $masque = $manager->getSingle2("select masquecentrale from centrale where trim(libellecentrale)=?", pg_escape_string(stripslashes($_GET['libellecentralenom'])));
                        infoLibelle($masque);
                    }
                    ?>
                </th>
            </tr>
        </table>
    </fieldset><br>
    <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------
    VILLE CENTRALE
----------------------------------------------------------------------------------------------------------------------------------------------------------------------      -->


    <fieldset id="identvc" style="border-color: #5D8BA2; margin-right: 20px;margin-left: 6px;padding-left: 20px;padding-top: 5px">
        <legend><?php echo TXT_VILLE . ' - ' . TXT_CENTRALE; ?></legend>
        <table>
            <tr><th><input type="text"  style="height:20px;margin-left:20px;background-color: white; width: 360px;vertical-align:middle;border: 0px" data-dojo-type="dijit/form/ValidationTextBox" readonly="true"
                           value="<?php
                           if (isset($_GET['libecentrale'])) {
                               echo TXT_CENTRALESELECTON . ' ' . removeDoubleQuote(stripslashes(pg_escape_string($_GET['libecentrale'])));
                           }
                           ?>"/></th>
            <input type="text" name="idcentraleactuelnom" style="display: none" data-dojo-type="dijit/form/ValidationTextBox" readonly="true"
                   value="<?php
                   if (isset($_GET['libecentrale'])) {
                       echo $manager->getSingle2("select idcentrale from centrale where libellecentrale=?", stripslashes(pg_escape_string($_GET['libecentrale'])));
                   }
                   ?>" />
            </tr>
            <tr>                                
                <th><?php $rowlibecentralenom = $manager->getList2("SELECT libellecentrale,villecentrale,idcentrale FROM centrale where idcentrale !=? order by idcentrale asc ;", IDCENTRALEAUTRE); ?>
                    <select  id="libcentralenom" data-dojo-type="dijit.form.ComboBox"  data-dojo-props="name: 'libcentralecodeunite',value: '',placeHolder: '<?php echo TXT_SELECTCENTRALE; ?>'"
                             style="width: 360px;margin-left:20px;"    onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/Manage_ville_centrale/<?php echo $lang ?>/' + this.value)" >
                                 <?php
                                 for ($i = 0; $i < count($rowlibecentralenom); $i++) {
                                     echo '<option value="' . ($rowlibecentralenom[$i]['idcentrale']) . '">' . removeDoubleQuote($rowlibecentralenom[$i]['libellecentrale']) . '</option>';
                                 }
                                 ?>
                    </select>

                </th>
                <th>
                    <input id="modifcentraleville" type="text" name="modifcentraleville" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_VILLE; ?>"
                           style="height:20px;margin-left:20px; width: 360px;vertical-align:middle;"
                           value="<?php
                           if (isset($_GET['libecentrale'])) {
                               echo removeDoubleQuote($manager->getSingle2("select villecentrale from centrale where libellecentrale=? ", pg_escape_string(stripslashes($_GET['libecentrale']))));
                           }
                           ?>"
                           data-dojo-props="<?php echo REGEX_TYPE; ?>" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >

                </th>
            </tr>
            <tr>
                <th>
                    <button class="admin" style="padding-left: 22px" type="submit" name="modifvillecentrale" ><?php echo TXT_MODIFIER; ?></button>
                </th>
            </tr>
        </table>
    </fieldset><br>
    <fieldset id="identce" style="border-color: #5D8BA2; margin-right: 20px;margin-left: 6px;padding-left: 20px;padding-top: 5px">
        <legend><?php echo TXT_MAIL . ' - ' . TXT_CENTRALE; ?></legend>
        <table>
            <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------
            EMAIL CENTRALE
            ----------------------------------------------------------------------------------------------------------------------------------------------------------------------      -->
            <tr><th>
                    <input type="text"  style="height:20px;margin-left:20px;background-color: white; width: 360px;vertical-align:middle;border: 0px" data-dojo-type="dijit/form/ValidationTextBox" readonly="true"
                           value="<?php
                           if (isset($_GET['libellecentrale'])) {
                               echo TXT_CENTRALESELECTON . ' ' . $_GET['libellecentrale'];
                           }
                           ?>" >
                </th>
                <?php
                if (isset($_GET['libellecentrale'])) {
                    $idcentrale = $manager->getSingle2("select idcentrale from centrale where libellecentrale=?", $_GET['libellecentrale']);
                }
                ?>
            <input type="text" name="idcentraleactuelemail" style="display: none" data-dojo-type="dijit/form/ValidationTextBox" readonly="true"
                   value="<?php
                   if (isset($_GET['libellecentrale'])) {
                       echo $idcentrale;
                   }
                   ?>" />
            </tr>
            <tr>
                <th>
                    <?php
                    $rowlibellecentrale = $manager->getList2('SELECT libellecentrale,email1,email2,email3,email4,email5,idcentrale FROM centrale where idcentrale!=? order by idcentrale asc;', IDCENTRALEAUTRE);
                    ?>
                    <select  id="libellecentrale" data-dojo-type="dijit.form.ComboBox"  data-dojo-props="name: 'libellecentrale',value: '',placeHolder: '<?php echo TXT_SELECTCENTRALE; ?>'"
                             style="width: 360px;margin-left:20px;"    onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/Manage_mail_centrale/<?php echo $lang ?>/' + this.value)" >
                                 <?php
                                 for ($i = 0; $i < count($rowlibellecentrale); $i++) {
                                     echo '<option value="' . ($rowlibellecentrale[$i]['idcentrale']) . '">' . $rowlibellecentrale[$i]['libellecentrale'] . '</option>';
                                 }
                                 ?>
                    </select>

                </th>
                <th>
                    <input id="modifcentraleemail1" type="text" name="modifcentraleemail1" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_MAIL . ' ' . '1'; ?>"
                           regExpGen="dojox.validate.regexp.emailAddress" invalidMessage="<?php echo TXT_EMAILNONVALIDE; ?>" style="height:20px;margin-left:20px; width: 360px;vertical-align:middle;"
                           value="<?php
                           if (isset($_GET['libellecentrale'])) {
                               $idcentrale = $manager->getSingle2("select idcentrale from centrale where libellecentrale=?", $_GET['libellecentrale']);
                               $email1centrale = $manager->getSingle2("select email1 from centrale where libellecentrale=?", $_GET['libellecentrale']);
                               if (!empty($email1centrale)) {
                                   echo trim($email1centrale);
                               }
                           }
                           ?>" />
                </th>
            </tr>
            <tr>
                <th>
                    <input id="modifcentraleemail2" type="text" name="modifcentraleemail2" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_MAIL . ' ' . '2'; ?>"
                           regExpGen="dojox.validate.regexp.emailAddress" invalidMessage="<?php echo TXT_EMAILNONVALIDE; ?>" style="height:20px;margin-left:20px; width: 360px;vertical-align:middle;"
                           value="<?php
                           if (isset($_GET['libellecentrale'])) {
                               $idcentrale = $manager->getSingle2("select idcentrale from centrale where libellecentrale=?", $_GET['libellecentrale']);
                               $email2centrale = $manager->getSingle2("select email2 from centrale where libellecentrale=?", $_GET['libellecentrale']);
                               if (!empty($email2centrale)) {
                                   echo trim($email2centrale);
                               }
                           }
                           ?>" />
                </th>

                <th>
                    <input id="modifcentraleemail3" type="text" name="modifcentraleemail3" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_MAIL . ' ' . '3'; ?>"
                           regExpGen="dojox.validate.regexp.emailAddress" invalidMessage="<?php echo TXT_EMAILNONVALIDE; ?>" style="height:20px;margin-left:20px; width: 360px;vertical-align:middle;"
                           value="<?php
                           if (isset($_GET['libellecentrale'])) {
                               $idcentrale = $manager->getSingle2("select idcentrale from centrale where libellecentrale=?", $_GET['libellecentrale']);
                               $email3centrale = $manager->getSingle2("select email3 from centrale where libellecentrale=?", $_GET['libellecentrale']);
                               if (!empty($email3centrale)) {
                                   echo trim($email3centrale);
                               }
                           }
                           ?>" />

                </th>
            </tr>
            <tr>
                <th>
                    <input id="modifcentraleemail4" type="text" name="modifcentraleemail4" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_MAIL . ' ' . '4'; ?>"
                           regExpGen="dojox.validate.regexp.emailAddress" invalidMessage="<?php echo TXT_EMAILNONVALIDE; ?>" style="height:20px;margin-left:20px; width: 360px;vertical-align:middle;"
                           value="<?php
                           if (isset($_GET['libellecentrale'])) {
                               $idcentrale = $manager->getSingle2("select idcentrale from centrale where libellecentrale=?", $_GET['libellecentrale']);
                               $email4centrale = $manager->getSingle2("select email4 from centrale where libellecentrale=?", $_GET['libellecentrale']);
                               if (!empty($email4centrale)) {
                                   echo trim($email4centrale);
                               }
                           }
                           ?>" />

                </th>
                <th>
                    <input id="modifcentraleemail5" type="text" name="modifcentraleemail5" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_MAIL . ' ' . '5'; ?>"
                           regExpGen="dojox.validate.regexp.emailAddress" invalidMessage="<?php echo TXT_EMAILNONVALIDE; ?>" style="height:20px;margin-left:20px; width: 360px;vertical-align:middle;"
                           value="<?php
                           if (isset($_GET['libellecentrale'])) {
                               $idcentrale = $manager->getSingle2("select idcentrale from centrale where libellecentrale=?", $_GET['libellecentrale']);
                               $email5centrale = $manager->getSingle2("select email5 from centrale where libellecentrale=?", $_GET['libellecentrale']);
                               if (!empty($email5centrale)) {
                                   echo trim($email5centrale);
                               }
                           }
                           ?>" />

                </th>
            </tr>
            <tr>
                <th>
                    <button class="admin" style="padding-left: 22px" type="submit" name="modifemailcentrale" ><?php echo TXT_MODIFIER; ?></button>
                </th>
            </tr>
        </table>
    </fieldset>
    <br>
    <fieldset id="identco" style="width:990px">
        <legend><?php echo TXT_CODEUNITE . ' - ' . TXT_CENTRALE; ?></legend>
        <table>
            <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------
            CODE UNITE CENTRALE
            ----------------------------------------------------------------------------------------------------------------------------------------------------------------------      -->
            <tr><th>
                    <input type="text"  style="height:20px;margin-left:20px;background-color: white; width: 360px;vertical-align:middle;border: 0px" data-dojo-type="dijit/form/ValidationTextBox" readonly="true"
                           value="<?php
                           if (isset($_GET['libecentral'])) {
                               echo TXT_CENTRALESELECTON . ' ' . $_GET['libecentral'];
                           }
                           ?>"/>
                </th>
                <?php
                if (isset($_GET['libecentral'])) {
                    $idcentrale = $manager->getSingle2("select idcentrale from centrale where libellecentrale=?", $_GET['libecentral']);
                }
                ?>
            <input type="text" name="idcentraleactuelcodeunite" style="display: none" data-dojo-type="dijit/form/ValidationTextBox" readonly="true"
                   value="<?php
                   if (isset($_GET['libecentral'])) {
                       echo $idcentrale;
                   }
                   ?>" />
            </tr>
            <tr>
                <th>
                    <?php
                    $rowlibcentralecodeunite = $manager->getList2('SELECT libellecentrale,codeunite,idcentrale FROM centrale where idcentrale!=? order by idcentrale asc;', IDCENTRALEAUTRE);
                    ?>
                    <select  id="libcentralecodeunite" data-dojo-type="dijit.form.ComboBox"  data-dojo-props="name: 'libcentralecodeunite',value: '',placeHolder: '<?php echo TXT_SELECTCENTRALE; ?>'"
                             style="width: 360px;margin-left:20px;"    onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/Manage_codeunite/<?php echo $lang ?>/' + this.value)" >
                                 <?php
                                 for ($i = 0; $i < count($rowlibcentralecodeunite); $i++) {
                                     echo '<option value="' . ($rowlibcentralecodeunite[$i]['idcentrale']) . '">' . $rowlibcentralecodeunite[$i]['libellecentrale'] . '</option>';
                                 }
                                 ?>
                    </select>

                </th>
                <th>
                    <input id="modifcentralecodeunite" type="text" name="modifcentralecodeunite" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_CODEUNITE; ?>"
                           invalidMessage="<?php echo '' ?>" style="height:20px;margin-left:20px; width: 360px;vertical-align:middle;"
                           value="<?php
                           if (!empty($_GET['libecentral'])) {
                               $idcentrale = $manager->getSingle2("select idcentrale from centrale where libellecentrale=?", $_GET['libecentral']);
                               $cuCentrale = $manager->getSingle2("select codeunite  from centrale where libellecentrale=?", $_GET['libecentral']);
                               if (!empty($cuCentrale)) {
                                   echo stripslashes(trim($cuCentrale));
                               }
                           }
                           ?>"
                           data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð0-9\',.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >
                </th>
            </tr>

            <tr>
                <th>
                    <button class="admin" style="padding-left: 22px" type="submit" name="modifcodeunitecentrale" ><?php echo TXT_MODIFIER; ?></button>
                </th>
            </tr>
        </table>
    </fieldset><br>
    <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------
    RESSOURCE
----------------------------------------------------------------------------------------------------------------------------------------------------------------------      -->
    <fieldset id="identre" style="width:990px">
        <legend><?php echo TXT_RESSOURCE; ?></legend>
        <table>
            <tr>
                <th><input type="text"  style="height:20px;margin-left:20px;background-color: white; width: 360px;vertical-align:middle;border: 0px" data-dojo-type="dijit/form/ValidationTextBox" readonly="true" ></th>
            <input type="text" name="idressourceactuel" style="display: none" data-dojo-type="dijit/form/ValidationTextBox" readonly="true"
                   value="<?php
                   if (isset($_GET['idressource'])) {
                       echo $_GET['idressource'];
                   }
                   ?>" />
            </tr>
            <tr>
                <th>
                    <?php
                    if ($lang == 'fr') {
                        $rowressource = $manager->getList("SELECT libelleressource,idressource from ressource  order by idressource asc;");
                    } elseif ($lang == 'en') {
                        $rowressource = $manager->getList("SELECT libelleressourceen,idressource from ressource  order by idressource asc;");
                    }
                    ?>
                    <select  id="libelleressource" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'libelleressource',value: '',required:false,placeHolder: '<?php echo TXT_SELECTRESSOURCE; ?>'"
                             style="width: 360px;margin-left:20px;"    onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/Manage_ressource_centrale/<?php echo $lang ?>/' + this.value)" >
                                 <?php
                                 if ($lang == 'fr') {
                                     for ($i = 0; $i < count($rowressource); $i++) {
                                         echo '<option value="' . ($rowressource[$i]['idressource']) . '">' . removeDoubleQuote($rowressource[$i]['libelleressource']) . '</option>';
                                     }
                                 } elseif ($lang == 'en') {
                                     for ($i = 0; $i < count($rowressource); $i++) {
                                         echo '<option value="' . ($rowressource[$i]['idressource']) . '">' . removeDoubleQuote($rowressource[$i]['libelleressourceen']) . '</option>';
                                     }
                                 }
                                 ?>
                    </select>

                </th>
                <th>
                    <input id="modifressource" type="text" name="modifressource" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_RESSOURCES; ?>"
                           style="height:20px;margin-left:20px; width: 360px;vertical-align:middle;"
                           value="<?php
                           if (isset($_GET['idressource'])) {
                               echo removeDoubleQuote($manager->getSingle2("select libelleressource from ressource where idressource=?", $_GET['idressource']));
                           }
                           ?>" data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð/\'();_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >
                </th>
            </tr>
            <tr>
                <td style="width: 360px;"></td>
                <th>
                    <input id="modifressourceen" type="text" name="modifressourceen" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_RESSOURCEEN; ?>"
                           style="height:20px;margin-left:20px; width: 360px;vertical-align:middle;" value="<?php
                           if (isset($_GET['idressource'])) {
                               echo $manager->getSingle2("select libelleressourceen from ressource where idressource=?", $_GET['idressource']);
                           }
                           ?>" data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð/\'();_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >
                </th>
            </tr>
            <tr>
                <th>
                    <input class="admin" style="padding-left: 20px" type="submit" name="modifRessource" value="<?php echo TXT_MODIFIER; ?>" />
                    <input class="admin" style="padding-left: 20px" type="submit" name="ajouteressource" value="<?php echo TXT_AJOUTER; ?>" />
                </th>
                <th>
                    <input class="admin" style="padding-left: 20px" type="submit" name="masqueressource" value="<?php echo TXT_MASQUER; ?>"/>
                    <input class="admin" style="padding-left: 20px" type="submit" name="afficheressource" value="<?php echo TXT_REAFFICHER; ?>"/>
                </th>
            </tr>
            <tr>
                <th style="padding-left:22px;">
                    <?php
                    if (isset($_GET['idressource'])) {
                        $masque = $manager->getSingle2("select masqueressource from ressource where idressource=?", $_GET['idressource']);
                        infoLibelle($masque);
                    }
                    ?>
                </th>
            </tr>
        </table>
    </fieldset>
    <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------
    SOURCE DE FINANCEMENT
    ----------------------------------------------------------------------------------------------------------------------------------------------------------------------      -->
    <fieldset id="identsf" style="border-color: #5D8BA2; margin-right: 20px;margin-left: 6px;padding-left: 20px;padding-top: 5px">
        <legend><?php echo TXT_SOURCEFINANCEMENT; ?></legend>
        <table>
            <tr>
                <th><input type="text"  style="height:20px;margin-left:20px;background-color: white; width: 360px;vertical-align:middle;border: 0px" data-dojo-type="dijit/form/ValidationTextBox" readonly="true" ></th>
            <input type="text" name="idsourcefinancementactuel" style="display: none" data-dojo-type="dijit/form/ValidationTextBox" readonly="true"
                   value="<?php
                   if (isset($_GET['idsourcefinancement'])) {
                       echo $_GET['idsourcefinancement'];
                   }
                   ?>" />
            </tr>
            <tr>
                <th>
                    <?php
                    if ($lang == 'fr') {
                        $sourcefinancement = $manager->getList("SELECT libellesourcefinancement,idsourcefinancement from sourcefinancement  order by idsourcefinancement asc;");
                    } elseif ($lang == 'en') {
                        $sourcefinancement = $manager->getList("SELECT libellesourcefinancementen,idsourcefinancement from sourcefinancement  order by idsourcefinancement asc;");
                    }
                    ?>
                    <select  id="libellesourcefinancement" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'libellesourcefinancement',value: '',required:false,placeHolder: '<?php echo TXT_SELECTSOURCEFINANCEMENT ?>'"
                             style="width: 360px;margin-left:20px;"    onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/Manage_source_financement/<?php echo $lang ?>/' + this.value)" >
                                 <?php
                                 if ($lang == 'fr') {
                                     for ($i = 0; $i < count($sourcefinancement); $i++) {
                                         echo '<option value="' . ($sourcefinancement[$i]['idsourcefinancement']) . '">' . removeDoubleQuote($sourcefinancement[$i]['libellesourcefinancement']) . '</option>';
                                     }
                                 } elseif ($lang == 'en') {
                                     for ($i = 0; $i < count($sourcefinancement); $i++) {
                                         echo '<option value="' . ($sourcefinancement[$i]['idsourcefinancement']) . '">' . removeDoubleQuote($sourcefinancement[$i]['libellesourcefinancementen']) . '</option>';
                                     }
                                 }
                                 ?>
                    </select>

                </th>
                <th>
                    <input id="modifsourcefinancement" type="text" name="modifsourcefinancement" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_SOURCEFINANCEMENT; ?>"
                           style="height:20px;margin-left:20px; width: 360px;vertical-align:middle;"
                           value="<?php
                           if (isset($_GET['idsourcefinancement'])) {
                               echo removeDoubleQuote($manager->getSingle2("select libellesourcefinancement from sourcefinancement where idsourcefinancement=?", $_GET['idsourcefinancement']));
                           }
                           ?>" data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð/\'();_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >
                </th>
            </tr>
            <tr>
                <td style="width: 360px;"></td>
                <th>
                    <input id="modifsourcefinancementen" type="text" name="modifsourcefinancementen" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_SOURCEFINANCEMENTEN; ?>"
                           style="height:20px;margin-left:20px; width: 360px;vertical-align:middle;" value="<?php
                           if (isset($_GET['idsourcefinancement'])) {
                               echo $manager->getSingle2("select libellesourcefinancementen from sourcefinancement where idsourcefinancement=?", $_GET['idsourcefinancement']);
                           }
                           ?>" data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð/\'();_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >
                </th>
            </tr>
            <tr>
                <th>
                    <input class="admin" style="padding-left: 21px" type="submit" name="modifSourcefinancement"  value= "<?php echo TXT_MODIFIER; ?>" >
                    <input class="admin" style="padding-left: 21px" type="submit" name="ajoutesourcefinancement" value="<?php echo TXT_AJOUTER; ?>" />
                </th>
                <th>
                    <input class="admin" style="padding-left: 20px" type="submit" name="masquesourcefinancement" value="<?php echo TXT_MASQUER; ?>"/>
                    <input class="admin" style="padding-left: 20px" type="submit" name="affichesourcefinancement" value="<?php echo TXT_REAFFICHER; ?>"/>
                </th>
            </tr>
            <tr>
                <th style="padding-left:22px;">
                    <?php
                    if (isset($_GET['idsourcefinancement'])) {
                        if ($lang == 'fr') {
                            $masque = $manager->getSingle2("select masquesourcefinancement from sourcefinancement where idsourcefinancement=?", $_GET['idsourcefinancement']);
                            infoLibelle($masque);
                        } elseif ($lang == 'en') {
                            $masque = $manager->getSingle2("select masquesourcefinancementen from sourcefinancement where idsourcefinancement=?", $_GET['idsourcefinancement']);
                            infoLibelle($masque);
                        }
                    }
                    ?>
                </th>
            </tr>
        </table>
    </fieldset>
</form>
