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
    $req = "SELECT distinct idprojet,refinterneprojet,acronyme,titre,  numero, dateprojet FROM concerne, projet WHERE idprojet_projet = idprojet and idcentrale_centrale=? and idstatutprojet_statutprojet=? and dateprojet = ?";
    $param = array($idcentrale, ENCOURSREALISATION, $dateprojet);
}
if (!empty($_POST['numprojet']) && $_POST['numprojet'] != '*') {
    if (!empty($_POST['numprojet'])) {
        $numprojet = $_POST['numprojet'];
        $req = "SELECT idprojet, titre, datedebutprojet, numero, dateprojet FROM concerne, projet "
                . "WHERE idprojet_projet = idprojet and numero like ? and idcentrale_centrale=? ";

        $param = array('%' . $numprojet . '%', $idcentrale);
    }
} else {
    $req = "SELECT distinct idprojet,refinterneprojet,acronyme, titre, datedebutprojet, numero, dateprojet FROM concerne, projet WHERE idprojet_projet = idprojet and idcentrale_centrale=? ";
    $param = array($idcentrale);
}
if (isset($_POST['numprojet']) && isset($_POST['dateprojet'])) {
    if (!empty($_POST['numprojet']) && !empty($_POST['dateprojet'])) {
        $req = "SELECT idprojet, titre,refinterneprojet,acronyme,numero, dateprojet FROM concerne, projet WHERE idprojet_projet = idprojet and numero like ? and idcentrale_centrale=?";
        $param = array('%' . $numprojet . '%', $dateprojet, $idcentrale);
    }
}
$row = $manager->getListbyArray($req, $param);
$fprow = fopen('tmp/projetTrace.json', 'w');

$datausercompte = "";
fwrite($fprow, '{"items": [');
for ($i = 0; $i < count($row); $i++) {
    if (!empty($row[$i]['refinterneprojet'])) {
        $refinterneprojet = filtredonnee($row[$i]['refinterneprojet']);
    } else {
        $refinterneprojet = '';
    }
    if (!empty($row[$i]['acronyme'])) {
        $acronyme = filtredonnee($row[$i]['acronyme']);
    } else {
        $acronyme = '';
    }
    $datausercompte = "" . '{"dateprojet":' . '"' . $row[$i]['dateprojet'] . '"' . "," .
            '"numero":' . '"' . $row[$i]['numero'] . '"' . "," . '"idprojet":' . '"' . $row[$i]['idprojet'] . '"' . ","
            . '"refinterneprojet":' . '"' . $refinterneprojet . '"' . ","
            . '"acronyme":' . '"' . $acronyme . '"' . ","
            . '"titre":' . '"' . filtredonnee($row[$i]['titre']) . '"' . "},";
    fputs($fprow, $datausercompte);
    fwrite($fprow, '');
}
fwrite($fprow, ']}');
$json_filetraceprojet = "tmp/projetTrace.json";
$jsontraceprojet1 = file_get_contents($json_filetraceprojet);
$jsontraceprojet = str_replace('},]}', '}]}', $jsontraceprojet1);
file_put_contents($json_filetraceprojet, $jsontraceprojet);
fclose($fprow);
chmod('tmp/projetTrace.json', 0777);
if (count($row) == 0) {
    echo TXT_NORESULT;
} else {
    echo '<div style="text-align:center;width: auto;height:25px" >' . TXT_NBRESULT . ' :' . count($row) . '</div>';
}
?>
<div style="height: 350px;">
    <div id="gridtraceprojet" ></div>
    <script>
        var gridtraceprojet, dataStore, store;
        require([
            "dojox/grid/DataGrid",
            "dojo/store/Memory",
            "dojo/data/ObjectStore",
            "dojo/request",
            "dojo/domReady!"
        ], function(DataGrid, Memory, ObjectStore, request) {
            request.get("<?php echo '/' . REPERTOIRE; ?>/tmp/projetTrace.json", {
                handleAs: "json"
            }).then(function(data) {
                store = new Memory({data: data.items});
                dataStore = new ObjectStore({objectStore: store});
                function hrefFormatterDatedemande(dateDemande, idx) {
                    var item = gridtraceprojet.getItem(idx);
                    var idprojet = item.idprojet;
                    return "<a  href=\"<?php echo '/' . REPERTOIRE; ?>/traceProjet/<?php echo $lang; ?>/" + idprojet + "\">" + dateDemande + "</a>";
                }

                gridtraceprojet = new DataGrid({
                    store: dataStore,
                    query: {id: "*"},
                    structure: [
                        {name: "<?php echo TXT_DATEDEMANDE; ?>", field: "dateprojet", width: "137px", formatter: hrefFormatterDatedemande},
                        {name: "<?php echo TXT_NUMERO; ?>", field: "numero", width: "120px"},
                        {name: "<?php echo TXT_REFINTERNE; ?>", field: "refinterneprojet", width: "120px"},
                        {name: "<?php echo TXT_ACRONYME; ?>", field: "acronyme", width: "120px"},
                        {name: "<?php echo TXT_TITREPROJET; ?>", field: "titre", width: "auto"}
                    ]
                }, "gridtraceprojet");
                gridtraceprojet.startup();
            });
        });
    </script>
</div>
<?php
BD::deconnecter();
//include 'html/footer.html';
