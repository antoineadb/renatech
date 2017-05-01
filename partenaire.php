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
    $arrayPartenaire = $manager->getList("SELECT  nomlaboentreprise FROM projetpartenaire, projet, partenaireprojet WHERE idpartenaire_partenaireprojet = idpartenaire AND idprojet_projet = projet.idprojet and numero='" . $numProjet . "'");
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
            <td>
<?php /*?>                
                <select name="<?php echo 'typeEntreprise' . $i; ?>" id="<?php echo 'typeEntreprise' . $i; ?>"   style="margin-left: 20px;width: 320px;" data-dojo-type="dijit/form/Select">
                <?php 
                        echo '<option>'.TXT_SELECTTYPEENTREPRISE.'</option>';
                        $rowtypeentreprise = $manager->getList("select idtypeentreprise,libelletypeentreprise from typeentreprise where masquetypeentreprise!=TRUE order by idtypeentreprise desc");
                        for($k=0;$k<count($rowtypeentreprise);$k++){
                                echo "<option value='".'te'.$rowtypeentreprise[$k]['idtypeentreprise']."'>".$rowtypeentreprise[$k]['libelletypeentreprise']."</option>";
                        }
                ?>
                </select>
<?php  */?>                
            </td>
        </tr>
      
    </table>
<?php
}
