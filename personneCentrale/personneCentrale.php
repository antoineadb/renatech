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
    function afficherAutreElement(id1, id2, id3, id4, id5) {
        if ((dijit.byId(id1).value).trim() === 'libqualdemaca' + '<?php echo NONPERMANENT ?>') {
            dijit.byId(id2).domNode.style.display = '';
            document.getElementById(id3).style.display = '';
            if (id4) {
                id4.style.display = '';
            }
        } else {
            dijit.byId(id2).set('value', 'qac2');
            dijit.byId(id2).domNode.style.display = 'none';
            document.getElementById(id3).style.display = 'none';
            document.getElementById(id5).style.display = 'none';
            id4.style.display = 'none';
            if (dijit.byId(id5)) {
                dijit.byId(id5).domNode.style.display = 'none';
            }

        }
    }
    function afficherAutreQualite(id1, id2, id3) {
        if ((dijit.byId(id1).value).trim() === 'qac' + '<?php echo IDAUTREQUALITE ?>') {
            dijit.byId(id2).domNode.style.display = '';
            id3.style.display = '';
        } else {
            dijit.byId(id2).domNode.style.display = 'none';
            id3.style.display = 'none';
        }
    }

</script>
<div style="width: 860px; margin-top:15px;display: block;" id="personCent" >
    <div data-dojo-type="dijit/layout/AccordionContainer" style="height:auto" >
        <?php
        if (isset($_GET['numProjet'])) {
            $numProjet = $_GET['numProjet'];
        } elseif (isset($_GET['idprojet'])) {
            $numProjet = $manager->getsingle2("select numero from projet where idprojet=?", $_GET['idprojet']);
        }
        if (!empty($numProjet)) {
            $arraypersonnecentrale = $manager->getList2("SELECT idqualitedemandeuraca,libellequalitedemandeuraca,libellequalitedemandeuracaen,nomaccueilcentrale,prenomaccueilcentrale,mailaccueilcentrale,telaccueilcentrale,
                idpersonnequalite,idautresqualite,connaissancetechnologiqueaccueil FROM personneaccueilcentrale,projetpersonneaccueilcentrale,qualitedemandeuraca,projet 
                WHERE idpersonneaccueilcentrale_personneaccueilcentrale = idpersonneaccueilcentrale AND idprojet_projet = idprojet AND idqualitedemandeuraca = idqualitedemandeuraca_qualitedemandeuraca AND numero =?", $numProjet);
            $nbarraypersonnecentrale = count($arraypersonnecentrale);        
        }
        if(isset($idprojet)){
            $arrayQualite =$manager->getListbyArray("SELECT pcq.idpersonnequalite,pcq.libellepersonnequalite,q.idqualitedemandeuraca,pac.idpersonneaccueilcentrale FROM personnecentralequalite pcq,
            projetpersonneaccueilcentrale ppac,qualitedemandeuraca q,personneaccueilcentrale pac WHERE pac.idpersonneaccueilcentrale = ppac.idpersonneaccueilcentrale_personneaccueilcentrale 
            AND pac.idqualitedemandeuraca_qualitedemandeuraca = q.idqualitedemandeuraca AND pac.idpersonnequalite = pcq.idpersonnequalite and ppac.idprojet_projet=?", array($idprojet));

        $arrayAutreQualite = $manager->getlistbyArray("SELECT a.libelleautresqualite FROM personneaccueilcentrale pac,projetpersonneaccueilcentrale ppac,autresqualite a "
                . "WHERE pac.idpersonneaccueilcentrale = ppac.idpersonneaccueilcentrale_personneaccueilcentrale AND pac.idautresqualite = a.idautresqualite and ppac.idprojet_projet=? "
                . "and a.libelleautresqualite !=?",array($idprojet,'n/a'));
        }
        $z=-1;
        for ($i = 0; $i < 21; $i++) {?>
            <div style="display: none" id="<?php echo'divpersonne' . $i; ?>">
                <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo TXT_PERSONNE . ' ' . ($i+1); ?>" selected="true" >
                    <table style='padding-left: 20px;width:830px'>
                        <tr>
                            <td>
                                <?php
                                if (isset($arraypersonnecentrale[$i]['nomaccueilcentrale']) && !empty($arraypersonnecentrale[$i]['nomaccueilcentrale'])) {
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
                                if (isset($arraypersonnecentrale[$i]['prenomaccueilcentrale']) && !empty($arraypersonnecentrale[$i]['prenomaccueilcentrale'])) {
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
                        <?php
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              QUALITE ACCUEIL CENTRALE
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
                        //CONTROLE QU'IL EXISTE UNE PERSONNE CENTRALE DANS LA BASE DE DONNEE
                        if (isset($arraypersonnecentrale[$i]['libellequalitedemandeuraca']) && !empty($arraypersonnecentrale[$i]['libellequalitedemandeuraca'])) {?>
                            <tr>
                                <td><label for="<?php echo 'qualiteaccueilcentrale' . $i; ?>" class="perCentrale" ><?php echo TXT_QUALITE . " *"; ?></label>                       
                                    <select   id="<?php echo 'qualiteaccueilcentrale' . $i; ?>" data-dojo-type="dijit/form/Select" 
                                              style="width:317px" data-dojo-props="name: '<?php echo 'qualiteaccueilcentrale' . $i; ?>',value: '',placeHolder: '<?php echo TXT_SELECTQUALITE; ?>'" 
                                              onchange="afficherAutreElement(this.id, '<?php echo 'autreQualite' . $i; ?>', '<?php echo 'labelqualite' . $i; ?>',<?php echo 'autresQualite' . $i; ?>, '<?php echo 'libautresQualite' . $i; ?>')" >
                                        <?php if (isset($arraypersonnecentrale[$i]['idqualitedemandeuraca'])) { 
                                                $row = $manager->getListbyArray("SELECT idqualitedemandeuraca,libellequalitedemandeuraca,libellequalitedemandeuracaen FROM qualitedemandeuraca where "
                                                        . "libellequalitedemandeuraca !=? and libellequalitedemandeuraca != ?", array('n/a', $arraypersonnecentrale[$i]['libellequalitedemandeuraca']));
                                            ?>   
                                            <option value="<?php echo 'libqualdemaca'.$arraypersonnecentrale[$i]['idqualitedemandeuraca']; ?> " selected='selected' > <?php echo $arraypersonnecentrale[$i]['libellequalitedemandeuraca'] ?> </option>
                                        <?php } else { 
                                                $row = $manager->getList2("SELECT idqualitedemandeuraca,libellequalitedemandeuraca,libellequalitedemandeuracaen FROM qualitedemandeuraca where libellequalitedemandeuraca !=?",'n/a');
                                            ?>
                                            <option value='libqualdemaca0' selected='selected'> <?php echo TXT_SELECTQUALITE; ?> </option>
                                        <?php }  

                                        $nbrow = count($row);
                                        for ($k = 0; $k < $nbrow; $k++) {
                                            if (isset($row[$k]['idqualitedemandeuraca'])) {
                                                if ($lang == 'fr') {
                                                    echo "<option value='" . 'libqualdemaca' . $row[$k]['idqualitedemandeuraca'] . "'>" . $row[$k]['libellequalitedemandeuraca'] . "</option>";
                                                } else {
                                                    echo "<option value='" . 'libqualdemaca' . $row[$k]['idqualitedemandeuraca'] . "'>" . $row[$k]['libellequalitedemandeuracaen'] . "</option>";
                                                }
                                            }
                                        }
                                        ?>
                                    </select>                            
                        <?php 
                            if ($arrayQualite[$i]['idqualitedemandeuraca'] == PERMANENT) {?>
                              <tr>
                                <td>
                                    <label for="<?php echo 'autreQualite' . $i; ?>" class="perCentrale" id="<?php echo 'labelqualite' . $i; ?>" style=";display: none" > <?php echo TXT_AUTRESQUALITE; ?></label>
                                    <select  style='width:317px;display: none' id='<?php echo 'autreQualite' . $i; ?>' data-dojo-type='dijit/form/Select' onchange="afficherAutreQualite(this.id, '<?php echo 'autresQualite' . $i; ?>',<?php echo 'libautresQualite' . $i; ?>)"
                                             data-dojo-props="name: '<?php echo 'autreQualite' . $i; ?>',value: '',placeHolder: '<?php echo TXT_SELECTQUALITE; ?>'">
                                                 <?php
                                                 $row = $manager->getList2("SELECT idpersonnequalite,libellepersonnequalite FROM personnecentralequalite where idpersonnequalite!=? ", IDNAAUTRESQUALITE);
                                                 echo "<option value='qac0'  selected='selected'>" . TXT_SELECTVALUE . '</option>';
                                                 $nbrow = count($row);
                                                 for ($k = 0; $k < $nbrow; $k++) {
                                                     echo "<option value='" . 'qac' . $row[$k]['idpersonnequalite'] . "'>" . $row[$k]['libellepersonnequalite'] . "</option>";
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
                            <?php    //IL EXISTE UNE PERSONNE CENTRALE DANS LA BASE DE DONNEE IL EST NON PERMANENT
                            } elseif ($arraypersonnecentrale[$i]['idqualitedemandeuraca'] == NONPERMANENT) {
                                    if (isset($arrayQualite[$i]['idpersonnequalite'])&& $arrayQualite[$i]['idpersonnequalite'] == IDAUTREQUALITE ) {
                                        $z++; ?>
                                        <tr>
                                            <td>
                                                <label for="<?php echo 'autreQualite' . $i; ?>" class="perCentrale" id="<?php echo 'labelqualite' . $i; ?>" > <?php echo TXT_AUTRESQUALITE; ?></label>
                                                <select  style='width:317px' id='<?php echo 'autreQualite' . $i; ?>' data-dojo-type='dijit/form/Select' 
                                                         onchange="afficherAutreQualite(this.id, '<?php echo 'autresQualite' . $i; ?>',<?php echo 'libautresQualite' . $i; ?>)"
                                                         data-dojo-props="name: '<?php echo 'autreQualite' . $i; ?>',value: '',placeHolder: '<?php echo TXT_SELECTQUALITE; ?>'">
                                                             <?php
                                                            $row0 = $manager->getListbyArray("SELECT idpersonnequalite,libellepersonnequalite,libellepersonnequaliteen FROM  personnecentralequalite "
                                                                    . "where idpersonnequalite!=? and idpersonnequalite!=?",array(IDNAAUTREQUALITE,$arrayQualite[$i]['idpersonnequalite'])); 
                                                            $nbrow0 = count($row0);
                                                             echo "<option value='" . 'qac' . IDAUTREQUALITE . "'  selected='selected'>" . TXT_AUTRES . '</option>';
                                                             for ($k = 0; $k < $nbrow0; $k++) {
                                                                 echo "<option value='" . 'qac' . $row0[$k]['idpersonnequalite'] . "'>" . $row0[$k]['libellepersonnequalite'] . "</option>";
                                                             }
                                                             ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label for="<?php echo 'autresQualite' . $i; ?>" class="perCentrale" id="<?php echo 'libautresQualite' . $i; ?>" > <?php echo TXT_AUTRES; ?></label>
                                                <input id="<?php echo 'autresQualite' . $i; ?>" type="text" autocomplete="on" name="<?php echo 'autresQualite' . $i; ?>" 
                                                       data-dojo-type="dijit/form/ValidationTextBox" placeholder="<?php echo TXT_AUTRES; ?>"  data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>"  maxlength="100" 
                                                       style="width: 317px;"  data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð%&:0-9\042\'()+/_ ,.-]+'" 
                                                       value="<?php if(isset($arrayAutreQualite[$z]['libelleautresqualite'])){echo $arrayAutreQualite[$z]['libelleautresqualite'];} ?>" >
                                            </td>
                                        </tr>
                                        
                                        
                              <?php } elseif (isset($arrayQualite[$i]['libellepersonnequalite'])&& $arrayQualite[$i]['idpersonnequalite'] != IDAUTREQUALITE) {
                                            $idpersonnequalite = $arrayQualite[$i]['idpersonnequalite'];
                                            if ($lang == 'fr') {
                                                $libellepersonnequalite = $arrayQualite[$i]['libellepersonnequalite'];
                                            } else {
                                                $libellepersonnequalite = $arrayQualite[$i]['libellepersonnequaliteen'];
                                            }                                        
                                                                                      
                                            ?>
                                            <tr>
                                                <td>
                                                    <label for="<?php echo 'autreQualite' . $i; ?>" class="perCentrale" id="<?php echo 'labelqualite' . $i; ?>" > <?php echo TXT_AUTRESQUALITE; ?></label>
                                                    <select  style='width:317px' id='<?php echo 'autreQualite' . $i; ?>' data-dojo-type='dijit/form/Select' 
                                                             onchange="afficherAutreQualite(this.id, '<?php echo 'autresQualite' . $i; ?>',<?php echo 'libautresQualite' . $i; ?>)"
                                                             data-dojo-props="name: '<?php echo 'autreQualite' . $i; ?>',value: '',placeHolder: '<?php echo TXT_SELECTQUALITE; ?>'">
                                                                 <?php
                                                                    $row1 =$manager->getListbyArray("SELECT idpersonnequalite,libellepersonnequalite,libellepersonnequaliteen FROM personnecentralequalite "
                                                                            . "where idpersonnequalite!=? and idpersonnequalite!=?",array($idpersonnequalite, IDNAAUTREQUALITE));
                                                                    $nbrow1 = count($row1);
                                                                    echo "<option value='" . 'qac' . $idpersonnequalite . "'  selected='selected'>" . $libellepersonnequalite . '</option>';
                                                                    for ($k = 0; $k < $nbrow1; $k++) {
                                                                        if($lang=='fr'){
                                                                           echo "<option value='" . 'qac' . $row1[$k]['idpersonnequalite'] . "'>" . $row1[$k]['libellepersonnequalite'] . "</option>";
                                                                        }else{
                                                                            echo "<option value='" . 'qac' . $row1[$k]['idpersonnequalite'] . "'>" . $row1[$k]['libellepersonnequaliteen'] . "</option>";
                                                                        }
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
                                        <?php }else{ echo '3';?>
                                             <tr>
                                                <td>
                                                    <label for="<?php echo 'autreQualite' . $i; ?>" class="perCentrale" id="<?php echo 'labelqualite' . $i; ?>" style=";display: none" > <?php echo TXT_AUTRESQUALITE; ?></label>
                                                    <select  style='width:317px;display: none' id='<?php echo 'autreQualite' . $i; ?>' data-dojo-type='dijit/form/Select' onchange="afficherAutreQualite(this.id, '<?php echo 'autresQualite' . $i; ?>',<?php echo 'libautresQualite' . $i; ?>)"
                                                             data-dojo-props="name: '<?php echo 'autreQualite' . $i; ?>',value: '',placeHolder: '<?php echo TXT_SELECTQUALITE; ?>'">
                                                                 <?php
                                                                 $row = $manager->getList2("SELECT idpersonnequalite,libellepersonnequalite FROM personnecentralequalite where idpersonnequalite!=? ", IDNAAUTRESQUALITE);
                                                                 echo "<option value='qac0'  selected='selected'>" . TXT_SELECTVALUE . '</option>';
                                                                 $nbrow = count($row);
                                                                 for ($k = 0; $k < $nbrow; $k++) {
                                                                     echo "<option value='" . 'qac' . $row[$k]['idpersonnequalite'] . "'>" . $row[$k]['libellepersonnequalite'] . "</option>";
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
                                        <?php }?>
                            <?php }
                            //IL N'EXISTE PAS DE PERSONNE CENTRALE DANS LA BASE DE DONNEE
                        } else {?>
                             <tr>
                                <td><label for="<?php echo 'qualiteaccueilcentrale' . $i; ?>" class="perCentrale" ><?php echo TXT_QUALITE . " *"; ?></label>                       
                                    <select   id="<?php echo 'qualiteaccueilcentrale' . $i; ?>" data-dojo-type="dijit/form/Select" 
                                              style="width:317px" data-dojo-props="name: '<?php echo 'qualiteaccueilcentrale' . $i; ?>',value: '',placeHolder: '<?php echo TXT_SELECTQUALITE; ?>'" 
                                              onchange="afficherAutreElement(this.id, '<?php echo 'autreQualite' . $i; ?>', '<?php echo 'labelqualite' . $i; ?>',<?php echo 'autresQualite' . $i; ?>, '<?php echo 'libautresQualite' . $i; ?>')" >
                                        <?php if (isset($arraypersonnecentrale[$i]['idqualitedemandeuraca'])) { 
                                                $row = $manager->getListbyArray("SELECT idqualitedemandeuraca,libellequalitedemandeuraca,libellequalitedemandeuracaen FROM qualitedemandeuraca where "
                                                        . "libellequalitedemandeuraca !=? and libellequalitedemandeuraca != ?", array('n/a', $arraypersonnecentrale[$i]['libellequalitedemandeuraca']));
                                            ?>   
                                                <option value='libqualdemaca' <?php echo $arraypersonnecentrale[$i]['idqualitedemandeuraca']; ?> > <?php echo $arraypersonnecentrale[$i]['libellequalitedemandeuraca'] ?> </option>
                                        <?php } else { 
                                                $row = $manager->getList2("SELECT idqualitedemandeuraca,libellequalitedemandeuraca,libellequalitedemandeuracaen FROM qualitedemandeuraca where libellequalitedemandeuraca !=?",'n/a');
                                            ?>
                                                <option value='libqualdemaca0'> <?php echo TXT_SELECTQUALITE; ?> </option>
                                        <?php }  

                                        $nbrow = count($row);
                                        for ($k = 0; $k < $nbrow; $k++) {
                                            if (isset($row[$k]['idqualitedemandeuraca'])) {
                                                if ($lang == 'fr') {
                                                    echo "<option value='" . 'libqualdemaca' . $row[$k]['idqualitedemandeuraca'] . "'>" . $row[$k]['libellequalitedemandeuraca'] . "</option>";
                                                } else {
                                                    echo "<option value='" . 'libqualdemaca' . $row[$k]['idqualitedemandeuraca'] . "'>" . $row[$k]['libellequalitedemandeuracaen'] . "</option>";
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                            </tr>  
                            <tr>
                                <td>
                                    <label for="<?php echo 'autreQualite' . $i; ?>" class="perCentrale" id="<?php echo 'labelqualite' . $i; ?>" style=";display: none" > <?php echo TXT_AUTRESQUALITE; ?></label>
                                    <select  style='width:317px;display: none' id='<?php echo 'autreQualite' . $i; ?>' data-dojo-type='dijit/form/Select' onchange="afficherAutreQualite(this.id, '<?php echo 'autresQualite' . $i; ?>',<?php echo 'libautresQualite' . $i; ?>)"
                                             data-dojo-props="name: '<?php echo 'autreQualite' . $i; ?>',value: '',placeHolder: '<?php echo TXT_SELECTQUALITE; ?>'">
                                                 <?php
                                                 $row = $manager->getList2("SELECT idpersonnequalite,libellepersonnequalite FROM personnecentralequalite where idpersonnequalite!=? ", IDNAAUTRESQUALITE);
                                                 echo "<option value='qac0'  selected='selected'>" . TXT_SELECTVALUE . '</option>';
                                                 $nbrow = count($row);
                                                 for ($k = 0; $k < $nbrow; $k++) {
                                                     echo "<option value='" . 'qac' . $row[$k]['idpersonnequalite'] . "'>" . $row[$k]['libellepersonnequalite'] . "</option>";
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
                        <?php
                        }
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
                        ?>                       
                        <tr>
                            <td>
                                <label for="<?php echo 'mailaccueilcentrale' . $i; ?>" class="perCentrale" ><?php echo TXT_MAIL . " *"; ?></label>
                                <input data-dojo-type="dijit/form/ValidationTextBox" style="width: 317px;"  name="<?php echo 'mailaccueilcentrale' . $i; ?>" id="<?php echo 'mailaccueilcentrale' . $i; ?>"  regExpGen="dojox.validate.regexp.emailAddress"
                                       invalidMessage="<?php echo TXT_EMAILNONVALIDE; ?>" autocomplete="on"  placeHolder ='<?php echo TXT_EMAIL; ?>'    disabled="<?php echo $bool; ?>"
                                       value="<?php
                                       if (isset($arraypersonnecentrale[$i]['mailaccueilcentrale']) && !empty($arraypersonnecentrale[$i]['mailaccueilcentrale'])) {
                                           echo $arraypersonnecentrale[$i]['mailaccueilcentrale'];
                                       }
                                       ?>" 
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
                                       if (isset($arraypersonnecentrale[$i]['telaccueilcentrale']) && !empty($arraypersonnecentrale[$i]['telaccueilcentrale'])) {
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
                                        if (isset($arraypersonnecentrale[$i]['connaissancetechnologiqueaccueil']) && !empty($arraypersonnecentrale[$i]['connaissancetechnologiqueaccueil'])) {
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
