<?php
include_once 'outils/constantes.php';
include_once 'outils/toolBox.php';
include_once 'decide-lang.php';
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
?>
<script>
function afficherAutreElement(id1, id2,id3,id4,id5,id6,id7,id8) {
    if (dijit.byId(id1).value === 'libqualdemaca'+'<?php echo NONPERMANENT ?>') {
        dijit.byId(id2).domNode.style.display = '';
        document.getElementById(id3).style.display = '';
        id7.style.display = '';
        id8.style.display = '';
    } else {
        dijit.byId(id2).domNode.style.display = 'none';
        document.getElementById(id3).style.display = 'none';
        dijit.byId(id4).domNode.style.display = 'none';
        document.getElementById(id5).style.display = 'none';
        if(id6){
            id6.style.display = 'none';
        }
        id7.style.display = 'none';
        id8.style.display = 'none';
    }
}
function afficherAutreQualite(id1,id2,id3){
    if (dijit.byId(id1).value ==='qac'+'<?php echo IDAUTREQUALITE ?>') {
        dijit.byId(id2).domNode.style.display = '';
        id3.style.display = '';
    } else {        
        dijit.byId(id2).domNode.style.display = 'none';
        id3.style.display = 'none';
        if(dijit.byId('autresQualite')){
            dijit.byId('autresQualite').domNode.style.display='none';
            document.getElementById('libautresQualite').style.display = 'none';
        }
    }
}

</script>
<div style="width: 860px; margin-top:15px;display: block;" id="personCent" >
<!--<div style="width: 1200px; margin-top:15px;display: block;" id="personCent" >-->
    <div data-dojo-type="dijit/layout/AccordionContainer" style="height:auto" >
        <?php
        if (isset($_GET['numProjet'])) {
            $numProjet = $_GET['numProjet'];
        } elseif (isset($_GET['idprojet'])) {
            $numProjet = $manager->getsingle2("select numero from projet where idprojet=?", $_GET['idprojet']);
        }//rajouter //idpersonnequalite,//idautresqualite
        if (!empty($numProjet)) {
            $arraypersonnecentrale = 
                    $manager->getList2(
                            "SELECT 
                                idqualitedemandeuraca,
                                libellequalitedemandeuraca,
                                libellequalitedemandeuracaen,
                                nomaccueilcentrale,
                                prenomaccueilcentrale,
                                mailaccueilcentrale,
                                telaccueilcentrale,
                                connaissancetechnologiqueaccueil
                                FROM personneaccueilcentrale,projetpersonneaccueilcentrale,qualitedemandeuraca,projet 
                                WHERE idpersonneaccueilcentrale_personneaccueilcentrale = idpersonneaccueilcentrale 
                                AND idprojet_projet = idprojet 
                                AND idqualitedemandeuraca = idqualitedemandeuraca_qualitedemandeuraca 
                                AND numero =?", $numProjet);
        }        
        for ($i = 0; $i < 21; $i++) {
            $j = $i + 1;
            ?>
            <div style="display: none" id="<?php echo'divpersonne' . $i; ?>">
                <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo TXT_PERSONNE . ' ' . $j; ?>" selected="true" >
                    <table style='padding-left: 20px;width:830px'>
                        <tr>
                            <td>
                                <?php
                                if (isset($arraypersonnecentrale[$i]['nomaccueilcentrale'])) {
                                    $nomaccueilcentrale = str_replace("''", "'", stripslashes($arraypersonnecentrale[$i]['nomaccueilcentrale']));
                                } else {
                                    $nomaccueilcentrale = '';
                                }
                                ?>
                                <label for="<?php echo 'nomaccueilcentrale' . $i; ?>" class="perCentrale" ><?php echo TXT_NOM . '*'; ?></label>
                                <input style="width: 317px" data-dojo-type="dijit/form/ValidationTextBox"  name="<?php echo 'nomaccueilcentrale' . $i; ?>" id="<?php echo 'nomaccueilcentrale' . $i; ?>"  autocomplete="on"
                                       data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð%&:0-9\042\'()_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>"
                                       value="<?php echo $nomaccueilcentrale; ?>" disabled="<?php echo $bool; ?>" >
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="<?php echo 'prenomaccueilcentrale' . $i; ?>" class="perCentrale" ><?php echo TXT_PRENOM . '*'; ?></label>
                                <?php
                                if (isset($arraypersonnecentrale[$i]['prenomaccueilcentrale'])) {
                                    $prenomaccueilcentrale = str_replace("''", "'", stripslashes($arraypersonnecentrale[$i]['prenomaccueilcentrale']));
                                } else {
                                    $prenomaccueilcentrale = '';
                                }
                                ?>
                                <input style="width: 317px"  data-dojo-type="dijit/form/ValidationTextBox" name="<?php echo 'prenomaccueilcentrale' . $i; ?>" id="<?php echo 'prenomaccueilcentrale' . $i; ?>" autocomplete="on"
                                       data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð%&:0-9\042\'()_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>"
                                       value="<?php echo $prenomaccueilcentrale ?>" disabled="<?php echo $bool; ?>" >
                            </td>
                        </tr>
                        <tr>
                            <td><label for="<?php echo 'qualiteaccueilcentrale' . $i; ?>" class="perCentrale" ><?php echo TXT_QUALITE . " *"; ?></label>                                
                                    <select   id="<?php echo 'qualiteaccueilcentrale' . $i; ?>" data-dojo-type="dijit/form/Select" style="width:317px"  
                                     data-dojo-props="name: '<?php echo 'qualiteaccueilcentrale' . $i; ?>',value: '',placeHolder: '<?php echo TXT_SELECTQUALITE; ?>'" >         
                                   <!-- onchange="afficherAutreElement(this.id,'<?php /*echo 'autreQualite' . $i; ?>','<?php echo 'labelqualite' . $i; ?>','<?php echo 'autresqualite' . $i;  */ ?>',-->
                                                <!--'<?php /*/*echo 'libautresqualite' . $i; ?>',<?php echo 'libautresQualite' . $i; ?>,<?php echo 'autresQualite' . $i; ?>,<?php echo 'libautresQualite' . $i; */?>)"-->
                                              
                            <?php 
                                        if (!empty($arraypersonnecentrale[$i]['idqualitedemandeuraca'])) {                                           
                                            if($lang=='fr'){
                                                echo "<option value='" . 'libqualdemaca' . $arraypersonnecentrale[$i]['idqualitedemandeuraca'] . "'>" . $arraypersonnecentrale[$i]['libellequalitedemandeuraca'] . '</option>';
                                                $row = $manager->getListbyArray("SELECT idqualitedemandeuraca,libellequalitedemandeuraca FROM qualitedemandeuraca where libellequalitedemandeuraca !=? and libellequalitedemandeuraca != ?", array('n/a', $arraypersonnecentrale[$i]['libellequalitedemandeuraca']));
                                                $nbrow = count($row);
                                                for ($k = 0; $k < $nbrow; $k++) {
                                                    echo "<option value='" . 'libqualdemaca' . $row[$k]['idqualitedemandeuraca'] . "'>" . $row[$k]['libellequalitedemandeuraca'] . "</option>";
                                                }
                                            }else{
                                                echo "<option value='" . 'libqualdemaca' . $arraypersonnecentrale[$i]['idqualitedemandeuraca'] . "'>" . $arraypersonnecentrale[$i]['libellequalitedemandeuracaen'] . '</option>';
                                                $row = $manager->getListbyArray("SELECT idqualitedemandeuraca,libellequalitedemandeuracaen FROM qualitedemandeuraca where libellequalitedemandeuraca !=? and libellequalitedemandeuracaen != ?", array('n/a', $arraypersonnecentrale[$i]['libellequalitedemandeuracaen']));
                                                $nbrow = count($row);
                                                for ($k = 0; $k < $nbrow; $k++) {
                                                    echo "<option value='" . 'libqualdemaca' . $row[$k]['idqualitedemandeuraca'] . "'>" . $row[$k]['libellequalitedemandeuracaen'] . "</option>";
                                                }
                                            }
                                        } else {
                                            if($lang=='fr'){
                                                $row = $manager->getListbyArray("SELECT idqualitedemandeuraca,libellequalitedemandeuraca FROM qualitedemandeuraca where libellequalitedemandeuraca !=? ", array('n/a'));
                                                $nbrow = count($row);
                                                for ($k = 0; $k < $nbrow; $k++) {
                                                    echo "<option value='" . 'libqualdemaca' . $row[$k]['idqualitedemandeuraca'] . "'>" . $row[$k]['libellequalitedemandeuraca'] . "</option>";
                                                }
                                            }else{
                                                $row = $manager->getListbyArray("SELECT idqualitedemandeuraca,libellequalitedemandeuracaen FROM qualitedemandeuraca where libellequalitedemandeuracaen !=? ", array('n/a'));
                                                $nbrow = count($row);
                                                for ($k = 0; $k < $nbrow; $k++) {
                                                    echo "<option value='" . 'libqualdemaca' . $row[$k]['idqualitedemandeuraca'] . "'>" . $row[$k]['libellequalitedemandeuracaen'] . "</option>";
                                                }
                                            }
                                        }
                            ?>
                                    </select>
                            </td>
                        </tr>
                        <?php /* if (isset($arraypersonnecentrale[$i]['idpersonnequalite'])&& $arraypersonnecentrale[$i]['idpersonnequalite']==IDNAAUTRESQUALITE) {
                            //CAS DE LA MODIFICATION D'UNE PERSONNE PERMANENTE    ?>
                        <tr>
                            <td><label for="<?php echo 'autreQualite' . $i; ?>" class="perCentrale" id="<?php echo 'labelqualite' . $i; ?>" style="display:none;" > <?php echo TXT_AUTRESQUALITE; ?></label>                                
                                <select  style='display:none;width:317px' id='<?php echo 'autreQualite' . $i; ?>' data-dojo-type='dijit/form/Select' name="<?php echo 'autreQualite' . $i; ?>"
                                         onchange="afficherAutreQualite(this.id,'<?php echo 'autresqualite' . $i; ?>',<?php echo 'libautresqualite' . $i; ?>)" 
                                         data-dojo-props="name: '<?php echo 'autreQualite' . $i; ?>',value: '',placeHolder: '<?php echo TXT_SELECTQUALITE; ?>'">
                                         >
                                    <?php                                        
                                        if ($lang == 'fr') {                                            
                                            echo "<option value='' selected='selected'>" .TXT_AUTRESQUALITE. '</option>';
                                            $row = $manager->getList2("SELECT idpersonnequalite,libellepersonnequalite FROM personnecentralequalite where libellepersonnequalite !=?", 'n/a');
                                            $nbrow = count($row);
                                            for ($k = 0; $k < $nbrow; $k++) {
                                                echo "<option value='" . 'qac' . $row[$k]['idpersonnequalite'] . "'>" . $row[$k]['libellepersonnequalite'] . "</option>";
                                            }
                                        } else {                                            
                                            echo "<option value='' selected='selected'>" . TXT_AUTRESQUALITE . '</option>';
                                            $row = $manager->getList2("SELECT idpersonnequalite,libellepersonnequaliteen FROM personnecentralequalite where libellepersonnequalite !=?", 'n/a');
                                            $nbrow = count($row);
                                            for ($k = 0; $k < $nbrow; $k++) {
                                                echo "<option value='" . 'qac' . $row[$k]['idpersonnequalite'] . "'>" . $row[$k]['libellepersonnequaliteen'] . "</option>";
                                            }
                                        }
                                        ?>
                                </select>
                            </td>
                        </tr>
                        <?php }else{//CAS OU LA PERSONNE A DEJA SELECTIONNE AUTRES ?>                        
                                <tr>
                            <td><label for="<?php echo 'autreQualite' . $i; ?>" class="perCentrale" id="<?php echo 'labelqualite' . $i; ?>"  > <?php echo TXT_AUTRESQUALITE; ?></label>                                
                                <select  style='width:317px' id='<?php echo 'autreQualite' . $i; ?>' data-dojo-type='dijit/form/Select'                                       
                                         onchange="afficherAutreQualite(this.id,'<?php echo 'autresQualite' . $i; ?>',<?php echo 'libautresQualite' . $i; ?>)" 
                                         data-dojo-props="name: '<?php echo 'autreQualite' . $i; ?>',value: '',placeHolder: '<?php echo TXT_SELECTQUALITE; ?>'">
                                    <?php                                         
                                        if($lang == 'fr') {
                                            $libellepersonnequalite = $manager->getSingle2("select libellepersonnequalite from personnecentralequalite where idpersonnequalite =?", $arraypersonnecentrale[$i]['idpersonnequalite']);
                                            echo "<option value='" . 'qac' . $arraypersonnecentrale[$i]['idpersonnequalite'] . "' selected='selected'>" . $libellepersonnequalite . '</option>';                                             
                                            $row = $manager->getListbyArray("SELECT idpersonnequalite,libellepersonnequalite FROM personnecentralequalite where libellepersonnequalite !=? "
                                                    . "and libellepersonnequalite != ?", array('n/a', $libellepersonnequalite));
                                            $nbrow = count($row);
                                            for ($k = 0; $k < $nbrow; $k++) {
                                                echo "<option value='" . 'qac' . $row[$k]['idpersonnequalite'] . "'>" . $row[$k]['libellepersonnequalite'] ."</option>";
                                            }
                                        } else {
                                            $libellepersonnequalite = $manager->getSingle2("select libellepersonnequaliteen from personnecentralequalite where idpersonnequalite =?", $arraypersonnecentrale[$i]['idpersonnequalite']);
                                            echo "<option value='" . 'qac' . $arraypersonnecentrale[$i]['idpersonnequalite'] . "' >" . $libellepersonnequalite . '</option>';
                                            $row = $manager->getListbyArray("SELECT idpersonnequalite,libellepersonnequaliteen FROM personnecentralequalite where libellepersonnequalite !=? "
                                                    . "and libellepersonnequaliteen != ?", array('n/a', $libellepersonnequalite));
                                            $nbrow = count($row);
                                            for ($k = 0; $k < $nbrow; $k++) {
                                                echo "<option value='" . 'qac' . $row[$k]['idpersonnequalite'] . "'>" . $row[$k]['libellepersonnequaliteen'] . "</option>";
                                            }
                                        }
                                        ?>
                                </select>
                            </td>
                        </tr><?php  $libelleautrequalite = $manager->getSingle2("select libelleautresqualite from personneaccueilcentrale p,autresqualite a,projetpersonneaccueilcentrale pa where p.idautresqualite = a.idautresqualite and pa.idpersonneaccueilcentrale_personneaccueilcentrale= p.idpersonneaccueilcentrale and pa.idprojet_projet=?",$idprojet);?>
                            <tr>
                                <td><label for="<?php echo 'autresQualite' . $i; ?>" class="perCentrale" id="<?php echo 'libautresQualite' . $i; ?>" > <?php echo TXT_AUTRES; ?></label>   
                                    <input id="<?php echo 'autresQualite' . $i; ?>" type="text" autocomplete="on" name="<?php echo 'autresQualite' . $i; ?>" value="<?php echo $libelleautrequalite;?>"
                                data-dojo-type="dijit/form/ValidationTextBox" placeholder="<?php echo TXT_AUTRES;?>" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING ;?>"  maxlength="100"
                                style="width: 317px;" value="" data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð%&:0-9\042\'()+/_ ,.-]+'">
                            </td>
                        </tr>
                        
                        <?php }?>
                        <tr>
                            <td><label for="<?php echo 'autresqualite' . $i; ?>" class="perCentrale" id="<?php echo 'libautresqualite' . $i; ?>" style="display:none;" > <?php echo TXT_AUTRES.' toto'; ?></label>   
                                <input id="<?php echo 'autresqualite' . $i; ?>" type="text" autocomplete="on" name="<?php echo 'autresqualite' . $i; ?>" 
                                data-dojo-type="dijit/form/ValidationTextBox" placeholder="<?php echo TXT_AUTRES;?>" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING ;?>"  maxlength="100"
                                style="width: 317px;display:none" value="" data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð%&:0-9\042\'()+/_ ,.-]+'">
                            </td>
                        </tr>
        <tr><?php  */ ?>
                            <td>
                                <label for="<?php echo 'mailaccueilcentrale' . $i; ?>" class="perCentrale" ><?php echo TXT_MAIL . " *"; ?></label>
                                <input data-dojo-type="dijit/form/ValidationTextBox" style="width: 317px"  name="<?php echo 'mailaccueilcentrale' . $i; ?>" id="<?php echo 'mailaccueilcentrale' . $i; ?>"  regExpGen="dojox.validate.regexp.emailAddress"
                                       invalidMessage="<?php echo TXT_EMAILNONVALIDE; ?>" autocomplete="on"  placeHolder ='<?php echo TXT_EMAIL; ?>'    disabled="<?php echo $bool; ?>"
                                       value="<?php
                                       if (isset($arraypersonnecentrale[$i]['mailaccueilcentrale'])) {
                                           echo $arraypersonnecentrale[$i]['mailaccueilcentrale'];
                                       } ?>" 
                                >
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="<?php echo 'telaccueilcentrale' . $i; ?>" class="perCentrale" ><?php echo TXT_TELEPHONE; ?></label>
                                <input type="text" name="<?php echo 'telaccueilcentrale' . $i; ?>"  data-dojo-type="dijit/form/ValidationTextBox"  style="width: 317px"
                                       data-dojo-props="maxLength:'20',regExp:'[a-zA-Z0-9$\\054\\073\\340\\047\\341\\342\\343\\344\\345\\346\\347\\350\\351\\352\\353\\354\\355\\356\\357\\360\\361\\362\\363\\364\\365\\366\\370\\371\\372\\373\\374\\375\\376\\377\\s\.\-]+',
                                       invalidMessage:'<?php echo TXT_ERRSTRINGTEL; ?>'"  autocomplete="on" disabled="<?php echo $bool; ?>"
                                       value="<?php
                                       if (isset($arraypersonnecentrale[$i]['telaccueilcentrale'])) {
                                           echo $arraypersonnecentrale[$i]['telaccueilcentrale'];
                                       }
                                       ?>"
                                >
                            </td>
                        </tr><tr><td><br></td></tr>
                        <tr>
                            <td>
                                <label for="<?php echo 'connaissancetechnologiqueaccueil' . $i; ?>" style="width: 450px;"><?php echo TXT_CONNAISSANCETECHNOLOGIQUE . ' :'; ?>
                                    <a class="infoBulle" href="#"><img src='<?php echo '/' . REPERTOIRE; ?>/styles/img/help.gif' height="13px" width="13px"/><span style="width: 360px"><?php echo affiche('TXT_AIDECONNAISSANCETECHNOLOGIQUE'); ?></span></a>
                                </label>
                                <?php if ($bool == 'true') { ?>
                                    <textarea  name="<?php echo 'connaissancetechnologiqueaccueil' . $i; ?>" data-dojo-type="dijit/form/SimpleTextarea" readonly="true" rows="50" cols="84" style="height: 100px"><?php
                                        if (isset($arraypersonnecentrale[$i]['connaissancetechnologiqueaccueil'])) {
                                            echo str_replace("''", "'", stripslashes($arraypersonnecentrale[$i]['connaissancetechnologiqueaccueil']));
                                        }
                                        ?>
                                    </textarea>
                                <?php } else { ?>
                                    <textarea  name="<?php echo 'connaissancetechnologiqueaccueil' . $i; ?>" data-dojo-type="dijit/form/SimpleTextarea"    rows="50" cols="84" style="height: 100px"><?php
                                        if (isset($arraypersonnecentrale[$i]['connaissancetechnologiqueaccueil'])) {
                                            echo str_replace("''", "'", stripslashes($arraypersonnecentrale[$i]['connaissancetechnologiqueaccueil']));
                                        }
                                        ?>
                                    </textarea>
                                <?php } ?>
                            </td>
                        </tr>
                        <script>require(["dojo/parser", "dijit/form/Textarea"]);</script>
                        <tr><td><br></td></tr>
                    </table>
                </div>
            </div><?php } ?>
    </div>    
</div>
