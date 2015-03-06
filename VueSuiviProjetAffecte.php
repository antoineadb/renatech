<?php
session_start();
include('decide-lang.php');
include_once 'outils/toolBox.php';
include_once 'outils/constantes.php';
include_once 'class/Manager.php';
$db = BD::connecter();
$manager = new Manager($db);
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
				if (!isset($_SESSION['nom'])) {
    $_SESSION['nom'] = $_SESSION['nomConnect'];
}
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
include 'html/header.html';
//---------------------------------------------------------------------------------------------------------------------------------------------------
//                              CREATION DU PROJET JSON EN COURS
//---------------------------------------------------------------------------------------------------------------------------------------------------

$rowAffecte = $manager->getListbyArray("
SELECT p.numero,p.dateprojet,p.titre,s.libellestatutprojet,ce.libellecentrale
FROM utilisateurporteurprojet up,statutprojet s,projet p,utilisateur u,concerne co,centrale ce,loginpassword l
WHERE up.idprojet_projet = p.idprojet AND up.idutilisateur_utilisateur = u.idutilisateur AND s.idstatutprojet = co.idstatutprojet_statutprojet AND
co.idprojet_projet = p.idprojet AND  ce.idcentrale = co.idcentrale_centrale AND  l.idlogin = u.idlogin_loginpassword AND   l.pseudo =?", array($_SESSION['pseudo']));


$fpAffecte = fopen('tmp/projetaffecte.json', 'w');
$dataAffecte = "";
fwrite($fpAffecte, '{"items": [');
for ($i = 0; $i < count($rowAffecte); $i++) {
    $dataAffecte = "" . '{"numero":' . '"' . $rowAffecte[$i]['numero'] . '"' . "," . '"dateprojet":' . '"' . $rowAffecte[$i]['dateprojet'] . '"' . "," . '"titre":' . '"' .
            trim(stripslashes(str_replace("''", "'", trim($rowAffecte[$i]['titre'])))) . '"' . "," . '"libellestatutprojet":' . '"' . $rowAffecte[$i]['libellestatutprojet'] . '"'
            . "," . '"libellecentrale":' . '"' . $rowAffecte[$i]['libellecentrale'] . '"' . "},";
    fputs($fpAffecte, $dataAffecte);
    fwrite($fpAffecte, '');
}
fwrite($fpAffecte, ']}');
$json_fileAffecte = "tmp/projetaffecte.json";
$jsonAffecte1 = file_get_contents($json_fileAffecte);
$jsonAffecte = str_replace('},]}', '}]}', $jsonAffecte1);
file_put_contents($json_fileAffecte, $jsonAffecte);
fclose($fpAffecte);
chmod('tmp/projetaffecte.json', 0777);
?>
<div id="global">
    <?php include 'html/entete.html'; ?>
    <div style="width: 752px;">
        <div data-dojo-type="dijit/layout/AccordionContainer"   style="height: 400px;">
            <div data-dojo-type="dijit/layout/ContentPane"  title="<?php echo TXT_SUIVIPROJETAFFECTE; ?>" >
                <div id="gridaffecte" ></div>
                <script>
                    var gridaffecte, dataStore, store;
                    require([
                    "dojox/grid/DataGrid",
                    "dojo/store/Memory",
                    "dojo/data/ObjectStore",
                    "dojo/request",
                    "dojo/domReady!"
                    ], function(DataGrid, Memory, ObjectStore, request){
                    request.get("tmp/projetaffecte.json", {
                    handleAs: "json"
                    }).then(function(data){
                    store = new Memory({ data: data.items });
                    dataStore = new ObjectStore({ objectStore: store });
                    function hrefFormatter(numero,idx){
                    var item = gridaffecte.getItem(idx);//idx = index
                    var centrale =item.libellecentrale;//libelle centrale
                    return "<a  href=\"<?php echo '/'.REPERTOIRE; ?>/controler/controlestatutprojet.php?lang=<?php echo $lang; ?>&numProjet=" + numero+"&centrale="+centrale+"\">"+numero+"</a>";
                    }
                    gridaffecte = new DataGrid({
                    store: dataStore,
                    query: { id: "*" },
                    structure: [
                    { name: "Numéro", field: "numero", width: "auto",formatter: hrefFormatter},
                    { name: "Date", field: "dateprojet", width: "auto"},
                    { name: "Titre", field: "titre", width: "190px" },
                    { name: "Statut", field: "libellestatutprojet", width: "auto"},
                    { name: "Centrale", field: "libellecentrale", width: "auto" }
                    ]
                    }, "gridaffecte");
                    gridaffecte.startup();
                    });
                    });
                </script>
            </div>

        </div>
    </div>
    <br><br><br><br><?php include 'html/footer.html'; ?>
</div>
</body>
</html>
