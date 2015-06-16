<?php 
$arraylibelleautrequalite = $manager->getlistbyArray("SELECT a.libelleautresqualite FROM autresqualite a,personneaccueilcentrale pe,projetpersonneaccueilcentrale pa
WHERE a.idautresqualite = pe.idautresqualite AND pa.idpersonneaccueilcentrale_personneaccueilcentrale = pe.idpersonneaccueilcentrale AND pa.idprojet_projet = ? 
and a.libelleautresqualite!=? " , array($idprojet, 'n/a'));

?>
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
                if ($lang == 'fr') {
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
        <select  style='width:317px;' id='<?php echo 'autreQualite' . $i; ?>' data-dojo-type='dijit/form/Select' onchange="afficherAutreQualite(this.id, '<?php echo 'autresQualite' . $i; ?>',<?php echo 'libautresQualite' . $i; ?>)"
                 data-dojo-props="name: '<?php echo 'autreQualite' . $i; ?>',value: '',placeHolder: '<?php echo TXT_SELECTQUALITE; ?>'">
                     <?php
                     $row2 = $manager->getListbyArray("SELECT idpersonnequalite,libellepersonnequalite FROM personnecentralequalite where idpersonnequalite!=? and idpersonnequalite!=? ", array(IDNAAUTRESQUALITE,4));
                     echo "<option value='qac4'  selected='selected'>" . TXT_AUTRES . '</option>';
                     $nbrow2 = count($row2);
                     for ($k = 0; $k < $nbrow2; $k++) {
                         echo "<option value='" . 'qac' . $row2[$k]['idpersonnequalite'] . "'>" . $row2[$k]['libellepersonnequalite'] . "</option>";
                     }
                     ?>
        </select>
    </td>
</tr>
<?php for ($e = 0; $e < count($arraylibelleautrequalite); $e++) {?>
<tr>
    <td>
        <label for="<?php echo 'autresQualite' . $i; ?>" class="perCentrale" id="<?php echo 'libautresQualite' . $i; ?>" > <?php echo TXT_AUTRES; ?></label>
        <input id="<?php echo 'autresQualite' . $i; ?>" type="text" autocomplete="on" name="<?php echo 'autresQualite' . $i; ?>" 
               data-dojo-type="dijit/form/ValidationTextBox" placeholder="<?php echo TXT_AUTRES; ?>" value="<?php echo $arraylibelleautrequalite[$e]['libelleautresqualite']; ?>"
               data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>"  maxlength="100" style="width: 317px;"  
               data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð%&:0-9\042\'()+/_ ,.-]+'" >
    </td>
</tr>
<?php } ?>