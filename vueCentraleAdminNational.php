<?php
session_start();
include 'decide-lang.php';
include_once 'outils/toolBox.php';
include_once 'outils/constantes.php';
include_once 'class/Cache.php';
$cache =  $videCache = new Cache(REP_ROOT . '/cache', 1);
$cache->clear();
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
    <script>
        function unselect() {
            dijit.byId(datedebut).value = '';
            dijit.byId(datefin).value = '';
        }
    </script>    
    <form data-dojo-type="dijit/form/Form" name="exportProjet" style="font-size:1.2em;" id="exportProjet" method="post" action="<?php echo '/'.REPERTOIRE ?>/controler/controleSuiviProjetRespCentrale.php?lang=<?php echo $lang; ?>"  >
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
        <fieldset id="export" style="border-color: #5D8BA2;width: 1017px;margin-top: 50px;height:80px;padding-top:25px"  >
                <legend style="color: #5D8BA2;"><b><?php echo TXT_VUEPROJETCENTRALE; ?></b></legend>
                <table>
                    <tr>
                        <td><div style="margin-right: 10px"><?php echo TXT_CENTRALE . '* :'; ?></div></td>
                        <td>
                            <?php $libellecentrale = $manager->getListbyArray("select idcentrale,libellecentrale from centrale where idcentrale!=? and masquecentrale!=? order by libellecentrale asc",array(IDCENTRALEAUTRE,TRUE)); ?>
                            </select>
                            <select id="centrale" name="centrale" data-dojo-type="dijit/form/FilteringSelect" style="width: 230px;height:24px"
                                    data-dojo-props="  value: '' , placeHolder: '<?php echo TXT_SELECTCENTRALE; ?>',required:'required'" >
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
        </fieldset>      
    </form>
<?php include 'html/footer.html'; ?>
</div>
</body>
</html>
