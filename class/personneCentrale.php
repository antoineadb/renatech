<div style="width: 467px; margin-left:-91px;margin-top:15px;display: block" id="personCent" >
    <div data-dojo-type="dijit/layout/AccordionContainer" style="height:auto" >
        <?php
        if (isset($_GET['numProjet'])) {
            $numProjet = $_GET['numProjet'];
        } elseif (isset($_GET['idprojet'])) {
            $numProjet = $manager->getsingle2("select numero from projet where idprojet=?", $_GET['idprojet']);
        }
        include_once 'outils/toolBox.php';
        if (isset($_SESSION['pseudo'])) {
            check_authent($_SESSION['pseudo']);
        } else {
            echo '<script>window.location.replace("erreurlogin.php")</script>';
        }
        $arraypersonnecentrale = $manager->getListbyArray("SELECT libellequalitedemandeuraca,nomaccueilcentrale,prenomaccueilcentrale,mailaccueilcentrale,telaccueilcentrale,connaissancetechnologiqueaccueil
FROM personneaccueilcentrale,projetpersonneaccueilcentrale,qualitedemandeuraca,projet WHERE idpersonneaccueilcentrale_personneaccueilcentrale = idpersonneaccueilcentrale AND idprojet_projet = idprojet AND
idqualitedemandeuraca = idqualitedemandeuraca_qualitedemandeuraca AND numero =?", array($numProjet));

        for ($i = 1; $i <= 9; $i++) {
            ?>
            <div style="display: none" id="<?php echo'divpersonne' . $i; ?>">
                <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo 'Personne ' . $i; ?>" selected="true" >
                    <table>
                        <tr>
                            <td>
                                <?php
                                if (isset($arraypersonnecentrale[$i - 1]['nomaccueilcentrale'])) {
                                    $nomaccueilcentrale = str_replace("''", "'", stripslashes($arraypersonnecentrale[$i - 1]['nomaccueilcentrale']));
                                } else {
                                    $nomaccueilcentrale = '';
                                }
                                ?>
                                <label for="<?php echo 'nomaccueilcentrale' . $i; ?>" class="perCentrale" ><?php echo TXT_NOM; ?></label>
                                <input style="width: 210px" data-dojo-type="dijit/form/ValidationTextBox"  name="<?php echo 'nomaccueilcentrale' . $i; ?>" autocomplete="on"
                                       data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð%&:0-9\042\'()_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>"
                                       value="<?php echo $nomaccueilcentrale; ?>" disabled="<?php echo $bool; ?>"
                                       />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="<?php echo 'prenomaccueilcentrale' . $i; ?>" class="perCentrale" ><?php echo TXT_PRENOM; ?></label>
                                <?php
                                if (isset($arraypersonnecentrale[$i - 1]['prenomaccueilcentrale'])) {
                                    $prenomaccueilcentrale = str_replace("''", "'", stripslashes($arraypersonnecentrale[$i - 1]['prenomaccueilcentrale']));
                                } else {
                                    $prenomaccueilcentrale = '';
                                }
                                ?>
                                <input style="width: 210px"  data-dojo-type="dijit/form/ValidationTextBox" name="<?php echo 'prenomaccueilcentrale' . $i; ?>" autocomplete="on"
                                       data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð%&:0-9\042\'()_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>"
                                       value="<?php echo $prenomaccueilcentrale ?>" disabled="<?php echo $bool; ?>"
                                       />
                            </td>
                        </tr>

                        <tr>
                            <td><label for="<?php echo 'qualiteaccueilcentrale' . $i; ?>" class="perCentrale" ><?php echo TXT_QUALITE; ?></label>
                                <?php
                                $row = $manager->getListbyArray("SELECT idqualitedemandeuraca,libellequalitedemandeuraca FROM qualitedemandeuraca where libellequalitedemandeuraca !=?", array('n/a'));
                                ?>
                                <select name="<?php echo 'qualiteaccueilcentrale' . $i; ?>" id="<?php echo 'qualiteaccueilcentrale' . $i; ?>"  data-dojo-type="dijit/form/ComboBox" style="width: 210px"  data-dojo-props="
                                        value: '<?php
                            if (isset($arraypersonnecentrale[$i - 1]['libellequalitedemandeuraca'])) {
                                echo $arraypersonnecentrale[$i - 1]['libellequalitedemandeuraca'];
                            }
                                ?>', placeHolder: '<?php echo TXT_SELECTQUALITE; ?>'"  disabled="<?php echo $bool; ?>"  >
                                            <?php
                                            for ($k = 0; $k < count($row); $k++) {
                                                echo "<option value='" . 'libqualdemaca' . $row[$k]['idqualitedemandeuraca'] . "'>" . $row[$k]['libellequalitedemandeuraca'] . "</option>";
                                            }
                                            ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="<?php echo 'mailaccueilcentrale' . $i; ?>" class="perCentrale" ><?php echo TXT_MAIL; ?></label>
                                <input data-dojo-type="dijit/form/ValidationTextBox" style="width: 210px"  name="<?php echo 'mailaccueilcentrale' . $i; ?>"  regExpGen="dojox.validate.regexp.emailAddress"
                                       invalidMessage="<?php echo TXT_EMAILNONVALIDE; ?>" autocomplete="on"  placeHolder ='<?php echo TXT_EMAIL; ?>'
                                       value="<?php
                                        if (isset($arraypersonnecentrale[$i - 1]['mailaccueilcentrale'])) {
                                            echo $arraypersonnecentrale[$i - 1]['mailaccueilcentrale'];
                                        }
                                            ?>"	disabled="<?php echo $bool; ?>"
                                       >
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="<?php echo 'telaccueilcentrale' . $i; ?>" class="perCentrale" ><?php echo TXT_TELEPHONE; ?></label>
                                <input type="text" name="<?php echo 'telaccueilcentrale' . $i; ?>"  data-dojo-type="dijit/form/ValidationTextBox"  style="width: 210px"
                                       data-dojo-props="regExp:'\\d{10}', invalidMessage:'<?php echo TXT_TELINVALIDE; ?> '" autocomplete="on"
                                       value="<?php
                                   if (isset($arraypersonnecentrale[$i - 1]['telaccueilcentrale'])) {
                                       echo $arraypersonnecentrale[$i - 1]['telaccueilcentrale'];
                                   }
                                            ?>" disabled="<?php echo $bool; ?>"
                                       >
                            </td>
                        </tr><tr><td><br></td></tr>
                        <tr>
                            <td>
                                <label for="<?php echo 'connaissancetechnologiqueaccueil' . $i; ?>" style="width: 450px;"><?php echo TXT_CONNAISSANCETECHNOLOGIQUE . ' :'; ?>
                                    <a class="infoBulle" href="#"><img src='styles/img/help.gif'/><span style="width: 153px"><?php echo TXT_AIDECONNAISSANCETECHNOLOGIQUE; ?></span></a>
                                </label>
                                <?php if ($bool == 'true') { ?>
                                    <textarea name="<?php echo 'connaissancetechnologiqueaccueil' . $i; ?>" rows="4" cols="51" style="width:432px;padding-left: 0px" readonly="true" >
                                        <?php
                                        if (isset($arraypersonnecentrale[$i - 1]['connaissancetechnologiqueaccueil'])) {
                                            echo stripslashes(str_replace("''", "'", $arraypersonnecentrale[$i - 1]['connaissancetechnologiqueaccueil']));
                                        }
                                        ?>
                                    </textarea>
                                <?php } else { ?>
                                    <textarea name="<?php echo 'connaissancetechnologiqueaccueil' . $i; ?>" rows="4" cols="51" style="width:432px;padding-left: 0px"  >
                                        <?php
                                        if (isset($arraypersonnecentrale[$i - 1]['connaissancetechnologiqueaccueil'])) {
                                            echo stripslashes(str_replace("''", "'", $arraypersonnecentrale[$i - 1]['connaissancetechnologiqueaccueil']));
                                        }
                                        ?>
                                    </textarea>
                                <?php } ?>
                            </td>
                        </tr>
                    <tr><td><br></td></tr>
            </table>
        </div><?php } ?>
</div>
</div>
<div>
</div>
</div>
