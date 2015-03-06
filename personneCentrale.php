<?php
include_once 'outils/constantes.php';
include_once 'outils/toolBox.php';
include_once 'decide-lang.php';
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
?>
<div style="width: 860px; margin-top:15px;display: block;" id="personCent" >
    <div data-dojo-type="dijit/layout/AccordionContainer" style="height:auto" >
        <?php
        if (isset($_GET['numProjet'])) {
            $numProjet = $_GET['numProjet'];
        } elseif (isset($_GET['idprojet'])) {
            $numProjet = $manager->getsingle2("select numero from projet where idprojet=?", $_GET['idprojet']);
        }
        if (!empty($numProjet)) {
            $arraypersonnecentrale = $manager->getListbyArray("SELECT idqualitedemandeuraca,libellequalitedemandeuraca,libellequalitedemandeuracaen,nomaccueilcentrale,prenomaccueilcentrale,mailaccueilcentrale,telaccueilcentrale,connaissancetechnologiqueaccueil
FROM personneaccueilcentrale,projetpersonneaccueilcentrale,qualitedemandeuraca,projet WHERE idpersonneaccueilcentrale_personneaccueilcentrale = idpersonneaccueilcentrale AND idprojet_projet = idprojet AND
idqualitedemandeuraca = idqualitedemandeuraca_qualitedemandeuraca AND numero =?", array($numProjet));
        }
        for ($i = 0; $i < 21; $i++) {
            $j = $i + 1;
            ?>
            <div style="display: none" id="<?php echo'divpersonne' . $i; ?>">
                <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo TXT_PERSONNE . ' ' . $j; ?>" selected="true" >
                    <table style='padding-left: 20px;width:830px'>
                        <tr>
                            <td>
                                <?php
                                if (isset($arraypersonnecentrale[$i]['nomaccueilcentrale'])) {
                                    $nomaccueilcentrale = str_replace("''", "'", stripslashes($arraypersonnecentrale[$i]['nomaccueilcentrale']));
                                } else {
                                    $nomaccueilcentrale = '';
                                }
                                ?>
                                <label for="<?php echo 'nomaccueilcentrale' . $i; ?>" class="perCentrale" ><?php echo TXT_NOM . '*'; ?></label>
                                <input style="width: 317px" data-dojo-type="dijit/form/ValidationTextBox"  name="<?php echo 'nomaccueilcentrale' . $i; ?>" id="<?php echo 'nomaccueilcentrale' . $i; ?>"  autocomplete="on"
                                       data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð%&:0-9\042\'()_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>"
                                       value="<?php echo $nomaccueilcentrale; ?>" disabled="<?php echo $bool; ?>" >
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="<?php echo 'prenomaccueilcentrale' . $i; ?>" class="perCentrale" ><?php echo TXT_PRENOM . '*'; ?></label>
                                <?php
                                if (isset($arraypersonnecentrale[$i]['prenomaccueilcentrale'])) {
                                    $prenomaccueilcentrale = str_replace("''", "'", stripslashes($arraypersonnecentrale[$i]['prenomaccueilcentrale']));
                                } else {
                                    $prenomaccueilcentrale = '';
                                }
                                ?>
                                <input style="width: 317px"  data-dojo-type="dijit/form/ValidationTextBox" name="<?php echo 'prenomaccueilcentrale' . $i; ?>" id="<?php echo 'prenomaccueilcentrale' . $i; ?>" autocomplete="on"
                                       data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð%&:0-9\042\'()_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>"
                                       value="<?php echo $prenomaccueilcentrale ?>" disabled="<?php echo $bool; ?>" >
                            </td>
                        </tr>
                        <tr>
                            <td><label for="<?php echo 'qualiteaccueilcentrale' . $i; ?>" class="perCentrale" ><?php echo TXT_QUALITE . " *"; ?></label>
                                <?php if ($lang == 'fr') { ?>
                                    <select   id="<?php echo 'qualiteaccueilcentrale' . $i; ?>" data-dojo-type="dijit/form/Select" style="width:317px" 
                                              data-dojo-props="name: '<?php echo 'qualiteaccueilcentrale' . $i; ?>',value: '',placeHolder: '<?php echo TXT_SELECTQUALITE; ?>'">
                                        <?php
                                        if (!empty($arraypersonnecentrale[$i]['libellequalitedemandeuraca'])) {
                                            echo "<option value='" . 'libqualdemaca' . $arraypersonnecentrale[$i]['idqualitedemandeuraca'] . "'>" . $arraypersonnecentrale[$i]['libellequalitedemandeuraca'] . '</option>';
                                            $row = $manager->getListbyArray("SELECT idqualitedemandeuraca,libellequalitedemandeuraca FROM qualitedemandeuraca where libellequalitedemandeuraca !=? and libellequalitedemandeuraca != ?", array('n/a', $arraypersonnecentrale[$i]['libellequalitedemandeuraca']));
                                            $nbrow = count($row);
                                            for ($k = 0; $k < $nbrow; $k++) {
                                                echo "<option value='" . 'libqualdemaca' . $row[$k]['idqualitedemandeuraca'] . "'>" . $row[$k]['libellequalitedemandeuraca'] . "</option>";
                                            }
                                        } else {
                                            $row = $manager->getListbyArray("SELECT idqualitedemandeuraca,libellequalitedemandeuraca FROM qualitedemandeuraca where libellequalitedemandeuraca !=? ", array('n/a'));
                                            $nbrow = count($row);
                                            for ($k = 0; $k < $nbrow; $k++) {
                                                echo "<option value='" . 'libqualdemaca' . $row[$k]['idqualitedemandeuraca'] . "'>" . $row[$k]['libellequalitedemandeuraca'] . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                <?php }elseif ($lang == 'en') { ?>
                                    <select   id="<?php echo 'qualiteaccueilcentrale' . $i; ?>" data-dojo-type="dijit/form/Select" style="width:317px"
                                              data-dojo-props="name: '<?php echo 'qualiteaccueilcentrale' . $i; ?>',value: '',placeHolder: '<?php echo TXT_SELECTQUALITE; ?>'">

                                        <?php
                                        if (!empty($arraypersonnecentrale[$i]['libellequalitedemandeuracaen'])) {
                                            echo "<option value='" . 'libqualdemaca' . $arraypersonnecentrale[$i]['idqualitedemandeuraca'] . "'>" . $arraypersonnecentrale[$i]['libellequalitedemandeuracaen'] . '</option>';
                                            $row = $manager->getListbyArray("SELECT idqualitedemandeuraca,libellequalitedemandeuracaen FROM qualitedemandeuraca where libellequalitedemandeuracaen !=? and libellequalitedemandeuracaen != ?", array('n/a', $arraypersonnecentrale[$i]['libellequalitedemandeuraca']));
                                            $nbrow = count($row);
                                            for ($k = 0; $k < $nbrow; $k++) {
                                                echo "<option value='" . 'libqualdemaca' . $row[$k]['idqualitedemandeuraca'] . "'>" . $row[$k]['libellequalitedemandeuracaen'] . "</option>";
                                            }
                                        } else {
                                            $row = $manager->getListbyArray("SELECT idqualitedemandeuraca,libellequalitedemandeuracaen FROM qualitedemandeuraca where libellequalitedemandeuraca !=? ", array('n/a'));
                                            $nbrow = count($row);
                                            for ($k = 0; $k < $nbrow; $k++) {
                                                echo "<option value='" . 'libqualdemaca' . $row[$k]['idqualitedemandeuraca'] . "'>" . $row[$k]['libellequalitedemandeuracaen'] . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="<?php echo 'mailaccueilcentrale' . $i; ?>" class="perCentrale" ><?php echo TXT_MAIL . " *"; ?></label>
                                <input data-dojo-type="dijit/form/ValidationTextBox" style="width: 317px"  name="<?php echo 'mailaccueilcentrale' . $i; ?>" id="<?php echo 'mailaccueilcentrale' . $i; ?>"  regExpGen="dojox.validate.regexp.emailAddress"
                                       invalidMessage="<?php echo TXT_EMAILNONVALIDE; ?>" autocomplete="on"  placeHolder ='<?php echo TXT_EMAIL; ?>'    disabled="<?php echo $bool; ?>"
                                       value="<?php
                                       if (isset($arraypersonnecentrale[$i]['mailaccueilcentrale'])) {
                                           echo $arraypersonnecentrale[$i]['mailaccueilcentrale'];
                                       } ?>" 
                                >
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="<?php echo 'telaccueilcentrale' . $i; ?>" class="perCentrale" ><?php echo TXT_TELEPHONE; ?></label>
                                <input type="text" name="<?php echo 'telaccueilcentrale' . $i; ?>"  data-dojo-type="dijit/form/ValidationTextBox"  style="width: 317px"
                                       data-dojo-props="maxLength:'20',regExp:'[a-zA-Z0-9$\\054\\073\\340\\047\\341\\342\\343\\344\\345\\346\\347\\350\\351\\352\\353\\354\\355\\356\\357\\360\\361\\362\\363\\364\\365\\366\\370\\371\\372\\373\\374\\375\\376\\377\\s\.\-]+',
                                       invalidMessage:'<?php echo TXT_ERRSTRINGTEL; ?>'"  autocomplete="on" disabled="<?php echo $bool; ?>"
                                       value="<?php
                                       if (isset($arraypersonnecentrale[$i]['telaccueilcentrale'])) {
                                           echo $arraypersonnecentrale[$i]['telaccueilcentrale'];
                                       }
                                       ?>"
                                >
                            </td>
                        </tr><tr><td><br></td></tr>
                        <tr>
                            <td>
                                <label for="<?php echo 'connaissancetechnologiqueaccueil' . $i; ?>" style="width: 450px;"><?php echo TXT_CONNAISSANCETECHNOLOGIQUE . ' :'; ?>
                                    <a class="infoBulle" href="#"><img src='<?php echo '/' . REPERTOIRE; ?>/styles/img/help.gif' height="13px" width="13px"/><span style="width: 360px"><?php echo affiche('TXT_AIDECONNAISSANCETECHNOLOGIQUE'); ?></span></a>
                                </label>
                                <?php if ($bool == 'true') { ?>
                                    <textarea  name="<?php echo 'connaissancetechnologiqueaccueil' . $i; ?>" data-dojo-type="dijit/form/SimpleTextarea" readonly="true" rows="50" cols="84" style="height: 100px"><?php
                                        if (isset($arraypersonnecentrale[$i]['connaissancetechnologiqueaccueil'])) {
                                            echo str_replace("''", "'", stripslashes($arraypersonnecentrale[$i]['connaissancetechnologiqueaccueil']));
                                        }
                                        ?>
                                    </textarea>
                                <?php } else { ?>
                                    <textarea  name="<?php echo 'connaissancetechnologiqueaccueil' . $i; ?>" data-dojo-type="dijit/form/SimpleTextarea"    rows="50" cols="84" style="height: 100px"><?php
                                        if (isset($arraypersonnecentrale[$i]['connaissancetechnologiqueaccueil'])) {
                                            echo str_replace("''", "'", stripslashes($arraypersonnecentrale[$i]['connaissancetechnologiqueaccueil']));
                                        }
                                        ?>
                                    </textarea>
                                <?php } ?>
                            </td>
                        </tr>
                        <script>require(["dojo/parser", "dijit/form/Textarea"]);</script>
                        <tr><td><br></td></tr>
                    </table>
                </div>
            </div><?php } ?>
    </div>    
</div>
