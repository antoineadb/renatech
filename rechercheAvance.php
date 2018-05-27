<?php
session_start();
include_once 'class/Manager.php';
include_once 'outils/constantes.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include('decide-lang.php');
include_once 'outils/toolBox.php';
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
include 'html/header.html';
?>

<div id="global">
    <?php include 'html/entete.html'; ?>
    <div style="margin-top: 70px;">
        <?php include_once 'outils/bandeaucentrale.php'; ?>
    </div>    
    <div style="margin-top:40px;width:1050px" >
        <form  method="post" action="<?php echo '/'.REPERTOIRE ?>/resultats/<?php echo $lang; ?>" id='videCache' name='videCache' onsubmit="">
            <fieldset id="ident" style="border-color: #5D8BA2;width: 1008px;padding-bottom:30px;padding-top:10px;font-size:1.2em" >
                <legend><?php echo 'Recherche avancés';?></legend>                
                <table style="float: left">
                    <tr>
                        <td>
                            <label style="margin-bottom: 10px"><?php echo TXT_TYPEPROJET; ?></label>
                        </td>
                    </tr>
                    <tr>
                        <td>                                    
                            <?php
                                $row = $manager->getList("SELECT libellestatutprojet,idstatutprojet FROM statutprojet WHERE idstatutprojet !=5 order by ordre asc");
                                for ($i = 0; $i < count($row); $i++) {
                                    $libellestatut = $row[$i][0];
                                    $idstatut = $row[$i][1];
                                    echo
                                    "<input  class='opt'  type='checkbox' data-dojo-type='dijit/form/CheckBox' id='" . $idstatut . "' "
                                    . "name='statut[]' value='" . $idstatut . "' checked >
                                    <label for = '" . $idstatut . "' class='opt' > " . $libellestatut . "</label>";
                                    echo '&nbsp;';
                                }
                                BD::deconnecter();
                                ?>
                        </td>                      
                    </tr>
                </table>
                <table style="float: left;margin-top: 10px">
                    <tr>
                        <td>
                            <input type="text" class="rechAvance" type="text" id="rechAvance" name="rechAvance" />
                        </td>
                        <td><input type="submit"   label="<?php echo TXT_VALIDER; ?>" data-dojo-Type="dijit.form.Button" data-dojo-props="" style="margin-left:20px">
                    </tr>
                </table>
            </fieldset>
<?php include 'html/footer.html'; ?>
        </form>
    </div>
</div>

