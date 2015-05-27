<?php
session_start();
include('decide-lang.php');
include_once 'class/Manager.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include_once 'outils/toolBox.php';
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
    $statutCompte = array(TXT_ACTIF, TXT_NONACTIF);
    ?>
<div style="padding-top: 75px;">
    <?php include'outils/bandeaucentrale.php'; ?>
</div>
    <table>
    <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>
    <?php
    if(!empty($_GET['msgerrcentrale'])){;?>
    <tr>
        <td>
            <fieldset id='ident' style="width: 1005px">
                <legend><?php echo TXT_ERR;?></legend>
                <?php 	echo TXT_MESSAGEERREUREMAILCENTRALESELECT;?>
            </fieldset>

        </td>
    </tr>
    <?php } ?>
</table>
    <div style="margin-top:5px;width:1050px" >
        <form  method="post" action="<?php echo '/'.REPERTOIRE; ?>/comptes/<?php echo $lang; ?>" id='gestioncompte' name='gestioncompte' >
            <fieldset id="ident" style="border-color: #5D8BA2;width: 1008px;padding-bottom:30px;padding-top:10px;font-size:1.2em" >
                <legend><?php echo TXT_RECHERCHEUSER; ?></legend>
                <table>
                    <tr><td><br></td></tr>
                    <tr>
                        <td valign="top" style="width: 80px;"><input type="text" style="width: 210px;border-radius: 8px;padding: 3px;" placeholder="<?php echo TXT_NOMUTILISATEUR; ?>" name="nom" id="nom" data-dojo-type="dijit/form/ValidationTextBox" autocomplete="on"/></td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td valign="top" style="width: 100px;text-align: left;"><input type="text" style="width: 210px;border-radius: 8px;padding: 3px;" placeholder="<?php echo TXT_PRENOMUTILISATEUR; ?>" name="prenom" id="prenom" data-dojo-type="dijit/form/ValidationTextBox" autocomplete="on"/></td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td>&nbsp;&nbsp;</td>
                        <td valign="bottom" ><input name ="rechercheUser" type="submit" ondragover="tous"  value="<?php echo TXT_BOUTONRECHERCHE; ?>"  class="maj" onclick="
                                if (document.getElementById('nom').value === '' && document.getElementById('prenom').value === '') {
                                    document.getElementById('nom').value = '*';
                                }"/>
                        </td>
                    </tr>
                </table>
                <table>
                    <tr><td><br></td></tr>
                    <tr><td>
                            <?php if (isset($_POST['academiqueinterne'])) { ?>
                                <input  class='opt'  type='checkbox' data-dojo-type='dijit/form/CheckBox' id='academiqueinterne' name='academiqueinterne' value="true" checked />
                                <label for='test' class='opt' ><?php echo TXT_ACADEMIQUEINTERNE; ?></label>
                            <?php } else { ?>
                                <input  class='opt'  type='checkbox' data-dojo-type='dijit/form/CheckBox' id='academiqueinterne' name='academiqueinterne' value="true"  checked />
                                <label for='test' class='opt' ><?php echo TXT_ACADEMIQUEINTERNE; ?></label>
                            <?php }if (isset($_POST['academiqueexterne'])) { ?>
                                <input  class='opt'  type='checkbox' data-dojo-type='dijit/form/CheckBox' id='academiqueexterne' name='academiqueexterne' value="true"  checked />
                                <label for='test' class='opt' ><?php echo TXT_ACADEMIQUEEXTERNE; ?></label>
                            <?php } else { ?>
                                <input  class='opt'  type='checkbox' data-dojo-type='dijit/form/CheckBox' id='academiqueexterne' name='academiqueexterne' value="true"   checked />
                                <label for='test' class='opt' ><?php echo TXT_ACADEMIQUEEXTERNE; ?></label>
                            <?php }if (isset($_POST['industriel'])) { ?>
                                <input  class='opt'  type='checkbox' data-dojo-type='dijit/form/CheckBox' id='industriel' name='industriel' value="true"  checked checked />
                                <label for='test' class='opt' ><?php echo TXT_INDUSTRIEL; ?></label>
                            <?php } else { ?>
                                <input  class='opt'  type='checkbox' data-dojo-type='dijit/form/CheckBox' id='industriel' name='industriel' value="true"   checked />
                                <label for='test' class='opt' ><?php echo TXT_INDUSTRIEL; ?></label>
                            <?php } ?>
                        </td></tr>
                </table>
            </fieldset>
            <?php
            if (!isset($_POST['nom']) || !isset($_POST['prenom'])) {
                if (!isset($_GET['iduser'])) {
                    include 'html/footer.html';
                }
            }
            if (!empty($_POST['nom']) || !empty($_POST['prenom']) || isset($_GET['idutilisateur'])) {
                if (isset($_GET['erreur'])) {
                    echo "<div id='erreur' style='width:525px; margin-top:15px;text-align:center;color:red'>" . $_GET['erreur'] . "</div>";
                }
                ?>
                <fieldset id='compte' style="border-color: #5D8BA2;width: 1018px;padding:15px; margin-top: 15px;font-size:1.2em" >
                    <legend><?php echo TXT_RESULTAT; ?></legend>
                    <?php
                    $_SESSION['page_precedente'] = basename(__FILE__);
                    include 'findUsercompte.php';
                    ?>
                </fieldset><?php
                include 'html/footer.html';
            }
            if (!empty($_GET['iduser']) && !empty($_GET['idqualiteaca']) && empty($_GET['idqualiteindust'])) {
                $iduser = $_GET['iduser'];
                $row = $manager->getList2("
SELECT u.nom,u.prenom,u.adresse,u.ville,u.codepostal,u.telephone,u.fax,u.idpays_pays,u.idqualitedemandeuraca_qualitedemandeuraca,l.pseudo,l.mail,l.actif,qa.libellequalitedemandeuraca,n.libelleemployeur,n.idemployeur,an.libelleautrenomemployeur,at.libelleautrestutelle,t.idtutelle,t.libelletutelle,d.libellediscipline,d.iddiscipline,ad.libelleautrediscipline,
at.idautrestutelle,an.idautrenomemployeur,ad.idautrediscipline,u.nomresponsable,u.mailresponsable,u.idtypeutilisateur_typeutilisateur,administrateur
FROM utilisateur u,loginpassword l,qualitedemandeuraca qa,nomemployeur n,autrenomemployeur an,autrestutelle at,tutelle t,autrecodeunite ac,disciplinescientifique d,autredisciplinescientifique ad
WHERE l.idlogin = u.idlogin_loginpassword AND qa.idqualitedemandeuraca = u.idqualitedemandeuraca_qualitedemandeuraca AND
n.idemployeur = u.idemployeur_nomemployeur AND an.idautrenomemployeur = u.idautrenomemployeur_autrenomemployeur AND 
at.idautrestutelle = u.idautrestutelle_autrestutelle AND  ad.idautrediscipline = u.idautrediscipline_autredisciplinescientifique and
t.idtutelle = u.idtutelle_tutelle AND ac.idautrecodeunite = u.idautrecodeunite_autrecodeunite and 
d.iddiscipline = u.iddiscipline_disciplinescientifique and idutilisateur=?", $iduser);
                for ($i = 0; $i < count($iduser); $i++) {
                    $nom = $row[$i]['nom'];
                    $prenom = $row[$i]['prenom'];
                    $adresse = $row[$i]['adresse'];
                    $mail  = $row[$i]['mail'];
                    $ville = $row[$i]['ville'];
                    $tel = $row[$i]['telephone'];
                    $fax = $row[$i]['fax'];
                    $codepostal = $row[$i]['codepostal'];
                    $idpays  = $row[$i]['idpays_pays'];
                    $libellepays = $manager->getSingle2("select nompays from pays where idpays=?", $idpays);
                    $pseudo = $row[$i]['pseudo'];                    
                    $idqualitedemandeuraca = $row[$i]['idqualitedemandeuraca_qualitedemandeuraca'];
                    $libellequalitedemandeuraca = $row[$i]['libellequalitedemandeuraca'];
                    $libelleemployeur = $row[$i]['libelleemployeur'];
                    $libelleautrenomemployeur = trim($row[$i]['libelleautrenomemployeur']);
                    $libelleautrestutelle = trim($row[$i]['libelleautrestutelle']);
                    $libelletutelle = $row[$i]['libelletutelle'];
                    $libellediscipline = $row[$i]['libellediscipline'];
                    $libelleautrediscipline = $row[$i]['libelleautrediscipline'];
                    $nomresponsable = $row[$i]['nomresponsable'];
                    $mailresponsable = $row[$i]['mailresponsable'];
                    $actif = $row[$i]['actif'];
                    $iddroit = $row[$i]['idtypeutilisateur_typeutilisateur'];
                    $administrateur = $row[$i]['administrateur'];
                }
                if (empty($actif)) {
                    $valeuractif = TXT_NONACTIF;
                } else {
                    $valeuractif = TXT_ACTIF;
                }
                ?><br>
                <fieldset id="compteuser" style="border-color: #5D8BA2;width: 1008px;padding-bottom:10px;padding-top:8px;font-size:1.2em;" >
                    <legend><?php echo TXT_COMPTE; ?></legend>
                    <?php
                    if (isset($_GET['update']) && $_GET['update'] == 'ok') {
                        echo '<table style="width:500px" align:center><tr><td  align=center><div  style="height:20px "><b>Compte mis à jour</b></div></td><tr></table>';
                    }
                    ?>
                    <div id="compteutilisateuraca" >
                        <table>
                            <tr>
                                <td valign="top" style="text-align: left"><?php echo TXT_PSEUDO; ?></td><td style="width: 110px"></td>
                                <td>
                                    <input id="pseudo" style="width: 318px;margin-left: 50px"  type="text" name="pseudo" data-dojo-type="dijit/form/ValidationTextBox" value="<?php echo $pseudo ?>"  />
                                </td>                                
                            </tr>
                             <tr>
                                <td valign="top" style="text-align: left"><?php echo TXT_MAIL; ?></td><td style="width: 110px"></td>
                                <td>
                                    <input id="mail" style="width: 318px;margin-left: 50px"  type="text" name="mail" data-dojo-type="dijit/form/ValidationTextBox" value="<?php echo $mail ?>" 
                                            data-dojo-type="dijit/form/ValidationTextBox" regExpGen="dojox.validate.regexp.emailAddress" required="required"
                               invalidMessage="Email non valide." autocomplete="on" onfocus="enabledmodif()" >
                                           
                                </td>                                
                            </tr>
                            <tr>
                                <td valign="top"><?php echo TXT_NOM; ?> </td><td></td>
                                <td>
                                    <input type="text" style="width: 318px;margin-left: 50px"  required="required" name="nomuser" id="nomuser" data-dojo-type="dijit/form/ValidationTextBox" value="<?php echo removeDoubleQuote($nom); ?>"    />
                                </td>
                            </tr>
                            <tr>
                                <td valign="top"><?php echo TXT_PRENOM; ?></td><td></td>
                                <td>
                                    <input type="text" style="width: 318px;margin-left: 50px"  required="required" name="prenomuser" data-dojo-type="dijit/form/ValidationTextBox" id="prenomuser" value="<?php echo removeDoubleQuote($prenom); ?>"   />
                                </td>
                            </tr>
                            <tr>
                                    <td valign="top"><?php echo TXT_ADRESSE; ?></td><td></td>
                                    <td>
                                        <input type="text" style="width: 318px;margin-left: 50px"  required="required" name="adresseuser" data-dojo-type="dijit/form/ValidationTextBox" id="adresseuser" 
                                               value="<?php echo removeDoubleQuote($adresse); ?>"  onfocus="enabledmodif()" >
                                    </td>
                                </tr>
                            <tr>
                                <td valign="top"><?php echo TXT_CP; ?></td><td></td>                                
                                <td>
                                    <input style="width: 318px;margin-left: 50px"  type="text" required="required" name="cp" id="cp"  placeholder="" data-dojo-type="dijit/form/ValidationTextBox"
                                           value="<?php echo $codepostal;?>"  onfocus="enabledmodif()" >
                                </td>
                            </tr>
                             <tr>
                                <td valign="top"><?php echo TXT_VILLE; ?></td><td></td>                                
                                <td>
                                    <input style="width: 318px;margin-left: 50px"  type="text" required="required" name="ville" id="ville"  placeholder="" data-dojo-type="dijit/form/ValidationTextBox"
                                           value="<?php echo $ville;?>"  onfocus="enabledmodif()" >
                                </td>
                            </tr>
                            
                            <tr>
                                <th style="text-align: left"><?php echo TXT_PAYS; ?></th><td></td>
                                <td>
                                    <?php $rowpays = $manager->getList('SELECT idpays,nompays FROM pays order by idpays asc;'); ?>
                                    <select name="nompays" id="nompays" data-dojo-type="dijit/form/Select" style="width: 318px;margin-left: 50px">
                                                <?php
                                                
                                                for ($i = 0; $i < count($rowpays); $i++) {
                                                    if ($libellepays == $rowpays[$i]['nompays']) {
                                                        echo '<option value="' .$rowpays[$i]['idpays'] . '"  selected="selected" >' . $libellepays . '</option>';
                                                    } else {
                                                        echo '<option value="' .$rowpays[$i]['idpays'] . '">' . $rowpays[$i]['nompays'] . '</option>';
                                                    }
                                                }
                                                ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td valign="top"><?php echo TXT_TELEPHONE; ?></td><td></td>
                                <td>
                                    <input type="text" style="width: 318px;margin-left: 50px" maxlength="20"  required="required" name="teluser" data-dojo-type="dijit/form/ValidationTextBox" id="teluser" value="<?php echo removeDoubleQuote($tel); ?>"   />
                                </td>
                            </tr>
                            <tr>
                                <td valign="top"><?php echo TXT_FAX; ?></td><td></td>
                                <td>
                                    <input type="text" style="width: 318px;margin-left: 50px"  maxlength="20"  name="faxuser" data-dojo-type="dijit/form/ValidationTextBox" id="faxuser" value="<?php echo removeDoubleQuote($fax); ?>"   />
                                </td>
                            </tr>
                            <tr>
                                <th style="text-align: left"><?php echo TXT_QUALITEDEMANDEUR; ?></th><td></td>
                                <td>
                                    <?php 
                                    if($lang=='fr'){
                                        $rowqualite = $manager->getList2('SELECT idqualitedemandeuraca,libellequalitedemandeuraca FROM qualitedemandeuraca where idqualitedemandeuraca!=? order by idqualitedemandeuraca asc;',NAQAUALITEACA);
                                    }else{
                                         $rowqualite = $manager->getList2('SELECT idqualitedemandeuraca,libellequalitedemandeuracaen FROM qualitedemandeuraca where idqualitedemandeuraca!=? order by idqualitedemandeuraca asc;',NAQAUALITEACA);
                                    }?>
                                    <select name="qualitedemandeuraca" id="qualitedemandeuraca" data-dojo-type="dijit/form/Select" style="width: 318px;margin-left: 50px" 
                                            onChange="if (dijit.byId(this.id).get('value') === '<?php echo 'qa'.NONPERMANENT ?>') {
                                                        document.getElementById('trnomresponsable').style.display = 'table-row';
                                                        document.getElementById('trmailresponsable').style.display = 'table-row';
                                                    } else {
                                                        document.getElementById('trnomresponsable').style.display = 'none';
                                                        document.getElementById('trmailresponsable').style.display = 'none';
                                                    }
                                                    ;" >
                                                <?php
                                                
                                                for ($i = 0; $i < count($rowqualite); $i++) {
                                                    if ($idqualitedemandeuraca == $rowqualite[$i]['idqualitedemandeuraca']) {
                                                        echo '<option value="' . 'qa' . $rowqualite[$i]['idqualitedemandeuraca'] . '"  selected="selected" >' . $libellequalitedemandeuraca . '</option>';
                                                    } else {
                                                        if($lang=='fr'){
                                                            echo '<option value="' . 'qa' . $rowqualite[$i]['idqualitedemandeuraca'] . '">' . $rowqualite[$i]['libellequalitedemandeuraca'] . '</option>';
                                                        }else{
                                                            echo '<option value="' . 'qa' . $rowqualite[$i]['idqualitedemandeuraca'] . '">' . $rowqualite[$i]['libellequalitedemandeuracaen'] . '</option>';
                                                        }
                                                    }
                                                }
                                                ?>
                                    </select>
                                </td>
                            </tr>
                            
                            <?php
                            if (!empty($nomresponsable)) {
                                $nomResponsable = removeDoubleQuote($nomresponsable);
                                ?>                                
                                <tr  id="trnomresponsable">
                                        <td valign="top"><?php echo TXT_NOMRESPONSABLE; ?> </td><td></td>
                                        <td>
                                            <input type="text" required="required" style="width: 318px;margin-left: 50px"  name="nomresponsable" id="nomresponsable"  data-dojo-type="dijit/form/ValidationTextBox" autocomplete="on" value="<?php echo $nomResponsable; ?>"
                                                   data-dojo-props="regExp:'[a-zA-Z0-9$\\340\\047\\341\\342\\343\\344\\345\\346\\347\\350\\351\\352\\353\\354\\355\\356\\357\\360\\361\\362\\363\\364\\365\\366\\370\\371\\372\\373\\374\\375\\376\\377\\s\.\-]+'" />
                                        </td>
                                    </tr>
                                <tr   id="trmailresponsable">
                                        <td valign="top"><?php echo TXT_RESPMAILMAIL; ?> </td><td></td>
                                        <td>
                                             <input id="mailresponsable" type="text" name="mailresponsable" class="long" data-dojo-type="dijit/form/ValidationTextBox"   regExpGen="dojox.validate.regexp.emailAddress" required="required"
                                                   style="width: 318px;margin-left: 50px"  invalidMessage="<?php echo TXT_EMAILNONVALIDE ?>" autocomplete="on"  value="<?php echo $mailresponsable; ?>"/>
                                        </td>
                                    </tr>                                        
                            <?php }else{?>
                                <tr  id="trnomresponsable" style="display: none">
                                    <td valign="top"><?php echo TXT_NOMRESPONSABLE; ?> </td><td></td>
                                    <td>
                                        <input type="text" required="required" style="width: 318px;margin-left: 50px"  name="nomresponsable" id="nomresponsable"  data-dojo-type="dijit/form/ValidationTextBox" autocomplete="on" 
                                               data-dojo-props="regExp:'[a-zA-Z0-9$\\340\\047\\341\\342\\343\\344\\345\\346\\347\\350\\351\\352\\353\\354\\355\\356\\357\\360\\361\\362\\363\\364\\365\\366\\370\\371\\372\\373\\374\\375\\376\\377\\s\.\-]+'" />
                                    </td>
                                </tr>
                                <tr   id="trmailresponsable" style="display: none">
                                        <td valign="top"><?php echo TXT_RESPMAILMAIL; ?> </td><td></td>
                                        <td>
                                             <input id="mailresponsable" type="text" name="mailresponsable1 class="long" data-dojo-type="dijit/form/ValidationTextBox"   regExpGen="dojox.validate.regexp.emailAddress" required="required"
                                                   style="width: 318px;margin-left: 50px"  invalidMessage="<?php echo TXT_EMAILNONVALIDE ?>" autocomplete="on"  
                                        </td>
                                    </tr>
                           <?php }?>             
                            <tr>
                                <th style="text-align: left"><?php echo TXT_NOMEMPLOYEUR; ?></th><td></td>
                                <td>
                                    <?php $rowlibelleemployeur = $manager->getList('SELECT idemployeur,libelleemployeur FROM nomemployeur order by idemployeur asc;'); ?>
                                    <select name="libelleemployeur" id="libelleemployeur" data-dojo-type="dijit/form/Select" style="width: 318px;margin-left: 50px"
                                            onChange="if (dijit.byId(this.id).get('displayedValue') === 'Autres') {
                                                        document.getElementById('trAutrenom').style.display = ''
                                                    } else {
                                                        document.getElementById('trAutrenom').style.display = 'none'
                                                    }
                                                    ;" >
                                                <?php
                                                for ($i = 0; $i < count($rowlibelleemployeur); $i++) {
                                                    if ($libelleemployeur == $rowlibelleemployeur[$i]['libelleemployeur']) {
                                                        echo '<option value="' . 'ne' . ($rowlibelleemployeur[$i]['idemployeur']) . '"  selected="selected" >' . $libelleemployeur . '</option>';
                                                    } else {
                                                        echo '<option value="' . 'ne' . ($rowlibelleemployeur[$i]['idemployeur']) . '">' . $rowlibelleemployeur[$i]['libelleemployeur'] . '</option>';
                                                    }
                                                }
                                                ?>
                                    </select>
                                </td>
                            </tr>
                            <tr  style="display:none "  id="trAutrenom">
                                <th style="text-align: left"><?php echo TXT_AUTREEMPLOYEUR; ?></th><td></td>
                                <td>
                                    <input type="text" name="autreEmployeur" style="width: 318px;margin-left: 50px"  id="autreEmployeur"  data-dojo-type="dijit/form/ValidationTextBox" value="<?php
                                    if ($libelleautrenomemployeur != 'n/a') {
                                        echo stripslashes(str_replace("''", "'", ($libelleautrenomemployeur)));
                                    }
                                    ?>"/>
                                </td>
                            </tr>
                            <tr>
                                <th style="text-align: left"><?php echo TXT_TUTELLE; ?></th><td></td>
                                <td>
                                    <?php $rowtutelle = $manager->getList('SELECT idtutelle,libelletutelle FROM tutelle order by idtutelle asc;'); ?>
                                    <select name="libelletutelle" id="libelletutelle" data-dojo-type="dijit/form/Select" style="width: 318px;margin-left: 50px"
                                            onChange="if (dijit.byId(this.id).get('displayedValue') === 'Autres') {
                                                        document.getElementById('trAutretutelle').style.display = ''
                                                    } else {
                                                        document.getElementById('trAutretutelle').style.display = 'none'
                                                    }
                                                    ;" >
                                        <?php
                                        for ($i = 0; $i < count($rowtutelle); $i++) {
                                            if ($libelletutelle == $rowtutelle[$i]['libelletutelle']) {
                                                echo '<option value="' . 'tu' . ($rowtutelle[$i]['idtutelle']) . '" selected="selected">' . str_replace("''", "'", $libelletutelle) . '</option>';
                                            } else {
                                                echo '<option value="' . 'tu' . ($rowtutelle[$i]['idtutelle']) . '">' . str_replace("''", "'", $rowtutelle[$i]['libelletutelle']) . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr  style="display:none "  id="trAutretutelle">
                                <th style="text-align: left"><?php echo TXT_AUTRETUTELLE; ?></th><td></td>
                                <td>
                                    <input type="text"  style="width: 318px;margin-left: 50px"  id="autreTutelle"  name="autreTutelle" data-dojo-type="dijit/form/ValidationTextBox" value="<?php
                                    if ($libelleautrestutelle != 'n/a') {
                                        echo stripslashes(str_replace("''", "'", $libelleautrestutelle));
                                    }
                                    ?>"/>
                                </td>                           
                            <tr>
                                <th style="text-align: left"><?php echo TXT_DISCIPLINESCIENTIFIQUE; ?></th><td></td>
                                <td><?php $rowDiscipline = $manager->getList('SELECT iddiscipline,libellediscipline,libelledisciplineen FROM disciplinescientifique where masquediscipline!=TRUE order by iddiscipline asc;'); ?>
                                    <select name="libellediscipline" id="libellediscipline" data-dojo-type="dijit/form/Select" style="width: 318px;margin-left: 50px"
                                            onChange="if (dijit.byId(this.id).get('DisplayedValue') === 'Autres') {document.getElementById('trDiscipline').style.display = ''}else{document.getElementById('trDiscipline').style.display = 'none'};" >
                                                <?php
                                                for ($i = 0; $i < count($rowDiscipline); $i++) {
                                                    if ($libellediscipline == $rowDiscipline[$i]['libellediscipline']) {
                                                        echo '<option value="' . 'di' . ($rowDiscipline[$i]['iddiscipline']) . '"  selected="selected" >' . $libellediscipline . '</option>';
                                                    } else {
                                                        if($lang=='fr'){
                                                            echo '<option value="' . 'di' . ($rowDiscipline[$i]['iddiscipline']) . '">' . $rowDiscipline[$i]['libellediscipline'] . '</option>';
                                                        }else{
                                                            echo '<option value="' . 'di' . ($rowDiscipline[$i]['iddiscipline']) . '">' . $rowDiscipline[$i]['libelledisciplineen'] . '</option>';
                                                        }
                                                    }
                                                }
                                                ?>
                                    </select>
                                </td>
                            </tr>
                            <tr  style="display:none "  id="trDiscipline">
                                <th style="text-align: left"><?php echo TXT_AUTREDISCIPLINE; ?></th><td></td>
                                <td>
                                    <input type="text" id="autreDiscipline" style="width: 318px;margin-left: 50px"  name="autreDiscipline" data-dojo-type="dijit/form/ValidationTextBox" value="<?php
                                    if ($libelleautrediscipline != 'n/a') {
                                        echo stripslashes(str_replace("''", "'", ($libelleautrediscipline)));
                                    } 
                                    ?>" required="required" />
                                </td>
                            </tr>
                            <!--  ACRONYME DU LABORATOIRE -->
                            <?php
                            $acronymelaboratoire = $manager->getSingle2("select acronymelaboratoire from utilisateur where idutilisateur =?", $_GET['iduser']);
                            ?>
                            <tr>
                                <th style="text-align: left"><?php echo TXT_NOMLABO; ?></th><td></td>
                                <td>
                                    <input type="text" required="required" style="width: 318px;margin-left: 50px"  name="acronymelaboratoire" id="acronymelaboratoire"  data-dojo-type="dijit/form/ValidationTextBox" autocomplete="on" value="<?php echo stripslashes(str_replace("''", "'", $acronymelaboratoire)); ?>"
                                           placeholder="<?php echo TXT_NOMLABO; ?>"   data-dojo-props="regExp:'[a-zA-Z0-9àáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð/\'();_ ,.-]+'"  />
                                </td>
                            </tr>
                            <tr>
                                <th style="text-align: left"><?php echo TXT_STATUTCOMPTE; ?></th><td></td>
                                <td>
                                    <select name="statutcompte" id="statutcompte" data-dojo-type="dijit/form/Select" style="width: 318px;margin-left: 50px"  >
                                        <?php
                                        foreach ($statutCompte as $key => $value) {
                                            if ($valeuractif == $value) {
                                                echo '<option value="' . $value . '" selected="selected">' . $value . '</option>';
                                            } else {
                                                echo "<option value='" . $value . "'>" . $value . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr><th style="text-align: left"><?php echo TXT_ADMINDEPROJET; ?>
                                <a class="infoBulle" href="#"><img src='<?php echo "/".REPERTOIRE; ?>/styles/img/help.gif' height="13px" width="13px"/>
                                        <span style="text-align: left;padding:10px;width: 750px;border-radius:5px" ><?php echo affiche('TXT_AIDEADMINDEPROJET');?></span>
                                    </a></th>
                                <td></td>
                                <td>
                                    <div style="margin-left: 50px">
                                        <?php if($administrateur==1){ ?>
                                        <input type= "radio" data-dojo-type="dijit/form/RadioButton" name="administrateur" id ="adminOui" checked="true"  class="btRadio" ><?php echo TXT_OUI; ?>
                                            <input type= "radio" data-dojo-type="dijit/form/RadioButton" name="administrateur" id="adminNon"  class="btRadio"> <?php echo TXT_NON; ?>
                                        <?php }else{ ?>
                                            <input type= "radio" data-dojo-type="dijit/form/RadioButton" name="administrateur" id ="adminOui"   class="btRadio" ><?php echo TXT_OUI; ?>
                                            <input type= "radio" data-dojo-type="dijit/form/RadioButton" name="administrateur" id="adminNon" checked="true"  class="btRadio"> <?php echo TXT_NON; ?>
                                        <?php } ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <?php $libelledroit = $manager->getSingle2("select libelletype FROM typeutilisateur where idtypeutilisateur=?", $iddroit); ?>
                                <th style="text-align: left"><?php echo TXT_DROITACTUEL; ?></th><td></td>
                                <td>
                                    <input name ="rechercheUser" type="text" data-dojo-type="dijit/form/ValidationTextBox" disabled="disabled" value="<?php echo $libelledroit; ?>"   style="width: 318px;margin-left: 50px"  >
                                </td>
                            </tr>
                            <?php
                            $idtypeutilisateur = $manager->getSingle2("SELECT idtypeutilisateur_typeutilisateur FROM  loginpassword, utilisateur WHERE  idlogin = idlogin_loginpassword and pseudo=?", $_SESSION['pseudo']);
                            if($idtypeutilisateur==ADMINNATIONNAL){
                                $rowdroit = $manager->getList("SELECT libelletype,idtypeutilisateur	 FROM typeutilisateur");                        
                            ?>
                        <tr>
                            <th style="text-align: left;"><?php echo TXT_NOUVEAUDROIT; ?></th><td></td>
                            <td>
                                <select name="libelledroit" id="libelledroit" data-dojo-type="dijit/form/Select" style="width: 220px;"
                                        data-dojo-props="  value: '' ,onChange:
                                        function(value){
                                            if(value=='tu4'){
                                                document.getElementById('central').style.display='';
                                            }else {
                                                document.getElementById('central').style.display='none';
                                            }
                                        }
                                        " >
                                            <?php
                                            for ($i = 0; $i < count($rowdroit); $i++) {
                                                if ($libelledroit == $rowdroit[$i]['libelletype']) {
                                                    echo "<option value='" . 'tu' . $iddroit . " ' selected='selected'>" . $libelledroit . "</option>";
                                                } else {
                                                    echo '<option value="' . 'tu' . ($rowdroit[$i]['idtypeutilisateur']) . '">' . $rowdroit[$i]['libelletype'] . '</option>';
                                                }
                                            }
                                            ?>
                                </select>

                            </td>
                        </tr>
                        
                        <?php                        
                            $libellecentrale = $manager->getSingle2("SELECT libellecentrale FROM utilisateur,centrale WHERE idcentrale = idcentrale_centrale and idutilisateur=?", $_GET['iduser']);
                            $idcentrale = $manager->getSingle2("select idcentrale from centrale where libellecentrale=?", $libellecentrale);
                            $rowcentrale = $manager->getList2("select libellecentrale,idcentrale from centrale where libellecentrale!='Autres' and idcentrale!=?", $idcentrale);
                            $rowcentrale2 = $manager->getList("select libellecentrale,idcentrale from centrale where libellecentrale!='Autres'");
                        ?>
                        <tr id='central' style="display: none" >
                            <th style="text-align: left;"><?php echo TXT_CENTRALE; ?></th><td></td>
    <?php if (!empty($libellecentrale)) { ?>
                                <td>
                                    <select name="centrale" id="centrale"   data-dojo-type="dijit/form/Select" style="width: 318px;margin-left: 50px" data-dojo-props="value:'<?php echo $libellecentrale; ?>',placeHolder:'<?php echo TXT_SELECTCENTRALE; ?>'"  >
                                        <option ><?php echo $libellecentrale; ?></option>
                                        <?php
                                        for ($i = 0; $i < count($rowcentrale); $i++) {
                                            echo '<option value="' . 'ce' . ($rowcentrale[$i]['idcentrale']) . '">' . $rowcentrale[$i]['libellecentrale'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </td>
    <?php } else { ?>
                                <td>
                                    <select name="centrale" required="true"  id="centrale" data-dojo-type="dijit/form/FilteringSelect" style="width: 318px;margin-left: 50px" data-dojo-props="value:'',placeHolder:'<?php echo TXT_SELECTCENTRALE; ?>'"  >
                                        <?php
                                        for ($i = 0; $i < count($rowcentrale2); $i++) {
                                            echo '<option value="' . 'ce' . ($rowcentrale2[$i]['idcentrale']) . '">' . $rowcentrale2[$i]['libellecentrale'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </td>
    <?php } ?>
                        </tr>                        
                            <?php } ?>
                        </table>
                        <button id="progButtonNode" data-dojo-type="dijit/form/Button" type="submit" name="submitButtonThree" value="Submit"><?php echo TXT_MAJ; ?></button>
                    </div>
                </fieldset>
                <script>
                    require(["dijit/form/Button", "dojo/dom", "dojo/domReady!"], function(Button, dom) {
                        // Create a button programmatically:
                        var myButton = new Button({
                            label: "<?php echo TXT_MAJ; ?>",
                            onClick: function() {
                                if (!empty(dijit.byId('pseudo'))){
                                    var pseudo = dijit.byId('pseudo').value;
                                }
                                if (!empty(dijit.byId('mail'))){
                                    var mail = dijit.byId('mail').value;
                                }
                                if (!empty(dijit.byId('nomuser'))){
                                    var nomuser = dijit.byId('nomuser').value;
                                }
                                if (!empty(dijit.byId('prenomuser'))){
                                    var prenomuser = dijit.byId('prenomuser').value;
                                }
                                if (!empty(dijit.byId('adresseuser'))){
                                    var adresseuser = dijit.byId('adresseuser').value;
                                }
                                if (!empty(dijit.byId('nompays'))){
                                    var nompays = dijit.byId('nompays').value;
                                }
                                if (!empty(dijit.byId('teluser'))){
                                    var teluser = dijit.byId('teluser').value;
                                }
                                if (!empty(dijit.byId('faxuser'))){
                                    var faxuser = dijit.byId('faxuser').value;
                                }
                                if (!empty(dijit.byId('cp'))){
                                    var codepostal = dijit.byId('cp').value;
                                }
                                if (!empty(dijit.byId('ville'))){
                                    var ville = dijit.byId('ville').value;
                                }                                
                                if (!empty(dijit.byId('qualitedemandeuraca'))){
                                    var idqualitedemandeuraca = dijit.byId('qualitedemandeuraca').value;
                                }
                                if (!empty(document.getElementById('nomresponsable'))) {
                                    var nomresponsable = document.getElementById('nomresponsable').value;
                                } else {
                                    nomresponsable = "";
                                }
                                if (!empty(document.getElementById('mailresponsable'))) {
                                    var mailresponsable = document.getElementById('mailresponsable').value;
                                } else {
                                    mailresponsable = "";
                                }
                                if (!empty(dijit.byId('libelleemployeur'))) {
                                    var idemployeur = dijit.byId('libelleemployeur').value;
                                } else {
                                    idemployeur = "";
                                }
                                if (!empty(dijit.byId('autreEmployeur')) && document.getElementById('trAutrenom').style.display !== 'none') {
                                    var libelleautrenomemployeur = dijit.byId('autreEmployeur').value;
                                } else {
                                    libelleautrenomemployeur = "";
                                }
                                if (!empty(dijit.byId('libelletutelle'))) {
                                    var idtutelle = dijit.byId('libelletutelle').value;
                                } else {
                                    idtutelle = "";
                                }
                                if (!empty(document.getElementById('autreTutelle')) && document.getElementById('trAutretutelle').style.display !== 'none') {
                                    var libelleautretutelle = document.getElementById('autreTutelle').value;
                                } else {
                                    libelleautretutelle = "";
                                }
                                if (!empty(dijit.byId('libellediscipline'))) {
                                    var iddiscipline = dijit.byId('libellediscipline').value;
                                } else {
                                    iddiscipline = "";
                                }
                                if (!empty(document.getElementById('autreDiscipline')) && document.getElementById('trDiscipline').style.display !== 'none') {
                                    var libelleautrediscipline = document.getElementById('autreDiscipline').value;
                                } else {
                                    libelleautrediscipline = "";
                                }
                                if (!empty(dijit.byId('statutcompte'))) {
                                    var statutcompte = dijit.byId('statutcompte').value;
                                } else {
                                    statutcompte = "";
                                }
                                if (!empty(dijit.byId('libelledroit'))) {
                                    var role = dijit.byId('libelledroit').value;
                                } else {
                                    role = "";
                                }
                                if (!empty(dijit.byId('acronymelaboratoire'))) {
                                    var acronymelaboratoire = dijit.byId('acronymelaboratoire').value;
                                } else {
                                    acronymelaboratoire = "";
                                }
                                if (!empty(dijit.byId('centrale'))) {
                                    var centrale = dijit.byId('centrale').value;
                                } else {
                                    centrale = "";
                                }
                                if (dijit.byId('adminOui').checked) {
                                    var admin = 1;
                                } else {
                                    admin = 0;
                                }
                                
                                
                                
                                window.location.replace("<?php echo '/'.REPERTOIRE ?>/modifBase/majgestioncompteaca.php?pseudo="+pseudo+"&mail="+mail+"&nomuser="+nomuser+"&prenomuser="+prenomuser+"&pays="+nompays+"&teluser="+teluser+
                                        "&faxuser="+faxuser+"&adresseuser="+adresseuser+"&codepostal="+codepostal+"&ville="+ville+"&qualitedemandeuraca="+idqualitedemandeuraca+"&nomresponsable=" + nomresponsable +"&mailresponsable=" + mailresponsable + "&acronymelaboratoire=" + acronymelaboratoire + "&idemployeur=" + idemployeur + "&role=" + role + "&idtutelle=" + idtutelle 
                                + "&libelleautretutelle=" + libelleautretutelle + "&libelleautrenomemployeur=" + libelleautrenomemployeur + "&iddiscipline=" + iddiscipline + "&centrale=" + centrale 
                                + "&libelleautrediscipline=" + libelleautrediscipline + "&iduser=" +<?php echo $iduser; ?> + "&statutcompte=" + statutcompte +"&admin="+admin+ "&page_precedente=<?php echo basename(__FILE__); ?>");
                            }
                        }, "progButtonNode");
                    });
                </script>
                <script>
                    require(["dojo"], function(dojo) {
                        dojo.addOnLoad(function() {
                            if (dijit.byId('libelleemployeur').get("displayedValue") === 'Autres') {
                                document.getElementById('trAutrenom').style.display = '';
                            } else {
                                document.getElementById('trAutrenom').style.display === 'none'
                            }
                            if (dijit.byId('libelletutelle').get("displayedValue") === 'Autres') {
                                document.getElementById('trAutretutelle').style.display = '';
                            } else {
                                document.getElementById('trAutretutelle').style.display = 'none';
                            }
                            if (dijit.byId('libellediscipline').get("displayedValue") === 'Autres') {
                                document.getElementById('trDiscipline').style.display = '';
                            } else {
                                document.getElementById('trDiscipline').style.display = 'none';
                            }
                        }
                        )
                    })
                </script>
                <?php
                include 'html/footer.html';
/*  ----------------------------------------------------------------------------------------------------------------------------------------------------------------
 *  CAS INDUSTRIEL
 *  ----------------------------------------------------------------------------------------------------------------------------------------------------------------
 */
            } elseif (!empty($_GET['iduser']) && empty($_GET['idqualiteaca']) && !empty($_GET['idqualiteindust'])) {
                $iduser = $_GET['iduser'];
                $rowindust = $manager->getListbyArray(
                        "SELECT l.pseudo,u.nom,u.prenom,l.mail,u.adresse,u.ville,u.codepostal,u.telephone,u.fax,u.idpays_pays,l.actif,t.idtypeentreprise, t.libelletypeentreprise, s.idsecteuractivite, s.libellesecteuractivite,
                            q.libellequalitedemandeurindust,u.idqualitedemandeurindust_qualitedemandeurindust,u.idtypeutilisateur_typeutilisateur,u.nomresponsable,u.mailresponsable
        FROM utilisateur u,loginpassword l,qualitedemandeurindust q,intervient i,appartient a,secteuractivite s,typeentreprise t
        WHERE l.idlogin = u.idlogin_loginpassword AND q.idqualitedemandeurindust = u.idqualitedemandeurindust_qualitedemandeurindust AND
        i.idutilisateur_utilisateur = u.idutilisateur AND a.idutilisateur_utilisateur = u.idutilisateur AND s.idsecteuractivite = i.idsecteuractivite_secteuractivite
	AND t.idtypeentreprise = a.idtypeentreprise_typeentreprise and idutilisateur=?", array($iduser));
                for ($i = 0; $i < count($rowindust); $i++) {
                    $nomuserindust = $rowindust[$i]['nom'];
                    $prenomuserindust = $rowindust[$i]['prenom'];
                    $pseudoindust = $rowindust[$i]['pseudo'];
                    $adresse = $rowindust[$i]['adresse'];
                    $mail = $rowindust[$i]['mail'];
                    $codepostal =  $rowindust[$i]['codepostal'];
                    $libellepaysindust = $manager->getSingle2("select nompays from pays where idpays=?", $rowindust[$i]['idpays_pays']);
                    $ville=  $rowindust[$i]['ville'];
                    $tel=  $rowindust[$i]['telephone'];
                    $fax =  $rowindust[$i]['fax'];
                    $libellequalitedemandeurindust = $rowindust[$i]['libellequalitedemandeurindust'];
                    $idqualitedemandeurindust = $rowindust[$i]['idqualitedemandeurindust_qualitedemandeurindust'];
                    $libelletypeentreprise = $rowindust[$i]['libelletypeentreprise'];
                    $libellesecteuractivite = $rowindust[$i]['libellesecteuractivite'];
                    $idsecteuractivite = $rowindust[$i]['idsecteuractivite'];
                    $iddroit = $rowindust[$i]['idtypeutilisateur_typeutilisateur'];
                    $actif = $rowindust[$i]['actif'];
                    $nomresponsable = $rowindust[$i]['nomresponsable'];
                    $mailresponsable = $rowindust[$i]['mailresponsable'];
                    if (empty($actif)) {
                        $valeuractif = TXT_NONACTIF;
                    } else {
                        $valeuractif = TXT_ACTIF;
                    }
                }echo '<pre>';print_r($rowindust);die;
                ?>
                <fieldset id="compteuserindust" style="border-color: #5D8BA2;width: 1008px;padding-bottom:10px;padding-top:8px;font-size:1.2em" >
                    <legend><?php echo TXT_COMPTE; ?></legend>
                    <?php
                    if (isset($_GET['update']) && $_GET['update'] == 'ok') {
                        echo '<table style="width:500px" align:center><tr><td  align=center><div  style="height:20px "><b>' . TXT_COMPTEMAJ . '</b></div></td><tr></table>';
                    }
                    ?>
                    <div id="compteutilisateurindust" >
                        <table>
                            <tr>
                                <td valign="top" style="text-align: left;width: 200px;"><?php echo TXT_PSEUDO; ?></td><td style="width: 110px"></td>
                                <td>
                                    <input id="pseudoindust" style="width: 318px;margin-left: 50px" type="text" name="pseudo" data-dojo-type="dijit/form/ValidationTextBox" value="<?php echo $pseudoindust ?>"  />
                                </td>
                            </tr>
                            <tr>
                                <td valign="top" style="text-align: left;width: 200px;"><?php echo TXT_MAIL; ?></td><td style="width: 110px"></td>
                                <td>
                                    <input id="mail" style="width: 318px;margin-left: 50px" type="text" name="mail" data-dojo-type="dijit/form/ValidationTextBox" value="<?php echo $mail ?>" 
                                     data-dojo-type="dijit/form/ValidationTextBox" regExpGen="dojox.validate.regexp.emailAddress" required="required"
                               invalidMessage="Email non valide." autocomplete="on" onfocus="enabledmodif()"/>
                                </td>
                            </tr>
                            <tr>
                                <td valign="top"><?php echo TXT_NOM; ?> </td><td></td>
                                <td>
                                    <input type="text" required="required" style="width: 318px;margin-left: 50px" name="nomuser" id="nomuserindust" data-dojo-type="dijit/form/ValidationTextBox" value="<?php echo $nomuserindust; ?>"   />
                                </td>
                            </tr>
                            <tr>
                                <td valign="top"><?php echo TXT_PRENOM; ?></td><td></td>
                                <td>
                                    <input type="text" required="required" style="width: 318px;margin-left: 50px" name="prenomuser" data-dojo-type="dijit/form/ValidationTextBox" id="prenomuserindust" value="<?php echo $prenomuserindust; ?>"  />
                                </td>
                            </tr>
                            
                             <tr>
                                    <td valign="top"><?php echo TXT_ADRESSE; ?></td><td></td>
                                    <td>
                                        <input type="text" style="width: 318px;margin-left: 50px"  required="required" name="adresseuser" data-dojo-type="dijit/form/ValidationTextBox" id="adresseuser" 
                                               value="<?php echo removeDoubleQuote($adresse); ?>"  onfocus="enabledmodif()" >
                                    </td>
                                </tr>
                            <tr>
                                <td valign="top"><?php echo TXT_CP; ?></td><td></td>                                
                                <td>
                                    <input style="width: 318px;margin-left: 50px"  type="text" required="required" name="cp" id="cp"  placeholder="" data-dojo-type="dijit/form/ValidationTextBox"
                                           value="<?php echo $codepostal;?>"  onfocus="enabledmodif()" >
                                </td>
                            </tr>
                             <tr>
                                <td valign="top"><?php echo TXT_VILLE; ?></td><td></td>                                
                                <td>
                                    <input style="width: 318px;margin-left: 50px"  type="text" required="required" name="ville" id="ville"  placeholder="" data-dojo-type="dijit/form/ValidationTextBox"
                                           value="<?php echo $ville;?>"  onfocus="enabledmodif()" >
                                </td>
                            </tr>
                            
                            <tr>
                                <th style="text-align: left"><?php echo TXT_PAYS; ?></th><td></td>
                                <td>
                                    <?php $rowpays = $manager->getList('SELECT idpays,nompays FROM pays order by idpays asc;'); ?>
                                    <select name="pays" id="nompays" data-dojo-type="dijit/form/Select" style="width: 318px;margin-left: 50px">
                                                <?php
                                                
                                                for ($i = 0; $i < count($rowpays); $i++) {
                                                    if ($libellepaysindust == $rowpays[$i]['nompays']) {
                                                        echo '<option value="' .$rowpays[$i]['idpays'] . '"  selected="selected" >' . $libellepaysindust . '</option>';
                                                    } else {
                                                        echo '<option value="' .$rowpays[$i]['idpays'] . '">' . $rowpays[$i]['nompays'] . '</option>';
                                                    }
                                                }
                                                ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td valign="top"><?php echo TXT_TELEPHONE; ?></td><td></td>
                                <td>
                                    <input type="text" style="width: 318px;margin-left: 50px" maxlength="20"  required="required" name="teluser" data-dojo-type="dijit/form/ValidationTextBox" id="teluser" 
                                           value="<?php echo removeDoubleQuote($tel); ?>"   />
                                </td>
                            </tr>
                            <tr>
                                <td valign="top"><?php echo TXT_FAX; ?></td><td></td>
                                <td>
                                    <input type="text" style="width: 318px;margin-left: 50px"  maxlength="20"  name="faxuser" data-dojo-type="dijit/form/ValidationTextBox" id="faxuser" 
                                           value="<?php echo removeDoubleQuote($fax); ?>"   />
                                </td>
                            </tr>
                            
                            
                            <tr>
                                <th style="text-align: left"><?php echo TXT_QUALITEDEMANDEUR; ?></th><td></td>
                                <td>
                                    <?php 
                                    if($lang=='fr'){
                                        $rowqualite = $manager->getList2('SELECT idqualitedemandeurindust,libellequalitedemandeurindust FROM qualitedemandeurindust where idqualitedemandeurindust!=? order by idqualitedemandeurindust asc;',NAQAUALITEINDUST);
                                    }else{
                                         $rowqualite = $manager->getList2('SELECT idqualitedemandeurindust,libellequalitedemandeurindusten FROM qualitedemandeurindust where idqualitedemandeurindust!=? order by idqualitedemandeurindust asc;',NAQAUALITEINDUST);
                                    }                                    
                                    ?>
                                    <select name="qualitedemandeurindust" id="qualitedemandeurindust" data-dojo-type="dijit/form/Select" style="width: 318px;margin-left: 50px" 
                                            onChange="if (dijit.byId(this.id).get('value') === '<?php echo 'qa'.NONPERMANENTINDUST ?>') {
                                                        document.getElementById('trnomresponsableindust').style.display = 'table-row';
                                                        document.getElementById('trmailresponsableindust').style.display = 'table-row';
                                                    } else {
                                                        document.getElementById('trnomresponsableindust').style.display = 'none';
                                                        document.getElementById('trmailresponsableindust').style.display = 'none';
                                                    }
                                                    ;" >
                                                <?php
                                                
                                                for ($i = 0; $i < count($rowqualite); $i++) {
                                                    if ($idqualitedemandeurindust == $rowqualite[$i]['idqualitedemandeurindust']) {
                                                        echo '<option value="' . 'qa' . $rowqualite[$i]['idqualitedemandeurindust'] . '"  selected="selected" >' . $libellequalitedemandeurindust . '</option>';
                                                    } else {
                                                        if($lang=='fr'){
                                                            echo '<option value="' . 'qa' . $rowqualite[$i]['idqualitedemandeurindust'] . '">' . $rowqualite[$i]['libellequalitedemandeurindust'] . '</option>';
                                                        }else{
                                                            echo '<option value="' . 'qa' . $rowqualite[$i]['idqualitedemandeurindust'] . '">' . $rowqualite[$i]['libellequalitedemandeurindusten'] . '</option>';
                                                        }
                                                    }
                                                }
                                                ?>
                                    </select>
                                </td>
                            </tr>
                            <!-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
                            
                            -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->
                            <?php
                            if (!empty($nomresponsable)) {
                                $nomResponsable = removeDoubleQuote($nomresponsable);
                                ?>                                
                                <tr  id="trnomresponsableindust">
                                        <td valign="top"><?php echo TXT_NOMRESPONSABLE; ?> </td><td></td>
                                        <td>
                                            <input type="text" required="required" style="width: 318px;margin-left: 50px"  name="nomresponsable" id="nomresponsable"  data-dojo-type="dijit/form/ValidationTextBox" autocomplete="on" value="<?php echo $nomResponsable; ?>"
                                                   data-dojo-props="regExp:'[a-zA-Z0-9$\\340\\047\\341\\342\\343\\344\\345\\346\\347\\350\\351\\352\\353\\354\\355\\356\\357\\360\\361\\362\\363\\364\\365\\366\\370\\371\\372\\373\\374\\375\\376\\377\\s\.\-]+'" />
                                        </td>
                                    </tr>
                                <tr   id="trmailresponsableindust">
                                        <td valign="top"><?php echo TXT_RESPMAILMAIL; ?> </td><td></td>
                                        <td>
                                             <input id="mailresponsable" type="text" name="mailresponsable" class="long" data-dojo-type="dijit/form/ValidationTextBox"   regExpGen="dojox.validate.regexp.emailAddress" required="required"
                                                   style="width: 318px;margin-left: 50px"  invalidMessage="<?php echo TXT_EMAILNONVALIDE ?>" autocomplete="on"  value="<?php echo $mailresponsable; ?>"/>
                                        </td>
                                    </tr>                                        
                            <?php }else{?>
                                <tr  id="trnomresponsableindust" style="display: none">
                                    <td valign="top"><?php echo TXT_NOMRESPONSABLE; ?> </td><td></td>
                                    <td>
                                        <input type="text" required="required" style="width: 318px;margin-left: 50px"  name="nomresponsable" id="nomresponsable"  data-dojo-type="dijit/form/ValidationTextBox" autocomplete="on" 
                                               data-dojo-props="regExp:'[a-zA-Z0-9$\\340\\047\\341\\342\\343\\344\\345\\346\\347\\350\\351\\352\\353\\354\\355\\356\\357\\360\\361\\362\\363\\364\\365\\366\\370\\371\\372\\373\\374\\375\\376\\377\\s\.\-]+'" />
                                    </td>
                                </tr>
                                <tr   id="trmailresponsableindust" style="display: none">
                                        <td valign="top"><?php echo TXT_RESPMAILMAIL; ?> </td><td></td>
                                        <td>
                                             <input id="mailresponsable" type="text" name="mailresponsable1 class="long" data-dojo-type="dijit/form/ValidationTextBox"   regExpGen="dojox.validate.regexp.emailAddress" required="required"
                                                   style="width: 318px;margin-left: 50px"  invalidMessage="<?php echo TXT_EMAILNONVALIDE ?>" autocomplete="on"  
                                        </td>
                                    </tr>
                           <?php }?>
                            <tr>
                                <th style="text-align: left"><?php echo TXT_TYPEENTREPRISE; ?></th><td></td>
                                <td><?php $rowtypeentreprise = $manager->getList('SELECT libelletypeentreprise,idtypeentreprise,masquetypeentreprise FROM typeentreprise where masquetypeentreprise!=TRUE order by idtypeentreprise asc;'); ?>
                                    <select name="libelletypeentreprise" id="libelletypeentreprise" data-dojo-type="dijit/form/Select" style="width: 318px;margin-left: 50px" >
                                                <?php
                                                for ($i = 0; $i < count($rowtypeentreprise); $i++) {
                                                    if ($libelletypeentreprise == $rowtypeentreprise[$i]['libelletypeentreprise']) {
                                                        echo '<option value="' . 'di' . ($rowtypeentreprise[$i]['idtypeentreprise']) . '"  selected="selected" >' . $libelletypeentreprise . '</option>';
                                                    } else {
                                                        if($lang=='fr'){
                                                            echo '<option value="' . 'di' . ($rowtypeentreprise[$i]['idtypeentreprise']) . '">' . $rowtypeentreprise[$i]['libelletypeentreprise'] . '</option>';
                                                        }else{
                                                            echo '<option value="' . 'di' . ($rowtypeentreprise[$i]['idtypeentreprise']) . '">' . $rowtypeentreprise[$i]['libelletypeentrepriseen'] . '</option>';
                                                        }
                                                    }
                                                }
                                                ?>
                                    </select>
                                </td>
                            </tr>                                    
                             <tr>
                                <th style="text-align: left"><?php echo TXT_SECTEURACTIVITE; ?></th><td></td>
                                <td><?php $rowsecteuractivite = $manager->getList('SELECT idsecteuractivite,libellesecteuractivite FROM secteuractivite where masquesecteuractivite!=TRUE order by idsecteuractivite asc;'); ?>
                                    <select name="libellesecteuractivite" id="libellesecteuractivite" data-dojo-type="dijit/form/Select" style="width: 318px;margin-left: 50px" >
                                                <?php
                                                for ($i = 0; $i < count($rowsecteuractivite); $i++) {
                                                    if ($libellesecteuractivite == $rowsecteuractivite[$i]['libellesecteuractivite']) {
                                                        echo '<option value="' . 'di' . ($rowsecteuractivite[$i]['idsecteuractivite']) . '"  selected="selected" >' . $libellesecteuractivite . '</option>';
                                                    } else {
                                                        if($lang=='fr'){
                                                            echo '<option value="' . 'di' . ($rowsecteuractivite[$i]['idsecteuractivite']) . '">' . $rowsecteuractivite[$i]['libellesecteuractivite'] . '</option>';
                                                        }else{
                                                            echo '<option value="' . 'di' . ($rowsecteuractivite[$i]['idsecteuractivite']) . '">' . $rowsecteuractivite[$i]['libellesecteuractiviteen'] . '</option>';
                                                        }
                                                    }
                                                }
                                                ?>
                                    </select>
                                </td>
                            </tr>            
                            
                            <tr>
                            <th style="text-align: left"><?php echo TXT_STATUTCOMPTE; ?></th><td></td>
                            <td>
                                <select name="statutcompte" id="statutcompte" data-dojo-type="dijit/form/Select" style="width: 318px;margin-left: 50px"  >
                                    <?php
                                    foreach ($statutCompte as $key => $value) {
                                        if ($valeuractif == $value) {
                                            echo '<option value="' . $value . '" selected="selected">' . $value . '</option>';
                                        } else {
                                            echo "<option value='" . $value . "'>" . $value . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </td>
                            </tr>
                            <tr>
                                <?php $libelledroit = $manager->getSingle2("select libelletype FROM typeutilisateur where idtypeutilisateur=?", $iddroit); ?>
                                <th style="text-align: left"><?php echo TXT_DROITACTUEL; ?></th><td></td>
                                <td>
                                    <input name ="rechercheUser" type="text" data-dojo-type="dijit/form/ValidationTextBox" disabled="disabled" value="<?php echo $libelledroit; ?>"   style="width: 318px;margin-left: 50px"  >
                                </td>
                            </tr>
                        </table>
                    </div>
                    <button id="progButtonNodeindust" data-dojo-type="dijit/form/Button" type="submit" name="submitButton" ></button>
                </fieldset> <?php include 'html/footer.html'; ?>
            </form>
        </div><!-- navigation -->
    </div>
    <script>
        require(["dijit/form/Button", "dojo/dom", "dojo/domReady!"], function(Button, dom) {
            var myButton = new Button({
                label: "<?php echo TXT_MAJ; ?>",
                onClick: function() {                    
                    if (!empty(dijit.byId('pseudoindust'))) {
                        var pseudoindust = dijit.byId('pseudoindust').value;
                    } else {
                        pseudoindust = "";
                    }
                     if (!empty(dijit.byId('mail'))) {
                        var mail = dijit.byId('mail').value;
                    } 
                    if (!empty(dijit.byId('nomuserindust'))) {
                        var nomuserindust = dijit.byId('nomuserindust').value;
                    } else {
                        nomuserindust = "";
                    }
                    if (!empty(dijit.byId('prenomuserindust'))) {
                        var prenomuserindust = dijit.byId('prenomuserindust').value;
                    } else {
                        prenomuserindust = "";
                    }
                    if (!empty(dijit.byId('adresseuser'))) {
                        var adresseuser = dijit.byId('adresseuser').value;
                    } else {
                        adresseuser = "";
                    }
                    if (!empty(dijit.byId('cp'))) {
                        var codepostal = dijit.byId('cp').value;
                    } else {
                        codepostal = "";
                    }
                    if (!empty(dijit.byId('ville'))) {
                        var ville = dijit.byId('ville').value;
                    } else {
                        ville = "";
                    }
                      if (!empty(dijit.byId('nompays'))) {
                        var pays = dijit.byId('nompays').value;
                    } else {
                        pays = "";
                    }
                    if (!empty(dijit.byId('teluser'))) {
                        var teluser = dijit.byId('teluser').value;
                    } else {
                        teluser = "";
                    }
                    if (!empty(dijit.byId('faxuser'))) {
                        var faxuser = dijit.byId('faxuser').value;
                    } else {
                        faxuser = "";
                    }
                    
                    if (!empty(dijit.byId('qualitedemandeurindust'))){
                        var idqualitedemandeurindust = dijit.byId('qualitedemandeurindust').value;
                    }
                    
                    if (!empty(dijit.byId('libelletypeentreprise'))) {
                        var idtypeentreprise = dijit.byId('libelletypeentreprise').value;
                    } else {
                        idtypeentreprise = "";
                    }
                    if (!empty(dijit.byId('libellesecteuractivite'))) {
                        var idsecteuractivite = dijit.byId('libellesecteuractivite').value;
                    } else {
                        idsecteuractivite = "";
                    }
                    if (!empty(dijit.byId('statutcompte'))) {
                        var statutcompte = dijit.byId('statutcompte').value;
                    } else {
                        statutcompte = "";
                    }
                    if (!empty(dijit.byId('libelledroit'))) {
                        var role = dijit.byId('libelledroit').value;
                    } else {
                        role = "";
                    }
                    if (!empty(document.getElementById('nomresponsable'))) {
                        var nomresponsable = document.getElementById('nomresponsable').value;
                    } else {
                        nomresponsable = "";
                    }
                    if (!empty(document.getElementById('mailresponsable'))) {
                        var mailresponsable = document.getElementById('mailresponsable').value;
                    } else {
                        mailresponsable = "";
                    }
                    window.location.replace("<?php echo '/'.REPERTOIRE; ?>/modifBase/majgestioncompteindust.php?iduser=" +<?php echo $iduser; ?> +"&pseudo="+pseudoindust+"&mail="+mail+"&nomuser="
                            +nomuserindust+"&prenomuser="+prenomuserindust+"&adresseuser="+adresseuser+"&pays="+pays+"&teluser="+teluser+"&faxuser="+faxuser+"&codepostal="+codepostal+"&ville="+ville+
                            "&idqualitedemandeurindust="+idqualitedemandeurindust+"&nomresponsable=" + nomresponsable + "&mailresponsable=" + mailresponsable + "&role=" + role + "&idtypeentreprise=" 
                            + idtypeentreprise + "&idsecteuractivite=" + idsecteuractivite + "&statutcompte=" + statutcompte + "&page_precedente=<?php echo basename(__FILE__); ?>");
                }
            }, "progButtonNodeindust");
        });
    </script>

<?php } ?>
<?php BD::deconnecter(); ?>

</body>
</html>