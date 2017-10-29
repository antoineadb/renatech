<?php
include_once 'outils/toolBox.php';
include_once 'outils/constantes.php';
include_once 'decide-lang.php';
include_once 'class/Manager.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER


if (!empty($_GET['numProjet'])) {
    $numProjet = $_GET['numProjet'];
    $idprojet = $manager->getSingle2("select idprojet from projet where numero=?", $_GET['numProjet']);
} elseif (!empty($_GET['idprojet'])) {
    $numProjet = $manager->getSingle2("select numero from projet where idprojet=?", $_GET['idprojet']);
    $idprojet = $_GET['idprojet'];
}
include_once 'outils/toolBox.php';
if (!empty($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}

if (!empty($idprojet)) {
    $arrayPartenaire =$manager->getList2("SELECT  nomlaboentreprise,idtypepartenaire_typepartenaire FROM projetpartenaire, projet, partenaireprojet WHERE idpartenaire_partenaireprojet = idpartenaire "
            . "AND idprojet_projet = idprojet and idprojet=?", $idprojet);
    
}else{
    $arrayPartenaire =array();    
}

for ($i = 0; $i < 11; $i++) {
    
    $j = $i + 1;     
    ?>
    <table id="<?php echo 'nom' . $i; ?>" style="display:none;float:right">
        <tr>
            <td ><label class="laboentreprise"  for="<?php echo 'nomLaboEntreprise' . $i; ?>"><?php echo TXT_NOMLABOENTREPRISE . '  ' . ($j + 1) . '*'; ?></label></td>
            <td>
                <input type="text" id="<?php echo 'nomLaboEntreprise' . $i; ?>" autocomplete="on" name="<?php echo 'nomLaboEntreprise' . $i; ?>" data-dojo-type="dijit/form/ValidationTextBox"
                       value="<?php
                       if (!empty($arrayPartenaire[$i]['nomlaboentreprise'])) {
                           echo stripslashes(removeDoubleQuote($arrayPartenaire[$i]['nomlaboentreprise']));
                       }
                       ?>" maxlength="100" disabled="<?php echo $bool; ?>" data-dojo-props="<?php echo REGEX_TYPE ?>">
            </td><td>
                <input type="text" id="<?php echo 'rang' . $j; ?>"  name="<?php echo 'rang' . $j; ?>" style="display: none;width:10px" value="<?php echo $j; ?>" >
            </td>
            <td>
                <select name="<?php echo 'tp' . $i; ?>" id="<?php echo 'typepartenaire' . $i; ?>"   style="margin-left: 20px;width: 320px;" data-dojo-type="dijit/form/Select">
                    <?php
                    if (!empty($arrayPartenaire[$i]['idtypepartenaire_typepartenaire'])) {
                        $rowtypepartenaire = $manager->getList2("select idtypepartenaire,libelletypepartenairefr,libelletypepartenaireen from typepartenaire where masquetypepartenaire!=TRUE "
                                . " and idtypepartenaire <>? order by idtypepartenaire asc", $arrayPartenaire[$i]['idtypepartenaire_typepartenaire']);
                        if ($lang == 'fr') {
                            $libelle = $manager->getSingle2("select libelletypepartenairefr from typepartenaire where idtypepartenaire=?", $arrayPartenaire[$i]['idtypepartenaire_typepartenaire']);
                        } else {
                            $libelle = $manager->getSingle2("select libelletypepartenaireen from typepartenaire where idtypepartenaire=?", $arrayPartenaire[$i]['idtypepartenaire_typepartenaire']);
                        }
                        echo "<option  selected='selected' value='" . 'tp' . $arrayPartenaire[$i]['idtypepartenaire_typepartenaire'] . "'>" . removeDoubleQuote($libelle) . "</option>";
                       
                    } else {
                        $rowtypepartenaire = $manager->getList("select idtypepartenaire,libelletypepartenairefr,libelletypepartenaireen from typepartenaire where masquetypepartenaire!=TRUE "
                                . "AND idtypepartenaire!=99 order by idtypepartenaire asc");
                        if ($lang == 'fr') {
                            $libelle = $manager->getSingle2("select libelletypepartenairefr from typepartenaire where idtypepartenaire=?", $arrayPartenaire[$i]['idtypepartenaire_typepartenaire']);
                        } else {
                            $libelle = $manager->getSingle2("select libelletypepartenaireen from typepartenaire where idtypepartenaire=?", $arrayPartenaire[$i]['idtypepartenaire_typepartenaire']);
                        }
                        echo "<option selected  value='tp99'>" . TXT_INCONNU . "</option>";
                      
                    }
                    for ($k = 0; $k < count($rowtypepartenaire); $k++) {
                        if($lang=='fr'){
                            echo "<option value='" . 'tp' . $rowtypepartenaire[$k]['idtypepartenaire'] . "'>" . $rowtypepartenaire[$k]['libelletypepartenairefr'] . "</option>";
                        }else{
                            echo "<option value='" . 'tp' . $rowtypepartenaire[$k]['idtypepartenaire'] . "'>" . $rowtypepartenaire[$k]['libelletypepartenaireen'] . "</option>";
                        }
                    }
                    ?>
                </select>
            </td>
        </tr>

    </table>
    <?php
    }