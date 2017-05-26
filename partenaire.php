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
if(isset($idprojet)){
    $aidpartenaire = $manager->getList2("select  idtypepartenaire_typepartenaire from projettypepartenaire where idprojet_projet=?",$idprojet);
}else{
    $aidpartenaire=array();
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
                           echo stripslashes(removeDoubleQuote($arrayPartenaire[$i]['nomlaboentreprise']));
                       }
                       ?>" maxlength="100" disabled="<?php echo $bool; ?>" data-dojo-props="<?php echo REGEX_TYPE ?>">
                                                                                            
            </td>
            <td>
                <select name="<?php echo 'tp' . $i; ?>" id="<?php echo 'typepartenaire' . $i; ?>"   style="margin-left: 20px;width: 320px;" data-dojo-type="dijit/form/Select">
                <?php   if (!empty($aidpartenaire[$i]['idtypepartenaire_typepartenaire'])){
                            $rowtypepartenaire = $manager->getList2("select idtypepartenaire,libelletypepartenairefr,libelletypepartenaireen from typepartenaire where masquetypepartenaire!=TRUE "
                                    . " and idtypepartenaire <>? order by idtypepartenaire asc",$aidpartenaire[$i]['idtypepartenaire_typepartenaire']);                            
                        }else{
                            $rowtypepartenaire = $manager->getList("select idtypepartenaire,libelletypepartenairefr,libelletypepartenaireen from typepartenaire where masquetypepartenaire!=TRUE order by idtypepartenaire asc");                            
                        }
                        if($lang==fr){
                            if (!empty($aidpartenaire[$i]['idtypepartenaire_typepartenaire'])){
                                $libelle = $manager->getSingle2("select libelletypepartenairefr from typepartenaire where idtypepartenaire=?",$aidpartenaire[$i]['idtypepartenaire_typepartenaire']);
                                echo "<option  value='".'tp'.$aidpartenaire[$i]['idtypepartenaire_typepartenaire']."'>". removeDoubleQuote($libelle)."</option>";
                            }else{
                                echo "<option  >".TXT_SELECTTYPEPARTENAIRE.'</option>';
                            }
                            for($k=0;$k<count($rowtypepartenaire);$k++){
                                echo "<option value='".'tp'.$rowtypepartenaire[$k]['idtypepartenaire']."'>".$rowtypepartenaire[$k]['libelletypepartenairefr']."</option>";                                
                            }
                        }else{
                            if (!empty($aidpartenaire[$i]['idtypepartenaire_typepartenaire'])){
                                $libelle = $manager->getSingle2("select libelletypepartenaireen from typepartenaire where idtypepartenaire=?",$aidpartenaire[$i]['idtypepartenaire_typepartenaire']);
                                echo "<option  value='".'tp'.$aidpartenaire[$i]['idtypepartenaire_typepartenaire']."'>". removeDoubleQuote($libelle)."</option>";
                            }else{
                                echo "<option  >".TXT_SELECTTYPEPARTENAIRE.'</option>';
                            }
                            for($k=0;$k<count($rowtypepartenaire);$k++){
                                echo "<option value='".'tp'.$rowtypepartenaire[$k]['idtypepartenaire']."'>".$rowtypepartenaire[$k]['libelletypepartenaireen']."</option>";                                
                            }
                        }
                ?>
                </select>

            </td>
        </tr>
      
    </table>
<?php
}
