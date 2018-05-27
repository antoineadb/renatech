<?php
session_start();
include_once 'outils/toolBox.php';
include_once 'outils/constantes.php';
include_once 'decide-lang.php';
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
include 'html/header.html';
include_once 'class/Manager.php';
$db = BD::connecter();
$manager = new Manager($db);
?>
<script src="<?php echo '/' . REPERTOIRE . '/'; ?>js/requete.js"></script>
<div id="global">
    <?php
    include 'html/entete.html';
    ?>
    <div style="margin-top: 70px;">
        <?php include_once 'outils/bandeaucentrale.php'; ?>
    </div> 
    <form data-dojo-type="dijit/form/Form" name="exportProjet" id="exportProjet" method="post"  action="<?php echo '/' . REPERTOIRE; ?>/modifBase/requete.php?lang=<?php echo $lang; ?>"  >
        <fieldset id="requetparam" >
            <legend style="color: #5D8BA2;font-size: 1.2em"><b><?php echo 'Contructeur de requetes'; ?></b></legend>            
            <table style="float: left">
                <tr>
                    <td style="float: right;margin-left: 20px;height: 300px">
                        <fieldset class="userTable" style="height: 280px">
                            <legend>Utilisateur</legend>                            
                            <?php
                            $row = $manager->getList("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'utilisateur' "
                                    . "AND COLUMN_NAME != 'idutilisateur'"
                                    . "AND COLUMN_NAME != 'fax'"
                                    . "AND COLUMN_NAME != 'idqualitedemandeuraca_qualitedemandeuraca'"
                                    . "AND COLUMN_NAME != 'administrateur'"
                                    . "AND COLUMN_NAME != 'vueprojetcentrale'"
                                    . "AND COLUMN_NAME != 'datecreation' "
                                    . "AND COLUMN_NAME != 'idtypeutilisateur_typeutilisateur' "
                                    . "AND COLUMN_NAME != 'idpays_pays'"
                                    . "AND COLUMN_NAME != 'idlogin_loginpassword'"
                                    . "AND COLUMN_NAME != 'iddiscipline_disciplinescientifique'"
                                    . "AND COLUMN_NAME != 'idcentrale_centrale'"
                                    . "AND COLUMN_NAME != 'idtutelle_tutelle'"
                                    . "AND COLUMN_NAME != 'idemployeur_nomemployeur'"
                                    . "AND COLUMN_NAME != 'idautrestutelle_autrestutelle'"
                                    . "AND COLUMN_NAME != 'idautrediscipline_autredisciplinescientifique'"
                                    . "AND COLUMN_NAME != 'idautrenomemployeur_autrenomemployeur'"
                                    . "AND COLUMN_NAME != 'idqualitedemandeurindust_qualitedemandeurindust'"
                                    . "AND COLUMN_NAME != 'idautrecodeunite_autrecodeunite'"
                                    . "AND COLUMN_NAME != 'idcentrale_centrale'");
                            echo '<input class="opt" type="checkbox" data-dojo-type="dijit/form/CheckBox" id="tous" name="user[]" value ="tous" onClick="selectAll(\'user\')" /><label for ="tous" class="opt"> Tous</label><hr style="color:#5d8ba2">';
                            for ($i = 0; $i < count($row); $i++) {
                                if ($row[$i][0] == 'prenom') {
                                    $nomcolonne = 'Prénom';
                                } elseif ($row[$i][0] == 'codepostal') {
                                    $nomcolonne = 'Code postal';
                                } elseif ($row[$i][0] == 'telephone') {
                                    $nomcolonne = 'Téléphone';
                                } elseif ($row[$i][0] == 'nomresponsable') {
                                    $nomcolonne = 'Nom du responsable';
                                } elseif ($row[$i][0] == 'mailresponsable') {
                                    $nomcolonne = 'E-mail du responsable';
                                } elseif ($row[$i][0] == 'nomentreprise') {
                                    $nomcolonne = "Nom de l'entreprise";
                                } elseif ($row[$i][0] == 'acronymelaboratoire') {
                                    $nomcolonne = "Acronyme du laboratoire";
                                } elseif ($row[$i][0] == 'entrepriselaboratoire') {
                                    $nomcolonne = "Entreprise du laboratoire";
                                } else {
                                    $nomcolonne = $row[$i][0];
                                }
                                echo
                                "<input  class='opt'  type='checkbox' data-dojo-type='dijit/form/CheckBox' id='" . $row[$i][0] . "' " . "name='user[]' value='" . $row[$i][0] . "'  
                                          onclick='checkUser(this.value+\" \")' >
                                    <label for = '" . $row[$i][0] . "' class='opt' > " . ucfirst($nomcolonne) . "</label>";
                                echo '<br>';
                            }
                            ?>
                        </fieldset>
                    </td>
                </tr>
            </table>           
            <table id="academicIndust" class="academicIndust" style="margin-left: 20px">
                <caption valign="top" style="text-align: center"> Filtre Utilisateur</caption>
                <tr>
                    <td>
                        <input type= "radio" data-dojo-type="dijit/form/RadioButton" name="typeuser" id ="academ" value="academique"  class="btRadio"
                               onclick="AfficheMasque('aca', 'ind')" ><?php echo TXT_ACADEMIQUE; ?>
                        <input type= "radio" data-dojo-type="dijit/form/RadioButton" name="typeuser" id="indust" value="industriel" class="btRadio"
                               onclick="AfficheMasque('ind', 'aca')" ><?php echo TXT_INDUSTRIEL; ?>
                    </td>
                </tr>
                <tr><!--------------------------------------------------------- CENTRALE ----------------------------------------------------------------------------->
                    <td style="float: left;margin-top: 10px;display: none" id="tdCentrale">
                    <?php $libellecentrale = $manager->getListbyArray("select idcentrale,libellecentrale from centrale where idcentrale!=? and masquecentrale!=? order by libellecentrale asc",array(IDCENTRALEAUTRE,TRUE)); ?>
                        <select id="centrale" data-dojo-type="dijit/form/Select"  data-dojo-props="  value: '' , placeHolder: '<?php echo TXT_SELECTCENTRALE; ?>',onChange: function(value){selectCentrale(this.value)}" >
                            <?php
                            echo "<option value='0'>" . TXT_SELECTCENTRALE . "</option>";
                            for ($i = 0; $i < count($libellecentrale); $i++) {
                                echo "<option value='" . $libellecentrale[$i]['idcentrale'] . "'>" . removeDoubleQuote($libellecentrale[$i]['libellecentrale']) . "
                            </option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr><!--------------------------------------------------------- QUALITE ACADEMIQUE ----------------------------------------------------------------------------->
                    <td style="float: left;margin-top: 10px;display: none" id="aca">
                        <?php $academique = $manager->getList2("select idqualitedemandeuraca,libellequalitedemandeuraca  from qualitedemandeuraca where idqualitedemandeuraca!=? order by idqualitedemandeuraca asc", IDNAAUTREQUALITE); ?>
                        <select  data-dojo-type="dijit/form/Select"  id="acad"
                                 data-dojo-props="  value: '' , placeHolder: '<?php echo 'Sélectionnez un type utilisateur'; ?>',onChange: function(value){selectQualite(this.value)}" >
                                     <?php
                                     echo "<option value='0'>" . TXT_SELECTQUALITE . "</option>";
                                     for ($i = 0; $i < count($academique); $i++) {
                                         echo "<option value='" . $academique[$i]['idqualitedemandeuraca'] . "'>" . removeDoubleQuote($academique[$i]['libellequalitedemandeuraca']) . "
                                </option>";
                                     }
                                     ?>
                        </select>
                    </td>
                </tr>
                <tr><!--------------------------------------------------------- QUALITE INDUSTRIEL ----------------------------------------------------------------------------->
                    <td style="float: left;margin-top: 10px;display: none" id="ind">
                        <?php $industriel = $manager->getList2("select idqualitedemandeurindust,libellequalitedemandeurindust  from qualitedemandeurindust where idqualitedemandeurindust!=? order by idqualitedemandeurindust asc", NAQAUALITEINDUST); ?>
                        <select data-dojo-type="dijit/form/Select" id="industr"  
                                data-dojo-props="  value: '' , placeHolder: '<?php echo 'Sélectionnez un type utilisateur'; ?>',onChange: function(value){selectQualite(this.value)}" >
                                    <?php
                                    echo "<option value='0'>" . TXT_SELECTQUALITE . "</option>";
                                    for ($i = 0; $i < count($industriel); $i++) {
                                        echo "<option value='" . $industriel[$i]['idqualitedemandeurindust'] . "'>" . removeDoubleQuote($industriel[$i]['libellequalitedemandeurindust']) . "
                                </option>";
                                    }
                                    ?>
                        </select>
                    </td>
                </tr>
                <tr><!--------------------------------------------------------- QUALITE ACADEMIQUE TUTELLE----------------------------------------------------------------------------->
                    <td style="float: left;margin-top: 10px;display: none" id="tdTutelle">
                        <?php $tutelle = $manager->getList2("select idtutelle,libelletutelle  from tutelle where idtutelle!=? order by idtutelle asc", IDAUTRETUTELLE); ?>
                        <select data-dojo-type="dijit/form/Select"  id="tutelle"
                                data-dojo-props="  value: '' , placeHolder: '<?php echo 'Sélectionnez un type utilisateur'; ?>',onChange: function(value){selectTutelle(this.value)}" >
                                    <?php
                                    echo "<option value='0'>" . TXT_SELECTTUTELLE . "</option>";
                                    for ($i = 0; $i < count($tutelle); $i++) {
                                        echo "<option value='" . $tutelle[$i]['idtutelle'] . "'>" . removeDoubleQuote($tutelle[$i]['libelletutelle']) . "
                                </option>";
                                    }
                                    ?>
                        </select>
                    </td>
                </tr>
                <tr><!--------------------------------------------------------- QUALITE DISCIPLINE ----------------------------------------------------------------------------->
                    <td style="float: left;margin-top: 10px;display: none" id="tdDiscipline">
                        <?php $discpiline = $manager->getList2("select iddiscipline,libellediscipline  from disciplinescientifique where iddiscipline!=? order by iddiscipline asc", IDAUTREDISCIPLINE); ?>
                        <select data-dojo-type="dijit/form/Select"  id="discipline"
                                data-dojo-props="  value: '' , placeHolder: '<?php echo TXT_SELECTDISCIPLINE; ?>',onChange: function(value){selectDiscipline(this.value)}" >
                                    <?php
                                    echo "<option value='0'>" . TXT_SELECTDISCIPLINE . "</option>";
                                    for ($i = 0; $i < count($discpiline); $i++) {
                                        echo "<option value='" . $discpiline[$i]['iddiscipline'] . "'>" . removeDoubleQuote($discpiline[$i]['libellediscipline']) . "
                              </option>";
                                    }
                                    ?>
                        </select>
                    </td>
                </tr>
                <tr><!--------------------------------------------------------- TYPE ENTREPRISE  ----------------------------------------------------------------------------->
                    <td style="float: left;margin-top: 10px;display: none" id="tdTypeEntreprise">
                        <?php $typeEntreprise = $manager->getList("select idtypeentreprise,libelletypeentreprise  from typeentreprise  order by idtypeentreprise asc"); ?>
                        <select data-dojo-type="dijit/form/Select"  id="typeEntreprise"
                                data-dojo-props="  value: '' , placeHolder: '<?php echo TXT_SELECTTYPEENTREPRISE; ?>',onChange: function(value){selectQualite(this.value)}" >
                                    <?php
                                    echo "<option value='0'>" . TXT_SELECTTYPEENTREPRISE . "</option>";
                                    for ($i = 0; $i < count($typeEntreprise); $i++) {
                                        echo "<option value='" . $typeEntreprise[$i]['idtypeentreprise'] . "'>" . removeDoubleQuote($typeEntreprise[$i]['libelletypeentreprise']) . "
                              </option>";
                                    }
                                    ?>
                        </select>
                    </td>
                </tr>
                <tr><!--------------------------------------------------------- SECTEUR ACTIVITE  ----------------------------------------------------------------------------->
                    <td style="float: left;margin-top: 10px;display: none" id="tdSecteurActivite">
                        <?php $secteurActivite = $manager->getList("select idsecteuractivite,libellesecteuractivite  from secteuractivite   order by idsecteuractivite asc"); ?>
                        <select data-dojo-type="dijit/form/Select"  id="secteurActivite"
                                data-dojo-props=' value: "" , placeHolder: "",onChange: function(value){selectQualite(this.value)}'>
                                    <?php
                                    echo "<option value='0'>" . TXT_SELECTSECTEURACTIVITE . "</option>";
                                    for ($i = 0; $i < count($secteurActivite); $i++) {
                                        echo "<option value='" . $secteurActivite[$i]['idsecteuractivite'] . "'>" . removeDoubleQuote($secteurActivite[$i]['libellesecteuractivite']) . "
                              </option>";
                                    }
                                    ?>
                        </select>
                    </td>
                </tr>
            </table>            
            <table style="float: right">
                <tr>
                    <td style="height: 300px">
                        <fieldset id="projetTable">
                            <legend>Projet</legend>
                            <?php
                            $row = $manager->getList("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'projet' "
                                    . "AND COLUMN_NAME !='idprojet'"
                                    . "AND COLUMN_NAME !='dateprojet'"
                                    . "AND COLUMN_NAME !='commentaire'"
                                    . "AND COLUMN_NAME !='attachement'"
                                    . "AND COLUMN_NAME !='attachementdesc'"
                                    . "AND COLUMN_NAME !='dureeprojet'"
                                    . "AND COLUMN_NAME !='verrouidentifiee'"
                                    . "AND COLUMN_NAME !='idtypeprojet_typeprojet'"
                                    . "AND COLUMN_NAME !='idthematique_thematique'"
                                    . "AND COLUMN_NAME !='idperiodicite_periodicite'"
                                    . "AND COLUMN_NAME !='idautrethematique_autrethematique'"
                                    . "AND COLUMN_NAME !='idetat_etatprojet'"
                                    . "AND COLUMN_NAME !='interneexterne'"
                                    . "AND COLUMN_NAME !='internationalnational'"
                                    . "AND COLUMN_NAME != 'envoidevis'"
                                    . "AND COLUMN_NAME != 'nbplaque'"
                                    . "AND COLUMN_NAME != 'nbrun'"
                                    . "AND COLUMN_NAME != 'typeformation'"
                                    . "AND COLUMN_NAME != 'nbheure'"
                                    . "AND COLUMN_NAME != 'nbeleve'"
                                    . "AND COLUMN_NAME != 'dureeestime'"
                                    . "AND COLUMN_NAME != 'periodestime'"
                                    . "AND COLUMN_NAME != 'nomformateur'"
                                    . "AND COLUMN_NAME != 'partenaire1'"
                                    . "AND COLUMN_NAME != 'emailrespdevis'"
                                    . "AND COLUMN_NAME != 'porteurprojet'"
                                    . "AND COLUMN_NAME != 'etapeautrecentrale'"
                                    . "AND COLUMN_NAME != 'centraleproximite'"
                                    . "AND COLUMN_NAME != 'devtechnologique'"
                                    . "AND COLUMN_NAME !='trashed'");
                            echo '<input class="opt" type="checkbox" data-dojo-type="dijit/form/CheckBox" id="tousProjet" name="projet[]" value ="tous" onClick="selectAllProjet(\'projet\')" /><label for ="tous" class="opt"> Tous</label><hr style="color:#5d8ba2">';
                            for ($i = 0; $i < count($row); $i++) {
                                if ($row[$i][0] == 'numero') {
                                    $nomcolonne = 'Numéro';
                                } elseif ($row[$i][0] == 'confidentiel') {
                                    $nomcolonne = 'Projet confidentiel';
                                } elseif ($row[$i][0] == 'contactscentraleaccueil') {
                                    $nomcolonne = "Contacts dans la centrale d'accueil";
                                } elseif ($row[$i][0] == 'nomresponsable') {
                                    $nomcolonne = 'Nom du responsable';
                                } elseif ($row[$i][0] == 'Datedebuttravaux') {
                                    $nomcolonne = 'Date de début des travaux';
                                } elseif ($row[$i][0] == 'centralepartenaireprojet') {
                                    $nomcolonne = "Nom de la centrale partenaire";
                                } elseif ($row[$i][0] == 'refinterneprojet') {
                                    $nomcolonne = "Référence interne";
                                } elseif ($row[$i][0] == 'descriptiftechnologique') {
                                    $nomcolonne = " Descriptif technologique";
                                } elseif ($row[$i][0] == 'datedebutprojet') {
                                    $nomcolonne = "Date début du projet";
                                } elseif ($row[$i][0] == 'datestatutfini') {
                                    $nomcolonne = "Date du statut finis";
                                } elseif ($row[$i][0] == 'datestatutcloturer') {
                                    $nomcolonne = "Date du statut clôturer";
                                } elseif ($row[$i][0] == 'acrosourcefinancement') {
                                    $nomcolonne = "Acronyme de la source de financement";
                                } elseif ($row[$i][0] == 'descriptionautrecentrale') {
                                    $nomcolonne = "Description de l'étape dans l'autre centrale";
                                } elseif ($row[$i][0] == 'datemaj') {
                                    $nomcolonne = "Date de mise à jour du projet";
                                } elseif ($row[$i][0] == 'descriptioncentraleproximite') {
                                    $nomcolonne = "Description de la centrale de proximité";
                                } else {
                                    $nomcolonne = ucfirst($row[$i][0]);
                                }
                                echo
                                "<input  class='opt'  type='checkbox' data-dojo-type='dijit/form/CheckBox' id='" . $nomcolonne . "' " . "name='projet[]' value='" . $nomcolonne . "'
                                      onclick='checkProject(this.value+\",\")' >
                                      <label for = '" . $nomcolonne . "' class='opt' > " . $nomcolonne . "</label>";
                                echo '<br>';
                            }
                            ?>
                        </fieldset>
                    </td>
                </tr>
            </table>
            <table id="filtreProjet" class="academicIndust" style="visibility: visible;margin-top: 5px;">
                <caption valign="top" style="text-align: center"> Filtre Projet</caption>
                <tr>
                    <td>
                       <?php $statutProjet = $manager->getList2("select idstatutprojet,libellestatutprojet  from statutprojet where idstatutprojet!=? order by idstatutprojet asc",TRANSFERERCENTRALE); ?>
                        <select style="width: 150px"  data-dojo-type="dijit/form/Select"  id="statutProjet" 
                                data-dojo-props="  value: '' , placeHolder: '<?php echo TXT_SELECTSTATUT; ?>',onChange: function(value){selectStatut(this.value)}" >
                                    <?php
                                    echo "<option value='0'>" . TXT_STATUTPROJETS . "</option>";
                                    for ($i = 0; $i < count($statutProjet); $i++) {
                                        echo "<option value='" . $statutProjet[$i]['idstatutprojet'] . "'>" . removeDoubleQuote($statutProjet[$i]['libellestatutprojet']) . "
                              </option>";
                                    }
                                    ?>
                        </select>
                    </td>
                </tr><tr>
                    <td>
                       <?php 
                       $years = $manager->getList("select distinct EXTRACT(YEAR from datedebutprojet)as year from projet where   EXTRACT(YEAR from datedebutprojet)>2012 order by year asc");?>
                        <select data-dojo-type="dijit/form/Select"  id="dateProjet" style="margin-top: 10px;width: 150px"
                                data-dojo-props="  value: '' , placeHolder: '<?php echo TXT_SELECTYEAR; ?>',onChange: function(value){selectStatut(this.value)}" >
                                    <?php
                                    echo "<option value='0'>" . TXT_ANNEEDEBUTPROJET . "</option>";
                                    for ($i = 0; $i < count($years); $i++) {
                                        echo "<option value='" . $years[$i][0] . "'>" . $years[$i][0] . "
                              </option>";
                                    }
                                    ?>
                        </select>
                    </td>
                </tr>
        </table>
             <table id="linkProject" class="academicIndust" style="visibility: visible;margin-top: 30px;">
                <tr>
                    <td>
                        <button data-dojo-type="dijit/form/Button" type="button" style="margin-left: 40px" onclick="addLinkProjet()">Lier les projets avec les utilisateurs
                        <script type="dojo/on" data-dojo-event="click" data-dojo-args="evt"></script>
                        </button>
                    </td>
                </tr>
            </table>
            <table>
                <tr>
                    <td>
                        <textarea  id="select"  style="width: 670px; height: 50px;margin-top:15px;margin-left: 20px;display: block">select </textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                        <textarea id="from"  style="width: 670px; height: 40px;margin-top:15px;margin-left: 20px;display: block">from</textarea>  
                    </td>
                <tr>                    
                    <td>
                        <textarea id="where"  style="width: 670px; height: 40px;margin-top:15px;margin-left: 20px;display: block"></textarea>
                    </td>                     
                </tr>
                <tr>                    
                    <td>
                        <textarea id="and"  style="width: 670px; height: 40px;margin-top:15px;margin-left: 20px;display: block"></textarea>
                    </td>                     
                </tr>
                <tr>
                    <td>
                        <textarea name="request" id="request"  style="width: 670px; height: 150px;margin-top:15px;margin-left: 20px"></textarea>
                    </td>
                </tr>               
                <tr>
                    <td>
                        <input type="button" id="executeRequete"  label="<?php echo 'excecute la requete'; ?>" data-dojo-Type="dijit.form.Button" onclick="exportProjet.submit()"
                               style="margin-top: 20px;margin-bottom: 20px;  height: 28px; text-align: center; font-size: 1.2em;float: left;margin-left: 20px" />
                    </td>
                    <td>
                        <input type="button" id="checkRequete"  label="<?php echo 'Valide la requete'; ?>" data-dojo-Type="dijit.form.Button" onclick="checkRequest()"
                               style="margin-top: 20px;margin-bottom: 20px;  height: 28px; text-align: center; font-size: 1.2em;float: left;margin-left: 20px" />
                    </td>
                    <td>
                        <input type="button" id="clearRequete"  label="<?php echo 'Efface tout'; ?>" data-dojo-Type="dijit.form.Button" onclick="clearAll()"
                               style="margin-top: 20px;margin-bottom: 20px;  height: 28px; text-align: center; font-size: 1.2em;float: left;margin-left: 20px" />
                    </td>
                </tr>
            </table>
        </fieldset>          
    </form>
    <?php include 'html/footer.html'; ?>
</div><?php BD::deconnecter(); ?>
</body>
</html>