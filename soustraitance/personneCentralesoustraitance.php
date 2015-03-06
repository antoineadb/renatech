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
        $nbarraypersonnecentrale = count($arraypersonnecentrale);
        for ($i = 0; $i < $nbarraypersonnecentrale; $i++) {
            $j = $i + 1;
            ?>
            <div id="<?php echo'divpersonne' . $i; ?>">
                <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo TXT_PERSONNE . ' ' . $j; ?>" selected="true" style="background-color:#FCFAF2;" >
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
                                <input style="width: 317px;background-color:#FCFAF2;" data-dojo-type="dijit/form/ValidationTextBox"  name="<?php echo 'nomaccueilcentrale' . $i; ?>" id="<?php echo 'nomaccueilcentrale' . $i; ?>" value="<?php echo $nomaccueilcentrale; ?>" readonly="true">
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
                                <input style="width: 317px;background-color:#FCFAF2;"  data-dojo-type="dijit/form/ValidationTextBox" name="<?php echo 'prenomaccueilcentrale' . $i; ?>" id="<?php echo 'prenomaccueilcentrale' . $i; ?>" value="<?php echo $prenomaccueilcentrale ?>" readonly="true" >
                            </td>
                        </tr>
                        <tr>
                            <td><label for="<?php echo 'qualiteaccueilcentrale' . $i; ?>" class="perCentrale" ><?php echo TXT_QUALITE . " *"; ?></label>
                                <?php if ($lang == 'fr') { ?>
                                    <select   id="<?php echo 'qualiteaccueilcentrale' . $i; ?>" data-dojo-type="dijit/form/Select" style="width:317px;background-color:#FCFAF2;" >
                                        <?php
                                        if (!empty($arraypersonnecentrale[$i]['libellequalitedemandeuraca'])) {
                                            echo "<option value='0'>" . $arraypersonnecentrale[$i]['libellequalitedemandeuraca'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                <?php }elseif ($lang == 'en') { ?>
                                    <select   id="<?php echo 'qualiteaccueilcentrale' . $i; ?>" data-dojo-type="dijit/form/Select" style="width:317px;background-color:#FCFAF2;" readonly="true">
                                        <?php
                                        if (!empty($arraypersonnecentrale[$i]['libellequalitedemandeuracaen'])) {
                                            echo "<option value='0'>" . $arraypersonnecentrale[$i]['libellequalitedemandeuracaen'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="<?php echo 'mailaccueilcentrale' . $i; ?>" class="perCentrale" ><?php echo TXT_MAIL . " *"; ?></label>
                                <input data-dojo-type="dijit/form/ValidationTextBox" style="width: 317px;background-color:#FCFAF2;"  name="<?php echo 'mailaccueilcentrale' . $i; ?>" id="<?php echo 'mailaccueilcentrale' . $i; ?>"  
                                       readonly="true" value="<?php if (isset($arraypersonnecentrale[$i]['mailaccueilcentrale'])) {echo $arraypersonnecentrale[$i]['mailaccueilcentrale'];} ?>" >
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="<?php echo 'telaccueilcentrale' . $i; ?>" class="perCentrale" ><?php echo TXT_TELEPHONE; ?></label> 
                                <input type="text" name="<?php echo 'telaccueilcentrale' . $i; ?>"  data-dojo-type="dijit/form/ValidationTextBox"  style="width: 317px;background-color:#FCFAF2;" readonly="true"
                                       value="<?php if (isset($arraypersonnecentrale[$i]['telaccueilcentrale'])) {echo $arraypersonnecentrale[$i]['telaccueilcentrale'];}?>">
                            </td>
                        </tr><tr><td><br></td></tr>
                        <tr>
                            <td>
                                <label for="<?php echo 'connaissancetechnologiqueaccueil' . $i; ?>" style="width: 450px;"><?php echo TXT_CONNAISSANCETECHNOLOGIQUE . ' :'; ?>
                                    <a class="infoBulle" href="#"><img src='<?php echo '/' . REPERTOIRE; ?>/styles/img/help.gif' height="13px" width="13px"/><span style="width: 360px;"><?php echo affiche('TXT_AIDECONNAISSANCETECHNOLOGIQUE'); ?></span></a>
                                </label>
                                <textarea  name="<?php echo 'connaissancetechnologiqueaccueil' . $i; ?>" data-dojo-type="dijit/form/SimpleTextarea" readonly="true" rows="50" cols="84" style="height: 100px;background-color:#FCFAF2;">
                                    <?php if (isset($arraypersonnecentrale[$i]['connaissancetechnologiqueaccueil'])) {echo str_replace("''", "'", stripslashes($arraypersonnecentrale[$i]['connaissancetechnologiqueaccueil']));}?>
                                </textarea>
                            </td>
                        </tr>
                        <script>require(["dojo/parser", "dijit/form/Textarea"]);</script>
                        <tr><td><br></td></tr>
                    </table>
                </div>
            </div><?php } ?>
    </div>    
</div>
