<?php

if ($lang == 'fr') {
    $libellequalite = $manager->getSingle2("select libellequalitedemandeuraca from qualitedemandeuraca where idqualitedemandeuraca=?", $idqualitedemandeuraca);
} elseif ($lang == 'en') {
    $libellequalite = $manager->getSingle2("select libellequalitedemandeuracaen from qualitedemandeuraca where idqualitedemandeuraca=?", $idqualitedemandeuraca);
}
$libelleQualite = removeDoubleQuote($libellequalite);
$rowQualite = $manager->getListbyArray("SELECT  libellequalitedemandeuraca,libellequalitedemandeuracaen,idqualitedemandeuraca FROM qualitedemandeuraca where idqualitedemandeuraca<>? and idqualitedemandeuraca !=?", 
        array(NAQAUALITEACA, $idqualitedemandeuraca));
$idnonpermanent = 'qa' . NONPERMANENT;
$valeur = '_';
if($lang=='fr'){
    $width='500px';
}else{
    $width='465px';
}
?>
<table>
    <tr>
        <td valign="middle" style="text-align: left;font-size: 1.2em;height:30px"><div style="width:<?php echo $width; ?>"><?php echo TXT_QUALITE; ?></div>
        </td><td></td>
<!-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
                        QUALITE DEMANDEUR
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------>        
        <td>
            <select name="qualiteDemandeuraca" id="qualiteDemandeuraca" data-dojo-type="dijit/form/Select" style="text-align: left;font-size: 1.2em;height:30px;width:340px;" 
                    value="<?php echo $libelleQualite; ?>" onchange="if (this.value === '<?php echo $idnonpermanent; ?>') {afficheAutre('tr_nomreponsable', 'tr_mailresponsable')} else {masqueAutre('tr_nomreponsable', 'tr_mailresponsable')}">
                <option value="<?php if (!empty($idqualitedemandeuraca)) {echo 'qa' . $idqualitedemandeuraca;}?>" ><?php echo $libellequalite; ?></option>
                <?php
                if ($lang == 'fr') {
                    $nbrowQualite = count($rowQualite);
                    for ($i = 0; $i < $nbrowQualite; $i++) {
                        echo "<option value='" . 'qa' . $rowQualite[$i]['idqualitedemandeuraca'] . "'>" .
                        utf8_decode($rowQualite[$i]['libellequalitedemandeuraca']) . "</option>";
                    }
                } elseif ($lang == 'en') {
                    $nbrowQualite = count($rowQualite);
                    for ($i = 0; $i < $nbrowQualite; $i++) {
                        echo "<option value='" . 'qa' . $rowQualite[$i]['idqualitedemandeuraca'] . "'>" .
                        utf8_decode($rowQualite[$i]['libellequalitedemandeuracaen']) . "</option>";
                    }
                }
                ?>
            </select>
        </td>
    </tr>
<?php if ($idqualitedemandeuraca == NONPERMANENT) { ?><!-- AFFICHAGE DU NOM RESPONSABLE ET EMAIL RESPONSABLE  -->
        <tr  id="tr_nomreponsable">
            <td valign="middle" style="text-align: left;font-size: 1.2em;height:30px"><div><?php echo TXT_NOMRESPONSABLE; ?></div></td>
            <td></td><?php $nomResponsable = removeDoubleQuote( $nomresponsable) ?>
            <td>
                <input style="text-align: left;font-size: 1.2em;height:30px;width:340px;"  type="text"  name="nomresponsable" id="nomresponsable" data-dojo-type="dijit/form/ValidationTextBox"
                       onchange="if(this.value===''){this.required='true';enabledmodif();}"    value="<?php echo $nomResponsable; ?>" onfocus="enabledmodif()"  
                         placeholder="<?php echo TXT_RESPNAMEMAIL; ?>"  >
            </td>
        </tr>
        <tr id='tr_mailresponsable'>
            <td valign="middle" style="text-align: left;font-size: 1.2em;height:30px"><?php echo TXT_RESPMAILMAIL; ?> </td><td></td>
            <td>
                <input style="text-align: left;font-size: 1.2em;height:30px;width:340px;"  type="text"  name="mailresponsable" id="mailresponsable" data-dojo-type="dijit/form/ValidationTextBox"
                  onchange="if(this.value===''){this.required='true';enabledmodif();}"   value="<?php echo $mailresponsable; ?>" onfocus="enabledmodif()"
                   placeholder="<?php echo TXT_RESPMAILMAIL; ?>" regExpGen="dojox.validate.regexp.emailAddress" >
            </td>
        </tr>
<?php }else{?>          
        <tr  id="tr_nomreponsable" style="display: none">
                <td valign="middle" style="text-align: left;font-size: 1.2em;height:30px"><div><?php echo TXT_NOMRESPONSABLE; ?></div></td><td></td>
                    <?php $nomResponsable = removeDoubleQuote( $nomresponsable) ?>
                <td>
                    <input style="text-align: left;font-size: 1.2em;height:30px;width:340px;"  type="text"  name="nomresponsable1" id="nomresponsable1" data-dojo-type="dijit/form/ValidationTextBox"
                      onchange="if(this.value===''){this.required='true';enabledmodif();}"    value="<?php echo $nomResponsable; ?>" onfocus="enabledmodif()"   
                        placeholder="<?php echo TXT_RESPNAMEMAIL; ?>"  >
                </td>
            </tr>
            <tr id='tr_mailresponsable' style="display: none">
                <td valign="middle" style="text-align: left;font-size: 1.2em;height:30px"><div><?php echo TXT_RESPMAILMAIL; ?></div></td><td></td>
                <td>
                    <input style="text-align: left;font-size: 1.2em;height:30px;width:340px;"  type="text"  name="mailresponsable1" id="mailresponsable1" data-dojo-type="dijit/form/ValidationTextBox"
                       onchange="if(this.value===''){this.required='true';enabledmodif();}"   onfocus="enabledmodif()"
                       value="<?php echo $mailresponsable; ?>"    placeholder="<?php echo TXT_RESPMAILMAIL; ?>" regExpGen="dojox.validate.regexp.emailAddress" >
                </td>
            </tr>
<?php }?>  
<!-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
                        NOM EMPLOYEUR
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------>
    <?php   
    $rowemployeur = $manager->getList2("SELECT libelleautrenomemployeur, libelleemployeur,libelleemployeuren,idemployeur_nomemployeur FROM   utilisateur, nomemployeur,autrenomemployeur WHERE "
                . "idemployeur = idemployeur_nomemployeur AND  idautrenomemployeur = idautrenomemployeur_autrenomemployeur and idutilisateur=?", $idutilisateur);
        $idrowemployeur = $rowemployeur[0]['idemployeur_nomemployeur'];    
    
    if ($rowemployeur[0]['idemployeur_nomemployeur'] != IDAUTREEMPLOYEUR) {
            include_once 'selectNomEmployeur.php'; 
            
    }else{
        include_once 'selectNomEmployeur.php';
    ?>
                <tr id='tr_autreemployeur'>
                    <td valign="middle" style="text-align: left;font-size: 1.2em;height:30px"><div><?php echo TXT_AUTREEMPLOYEUR; ?></div></td><td></td>
                    <td>                        
                        <input style="text-align: left;font-size: 1.2em;height:30px;width:340px;"  type="text"  name="libelleautreemployeur" id="libelleautreemployeur" data-dojo-type="dijit/form/ValidationTextBox"
                               onfocus="enabledmodif()"  value="<?php if($libelleautrenomemployeur!='n/a'){echo removeDoubleQuote($libelleautrenomemployeur);} ?>"  placeholder="<?php echo TXT_AUTREEMPLOYEUR; ?>"  />
                    </td>
                </tr>
    <?php }?><tr  style="display:none "  id="tr_autreemployeur">                    
                    <td valign="middle" style="text-align: left;font-size: 1.2em;height:30px"><div><?php echo TXT_AUTREEMPLOYEUR; ?></div></td><td></td>
                    <td>
                        <input style="text-align: left;font-size: 1.2em;height:30px;width:340px;"  type="text" name="libelleautreemployeur1" id="libelleautreemployeur1"  data-dojo-type="dijit/form/ValidationTextBox" 
                            onfocus="enabledmodif()"    placeholder="<?php echo TXT_AUTREEMPLOYEUR; ?>" value="<?php if($libelleautrenomemployeur!='n/a'){echo removeDoubleQuote($libelleautrenomemployeur);} ?>" />
                    </td>
                </tr>
                
<!-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
                TUTELLE
--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->
                <?php 
                $rowTutelle = $manager->getList2("SELECT libelleautrestutelle, libelletutelle,libelletutelleen,idtutelle FROM   utilisateur, tutelle,autrestutelle WHERE idtutelle = idtutelle_tutelle AND
                idautrestutelle = idautrestutelle_autrestutelle and   idutilisateur=? ",$idutilisateur);
                $idtutelle=$rowTutelle[0]['idtutelle'];
                if($rowTutelle[0]['idtutelle']!=IDAUTRETUTELLE){
                    include_once 'selectTutelle.php';
                }else{ 
                    include_once 'selectTutelle.php';
                    ?>
                <tr id='tr_autretutelle'>
                    <td valign="middle" style="text-align: left;font-size: 1.2em;height:30px"><div><?php echo TXT_AUTRETUTELLE; ?></div></td><td></td>
                    <td><?php if ($rowTutelle[0]['libelleautrestutelle'] != 'n/a') { $autrestutelle= stripslashes(removeDoubleQuote( ($rowTutelle[0]['libelleautrestutelle']))); }?>
                        <input style="text-align: left;font-size: 1.2em;height:30px;width:340px;" type="text" name="autreTutelle" id="autreTutelle"  data-dojo-type="dijit/form/ValidationTextBox" 
                           onfocus="enabledmodif()"    value="<?php echo $autrestutelle;?>" placeholder="<?php echo TXT_AUTRETUTELLE; ?>" onchange="if(this.value===''){this.required='true';enabledmodif();}"  />
                    </td>
                </tr>
                <?php }?>
                <tr id='tr_autretutelle' style="display: none">
                    <td valign="middle" style="text-align: left;font-size: 1.2em;height:30px"><div><?php echo TXT_AUTRETUTELLE; ?></div></td><td></td>
                    <td>
                        <input style="text-align: left;font-size: 1.2em;height:30px;width:340px;"  type="text" name="autreTutelle1" id="autreTutelle1"  data-dojo-type="dijit/form/ValidationTextBox" 
                           onfocus="enabledmodif()"     placeholder="<?php echo TXT_AUTRETUTELLE; ?>"  />
                    </td>
                </tr>
<!-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
                CODE UNITE
--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->    
                <?php   
if(isset($_SESSION['idTypeUser']) &&$_SESSION['idTypeUser']!=ADMINLOCAL){                
                $rowCodeunite=$manager->getList2("SELECT idcentrale,codeunite,libellecentrale,acronymelaboratoire,villecentrale,idautrecodeunite_autrecodeunite FROM centrale,utilisateur WHERE idcentrale = idcentrale_centrale AND idutilisateur=?", $idutilisateur);               
                if(!empty($rowCodeunite[0]['idcentrale']) && $rowCodeunite[0]['idcentrale']!=IDAUTRECENTRALE){
                    $idcodeunite= $rowCodeunite[0]['idcentrale'];
                }
                 if(!empty($rowCodeunite[0]['idautrecodeunite_autrecodeunite']) ){
                    $idautrecodeunite= $rowCodeunite[0]['idautrecodeunite_autrecodeunite'];
                }else{
                    $idautrecodeunite='';
                }
                $libelleautrecodeunite=$manager->getSingle2("SELECT libelleautrecodeunite FROM utilisateur,autrecodeunite WHERE idautrecodeunite = idautrecodeunite_autrecodeunite AND idutilisateur =? and libelleautrecodeunite!='n/a'", $idutilisateur);
               if(!empty($rowCodeunite)){ 
                    include_once 'selectCodeUnite.php';
                    ?>
                <?php }elseif(!empty($libelleautrecodeunite)){
                        include_once 'selectCodeUnite.php';
                ?>
                <tr id='tr_autrecodeunite' >
                        <td valign="middle" style="text-align: left;font-size: 1.2em;height:30px"><div><?php echo TXT_AUTRECODEUNITE; ?></div></td><td></td>
                        <td><?php $autrescodeunite = removeDoubleQuote($libelleautrecodeunite);?>
                            <input style="text-align: left;font-size: 1.2em;height:30px;width:340px;"  type="text" name="autreCodeunite" id="autreCodeunite"  data-dojo-type="dijit/form/ValidationTextBox" 
                             onfocus="enabledmodif()"     value="<?php echo $autrescodeunite; ?>" placeholder="<?php echo TXT_AUTRECODEUNITE; ?>"  />
                        </td>
                 </tr>                    
                       
                   <?php }else{include_once 'selectCodeUnite.php';}?>
                <?php
                $acronymelabo = trim (stripslashes(removeDoubleQuote($manager->getSingle2("select acronymelaboratoire from utilisateur where idutilisateur=?",$idutilisateur))));
                if(!empty($idcodeunite)){
                    $acronymelabo = $manager->getSingle2("select libellecentrale from centrale where idcentrale=?",$idcodeunite);
                ?>
                <tr><td valign="middle" style="text-align: left;font-size: 1.2em;height:30px"><div><?php echo TXT_NOMLABO; ?></div></td><td></td><td>
                    <input style="text-align: left;font-size: 1.2em;height:30px;width:340px;"  type="text"  name="nomlabo" id="nomlabo" data-dojo-type="dijit/form/ValidationTextBox"
                   onchange="if(this.value===''){this.required='true';enabledmodif();}"  onfocus="enabledmodif()"  value="<?php if(!empty($acronymelabo)){echo $acronymelabo; }elseif(!empty($acronymeLabo)){echo $acronymeLabo;} ?>"  disabled="true"   placeholder="<?php echo TXT_NOMLABO; ?>"  />
                </tr>
                <?php }elseif(empty($idcodeunite)&& !empty($acronymelabo)&& $idautrecodeunite==null){?>
                   <tr><td valign="middle" style="text-align: left;font-size: 1.2em;height:30px"><div><?php echo TXT_NOMLABO; ?></div></td><td></td><td>
                    <input style="text-align: left;font-size: 1.2em;height:30px;width:340px;"  type="text"  name="nomlabo" id="nomlabo" data-dojo-type="dijit/form/ValidationTextBox"
                   onchange="if(this.value===''){this.required='true';enabledmodif();}"  onfocus="enabledmodif()"  value="<?php if(!empty($acronymelabo)){echo $acronymelabo; }elseif(!empty($acronymeLabo)){echo $acronymeLabo;} ?>"   placeholder="<?php echo TXT_NOMLABO; ?>"  />
                </tr>
                
                <?php }else{ ?>
                    <tr><td valign="middle" style="text-align: left;font-size: 1.2em;height:30px"><div><?php echo TXT_NOMLABO; ?></div></td><td></td><td>
                    <input style="text-align: left;font-size: 1.2em;height:30px;width:340px;"  type="text"  name="nomlabo" id="nomlabo" data-dojo-type="dijit/form/ValidationTextBox"
                           required   onchange="enabledmodif()"  onfocus="enabledmodif()" value="<?php if(!empty($acronymelabo)){echo $acronymelabo; }elseif(!empty($acronymeLabo)){echo $acronymeLabo;} ?>"   placeholder="<?php echo TXT_NOMLABO; ?>"  />
                </tr>
                <tr id='tr_autrecodeunite' >
                        <td valign="middle" style="text-align: left;font-size: 1.2em;height:30px"><div><?php echo TXT_AUTRECODEUNITE; ?></div></td><td></td>
                        <td><?php $autrescodeunite = removeDoubleQuote($libelleautrecodeunite);?>
                            <input style="text-align: left;font-size: 1.2em;height:30px;width:340px;"  type="text" name="autreCodeunite2" id="autreCodeunite2"  data-dojo-type="dijit/form/ValidationTextBox" 
                            onchange="if(this.value===''){this.required='true';enabledmodif();}"   onfocus="enabledmodif()"    value="<?php echo $autrescodeunite; ?>" placeholder="<?php echo TXT_AUTRECODEUNITE; ?>"  />
                        </td>
                 </tr>  
                <?php } ?>
                <tr id='tr_autrecodeunite' style="display: none">
                        <td valign="middle" style="text-align: left;font-size: 1.2em;height:30px"><div><?php echo TXT_AUTRECODEUNITE; ?></div></td><td></td>
                        <td><?php $autrescodeunite = removeDoubleQuote($libelleautrecodeunite);?>
                            <input style="text-align: left;font-size: 1.2em;height:30px;width:340px;"  type="text" name="autreCodeunite1" id="autreCodeunite1"  data-dojo-type="dijit/form/ValidationTextBox" 
                            onchange="if(this.value===''){this.required='true';enabledmodif();}"   onfocus="enabledmodif()"    value="<?php echo $autrescodeunite; ?>" placeholder="<?php echo TXT_AUTRECODEUNITE; ?>"  />
                        </td>
                 </tr>
<?php }else{
                $acronymelabo=trim (stripslashes(str_replace("''", "'",$manager->getSingle2("SELECT codeunite FROM utilisateur,centrale WHERE  idcentrale_centrale = idcentrale AND   idutilisateur=?",$idutilisateur))));                
                if(!empty($acronymelabo)){
                ?>
                <tr><td valign="middle" style="text-align: left;font-size: 1.2em;height:30px"><?php echo TXT_NOMLABO; ?> </td><td></td><td>
                        <input style="text-align: left;font-size: 1.2em;height:30px;width:340px"  type="text"  name="nomlabo" id="nomlabo" data-dojo-type="dijit/form/ValidationTextBox"
                               value="<?php echo $acronymelabo; ?>"   disabled="disabled"  placeholder="<?php echo TXT_NOMLABO; ?>"  />
                </tr>
                <?php }
 }?>                 
<!-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
                DISCIPLINE SCIENTIFIQUE
--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->
                <?php
                $rowdiscipline=$manager->getListbyArray("SELECT libelleautrediscipline,libelledisciplineen,libellediscipline,iddiscipline FROM   utilisateur,disciplinescientifique,autredisciplinescientifique 
                    WHERE iddiscipline = iddiscipline_disciplinescientifique AND idautrediscipline = idautrediscipline_autredisciplinescientifique and  idutilisateur=? and libellediscipline!=?",array($idutilisateur,'n/a'));
                $iddiscipline = $rowdiscipline[0]['iddiscipline'];
                $idautrediscipline= IDAUTREDISCIPLINE;
                if($rowdiscipline[0]['iddiscipline']!=IDAUTREDISCIPLINE){
                    include_once 'selectDiscipline.php';
                ?>
                
                <?php }else{
                    include_once 'selectDiscipline.php';
                    ?>
                <tr id='tr_autrediscipline'>
                    <td valign="middle" style="text-align: left;font-size: 1.2em;height:30px"><div><?php echo TXT_AUTREDISCIPLINE; ?></div></td><td></td>
                    <td><?php if ($rowdiscipline[0]['libelleautrediscipline'] != 'n/a') { $autrediscipline= stripslashes(removeDoubleQuote( ($rowdiscipline[0]['libelleautrediscipline']))); }?>
                        <input style="text-align: left;font-size: 1.2em;height:30px;width:340px;"  type="text" name="autrediscipline" id="autrediscipline"  data-dojo-type="dijit/form/ValidationTextBox" 
                         onfocus="enabledmodif()"     value="<?php echo $autrediscipline;?>" placeholder="<?php echo TXT_AUTREDISCIPLINE; ?>" onchange="if(this.value===''){this.required='true';enabledmodif();}" />
                    </td>
                </tr>
                <?php }?>
                <tr id='tr_autrediscipline' style="display: none">                    
                    <td valign="middle" style="text-align: left;font-size: 1.2em;height:30px"><div><?php echo TXT_AUTREDISCIPLINE; ?></div></td><td></td>
                    <td>
                        <input style="text-align: left;font-size: 1.2em;height:30px;width:340px;"  type="text" name="autrediscipline1" id="autrediscipline1"  data-dojo-type="dijit/form/ValidationTextBox" 
                        onfocus="enabledmodif()"       placeholder="<?php echo TXT_AUTREDISCIPLINE; ?>"  />
                    </td>
                </tr>
    
</table>                