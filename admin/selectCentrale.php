<?php 
$repertoire = explode('/', $_SERVER['PHP_SELF']);
?>
<fieldset id="export" style="border-color: #5D8BA2;width: 1017px;margin-top: 50px;height:80px;padding-top:25px"  >
    <?php if($repertoire[2]== 'vueCentraleAdminNational.php'){  ?>
        <legend style="color: #5D8BA2;"><b><?php echo TXT_VUEPROJETCENTRALE; ?></b></legend>
    <?php }elseif($repertoire[2]=='switch.php'){?>
        <legend style="color: #5D8BA2;"><b><?php echo 'Changement de type de compte'; ?></b></legend>
    <?php }?>
    <table>
        <tr>
            <td><div style="margin-right: 10px"><?php echo TXT_CENTRALE . '* :'; ?></div></td>
            <td>
                <?php $libellecentrale = $manager->getListbyArray("select idcentrale,libellecentrale from centrale where idcentrale!=? and masquecentrale!=? order by libellecentrale asc",array(IDCENTRALEAUTRE,TRUE)); ?>
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