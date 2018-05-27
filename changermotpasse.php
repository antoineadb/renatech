<?php
session_start();
include_once('decide-lang.php');
include_once 'outils/constantes.php';
include_once 'outils/toolBox.php';
if (isset($_SESSION['pseudo'])) {
    include_once 'class/Manager.php';
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
include 'html/header.html';
?>

<div id="global">
    <?php include 'html/entete.html'; ?>
    <div id="contenu" style='font-size:1.2em'>
        <div id="messagemauvaisemotpasse" style="display: none;color: red;"><?php echo TXT_MAUVAISMOTPASSE; ?></div>
        <div id="changermotpasse" style="display: none;color: red;padding-left: 2%"><?php echo TXT_MOTPASSEIDENTIQUE; ?></div>
        <br>
        <form data-dojo-type="dijit/form/Form" id="login" name="changemotpasse"  method="post" action="<?php echo '/'.REPERTOIRE; ?>/modifBase/updatemotpasse.php"  >
            <input name="page_precedente" type="hidden" value="<?php echo basename(__FILE__); ?>">
            <script type="dojo/method" data-dojo-event="onSubmit">
                if(this.validate()){
                return true;
                }else{
                alert('<?php echo TXT_MESSAGEERREURCONTACT; ?>');
                return false;exit();
                }
            </script>
            <fieldset id="ident" style="border-color: #5D8BA2;width: 952px;padding-left: 70px;margin-top: 50px;">
                <legend style="color: #5D8BA2"><b> <?php echo TXT_CHANGERMOTPASSE; ?></b></legend>
																<br />
                <label style="width: 350px"> <?php echo TXT_SAISIRMOTPASSE; ?></label> 
                <input type="password" required="required" name="motPasse" id="motPasse"
                       data-dojo-type="dijit/form/ValidationTextBox"
                       data-dojo-props="regExp:'^.*(?=.{6,})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).*$',invalidMessage:'<?php echo TXT_MAUVAISMOTPASSE; ?>'"
                       promptMessage ="<?php echo TXT_MAUVAISMOTPASSE; ?>"  placeHolder ='<?php echo TXT_VOTREMOTPASSE; ?>'/>
                <br /><br /><br />
                <label style="width: 250px"> <?php echo TXT_CONFIRMEMOTPASSE; ?></label> 
                <input type="password" required="required" name="motPasse1" id="motPasse1" data-dojo-type="dijit/form/ValidationTextBox"
                       validator="return this.getValue() == dijit.byId('motPasse').getValue()" invalidMessage="Mot de passe diff&eacute;rent" placeHolder ='<?php echo TXT_VOTREMOTPASSE; ?>' />
                <br /><br />
                <input type="submit" label="<?php echo TXT_BOUTONLOG; ?>" data-dojo-Type="dijit.form.Button"/>
                <p style="font-size: x-small;"><?php echo TXT_CHAMPSOBLIGATOIRES; ?></p>
            </fieldset><?php include 'html/footer.html'; ?>
        </form>
    </div>
</div>

</body>
</html>