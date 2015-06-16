<tr>
    <td><label for="<?php echo 'qualiteaccueilcentrale' . $i; ?>" class="perCentrale" ><?php echo TXT_QUALITE . " *"; ?></label>                       
        <select   id="<?php echo 'qualiteaccueilcentrale' . $i; ?>" data-dojo-type="dijit/form/Select" style="width:317px" data-dojo-props="name: '<?php echo 'qualiteaccueilcentrale' . $i; ?>',value: '',placeHolder: '<?php echo TXT_SELECTQUALITE; ?>'" onchange="afficherAutreElement(this.id,'<?php echo 'autreQualite' . $i; ?>','<?php echo 'labelqualite' . $i; ?>',<?php echo 'autresQualite' . $i; ?>,'<?php echo 'libautresQualite'. $i; ?>')" >
        <?php 
        if(isset($arraypersonnecentrale[$i]['idqualitedemandeuraca'])){?>
            <option value='libqualdemaca' <?php echo $arraypersonnecentrale[$i]['idqualitedemandeuraca']?> > <?php echo  $arraypersonnecentrale[$i]['libellequalitedemandeuraca']?> </option>
        <?php }else{?>
            <option value='libqualdemaca0'> <?php echo  TXT_SELECTQUALITE; ?> </option>
        <?php }?>
        <?php
        for ($k = 0; $k < $nbrow; $k++) {
            if (isset($row[$k]['idqualitedemandeuraca'])) {
                if ($iang == 'fr') {
                    echo "<option value='" . 'libqualdemaca' . $row[$k]['idqualitedemandeuraca'] . "'>" . $row[$k]['libellequalitedemandeuraca'] . "</option>";
                } else {
                    echo "<option value='" . 'libqualdemaca' . $row[$k]['idqualitedemandeuraca'] . "'>" . $row[$k]['libellequalitedemandeuracaen'] . "</option>";
                }
            }
        }?>
        </select>
    </td>
</tr>   
<tr>
    <td>
        <label for="<?php echo 'autreQualite' . $i; ?>" class="perCentrale" id="<?php echo 'labelqualite' . $i; ?>" > <?php echo TXT_AUTRESQUALITE; ?></label>
        <select  style='width:317px' id='<?php echo 'autreQualite' . $i; ?>' data-dojo-type='dijit/form/Select' 
                 onchange="afficherAutreQualite(this.id, '<?php echo 'autresQualite' . $i; ?>',<?php echo 'libautresQualite' . $i; ?>)"
                 data-dojo-props="name: '<?php echo 'autreQualite' . $i; ?>',value: '',placeHolder: '<?php echo TXT_SELECTQUALITE; ?>'">
                     <?php
                     $row1 = $manager->getListbyArray("SELECT idpersonnequalite,libellepersonnequalite FROM personnecentralequalite where idpersonnequalite!=? and idpersonnequalite!=? ", 
                             array(IDNAAUTRESQUALITE, $arrayQualite[1]['idpersonnequalite']));                     
                     echo "<option value='" . 'qac' . $arrayQualite[1]['idpersonnequalite'] . "'  selected='selected'>" . $arrayQualite[1]['libellepersonnequalite'] . '</option>';
                     $nbrow1 = count($row1);
                     for ($k = 0; $k < $nbrow1; $k++) {
                         echo "<option value='" . 'qac' . $row1[$k]['idpersonnequalite'] . "'>" . $row1[$k]['libellepersonnequalite'] . "</option>";
                     }
                     ?>
        </select>
    </td>
</tr>
<tr>
    <td>
        <label for="<?php echo 'autresQualite' . $i; ?>" class="perCentrale" id="<?php echo 'libautresQualite' . $i; ?>" style="display: none"> <?php echo TXT_AUTRES; ?></label>
        <input id="<?php echo 'autresQualite' . $i; ?>" type="text" autocomplete="on" name="<?php echo 'autresQualite' . $i; ?>" data-dojo-type="dijit/form/ValidationTextBox" placeholder="<?php echo TXT_AUTRES; ?>" 
               data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>"  maxlength="100" style="width: 317px;display: none"  
               data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð%&:0-9\042\'()+/_ ,.-]+'" >
    </td>
</tr>
