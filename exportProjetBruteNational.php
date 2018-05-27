<?php
session_start();
include 'decide-lang.php';
include_once 'outils/toolBox.php';
include_once 'outils/constantes.php';
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
include 'html/header.html';
?>
<script src="<?php echo '/' . REPERTOIRE ?>/js/ajax.js"></script>
<div id="global">        
    <?php include 'html/entete.html'; ?>
    <script>
        function unselect() {
            dijit.byId(datedebut).value = '';
            dijit.byId(datefin).value = '';
        }
    </script>    
    <form data-dojo-type="dijit/form/Form" name="exportProjet" style="font-size:1.2em;" id="exportProjet" method="post" action="<?php echo '/' . REPERTOIRE ?>/exportprojetsBrute.php?lang=<?php echo $lang; ?>"  >   
        <script type="dojo/method" data-dojo-event="onSubmit">
            if(this.validate()){
            return true;
            }else{
            alert('<?php echo TXT_MESSAGEERREURCONTACT; ?>');
            return false;
            exit();
            }

        </script>
        <div style="margin-top: 70px;">
            <?php include_once 'outils/bandeaucentrale.php'; ?>
        </div>   
        <fieldset id="export" style="border-color: #5D8BA2;width: 1017px;margin-top: 60px;height:120px;padding-top:35px;padding-bottom: 0px"  >
            <legend style="color: #5D8BA2;"><b><?php echo TXT_EXPORTDONNEBRUTE; ?></b>
                <a class="infoBulle" href="#"><img src='<?php echo '/' . REPERTOIRE; ?>/styles/img/help.gif' height="13px" width="13px"/><span style="width: 315px;border-radius: 5px"><?php echo affiche('TXT_AIDEEXPORT'); ?></span></a>
            </legend>
            <table style="float: left">
                <tr>
                    <td valign="middle" style="text-align: left;"><?php echo TXT_DATEDEBUT . ':' ?></td>
                    <td><input id="datedebut" type="text" name="datedebut" style="width: 140px;font-size: 1.1em" data-dojo-type="dijit/form/DateTextBox"/></td>
                    <td>&nbsp;&nbsp;&nbsp;</td>
                    <td valign="middle" style="text-align: left;"><?php echo TXT_DATEFIN . ':' ?></td>
                    <td><input id="datefin" type="text" name="datefin" style="width: 140px;font-size: 1.1em" data-dojo-type="dijit/form/DateTextBox"/></td>
                    <td>&nbsp;&nbsp;&nbsp;</td>
                    <td>
                        <?php $libellecentrale = $manager->getListbyArray("select idcentrale,libellecentrale from centrale where idcentrale!=? and masquecentrale!=?  order by libellecentrale asc", array(IDCENTRALEAUTRE, TRUE));
                        ?>                            
                        <select id="centrale" name="centrale" data-dojo-type="dijit/form/FilteringSelect" style="width: 250px;height:24px"
                                data-dojo-props="  value: '' , placeHolder: '<?php echo TXT_SELECTCENTRALE; ?>',required:'required'">
                                    <?php                                   
                                    for ($i = 0; $i < count($libellecentrale); $i++) {
                                        echo "<option value='" . $libellecentrale[$i]['idcentrale'] . "'>" . $libellecentrale[$i]['libellecentrale'] . "</option>";
                                    }
                                    ?>
                        </select>
                    </td>
                    <td><input type="submit"  style="margin-left: 35px;height:28px;text-align: center;" label="<?php echo TXT_ENVOYER; ?>" data-dojo-Type="dijit.form.Button" /></td>
                </tr> 

            </table>
            <table style="  float: right;margin-right: 20px;padding-top: 8px;"><tr><td> <div id="nbProjet" style="display: none"></div></td></tr></table>


            <div id="dev1" style="margin-left: 280px;padding-top: 3px;display: none">
                <table style="float: left;margin-right: 0px">
                    <tr><td>
                            <?php echo TXT_PROJETDU; ?> &nbsp;&nbsp;&nbsp;
                            <input style='width: 80px;' data-dojo-type="dijit/form/NumberSpinner" id="ordreprojet"  data-dojo-props="smallDelta:300, constraints:{min:0,places:0}" name="ordreprojet" value='0' onclick="increment(this.id, 'input2')"/><?php echo TXT_AU; ?>
                            &nbsp;&nbsp;&nbsp;
                            <input style='width: 70px;' data-dojo-type="dijit/form/TextBox" id="input2"  value="300" readonly="true"/>
                        </td>
                    </tr>
                    <script>
                        function increment(id1, id2) {
                            var nb = parseInt(dijit.byId(id1).value) + 300;
                            dijit.byId(id2).set("value", nb);
                            document.getElementById('topping1').value = nb - 300;
                        }
                    </script>
                </table>
                </table>
            </div>

        </fieldset>      
    </form>
    <?php include 'html/footer.html'; ?>
</div>
</body>
</html>
