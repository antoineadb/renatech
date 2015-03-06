<?php
include_once 'outils/toolBox.php';
include_once 'outils/constantes.php';
include_once 'decide-lang.php';

if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
$idcentrale = $manager->getSingle2("SELECT  idcentrale_centrale FROM  loginpassword,utilisateur WHERE  idlogin = idlogin_loginpassword AND pseudo = ?", $_SESSION['pseudo']);
if (!empty($_POST['dateprojet'])) {
    $dateprojet = $_POST['dateprojet'];
    $req = "SELECT distinct idprojet, titre, datedebutprojet, numero, dateprojet FROM concerne, projet WHERE idprojet_projet = idprojet and idcentrale_centrale=? and idstatutprojet_statutprojet=? and dateprojet = ?"
            . " union "
            . "SELECT distinct idprojet, titre, datedebutprojet, numero, dateprojet FROM concerne, projet WHERE idprojet_projet = idprojet and idcentrale_centrale=? and idstatutprojet_statutprojet=? and dateprojet = ?";
    $param = array($idcentrale, ENCOURSREALISATION,$dateprojet,$idcentrale, ACCEPTE ,$dateprojet);
}elseif (!empty($_POST['numprojet']) && $_POST['numprojet'] != '*') {
    if (!empty($_POST['numprojet'])) {
        $numprojet = $_POST['numprojet'];
        $req = "SELECT idprojet, titre, datedebutprojet, numero, dateprojet FROM concerne, projet WHERE idprojet_projet = idprojet and numero like ? and idcentrale_centrale=? and idstatutprojet_statutprojet=?"
                . "union "
                . "SELECT idprojet, titre, datedebutprojet, numero, dateprojet FROM concerne, projet WHERE idprojet_projet = idprojet and numero like ? and idcentrale_centrale=? and idstatutprojet_statutprojet=?";

        $param = array('%' . $numprojet . '%', $idcentrale, ENCOURSREALISATION,'%' . $numprojet . '%', $idcentrale, ACCEPTE);

    }
} else {
    $req = "SELECT distinct idprojet, titre, datedebutprojet, numero, dateprojet FROM concerne, projet WHERE idprojet_projet = idprojet and idcentrale_centrale=? and  idstatutprojet_statutprojet=? "
            . " union "
            . "SELECT distinct idprojet, titre, datedebutprojet, numero, dateprojet FROM concerne, projet WHERE idprojet_projet = idprojet and idcentrale_centrale=? and  idstatutprojet_statutprojet=?";
    $param = array($idcentrale, ENCOURSREALISATION,$idcentrale,ACCEPTE);
}
if (isset($_POST['numprojet']) && isset($_POST['dateprojet'])) {
    if (!empty($_POST['numprojet']) && !empty($_POST['dateprojet'])) {
        $req = "SELECT idprojet, titre, datedebutprojet, numero, dateprojet FROM concerne, projet WHERE idprojet_projet = idprojet and numero like ? and idstatutprojet_statutprojet=? and dateprojet=? and idcentrale_centrale=?"
             . "union"
             . "SELECT idprojet, titre, datedebutprojet, numero, dateprojet FROM concerne, projet WHERE idprojet_projet = idprojet and numero like ? and idstatutprojet_statutprojet=? and dateprojet=? and idcentrale_centrale=?";
        $param = array('%' . $numprojet . '%', ENCOURSREALISATION,$dateprojet, $idcentrale,'%' . $numprojet . '%', ACCEPTE,$dateprojet, $idcentrale);
    }
}
$row = $manager->getListbyArray($req, $param);
$fprow = fopen('tmp/projetAdmin.json', 'w');
$datausercompte = "";
fwrite($fprow, '{"items": [');
for ($i = 0; $i < count($row); $i++) {
    $datausercompte = "" . '{"dateprojet":' . '"' . $row[$i]['dateprojet'] . '"' . "," .
            '"numero":' . '"' . $row[$i]['numero'] . '"' . "," . '"idprojet":' . '"' . $row[$i]['idprojet'] . '"' . ","
            . '"datedebutprojet":' . '"' . $row[$i]['datedebutprojet'] . '"' . ","
            . '"titre":' . '"' . filtredonnee($row[$i]['titre']) . '"' . "},";
    fputs($fprow, $datausercompte);
    fwrite($fprow, '');
}
fwrite($fprow, ']}');
$json_fileuserCompte = "tmp/projetAdmin.json";
$jsonusercompte1 = file_get_contents($json_fileuserCompte);
$jsonUsercompte = str_replace('},]}', '}]}', $jsonusercompte1);
file_put_contents($json_fileuserCompte, $jsonUsercompte);
fclose($fprow);
chmod('tmp/projetAdmin.json', 0777);
if (count($row) == 0) {
    echo TXT_NORESULT;
} else {
    echo '<div style="text-align:center;width: auto;height:25px" >' . TXT_NBRESULT . ' :' . count($row) . '</div>';
}
?>
<div style="height: 350px;">
    <div id="grideusercompte" ></div>
    <script>
        var grideusercompte, dataStore, store;
        require([
            "dojox/grid/DataGrid",
            "dojo/store/Memory",
            "dojo/data/ObjectStore",
            "dojo/request",
            "dojo/domReady!"
        ], function(DataGrid, Memory, ObjectStore, request) {
            request.get("<?php echo '/' . REPERTOIRE; ?>/tmp/projetAdmin.json", {
                handleAs: "json"
            }).then(function(data) {
                store = new Memory({data: data.items});
                dataStore = new ObjectStore({objectStore: store});
                function hrefFormatterDatedemande(dateDemande, idx) {
                    var item = grideusercompte.getItem(idx);
                    var idprojet = item.idprojet;
                    return "<a  href=\"<?php echo '/' . REPERTOIRE; ?>/recherche_projetadmin/<?php echo $lang; ?>/" + idprojet + "\">" + dateDemande + "</a>";
                }
                function hrefFormatterdatedebutprojet(datedebutprojet, idx) {
                    var item = grideusercompte.getItem(idx);
                    var idprojet = item.idprojet;
                    return "<a  href=\"<?php echo '/' . REPERTOIRE; ?>/recherche_projetadmin/<?php echo $lang; ?>/" + idprojet + "\">" + datedebutprojet + "</a>";
                }
                function hrefFormatterNumero(numero, idx) {
                    var item = grideusercompte.getItem(idx);
                    var idprojet = item.idprojet;
                    return "<a  href=\"<?php echo '/' . REPERTOIRE; ?>/recherche_projetadmin/<?php echo $lang; ?>/" + idprojet + "\">" + numero + "</a>";
                }
                function hrefFormatterTitre(titre, idx) {
                    var item = grideusercompte.getItem(idx);
                    var idprojet = item.idprojet;
                    return "<a  href=\"<?php echo '/' . REPERTOIRE; ?>/recherche_projetadmin/<?php echo $lang; ?>/" + idprojet + "\">" + titre + "</a>";
                }

                grideusercompte = new DataGrid({
                    store: dataStore,
                    query: {id: "*"},
                    structure: [
                        {name: "<?php echo TXT_DATEDEMANDE; ?>", field: "dateprojet", width: "137px", formatter: hrefFormatterDatedemande},
                        {name: "<?php echo TXT_DATEDEBUTPROJET; ?>", field: "datedebutprojet", width: "137px", formatter: hrefFormatterdatedebutprojet},
                        {name: "<?php echo TXT_NUMERO; ?>", field: "numero", width: "120px", formatter: hrefFormatterNumero},
                        {name: "<?php echo TXT_TITREPROJET; ?>", field: "titre", width: "auto", formatter: hrefFormatterTitre},
                    ]
                }, "grideusercompte");
                grideusercompte.startup();
            });
        });
    </script>
</div>
<?php BD::deconnecter(); ?>