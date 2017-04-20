<?php
include_once 'outils/toolBox.php';
include_once 'outils/constantes.php';
include_once 'decide-lang.php';
if (!empty($_GET['numProjet'])) {
    $numProjet = $_GET['numProjet'];
} elseif (!empty($_GET['idprojet'])) {
    $numProjet = $manager->getSingle2("select numero from projet where idprojet=?", $_GET['idprojet']);
}
include_once 'outils/toolBox.php';
if (!empty($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
if (!empty($numProjet)) {
    $arrayPartenaire = $manager->getList("SELECT nompartenaire, nomlaboentreprise FROM projetpartenaire, projet, partenaireprojet WHERE idpartenaire_partenaireprojet = idpartenaire AND idprojet_projet = projet.idprojet and numero='" . $numProjet . "'");
}
for ($i = 0; $i < 11; $i++) {
    $j = $i + 1;
    ?>
    <table id="<?php echo 'nom' . $i; ?>" style="display:none">
        <tr>
            <td ><label class="laboentreprise"  for="<?php echo 'nomLaboEntreprise' . $i; ?>"><?php echo TXT_NOMLABOENTREPRISE . '  ' . ($j + 1) . '*'; ?></label></td>
            <td>
                <input type="text" id="<?php echo 'nomLaboEntreprise' . $i; ?>" autocomplete="on" name="<?php echo 'nomLaboEntreprise' . $i; ?>" data-dojo-type="dijit/form/ValidationTextBox"
                       value="<?php
                       if (!empty($arrayPartenaire[$i]['nomlaboentreprise'])) {
                           echo stripslashes(str_replace("''", "'", $arrayPartenaire[$i]['nomlaboentreprise']));
                       }
                       ?>" maxlength="100" disabled="<?php echo $bool; ?>" data-dojo-props="<?php echo REGEX_TYPE ?>">
                                                                                            
            </td>
        </tr>
        <?php /* ?>
        <tr>
            <td>
                <label  for="<?php echo 'nomPartenaire' . $i; ?>"><?php echo TXT_NOMPARTENAIRE . '  ' . ($j + 1); ?></label>
            </td>
            <td>
                <input type="text" id="<?php echo 'nomPartenaire' . $i; ?>" autocomplete="on" name="<?php echo 'nomPartenaire' . $i; ?>"
                       data-dojo-type="dijit/form/ValidationTextBox" disabled="<?php echo $bool; ?>"
                       value="<?php
                       if (!empty($arrayPartenaire[$i]['nompartenaire'])) {
                           echo str_replace("''", "'", $arrayPartenaire[$i]['nompartenaire']);
                       }
                       ?>" maxlength="100"
                      data-dojo-props="<?php echo REGEX_TYPE ?>">
            </td>
        </tr>
         <?php  */ ?>
         
    </table>
<?php
}
