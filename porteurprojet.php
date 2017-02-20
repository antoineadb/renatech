<?php
session_start();
include_once 'class/Manager.php';
include_once 'outils/toolBox.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include 'decide-lang.php';
include 'outils/constantes.php';

if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
include 'html/header.html';
?>
<div id="global">
    <?php include 'html/entete.html'; ?>
    <div style="margin-top: 70px;">
        <?php include_once 'outils/bandeaucentrale.php'; ?>
    </div>
    <div style="margin-top:15px;width:1050px" id="navigation">
        <form  method="post" action="<?php echo '/' . REPERTOIRE ?>/projet_porteur/<?php echo $lang; ?>" id='gestionadminprojet' name='gestionadminprojet' >
            <?php if (!isset($_GET['idprojet'])) {?>
            <fieldset id="findProjetporteur" style="display: block;height:50px;border-color: #5D8BA2;padding-bottom:20px;padding-top: 8px;border:solid 1px #5D8BA2;color:midnightblue;font-family:verdana;border-radius: 5px;" >
                <legend style="margin-left: 20px;font-size: 1.2em"><?php echo TXT_CHOIXPROJET; ?></legend>
                <table>
                    <tr>
                        <td valign="top" style="width: 105px;padding-top: 3px">
                            <input style="width: 260px;border-radius: 3px;margin-left: 22px;height:24px;font-size: 1.2em" placeholder="<?php echo TXT_DATEPROJET; ?>" name="dateprojet" id="dateprojet" data-dojo-type="dijit/form/DateTextBox" autocomplete="on"/></td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td valign="top" style="width: 105px;"><input type="text" style="width: 260px;border-radius: 8px;padding: 3px;height:24px;font-size:1.2em" name="numprojet" placeholder="<?php echo TXT_NUMPROJET; ?>" id="numprojet" data-dojo-type="dijit/form/ValidationTextBox" autocomplete="on"/></td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td>&nbsp;&nbsp;&nbsp;</td>
                        <td valign="bottom" >
                            <input name ="rechercheProjet" type="submit"  value="<?php echo TXT_CHOIXPROJET; ?>"  class="maj" onclick="
                                    if (document.getElementById('dateprojet').value === '' && document.getElementById('numprojet').value === '') {
                                        document.getElementById('numprojet').value = '*';
                                    }"  >
                        </td>
                    </tr>
                </table>
            </fieldset>            
            <?php }
            if (!empty($_POST['dateprojet']) || !empty($_POST['numprojet'])) {?>
                <fieldset id="new" style="border-color: #5D8BA2;width: 1050px;padding:4px; margin-top: 15px" >
                <legend style="font-size:1.2em;margin-left: 20px;" ><?php echo TXT_CHOIXPROJET; ?> 
                    <a class="infoBulle" style="display: inline" href="#"><img src="<?php echo  '/'.REPERTOIRE; ?>/styles/img/help.gif" height="13px" width="13px"/>
                        <span style="text-align: left;padding:10px;width: 540px;border-radius:5px" ><?php echo  TXT_AIDEFINDPROJET; ?></span>
                    </a>
                </legend>                
                <?php include 'findProjetporteur.php';?>
            </fieldset><?php
            }
             if (!empty($_GET['messagedejaaffecte'])) { ?>
                <br>
                <table style="width:320px" id="msgdeja">
                    <tr>
                        <td>
                            <div  style="height:20px;width:520px;" ><b><?php echo TXT_PROJETDEJAAFFECTE; ?></b></div>
                        </td>
                    <tr>
                </table>
            <?php } ?>
            <?php if (!empty($_GET['update'])) { ?>
                <br>
                <table>
                    <tr>
                        <td><div style="color: midnightblue" ><?php echo TXT_PROJETADD; ?></b></div></td>
                    <tr>
                </table>
            <?php } ?>

            <?php
            if (isset($_GET['idutilisateur'])) {
                $row = $manager->getList2("SELECT dateaffectation,numero,titre,idprojet FROM utilisateurporteurprojet, projet WHERE  idprojet_projet = idprojet and datestatutcloturer is null and idutilisateur_utilisateur=?", $_GET['idutilisateur']);
                $nom = $manager->getSingle2("select nom from utilisateur where idutilisateur = ?", $_GET['idutilisateur']);
                ?><br>
                <fieldset id="findporteur" style="border-color: #5D8BA2;width: 1040px;padding-bottom:10px;padding-top: 5px;border:solid 1px #5D8BA2;color:midnightblue;font-family:verdana;border-radius: 5px;" >
                    <legend style="margin-left: 20px"><?php echo TXT_PROJETPORTEUR . ' ' . ucfirst($nom); ?></legend>

                    <?php
                    $fprow = fopen('tmp/porteurProjet.json', 'w');
                    $dataporteurprojet = "";
                    fwrite($fprow, '{"items": [');
                    for ($i = 0; $i < count($row); $i++) {
                        $dataporteurprojet = "" . '{"dateaffectation":' . '"' . $row[$i]['dateaffectation'] . '"' . "," . '"numero":' . '"' . $row[$i]['numero'] . '"' .
                                "," . '"titre":' . '"' . filtredonnee($row[$i]['titre']) . '"' .
                                "," . '"idprojet":' . '"' . $row[$i]['idprojet'] . '"' . "," .
                                '"Enlever":' . '"' . TXT_RETIRER . '"' . "},";
                        fputs($fprow, $dataporteurprojet);
                        fwrite($fprow, '');
                    }
                    fwrite($fprow, ']}');
                    $json_fileporteurprojet = "tmp/porteurProjet.json";
                    $jsonporteurprojet1 = file_get_contents($json_fileporteurprojet);
                    $jsonPorteurprojet = str_replace('},]}', '}]}', $jsonporteurprojet1);
                    file_put_contents($json_fileporteurprojet, $jsonPorteurprojet);
                    fclose($fprow);
                    chmod('tmp/porteurProjet.json', 0777);
                    if (count($row) == 0) {
                        echo '<div style="text-align:center;width: auto;height:25px" >' . TXT_NORESULT . '</div>';
                    } else {
                        echo '<div style="text-align:center;width: auto;height:25px" >' . TXT_NBRESULT . ' :' . count($row) . '</div>';
                    }
                    ?>
                    <div style="height: 250px;">
                        <div id="griduserporteur" ></div>
                        <script>
                            var griduserporteur, dataStore, store;
                            require([
                                "dojox/grid/DataGrid",
                                "dojo/store/Memory",
                                "dojo/data/ObjectStore",
                                "dojo/request",
                                "dojo/date/stamp",
                                "dojo/date/locale",
                                "dojo/domReady!"
                            ], function(DataGrid, Memory, ObjectStore, request, stamp, locale) {
                                request.get("<?php echo '/' . REPERTOIRE; ?>/tmp/porteurProjet.json", {
                                    handleAs: "json"
                                }).then(function(data) {
                                    store = new Memory({data: data.items});
                                    dataStore = new ObjectStore({objectStore: store});

                                    function deleteProjet(datum, idx) {
                                        return '<button class="btnSuppr" data-dojo-type="dijit/form/Button" type="button" onClick="reponse.show();"><?php echo TXT_RETIRER; ?></button>';
                                    }
                                    function formatDate(datum) {
                                        var d = stamp.fromISOString(datum);
                                        return locale.format(d, {selector: 'date', formatLength: 'long'});
                                    }

                                    griduserporteur = new DataGrid({
                                        store: dataStore,
                                        query: {id: "*"},
                                        structure: [
                                            {name: "<?php echo TXT_DATEAFFECTATION; ?>", field: "dateaffectation", width: "auto", formatter: formatDate},
                                            {name: "<?php echo TXT_NUMERO; ?>", field: "numero", width: "auto"},
                                            {name: "<?php echo TXT_TITREPROJET; ?>", field: "titre", width: "auto"},
                                            {name: " ", field: "supprime", width: "80px", formatter: deleteProjet}
                                        ]
                                    }, "griduserporteur");
                                    griduserporteur.startup();
                                    griduserporteur.on("RowClick", function(evt) {
                                        var idx = evt.rowIndex;
                                        var item = griduserporteur.getItem(idx);
                                        var idprojet = item.idprojet;
                                        var numero = item.numero;
                                        document.getElementById("results").value = idprojet;
                                        dijit.byId("reponse").set('title', "<?php echo TXT_CONFIRMREMOVEPROJECT; ?><br>" + numero);
                                    }, true);
                                });
                            });
                        </script>
                    </div>
                    <script>
                        function retireprojet(idprojet) {
                            window.location.replace('<?php echo "/" . REPERTOIRE ?>/modifBase/deleteutilisateurporteurprojet.php?lang=<?php echo $lang; ?>&idprojet=' + idprojet + '&idutilisateur=<?php echo $_GET['idutilisateur']; ?>');
                        }
                    </script>
                    <div data-dojo-type="dijit/Dialog" data-dojo-id="reponse" id ='reponse' title="" style="width: 380px;margin-left: 20px;"  >
                        <table class="dijitDialogPaneContentArea">
                            <tr>
                                <td><button type="submit" data-dojo-type="dijit/form/Button"  id="submitOui" data-dojo-props="onClick:function(){retireprojet(document.getElementById('results').value);}" >
                                        <?php echo TXT_OUI; ?></button></td>
                                <td><button type="submit" data-dojo-type="dijit/form/Button"  id="submitNon" data-dojo-props="onClick:function(){reponse.hide();}">
                                        <?php echo TXT_NON; ?></button></td>
                                <td><button data-dojo-type="dijit/form/Button" type="submit" data-dojo-props="onClick:function(){reponse.hide();}" id="cancel"><?php echo TXT_ANNULE; ?></button></td>
                            </tr>
                        </table>
                    </div>
                </fieldset>
                <div id="results"></div>
                <?php
            }
            if (isset($_GET['idprojet']) && !empty($_GET['idprojet'])) { 
                $arrayprojet = $manager->getList2("select numero,titre from projet where idprojet=?", $_GET['idprojet']);
                $numero = $arrayprojet[0]['numero'];
                $titre = $arrayprojet[0]['titre'];
                ?>
                <div id='fieldprojetporteur'  >
                    <table style="border: 1px solid #57758D;clear: both;margin-top: 15px;width: 1050px;border-radius: 5px;">
                        <th style="background-color: #E3ECF2; padding-left:6px; color: midnightblue;height:20px" ><?php echo TXT_SELECTEDPROJET; ?></th>
                        <tr>
                            <td>
                                <div   style="padding-top: 5px;padding-bottom: 5px;color: midnightblue;font-size: 1.2em;height: 20px;margin-left: 5px;text-align: left;vertical-align: middle;width: 850px;" id="copieprojet" name="copieprojet" value="<?php echo $numero; ?>" ><?php echo $numero . ' - ' . stripslashes(str_replace("''", "'", substr($titre, 0, 60))) . '...'; ?></div>
                            </td>
                        </tr>
                    </table>
                    <table><tr>  <td><button type="button" data-dojo-type="dijit/form/Button"  id="annule" data-dojo-props="onClick:function(){window.location.replace('<?php echo "/" . REPERTOIRE ?>/porteur_Projet/<?php echo $lang;?>')}"><?php echo TXT_ANNULE ?></button></td></tr></table>
                        <br>
                        <fieldset id="ident" style="border-color: #5D8BA2;width: 1008px;padding-bottom:10px;padding-top:8px;margin-top:20px" >
                            <legend><?php echo TXT_RECHERCHEUSER; ?></legend>
                            <table>
                                <tr>
                                    <td valign="top" style="width: 80px;"><input type="text" style="width: 260px;border-radius: 8px;padding: 3px;font-size:1.2em" placeholder="<?php echo TXT_NOMUTILISATEUR; ?>" name="nom" id="nom" data-dojo-type="dijit/form/ValidationTextBox" autocomplete="on"/></td>
                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td valign="top" style="width: 100px;text-align: left;"><input type="text" style="width: 260px;border-radius: 8px;padding: 3px;font-size: 1.2em" placeholder="<?php echo TXT_PRENOMUTILISATEUR; ?>" name="prenom" id="prenom" data-dojo-type="dijit/form/ValidationTextBox" autocomplete="on"/></td>
                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td>&nbsp;&nbsp</td>
                                    <td valign="bottom" ><input name ="rechercheUserporteur" type="submit" ondragover="tous"  value="<?php echo TXT_BOUTONRECHERCHE; ?>"  class="maj"
                                                                onclick="if (document.getElementById('nom').value === '' && document.getElementById('prenom').value === '') {
                                                                            document.getElementById('nom').value = '*';
                                                                        }"/>
                                    </td>
                                </tr>
                            </table>
                        </fieldset>
                </div>
                   <?php } ?>
            <input type="text" id ="copieProjet" name="copieProjet" style="display: none" value="<?php
                   if (isset($_GET['idprojet'])) {
                       echo $_GET['idprojet'];
                   }
                   ?>"/>
                   <?php
                   if (isset($_GET['idprojet'])) {
                       $_SESSION['idprojet'] = $_GET['idprojet'];
                   }
                   ?>
<?php include 'html/footer.html'; ?>
        </form>
    </div>
</div>
</body>
</html>