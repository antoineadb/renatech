<form id='administ' name='administ' method="post" action ="<?php echo '/' . REPERTOIRE . '/'; ?>controler/ctrladmin.php?lang=<?php echo $lang; ?>" data-dojo-type="dijit/form/Form" >
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
    PAYS
----------------------------------------------------------------------------------------------------------------------------------------------------------------------      -->
    <fieldset id="identpays" style="border-color: #5D8BA2; margin-right: 20px;margin-left: 6px;padding-left: 20px;padding-top: 5px">
        <legend><?php echo TXT_PAYS; ?></legend>
        <table>
            <tr>
                <th><input type="text"  style="height:20px;margin-left:20px;background-color: white; width: 360px;vertical-align:middle;border: 0px" data-dojo-type="dijit/form/ValidationTextBox" readonly="true" ></th>
            <input type="text" name="idlibellepaysactuel" style="display: none" data-dojo-type="dijit/form/ValidationTextBox" readonly="true"
                   value="<?php
                   if (isset($_GET['idpays'])) {
                       echo $_GET['idpays'];
                   }
                   ?>" />
            </tr>
            <tr>
                <th>
                    <?php
                    if ($lang == 'fr') {
                        $rowpays = $manager->getList("SELECT nompays,idpays from pays  order by nompays asc;");
                    } elseif ($lang == 'en') {
                        $rowpays = $manager->getList("SELECT nompaysen,idpays from pays  order by nompaysen asc;");
                    }
                    ?>
                    <select  id="libellepays" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'libellepays',value: '',required:false,placeHolder: '<?php echo TXT_SELECTEDPAYS; ?>'"
                             style="width: 360px;margin-left:20px;" onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/Manage_pays/<?php echo $lang ?>/' + this.value)" >
                                 <?php
                                 if ($lang == 'fr') {
                                     for ($i = 0; $i < count($rowpays); $i++) {
                                         echo '<option value="' . ($rowpays[$i]['idpays']) . '">' . removeDoubleQuote($rowpays[$i]['nompays']) . '</option>';
                                     }
                                 } elseif ($lang == 'en') {
                                     for ($i = 0; $i < count($rowpays); $i++) {
                                         echo '<option value="' . ($rowpays[$i]['idpays']) . '">' . removeDoubleQuote($rowpays[$i]['nompaysen']) . '</option>';
                                     }
                                 }
                                 ?>
                    </select>

                </th>
                <th>
                    <input id="modifpays" type="text" name="modifpays" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_PAYS; ?>" style="height:20px;margin-left:20px; width: 360px;vertical-align:middle;"
                           value="<?php
                           if (isset($_GET['idpays'])) {
                               echo removeDoubleQuote($manager->getSingle2("select nompays from pays where idpays=?", $_GET['idpays']));
                           }
                           ?>"
                           data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð/\'();_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >
                </th>
            </tr>
            <tr>
                <th>
                    <?php $rowsituationgeo = $manager->getList("SELECT libellesituationgeo,idsituation from situationgeographique  order by idsituation asc;"); ?>
                    <select id="libellesituationgeo" data-dojo-type="dijit.form.ComboBox"  data-dojo-props="name: 'libellesituationgeo',value: '',readonly:true,placeHolder: '<?php echo TXT_SELECTSITUATIONGEOGRAPHIQUE; ?>'"
                            style="width: 360px;margin-left:20px;"   >
                                <?php
                                for ($i = 0; $i < count($rowsituationgeo); $i++) {
                                    echo '<option value="' . ($rowsituationgeo[$i]['idsituation']) . '">' . removeDoubleQuote($rowsituationgeo[$i]['libellesituationgeo']) . '</option>';
                                }
                                ?>
                    </select>

                </th>

                <th>
                    <input id="modifpaysen" type="text" name="modifpaysen" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_PAYSEN; ?>"
                           style="height:20px;margin-left:20px; width: 360px;vertical-align:middle;"
                           value="<?php
                           if (isset($_GET['idpays'])) {
                               echo $manager->getSingle2("select nompaysen from pays where idpays=?", $_GET['idpays']);
                           }
                           ?>" data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð/\'();_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >
                </th>

            </tr>
            <tr>
                <th>
                    <input class="admin" style="padding-left: 21px" type="submit" name="modifPays"  value= "<?php echo TXT_MODIFIER; ?>" >
                    <input class="admin" style="padding-left: 21px" type="submit" name="ajoutepays" value="<?php echo TXT_AJOUTER; ?>" />
                </th>
                <th>
                    <input class="admin" style="padding-left: 20px" type="submit" name="masquepays" value="<?php echo TXT_MASQUER; ?>"/>
                    <input class="admin" style="padding-left: 20px" type="submit" name="affichepays" value="<?php echo TXT_REAFFICHER; ?>"/>
                </th>
            </tr>
            <tr>
                <th style="padding-left:22px;">
                    <?php
                    if (isset($_GET['idpays'])) {
                        $masque = $manager->getSingle2("select masquepays from pays where idpays=?", $_GET['idpays']);
                        infoLibelle($masque);
                    }
                    ?>
                </th>
            </tr>
        </table>
    </fieldset>
    <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------
    SECTEUR ACTIVITE
    ----------------------------------------------------------------------------------------------------------------------------------------------------------------------      -->
    <fieldset id="identsa" style="border-color: #5D8BA2; margin-right: 20px;margin-left: 6px;padding-left: 20px;padding-top: 5px">
        <legend><?php echo TXT_SECTEURACTIVITE; ?></legend>
        <table>
            <tr>
                <th><input type="text"  style="height:20px;margin-left:20px;background-color: white; width: 360px;vertical-align:middle;border: 0px" data-dojo-type="dijit/form/ValidationTextBox" readonly="true" ></th>

            <input type="text" name="idlibellesecteuractiviteactuel" style="display: none" data-dojo-type="dijit/form/ValidationTextBox" readonly="true"
                   value="<?php
                   if (isset($_GET['idsecteuractivite'])) {
                       echo $_GET['idsecteuractivite'];
                   }
                   ?>" />
            </tr>
            <tr>
                <th>
                    <?php
                    if ($lang == 'fr') {
                        $rowsecteuractivite = $manager->getList("SELECT libellesecteuractivite,idsecteuractivite from secteuractivite  order by idsecteuractivite asc;");
                    } elseif ($lang == 'en') {
                        $rowsecteuractivite = $manager->getList("SELECT libellesecteuractiviteen,idsecteuractivite from secteuractivite  order by idsecteuractivite asc;");
                    }
                    ?>
                    <select  id="libellesecteuractivite" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'libellesecteuractivite',value: '',required:false,placeHolder: '<?php echo TXT_SELECTSECTEUR ?>'"
                             style="width: 360px;margin-left:20px;"    onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/Manage_sector/<?php echo $lang ?>/' + this.value)" >
                                 <?php
                                 if ($lang == 'fr') {
                                     for ($i = 0; $i < count($rowsecteuractivite); $i++) {
                                         echo '<option value="' . ($rowsecteuractivite[$i]['idsecteuractivite']) . '">' . removeDoubleQuote($rowsecteuractivite[$i]['libellesecteuractivite']) . '</option>';
                                     }
                                 } elseif ($lang == 'en') {
                                     for ($i = 0; $i < count($rowsecteuractivite); $i++) {
                                         echo '<option value="' . ($rowsecteuractivite[$i]['idsecteuractivite']) . '">' . removeDoubleQuote($rowsecteuractivite[$i]['libellesecteuractiviteen']) . '</option>';
                                     }
                                 }
                                 ?>
                    </select>

                </th>
                <th>
                    <input id="modifsecteuractivite" type="text" name="modifsecteuractivite" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_SECTEURACTIVITE; ?>"
                           style="height:20px;margin-left:20px; width: 360px;vertical-align:middle;" value="<?php
                           if (isset($_GET['idsecteuractivite'])) {
                               echo removeDoubleQuote($manager->getSingle2("select libellesecteuractivite from secteuractivite where idsecteuractivite=?", $_GET['idsecteuractivite']));
                           }
                           ?>"
                           data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð/\'();_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >
                </th>
            </tr>
            <tr>
                <td style="width: 360px;"></td>
                <td>
                    <input id="modifsecteuractiviteen" style="height:20px;margin-left:20px; width: 360px;" type="text" name="modifsecteuractiviteen" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_SECTEURACTIVITEEN; ?>"
                           value="<?php
                           if (isset($_GET['idsecteuractivite'])) {
                               echo $manager->getSingle2("select libellesecteuractiviteen from secteuractivite where idsecteuractivite=?", $_GET['idsecteuractivite']);
                           }
                           ?>"
                           data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð/\'();_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >

                </td>


            </tr>
            <tr>
                <th>
                    <input class="admin" style="padding-left: 21px" type="submit" name="modifsecteur"  value= "<?php echo TXT_MODIFIER; ?>" >
                    <input class="admin" style="padding-left: 21px" type="submit" name="ajoutesecteuractivite" value="<?php echo TXT_AJOUTER; ?>" />
                </th>
                <th>
                    <input class="admin" style="padding-left: 20px" type="submit" name="masquesecteuractivite" value="<?php echo TXT_MASQUER; ?>"/>
                    <input class="admin" style="padding-left: 20px" type="submit" name="affichesecteuractivite" value="<?php echo TXT_REAFFICHER; ?>"/>
                </th>
            </tr>
            <tr>
                <th style="padding-left:22px;">
                    <?php
                    if (isset($_GET['idsecteuractivite'])) {
                        $masque = $manager->getSingle2("select masquesecteuractivite from secteuractivite where idsecteuractivite=?", $_GET['idsecteuractivite']);
                        infoLibelle($masque);
                    }
                    ?>
                </th>
            </tr>
        </table>
    </fieldset>
    <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------
    TYPE ENTREPRISE
    ----------------------------------------------------------------------------------------------------------------------------------------------------------------------      -->

    <fieldset id="identte" style="border-color: #5D8BA2; margin-right: 20px;margin-left: 6px;padding-left: 20px;padding-top: 5px">
        <legend><?php echo TXT_TYPEENTREPRISE; ?></legend>
        <table>
            <tr>
                <th><input type="text"  style="height:20px;margin-left:20px;background-color: white; width: 360px;vertical-align:middle;border: 0px" data-dojo-type="dijit/form/ValidationTextBox" readonly="true" ></th>
            <input type="text" name="idtypeentrepriseactuel" style="display: none" data-dojo-type="dijit/form/ValidationTextBox" readonly="true"
                   value="<?php
                   if (isset($_GET['idtypeentreprise'])) {
                       echo $_GET['idtypeentreprise'];
                   }
                   ?>" />
            </tr>
            <tr>
                <th>
                    <?php
                    if ($lang == 'fr') {
                        $rowtypeentreprise = $manager->getList("SELECT libelletypeentreprise,idtypeentreprise from typeentreprise  order by idtypeentreprise asc;");
                    } elseif ($lang == 'en') {
                        $rowtypeentreprise = $manager->getList("SELECT libelletypeentrepriseen,idtypeentreprise from typeentreprise  order by idtypeentreprise asc;");
                    }
                    ?>
                    <select  id="libelletypeentreprise" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'libelletypeentreprise',value: '',required:false,placeHolder: '<?php echo TXT_SELECTTYPEENTREPRISE; ?>'"
                             style="width: 360px;margin-left:20px;"    onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/Manage_typeent/<?php echo $lang ?>/' + this.value)" >
                                 <?php
                                 if ($lang == 'fr') {
                                     for ($i = 0; $i < count($rowtypeentreprise); $i++) {
                                         echo '<option value="' . ($rowtypeentreprise[$i]['idtypeentreprise']) . '">' . removeDoubleQuote($rowtypeentreprise[$i]['libelletypeentreprise']) . '</option>';
                                     }
                                 } elseif ($lang == 'en') {
                                     for ($i = 0; $i < count($rowtypeentreprise); $i++) {
                                         echo '<option value="' . ($rowtypeentreprise[$i]['idtypeentreprise']) . '">' . removeDoubleQuote($rowtypeentreprise[$i]['libelletypeentrepriseen']) . '</option>';
                                     }
                                 }
                                 ?>
                    </select>

                </th>
                <th>
                    <input id="modiftypeentreprise" type="text" name="modiftypeentreprise" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_TYPEENTREPRISE; ?>"
                           style="height:20px;margin-left:20px; width: 360px;vertical-align:middle;"
                           value="<?php
                           if (isset($_GET['idtypeentreprise'])) {
                               echo removeDoubleQuote($manager->getSingle2("select libelletypeentreprise from typeentreprise where idtypeentreprise=?", $_GET['idtypeentreprise']));
                           }
                           ?>" data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð/\'();_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >
                </th>
            </tr>
            <tr>
                <td style="width: 360px;"></td>
                <th>
                    <input id="modiftypeentrepriseen" type="text" name="modiftypeentrepriseen" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_TYPEENTREPRISEEN; ?>"
                           style="height:20px;margin-left:20px; width: 360px;vertical-align:middle;" value="<?php
                           if (isset($_GET['idtypeentreprise'])) {
                               echo $manager->getSingle2("select libelletypeentrepriseen from typeentreprise where idtypeentreprise=?", $_GET['idtypeentreprise']);
                           }
                           ?>" data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð/\'();_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >
                </th>
            </tr>
            <tr>
                <th>
                    <input class="admin" style="padding-left: 21px" type="submit" name="modifTypeentreprise"  value= "<?php echo TXT_MODIFIER; ?>" >
                    <input class="admin" style="padding-left: 21px" type="submit" name="ajoutetypeentreprise" value="<?php echo TXT_AJOUTER; ?>" />
                </th>
                <th>
                    <input class="admin" style="padding-left: 20px" type="submit" name="masquetypeentreprise" value="<?php echo TXT_MASQUER; ?>"/>
                    <input class="admin" style="padding-left: 20px" type="submit" name="affichetypeentreprise" value="<?php echo TXT_REAFFICHER; ?>"/>
                </th>
            </tr>
            <tr>
                <th style="padding-left:22px;">
                    <?php
                    if (isset($_GET['idtypeentreprise'])) {
                        $masque = $manager->getSingle2("select masquetypeentreprise from typeentreprise where idtypeentreprise=?", $_GET['idtypeentreprise']);
                        infoLibelle($masque);
                    }
                    ?>
                </th>
            </tr>
        </table>
    </fieldset>
    <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------
    DISCIPLINE SCIENTIFIQUE
    ----------------------------------------------------------------------------------------------------------------------------------------------------------------------      -->

    <fieldset id="identds" style="border-color: #5D8BA2; margin-right: 20px;margin-left: 6px;padding-left: 20px;padding-top: 5px">
        <legend><?php echo TXT_DISCIPLINESCIENTIFIQUE; ?></legend>
        <table>
            <tr>
                <th><input type="text"  style="height:20px;margin-left:20px;background-color: white; width: 360px;vertical-align:middle;border: 0px" data-dojo-type="dijit/form/ValidationTextBox" readonly="true" ></th>
            <input type="text" name="iddisciplineactuel" style="display: none" data-dojo-type="dijit/form/ValidationTextBox" readonly="true"
                   value="<?php
                   if (isset($_GET['iddiscipline'])) {
                       echo $_GET['iddiscipline'];
                   }
                   ?>" />
            </tr>
            <tr>
                <th>
                    <?php
                    if ($lang == 'fr') {
                        $rowdiscipline = $manager->getList("SELECT libellediscipline,iddiscipline from disciplinescientifique  order by iddiscipline asc;");
                    } elseif ($lang == 'en') {
                        $rowdiscipline = $manager->getList("SELECT libelledisciplineen,iddiscipline from disciplinescientifique  order by iddiscipline asc;");
                    }
                    ?>
                    <select  id="libellediscipline" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'libellediscipline',value: '',required:false,placeHolder: '<?php echo TXT_SELECTDISCIPLINE; ?>'"
                             style="width: 360px;margin-left:20px;"    onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/Manage_discipline/<?php echo $lang ?>/' + this.value)" >
                                 <?php
                                 if ($lang == 'fr') {
                                     for ($i = 0; $i < count($rowdiscipline); $i++) {
                                         echo '<option value="' . ($rowdiscipline[$i]['iddiscipline']) . '">' . removeDoubleQuote($rowdiscipline[$i]['libellediscipline']) . '</option>';
                                     }
                                 } elseif ($lang == 'en') {
                                     for ($i = 0; $i < count($rowdiscipline); $i++) {
                                         echo '<option value="' . ($rowdiscipline[$i]['iddiscipline']) . '">' . removeDoubleQuote($rowdiscipline[$i]['libelledisciplineen']) . '</option>';
                                     }
                                 }
                                 ?>
                    </select>

                </th>
                <th>
                    <input id="modifdiscipline" type="text" name="modifdiscipline" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_DISCIPLINESCIENTIFIQUE; ?>"
                           style="height:20px;margin-left:20px; width: 360px;vertical-align:middle;"
                           value="<?php
                           if (isset($_GET['iddiscipline'])) {
                               echo removeDoubleQuote($manager->getSingle2("select libellediscipline from disciplinescientifique where iddiscipline=?", $_GET['iddiscipline']));
                           }
                           ?>" data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð/\'();_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >
                </th>
            </tr>
            <tr>
                <td style="width: 360px;"></td>
                <th>
                    <input id="modifdisciplineen" type="text" name="modifdisciplineen" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_DISCIPLINESCIENTIFIQUEEN; ?>"
                           style="height:20px;margin-left:20px; width: 360px;vertical-align:middle;" value="<?php
                           if (isset($_GET['iddiscipline'])) {
                               echo $manager->getSingle2("select libelledisciplineen from disciplinescientifique where iddiscipline=?", $_GET['iddiscipline']);
                           }
                           ?>" data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð/\'();_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >
                </th>
            </tr>
            <tr>
                <th>
                    <input class="admin" style="padding-left: 21px" type="submit" name="modifDiscipline"  value= "<?php echo TXT_MODIFIER; ?>" >
                    <input class="admin" style="padding-left: 21px" type="submit" name="ajoutediscipline" value="<?php echo TXT_AJOUTER; ?>" />
                </th>
                <th>
                    <input class="admin" style="padding-left: 20px" type="submit" name="masquediscipline" value="<?php echo TXT_MASQUER; ?>"/>
                    <input class="admin" style="padding-left: 20px" type="submit" name="affichediscipline" value="<?php echo TXT_REAFFICHER; ?>"/>
                </th>
            </tr>
            <tr>
                <th style="padding-left:22px;">
                    <?php
                    if (isset($_GET['iddiscipline'])) {
                        $masque = $manager->getSingle2("select masquediscipline from disciplinescientifique where iddiscipline=?", $_GET['iddiscipline']);
                        infoLibelle($masque);
                    }
                    ?>
                </th>
            </tr>
        </table>
    </fieldset>
    <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------
    NOM DE L'EMPLOYEUR
    ----------------------------------------------------------------------------------------------------------------------------------------------------------------------      -->
    <fieldset id="identne" style="border-color: #5D8BA2; margin-right: 20px;margin-left: 6px;padding-left: 20px;padding-top: 5px">
        <legend><?php echo TXT_NOMEMPLOYEUR; ?></legend>
        <table>
            <tr>
                <th><input type="text"  style="height:20px;margin-left:20px;background-color: white; width: 360px;vertical-align:middle;border: 0px" data-dojo-type="dijit/form/ValidationTextBox" readonly="true" ></th>
            <input type="text" name="idnomemployeuractuel" style="display: none" data-dojo-type="dijit/form/ValidationTextBox" readonly="true"
                   value="<?php
                   if (isset($_GET['idemployeur'])) {
                       echo $_GET['idemployeur'];
                   }
                   ?>" />
            </tr>
            <tr>
                <th>
                    <?php
                    if ($lang == 'fr') {
                        $rownomemployeur = $manager->getList("SELECT libelleemployeur,idemployeur from nomemployeur order by idemployeur asc;");
                    } elseif ($lang == 'en') {
                        $rownomemployeur = $manager->getList("SELECT libelleemployeuren,idemployeur from nomemployeur order by idemployeur asc;");
                    }
                    ?>
                    <select  id="libellenomemployeur" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'libellenomemployeur',value: '',required:false,placeHolder: '<?php echo TXT_SELECTEMPLOYEUR; ?>'"
                             style="width: 360px;margin-left:20px;"    onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/Manage_employeur/<?php echo $lang ?>/' + this.value)" >
                                 <?php
                                 if ($lang == 'fr') {
                                     for ($i = 0; $i < count($rownomemployeur); $i++) {
                                         echo '<option value="' . ($rownomemployeur[$i]['idemployeur']) . '">' . removeDoubleQuote($rownomemployeur[$i]['libelleemployeur']) . '</option>';
                                     }
                                 } elseif ($lang == 'en') {
                                     for ($i = 0; $i < count($rownomemployeur); $i++) {
                                         echo '<option value="' . ($rownomemployeur[$i]['idemployeur']) . '">' . removeDoubleQuote($rownomemployeur[$i]['libelleemployeuren']) . '</option>';
                                     }
                                 }
                                 ?>
                    </select>

                </th>
                <th>
                    <input id="modifnomemployeur" type="text" name="modifnomemployeur" class="long" data-dojo-type="dijit/form/ValidationTextBox"
                           placeholder="<?php echo TXT_NOMEMPLOYEUR; ?>" style="height:20px;margin-left:20px; width: 360px;vertical-align:middle;"
                           value="<?php
                           if (isset($_GET['idemployeur'])) {
                               echo removeDoubleQuote($manager->getSingle2("select libelleemployeur from nomemployeur where idemployeur=?", $_GET['idemployeur']));
                           }
                           ?>"
                           data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð/\'();_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >
                </th>
            </tr>
            <tr>
                <td>
                <th>
                    <input id="modifnomemployeuren" type="text" name="modifnomemployeuren" class="long" data-dojo-type="dijit/form/ValidationTextBox"
                           placeholder="<?php echo TXT_NOMEMPLOYEUREN; ?>" style="height:20px;margin-left:20px; width: 360px;vertical-align:middle;"
                           value="<?php
                           if (isset($_GET['idemployeur'])) {
                               echo $manager->getSingle2("select libelleemployeuren from nomemployeur where idemployeur=?", $_GET['idemployeur']);
                           }
                           ?>" data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð/\'();_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >
                </th>
                </td>
            </tr>
            <tr>
                <th>
                    <input class="admin" style="padding-left: 21px" type="submit" name="modifNomemployeur"  value= "<?php echo TXT_MODIFIER; ?>" >
                    <input class="admin" style="padding-left: 21px" type="submit" name="ajoutenomemployeur" value="<?php echo TXT_AJOUTER; ?>" />
                </th>
                <th>
                    <input class="admin" style="padding-left: 20px" type="submit" name="masqueNomemployeur" value="<?php echo TXT_MASQUER; ?>"/>
                    <input class="admin" style="padding-left: 20px" type="submit" name="afficheNomemployeur" value="<?php echo TXT_REAFFICHER; ?>"/>
                </th>
            </tr>
            <tr>
                <th style="padding-left:22px;">
                    <?php
                    if (isset($_GET['idemployeur'])) {
                        $masque = $manager->getSingle2("select masquenomemployeur from nomemployeur where idemployeur=?", $_GET['idemployeur']);
                        infoLibelle($masque);
                    }
                    ?>
                </th>
            </tr>
        </table>
    </fieldset>
    <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------
    TUTELLE
    ----------------------------------------------------------------------------------------------------------------------------------------------------------------------      -->
    <fieldset id="identtu" style="border-color: #5D8BA2; margin-right: 20px;margin-left: 6px;padding-left: 20px;padding-top: 5px">
        <legend><?php echo TXT_TUTELLE; ?></legend>
        <table>
            <tr>
                <th><input type="text"  style="height:20px;margin-left:20px;background-color: white; width: 360px;vertical-align:middle;border: 0px" data-dojo-type="dijit/form/ValidationTextBox" readonly="true" ></th>
            <input type="text" name="idtutelleactuel" style="display: none" data-dojo-type="dijit/form/ValidationTextBox" readonly="true"
                   value="<?php
                   if (isset($_GET['idtutelle'])) {
                       echo $_GET['idtutelle'];
                   }
                   ?>" />
            </tr>
            <tr>
                <th>
                    <?php
                    if ($lang == 'fr') {
                        $rowtutelle = $manager->getList("SELECT libelletutelle,idtutelle from tutelle  order by idtutelle asc;");
                    } elseif ($lang == 'en') {
                        $rowtutelle = $manager->getList("SELECT libelletutelleen,idtutelle from tutelle  order by idtutelle asc;");
                    }
                    ?>
                    <select  id="libelletutelle" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'libelletutelle',value: '',required:false,placeHolder: '<?php echo TXT_SELECTTUTELLE; ?>'"
                             style="width: 360px;margin-left:20px;"    onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/Manage_tutelle/<?php echo $lang ?>/' + this.value)" >
                                 <?php
                                 if ($lang == 'fr') {
                                     for ($i = 0; $i < count($rowtutelle); $i++) {
                                         echo '<option value="' . ($rowtutelle[$i]['idtutelle']) . '">' . removeDoubleQuote($rowtutelle[$i]['libelletutelle']) . '</option>';
                                     }
                                 } elseif ($lang == 'en') {
                                     for ($i = 0; $i < count($rowtutelle); $i++) {
                                         echo '<option value="' . ($rowtutelle[$i]['idtutelle']) . '">' . removeDoubleQuote($rowtutelle[$i]['libelletutelleen']) . '</option>';
                                     }
                                 }
                                 ?>
                    </select>

                </th>
                <th>
                    <input id="modiftutelle" type="text" name="modiftutelle" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_TUTELLE; ?>"
                           style="height:20px;margin-left:20px; width: 360px;vertical-align:middle;"
                           value="<?php
                           if (isset($_GET['idtutelle'])) {
                               echo removeDoubleQuote($manager->getSingle2("select libelletutelle from tutelle where idtutelle=?", $_GET['idtutelle']));
                           }
                           ?>" data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð/\'();_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >
                </th>
            </tr>
            <tr>
                <td style="width: 360px;"></td>
                <th>
                    <input id="modiftutelleen" type="text" name="modiftutelleen" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_TUTELLEEN; ?>"
                           style="height:20px;margin-left:20px; width: 360px;vertical-align:middle;" value="<?php
                           if (isset($_GET['idtutelle'])) {
                               echo $manager->getSingle2("select libelletutelleen from tutelle where idtutelle=?", $_GET['idtutelle']);
                           }
                           ?>" data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð/\'();_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >
                </th>
            </tr>
            <tr>
                <th>
                    <input class="admin" style="padding-left: 21px" type="submit" name="modifTutelle"  value= "<?php echo TXT_MODIFIER; ?>" >
                    <input class="admin" style="padding-left: 21px" type="submit" name="ajoutetutelle" value="<?php echo TXT_AJOUTER; ?>" />
                </th>
                <th>
                    <input class="admin" style="padding-left: 20px" type="submit" name="masquetutelle" value="<?php echo TXT_MASQUER; ?>"/>
                    <input class="admin" style="padding-left: 20px" type="submit" name="affichetutelle" value="<?php echo TXT_REAFFICHER; ?>"/>
                </th>
            </tr>
            <tr>
                <th style="padding-left:22px;">
                    <?php
                    if (isset($_GET['idtutelle'])) {
                        $masque = $manager->getSingle2("select masquetutelle from tutelle where idtutelle=?", $_GET['idtutelle']);
                        infoLibelle($masque);
                    }
                    ?>
                </th>
            </tr>
        </table>
    </fieldset>
    <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------
    TYPE DE PROJET
    ----------------------------------------------------------------------------------------------------------------------------------------------------------------------      -->
    <fieldset id="identtp" style="border-color: #5D8BA2; margin-right: 20px;margin-left: 6px;padding-left: 20px;padding-top: 5px">
        <legend><?php echo TXT_TYPEPROJET; ?></legend>
        <table>
            <tr>
                <th><input type="text"  style="height:20px;margin-left:20px;background-color: white; width: 360px;vertical-align:middle;border: 0px" data-dojo-type="dijit/form/ValidationTextBox" readonly="true" ></th>
            <input type="text" name="idtypeprojetactuel" style="display: none" data-dojo-type="dijit/form/ValidationTextBox" readonly="true"
                   value="<?php
                   if (isset($_GET['idtypeprojet'])) {
                       echo $_GET['idtypeprojet'];
                   }
                   ?>" />
            </tr>
            <tr>
                <th>
                    <?php
                    if ($lang == 'fr') {
                        $rowtypeprojet = $manager->getList("SELECT libelletype,idtypeprojet from typeprojet  order by idtypeprojet asc;");
                    } elseif ($lang == 'en') {
                        $rowtypeprojet = $manager->getList("SELECT libelletypeen,idtypeprojet from typeprojet  order by idtypeprojet asc;");
                    }
                    ?>
                    <select  id="libelletypeprojet" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: id='libelletypeprojet',value: '',required:false,placeHolder: '<?php echo TXT_SELECTDISCIPLINE; ?>'"
                             style="width: 360px;margin-left:20px;"    onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/Manage_typeprojet/<?php echo $lang ?>/' + this.value)" >
                                 <?php
                                 if ($lang == 'fr') {
                                     for ($i = 0; $i < count($rowtypeprojet); $i++) {
                                         echo '<option value="' . ($rowtypeprojet[$i]['idtypeprojet']) . '">' . removeDoubleQuote($rowtypeprojet[$i]['libelletype']) . '</option>';
                                     }
                                 } elseif ($lang == 'en') {
                                     for ($i = 0; $i < count($rowtypeprojet); $i++) {
                                         echo '<option value="' . ($rowtypeprojet[$i]['idtypeprojet']) . '">' . removeDoubleQuote($rowtypeprojet[$i]['libelletypeen']) . '</option>';
                                     }
                                 }
                                 ?>
                    </select>

                </th>
                <th>
                    <input id="modiftypeprojet" type="text" name="modiftypeprojet" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_TYPEPROJET; ?>"
                           style="height:20px;margin-left:20px; width: 360px;vertical-align:middle;"
                           value="<?php
                           if (isset($_GET['idtypeprojet'])) {
                               echo removeDoubleQuote($manager->getSingle2("select libelletype from typeprojet where idtypeprojet=?", $_GET['idtypeprojet']));
                           }
                           ?>" data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð/\'();_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >
                </th>
            </tr>
            <tr>
                <td style="width: 360px;"></td>
                <th>
                    <input id="modiftypeprojeten" type="text" name="modiftypeprojeten" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_TYPEPROJETEN; ?>"
                           style="height:20px;margin-left:20px; width: 360px;vertical-align:middle;" value="<?php
                           if (isset($_GET['idtypeprojet'])) {
                               echo $manager->getSingle2("select libelletypeen from typeprojet where idtypeprojet=?", $_GET['idtypeprojet']);
                           }
                           ?>" data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð/\'();_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >
                </th>
            </tr>
            <tr>
                <th>
                    <input class="admin" style="padding-left: 21px" type="submit" name="modifTypeprojet"  value= "<?php echo TXT_MODIFIER; ?>" >
                    <input class="admin" style="padding-left: 21px" type="submit" name="ajoutetypeprojet" value="<?php echo TXT_AJOUTER; ?>" />
                </th>
                <th>
                    <input class="admin" style="padding-left: 20px" type="submit" name="masquetypeprojet" value="<?php echo TXT_MASQUER; ?>"/>
                    <input class="admin" style="padding-left: 20px" type="submit" name="affichetypeprojet" value="<?php echo TXT_REAFFICHER; ?>"/>
                </th>
            </tr>
            <tr>
                <th style="padding-left:22px;">
                    <?php
                    if (isset($_GET['idtypeprojet'])) {
                        $masque = $manager->getSingle2("select masquetypeprojet from typeprojet where idtypeprojet=?", $_GET['idtypeprojet']);
                        infoLibelle($masque);
                    }
                    ?>
                </th>
            </tr>
        </table>
    </fieldset>
    <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------
    TYPE DE FORMATION                                                               ///////////////////////////////////////////////////////////////////////////////////////
    ----------------------------------------------------------------------------------------------------------------------------------------------------------------------      -->
    <fieldset id="identtf" style="border-color: #5D8BA2; margin-right: 20px;margin-left: 6px;padding-left: 20px;padding-top: 5px">
        <legend><?php echo TXT_TYPEFORMATION; ?></legend>
        <table>
            <tr>
                <th><input type="text"  style="height:20px;margin-left:20px;background-color: white; width: 360px;vertical-align:middle;border: 0px" data-dojo-type="dijit/form/ValidationTextBox" readonly="true" ></th>
            <input type="text" name="idtypeformationactuel" style="display: none" data-dojo-type="dijit/form/ValidationTextBox" readonly="true"
                   value="<?php
                   if (isset($_GET['idtypeformation'])) {
                       echo $_GET['idtypeformation'];
                   }
                   ?>" />
            </tr>
            <tr>
                <th>
                    <?php
                    if ($lang == 'fr') {
                        $rowtypeformation = $manager->getList("SELECT libelletypeformation,idtypeformation from typeformation  order by idtypeformation asc;");
                    } elseif ($lang == 'en') {
                        $rowtypeformation = $manager->getList("SELECT libelletypeformationen,idtypeformation from typeformation  order by idtypeformation asc;");
                    }
                    ?>
                    <select  id="libelletypeformation" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: id='libelletypeformation',value: '',required:false,placeHolder: '<?php echo TXT_SELECTTYPEFORMATION; ?>'"
                             style="width: 360px;margin-left:20px;"    onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/Manage_typeformation/<?php echo $lang ?>/' + this.value)" >
                                 <?php
                                 if ($lang == 'fr') {
                                     for ($i = 0; $i < count($rowtypeformation); $i++) {
                                         echo '<option value="' . ($rowtypeformation[$i]['idtypeformation']) . '">' . removeDoubleQuote($rowtypeformation[$i]['libelletypeformation']) . '</option>';
                                     }
                                 } elseif ($lang == 'en') {
                                     for ($i = 0; $i < count($rowtypeformation); $i++) {
                                         echo '<option value="' . ($rowtypeformation[$i]['idtypeformation']) . '">' . removeDoubleQuote($rowtypeformation[$i]['libelletypeformationen']) . '</option>';
                                     }
                                 }
                                 ?>
                    </select>

                </th>
                <th>
                    <input id="modiftypeformation" type="text" name="modiftypeformation" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_TYPEFORMATION; ?>"
                           style="height:20px;margin-left:20px; width: 360px;vertical-align:middle;"
                           value="<?php
                           if (isset($_GET['idtypeformation'])) {
                               echo removeDoubleQuote($manager->getSingle2("select libelletypeformation from typeformation where idtypeformation=?", $_GET['idtypeformation']));
                           }
                           ?>" data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð/\'();_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >
                </th>
            </tr>
            <tr>
                <td style="width: 360px;"></td>
                <th>
                    <input id="modiftypeformationen" type="text" name="modiftypeformationen" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_TYPEFORMATION; ?>"
                           style="height:20px;margin-left:20px; width: 360px;vertical-align:middle;" value="<?php
                           if (isset($_GET['idtypeformation'])) {
                               echo $manager->getSingle2("select libelletypeformationen from typeformation where idtypeformation=?", $_GET['idtypeformation']);
                           }
                           ?>" data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð/\'();_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >
                </th>
            </tr>
            <tr>
                <th>
                    <input class="admin" style="padding-left: 21px" type="submit" name="modifTypeformation"  value= "<?php echo TXT_MODIFIER; ?>" >
                    <input class="admin" style="padding-left: 21px" type="submit" name="ajoutetypeformation" value="<?php echo TXT_AJOUTER; ?>" />
                </th>
                <th>
                    <input class="admin" style="padding-left: 20px" type="submit" name="masquetypeformation" value="<?php echo TXT_MASQUER; ?>"/>
                    <input class="admin" style="padding-left: 20px" type="submit" name="affichetypeformation" value="<?php echo TXT_REAFFICHER; ?>"/>
                </th>
            </tr>
            <tr>
                <th style="padding-left:22px;">
                    <?php
                    if (isset($_GET['idtypeformation'])) {
                        $masque = $manager->getSingle2("select masquetypeformation from typeformation where idtypeformation=?", $_GET['idtypeformation']);
                        infoLibelle($masque);
                    }
                    ?>
                </th>
            </tr>
        </table>
    </fieldset>
    <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------
    THEMATIQUE
    ----------------------------------------------------------------------------------------------------------------------------------------------------------------------      -->
    <fieldset id="identth" style="border-color: #5D8BA2; margin-right: 20px;margin-left: 6px;padding-left: 20px;padding-top: 5px">
        <legend><?php echo TXT_THEMATIQUE; ?></legend>
        <table>
            <tr>
                <th><input type="text"  style="height:20px;margin-left:20px;background-color: white; width: 360px;vertical-align:middle;border: 0px" data-dojo-type="dijit/form/ValidationTextBox" readonly="true" ></th>
            <input type="text" name="idthematiqueactuel" style="display: none" data-dojo-type="dijit/form/ValidationTextBox" readonly="true"
                   value="<?php
                   if (isset($_GET['idthematique'])) {
                       echo $_GET['idthematique'];
                   }
                   ?>" />
            </tr>
            <tr>
                <th>
                    <?php
                    if ($lang == 'fr') {
                        $rowthematique = $manager->getList("SELECT libellethematique,idthematique from thematique  order by idthematique asc;");
                    } elseif ($lang == 'en') {
                        $rowthematique = $manager->getList("SELECT libellethematiqueen,idthematique from thematique  order by idthematique asc;");
                    }
                    ?>
                    <select  id="idthematique" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'idthematique',value: '',required:false,placeHolder: '<?php echo TXT_SELECTTHEMATIQUE; ?>'"
                             style="width: 360px;margin-left:20px;"    onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/Manage_thematique/<?php echo $lang ?>/' + this.value)" >
                                 <?php
                                 if ($lang == 'fr') {
                                     for ($i = 0; $i < count($rowthematique); $i++) {
                                         echo '<option value="' . ($rowthematique[$i]['idthematique']) . '">' . removeDoubleQuote($rowthematique[$i]['libellethematique']) . '</option>';
                                     }
                                 } elseif ($lang == 'en') {
                                     for ($i = 0; $i < count($rowthematique); $i++) {
                                         echo '<option value="' . ($rowthematique[$i]['idthematique']) . '">' . removeDoubleQuote($rowthematique[$i]['libellethematiqueen']) . '</option>';
                                     }
                                 }
                                 ?>
                    </select>

                </th>
                <th>
                    <input id="modifthematique" type="text" name="modifthematique" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_THEMATIQUE; ?>"
                           style="height:20px;margin-left:20px; width: 360px;vertical-align:middle;"
                           value="<?php
                           if (isset($_GET['idthematique'])) {
                               echo removeDoubleQuote($manager->getSingle2("select libellethematique from thematique where idthematique=?", $_GET['idthematique']));
                           }
                           ?>" data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð/\'();_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >
                </th>
            </tr>
            <tr>
                <td style="width: 360px;"></td>
                <th>
                    <input id="modifthematiqueen" type="text" name="modifthematiqueen" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_THEMATIQUEEN; ?>"
                           style="height:20px;margin-left:20px; width: 360px;vertical-align:middle;"
                           value="<?php
                           if (isset($_GET['idthematique'])) {
                               echo $manager->getSingle2("select libellethematiqueen from thematique where idthematique=?", $_GET['idthematique']);
                           }
                           ?>" data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð/\'();_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >
                </th>
            </tr>
            <tr>
                <th>
                    <input class="admin" style="padding-left: 21px" type="submit" name="modifThematique"  value= "<?php echo TXT_MODIFIER; ?>" >
                    <input class="admin" style="padding-left: 21px" type="submit" name="ajoutethematique" value="<?php echo TXT_AJOUTER; ?>" />
                </th>
                <th>
                    <input class="admin" style="padding-left: 20px" type="submit" name="masquethematique" value="<?php echo TXT_MASQUER; ?>"/>
                    <input class="admin" style="padding-left: 20px" type="submit" name="affichethematique" value="<?php echo TXT_REAFFICHER; ?>"/>
                </th>
            </tr>
            <tr>
                <th style="padding-left:22px;">
                    <?php
                    if (isset($_GET['idthematique'])) {
                        $masque = $manager->getSingle2("select masquethematique from thematique where idthematique=?", $_GET['idthematique']);
                        infoLibelle($masque);
                    }
                    ?>
                </th>
            </tr>
        </table>
    </fieldset>
</form>