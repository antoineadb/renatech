<?php
session_start();
include_once 'outils/toolBox.php';
include_once 'outils/constantes.php';
include 'decide-lang.php';
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
include 'html/header.html';
?>
<div id="global">
    <?php
    include 'html/entete.html';
    ?>
     <div style="margin-top: 70px;">
    <?php include_once 'outils/bandeaucentrale.php'; ?>
            </div> 
    <script>
        function unselect() {
            dijit.byId(datedebut).value = '';
            dijit.byId(datefin).value = '';
        }
    </script>
    <form data-dojo-type="dijit/form/Form" name="exportProjet" id="exportProjet" method="post" onsubmit="unselect()" action="<?php echo '/'.REPERTOIRE ;?>/exportprojetsBrute.php?lang=<?php echo $lang; ?>"  >
        <fieldset id="export" style="border-color: #5D8BA2;width: 1017px;margin-top: 15px;height:80px;padding-top:25px"  >
            <legend style="color: #5D8BA2;font-size: 1.2em"><b><?php echo TXT_EXPORTRESPCENT; ?></b>
                <a class="infoBulle" href="#"><img src='<?php echo '/'.REPERTOIRE ?>/styles/img/help.gif' height="13px" width="13px"/><span style="width: 166px"><?php echo affiche('TXT_AIDEEXPORTBRUTE'); ?></span></a>
            </legend>
            <table>
                <tr>
                    <td valign="middle" style="text-align: left;font-size: 1.2em;padding-right: 20px"><?php echo TXT_DATEDEBUT . ':' ?></td>
                    <td><input id="datedebut" type="text" name="datedebut" style="width: 140px;height:25px;font-size: 1.2em" data-dojo-type="dijit/form/DateTextBox"/></td>
                    <td>&nbsp;&nbsp;&nbsp;</td>
                    <td valign="middle" style="text-align: left;font-size: 1.2em;padding-right: 20px"><?php echo TXT_DATEFIN . ':' ?></td>
                    <td><input id="datefin" type="text" name="datefin" style="width: 140px;height:25px;font-size: 1.2em" data-dojo-type="dijit/form/DateTextBox"/></td>
                    <td>&nbsp;&nbsp;&nbsp;</td>
                    <td><input type="submit"   label="<?php echo TXT_ENVOYER; ?>" data-dojo-Type="dijit.form.Button" data-dojo-type="dijit/form/Button" style="margin-left: 35px;height:28px;text-align: center;font-size: 1.2em" /></td></tr>
            </table>

        </fieldset>

    </form>
    <?php include 'html/footer.html'; ?>
</div>

</body>
</html>
