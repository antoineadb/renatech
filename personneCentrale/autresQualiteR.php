<tr>
<td>
    <label for="<?php echo 'autreQualite' . $i; ?>" class="perCentrale" id="<?php echo 'labelqualite' . $i; ?>" > <?php echo TXT_AUTRESQUALITE; ?></label>
    <select  style='width:317px;' id='<?php echo 'autreQualite' . $i; ?>' data-dojo-type='dijit/form/Select' onchange="afficherAutreQualite(this.id, '<?php echo 'autresQualite' . $i; ?>',<?php echo 'libautresQualite' . $i; ?>)"
    data-dojo-props="name: '<?php echo 'autreQualite' . $i; ?>',value: '',placeHolder: '<?php echo TXT_SELECTQUALITE; ?>'">
    <?php
        if(isset($arrayautrequalite[$i]['idpersonnequalite'])){
                $row = $manager->getListbyArray("SELECT idpersonnequalite,libellepersonnequalite FROM personnecentralequalite where idpersonnequalite!=? and idpersonnequalite!=? ",
                        array(IDNAAUTRESQUALITE,$arrayautrequalite[$i]['idpersonnequalite']));
                echo "<option value='".'qac'.$arrayautrequalite[$i]['idpersonnequalite']."'  selected='selected'>" . $arrayautrequalite[$i]['libellepersonnequalite'] .'</option>';

        }else{
            $row = $manager->getList2("SELECT idpersonnequalite,libellepersonnequalite FROM personnecentralequalite where idpersonnequalite!=? ",IDNAAUTRESQUALITE);
            echo "<option value='qac0'  selected='selected'>" . TXT_SELECTVALUE . '</option>';
        }
        $nbrow = count($row);
        for ($k = 0; $k < $nbrow; $k++) {
            echo "<option value='" . 'qac' . $row[$k]['idpersonnequalite'] . "'>" . $row[$k]['libellepersonnequalite'] . "</option>";
        }
    ?>
    </select>
</td>
</tr>
<?php if(isset($arrayautrequalite[$i]['idpersonnequalite']) && $arrayautrequalite[$i]['idpersonnequalite'] == IDAUTREQUALITE) { ?>
    <tr>
        <td>
                <label for="<?php echo 'autresQualite' . $i; ?>" class="perCentrale" id="<?php echo 'libautresQualite' . $i; ?>" > <?php echo TXT_AUTRES; ?></label>
                <input id="<?php echo 'autresQualite' . $i; ?>" type="text" autocomplete="on" name="<?php echo 'autresQualite' . $i; ?>" data-dojo-type="dijit/form/ValidationTextBox" placeholder="<?php echo TXT_AUTRES;?>" 
                       data-dojo-invalidMessage="<?php echo TXT_ERRSTRING ;?>"  maxlength="100" style="width: 317px;"  
                    value="<?php if(isset($arraylibelleautrequalite[$i]['libelleautresqualite'])){echo $arraylibelleautrequalite[$i]['libelleautresqualite'];} ?>"
                    data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð%&:0-9\042\'()+/_ ,.-]+'" >
        </td>
    </tr>
<?php } ?> 