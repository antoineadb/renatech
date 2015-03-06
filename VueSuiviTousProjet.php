<?php
session_start();
include('decide-lang.php');
include_once 'outils/constantes.php';
include_once 'outils/toolBox.php';
include_once 'class/Manager.php';
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
    if (!isset($_SESSION['nom'])) {
        $_SESSION['nom'] = $_SESSION['nomConnect'];
    }
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
include 'html/header.html';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
$centrale = $manager->getSingle2("SELECT libellecentrale FROM  loginpassword,utilisateur,centrale WHERE idlogin = idlogin_loginpassword "
        . "AND idcentrale = idcentrale_centrale and pseudo =?", $_SESSION['pseudo']);
?>
<div id="global" >
    <?php include 'html/entete.html'; ?>
       <div style="margin-top: 70px;">
        <?php include_once 'outils/bandeaucentrale.php'; ?>
    </div>
    <div style="margin-top:15px;width:1050px;">
                <?php require_once 'html/vueSuiviTousProjet.html'; ?>
    </div>
        
        <p style="color: darkblue;font-size:12px;font-family:sans-serif "><?php echo TXT_SUIVITOUTPROJET; ?></p>
        <p>
            <button data-dojo-type="dijit/form/Button" type="button"onclick="
                    var items = grid.selection.getSelected();
                    for (i = 0; i < items.length; i++) {
                        var numero = items[i]['col2'];
                        window.location.replace('<?php echo "/".REPERTOIRE;?>/modifBase/updateStatutProjetph2.php?lang=<?php echo $lang; ?>&numProjet=' + numero + '&page_precedente=VueSuiviTousProjet');
                    }
                    "><?php echo TXT_VALIDER; ?></button>

        </p><?php
        if (isset($_GET['messg'])) {
            if (isset($_GET['idprojet'])) {
                $numero = $manager->getSingle2("select numero from projet where idprojet=?", $_GET['idprojet']);
                ?>
                <table><tr>
                        <td>
                            <fieldset id='droit' style="font-size:1.2em;border-color: #5D8BA2;width:1009px;padding-top: 10px;padding-left: 15px;">
                                <legend><?php echo TXT_ERR; ?></legend>
                                <?php echo stripslashes($_GET['messg']); ?>
                                <a style="text-decoration: none" href="<?php echo "/".REPERTOIRE;?>/controler/controlestatutprojet.php?lang=<?php echo $_GET['lang']; ?>&numProjet=<?php echo $numero; ?>&centrale=<?php echo $centrale; ?>"	><?php echo $numero; ?></a><?php echo TXT_CHPXOBLIGATOIRE1; ?>
                            </fieldset>

                        </td>
                    </tr>
                </table>
                <?php
            } else {
                echo '<p style="color: red;font-size:12px;font-family:sans-serif ">' . $_GET['messg'] . TXT_CHPXOBLIGATOIRE1 . '</p>';
            }
        }
        ?><?php include 'html/footer.html'; ?>
    </div>
    <br><br><br><br>
</div>
</body>
</html>
<?php $db = BD::deconnecter(); ?>