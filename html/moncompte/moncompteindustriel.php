<?php 
error_reporting(E_ALL);
if ($lang == 'fr') {
    $libellequalite = $manager->getSingle2("select libellequalitedemandeurindust from qualitedemandeurindust where idqualitedemandeurindust=?", $idqualitedemandeurindust);
} elseif ($lang == 'en') {
    $libellequalite = $manager->getSingle2("select libellequalitedemandeurindusten from qualitedemandeurindust where idqualitedemandeurindust=?", $idqualitedemandeurindust);
}
$libelleQualite = removeDoubleQuote($libellequalite);
$rowQualite = $manager->getListbyArray("SELECT  libellequalitedemandeurindust,libellequalitedemandeurindusten,idqualitedemandeurindust FROM qualitedemandeurindust where idqualitedemandeurindust<>? and idqualitedemandeurindust !=?", 
        array(NAQAUALITEACA, $idqualitedemandeurindust));
$idnonpermanent = 'qa' . NONPERMANENT;
$valeur = '_';
if($lang=='fr'){
    $width='502px';
}else{
    $width='465px';
}
?>
<table>
    <tr>
        <td valign="middle" style="text-align: left;font-size: 1.2em;height:30px"><div style="width:<?php echo $width; ?>"><?php echo TXT_QUALITE; ?></div>
        </td>
        <td><!--QUALITE DEMANDEUR  -->
            <select name="qualiteDemandeurindust" id="qualiteDemandeurindust" data-dojo-type="dijit/form/Select" style="text-align: left;font-size: 1.2em;height:30px;width:340px;" 
                    value="<?php echo $libelleQualite; ?>" onchange="if (this.value === '<?php echo $idnonpermanent; ?>') {afficheAutre('tr_nomreponsable', 'tr_mailresponsable')} else {masqueAutre('tr_nomreponsable', 'tr_mailresponsable')}"
                    onchange="enabledmodif()">
                <option value="<?php if (!empty($idqualitedemandeurindust)) {echo 'qa' . $idqualitedemandeurindust;}?>" ><?php echo $libellequalite; ?></option>
                <?php
                if ($lang == 'fr') {
                    $nbrowQualite = count($rowQualite);
                    for ($i = 0; $i < $nbrowQualite; $i++) {
                        echo "<option value='" . 'qa' . $rowQualite[$i]['idqualitedemandeurindust'] . "'>" .
                        utf8_decode($rowQualite[$i]['libellequalitedemandeurindust']) . "</option>";
                    }
                } elseif ($lang == 'en') {
                    $nbrowQualite = count($rowQualite);
                    for ($i = 0; $i < $nbrowQualite; $i++) {
                        echo "<option value='" . 'qa' . $rowQualite[$i]['idqualitedemandeurindust'] . "'>" .
                        utf8_decode($rowQualite[$i]['libellequalitedemandeurindusten']) . "</option>";
                    }
                }
                ?>
            </select>
        </td>
    </tr>
<?php if ($idqualitedemandeurindust == NONPERMANENT) { ?><!-- AFFICHAGE DU NOM RESPONSABLE ET EMAIL RESPONSABLE  -->
        <tr  id="tr_nomreponsable">
            <td valign="middle" style="text-align: left;font-size: 1.2em;height:30px"><div><?php echo TXT_NOMRESPONSABLE; ?></div></td>
            <?php $nomResponsable = removeDoubleQuote( $nomresponsable) ?>
            <td>
                <input style="text-align: left;font-size: 1.2em;height:30px;width:340px;"  type="text"  name="nomresponsable" id="nomresponsable" data-dojo-type="dijit/form/ValidationTextBox"
                       value="<?php echo $nomResponsable; ?>"    placeholder="<?php echo TXT_RESPNAMEMAIL; ?>"  >
            </td>
        </tr>
        <tr id='tr_mailresponsable'>
            <td valign="middle" style="text-align: left;font-size: 1.2em;height:30px"><?php echo TXT_RESPMAILMAIL; ?> </td>
            <td>
                <input style="text-align: left;font-size: 1.2em;height:30px;width:340px;"  type="text"  name="mailresponsable" id="mailresponsable" data-dojo-type="dijit/form/ValidationTextBox"
                       value="<?php echo $mailresponsable; ?>"      placeholder="<?php echo TXT_RESPMAILMAIL; ?>"  >
            </td>
        </tr>
<?php }?>
        <tr  id="tr_nomreponsable" style="display: none">
            <td valign="middle" style="text-align: left;font-size: 1.2em;height:30px"><?php echo TXT_NOMRESPONSABLE; ?> </td>
            <?php $nomResponsable = removeDoubleQuote($nomresponsable) ?>
            <td>
                <input style="text-align: left;font-size: 1.2em;height:30px;width:340px;"  type="text"  name="nomresponsable1" id="nomresponsable1" data-dojo-type="dijit/form/ValidationTextBox"
                       value="<?php echo $nomResponsable; ?>"    placeholder="<?php echo TXT_RESPNAMEMAIL; ?>"  >
            </td>
        </tr>
        <tr id='tr_mailresponsable' style="display: none">
            <td valign="middle" style="text-align: left;font-size: 1.2em;height:30px"><?php echo TXT_RESPMAILMAIL; ?> </td>
            <td>
                <input style="text-align: left;font-size: 1.2em;height:30px;width:340px;"  type="text"  name="mailresponsable1" id="mailresponsable1" data-dojo-type="dijit/form/ValidationTextBox"
                       value="<?php echo $mailresponsable; ?>"      placeholder="<?php echo TXT_RESPMAILMAIL; ?>"  >
            </td>
        </tr>

        <tr>
            <td valign="middle" style="text-align: left;font-size: 1.2em;height:30px"><?php echo TXT_NOMENTREPRISE; ?> </td>
            <td>
                <?php $nomEntreprise = stripslashes(removeDoubleQuote($nomentreprise)); ?>
                <input style="text-align: left;font-size: 1.2em;height:30px;width:340px;"  type="text"  name="nomentreprise" id="nomentreprise" data-dojo-type="dijit/form/ValidationTextBox"
                       value="<?php echo $nomEntreprise; ?>"    onfocus="enabledmodif()" />
            </td>
        </tr>
    <tr>
        <td style="text-align: left;font-size: 1.2em;height:30px;"><div><?php echo TXT_TYPEENTREPRISE.' :*'; ?></div></td>
        <td>
            <?php  
            $rowtypeentreprise = $manager->getList2("SELECT idtypeentreprise,libelletypeentreprise, libelletypeentrepriseen FROM utilisateur,typeentreprise,appartient WHERE idutilisateur_utilisateur = idutilisateur AND idtypeentreprise_typeentreprise = idtypeentreprise AND  idutilisateur=?",$idutilisateur);
            
            if(!empty($rowtypeentreprise[0]['idtypeentreprise'])){
                $idtypeentreprise=$rowtypeentreprise[0]['idtypeentreprise'];
            }
            $typeentreprise = $manager->getList2("SELECT idtypeentreprise,libelletypeentreprise,libelletypeentrepriseen FROM typeentreprise where masquetypeentreprise!=TRUE and idtypeentreprise!=?",$idtypeentreprise);?>
            <select data-dojo-type="dijit/form/Select" name="typeEntreprise" id="typeEntreprise" style="text-align: left;font-size: 1.2em;height:30px;width:340px" onchange="enabledmodif()">
                <?php
                if(!empty($rowtypeentreprise[0]['idtypeentreprise'])){
                    $idtypeEntreprise='te'.$rowtypeentreprise[0]['idtypeentreprise'];
                    if($lang=='fr'){
                        echo "<option value=".$idtypeEntreprise.">".$rowtypeentreprise[0]['libelletypeentreprise']."</option>";
                    }else{
                        echo "<option value=".$idtypeEntreprise.">".$rowtypeentreprise[0]['libelletypeentrepriseen']."</option>";
                    }
                    $idtypeentreprise=$rowtypeentreprise[0]['idtypeentreprise'];
                }else{
                    echo "<option value='te0'>".TXT_SELECTTYPEENTREPRISE."</option>";
                }
                if($lang=='fr'){
                for ($i = 0;$i < count($typeentreprise);$i++) {
                    echo "<option value='".'te'.$typeentreprise[$i]['idtypeentreprise']."'>".str_replace("''","'",$typeentreprise[$i]['libelletypeentreprise'])."</option>";
                }
                }elseif($lang=='en'){
                    for ($i = 0;$i < count($typeentreprise);$i++) {
                        echo "<option value='".'te'.$typeentreprise[$i]['idtypeentreprise']."'>".str_replace("''","'",$typeentreprise[$i]['libelletypeentrepriseen'])."</option>";
                    }
                }
                ?>
            </select>
        </td>
    </tr>
    <tr id="secteurActivite">
        <td style="text-align: left;font-size: 1.2em;height:30px;"><div><?php echo TXT_SECTEURACTIVITE.' :*';?></div></td>
        <td>
            <?php
            $rowsecteurActivite = $manager->getList2("SELECT idsecteuractivite, libellesecteuractivite, libellesecteuractiviteen, masquesecteuractivite FROM  utilisateur,intervient,secteuractivite 
                WHERE  idsecteuractivite_secteuractivite = idsecteuractivite AND idutilisateur_utilisateur = idutilisateur AND idutilisateur =?",$idutilisateur);
             if(!empty($rowsecteurActivite[0]['idsecteuractivite'])){
                $idsecteuractivite='se'.$rowsecteurActivite[0]['idsecteuractivite'];
            }
            $secteuractivite = $manager->getList2("SELECT  idsecteuractivite,libellesecteuractivite,libellesecteuractiviteen  FROM secteuractivite where masquesecteuractivite!=TRUE and idsecteuractivite!=?",$rowsecteurActivite[0]['idsecteuractivite']);
            ?>
            <select data-dojo-type="dijit/form/Select" name="secteurActivite" id="secteurActivite" style="text-align: left;font-size: 1.2em;height:30px;width:340px" onchange="enabledmodif()">
                <?php
                if(!empty($idsecteuractivite)){
                    if($lang=='fr'){
                        echo "<option value=".$idsecteuractivite.">".$rowsecteurActivite[0]['libellesecteuractivite']."</option>";
                    }else{
                        echo "<option value=".$idsecteuractivite.">".$rowsecteurActivite[0]['libellesecteuractiviteen']."</option>";
                    }
                }else{
                    echo "<option value='se0'>".TXT_SELECTSECTEURACTIVITE."</option>";
                }
                if($lang=='fr'){
                    for ($i = 0;$i < count($secteuractivite);$i++) {
                        echo "<option value='".'sa'.$secteuractivite[$i]['idsecteuractivite']."'>".str_replace("''","'",$secteuractivite[$i]['libellesecteuractivite'])."</option>";
                    }
                }elseif($lang=='en'){
                    for ($i = 0;$i < count($secteuractivite);$i++) {
                        echo "<option value='".'sa'.$secteuractivite[$i]['idsecteuractivite']."'>".$secteuractivite[$i]['libellesecteuractiviteen']."</option>";
                    }
                }
                ?>
            </select>
        </td>
    </tr>
</table>