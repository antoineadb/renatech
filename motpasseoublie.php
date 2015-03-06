<?php
session_start();
include('decide-lang.php');
include 'html/header.html';
include_once 'class/Manager.php';
include_once 'outils/constantes.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
/*echo '<pre>';print_r($_SESSION);echo '</pre>';
echo '<pre>';print_r($_GET);echo '</pre>';*/
?>
<div id="global">
    <?php include 'html/entete.html'; ?>
    <div id="motpassemessage" style="display: none;color: red;"><?php echo TXT_MOTPASSEMESSAGE; ?></div>
    <?php if (isset($_GET['msg'])) { ?>
        <div id="message" style=";color: red;font-size: 1.2em;margin-top: 15px;text-align: center;"><?php echo stripslashes($_GET['msg']); ?></div>
    <?php } ?>   
        <?php if (isset($_GET['msgnocompte'])) { ?>
        <div id="message" style=";color: red;font-size: 1.2em;margin-top: 15px;text-align: center;"><?php echo TXT_NOCOMPTE; ?></div>
    <?php } ?>        
        <?php if (isset($_GET['msgnoselect'])) { ?>
        <div id="message" style=";color: red;font-size: 1.2em;margin-top: 15px;text-align: center;"><?php echo TXT_MESSAGEERREURENOSELECT; ?></div>
    <?php } ?>
        <?php if (isset($_GET['msg_nologinaccount'])) { ?>
        <div id="message" style=";color: red;font-size: 1.2em;margin-top: 15px;text-align: center;"><?php echo TXT_NOCOMPTEPSEUDO; ?></div>
    <?php } ?>
        <?php if (isset($_GET['msg_noemailaccount'])) { ?>
        <div id="message" style=";color: red;font-size: 1.2em;margin-top: 15px;text-align: center;"><?php echo TXT_NOCOMPTE; ?></div>
    <?php } ?>
        <?php if (isset($_GET['msg_nolink'])) { ?>
        <div id="message" style=";color: red;font-size: 1.2em;margin-top: 15px;text-align: center;"><?php echo TXT_ERRPSEUDOMAIL; ?></div>
    <?php } ?>
    <fieldset id="motpasseoublie" >
        <legend><?php echo TXT_RETROUVEMOTPASSE; ?></legend>
        <form name="motpasseoublie" method="post" action="<?php echo '/'.REPERTOIRE ?>/modifBase/retrouvemotpasse.php"  >
            <input name="page_precedente" type="hidden" value="<?php echo basename(__FILE__); ?>">
            <label for = "pseudo"><?php echo TXT_EMAILPSEUDOUTILISE; ?></label><br>
            <input id="pseudo" type="text" name="pseudo"   data-dojo-type="dijit/form/ValidationTextBox"
                   autocomplete="on"   promptMessage ="<?php echo TXT_MAUVAISPSEUDO; ?>"
                   data-dojo-props="regExp:'^[a-zA-Z0-9._&-]{6,}',invalidMessage:'<?php echo TXT_MAUVAISPSEUDO; ?>'"
                   placeHolder ='<?php echo TXT_PSEUDO; ?>' >
                   <?php echo TXT_OU; ?>
            <input id="mail" type="text" name="mail" required="required" onchange=""
                   data-dojo-type="dijit/form/ValidationTextBox"
                   regExpGen="dojox.validate.regexp.emailAddress"
                   invalidMessage="<?php echo TXT_EMAILNONVALIDE ?>" autocomplete="on"  placeHolder ="<?php echo TXT_EMAIL; ?>" >
            <div style="font-style: italic;font-size: 10px"><br><?php echo TXT_TEXTMOTPASSEOUBLIER; ?></div><br>
            <?php if (isset($_SESSION['s_pseudo'])&& isset($_GET['pseudos'])) {
                echo TXT_PLUSPSEUDOS . '<br><br>' . TXT_VOSPSEUDOS . ' : ' . $_SESSION['s_pseudo'];
            } ?><br><br>
            <input type="submit" label="<?php echo TXT_ENVOYER; ?>" data-dojo-Type="dijit.form.Button" />
        </form>

    </fieldset>
<?php 
include 'html/footer.html'; ?>
</div>
</body>
</html>