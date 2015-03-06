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
        <form  method="post" action="<?php echo '/' . REPERTOIRE ?>/exportUser.php?lang=<?php echo $lang; ?>" id='exportuser' name='exportuser' onsubmit="if(document.getElementById('msgerreur')){document.getElementById('msgerreur').style.display='none'}">
            <fieldset id="ident" style="border-color: #5D8BA2;width: 1008px;padding-bottom:30px;padding-top:10px;font-size:1.2em" >
                <?php
                $idtypeuser = $manager->getSingle2("SELECT idtypeutilisateur_typeutilisateur FROM loginpassword,utilisateur WHERE idlogin = idlogin_loginpassword and pseudo=?", $_SESSION['pseudo']);
                if ($idtypeuser == ADMINLOCAL) {
                    ?>
                    <legend><?php echo TXT_USERCENTRALE; ?></legend>
                <?php } elseif ($idtypeuser == ADMINNATIONNAL) { ?>
                    <legend><?php echo TXT_TOUSUSER; ?></legend>
<?php } ?>
                <table>
                    <tr><td><br></td></tr>
                    <tr><td>
                            <input  class='opt'  type='checkbox' data-dojo-type='dijit/form/CheckBox' id='academiqueinterne' name='academiqueinterne' checked>
                            <label for='academiqueinterne' class='opt' ><?php echo TXT_ACADEMIQUEINTERNE; ?></label>
                            <input  class='opt'  type='checkbox' data-dojo-type='dijit/form/CheckBox' id='academiqueexterne' name='academiqueexterne'>
                            <label for='academiqueexterne' class='opt' ><?php echo TXT_ACADEMIQUEEXTERNE; ?></label>
                            <input  class='opt'  type='checkbox' data-dojo-type='dijit/form/CheckBox' id='industriel' name='industriel' value="true"  >
                            <label for='industriel' class='opt' ><?php echo TXT_INDUSTRIEL; ?></label>
                            <button type="submit" data-dojo-type="dijit/form/Button" ><?php echo TXT_EXPORTUTILISATEUR; ?></button>
                </table>															
<?php if (isset($_GET['msgerr'])) { ?>
                    <table id="msgerreur">
                        <tr>
                            <td>
                                <div  style=" color: #FF0000;font-size: 1em;padding-top: 4px;text-align: center;width: 1004px;" ><?php echo TXT_TYPENONSELECTIONNE . '!'; ?></div>
                            </td>
                        </tr>
                    </table>
            <?php } ?>
            </fieldset>
<?php include 'html/footer.html'; ?>
        </form>
    </div>
</div>

