<?php
include 'decide-lang.php';
include_once 'outils/toolBox.php';
include_once 'class/Manager.php';
include_once 'outils/constantes.php';
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER

$idcentrale = $manager->getSingle2("SELECT idcentrale_centrale FROM loginpassword,utilisateur WHERE  idlogin = idlogin_loginpassword and pseudo =?", $_SESSION['pseudo']);
$manager->exeRequete("drop table if exists tmpdelete;");
//CREATION DE LA TABLE TEMPORAIRE
$manager->exeRequete("create table tmpdelete as SELECT  distinct p.idprojet,p.dateprojet,co.idstatutprojet_statutprojet,p.numero,p.titre,p.acronyme,co.idcentrale_centrale from projet p ,projet p1, concerne co where p.titre = p1.titre and p.idprojet!= p1.idprojet and co.idprojet_projet = p.idprojet order by titre asc");

$row = $manager->getList2("select distinct on (t2.idprojet)* from tmpdelete t1,tmpdelete t2 where t1.idcentrale_centrale=? and t1.titre=t2.titre ", $idcentrale);

$fprow = fopen('tmp/projetsDoublon.json', 'w');
$datausercompte = "";
fwrite($fprow, '{"items": [');
for ($i = 0; $i < count($row); $i++) {
    $libellestatut = $manager->getSingle2("SELECT libellestatutprojet FROM statutprojet where idstatutprojet=?", $row[$i]['idstatutprojet_statutprojet']);
    $libellecentrale = $manager->getSingle2("SELECT libellecentrale FROM centrale where idcentrale=?", $row[$i]['idcentrale_centrale']);
    $datausercompte = "" . '{"dateprojet":' . '"' . $row[$i]['dateprojet'] . '"' . "," .
            '"numero":' . '"' . $row[$i]['numero'] . '"' . "," . '"idprojet":' . '"' . $row[$i]['idprojet'] . '"' . ","
            . '"acronyme":' . '"' . trim($row[$i]['acronyme']) . '"' . ","
            . '"libellestatut":' . '"' . $libellestatut . '"' . ","
            . '"idcentrale_centrale":' . '"' . $row[$i]['idcentrale_centrale'] . '"' . ","
            . '"centrale":' . '"' . $libellecentrale . '"' . ","
            . '"titre":' . '"' . filtredonnee($row[$i]['titre']) . '"' . "},";
    fputs($fprow, $datausercompte);
    fwrite($fprow, '');
}
fwrite($fprow, ']}');
$json_fileuserCompte = "tmp/projetsDoublon.json";
$jsonusercompte1 = file_get_contents($json_fileuserCompte);
$jsonUsercompte = str_replace('},]}', '}]}', $jsonusercompte1);
file_put_contents($json_fileuserCompte, $jsonUsercompte);
fclose($fprow);
chmod('tmp/projetsDoublon.json', 0777);
if (count($row) == 0) {
    echo TXT_NORESULT;
} else {
    echo '<div style="text-align:center;width: auto;height:25px" >' .TXT_NBRESULT . ' :' . count($row) . '</div>';
}
?>
<div style="height: 500px;">
    <div id="griddoublonsprojet" ></div>
    <script>
        var griddoublonsprojet, dataStore, store;
        require([
            "dojox/grid/DataGrid",
            "dojo/store/Memory",
            "dojo/data/ObjectStore",
            "dojo/request",
            "dojo/date/stamp",
            "dojo/date/locale",
            "dojo/domReady!"
        ], function(DataGrid, Memory, ObjectStore, request, stamp, locale) {
            request.get("<?php echo '/'.REPERTOIRE; ?>/tmp/projetsDoublon.json", {
                handleAs: "json"
            }).then(function(data) {
                store = new Memory({data: data.items});
                dataStore = new ObjectStore({objectStore: store});

                function formatDate(datum) {
                    var d = stamp.fromISOString(datum);
                    return locale.format(d, {selector: 'date', formatLength: 'long'});
                }

                griddoublonsprojet = new DataGrid({
                    store: dataStore,
                    query: {id: "*"},
                    structure: [
                        {name: "<?php echo TXT_DATEDEMANDE; ?>", field: "dateprojet", width: "130px", formatter: formatDate},
                        {name: "<?php echo TXT_NUMERO; ?>", field: "numero", width: "80px"},
                        {name: "<?php echo TXT_TITREPROJET; ?>", field: "titre", width: "auto"},
                        {name: "<?php echo TXT_ACRONYME; ?>", field: "acronyme", width: "90px"},
                        {name: "<?php echo TXT_CENTRALE; ?>", field: "centrale", width: "80px"},
                        {name: "<?php echo TXT_STATUTPROJET; ?>", field: "libellestatut", width: "100px"}
                    ]
                }, "griddoublonsprojet");
                griddoublonsprojet.startup();
            });
        });
    </script>
</div>
<?php BD::deconnecter(); ?>