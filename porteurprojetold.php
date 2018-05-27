<?php
session_start();
include_once 'class/Manager.php';
include_once 'outils/toolBox.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include 'decide-lang.php';
include 'html/header.html';
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    echo '<script>window.location.replace("erreurlogin.php")</script>';
}
?>
<div id="global">
    <?php include 'html/entete.html'; ?>
    <br>
    <div style="margin-top:40px;width:1050px" id="navigation">
        <form  method="post" action="ctrlporteur.php?lang=<?php echo $lang; ?>" id='gestionporteur' name='gestionporteur' >
            <fieldset id="findProjetporteur" style="display: block;height:50px;border-color: #5D8BA2;padding-bottom:20px;padding-top: 8px;border:solid 1px #5D8BA2;color:midnightblue;font-family:verdana;border-radius: 5px;" >
                <legend style="margin-left: 20px;font-size: 1.2em"><?php echo affiche('TXT_CHOIXPROJET'); ?></legend>
                <table>
                    <tr>
                        <td valign="top" style="width: 105px;padding-top: 3px">
                            <input type="dijit/form/DateTextBox" style="width: 260px;border-radius: 3px;margin-left: 22px;height:24px;font-size: 1.2em" placeholder="<?php echo affiche('TXT_DATEPROJET'); ?>" name="dateprojet" id="dateprojet" data-dojo-type="dijit/form/DateTextBox" autocomplete="on"/></td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td valign="top" style="width: 105px;"><input type="text" style="width: 260px;border-radius: 8px;padding: 3px;height:24px;font-size:1.2em" name="numprojet" placeholder="<?php echo affiche('TXT_NUMPROJET'); ?>" id="numprojet" data-dojo-type="dijit/form/ValidationTextBox" autocomplete="on"/></td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td>&nbsp;&nbsp;&nbsp;</td>
                        <td valign="bottom" >
                            <input name ="rechercheProjet" type="submit"  value="<?php echo affiche('TXT_CHOIXPROJET'); ?>"  class="maj" onclick="
                                    if (document.getElementById('dateprojet').value === '' && document.getElementById('numprojet').value === '') {
                                        document.getElementById('numprojet').value = '*';
                                    }"  >
                        </td>
                    </tr>
                </table>
            </fieldset>
            <?php
            if (isset($_POST['dateprojet']) && !empty($_POST['dateprojet']) || isset($_POST['numprojet']) && !empty($_POST['numprojet'])) {
                echo '<fieldset id="new" style="border-color: #5D8BA2;width: 1050px;padding:4px; margin-top: 15px" > <legend>' . affiche('TXT_CHOIXPROJET') . ' ' . affiche('TXT_NONCLOTURER') . '</legend> ';
                include 'findProjetporteur.php';
                echo '</fieldset>';
            }
            ?>
            <?php if (isset($_GET['messagedejaaffecte'])) { ?>
                <br>
                <table style="width:320px" id="msgdeja">
                    <tr>
                        <td>
                            <div  style="height:20px;width:520px;" ><b><?php echo affiche('TXT_PROJETDEJAAFFECTE'); ?></b></div>
                        </td>
                    <tr>
                </table>
            <?php } ?>
            <?php if (isset($_GET['messageupdate'])) { ?>
                <br>
                <table>
                    <tr>
                        <td><div style="color: midnightblue" ><?php echo affiche('TXT_PROJETADD'); ?></b></div></td>
                    <tr>
                </table>
            <?php } ?>

            <?php                
            if(isset($_GET['idutilisateur'])){
                            
                $row = $manager->getList2("SELECT dateaffectation,numero,titre,idprojet FROM utilisateurporteurprojet, projet WHERE  idprojet_projet = idprojet and datestatutcloturer is null and idutilisateur_utilisateur=?", $_GET['idutilisateur']);
                
                $nom = $manager->getSingle2("select nom from utilisateur where idutilisateur = ?", $_GET['idutilisateur']);
                ?><br>
                <fieldset id="findporteur" style="border-color: #5D8BA2;width: 1040px;padding-bottom:10px;padding-top: 5px;border:solid 1px #5D8BA2;color:midnightblue;font-family:verdana;border-radius: 5px;" >
                    <legend style="margin-left: 20px"><?php echo affiche('TXT_PROJETPORTEUR') . ' ' . ucfirst($nom); ?></legend>

                    <?php
                    $fprow = fopen('tmp/porteurProjet.json', 'w');
                    $dataporteurprojet = "";
                    fwrite($fprow, '{"items": [');
                    for ($i = 0; $i < count($row); $i++) {
                        $dataporteurprojet = "" . '{"dateaffectation":' . '"' . $row[$i]['dateaffectation'] . '"' . "," . '"numero":' . '"' . $row[$i]['numero'] . '"' .
                                "," . '"titre":' . '"' . filtredonnee($row[$i]['titre']) . '"' .
                                "," . '"idprojet":' . '"' . $row[$i]['idprojet'] . '"' . "," .
                                '"Enlever":' . '"' . affiche('TXT_RETIRER') . '"' . "},";
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
                        echo '<div style="text-align:center;width: auto;height:25px" >' . affiche('TXT_NORESULT') . '</div>';
                    } else {
                        echo '<div style="text-align:center;width: auto;height:25px" >' . affiche('TXT_NBRESULT') . ' :' . count($row) . '</div>';
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
                                "dojo/domReady!"
                            ], function(DataGrid, Memory, ObjectStore, request) {
                                request.get("tmp/porteurProjet.json", {
                                    handleAs: "json"
                                }).then(function(data) {
                                    store = new Memory({data: data.items});
                                    dataStore = new ObjectStore({objectStore: store});
                                    function hrefFormatterNumero(numero, idx) {
                                        var item = griduserporteur.getItem(idx);
                                        var numero = item.numero;
                                        return "<a  href=\"modifProjetRespCentrale.php?lang=<?php echo $lang; ?>&numProjet=" + numero + "\">" + numero + "</a>";
                                    }
                                    function hrefFormatterDate(date, idx) {
                                        var item = griduserporteur.getItem(idx);
                                        var numero = item.numero;
                                        return "<a  href=\"modifProjetRespCentrale.php?lang=<?php echo $lang; ?>&numProjet=" + numero + "\">" + date + "</a>";
                                    }
                                    function hrefFormatterTitre(titre, idx) {
                                        var item = griduserporteur.getItem(idx);
                                        var numero = item.numero;
                                        return "<a  href=\"modifProjetRespCentrale.php?lang=<?php echo $lang; ?>&numProjet=" + numero + "\">" + titre + "</a>";
                                    }
                                    function hrefFormatterEnleve(enleve, idx) {
                                        var item = griduserporteur.getItem(idx);
                                        var idprojet = item.idprojet;
                                        return "<a  href=\"modifBase/deleteutilisateurporteurprojet.php?lang=<?php echo $lang; ?>&idprojet=" + idprojet + "&idutilisateur=" +<?php echo $_GET['idutilisateur']; ?> + "\">" + enleve + "</a>";
                                    }

                                    griduserporteur = new DataGrid({
                                        store: dataStore,
                                        query: {id: "*"},
                                        structure: [
                                            {name: "Date d'affectation", field: "dateaffectation", width: "137px", formatter: hrefFormatterDate},
                                            {name: "Numéro", field: "numero", width: "120px", formatter: hrefFormatterNumero},
                                            {name: "Titre", field: "titre", width: "auto", formatter: hrefFormatterTitre},
                                            {name: " ", field: "Enlever", width: "100px", formatter: hrefFormatterEnleve},
                                        ]
                                    }, "griduserporteur");
                                    griduserporteur.startup();
                                });
                            });
                        </script>
                    </div></fieldset>

                <?php
            }
            if (isset($_GET['idprojet'])) {
                $arrayprojet = $manager->getList2("select numero,titre from projet where idprojet=?", $_GET['idprojet']);
                $numero = $arrayprojet[0]['numero'];
                $titre = $arrayprojet[0]['titre'];
                ?>
                <div id='fieldprojetporteur'  >
                    <table style="border: 1px solid #57758D;clear: both;margin-top: 15px;width: 1050px;border-radius: 5px;">
                        <th style="background-color: #E3ECF2; padding-left:6px; color: midnightblue;height:20px" ><?php echo affiche('TXT_SELECTEDPROJET'); ?></th>
                        <tr>
                            <td>
                                <div   style="width:520px;text-align:left;color: midnightblue;margin-left: 5px;height:20px;vertical-align: middle" id="copieprojet" name="copieprojet" value="<?php echo $numero; ?>" ><?php echo $numero . ' - ' . stripslashes(str_replace("''", "'", substr($titre, 0, 60))) . '...'; ?></div>
                            </td>
                        </tr>
                    </table>

                    <?php if (isset($_GET['idprojet']) && !empty($_GET['idprojet'])) { ?>
                        <br>
                        <fieldset id="ident" style="border-color: #5D8BA2;width: 1008px;padding-bottom:10px;padding-top:8px" >
                            <legend><?php echo affiche('TXT_RECHERCHEUSER'); ?></legend>
                            <table>
                                <tr>
                                    <td valign="top" style="width: 80px;"><input type="text" style="width: 260px;border-radius: 8px;padding: 3px;font-size:1.2em" placeholder="<?php echo affiche('TXT_NOMUTILISATEUR'); ?>" name="nom" id="nom" data-dojo-type="dijit/form/ValidationTextBox" autocomplete="on"/></td>
                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td valign="top" style="width: 100px;text-align: left;"><input type="text" style="width: 260px;border-radius: 8px;padding: 3px;font-size: 1.2em" placeholder="<?php echo affiche('TXT_PRENOMUTILISATEUR'); ?>" name="prenom" id="prenom" data-dojo-type="dijit/form/ValidationTextBox" autocomplete="on"/></td>
                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td>&nbsp;&nbsp</td>
                                    <td valign="bottom" ><input name ="rechercheUserporteur" type="submit" ondragover="tous"  value="<?php echo affiche('TXT_BOUTONRECHERCHE'); ?>"  class="maj"
                                        onclick="if (document.getElementById('nom').value === '' && document.getElementById('prenom').value === '') {document.getElementById('nom').value = '*';}"/>
                                    </td>
                                </tr>
                            </table>
                        </fieldset>
                    <?php } ?>
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
