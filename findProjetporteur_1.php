<?php
include_once 'outils/toolBox.php';

if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    echo '<script>window.location.replace("erreurlogin.php")</script>';
}
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER

$idcentrale = $manager->getSingle2("SELECT  idcentrale_centrale FROM  loginpassword,utilisateur WHERE  idlogin = idlogin_loginpassword AND pseudo = ?", $_SESSION['pseudo']);

if (!empty($_POST['dateprojet'])) {
    $dateprojet = $_POST['dateprojet'];
    $req = "SELECT distinct idprojet, titre, datedebutprojet, numero, dateprojet FROM concerne, projet WHERE idprojet_projet = idprojet and idcentrale_centrale=? and idstatutprojet_statutprojet=3 and dateprojet = ?
            union
            SELECT distinct idprojet, titre, datedebutprojet, numero, dateprojet FROM concerne, projet WHERE idprojet_projet = idprojet and idcentrale_centrale=? and idstatutprojet_statutprojet=6  and dateprojet = ?
            union
            SELECT distinct idprojet, titre, datedebutprojet, numero, dateprojet FROM concerne, projet WHERE idprojet_projet = idprojet and idcentrale_centrale=? and idstatutprojet_statutprojet=8  and dateprojet = ?
            ";
    $param = array($idcentrale, $dateprojet, $idcentrale, $dateprojet, $idcentrale, $dateprojet);
}

if (isset($_POST['numprojet']) && $_POST['numprojet'] != '*') {
    if (!empty($_POST['numprojet'])) {
        $numprojet = $_POST['numprojet'];
        $req = "SELECT idprojet, titre, datedebutprojet, numero, dateprojet FROM concerne, projet WHERE idprojet_projet = idprojet and numero like ? and idcentrale_centrale=? and idstatutprojet_statutprojet=3
        union
        SELECT idprojet, titre, datedebutprojet, numero, dateprojet FROM concerne, projet WHERE idprojet_projet = idprojet and numero like ? and idcentrale_centrale=? and idstatutprojet_statutprojet=6
        union
        SELECT idprojet, titre, datedebutprojet, numero, dateprojet FROM concerne, projet WHERE idprojet_projet = idprojet and numero like ? and idcentrale_centrale=? and idstatutprojet_statutprojet=8 ";

        $param = array('%' . $numprojet . '%', $idcentrale, '%' . $numprojet . '%', $idcentrale, '%' . $numprojet . '%', $idcentrale);
    }
} else {
    $req = "SELECT distinct idprojet, titre, datedebutprojet, numero, dateprojet FROM concerne, projet WHERE idprojet_projet = idprojet and idcentrale_centrale=? and idstatutprojet_statutprojet=3 
    union
    SELECT distinct idprojet, titre, datedebutprojet, numero, dateprojet FROM concerne, projet WHERE idprojet_projet = idprojet and idcentrale_centrale=? and idstatutprojet_statutprojet=6 
    union
    SELECT distinct idprojet, titre, datedebutprojet, numero, dateprojet FROM concerne, projet WHERE idprojet_projet = idprojet and idcentrale_centrale=? and idstatutprojet_statutprojet=8 
    ";
    $param = array($idcentrale, $idcentrale, $idcentrale);
}

if (isset($_POST['numprojet']) && isset($_POST['dateprojet'])) {
    if (!empty($_POST['numprojet']) && !empty($_POST['dateprojet'])) {
        $req = "SELECT idprojet, titre, datedebutprojet, numero, dateprojet FROM concerne, projet WHERE idprojet_projet = idprojet and numero like ? and idstatutprojet_statutprojet=3 and dateprojet=? and idcentrale_centrale=?
                union
                SELECT idprojet, titre, datedebutprojet, numero, dateprojet FROM concerne, projet WHERE idprojet_projet = idprojet and numero like ? and idstatutprojet_statutprojet=6  and dateprojet=? and idcentrale_centrale=?
                union
                SELECT idprojet, titre, datedebutprojet, numero, dateprojet FROM concerne, projet WHERE idprojet_projet = idprojet and numero like ?  and idstatutprojet_statutprojet=8 and dateprojet=? and idcentrale_centrale=?";

//        $req = "SELECT idprojet, titre, datedebutprojet, numero, dateprojet FROM concerne, projet WHERE idprojet_projet = idprojet and numero like? and idstatutprojet_statutprojet=3 and idstatutprojet_statutprojet=6 and idstatutprojet_statutprojet=8 and dateprojet=? and idcentrale_centrale=? order by idprojet asc";
        $param = array('%' . $numprojet . '%', $dateprojet, $idcentrale, '%' . $numprojet . '%', $dateprojet, $idcentrale, '%' . $numprojet . '%', $dateprojet, $idcentrale);
    }
}

$row = $manager->getListbyArray($req, $param);

$fprow = fopen('tmp/projetPorteur.json', 'w');
$datausercompte = "";
fwrite($fprow, '{"items": [');
for ($i = 0; $i < count($row); $i++) {
    $datausercompte = "" . '{"dateprojet":' . '"' . $row[$i]['dateprojet'] . '"' . "," .
            '"numero":' . '"' . $row[$i]['numero'] . '"' . "," . '"idprojet":' . '"' . $row[$i]['idprojet'] . '"' . ","
            . '"datedebutprojet":' . '"' . $row[$i]['datedebutprojet'] . '"' . ","
            . '"titre":' . '"' . stripslashes(str_replace("''", "'", trim($row[$i]['titre']))) . '"' . "},";
    fputs($fprow, $datausercompte);
    fwrite($fprow, '');
}
fwrite($fprow, ']}');
$json_fileuserCompte = "tmp/projetPorteur.json";
$jsonusercompte1 = file_get_contents($json_fileuserCompte);
$jsonUsercompte = str_replace('},]}', '}]}', $jsonusercompte1);
file_put_contents($json_fileuserCompte, $jsonUsercompte);
fclose($fprow);
chmod('tmp/projetPorteur.json', 0777);
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
        ], function(DataGrid, Memory, ObjectStore, request){
            request.get("tmp/projetPorteur.json", {
                handleAs: "json"
            }).then(function(data){
                store = new Memory({ data: data.items });
                dataStore = new ObjectStore({ objectStore: store });
                function hrefFormatterDatedemande(dateDemande,idx){
                    var item = grideusercompte.getItem(idx);
                    var idprojet =item.idprojet;
                    return "<a  href=\"porteurprojet.php?lang=<?php echo $lang; ?>&idprojet="+idprojet+"\">"+dateDemande+"</a>";
                }
                function hrefFormatterdatedebutprojet(datedebutprojet,idx){
                    var item = grideusercompte.getItem(idx);
                    var idprojet =item.idprojet;
                    return "<a  href=\"porteurprojet.php?lang=<?php echo $lang; ?>&idprojet="+idprojet+"\">"+datedebutprojet+"</a>";
                }                    
                function hrefFormatterNumero(numero,idx){
                    var item = grideusercompte.getItem(idx);
                    var idprojet =item.idprojet;
                    return "<a  href=\"porteurprojet.php?lang=<?php echo $lang; ?>&idprojet="+idprojet+"\">"+numero+"</a>";
                }
                function hrefFormatterTitre(titre,idx){
                    var item = grideusercompte.getItem(idx);
                    var idprojet =item.idprojet;
                    return "<a  href=\"porteurprojet.php?lang=<?php echo $lang; ?>&idprojet="+idprojet+"\">"+titre+"</a>";
                }

                grideusercompte = new DataGrid({
                    store: dataStore,
                    query: { id: "*" },
                    structure: [
                        { name: "Date de demande", field: "dateprojet", width: "auto",formatter: hrefFormatterDatedemande},
                        { name: "Date de début de projet", field: "datedebutprojet", width: "auto",formatter: hrefFormatterdatedebutprojet},
                        { name: "Numéro", field: "numero", width: "auto" ,formatter: hrefFormatterNumero},
                        { name: "Titre", field: "titre", width: "220px",formatter: hrefFormatterTitre},
                    ]
                }, "grideusercompte");
                grideusercompte.startup();
            });
        });
    </script>
</div>
<?php BD::deconnecter(); ?>