<table  style="margin-top: 10px">
            <tr>
                <th style="color: sienna;font-weight: bold;text-align: left"><?php echo TXT_DELETEACCOUNT; ?><a class="infoBulle" style="margin-left: 5px" href="#"><img src='<?php echo "/".REPERTOIRE; ?>/styles/img/help.gif' height="13px" width="13px"/>
                    <span style="text-align: left;padding:10px;width: 700px;border-radius:5px" ><?php echo TXT_INFODELETUSER;?></span>
                </a></th>
                <td></td>

                <td>
                    <?php if(isset($_GET['idqualiteindust'])){?>
                    <div style="margin-left:144px; ?>">
                    <?php }else{?>    
                    <div style="margin-left:194px">    
                    <?php }?>        
                        <input type= "radio" data-dojo-type="dijit/form/RadioButton" name="supprCompte" id ="supprOui"   class="btRadio" onclick="repDeleteUser.show();" ><b style="color:sienna;"><?php echo TXT_OUI; ?></b>
                        <input type= "radio" data-dojo-type="dijit/form/RadioButton" name="supprCompte" id= "supprNon" checked="true"  class="btRadio" > <b style="color: sienna;"><?php echo TXT_NON; ?></b>
                    </div>
                </td>
            </tr>
    </table>                        
</div>
</fieldset>
<div data-dojo-type="dijit/Dialog" data-dojo-id="repDeleteUser" title="<?php echo TXT_CONFIRMDELETEUSER;?>" style="width: 410px;margin-left: 20px;   ">
<table class="dijitDialogPaneContentArea">
    <tr>
        <td><button type="submit" data-dojo-type="dijit/form/Button"  id="delOui" data-dojo-props="onClick:function(){deleteUtilisateur();}"><?php echo TXT_OUI ;?></button></td><td>
        <td><button type="submit" data-dojo-type="dijit/form/Button"  id="delNon"  data-dojo-props="onClick:function(){cancelDelete();}"><?php echo TXT_NON ;?></button></td>                                                           
    </tr>
</table>
</div>
<script>
function cancelDelete(){
    repDeleteUser.hide();
    dijit.byId('supprNon').set('checked',true);
}
function deleteUtilisateur(){
    window.location.replace("/<?php echo REPERTOIRE; ?>/modifBase/deleteUser.php?idUser=<?php echo $iduser;?>");
}                    
</script>