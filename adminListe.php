<?php
session_start();
include('decide-lang.php');
include_once 'class/Manager.php';
include_once 'outils/toolBox.php';
include_once 'outils/affichelibelle.php';
include_once 'outils/constantes.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
?>
<div id="global">
    <?php
    include 'html/entete.html';
    include 'html/header.html';
    ?>
    <div style="margin-top: 70px;">
        <?php include_once 'outils/bandeaucentrale.php'; ?>
    </div>

    <div style="color: darkblue;font-size: 1.2em; margin-left: 304px; margin-top: 45px;">
        <?php
//TYPE PROJET
        if (!empty($_GET['msgserveurtypeprojet'])) {
            echo TXT_MESSAGESERVEURTYPEPROJET;
        } elseif (!empty($_GET['msgErrtypeProjetnonsaisie'])) {
            echo TXT_MESSAGEERREURTYPEPROJETNONSAISIE;
        } elseif (!empty($_GET['msgErrtypeexiste'])) {
            echo TXT_MESSAGESERVEURTYPEEXISTE;
        } elseif (!empty($_GET['msgErrtypeprojetselect'])) {
            echo TXT_MESSAGEERREURGETYPEPROJETSELECT;
        } elseif (!empty($_GET['msgtypeprojetupdate'])) {
            echo TXT_MESSAGETYPEPROJETUPDATE;
        } elseif (!empty($_GET['msgserveurtypeprojethide'])) {
            echo TXT_MESSAGESERVEURTYPEPROJETMASQUER;
        } elseif (!empty($_GET['msgserveurtypeprojetshow'])) {
            echo TXT_MESSAGESERVEURTYPEPROJETAFFICHE;
//TYPE  FORMATION
        } elseif (!empty($_GET['msgErrtypeformationnonsaisie'])) {
            echo TXT_MESSAGEERREURTYPEFORMATIONNONSAISIE;
        } elseif (!empty($_GET['msgErrtypeformationexiste'])) {
            echo TXT_MESSAGESERVEURTYPEFORMATIONEXISTE;
        } elseif (!empty($_GET['msgserveurtypeformation'])) {
            echo TXT_MESSAGESERVEURTYPEFORMATION;
        } elseif (!empty($_GET['msgErrtypeformationselect'])) {
            echo TXT_MESSAGEERREURGETYPEFORMATIONSELECT;
        } elseif (!empty($_GET['msgtypeformationupdate'])) {
            echo TXT_MESSAGETYPEFORMATIONUPDATE;
        } elseif (!empty($_GET['msgserveurtypeformationhide'])) {
            echo TXT_MESSAGESERVEURTYPEFORMATIONMASQUER;
        } elseif (!empty($_GET['msgserveurtypeformationshow'])) {
            echo TXT_MESSAGESERVEURTYPEFORMATIONAFFICHE;
//THEMATIQUE
        } elseif (!empty($_GET['msgErrthematiquenonsaisie'])) {
            echo TXT_MESSAGEERREURTHEMATIQUENONSAISIE;
        } elseif (!empty($_GET['msgErrthematiqueexiste'])) {
            echo TXT_MESSAGESERVEURTHEMATIQUEEXISTE;
        } elseif (!empty($_GET['msgserveurthematique'])) {
            echo TXT_MESSAGESERVEURTHEMATIQUE;
        } elseif (!empty($_GET['msgErrthematiqueselect'])) {
            echo TXT_MESSAGEERREURTHEMATIQUESELECT;
        } elseif (!empty($_GET['msgthematiqueupdate'])) {
            echo TXT_MESSAGETHEMATIQUEUPDATE;
        } elseif (!empty($_GET['msgserveurthematiquehide'])) {
            echo TXT_MESSAGESERVEURTHEMATIQUEMASQUER;
        } elseif (!empty($_GET['msgserveurthematiqueshow'])) {
            echo TXT_MESSAGESERVEURTHEMATIQUEAFFICHE;
//STATUT DU PROJET
        } elseif (!empty($_GET['msgErrstatutselect'])) {
            echo TXT_MESSAGEERREURSTATUTSELECT;
        } elseif (!empty($_GET['msgErrstatutnonsaisie'])) {
            echo TXT_MESSAGEERREURSTATUTNONSAISIE;
        } elseif (!empty($_GET['msgErrstatutexiste'])) {
            echo TXT_MESSAGESERVEURSTATUTEXISTE;
        } elseif (!empty($_GET['msgupdatestatut'])) {
            echo TXT_MESSAGESTAUTUPDATE;
        } elseif (!empty($_GET['msgserveurstatut'])) {
            echo TXT_MESSAGESERVEURSTATUT;
        }
//VILLE PAYS
        if (!empty($_GET['msgErrVil'])) {
            echo TXT_MESSAGEERREURVILLENONSAISIE;
        } elseif (!empty($_GET['msgErrpays'])) {
            echo TXT_MESSAGEERREURPAYSNONSAISIE;
        } elseif (!empty($_GET['msgErrpaysnonsaisie'])) {
            echo TXT_MESSAGEERREURPAYSNONSAISIE;
        } elseif (!empty($_GET['msgErrpaysennonsaisie'])) {
            echo TXT_MESSAGEERREURPAYSENNONSAISIE;
        } elseif (!empty($_GET['msgErrpaysexiste'])) {
            echo TXT_MESSAGESERVEURPAYSEXISTE;
        } elseif ((!empty($_GET['msgErrpaysennonsaisie']))) {
            echo TXT_MESSAGEERREURPAYSENNONSAISIE;
        } elseif (!empty($_GET['msgserveurpays'])) {
            echo TXT_MESSAGESERVEURPAYS;
        } elseif (!empty($_GET['msgErrpaysselect'])) {
            echo TXT_MESSAGEERREURPAYSENONSELECT;
        } elseif (!empty($_GET['msgpaysupdate'])) {
            echo TXT_MESSAGESERVEURUPDATEPAYS;
        } elseif (!empty($_GET['msgserveurpayshide'])) {
            echo TXT_MESSAGESERVEURPAYSMASQUER;
        } elseif (!empty($_GET['msgserveurpaysshow'])) {
            echo TXT_MESSAGESERVEURPAYSAFFICHE;
// SITUATION GEOGRAPHIQUE
        } elseif (!empty($_GET['msgErrsituationselect'])) {
            echo TXT_MESSAGEERREURGEONONSELECT;
//SECTEUR ACTIVITE
        } elseif (!empty($_GET['msgErrSecteurnonsaisie'])) {
            echo TXT_MESSAGEERREURSECTEURNONSAISIE;
        } elseif (!empty($_GET['msgErrSecteurennonsaisie'])) {
            echo TXT_MESSAGEERREURSECTEURNONSAISIE;
        } elseif (!empty($_GET['msgErrsecteurselect'])) {
            echo TXT_MESSAGEERREURGESECTEURONSELECT;
        } elseif (!empty($_GET['msgErrSecteurexiste'])) {
            echo TXT_MESSAGESERVEURSECTEUREXISTE;
        } elseif (!empty($_GET['msgserveursecteur'])) {
            echo TXT_MESSAGESERVEURSETCEURACTIVITE;
        } elseif (!empty($_GET['msgsecteurupdate'])) {
            echo TXT_MESSAGESEECTEURUPDATE;
        } elseif (!empty($_GET['msgErrtypeentreselect'])) {
            echo TXT_MESSAGEERREURSELECTSECTEURACTIVITE;
        } elseif (!empty($_GET['msgErrtypeEntreprisenonsaisie'])) {
            echo TXT_MESSAGEERREURSECTEURACTIVITENONSAISIE;
        } elseif (!empty($_GET['msgErrsecteuractiviteexiste'])) {
            echo TXT_MESSAGEERREURSECTEURACTIVITEEXISTE;
        } elseif (!empty($_GET['msgserveursecteuractivitehide'])) {
            echo TXT_MESSAGESERVEURSECTEURACTIVITEMASQUER;
        } elseif (!empty($_GET['msgserveursecteuractiviteshow'])) {
            echo TXT_MESSAGESERVEURSECTEURACTIVITEAFFICHE;
//TYTE ENTREPRISE
        } elseif (!empty($_GET['msgErrtypeEntreprisenonsaisie'])) {
            echo TXT_MESSAGEERREURTYPEENTREPRISENONSAISIE;
        } elseif (!empty($_GET['msgErrtypeentrepriseexiste'])) {
            echo TXT_MESSAGESERVEURTYPEENTREPRISEEXISTE;
        } elseif (!empty($_GET['msgserveurtypeentreprise'])) {
            echo TXT_MESSAGESERVEURTYPEENTREPRISE;
        } elseif (!empty($_GET['msgErrtypeentreselect'])) {
            echo TXT_MESSAGEERREURTYPEENTRESELECT;
        } elseif (!empty($_GET['msgserveurtypeentrepriseupdate'])) {
            echo TXT_MESSAGESERVEURTYPEENTREPRISEUPDATE;
        } elseif (!empty($_GET['msgserveurtypeentreprisehide'])) {
            echo TXT_MESSAGESERVEURTYPEENTREPRISEMASQUER;
        } elseif (!empty($_GET['msgserveurtypeentrepriseshow'])) {
            echo TXT_MESSAGESERVEURTYPEENTREPRISEAFFICHE;
        }
//DISCIPLINE SCIENTIFIQUE
        elseif ((!empty($_GET['msgErrdisciplinenonsaisie']))) {
            echo TXT_MESSAGEERREURDISCIPLINENONSAISIE;
        } elseif ((!empty($_GET['msgserveurdiscipline']))) {
            echo TXT_MESSAGESERVEURDISCIPLINE;
        } elseif ((!empty($_GET['msgdisciplineupdate']))) {
            echo TXT_MESSAGEDISCIPLINEUPDATE;
        } elseif ((!empty($_GET['msgserveurnomemployeur']))) {
            echo TXT_MESSAGESERVEUREMPLOYEUR;
        } elseif (!empty($_GET['msgErrdisciplineselect'])) {
            echo TXT_MESSAGEERREURDISCIPLINESELECT;
        } elseif (!empty($_GET['msgserveurdisciplinehide'])) {
            echo TXT_MESSAGESERVEURDISCIPLINEMASQUER;
        } elseif (!empty($_GET['msgserveurtypedisciplineshow'])) {
            echo TXT_MESSAGESERVEURDISCIPLINAFFICHE;
        } elseif (!empty($_GET['msgErrdisciplineexiste'])) {
            echo TXT_MESSAGESERVEURDISCIPLINEEXISTE;
//NOM EMPLOYEUR
        } elseif (!empty($_GET['msgemployeurupdate'])) {
            echo TXT_MESSAGEEMPLOYEURUPDATE;
        } elseif (!empty($_GET['msgErremployeurnonsaisie'])) {
            echo TXT_MESSAGEERREURNOMEMPOYEURNONSAISIE;
        } elseif (!empty($_GET['msgserveurnomemployeurhide'])) {
            echo TXT_MESSAGESERVEURNOMEMPLOYEURMASQUER;
        } elseif (!empty($_GET['msgserveurnomemployeurshow'])) {
            echo TXT_MESSAGESERVEURNOMEMPLOYEURAFFICHE;
        } elseif (!empty($_GET['msgErrnomemployeurexiste'])) {
            echo TXT_MESSAGESERVEUREMPLOYEUREXISTE;
//TUTELLE
        } elseif (!empty($_GET['msgErrtutelleselect'])) {
            echo TXT_MESSAGEERREURTUTELLESELECT;
        } elseif (!empty($_GET['msgErrtutellenonsaisie'])) {
            echo TXT_MESSAGEERREURTUTELLENONSAISIE;
        } elseif (!empty($_GET['msgErrtutelleexiste'])) {
            echo TXT_MESSAGESERVEURTUTELLEEXISTE;
        } elseif (!empty($_GET['msgTutelleupdate'])) {
            echo TXT_MESSAGETUTELLEUPDATE;
        } elseif (!empty($_GET['msgserveurtutelle'])) {
            echo TXT_MESSAGESERVEURTUTELLE;
        } elseif (!empty($_GET['msgserveurtutellehide'])) {
            echo TXT_MESSAGESERVEURTUTELLEMASQUER;
        } elseif (!empty($_GET['msgserveurtutelleshow'])) {
            echo TXT_MESSAGESERVEURTUTELLEAFFICHE;
//RESSOURCE
        } elseif (!empty($_GET['msgserveurressourcehide'])) {
            echo TXT_MESSAGESERVEURRESSOURCEMASQUER;
        } elseif (!empty($_GET['msgserveurressourceshow'])) {
            echo TXT_MESSAGESERVEURRESSOURCEAFFICHE;
        } elseif (!empty($_GET['msgErrressourcenonsaisie'])) {
            echo TXT_MESSAGEERREURRESSOURCENONSAISIE;
        } elseif (!empty($_GET['msgErrressourceexiste'])) {
            echo TXT_MESSAGESERVEURRESSOURCEEXISTE;
        } elseif (!empty($_GET['msgserveurressource'])) {
            echo TXT_MESSAGESERVEURRESSOURCE;
        } elseif (!empty($_GET['msgErrressourceselect'])) {
            echo TXT_MESSAGEERREURRESSOURCESELECT;
        } elseif (isset($_GET['msgRessourceupdate'])) {
            echo TXT_MESSAGERESSOURCEUPDATE;
//CENTRALE
        } elseif (!empty($_GET['msgserveurcentralehide'])) {
            echo TXT_MESSAGESERVEURCENTRALEMASQUER;
        } elseif (!empty($_GET['msgserveurtypecentraleshow'])) {
            echo TXT_MESSAGESERVEURCENTRALEAFFICHE;
        } elseif (!empty($_GET['msgErrCentupdate'])) {
            echo TXT_MESSAGESERVEURUPDATECENTRALE;
        } elseif (!empty($_GET['msgErrCentselect'])) {
            echo TXT_MESSAGEERREURCENTRALENONSELECT;
        } elseif (!empty($_GET['&msginsertcentrale'])) {
            echo TXT_MESSAGESERVEURCENTRALE;
        } elseif (isset($_GET['msgErrCentExist'])) {
            echo TXT_MESSAGESERVEURCENTRALEEXISTE;
        } elseif (!empty($_GET['msginsertcentrale'])) {
            echo TXT_MESSAGESERVEURCENTRALE;
        } elseif (!empty($_GET['msgErrvilleselect'])) {
            echo TXT_MESSAGEERREURVILLENONSELECT;
        } elseif (!empty($_GET['msgErrvillenonsaisie'])) {
            echo TXT_MESSAGEERREURVILLENONSAISIE;
        } elseif (!empty($_GET['msgErrvilleexiste'])) {
            echo TXT_MESSAGESERVEURVILLEEXISTE;
        } elseif (!empty($_GET['msgVilleupdate'])) {
            echo TXT_MESSAGEVILLEUPDATE;
        } elseif (isset($_GET['msgErrcentralemaileselect'])) {
            echo TXT_MESSAGEERREUREMAILCENTRALESELECT;
        } elseif (isset($_GET['msgErrcentralemailenomsaisie'])) {
            echo TXT_MESSAGEERREUREMAILCENTRALENONSAISIE;
        } elseif (isset($_GET['msgEmailcentralupdate'])) {
            echo TXT_MESSAGEEMAILCENTRALEUPDATE;
        } elseif (isset($_GET['msgCodeunitecentralupdate'])) {
            echo TXT_MESSAGECODEUNITECENTRALEUPDATE;
        } elseif (isset($_GET['msgErrcentralcodeunitenomsaisie'])) {
            echo TXT_MESSAGEERREURCODEUNITECENTRALENONSAISIE;
        } elseif (isset($_GET['msgErrcentralcodeuniteselect'])) {
            echo TXT_MESSAGEERREURECODEUNITECENTRALESELECT;
        } elseif (isset($_GET['erreurctrladmin'])) {
            echo TXT_MESSAGEERREURENOSELECT;
        } elseif (isset($_GET['msgErrCentrale'])) {
            echo TXT_MESSAGEERREURCENTRALENONSAISIE;
        }//SOURCE DE FINANCEMENT
        elseif (isset($_GET['msgserveursourcefinancementhide'])) {
            echo TXT_MESSAGESERVEURSOURCEFINANCEMENTMASQUER;
        } elseif (isset($_GET['msgserveurtypesourcefinancementshow'])) {
            echo TXT_MESSAGESERVEURSOURCEFINANCEMENTAFFICHE;
        } elseif (isset($_GET['msgErrsourcefinancementnonsaisie'])) {
            echo TXT_MESSAGEERREURSOURCEFINANCEMENTNONSAISIE;
        } elseif (isset($_GET['msgErridsourcefinancementexiste'])) {
            echo TXT_MESSAGESERVEURSOURCEFINANCEMENTEXISTE;
        } elseif (isset($_GET['msgserveursourcefinancement'])) {
            echo TXT_MESSAGESERVEURSOURCEFINANCEMENT;
        } elseif (isset($_GET['msgErrtsourcefinancementselect'])) {
            echo TXT_MESSAGEERREURSOURCEFINANCEMENTSELECT;
        } elseif (isset($_GET['msgsourcefinancementupdate'])) {
            echo TXT_MESSAGESOURCEFINANCEMENTUPDATE;
        }
        ?></div>

    <script>
        dojo.require("dijit.form.ComboBox");
    </script>
    <div data-dojo-type="dijit/layout/TabContainer" style="width: 1050px;font-size: 1.2em;margin-top:40px" doLayout="false">
        <div data-dojo-type="dijit/layout/ContentPane" title=" <?php echo '--- ' . TXT_ONGLETADMINLISTE . ' ---'; ?>  " style=" height: auto;" >
            <form id='administ' name='administ' method="post" action ="<?php echo '/' . REPERTOIRE . '/'; ?>controler/ctrladmin.php?lang=<?php echo $lang; ?>" data-dojo-type="dijit/form/Form" >
                <input name="page_precedente" type="hidden" value="<?php echo basename(__FILE__); ?>">
                <script type="dojo/method" data-dojo-event="onSubmit">
                    if(this.validate()){
                    return true;
                    }else{
                    alert('<?php echo TXT_MESSAGEERREURCONTACT; ?>');
                    return false;
                    exit();
                    }
                </script>
                <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------
                PAYS
----------------------------------------------------------------------------------------------------------------------------------------------------------------------      -->
                <fieldset id="identpays" style="border-color: #5D8BA2; margin-right: 20px;margin-left: 6px;padding-left: 20px;padding-top: 5px">
                    <legend><?php echo TXT_PAYS; ?></legend>
                    <table>
                        <tr>
                            <th><input type="text"  style="height:20px;margin-left:20px;background-color: white; width: 360px;vertical-align:middle;border: 0px" data-dojo-type="dijit/form/ValidationTextBox" readonly="true" ></th>
                        <input type="text" name="idlibellepaysactuel" style="display: none" data-dojo-type="dijit/form/ValidationTextBox" readonly="true"
                               value="<?php
                               if (isset($_GET['idpays'])) {
                                   echo $_GET['idpays'];
                               }
                               ?>" />
                        </tr>
                        <tr>
                            <th>
                                <?php
                                if ($lang == 'fr') {
                                    $rowpays = $manager->getList("SELECT nompays,idpays from pays  order by idpays asc;");
                                } elseif ($lang == 'en') {
                                    $rowpays = $manager->getList("SELECT nompaysen,idpays from pays  order by idpays asc;");
                                }
                                ?>
                                <select  id="libellepays" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'libellepays',value: '',required:false,placeHolder: '<?php echo TXT_SELECTEDPAYS; ?>'"
                                         style="width: 360px;margin-left:20px;" onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/Manage_pays/<?php echo $lang ?>/' + this.value)" >
                                             <?php
                                             if ($lang == 'fr') {
                                                 for ($i = 0; $i < count($rowpays); $i++) {
                                                     echo '<option value="' . ($rowpays[$i]['idpays']) . '">' . str_replace("''", "'", $rowpays[$i]['nompays']) . '</option>';
                                                 }
                                             } elseif ($lang == 'en') {
                                                 for ($i = 0; $i < count($rowpays); $i++) {
                                                     echo '<option value="' . ($rowpays[$i]['idpays']) . '">' . str_replace("''", "'", $rowpays[$i]['nompaysen']) . '</option>';
                                                 }
                                             }
                                             ?>
                                </select>

                            </th>
                            <th>
                                <input id="modifpays" type="text" name="modifpays" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_PAYS; ?>" style="height:20px;margin-left:20px; width: 360px;vertical-align:middle;"
                                       value="<?php
                                       if (isset($_GET['idpays'])) {
                                           echo str_replace("''", "'", $manager->getSingle2("select nompays from pays where idpays=?", $_GET['idpays']));
                                       }
                                       ?>"
                                       data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð/\'();_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >
                            </th>
                        </tr>
                        <tr>
                            <th>
                                <?php $rowsituationgeo = $manager->getList("SELECT libellesituationgeo,idsituation from situationgeographique  order by idsituation asc;"); ?>
                                <select id="libellesituationgeo" data-dojo-type="dijit.form.ComboBox"  data-dojo-props="name: 'libellesituationgeo',value: '',readonly:true,placeHolder: '<?php echo TXT_SELECTSITUATIONGEOGRAPHIQUE; ?>'"
                                        style="width: 360px;margin-left:20px;"   >
                                            <?php
                                            for ($i = 0; $i < count($rowsituationgeo); $i++) {
                                                echo '<option value="' . ($rowsituationgeo[$i]['idsituation']) . '">' . str_replace("''", "'", $rowsituationgeo[$i]['libellesituationgeo']) . '</option>';
                                            }
                                            ?>
                                </select>

                            </th>

                            <th>
                                <input id="modifpaysen" type="text" name="modifpaysen" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_PAYSEN; ?>"
                                       style="height:20px;margin-left:20px; width: 360px;vertical-align:middle;"
                                       value="<?php
                                       if (isset($_GET['idpays'])) {
                                           echo $manager->getSingle2("select nompaysen from pays where idpays=?", $_GET['idpays']);
                                       }
                                       ?>" data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð/\'();_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >
                            </th>

                        </tr>
                        <tr>
                            <th>
                                <input class="admin" style="padding-left: 21px" type="submit" name="modifPays"  value= "<?php echo TXT_MODIFIER; ?>" >
                                <input class="admin" style="padding-left: 21px" type="submit" name="ajoutepays" value="<?php echo TXT_AJOUTER; ?>" />
                            </th>
                            <th>
                                <input class="admin" style="padding-left: 20px" type="submit" name="masquepays" value="<?php echo TXT_MASQUER; ?>"/>
                                <input class="admin" style="padding-left: 20px" type="submit" name="affichepays" value="<?php echo TXT_REAFFICHER; ?>"/>
                            </th>
                        </tr>
                        <tr>
                            <th style="padding-left:22px;">
                                <?php
                                if (isset($_GET['idpays'])) {
                                    $masque = $manager->getSingle2("select masquepays from pays where idpays=?", $_GET['idpays']);
                                    infoLibelle($masque);
                                }
                                ?>
                            </th>
                        </tr>
                    </table>
                </fieldset>
                <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------
                SECTEUR ACTIVITE
                ----------------------------------------------------------------------------------------------------------------------------------------------------------------------      -->
                <fieldset id="identsa" style="border-color: #5D8BA2; margin-right: 20px;margin-left: 6px;padding-left: 20px;padding-top: 5px">
                    <legend><?php echo TXT_SECTEURACTIVITE; ?></legend>
                    <table>
                        <tr>
                            <th><input type="text"  style="height:20px;margin-left:20px;background-color: white; width: 360px;vertical-align:middle;border: 0px" data-dojo-type="dijit/form/ValidationTextBox" readonly="true" ></th>

                        <input type="text" name="idlibellesecteuractiviteactuel" style="display: none" data-dojo-type="dijit/form/ValidationTextBox" readonly="true"
                               value="<?php
                                if (isset($_GET['idsecteuractivite'])) {
                                    echo $_GET['idsecteuractivite'];
                                }
                                ?>" />
                        </tr>
                        <tr>
                            <th>
                                <?php
                                if ($lang == 'fr') {
                                    $rowsecteuractivite = $manager->getList("SELECT libellesecteuractivite,idsecteuractivite from secteuractivite  order by idsecteuractivite asc;");
                                } elseif ($lang == 'en') {
                                    $rowsecteuractivite = $manager->getList("SELECT libellesecteuractiviteen,idsecteuractivite from secteuractivite  order by idsecteuractivite asc;");
                                }
                                ?>
                                <select  id="libellesecteuractivite" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'libellesecteuractivite',value: '',required:false,placeHolder: '<?php echo TXT_SELECTSECTEUR ?>'"
                                         style="width: 360px;margin-left:20px;"    onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/Manage_sector/<?php echo $lang ?>/' + this.value)" >
                                             <?php
                                             if ($lang == 'fr') {
                                                 for ($i = 0; $i < count($rowsecteuractivite); $i++) {
                                                     echo '<option value="' . ($rowsecteuractivite[$i]['idsecteuractivite']) . '">' . str_replace("''", "'", $rowsecteuractivite[$i]['libellesecteuractivite']) . '</option>';
                                                 }
                                             } elseif ($lang == 'en') {
                                                 for ($i = 0; $i < count($rowsecteuractivite); $i++) {
                                                     echo '<option value="' . ($rowsecteuractivite[$i]['idsecteuractivite']) . '">' . str_replace("''", "'", $rowsecteuractivite[$i]['libellesecteuractiviteen']) . '</option>';
                                                 }
                                             }
                                             ?>
                                </select>

                            </th>
                            <th>
                                <input id="modifsecteuractivite" type="text" name="modifsecteuractivite" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_SECTEURACTIVITE; ?>"
                                       style="height:20px;margin-left:20px; width: 360px;vertical-align:middle;" value="<?php
                                             if (isset($_GET['idsecteuractivite'])) {
                                                 echo str_replace("''", "'", $manager->getSingle2("select libellesecteuractivite from secteuractivite where idsecteuractivite=?", $_GET['idsecteuractivite']));
                                             }
                                             ?>"
                                       data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð/\'();_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >
                            </th>
                        </tr>
                        <tr>
                            <td style="width: 360px;"></td>
                            <td>
                                <input id="modifsecteuractiviteen" style="height:20px;margin-left:20px; width: 360px;" type="text" name="modifsecteuractiviteen" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_SECTEURACTIVITEEN; ?>"
                                       value="<?php
                                       if (isset($_GET['idsecteuractivite'])) {
                                           echo $manager->getSingle2("select libellesecteuractiviteen from secteuractivite where idsecteuractivite=?", $_GET['idsecteuractivite']);
                                       }
                                             ?>"
                                       data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð/\'();_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >

                            </td>


                        </tr>
                        <tr>
                            <th>
                                <input class="admin" style="padding-left: 21px" type="submit" name="modifsecteur"  value= "<?php echo TXT_MODIFIER; ?>" >
                                <input class="admin" style="padding-left: 21px" type="submit" name="ajoutesecteuractivite" value="<?php echo TXT_AJOUTER; ?>" />
                            </th>
                            <th>
                                <input class="admin" style="padding-left: 20px" type="submit" name="masquesecteuractivite" value="<?php echo TXT_MASQUER; ?>"/>
                                <input class="admin" style="padding-left: 20px" type="submit" name="affichesecteuractivite" value="<?php echo TXT_REAFFICHER; ?>"/>
                            </th>
                        </tr>
                        <tr>
                            <th style="padding-left:22px;">
                                <?php
                                if (isset($_GET['idsecteuractivite'])) {
                                    $masque = $manager->getSingle2("select masquesecteuractivite from secteuractivite where idsecteuractivite=?", $_GET['idsecteuractivite']);
                                    infoLibelle($masque);
                                }
                                ?>
                            </th>
                        </tr>
                    </table>
                </fieldset>
                <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------
                TYPE ENTREPRISE
                ----------------------------------------------------------------------------------------------------------------------------------------------------------------------      -->

                <fieldset id="identte" style="border-color: #5D8BA2; margin-right: 20px;margin-left: 6px;padding-left: 20px;padding-top: 5px">
                    <legend><?php echo TXT_TYPEENTREPRISE; ?></legend>
                    <table>
                        <tr>
                            <th><input type="text"  style="height:20px;margin-left:20px;background-color: white; width: 360px;vertical-align:middle;border: 0px" data-dojo-type="dijit/form/ValidationTextBox" readonly="true" ></th>
                        <input type="text" name="idtypeentrepriseactuel" style="display: none" data-dojo-type="dijit/form/ValidationTextBox" readonly="true"
                               value="<?php
                                if (isset($_GET['idtypeentreprise'])) {
                                    echo $_GET['idtypeentreprise'];
                                }
                                ?>" />
                        </tr>
                        <tr>
                            <th>
                                <?php
                                if ($lang == 'fr') {
                                    $rowtypeentreprise = $manager->getList("SELECT libelletypeentreprise,idtypeentreprise from typeentreprise  order by idtypeentreprise asc;");
                                } elseif ($lang == 'en') {
                                    $rowtypeentreprise = $manager->getList("SELECT libelletypeentrepriseen,idtypeentreprise from typeentreprise  order by idtypeentreprise asc;");
                                }
                                ?>
                                <select  id="libelletypeentreprise" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'libelletypeentreprise',value: '',required:false,placeHolder: '<?php echo TXT_SELECTTYPEENTREPRISE; ?>'"
                                         style="width: 360px;margin-left:20px;"    onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/Manage_typeent/<?php echo $lang ?>/' + this.value)" >
                                             <?php
                                             if ($lang == 'fr') {
                                                 for ($i = 0; $i < count($rowtypeentreprise); $i++) {
                                                     echo '<option value="' . ($rowtypeentreprise[$i]['idtypeentreprise']) . '">' . str_replace("''", "'", $rowtypeentreprise[$i]['libelletypeentreprise']) . '</option>';
                                                 }
                                             } elseif ($lang == 'en') {
                                                 for ($i = 0; $i < count($rowtypeentreprise); $i++) {
                                                     echo '<option value="' . ($rowtypeentreprise[$i]['idtypeentreprise']) . '">' . str_replace("''", "'", $rowtypeentreprise[$i]['libelletypeentrepriseen']) . '</option>';
                                                 }
                                             }
                                             ?>
                                </select>

                            </th>
                            <th>
                                <input id="modiftypeentreprise" type="text" name="modiftypeentreprise" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_TYPEENTREPRISE; ?>"
                                       style="height:20px;margin-left:20px; width: 360px;vertical-align:middle;"
                                       value="<?php
                                             if (isset($_GET['idtypeentreprise'])) {
                                                 echo str_replace("''", "'", $manager->getSingle2("select libelletypeentreprise from typeentreprise where idtypeentreprise=?", $_GET['idtypeentreprise']));
                                             }
                                             ?>" data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð/\'();_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >
                            </th>
                        </tr>
                        <tr>
                            <td style="width: 360px;"></td>
                            <th>
                                <input id="modiftypeentrepriseen" type="text" name="modiftypeentrepriseen" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_TYPEENTREPRISEEN; ?>"
                                       style="height:20px;margin-left:20px; width: 360px;vertical-align:middle;" value="<?php
                                       if (isset($_GET['idtypeentreprise'])) {
                                           echo $manager->getSingle2("select libelletypeentrepriseen from typeentreprise where idtypeentreprise=?", $_GET['idtypeentreprise']);
                                       }
                                             ?>" data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð/\'();_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >
                            </th>
                        </tr>
                        <tr>
                            <th>
                                <input class="admin" style="padding-left: 21px" type="submit" name="modifTypeentreprise"  value= "<?php echo TXT_MODIFIER; ?>" >
                                <input class="admin" style="padding-left: 21px" type="submit" name="ajoutetypeentreprise" value="<?php echo TXT_AJOUTER; ?>" />
                            </th>
                            <th>
                                <input class="admin" style="padding-left: 20px" type="submit" name="masquetypeentreprise" value="<?php echo TXT_MASQUER; ?>"/>
                                <input class="admin" style="padding-left: 20px" type="submit" name="affichetypeentreprise" value="<?php echo TXT_REAFFICHER; ?>"/>
                            </th>
                        </tr>
                        <tr>
                            <th style="padding-left:22px;">
                                <?php
                                if (isset($_GET['idtypeentreprise'])) {
                                    $masque = $manager->getSingle2("select masquetypeentreprise from typeentreprise where idtypeentreprise=?", $_GET['idtypeentreprise']);
                                    infoLibelle($masque);
                                }
                                ?>
                            </th>
                        </tr>
                    </table>
                </fieldset>
                <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------
                DISCIPLINE SCIENTIFIQUE
                ----------------------------------------------------------------------------------------------------------------------------------------------------------------------      -->

                <fieldset id="identds" style="border-color: #5D8BA2; margin-right: 20px;margin-left: 6px;padding-left: 20px;padding-top: 5px">
                    <legend><?php echo TXT_DISCIPLINESCIENTIFIQUE; ?></legend>
                    <table>
                        <tr>
                            <th><input type="text"  style="height:20px;margin-left:20px;background-color: white; width: 360px;vertical-align:middle;border: 0px" data-dojo-type="dijit/form/ValidationTextBox" readonly="true" ></th>
                        <input type="text" name="iddisciplineactuel" style="display: none" data-dojo-type="dijit/form/ValidationTextBox" readonly="true"
                               value="<?php
                                if (isset($_GET['iddiscipline'])) {
                                    echo $_GET['iddiscipline'];
                                }
                                ?>" />
                        </tr>
                        <tr>
                            <th>
                                <?php
                                if ($lang == 'fr') {
                                    $rowdiscipline = $manager->getList("SELECT libellediscipline,iddiscipline from disciplinescientifique  order by iddiscipline asc;");
                                } elseif ($lang == 'en') {
                                    $rowdiscipline = $manager->getList("SELECT libelledisciplineen,iddiscipline from disciplinescientifique  order by iddiscipline asc;");
                                }
                                ?>
                                <select  id="libellediscipline" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'libellediscipline',value: '',required:false,placeHolder: '<?php echo TXT_SELECTDISCIPLINE; ?>'"
                                         style="width: 360px;margin-left:20px;"    onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/Manage_discipline/<?php echo $lang ?>/' + this.value)" >
                                             <?php
                                             if ($lang == 'fr') {
                                                 for ($i = 0; $i < count($rowdiscipline); $i++) {
                                                     echo '<option value="' . ($rowdiscipline[$i]['iddiscipline']) . '">' . str_replace("''", "'", $rowdiscipline[$i]['libellediscipline']) . '</option>';
                                                 }
                                             } elseif ($lang == 'en') {
                                                 for ($i = 0; $i < count($rowdiscipline); $i++) {
                                                     echo '<option value="' . ($rowdiscipline[$i]['iddiscipline']) . '">' . str_replace("''", "'", $rowdiscipline[$i]['libelledisciplineen']) . '</option>';
                                                 }
                                             }
                                             ?>
                                </select>

                            </th>
                            <th>
                                <input id="modifdiscipline" type="text" name="modifdiscipline" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_DISCIPLINESCIENTIFIQUE; ?>"
                                       style="height:20px;margin-left:20px; width: 360px;vertical-align:middle;"
                                       value="<?php
                                             if (isset($_GET['iddiscipline'])) {
                                                 echo str_replace("''", "'", $manager->getSingle2("select libellediscipline from disciplinescientifique where iddiscipline=?", $_GET['iddiscipline']));
                                             }
                                             ?>" data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð/\'();_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >
                            </th>
                        </tr>
                        <tr>
                            <td style="width: 360px;"></td>
                            <th>
                                <input id="modifdisciplineen" type="text" name="modifdisciplineen" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_DISCIPLINESCIENTIFIQUEEN; ?>"
                                       style="height:20px;margin-left:20px; width: 360px;vertical-align:middle;" value="<?php
                                       if (isset($_GET['iddiscipline'])) {
                                           echo $manager->getSingle2("select libelledisciplineen from disciplinescientifique where iddiscipline=?", $_GET['iddiscipline']);
                                       }
                                             ?>" data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð/\'();_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >
                            </th>
                        </tr>
                        <tr>
                            <th>
                                <input class="admin" style="padding-left: 21px" type="submit" name="modifDiscipline"  value= "<?php echo TXT_MODIFIER; ?>" >
                                <input class="admin" style="padding-left: 21px" type="submit" name="ajoutediscipline" value="<?php echo TXT_AJOUTER; ?>" />
                            </th>
                            <th>
                                <input class="admin" style="padding-left: 20px" type="submit" name="masquediscipline" value="<?php echo TXT_MASQUER; ?>"/>
                                <input class="admin" style="padding-left: 20px" type="submit" name="affichediscipline" value="<?php echo TXT_REAFFICHER; ?>"/>
                            </th>
                        </tr>
                        <tr>
                            <th style="padding-left:22px;">
                                <?php
                                if (isset($_GET['iddiscipline'])) {
                                    $masque = $manager->getSingle2("select masquediscipline from disciplinescientifique where iddiscipline=?", $_GET['iddiscipline']);
                                    infoLibelle($masque);
                                }
                                ?>
                            </th>
                        </tr>
                    </table>
                </fieldset>
                <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------
                NOM DE L'EMPLOYEUR
                ----------------------------------------------------------------------------------------------------------------------------------------------------------------------      -->
                <fieldset id="identne" style="border-color: #5D8BA2; margin-right: 20px;margin-left: 6px;padding-left: 20px;padding-top: 5px">
                    <legend><?php echo TXT_NOMEMPLOYEUR; ?></legend>
                    <table>
                        <tr>
                            <th><input type="text"  style="height:20px;margin-left:20px;background-color: white; width: 360px;vertical-align:middle;border: 0px" data-dojo-type="dijit/form/ValidationTextBox" readonly="true" ></th>
                        <input type="text" name="idnomemployeuractuel" style="display: none" data-dojo-type="dijit/form/ValidationTextBox" readonly="true"
                               value="<?php
                                if (isset($_GET['idemployeur'])) {
                                    echo $_GET['idemployeur'];
                                }
                                ?>" />
                        </tr>
                        <tr>
                            <th>
                                <?php
                                if ($lang == 'fr') {
                                    $rownomemployeur = $manager->getList("SELECT libelleemployeur,idemployeur from nomemployeur order by idemployeur asc;");
                                } elseif ($lang == 'en') {
                                    $rownomemployeur = $manager->getList("SELECT libelleemployeuren,idemployeur from nomemployeur order by idemployeur asc;");
                                }
                                ?>
                                <select  id="libellenomemployeur" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'libellenomemployeur',value: '',required:false,placeHolder: '<?php echo TXT_SELECTEMPLOYEUR; ?>'"
                                         style="width: 360px;margin-left:20px;"    onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/Manage_employeur/<?php echo $lang ?>/' + this.value)" >
                                             <?php
                                             if ($lang == 'fr') {
                                                 for ($i = 0; $i < count($rownomemployeur); $i++) {
                                                     echo '<option value="' . ($rownomemployeur[$i]['idemployeur']) . '">' . str_replace("''", "'", $rownomemployeur[$i]['libelleemployeur']) . '</option>';
                                                 }
                                             } elseif ($lang == 'en') {
                                                 for ($i = 0; $i < count($rownomemployeur); $i++) {
                                                     echo '<option value="' . ($rownomemployeur[$i]['idemployeur']) . '">' . str_replace("''", "'", $rownomemployeur[$i]['libelleemployeuren']) . '</option>';
                                                 }
                                             }
                                             ?>
                                </select>

                            </th>
                            <th>
                                <input id="modifnomemployeur" type="text" name="modifnomemployeur" class="long" data-dojo-type="dijit/form/ValidationTextBox"
                                       placeholder="<?php echo TXT_NOMEMPLOYEUR; ?>" style="height:20px;margin-left:20px; width: 360px;vertical-align:middle;"
                                       value="<?php
                                             if (isset($_GET['idemployeur'])) {
                                                 echo str_replace("''", "'", $manager->getSingle2("select libelleemployeur from nomemployeur where idemployeur=?", $_GET['idemployeur']));
                                             }
                                             ?>"
                                       data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð/\'();_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >
                            </th>
                        </tr>
                        <tr>
                            <td>
                            <th>
                                <input id="modifnomemployeuren" type="text" name="modifnomemployeuren" class="long" data-dojo-type="dijit/form/ValidationTextBox"
                                       placeholder="<?php echo TXT_NOMEMPLOYEUREN; ?>" style="height:20px;margin-left:20px; width: 360px;vertical-align:middle;"
                                       value="<?php
                                       if (isset($_GET['idemployeur'])) {
                                           echo $manager->getSingle2("select libelleemployeuren from nomemployeur where idemployeur=?", $_GET['idemployeur']);
                                       }
                                             ?>" data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð/\'();_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >
                            </th>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <input class="admin" style="padding-left: 21px" type="submit" name="modifNomemployeur"  value= "<?php echo TXT_MODIFIER; ?>" >
                                <input class="admin" style="padding-left: 21px" type="submit" name="ajoutenomemployeur" value="<?php echo TXT_AJOUTER; ?>" />
                            </th>
                            <th>
                                <input class="admin" style="padding-left: 20px" type="submit" name="masqueNomemployeur" value="<?php echo TXT_MASQUER; ?>"/>
                                <input class="admin" style="padding-left: 20px" type="submit" name="afficheNomemployeur" value="<?php echo TXT_REAFFICHER; ?>"/>
                            </th>
                        </tr>
                        <tr>
                            <th style="padding-left:22px;">
                                <?php
                                if (isset($_GET['idemployeur'])) {
                                    $masque = $manager->getSingle2("select masquenomemployeur from nomemployeur where idemployeur=?", $_GET['idemployeur']);
                                    infoLibelle($masque);
                                }
                                ?>
                            </th>
                        </tr>
                    </table>
                </fieldset>
                <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------
                TUTELLE
                ----------------------------------------------------------------------------------------------------------------------------------------------------------------------      -->
                <fieldset id="identtu" style="border-color: #5D8BA2; margin-right: 20px;margin-left: 6px;padding-left: 20px;padding-top: 5px">
                    <legend><?php echo TXT_TUTELLE; ?></legend>
                    <table>
                        <tr>
                            <th><input type="text"  style="height:20px;margin-left:20px;background-color: white; width: 360px;vertical-align:middle;border: 0px" data-dojo-type="dijit/form/ValidationTextBox" readonly="true" ></th>
                        <input type="text" name="idtutelleactuel" style="display: none" data-dojo-type="dijit/form/ValidationTextBox" readonly="true"
                               value="<?php
                                if (isset($_GET['idtutelle'])) {
                                    echo $_GET['idtutelle'];
                                }
                                ?>" />
                        </tr>
                        <tr>
                            <th>
                                <?php
                                if ($lang == 'fr') {
                                    $rowtutelle = $manager->getList("SELECT libelletutelle,idtutelle from tutelle  order by idtutelle asc;");
                                } elseif ($lang == 'en') {
                                    $rowtutelle = $manager->getList("SELECT libelletutelleen,idtutelle from tutelle  order by idtutelle asc;");
                                }
                                ?>
                                <select  id="libelletutelle" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'libelletutelle',value: '',required:false,placeHolder: '<?php echo TXT_SELECTTUTELLE; ?>'"
                                         style="width: 360px;margin-left:20px;"    onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/Manage_tutelle/<?php echo $lang ?>/' + this.value)" >
                                             <?php
                                             if ($lang == 'fr') {
                                                 for ($i = 0; $i < count($rowtutelle); $i++) {
                                                     echo '<option value="' . ($rowtutelle[$i]['idtutelle']) . '">' . str_replace("''", "'", $rowtutelle[$i]['libelletutelle']) . '</option>';
                                                 }
                                             } elseif ($lang == 'en') {
                                                 for ($i = 0; $i < count($rowtutelle); $i++) {
                                                     echo '<option value="' . ($rowtutelle[$i]['idtutelle']) . '">' . str_replace("''", "'", $rowtutelle[$i]['libelletutelleen']) . '</option>';
                                                 }
                                             }
                                             ?>
                                </select>

                            </th>
                            <th>
                                <input id="modiftutelle" type="text" name="modiftutelle" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_TUTELLE; ?>"
                                       style="height:20px;margin-left:20px; width: 360px;vertical-align:middle;"
                                       value="<?php
                                             if (isset($_GET['idtutelle'])) {
                                                 echo str_replace("''", "'", $manager->getSingle2("select libelletutelle from tutelle where idtutelle=?", $_GET['idtutelle']));
                                             }
                                             ?>" data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð/\'();_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >
                            </th>
                        </tr>
                        <tr>
                            <td style="width: 360px;"></td>
                            <th>
                                <input id="modiftutelleen" type="text" name="modiftutelleen" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_TUTELLEEN; ?>"
                                       style="height:20px;margin-left:20px; width: 360px;vertical-align:middle;" value="<?php
                                       if (isset($_GET['idtutelle'])) {
                                           echo $manager->getSingle2("select libelletutelleen from tutelle where idtutelle=?", $_GET['idtutelle']);
                                       }
                                             ?>" data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð/\'();_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >
                            </th>
                        </tr>
                        <tr>
                            <th>
                                <input class="admin" style="padding-left: 21px" type="submit" name="modifTutelle"  value= "<?php echo TXT_MODIFIER; ?>" >
                                <input class="admin" style="padding-left: 21px" type="submit" name="ajoutetutelle" value="<?php echo TXT_AJOUTER; ?>" />
                            </th>
                            <th>
                                <input class="admin" style="padding-left: 20px" type="submit" name="masquetutelle" value="<?php echo TXT_MASQUER; ?>"/>
                                <input class="admin" style="padding-left: 20px" type="submit" name="affichetutelle" value="<?php echo TXT_REAFFICHER; ?>"/>
                            </th>
                        </tr>
                        <tr>
                            <th style="padding-left:22px;">
                                <?php
                                if (isset($_GET['idtutelle'])) {
                                    $masque = $manager->getSingle2("select masquetutelle from tutelle where idtutelle=?", $_GET['idtutelle']);
                                    infoLibelle($masque);
                                }
                                ?>
                            </th>
                        </tr>
                    </table>
                </fieldset>
                <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------
                TYPE DE PROJET
                ----------------------------------------------------------------------------------------------------------------------------------------------------------------------      -->
                <fieldset id="identtp" style="border-color: #5D8BA2; margin-right: 20px;margin-left: 6px;padding-left: 20px;padding-top: 5px">
                    <legend><?php echo TXT_TYPEPROJET; ?></legend>
                    <table>
                        <tr>
                            <th><input type="text"  style="height:20px;margin-left:20px;background-color: white; width: 360px;vertical-align:middle;border: 0px" data-dojo-type="dijit/form/ValidationTextBox" readonly="true" ></th>
                        <input type="text" name="idtypeprojetactuel" style="display: none" data-dojo-type="dijit/form/ValidationTextBox" readonly="true"
                               value="<?php
                                if (isset($_GET['idtypeprojet'])) {
                                    echo $_GET['idtypeprojet'];
                                }
                                ?>" />
                        </tr>
                        <tr>
                            <th>
                                <?php
                                if ($lang == 'fr') {
                                    $rowtypeprojet = $manager->getList("SELECT libelletype,idtypeprojet from typeprojet  order by idtypeprojet asc;");
                                } elseif ($lang == 'en') {
                                    $rowtypeprojet = $manager->getList("SELECT libelletypeen,idtypeprojet from typeprojet  order by idtypeprojet asc;");
                                }
                                ?>
                                <select  id="libelletypeprojet" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: id='libelletypeprojet',value: '',required:false,placeHolder: '<?php echo TXT_SELECTDISCIPLINE; ?>'"
                                         style="width: 360px;margin-left:20px;"    onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/Manage_typeprojet/<?php echo $lang ?>/' + this.value)" >
                                             <?php
                                             if ($lang == 'fr') {
                                                 for ($i = 0; $i < count($rowtypeprojet); $i++) {
                                                     echo '<option value="' . ($rowtypeprojet[$i]['idtypeprojet']) . '">' . str_replace("''", "'", $rowtypeprojet[$i]['libelletype']) . '</option>';
                                                 }
                                             } elseif ($lang == 'en') {
                                                 for ($i = 0; $i < count($rowtypeprojet); $i++) {
                                                     echo '<option value="' . ($rowtypeprojet[$i]['idtypeprojet']) . '">' . str_replace("''", "'", $rowtypeprojet[$i]['libelletypeen']) . '</option>';
                                                 }
                                             }
                                             ?>
                                </select>

                            </th>
                            <th>
                                <input id="modiftypeprojet" type="text" name="modiftypeprojet" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_TYPEPROJET; ?>"
                                       style="height:20px;margin-left:20px; width: 360px;vertical-align:middle;"
                                       value="<?php
                                             if (isset($_GET['idtypeprojet'])) {
                                                 echo str_replace("''", "'", $manager->getSingle2("select libelletype from typeprojet where idtypeprojet=?", $_GET['idtypeprojet']));
                                             }
                                             ?>" data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð/\'();_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >
                            </th>
                        </tr>
                        <tr>
                            <td style="width: 360px;"></td>
                            <th>
                                <input id="modiftypeprojeten" type="text" name="modiftypeprojeten" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_TYPEPROJETEN; ?>"
                                       style="height:20px;margin-left:20px; width: 360px;vertical-align:middle;" value="<?php
                                       if (isset($_GET['idtypeprojet'])) {
                                           echo $manager->getSingle2("select libelletypeen from typeprojet where idtypeprojet=?", $_GET['idtypeprojet']);
                                       }
                                             ?>" data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð/\'();_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >
                            </th>
                        </tr>
                        <tr>
                            <th>
                                <input class="admin" style="padding-left: 21px" type="submit" name="modifTypeprojet"  value= "<?php echo TXT_MODIFIER; ?>" >
                                <input class="admin" style="padding-left: 21px" type="submit" name="ajoutetypeprojet" value="<?php echo TXT_AJOUTER; ?>" />
                            </th>
                            <th>
                                <input class="admin" style="padding-left: 20px" type="submit" name="masquetypeprojet" value="<?php echo TXT_MASQUER; ?>"/>
                                <input class="admin" style="padding-left: 20px" type="submit" name="affichetypeprojet" value="<?php echo TXT_REAFFICHER; ?>"/>
                            </th>
                        </tr>
                        <tr>
                            <th style="padding-left:22px;">
                                <?php
                                if (isset($_GET['idtypeprojet'])) {
                                    $masque = $manager->getSingle2("select masquetypeprojet from typeprojet where idtypeprojet=?", $_GET['idtypeprojet']);
                                    infoLibelle($masque);
                                }
                                ?>
                            </th>
                        </tr>
                    </table>
                </fieldset>
                <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------
                TYPE DE FORMATION                                                               ///////////////////////////////////////////////////////////////////////////////////////
                ----------------------------------------------------------------------------------------------------------------------------------------------------------------------      -->
                <fieldset id="identtf" style="border-color: #5D8BA2; margin-right: 20px;margin-left: 6px;padding-left: 20px;padding-top: 5px">
                    <legend><?php echo TXT_TYPEFORMATION; ?></legend>
                    <table>
                        <tr>
                            <th><input type="text"  style="height:20px;margin-left:20px;background-color: white; width: 360px;vertical-align:middle;border: 0px" data-dojo-type="dijit/form/ValidationTextBox" readonly="true" ></th>
                        <input type="text" name="idtypeformationactuel" style="display: none" data-dojo-type="dijit/form/ValidationTextBox" readonly="true"
                               value="<?php
                                if (isset($_GET['idtypeformation'])) {
                                    echo $_GET['idtypeformation'];
                                }
                                ?>" />
                        </tr>
                        <tr>
                            <th>
                                <?php
                                if ($lang == 'fr') {
                                    $rowtypeformation = $manager->getList("SELECT libelletypeformation,idtypeformation from typeformation  order by idtypeformation asc;");
                                } elseif ($lang == 'en') {
                                    $rowtypeformation = $manager->getList("SELECT libelletypeformationen,idtypeformation from typeformation  order by idtypeformation asc;");
                                }
                                ?>
                                <select  id="libelletypeformation" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: id='libelletypeformation',value: '',required:false,placeHolder: '<?php echo TXT_SELECTTYPEFORMATION; ?>'"
                                         style="width: 360px;margin-left:20px;"    onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/Manage_typeformation/<?php echo $lang ?>/' + this.value)" >
                                             <?php
                                             if ($lang == 'fr') {
                                                 for ($i = 0; $i < count($rowtypeformation); $i++) {
                                                     echo '<option value="' . ($rowtypeformation[$i]['idtypeformation']) . '">' . str_replace("''", "'", $rowtypeformation[$i]['libelletypeformation']) . '</option>';
                                                 }
                                             } elseif ($lang == 'en') {
                                                 for ($i = 0; $i < count($rowtypeformation); $i++) {
                                                     echo '<option value="' . ($rowtypeformation[$i]['idtypeformation']) . '">' . str_replace("''", "'", $rowtypeformation[$i]['libelletypeformationen']) . '</option>';
                                                 }
                                             }
                                             ?>
                                </select>

                            </th>
                            <th>
                                <input id="modiftypeformation" type="text" name="modiftypeformation" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_TYPEFORMATION; ?>"
                                       style="height:20px;margin-left:20px; width: 360px;vertical-align:middle;"
                                       value="<?php
                                             if (isset($_GET['idtypeformation'])) {
                                                 echo str_replace("''", "'", $manager->getSingle2("select libelletypeformation from typeformation where idtypeformation=?", $_GET['idtypeformation']));
                                             }
                                             ?>" data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð/\'();_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >
                            </th>
                        </tr>
                        <tr>
                            <td style="width: 360px;"></td>
                            <th>
                                <input id="modiftypeformationen" type="text" name="modiftypeformationen" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_TYPEFORMATION; ?>"
                                       style="height:20px;margin-left:20px; width: 360px;vertical-align:middle;" value="<?php
                                       if (isset($_GET['idtypeformation'])) {
                                           echo $manager->getSingle2("select libelletypeformationen from typeformation where idtypeformation=?", $_GET['idtypeformation']);
                                       }
                                             ?>" data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð/\'();_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >
                            </th>
                        </tr>
                        <tr>
                            <th>
                                <input class="admin" style="padding-left: 21px" type="submit" name="modifTypeformation"  value= "<?php echo TXT_MODIFIER; ?>" >
                                <input class="admin" style="padding-left: 21px" type="submit" name="ajoutetypeformation" value="<?php echo TXT_AJOUTER; ?>" />
                            </th>
                            <th>
                                <input class="admin" style="padding-left: 20px" type="submit" name="masquetypeformation" value="<?php echo TXT_MASQUER; ?>"/>
                                <input class="admin" style="padding-left: 20px" type="submit" name="affichetypeformation" value="<?php echo TXT_REAFFICHER; ?>"/>
                            </th>
                        </tr>
                        <tr>
                            <th style="padding-left:22px;">
                                <?php
                                if (isset($_GET['idtypeformation'])) {
                                    $masque = $manager->getSingle2("select masquetypeformation from typeformation where idtypeformation=?", $_GET['idtypeformation']);
                                    infoLibelle($masque);
                                }
                                ?>
                            </th>
                        </tr>
                    </table>
                </fieldset>
                <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------
                THEMATIQUE
                ----------------------------------------------------------------------------------------------------------------------------------------------------------------------      -->
                <fieldset id="identth" style="border-color: #5D8BA2; margin-right: 20px;margin-left: 6px;padding-left: 20px;padding-top: 5px">
                    <legend><?php echo TXT_THEMATIQUE; ?></legend>
                    <table>
                        <tr>
                            <th><input type="text"  style="height:20px;margin-left:20px;background-color: white; width: 360px;vertical-align:middle;border: 0px" data-dojo-type="dijit/form/ValidationTextBox" readonly="true" ></th>
                        <input type="text" name="idthematiqueactuel" style="display: none" data-dojo-type="dijit/form/ValidationTextBox" readonly="true"
                               value="<?php
                                if (isset($_GET['idthematique'])) {
                                    echo $_GET['idthematique'];
                                }
                                ?>" />
                        </tr>
                        <tr>
                            <th>
                                <?php
                                if ($lang == 'fr') {
                                    $rowthematique = $manager->getList("SELECT libellethematique,idthematique from thematique  order by idthematique asc;");
                                } elseif ($lang == 'en') {
                                    $rowthematique = $manager->getList("SELECT libellethematiqueen,idthematique from thematique  order by idthematique asc;");
                                }
                                ?>
                                <select  id="idthematique" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'idthematique',value: '',required:false,placeHolder: '<?php echo TXT_SELECTTHEMATIQUE; ?>'"
                                         style="width: 360px;margin-left:20px;"    onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/Manage_thematique/<?php echo $lang ?>/' + this.value)" >
                                             <?php
                                             if ($lang == 'fr') {
                                                 for ($i = 0; $i < count($rowthematique); $i++) {
                                                     echo '<option value="' . ($rowthematique[$i]['idthematique']) . '">' . str_replace("''", "'", $rowthematique[$i]['libellethematique']) . '</option>';
                                                 }
                                             } elseif ($lang == 'en') {
                                                 for ($i = 0; $i < count($rowthematique); $i++) {
                                                     echo '<option value="' . ($rowthematique[$i]['idthematique']) . '">' . str_replace("''", "'", $rowthematique[$i]['libellethematiqueen']) . '</option>';
                                                 }
                                             }
                                             ?>
                                </select>

                            </th>
                            <th>
                                <input id="modifthematique" type="text" name="modifthematique" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_THEMATIQUE; ?>"
                                       style="height:20px;margin-left:20px; width: 360px;vertical-align:middle;"
                                       value="<?php
                                             if (isset($_GET['idthematique'])) {
                                                 echo str_replace("''", "'", $manager->getSingle2("select libellethematique from thematique where idthematique=?", $_GET['idthematique']));
                                             }
                                             ?>" data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð/\'();_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >
                            </th>
                        </tr>
                        <tr>
                            <td style="width: 360px;"></td>
                            <th>
                                <input id="modifthematiqueen" type="text" name="modifthematiqueen" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_THEMATIQUEEN; ?>"
                                       style="height:20px;margin-left:20px; width: 360px;vertical-align:middle;"
                                       value="<?php
                                       if (isset($_GET['idthematique'])) {
                                           echo $manager->getSingle2("select libellethematiqueen from thematique where idthematique=?", $_GET['idthematique']);
                                       }
                                             ?>" data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð/\'();_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >
                            </th>
                        </tr>
                        <tr>
                            <th>
                                <input class="admin" style="padding-left: 21px" type="submit" name="modifThematique"  value= "<?php echo TXT_MODIFIER; ?>" >
                                <input class="admin" style="padding-left: 21px" type="submit" name="ajoutethematique" value="<?php echo TXT_AJOUTER; ?>" />
                            </th>
                            <th>
                                <input class="admin" style="padding-left: 20px" type="submit" name="masquethematique" value="<?php echo TXT_MASQUER; ?>"/>
                                <input class="admin" style="padding-left: 20px" type="submit" name="affichethematique" value="<?php echo TXT_REAFFICHER; ?>"/>
                            </th>
                        </tr>
                        <tr>
                            <th style="padding-left:22px;">
                                <?php
                                if (isset($_GET['idthematique'])) {
                                    $masque = $manager->getSingle2("select masquethematique from thematique where idthematique=?", $_GET['idthematique']);
                                    infoLibelle($masque);
                                }
                                ?>
                            </th>
                        </tr>
                    </table>
                </fieldset>

            </form>
        </div>
        <?php
        $ongletcase = false;
        if (isset($_GET['libellecentralenom'])) {
            $ongletcase = true;
        } elseif (isset($_GET['msgserveurcentralehide'])) {
            $ongletcase = true;
        } elseif (isset($_GET['msgserveurtypecentraleshow'])) {
            $ongletcase = true;
        } elseif (isset($_GET['libecentrale'])) {
            $ongletcase = true;
        } elseif (isset($_GET['msgVilleupdate'])) {
            $ongletcase = true;
        } elseif (isset($_GET['libellecentrale'])) {
            $ongletcase = true;
        } elseif (isset($_GET['msgEmailcentralupdate'])) {
            $ongletcase = true;
        } elseif (isset($_GET['libcentrale'])) {
            $ongletcase = true;
        } elseif (isset($_GET['libcentral'])) {
            $ongletcase = true;
        } elseif (isset($_GET['msgCodeunitecentralupdate'])) {
            $ongletcase = true;
        } elseif (isset($_GET['idressource'])) {
            $ongletcase = true;
        } elseif (isset($_GET['msgRessourceupdate'])) {
            $ongletcase = true;
        } elseif (isset($_GET['msginsertcentrale'])) {
            $ongletcase = true;
        } elseif (isset($_GET['msgserveurressource'])) {
            $ongletcase = true;
        } elseif (isset($_GET['msgserveurressourcehide'])) {
            $ongletcase = true;
        } elseif (isset($_GET['msgserveursourcefinancement'])) {
            $ongletcase = true;
        } elseif (isset($_GET['msgErrsourcefinancementnonsaisie'])) {
            $ongletcase = true;
        } elseif (isset($_GET['msgErrCentupdate'])) {
            $ongletcase = true;
        } elseif (isset($_GET ['msgserveursourcefinancementhide'])) {
            $ongletcase = true;
        } elseif (isset($_GET['msgserveurtypesourcefinancementshow'])) {
            $ongletcase = true;
        } elseif (isset($_GET['idsourcefinancement'])) {
            $ongletcase = true;
        } elseif (isset($_GET['msgsourcefinancementupdate'])) {
            $ongletcase = true;
        } elseif (isset($_GET['msgserveurressourceshow'])) {
            $ongletcase = true;
        } elseif (isset($_GET['libecentral'])) {
            $ongletcase = true;
        } elseif (isset($_GET['msgErrCentrale'])) {
            $ongletcase = true;
        }
        if ($ongletcase == true) {
            ?>
            <div data-dojo-type="dijit/layout/ContentPane" title="  <?php echo '--- ' . TXT_ONGLETADMINCASE . ' ---'; ?> " style="width: 1050px; height: auto;"  data-dojo-props="selected:true">
            <?php } else { ?>
                <div data-dojo-type="dijit/layout/ContentPane" title="  <?php echo '--- ' . TXT_ONGLETADMINCASE . ' ---'; ?> " style="width: 1050px; height: auto;" >
                <?php } ?>
                <form id='admincase' name='admincase' method="post" action ="<?php echo '/' . REPERTOIRE . '/'; ?>controler/ctrladmin.php?lang=<?php echo $lang; ?>" data-dojo-type="dijit/form/Form" >
                    <fieldset id="identac" style="border-color: #5D8BA2; margin-right: 20px;margin-left: 6px;padding-left: 20px;padding-top: 5px">
                        <legend><?php echo TXT_NOM . ' - ' . TXT_CENTRALE . ''; ?></legend>
                        <table>
                            <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------
                            NOM CENTRALE
------------------------------------------------------------------------------------------------------------------------------------------------------------------------>
                            <tr>
                                <th><input type="text"  style="height:20px;margin-left:20px;background-color: white; width: 360px;vertical-align:middle;border: 0px" data-dojo-type="dijit/form/ValidationTextBox" readonly="true" ></th>
                            <input type="text" name="idcentralactuelnom" style="display: none" data-dojo-type="dijit/form/ValidationTextBox" readonly="true"
                                   value="<?php
                                   if (isset($_GET['libellecentralenom'])) {
                                       echo $manager->getSingle2("select idcentrale from centrale where libellecentrale=?", stripslashes(pg_escape_string($_GET['libellecentralenom'])));
                                   }
                                   ?>" />
                            </tr>
                            <tr>
                                <th>
                                    <?php $rowlibellecentralenom = $manager->getList("SELECT libellecentrale,idcentrale FROM centrale where libellecentrale !='Autres' order by idcentrale asc ;"); ?>
                                    <select  id="libecentralenom" data-dojo-type="dijit.form.ComboBox"  data-dojo-props="name: 'libcentralecodeunite',value: '',placeHolder: '<?php echo TXT_SELECTCENTRALE; ?>'"
                                             style="width: 360px;margin-left:20px;"    onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/Manage_nom_centrale/<?php echo $lang ?>/' + this.value)" >
                                                 <?php
                                                 for ($i = 0; $i < count($rowlibellecentralenom); $i++) {
                                                     echo '<option value="' . ($rowlibellecentralenom[$i]['idcentrale']) . '">' . str_replace("''", "'", $rowlibellecentralenom[$i]['libellecentrale']) . '</option>';
                                                 }
                                                 ?>
                                    </select>
                                </th>
                                <th>
                                    <input id="modifcentralenom" type="text" name="modifcentralenom" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_NOM . ' - ' . TXT_CENTRALE; ?>"
                                           style="height:20px;margin-left:20px; width: 360px;vertical-align:middle;"
                                           value="<?php
                                           if (isset($_GET['libellecentralenom'])) {
                                               echo stripslashes($_GET['libellecentralenom']);
                                           }
                                           ?>"
                                           data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð\()_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >
                                </th>
                            </tr>
                            <tr>
                                <th>
                                    <input class="admin" style="padding-left: 22px" type="submit" name="modifnomcentrale" value="<?php echo TXT_MODIFIER; ?>"/>
                                    <input class="admin" style="padding-left: 22px" type="submit" name="ajoutenomcentrale" value="<?php echo TXT_AJOUTER; ?>"/>
                                </th>
                                <th>
                                    <input class="admin" style="padding-left: 20px" type="submit" name="masquenomcentrale" value="<?php echo TXT_MASQUER; ?>"/>
                                    <input class="admin" style="padding-left: 20px" type="submit" name="affichenomcentrale" value="<?php echo TXT_REAFFICHER; ?>"/>
                                </th>
                            </tr>
                            <tr>
                                <th 	style="padding-left:22px;">
                                    <?php
                                    if (isset($_GET['libellecentralenom'])) {
                                        $masque = $manager->getSingle2("select masquecentrale from centrale where trim(libellecentrale)=?", pg_escape_string(stripslashes($_GET['libellecentralenom'])));
                                        infoLibelle($masque);
                                    }
                                    ?>
                                </th>
                            </tr>
                        </table>
                    </fieldset><br>
                    <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------
                    VILLE CENTRALE
----------------------------------------------------------------------------------------------------------------------------------------------------------------------      -->


                    <fieldset id="identvc" style="border-color: #5D8BA2; margin-right: 20px;margin-left: 6px;padding-left: 20px;padding-top: 5px">
                        <legend><?php echo TXT_VILLE . ' - ' . TXT_CENTRALE; ?></legend>
                        <table>
                            <tr><th><input type="text"  style="height:20px;margin-left:20px;background-color: white; width: 360px;vertical-align:middle;border: 0px" data-dojo-type="dijit/form/ValidationTextBox" readonly="true"
                                           value="<?php
                                           if (isset($_GET['libecentrale'])) {
                                               echo TXT_CENTRALESELECTON . ' ' . str_replace("''", "'", stripslashes(pg_escape_string($_GET['libecentrale'])));
                                           }
                                           ?>"/></th>
                            <input type="text" name="idcentraleactuelnom" style="display: none" data-dojo-type="dijit/form/ValidationTextBox" readonly="true"
                                   value="<?php
                                   if (isset($_GET['libecentrale'])) {
                                       echo $manager->getSingle2("select idcentrale from centrale where libellecentrale=?", stripslashes(pg_escape_string($_GET['libecentrale'])));
                                   }
                                   ?>" />
                            </tr>
                            <tr>
                                <th><?php $rowlibecentralenom = $manager->getList('SELECT libellecentrale,villecentrale,idcentrale FROM centrale order by idcentrale asc;'); ?>
                                    <select  id="libcentralenom" data-dojo-type="dijit.form.ComboBox"  data-dojo-props="name: 'libcentralecodeunite',value: '',placeHolder: '<?php echo TXT_SELECTCENTRALE; ?>'"
                                             style="width: 360px;margin-left:20px;"    onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/Manage_ville_centrale/<?php echo $lang ?>/' + this.value)" >
                                                 <?php
                                                 for ($i = 0; $i < count($rowlibecentralenom); $i++) {
                                                     echo '<option value="' . ($rowlibecentralenom[$i]['idcentrale']) . '">' . str_replace("''", "'", $rowlibecentralenom[$i]['libellecentrale']) . '</option>';
                                                 }
                                                 ?>
                                    </select>

                                </th>
                                <th>
                                    <input id="modifcentraleville" type="text" name="modifcentraleville" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_VILLE; ?>"
                                           style="height:20px;margin-left:20px; width: 360px;vertical-align:middle;"
                                           value="<?php
                                           if (isset($_GET['libecentrale'])) {
                                               echo str_replace("''", "'", $manager->getSingle2("select villecentrale from centrale where libellecentrale=? ", pg_escape_string(stripslashes($_GET['libecentrale']))));
                                           }
                                           ?>"
                                           data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð0-9\'()_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >

                                </th>
                            </tr>
                            <tr>
                                <th>
                                    <button class="admin" style="padding-left: 22px" type="submit" name="modifvillecentrale" ><?php echo TXT_MODIFIER; ?></button>
                                </th>
                            </tr>
                        </table>
                    </fieldset><br>
                    <fieldset id="identce" style="border-color: #5D8BA2; margin-right: 20px;margin-left: 6px;padding-left: 20px;padding-top: 5px">
                        <legend><?php echo TXT_MAIL . ' - ' . TXT_CENTRALE; ?></legend>
                        <table>
                            <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------
                            EMAIL CENTRALE
                            ----------------------------------------------------------------------------------------------------------------------------------------------------------------------      -->
                            <tr><th>
                                    <input type="text"  style="height:20px;margin-left:20px;background-color: white; width: 360px;vertical-align:middle;border: 0px" data-dojo-type="dijit/form/ValidationTextBox" readonly="true"
                                           value="<?php
                                           if (isset($_GET['libellecentrale'])) {
                                               echo TXT_CENTRALESELECTON . ' ' . $_GET['libellecentrale'];
                                           }
                                           ?>" >
                                </th>
                                <?php
                                if (isset($_GET['libellecentrale'])) {
                                    $idcentrale = $manager->getSingle2("select idcentrale from centrale where libellecentrale=?", $_GET['libellecentrale']);
                                }
                                ?>
                            <input type="text" name="idcentraleactuelemail" style="display: none" data-dojo-type="dijit/form/ValidationTextBox" readonly="true"
                                   value="<?php
                                   if (isset($_GET['libellecentrale'])) {
                                       echo $idcentrale;
                                   }
                                   ?>" />
                            </tr>
                            <tr>
                                <th>
                                    <?php
                                    $rowlibellecentrale = $manager->getList('SELECT libellecentrale,email1,email2,email3,email4,email5,idcentrale FROM centrale order by idcentrale asc;');
                                    ?>
                                    <select  id="libellecentrale" data-dojo-type="dijit.form.ComboBox"  data-dojo-props="name: 'libellecentrale',value: '',placeHolder: '<?php echo TXT_SELECTCENTRALE; ?>'"
                                             style="width: 360px;margin-left:20px;"    onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/Manage_mail_centrale/<?php echo $lang ?>/' + this.value)" >
                                                 <?php
                                                 for ($i = 0; $i < count($rowlibellecentrale); $i++) {
                                                     echo '<option value="' . ($rowlibellecentrale[$i]['idcentrale']) . '">' . $rowlibellecentrale[$i]['libellecentrale'] . '</option>';
                                                 }
                                                 ?>
                                    </select>

                                </th>
                                <th>
                                    <input id="modifcentraleemail1" type="text" name="modifcentraleemail1" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_MAIL . ' ' . '1'; ?>"
                                           regExpGen="dojox.validate.regexp.emailAddress" invalidMessage="<?php echo TXT_EMAILNONVALIDE; ?>" style="height:20px;margin-left:20px; width: 360px;vertical-align:middle;"
                                           value="<?php
                                           if (isset($_GET['libellecentrale'])) {
                                               $idcentrale = $manager->getSingle2("select idcentrale from centrale where libellecentrale=?", $_GET['libellecentrale']);
                                               echo trim($rowlibellecentrale[$idcentrale - 1]['email1']);
                                           }
                                           ?>" />
                                </th>
                            </tr>
                            <tr>
                                <th>
                                    <input id="modifcentraleemail2" type="text" name="modifcentraleemail2" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_MAIL . ' ' . '2'; ?>"
                                           regExpGen="dojox.validate.regexp.emailAddress" invalidMessage="<?php echo TXT_EMAILNONVALIDE; ?>" style="height:20px;margin-left:20px; width: 360px;vertical-align:middle;"
                                           value="<?php
                                           if (isset($_GET['libellecentrale'])) {
                                               $idcentrale = $manager->getSingle2("select idcentrale from centrale where libellecentrale=?", $_GET['libellecentrale']);
                                               echo trim($rowlibellecentrale[$idcentrale - 1]['email2']);
                                           }
                                           ?>" />
                                </th>

                                <th>
                                    <input id="modifcentraleemail3" type="text" name="modifcentraleemail3" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_MAIL . ' ' . '3'; ?>"
                                           regExpGen="dojox.validate.regexp.emailAddress" invalidMessage="<?php echo TXT_EMAILNONVALIDE; ?>" style="height:20px;margin-left:20px; width: 360px;vertical-align:middle;"
                                           value="<?php
                                           if (isset($_GET['libellecentrale'])) {
                                               $idcentrale = $manager->getSingle2("select idcentrale from centrale where libellecentrale=?", $_GET['libellecentrale']);
                                               echo trim($rowlibellecentrale[$idcentrale - 1]['email3']);
                                           }
                                           ?>" />

                                </th>
                            </tr>
                            <tr>
                                <th>
                                    <input id="modifcentraleemail4" type="text" name="modifcentraleemail4" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_MAIL . ' ' . '4'; ?>"
                                           regExpGen="dojox.validate.regexp.emailAddress" invalidMessage="<?php echo TXT_EMAILNONVALIDE; ?>" style="height:20px;margin-left:20px; width: 360px;vertical-align:middle;"
                                           value="<?php
                                           if (isset($_GET['libellecentrale'])) {
                                               $idcentrale = $manager->getSingle2("select idcentrale from centrale where libellecentrale=?", $_GET['libellecentrale']);
                                               echo trim($rowlibellecentrale[$idcentrale - 1]['email4']);
                                           }
                                           ?>" />

                                </th>
                                <th>
                                    <input id="modifcentraleemail5" type="text" name="modifcentraleemail5" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_MAIL . ' ' . '5'; ?>"
                                           regExpGen="dojox.validate.regexp.emailAddress" invalidMessage="<?php echo TXT_EMAILNONVALIDE; ?>" style="height:20px;margin-left:20px; width: 360px;vertical-align:middle;"
                                           value="<?php
                                           if (isset($_GET['libellecentrale'])) {
                                               $idcentrale = $manager->getSingle2("select idcentrale from centrale where libellecentrale=?", $_GET['libellecentrale']);
                                               echo trim($rowlibellecentrale[$idcentrale - 1]['email5']);
                                           }
                                           ?>" />

                                </th>
                            </tr>
                            <tr>
                                <th>
                                    <button class="admin" style="padding-left: 22px" type="submit" name="modifemailcentrale" ><?php echo TXT_MODIFIER; ?></button>
                                </th>
                            </tr>
                        </table>
                    </fieldset>
                    <br>
                    <fieldset id="identco" style="border-color: #5D8BA2; margin-right: 20px;margin-left: 6px;padding-left: 20px;padding-top: 5px">
                        <legend><?php echo TXT_CODEUNITE . ' - ' . TXT_CENTRALE; ?></legend>
                        <table>
                            <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------
                            CODE UNITE CENTRALE
                            ----------------------------------------------------------------------------------------------------------------------------------------------------------------------      -->
                            <tr><th>
                                    <input type="text"  style="height:20px;margin-left:20px;background-color: white; width: 360px;vertical-align:middle;border: 0px" data-dojo-type="dijit/form/ValidationTextBox" readonly="true"
                                           value="<?php
                                           if (isset($_GET['libecentral'])) {
                                               echo TXT_CENTRALESELECTON . ' ' . $_GET['libecentral'];
                                           }
                                           ?>"/>
                                </th>
                                <?php
                                if (isset($_GET['libecentral'])) {
                                    $idcentrale = $manager->getSingle2("select idcentrale from centrale where libellecentrale=?", $_GET['libecentral']);
                                }
                                ?>
                            <input type="text" name="idcentraleactuelcodeunite" style="display: none" data-dojo-type="dijit/form/ValidationTextBox" readonly="true"
                                   value="<?php
                                   if (isset($_GET['libecentral'])) {
                                       echo $idcentrale;
                                   }
                                   ?>" />
                            </tr>
                            <tr>
                                <th>
                                    <?php
                                    $rowlibcentralecodeunite = $manager->getList('SELECT libellecentrale,codeunite,idcentrale FROM centrale order by idcentrale asc;');
                                    ?>
                                    <select  id="libcentralecodeunite" data-dojo-type="dijit.form.ComboBox"  data-dojo-props="name: 'libcentralecodeunite',value: '',placeHolder: '<?php echo TXT_SELECTCENTRALE; ?>'"
                                             style="width: 360px;margin-left:20px;"    onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/Manage_codeunite/<?php echo $lang ?>/' + this.value)" >
                                                 <?php
                                                 for ($i = 0; $i < count($rowlibcentralecodeunite); $i++) {
                                                     echo '<option value="' . ($rowlibcentralecodeunite[$i]['idcentrale']) . '">' . $rowlibcentralecodeunite[$i]['libellecentrale'] . '</option>';
                                                 }
                                                 ?>
                                    </select>

                                </th>
                                <th>
                                    <input id="modifcentralecodeunite" type="text" name="modifcentralecodeunite" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_CODEUNITE; ?>"
                                           invalidMessage="<?php echo '' ?>" style="height:20px;margin-left:20px; width: 360px;vertical-align:middle;"
                                           value="<?php
                                                 if (!empty($_GET['libecentral'])) {
                                                     $idcentrale = $manager->getSingle2("select idcentrale from centrale where libellecentrale=?", $_GET['libecentral']);
                                                     echo stripslashes(trim($rowlibcentralecodeunite[$idcentrale - 1]['codeunite']));
                                                 }
                                                 ?>"
                                           data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð0-9\',.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >
                                </th>
                            </tr>

                            <tr>
                                <th>
                                    <button class="admin" style="padding-left: 22px" type="submit" name="modifcodeunitecentrale" ><?php echo TXT_MODIFIER; ?></button>
                                </th>
                            </tr>
                        </table>
                    </fieldset><br>
                    <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------
                    RESSOURCE
----------------------------------------------------------------------------------------------------------------------------------------------------------------------      -->
                    <fieldset id="identre" style="border-color: #5D8BA2; margin-right: 20px;margin-left: 6px;padding-left: 20px;padding-top: 5px">
                        <legend><?php echo TXT_RESSOURCE; ?></legend>
                        <table>
                            <tr>
                                <th><input type="text"  style="height:20px;margin-left:20px;background-color: white; width: 360px;vertical-align:middle;border: 0px" data-dojo-type="dijit/form/ValidationTextBox" readonly="true" ></th>
                            <input type="text" name="idressourceactuel" style="display: none" data-dojo-type="dijit/form/ValidationTextBox" readonly="true"
                                   value="<?php
                                           if (isset($_GET['idressource'])) {
                                               echo $_GET['idressource'];
                                           }
                                                 ?>" />
                            </tr>
                            <tr>
                                <th>
                                    <?php
                                    if ($lang == 'fr') {
                                        $rowressource = $manager->getList("SELECT libelleressource,idressource from ressource  order by idressource asc;");
                                    } elseif ($lang == 'en') {
                                        $rowressource = $manager->getList("SELECT libelleressourceen,idressource from ressource  order by idressource asc;");
                                    }
                                    ?>
                                    <select  id="libelleressource" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'libelleressource',value: '',required:false,placeHolder: '<?php echo TXT_SELECTRESSOURCE; ?>'"
                                             style="width: 360px;margin-left:20px;"    onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/Manage_ressource_centrale/<?php echo $lang ?>/' + this.value)" >
                                                 <?php
                                                 if ($lang == 'fr') {
                                                     for ($i = 0; $i < count($rowressource); $i++) {
                                                         echo '<option value="' . ($rowressource[$i]['idressource']) . '">' . str_replace("''", "'", $rowressource[$i]['libelleressource']) . '</option>';
                                                     }
                                                 } elseif ($lang == 'en') {
                                                     for ($i = 0; $i < count($rowressource); $i++) {
                                                         echo '<option value="' . ($rowressource[$i]['idressource']) . '">' . str_replace("''", "'", $rowressource[$i]['libelleressourceen']) . '</option>';
                                                     }
                                                 }
                                                 ?>
                                    </select>

                                </th>
                                <th>
                                    <input id="modifressource" type="text" name="modifressource" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_RESSOURCES; ?>"
                                           style="height:20px;margin-left:20px; width: 360px;vertical-align:middle;"
                                           value="<?php
                                                 if (isset($_GET['idressource'])) {
                                                     echo str_replace("''", "'", $manager->getSingle2("select libelleressource from ressource where idressource=?", $_GET['idressource']));
                                                 }
                                                 ?>" data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð/\'();_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >
                                </th>
                            </tr>
                            <tr>
                                <td style="width: 360px;"></td>
                                <th>
                                    <input id="modifressourceen" type="text" name="modifressourceen" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_RESSOURCEEN; ?>"
                                           style="height:20px;margin-left:20px; width: 360px;vertical-align:middle;" value="<?php
                                           if (isset($_GET['idressource'])) {
                                               echo $manager->getSingle2("select libelleressourceen from ressource where idressource=?", $_GET['idressource']);
                                           }
                                                 ?>" data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð/\'();_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >
                                </th>
                            </tr>
                            <tr>
                                <th>
                                    <input class="admin" style="padding-left: 20px" type="submit" name="modifRessource" value="<?php echo TXT_MODIFIER; ?>" />
                                    <input class="admin" style="padding-left: 20px" type="submit" name="ajouteressource" value="<?php echo TXT_AJOUTER; ?>" />
                                </th>
                                <th>
                                    <input class="admin" style="padding-left: 20px" type="submit" name="masqueressource" value="<?php echo TXT_MASQUER; ?>"/>
                                    <input class="admin" style="padding-left: 20px" type="submit" name="afficheressource" value="<?php echo TXT_REAFFICHER; ?>"/>
                                </th>
                            </tr>
                            <tr>
                                <th style="padding-left:22px;">
                                    <?php
                                    if (isset($_GET['idressource'])) {
                                        $masque = $manager->getSingle2("select masqueressource from ressource where idressource=?", $_GET['idressource']);
                                        infoLibelle($masque);
                                    }
                                    ?>
                                </th>
                            </tr>
                        </table>
                    </fieldset>
                    <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------
                    SOURCE DE FINANCEMENT
                    ----------------------------------------------------------------------------------------------------------------------------------------------------------------------      -->
                    <fieldset id="identsf" style="border-color: #5D8BA2; margin-right: 20px;margin-left: 6px;padding-left: 20px;padding-top: 5px">
                        <legend><?php echo TXT_SOURCEFINANCEMENT; ?></legend>
                        <table>
                            <tr>
                                <th><input type="text"  style="height:20px;margin-left:20px;background-color: white; width: 360px;vertical-align:middle;border: 0px" data-dojo-type="dijit/form/ValidationTextBox" readonly="true" ></th>
                            <input type="text" name="idsourcefinancementactuel" style="display: none" data-dojo-type="dijit/form/ValidationTextBox" readonly="true"
                                   value="<?php
                                    if (isset($_GET['idsourcefinancement'])) {
                                        echo $_GET['idsourcefinancement'];
                                    }
                                    ?>" />
                            </tr>
                            <tr>
                                <th>
                                    <?php
                                    if ($lang == 'fr') {
                                        $sourcefinancement = $manager->getList("SELECT libellesourcefinancement,idsourcefinancement from sourcefinancement  order by idsourcefinancement asc;");
                                    } elseif ($lang == 'en') {
                                        $sourcefinancement = $manager->getList("SELECT libellesourcefinancementen,idsourcefinancement from sourcefinancement  order by idsourcefinancement asc;");
                                    }
                                    ?>
                                    <select  id="libellesourcefinancement" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'libellesourcefinancement',value: '',required:false,placeHolder: '<?php echo TXT_SELECTSOURCEFINANCEMENT ?>'"
                                             style="width: 360px;margin-left:20px;"    onchange="window.location.replace('<?php echo "/" . REPERTOIRE; ?>/Manage_source_financement/<?php echo $lang ?>/' + this.value)" >
                                                 <?php
                                                 if ($lang == 'fr') {
                                                     for ($i = 0; $i < count($sourcefinancement); $i++) {
                                                         echo '<option value="' . ($sourcefinancement[$i]['idsourcefinancement']) . '">' . str_replace("''", "'", $sourcefinancement[$i]['libellesourcefinancement']) . '</option>';
                                                     }
                                                 } elseif ($lang == 'en') {
                                                     for ($i = 0; $i < count($sourcefinancement); $i++) {
                                                         echo '<option value="' . ($sourcefinancement[$i]['idsourcefinancement']) . '">' . str_replace("''", "'", $sourcefinancement[$i]['libellesourcefinancementen']) . '</option>';
                                                     }
                                                 }
                                                 ?>
                                    </select>

                                </th>
                                <th>
                                    <input id="modifsourcefinancement" type="text" name="modifsourcefinancement" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_SOURCEFINANCEMENT; ?>"
                                           style="height:20px;margin-left:20px; width: 360px;vertical-align:middle;"
                                           value="<?php
                                                 if (isset($_GET['idsourcefinancement'])) {
                                                     echo str_replace("''", "'", $manager->getSingle2("select libellesourcefinancement from sourcefinancement where idsourcefinancement=?", $_GET['idsourcefinancement']));
                                                 }
                                                 ?>" data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð/\'();_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >
                                </th>
                            </tr>
                            <tr>
                                <td style="width: 360px;"></td>
                                <th>
                                    <input id="modifsourcefinancementen" type="text" name="modifsourcefinancementen" class="long" data-dojo-type="dijit/form/ValidationTextBox"   placeholder="<?php echo TXT_SOURCEFINANCEMENTEN; ?>"
                                           style="height:20px;margin-left:20px; width: 360px;vertical-align:middle;" value="<?php
                                           if (isset($_GET['idsourcefinancement'])) {
                                               echo $manager->getSingle2("select libellesourcefinancementen from sourcefinancement where idsourcefinancement=?", $_GET['idsourcefinancement']);
                                           }
                                                 ?>" data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð/\'();_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >
                                </th>
                            </tr>
                            <tr>
                                <th>
                                    <input class="admin" style="padding-left: 21px" type="submit" name="modifSourcefinancement"  value= "<?php echo TXT_MODIFIER; ?>" >
                                    <input class="admin" style="padding-left: 21px" type="submit" name="ajoutesourcefinancement" value="<?php echo TXT_AJOUTER; ?>" />
                                </th>
                                <th>
                                    <input class="admin" style="padding-left: 20px" type="submit" name="masquesourcefinancement" value="<?php echo TXT_MASQUER; ?>"/>
                                    <input class="admin" style="padding-left: 20px" type="submit" name="affichesourcefinancement" value="<?php echo TXT_REAFFICHER; ?>"/>
                                </th>
                            </tr>
                            <tr>
                                <th style="padding-left:22px;">
                                    <?php
                                    if (isset($_GET['idsourcefinancement'])) {
                                        if ($lang == 'fr') {
                                            $masque = $manager->getSingle2("select masquesourcefinancement from sourcefinancement where idsourcefinancement=?", $_GET['idsourcefinancement']);
                                            infoLibelle($masque);
                                        } elseif ($lang == 'en') {
                                            $masque = $manager->getSingle2("select masquesourcefinancementen from sourcefinancement where idsourcefinancement=?", $_GET['idsourcefinancement']);
                                            infoLibelle($masque);
                                        }
                                    }
                                    ?>
                                </th>
                            </tr>
                        </table>
                    </fieldset>
                </form>
            </div>
            <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------!-->
            <?php include 'html/footer.html'; ?>

        </div>
        </body>
        </html>
        <?php
        $requetePays = " SELECT  nompays, nompays as autre  FROM pays order by nompays ASC";
        $cheminPays = "tmp";
        $nomselectPays = "selectPays.json";
        $libellePays = "nompays";
        $libellePays1 = "autre";
        creerJson($requetePays, $cheminPays, $nomselectPays, $libellePays, $libellePays1);
        