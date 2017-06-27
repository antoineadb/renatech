<?php
session_start();
include('decide-lang.php');
include_once 'outils/constantes.php';
include_once 'outils/toolBox.php';
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('location: /' . REPERTOIRE . '/erreurlogin.php');
}
?>
<div id="global">
    <?php
    include_once 'class/Manager.php';
    $db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
    $manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
    $statutCompte = array(TXT_ACTIF, TXT_NONACTIF);
    include 'html/header.html';
    include 'html/entete.html';
    ?>
    <script src="js/controle.js"></script>
    <form  method="post" action="<?php echo '/' . REPERTOIRE; ?>/ctrlcompte.php?lang=<?php echo $lang ?>" id='gestioncompte' name='gestioncompteadminnational' data-dojo-type="dijit/form/Form"   >
        <script type="dojo/method" data-dojo-event="onSubmit">
            if(this.validate()){
            return true;
            }else{
            alert('<?php echo TXT_MESSAGEERREURCONTACT; ?>');
            return false;
            exit();
            }
        </script>            
        <fieldset id="ident" style="border-color: #5D8BA2;width: 1005px;margin-top: 80px;height:80px;padding-top:25px"  >
            <legend style="font-size:1.2em"><?php echo TXT_RECHERCHEUSER; ?></legend>
            <table>
                <tr>
                    <td valign="top" >
                        <input type="text" style="width: 250px;border-radius: 8px;padding: 3px;font-size:1.2em" placeholder="<?php echo TXT_NOMUTILISATEUR; ?>" name="nom" id="nom" data-dojo-type="dijit/form/ValidationTextBox" autocomplete="on"/>
                    </td>
                    <td valign="top">
                        <input type="text" style="width: 250px;border-radius: 8px;padding: 3px;font-size:1.2em;margin-left: 25px;" placeholder="<?php echo TXT_PRENOMUTILISATEUR; ?>" name="prenom" id="prenom" data-dojo-type="dijit/form/ValidationTextBox" autocomplete="on"/>
                    </td>

                    <td valign="bottom" >
                        <input name ="rechercheUserAdminnationnal" type="submit" ondragover="tous"  value="<?php echo TXT_BOUTONRECHERCHE; ?>"  class="maj"
                               onclick="if (document.getElementById('nom').value === '' && document.getElementById('prenom').value === '') {
                                           document.getElementById('nom').value = '*';
                                       }" style="margin-left: 10px"/>
                    </td>
                </tr>
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
            <fieldset id='compte' style="border-color: #5D8BA2;width: 1035px;padding:5px; margin-top: 15px" >
                <legend><?php echo TXT_CHOIXUSER; ?></legend>
                <?php
                $_SESSION['page_precedente'] = basename(__FILE__);
                include 'findUsercompteadminnationnal.php';
                ?>
            </fieldset><?php
            include 'html/footer.html';
        }
//-------------------------------------------------------------------------------------------------------------------------------------------------
//																																											COMPTE ACADEMIQUE
//-------------------------------------------------------------------------------------------------------------------------------------------------
        if (!empty($_GET['iduser']) && !empty($_GET['idqualiteaca']) && empty($_GET['idqualiteindust'])) {
            $iduser = $_GET['iduser'];
            $row = $manager->getListbyArray("
SELECT u.nom,u.prenom,l.pseudo,l.actif,qa.libellequalitedemandeuraca,n.libelleemployeur,n.idemployeur,an.libelleautrenomemployeur,at.libelleautrestutelle,t.idtutelle,t.libelletutelle,d.libellediscipline,d.iddiscipline,ad.libelleautrediscipline,
at.idautrestutelle,an.idautrenomemployeur,ad.idautrediscipline,u.nomresponsable,u.mailresponsable,u.idtypeutilisateur_typeutilisateur
FROM utilisateur u,loginpassword l,qualitedemandeuraca qa,nomemployeur n,autrenomemployeur an,autrestutelle at,tutelle t,autrecodeunite ac,disciplinescientifique d,autredisciplinescientifique ad
WHERE l.idlogin = u.idlogin_loginpassword AND qa.idqualitedemandeuraca = u.idqualitedemandeuraca_qualitedemandeuraca AND
n.idemployeur = u.idemployeur_nomemployeur AND an.idautrenomemployeur = u.idautrenomemployeur_autrenomemployeur AND 
at.idautrestutelle = u.idautrestutelle_autrestutelle AND  ad.idautrediscipline = u.idautrediscipline_autredisciplinescientifique and
t.idtutelle = u.idtutelle_tutelle AND ac.idautrecodeunite = u.idautrecodeunite_autrecodeunite and 
d.iddiscipline = u.iddiscipline_disciplinescientifique and idutilisateur=?", array($iduser));
            for ($i = 0; $i < count($iduser); $i++) {
                $nom = $row[$i]['nom'];
                $prenom = $row[$i]['prenom'];
                $pseudo = $row[$i]['pseudo'];
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
            }

            if (empty($actif)) {
                $valeuractif = TXT_NONACTTXTIF;
            } else {
                $valeuractif = TXT_ACTIF;
            }
            ?><br>
    <?php if (isset($_GET['msgerr'])) { ?>
                <table>
                    <tr>
                        <td>
                            <fieldset id='droit' style="border-color: #5D8BA2;width:508px;padding-top: 10px;padding-left: 15px;">
                                <legend><?php echo TXT_ERR; ?></legend>
        <?php echo stripslashes($_GET['msgerr']); ?>
                            </fieldset>

                        </td>
                    </tr>
                </table><br>
                <?php } ?>
            <fieldset id="compteuser" style="border-color: #5D8BA2;width: 1006px;padding-bottom:10px;padding-top:8px" >
                <legend style="font-size: 1.2em"><?php echo TXT_COMPTE; ?></legend>
                <?php
                if (isset($_GET['update']) && $_GET['update'] == 'ok') {
                    echo '<table style="width:500px" align:center><tr><td  align=center><div  style="height:20px "><b>Compte mis à jour</b></div></td><tr></table>';
                }
                ?>
                <div id="compteutilisateuraca"  >
                    <table>
                        <tr>
                            <td valign="top" style="text-align: left;"><?php echo TXT_PSEUDO; ?></td><td style="width: 110px"></td>
                            <td >
                                <input  id="pseudo" style="font-size: 1.2em;width:318px;" type="text" name="pseudo" data-dojo-type="dijit/form/ValidationTextBox" value="<?php echo $pseudo ?>" disabled="disabled" />
                            </td>
                        </tr>
                        <tr>
                            <td valign="top" style="text-align: left;font-size: 1.2em"><?php echo TXT_NOM; ?> </td><td></td>
                            <td>
                                <input type="text" style="font-size: 1.2em;width:318px;" required="required" name="nomuser" id="nomuser" data-dojo-type="dijit/form/ValidationTextBox" value="<?php echo str_replace("''", "'", $nom); ?>"    disabled="disabled" />
                            </td>
                        </tr>
                        <tr>
                            <td valign="top" style="text-align: left;font-size: 1.2em"><?php echo TXT_PRENOM; ?></td><td></td>
                            <td>
                                <input type="text" style="font-size: 1.2em;width:318px;" required="required" name="prenomuser" data-dojo-type="dijit/form/ValidationTextBox" id="prenomuser" value="<?php echo str_replace("''", "'", $prenom); ?>"  disabled="disabled" />
                            </td>
                        </tr>
                        <tr>
                            <td valign="top" style="text-align: left;font-size: 1.2em"><?php echo TXT_QUALITEDEMANDEUR; ?> </td><td></td>
                            <td>
                                <input type="text" style="font-size: 1.2em;width:318px;" required="required" name="libellequalitedemandeuraca" id="libellequalitedemandeuraca" data-dojo-type="dijit/form/ValidationTextBox" value="<?php echo $libellequalitedemandeuraca; ?>"    disabled="disabled" />
                            </td>
                        </tr>

    <?php if (!empty($nomresponsable)) { ?>
                            <tr>
                                <td valign="top" style="text-align: left;font-size: 1.2em"><?php echo TXT_NOMRESPONSABLE; ?> </td><td></td>
                                <td>
                                    <input type="text" style="font-size: 1.2em;width:318px;" required="required" name="nomresponsable" id="nomresponsable" data-dojo-type="dijit/form/ValidationTextBox" value="<?php echo str_replace("''", "'", $nomresponsable) ?>"    />
                                </td>
                            </tr>
                            <tr>
                                <td valign="top" style="text-align: left;font-size: 1.2em"><?php echo TXT_RESPMAILMAIL; ?> </td><td></td>
                                <td>
                                    <input type="text" style="font-size: 1.2em;width:318px;" required="required" name="mailresponsable" id="mailresponsable" data-dojo-type="dijit/form/ValidationTextBox" value="<?php echo $mailresponsable; ?>"    />
                                </td>
                            </tr>
    <?php } ?>

                        <tr>
                            <th style="text-align: left;font-size: 1.2em"><?php echo TXT_NOMEMPLOYEUR; ?></th><td></td>
                            <td>
                                        <?php $rowlibelleemployeur = $manager->getList('SELECT idemployeur,libelleemployeur FROM nomemployeur order by idemployeur asc;'); ?>
                                <select name="libelleemployeur" id="libelleemployeur" data-dojo-type="dijit/form/Select" style="width: 220px"
                                        onChange="if (dijit.byId(this.id).get('displayedValue') === 'Autres') {
                                                    document.getElementById('trAutrenom').style.display = ''
                                                } else {
                                                    document.getElementById('trAutrenom').style.display = 'none'
                                                }
                                                ;" >

                                    <?php
                                    for ($i = 0; $i < count($rowlibelleemployeur); $i++) {
                                        if ($libelleemployeur == $rowlibelleemployeur[$i]['libelleemployeur']) {
                                            echo '<option style="text-align: left;font-size:1.2em" value="' . 'ne' . ($rowlibelleemployeur[$i]['idemployeur']) . '"  selected="selected" >' . $libelleemployeur . '</option>';
                                        } else {
                                            echo '<option style="text-align: left;font-size:1.2em" value="' . 'ne' . ($rowlibelleemployeur[$i]['idemployeur']) . '">' . $rowlibelleemployeur[$i]['libelleemployeur'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>

                        <tr  style="display:none"  id="trAutrenom">
                            <th style="text-align: left;font-size: 1.2em"><?php echo TXT_AUTREEMPLOYEUR; ?></th><td></td>
                            <td>
                                <input type="text" name="autreEmployeur" id="autreEmployeur"  name="autreEmployeur" style="font-size: 1.2em;width:318px;" data-dojo-type="dijit/form/ValidationTextBox" value="<?php
                                if ($libelleautrenomemployeur != 'n/a') {
                                    echo stripslashes(str_replace("''", "'", $libelleautrenomemployeur));
                                }
                                ?>"/>

                            </td>
                        </tr>

                        <tr>
                            <th style="text-align: left;font-size: 1.2em"><?php echo TXT_TUTELLE; ?></th><td></td>
                            <td>
                                        <?php $rowtutelle = $manager->getList('SELECT idtutelle,libelletutelle FROM tutelle order by idtutelle asc;'); ?>
                                <select name="libelletutelle" id="libelletutelle" data-dojo-type="dijit/form/Select" style="width: 220px"
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
                        <tr  style="display:none ;text-align: left;font-size: 1.2em" id="trAutretutelle">
                            <th style="text-align: left"><?php echo TXT_AUTRETUTELLE; ?></th><td></td>
                            <td>
                                <input type="text" name="autreTutelle" id="autreTutelle" style="font-size: 1.2em;width:318px;"  name="autreTutelle" data-dojo-type="dijit/form/ValidationTextBox" value="<?php
                                if ($libelleautrestutelle != 'n/a') {
                                    echo stripslashes(str_replace("''", "'", ($libelleautrestutelle)));
                                }
                                ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <th style="text-align: left;font-size: 1.2em"><?php echo TXT_DISCIPLINESACTUEL; ?></th><td></td>
                            <td>
                                <input type="text"  name="disciplineactuel"  style="font-size: 1.2em;width:318px;" id="disciplineactuel" data-dojo-type="dijit/form/ValidationTextBox" value="<?php
                                if ($libellediscipline != 'Autres') {
                                    echo stripslashes(str_replace("''", "'", $libellediscipline));
                                } else {
                                    echo stripslashes(str_replace("''", "'", $libelleautrediscipline));
                                }
                                ?>"    disabled="disabled" />
                            </td>
                        </tr>
                        <tr>
                            <th style="text-align: left;font-size: 1.2em"><?php echo TXT_NEWDISCIPLINE; ?></th><td></td>
                            <td>
                                        <?php $rowDiscipline = $manager->getList('SELECT iddiscipline,libellediscipline FROM disciplinescientifique order by iddiscipline asc;'); ?>
                                <select name="libellediscipline" id="libellediscipline" data-dojo-type="dijit/form/Select" style="width: 220px;font-size:1.2em"
                                        onChange="if (dijit.byId(this.id).get('displayedValue') === 'Autres') {
                                                    document.getElementById('trDiscipline').style.display = ''
                                                } else {
                                                    document.getElementById('trDiscipline').style.display = 'none'
                                                }
                                                ;" >
                                            <?php
                                            echo '<option value="' . 'te0' . '" selected="selected" >' . TXT_SELECTDISCIPLINE . '</option>';
                                            for ($i = 0; $i < count($rowDiscipline); $i++) {
                                                echo '<option value="' . 'di' . ($rowDiscipline[$i]['iddiscipline']) . '">' . stripslashes(str_replace("''", "'", $rowDiscipline[$i]['libellediscipline'])) . '</option>';
                                            }
                                            ?>
                                </select>
                            </td>
                        </tr>
                        <tr  style="display:none "  id="trDiscipline">
                            <th style="text-align: left;font-size:1.2em"><?php echo TXT_AUTREDISCIPLINE; ?></th><td></td>
                            <td>
                                <input type="text" name="autreEmployeur" id="autreDiscipline"  style="font-size: 1.2em;width:318px;" name="autreDiscipline" data-dojo-type="dijit/form/ValidationTextBox" value="<?php
                                if ($libelleautrediscipline != 'n/a') {
                                    echo stripslashes(str_replace("''", "'", ($libelleautrediscipline)));
                                }
                                ?>"/>
                            </td>
                        </tr>
                        <!--  ACRONYME DU LABORATOIRE -->
                        <?php
                        $acronymelaboratoire = $manager->getSingle2("select acronymelaboratoire from utilisateur where idutilisateur =?", $_GET['iduser']);
                        ?>
                        <tr>
                            <th style="text-align: left;font-size:1.2em"><?php echo TXT_NOMLABO; ?></th><td></td>
                            <td>
                                <input type="text"  name="acronymelaboratoire"  style="font-size: 1.2em;width:318px;" id="acronymelaboratoire"  data-dojo-type="dijit/form/ValidationTextBox" autocomplete="on" value="<?php echo stripslashes(str_replace("''", "'", $acronymelaboratoire)); ?>"
                                       placeholder="<?php echo TXT_NOMLABO; ?>"     data-dojo-props="regExp:'[a-zA-Z0-9()$\\340\\047\\341\\342\\343\\344\\345\\346\\347\\350\\351\\352\\353\\354\\355\\356\\357\\360\\361\\362\\363\\364\\365\\366\\370\\371\\372\\373\\374\\375\\376\\377\\s\.\-]+'" />

                            </td>
                        </tr>

                        <tr>
                            <th style="text-align: left;font-size:1.2em"><?php echo TXT_STATUTCOMPTE; ?></th><td></td>
                            <td>

                                <select name="statutcompte"  style="font-size: 1.2em;width:318px;" id="statutcompte" data-dojo-type="dijit/form/Select" style="width: 220px"  >
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

                        <?php
                        $rowdroit = $manager->getList("SELECT libelletype,idtypeutilisateur	 FROM typeutilisateur where idtypeutilisateur =" . ADMINLOCAL . " or idtypeutilisateur=" . UTILISATEUR . "");
                        $libelledroit = $manager->getSingle2("select libelletype FROM typeutilisateur where idtypeutilisateur=?", $iddroit);
                        ?>
                        <tr>
                            <th style="text-align: left;font-size: 1.2em"><?php echo TXT_NOUVEAUDROIT; ?></th><td></td>
                            <td>
                                <select name="libelledroit" id="libelledroit" data-dojo-type="dijit/form/Select" style="width: 220px;font-size:1.2em"
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
                            <th style="text-align: left;font-size:1.2em"><?php echo TXT_CENTRALE; ?></th><td></td>
    <?php if (!empty($libellecentrale)) { ?>
                                <td>
                                    <select name="centrale" id="centrale"   data-dojo-type="dijit/form/Select" style="width: 220px" data-dojo-props="value:'<?php echo $libellecentrale; ?>',placeHolder:'<?php echo TXT_SELECTCENTRALE; ?>'"  >
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
                                    <select name="centrale" required="true"  style="font-size: 1.2em" id="centrale" data-dojo-type="dijit/form/FilteringSelect" style="width: 220px" data-dojo-props="value:'',placeHolder:'<?php echo TXT_SELECTCENTRALE; ?>'"  >
                                        <?php
                                        for ($i = 0; $i < count($rowcentrale2); $i++) {
                                            echo '<option value="' . 'ce' . ($rowcentrale2[$i]['idcentrale']) . '">' . $rowcentrale2[$i]['libellecentrale'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </td>
    <?php } ?>
                        </tr>
                    </table>
                    <button id="progButtonNode" data-dojo-type="dijit/form/Button" type="submit" name="submitButtonThree" value="Submit"></button>
                </div>
            </fieldset>
            <?php
            include 'html/footer.html';
//-------------------------------------------------------------------------------------------------------------------------------------------------
//																																											COMPTE INDUSTRIEL
//-------------------------------------------------------------------------------------------------------------------------------------------------
        } elseif (!empty($_GET['iduser']) && empty($_GET['idqualiteaca']) && !empty($_GET['idqualiteindust'])) {
            $iduser = $_GET['iduser'];

            $rowindust = $manager->getListbyArray(
                    "SELECT l.pseudo,u.nom,u.prenom,l.actif,t.idtypeentreprise, t.libelletypeentreprise, s.idsecteuractivite, s.libellesecteuractivite,q.libellequalitedemandeurindust,u.idtypeutilisateur_typeutilisateur
        FROM utilisateur u,loginpassword l,qualitedemandeurindust q,intervient i,appartient a,secteuractivite s,typeentreprise t
        WHERE l.idlogin = u.idlogin_loginpassword AND q.idqualitedemandeurindust = u.idqualitedemandeurindust_qualitedemandeurindust AND
        i.idutilisateur_utilisateur = u.idutilisateur AND a.idutilisateur_utilisateur = u.idutilisateur AND s.idsecteuractivite = i.idsecteuractivite_secteuractivite
	AND t.idtypeentreprise = a.idtypeentreprise_typeentreprise and idutilisateur=?", array($iduser));

            for ($i = 0; $i < count($rowindust); $i++) {

                $nomuserindust = $rowindust[$i]['nom'];
                $prenomuserindust = $rowindust[$i]['prenom'];
                $pseudoindust = $rowindust[$i]['pseudo'];
                $libellequalitedemandeurindust = $rowindust[$i]['libellequalitedemandeurindust'];
                $libelletypeentreprise = $rowindust[$i]['libelletypeentreprise'];
                $libellesecteuractivite = $rowindust[$i]['libellesecteuractivite'];
                //$idsecteuractivite = $rowindust[$i]['idsecteuractivite'];
                $iddroit = $rowindust[$i]['idtypeutilisateur_typeutilisateur'];
                $actif = $rowindust[$i]['actif'];
                if (empty($actif)) {
                    $valeuractif = TXT_NONACTIF;
                } else {
                    $valeuractif = TXT_ACTIF;
                }
            }
            ?>
            <fieldset id="compteuserindust" style="border-color: #5D8BA2;width: 505px;padding-bottom:10px;padding-top:8px" >
                <legend><?php echo 'Compte'; ?></legend>
                <?php
                if (isset($_GET['update']) && $_GET['update'] == 'ok') {
                    echo '<table style="width:500px" align:center><tr><td  align=center><div  style="height:20px "><b>Compte mis à jour</b></div></td><tr></table>';
                }
                ?>
                <div id="compteutilisateurindust" >
                    <table>
                        <tr>
                            <td valign="top" style="text-align: left"><?php echo TXT_PSEUDO; ?></td><td style="width: 60px"></td>
                            <td>
                                <input id="pseudoindust" type="text" name="pseudoindust" data-dojo-type="dijit/form/ValidationTextBox" value="<?php echo $pseudoindust ?>" disabled="disabled" />
                            </td>
                        </tr>
                        <tr>
                            <td valign="top"><?php echo TXT_NOM; ?> </td><td></td>
                            <td>
                                <input type="text" required="required" name="nomuserindust" id="nomuserindust" data-dojo-type="dijit/form/ValidationTextBox" value="<?php echo $nomuserindust; ?>"    disabled="disabled" />
                            </td>
                        </tr>
                        <tr>
                            <td valign="top"><?php echo TXT_PRENOM; ?></td><td></td>
                            <td>
                                <input type="text" required="required" name="prenomuserindust" data-dojo-type="dijit/form/ValidationTextBox" id="prenomuserindust" value="<?php echo $prenomuserindust; ?>"  disabled="disabled" />
                            </td>
                        </tr>
                        <tr>
                            <td valign="top"><?php echo TXT_QUALITEDEMANDEUR; ?> </td><td></td>
                            <td>
                                <input type="text" required="required" name="libellequalitedemandeurindust" id="libellequalitedemandeurindust" data-dojo-type="dijit/form/ValidationTextBox" value="<?php echo $libellequalitedemandeurindust; ?>"    disabled="disabled" />
                            </td>
                        </tr>
    <?php ?>
                        <tr>
                            <th style="text-align: left"><?php echo TXT_TYPEENTREPRISEACTUEL; ?></th><td></td>
                            <td>
                                <input type="text"  name="typeentrepriseindust" id="typeentrepriseindust" data-dojo-type="dijit/form/ValidationTextBox" value="<?php echo $libelletypeentreprise; ?>"    disabled="disabled" />
                            </td>
                        </tr>
                        <tr>
                            <th style="text-align: left"><?php echo TXT_NEWTYPEENTREPRISE; ?></th><td></td>
                            <td>
                                    <?php $rowtypeentreprise = $manager->getList('SELECT libelletypeentreprise,idtypeentreprise,masquetypeentreprise FROM typeentreprise where masquetypeentreprise!=TRUE order by idtypeentreprise asc;'); ?>

                                <select name="libelletypeentreprise" id="libelletypeentreprise" data-dojo-type="dijit/form/Select" style="width: 220px" >
                                    <?php
                                    echo '<option value="' . 'te0' . '" selected="selected" >' . TXT_SELECTTYPEENTREPRISE . '</option>';
                                    for ($i = 0; $i < count($rowtypeentreprise); $i++) {
                                        echo '<option value="' . 'te' . ($rowtypeentreprise[$i]['idtypeentreprise']) . '">' . $rowtypeentreprise[$i]['libelletypeentreprise'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th style="text-align: left"><?php echo TXT_SECTEURACTIVITEACTUEL; ?></th><td></td>
                            <td>
                                <input type="text"  name="secteuractiviteindust" id="secteuractivite" data-dojo-type="dijit/form/ValidationTextBox" value="<?php echo $libellesecteuractivite; ?>"    disabled="disabled" />
                            </td>
                        </tr>
                        <tr>
                            <th style="text-align: left"><?php echo TXT_NEWSECTEURACTIVITE; ?></th><td></td>
                            <td>
                                    <?php $rowsecteuractivite = $manager->getList('SELECT idsecteuractivite,libellesecteuractivite FROM secteuractivite where masquesecteuractivite!=TRUE order by idsecteuractivite asc;'); ?>
                                <select name="libellesecteuractivite" id="libellesecteuractivite" data-dojo-type="dijit/form/Select" style="width: 220px"  >
                                    <?php
                                    echo '<option value="' . 'se0' . '" selected="selected" >' . TXT_SELECTSECTEURACTIVITE . '</option>';
                                    for ($i = 0; $i < count($rowsecteuractivite); $i++) {
                                        echo '<option value="' . 'se' . ($rowsecteuractivite[$i]['idsecteuractivite']) . '">' . $rowsecteuractivite[$i]['libellesecteuractivite'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th style="text-align: left"><?php echo TXT_STATUTCOMPTE; ?></th><td></td>
                            <td>

                                <select name="statutcompte" id="statutcompte" data-dojo-type="dijit/form/Select" style="width: 220px"  >
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
    <?php $libelledroit = $manager->getSingle2("select libelletype FROM typeutilisateur where idtypeutilisateur=?", $iddroit); ?>
                        <th style="text-align: left"><?php echo TXT_DROITACTUEL; ?></th><td></td>
                        <td>
                            <input name ="rechercheUseradminnationnal" type="text" data-dojo-type="dijit/form/ValidationTextBox" disabled="disabled" value="<?php echo $libelledroit; ?>"   >
                        </td>
                        </tr>
                    </table>
                </div>
                <button id="progButtonNodeindust" data-dojo-type="dijit/form/Button" type="submit" name="submitButton" value="Submit">Submit</button>
            </fieldset> <?php include 'html/footer.html'; ?></form>
    </div>
<?php } ?>
<script>
    require(["dijit/form/Button", "dojo/dom", "dojo/domReady!"], function(Button, dom) {
// Create a button programmatically:
        var myButton = new Button({
            label: "<?php echo TXT_MAJ; ?>",
            onClick: function() {
                if (!empty(document.getElementById('valeurEmailResponsablea1'))) {
                    var emailResponsable = document.getElementById('valeurEmailResponsablea1').value;
                } else {
                    emailResponsable = "";
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
                if (!empty(dijit.byId('autreEmployeur')) && document.getElementById('trAutrenom').style.display != 'none') {
                    var libelleautrenomemployeur = dijit.byId('autreEmployeur').value;
                } else {
                    libelleautrenomemployeur = "";
                }
                if (!empty(dijit.byId('libelletutelle'))) {
                    var idTutelle = dijit.byId('libelletutelle').value;
                } else {
                    idTutelle = "";
                }
                if (!empty(document.getElementById('autreTutelle')) && document.getElementById('trAutretutelle').style.display != 'none') {
                    var libelleautretutelle = document.getElementById('autreTutelle').value;
                } else {
                    libelleautretutelle = "";
                }
                if (!empty(dijit.byId('libellediscipline'))) {
                    var iddiscipline = dijit.byId('libellediscipline').value;
                } else {
                    iddiscipline = "";
                }
                if (!empty(document.getElementById('autreDiscipline')) && document.getElementById('trDiscipline').style.display != 'none') {
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


                window.location.replace("<?php  echo '/'.REPERTOIRE;  ?>/modifBase/majgestioncompteaca.php?nomresponsable=" + nomresponsable + "&emailResponsable=" + emailResponsable + "\n\
&mailresponsable=" + mailresponsable + "&idemployeur=" + idemployeur + "&role=" + role + "&idtutelle=" + idTutelle + "&libelleautretutelle=" + libelleautretutelle + "\n\
&libelleautrenomemployeur=" + libelleautrenomemployeur + "&acronymelaboratoire=" + acronymelaboratoire + "&iddiscipline=" + iddiscipline +
                        "&libelleautrediscipline=" + libelleautrediscipline + "&centrale=" + centrale + "&idqualiteaca=" +<?php echo $_GET['idqualiteaca'] ?> + "&iduser=" +<?php echo $iduser; ?> + "&statutcompte=" + statutcompte + "&page_precedente=<?php echo basename(__FILE__); ?>");
            }
        }, "progButtonNode");
    });
</script>

<script>
    require(["dijit/form/Button", "dojo/dom", "dojo/domReady!"], function(Button, dom) {
// Create a button programmatically:
        var myButton = new Button({
            label: "<?php echo TXT_MAJ; ?>",
            onClick: function() {
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
                window.location.replace("<?php echo '/'.REPERTOIRE; ?>/modifBase/majgestioncompteindust.php?iduser=" +<?php echo $iduser; ?> + "&role=" + role + "&idtypeentreprise=" + idtypeentreprise + "&idsecteuractivite=" + idsecteuractivite + "&statutcompte=" + statutcompte + "&page_precedente=<?php echo basename(__FILE__); ?>");
            }
        }, "progButtonNodeindust");
    });
</script>
<script>
    require(["dojo"], function(dojo) {
        dojo.addOnLoad(function() {
            if (dijit.byId('libelleemployeur').get("displayedValue") === 'Autres') {
                document.getElementById('trAutrenom').style.display = '';
            } else {
                document.getElementById('trAutrenom').style.display = 'none'
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

<?php BD::deconnecter(); ?>
</body>
</html>