<?php
session_start();
include_once 'class/Manager.php';
include_once 'outils/toolBox.php';
$db = BD::connecter();
$manager = new Manager($db);
include 'decide-lang.php';
include 'outils/constantes.php';

if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
include 'html/header.html';
$idcentrale = $manager->getSingle2("SELECT idcentrale_centrale FROM loginpassword,utilisateur WHERE idlogin = idlogin_loginpassword and pseudo =?", $_SESSION['pseudo']);
$manager->exeRequete("drop table if exists tmpoldproject");
$manager->getRequete("create table tmpoldproject as (select idprojet,numero,titre,acronyme,refinterneprojet,datedebutprojet,dureeprojet,idperiodicite_periodicite from projet,concerne where idprojet_projet=idprojet and idstatutprojet_statutprojet=? "
        . "and idcentrale_centrale=?)", array(ENCOURSREALISATION, $idcentrale));
$manager->exeRequete("ALTER TABLE tmpoldproject ADD COLUMN calcfinprojet date;");
$manager->exeRequete("ALTER TABLE tmpoldproject ADD COLUMN finprojetproche date;");
$arrayprojet = $manager->getList("select * from tmpoldproject");
$nbarrayprojet = count($arrayprojet);
//REMPLISSAGE DE LA COLONNE CALCFINPROJET DE LA TABLE OLDPROJECT
for ($i = 0; $i < $nbarrayprojet; $i++) {
    if ($arrayprojet[$i]['idperiodicite_periodicite'] == JOUR) {
        $datedepart = strtotime($arrayprojet[$i]['datedebutprojet']);
        $duree = ($arrayprojet[$i]['dureeprojet']);
        $dateFin = date('Y-m-d', strtotime('+' . $duree . 'day', $datedepart));
        $dureeproche =$duree-15;
        $dateFinproche = date('Y-m-d', strtotime('+' . $dureeproche . 'day', $datedepart));
        $manager->getRequete("update tmpoldproject set calcfinprojet=?,finprojetproche=? where idprojet=? ", array($dateFin,$dateFinproche, $arrayprojet[$i]['idprojet']));
    } elseif ($arrayprojet[$i]['idperiodicite_periodicite'] == MOIS) {
        $datedepart = strtotime($arrayprojet[$i]['datedebutprojet']);
        $duree = ($arrayprojet[$i]['dureeprojet']);
        $dateFin = date('Y-m-d', strtotime('+' . $duree . 'month', $datedepart));
        $dureeproche =($duree*30)-15;
        $dateFinproche = date('Y-m-d', strtotime('+' . $dureeproche . 'day', $datedepart));
        $manager->getRequete("update tmpoldproject set calcfinprojet=?,finprojetproche=? where idprojet=? ", array($dateFin,$dateFinproche, $arrayprojet[$i]['idprojet']));
    } elseif ($arrayprojet[$i]['idperiodicite_periodicite'] == ANNEE) {
        $datedepart = strtotime($arrayprojet[$i]['datedebutprojet']);
        $duree = ($arrayprojet[$i]['dureeprojet']);
        $dateFin = date('Y-m-d', strtotime('+' . $duree . 'year', $datedepart));
        $dureeproche =($duree*365)-15;
        $dateFinproche = date('Y-m-d', strtotime('+' . $dureeproche . 'day', $datedepart));         
        $manager->getRequete("update tmpoldproject set calcfinprojet=?,finprojetproche=? where idprojet=? ", array($dateFin,$dateFinproche, $arrayprojet[$i]['idprojet']));
    }
}
$arrayoldprojet = $manager->getList("select * from tmpoldproject");

$fprow = fopen('tmp/oldprojet.json', 'w');
$dataoldprojet = "";
$nbarrayuserprojet = count($arrayoldprojet);
fwrite($fprow, '{"items": [');
for ($i = 0; $i < $nbarrayuserprojet; $i++) {
    $dataoldprojet = "" . '{"numero":' . '"' . $arrayoldprojet[$i]['numero'] . '"'
            . "," . '"refinterneprojet":' . '"' . filtredonnee($arrayoldprojet[$i]['refinterneprojet']) . '"'
            . "," . '"acronyme":' . '"' . filtredonnee($arrayoldprojet[$i]['acronyme']) . '"' . ","
            . '"titre":' . '"' . filtredonnee($arrayoldprojet[$i]['titre']) . '"' . ","
            . '"datedebutprojet":' . '"' . $arrayoldprojet[$i]['datedebutprojet'] . '"' . ","
            . '"calcfinproche":' . '"' . $arrayoldprojet[$i]['finprojetproche'] . '"' . ","
            . '"calcfinprojet":' . '"' . $arrayoldprojet[$i]['calcfinprojet'] . '"' . "},";

    fputs($fprow, $dataoldprojet);
    fwrite($fprow, '');
}
fwrite($fprow, ']}');
$json_fileoldprojet = "tmp/oldprojet.json";
$jsonusercompte1 = file_get_contents($json_fileoldprojet);
$jsonUsercompte = str_replace('},]}', '}]}', $jsonusercompte1);
file_put_contents($json_fileoldprojet, $jsonUsercompte);
fclose($fprow);
chmod('tmp/oldprojet.json', 0777);
?>
<div id="global">
    <?php include 'html/entete.html'; ?>
    <div style="margin-top: 70px;">
        <?php include_once 'outils/bandeaucentrale.php'; ?>
    </div>
    <fieldset id="ident" style="border-color: #5D8BA2;height: 450px;margin-top: 20px;" >
        <legend style="color: #5D8BA2;font-size: 1.2em"><b><?php echo TXT_LISTEPROJETENCOURSREALISATION; ?></b></legend>
        <div style="height: 400px;">
            <div style="text-align:center;font-size:12px;"><?php echo TXT_NBPROJET . ' <b>' . $nbarrayuserprojet . '</b>'; ?></div>
            <div style="width:1012px;text-align: center;;margin-bottom:10px;font-size: 1.2em;font-weight: bolder "  ><?php echo ''; ?></div>
            <div id="gridoldprojet" ></div>
        </div>       
        <script>
           
            var gridoldprojet, dataStore, store;
            require([
                "dojox/grid/DataGrid",
                "dojo/store/Memory",
                "dojo/data/ObjectStore",
                "dojo/request",
                "dojo/date/stamp",
                "dojo/date/locale",
                "dijit/form/Button",
                "dojo/domReady!"
            ], function(DataGrid, Memory, ObjectStore, request, stamp, locale) {
                request.get("<?php echo '/' . REPERTOIRE; ?>/tmp/oldprojet.json", {
                    handleAs: "json"
                }).then(function(data) {
                    store = new Memory({data: data.items});
                    dataStore = new ObjectStore({objectStore: store});

                    function formatDate(datum) {
                        var d = stamp.fromISOString(datum);
                        return locale.format(d, {selector: 'date', formatLength: 'long'});
                    }
                    function hrefFormatterNumero(numero, idx) {
                        var item = gridoldprojet.getItem(idx);
                        var centrale = item.libellecentrale;
                        return "<a  href=\"<?php echo '/' . REPERTOIRE; ?>/controler/controlestatutprojet.php?lang=<?php echo $lang; ?>&statut=8&numProjet=" + numero + "&centrale=" + centrale + "\">" + numero + "</a>";
                    }
                    gridoldprojet = new DataGrid({
                        store: dataStore,
                        query: {id: "*"},
                        structure: [
                            {name: "<?php echo TXT_DATEDEBUTPROJET; ?>", field: "datedebutprojet", width: "120px", formatter: formatDate},
                            {name: "<?php echo TXT_ACRONYME; ?>", field: "acronyme", width: "120px"},
                            {name: "<?php echo TXT_REFINTERNE; ?>", field: "refinterneprojet", width: "120px"},
                            {name: "<?php echo TXT_TITREPROJET; ?>", field: "titre", width: "auto"},
                            {name: "<?php echo TXT_NUMERO; ?>", field: "numero", width: "120px", formatter: hrefFormatterNumero},
                            {name: "<?php echo TXT_DATEFIN; ?>", field: "calcfinprojet", width: "120px", formatter: formatDate},
                            {name: "<?php echo TXT_DATEFIN.' proche'; ?>", field: "calcfinproche", width: "120px", formatter: formatDate}
                        ]
                    }, "gridoldprojet");

                    //----------------------------------------------------------------------------------------------------------------------
                    //                                        TEST METHODE
                    //----------------------------------------------------------------------------------------------------------------------
                     
                    dojo.connect(dijit.byId('gridoldprojet'), 'onStyleRow', this, function(row) {
                        var item = gridoldprojet.getItem(row.index);
                        if (item) {                           
                            var datefin = gridoldprojet.store.getValue(item, "calcfinprojet", null);
                            var datefinProche = gridoldprojet.store.getValue(item, "calcfinproche", null);
                            var now = new Date();
                            var curr_month = now.getMonth();
                            if(curr_month<10){
                                curr_month="0"+curr_month;
                            }
                            var maintenant = new Date (now.getFullYear() ,curr_month , now.getDate());
                            var dateFin = new Date(datefin);
                            var datefinproche = new Date(datefinProche);    
                            var maint = maintenant;
                            var fin = dateFin;
                            var finp =datefinproche;
                            
                            if(maint>fin){
                                row.customStyles += "color:red;font-weight:bolder";
                            }else  if(maint >=finp){
                                row.customStyles += "color:darkgoldenrod";
                            }else{
                                row.customStyles += "color:darkgreen";
                            }
                        }
                        gridoldprojet.focus.styleRow(row);
                        gridoldprojet.edit.styleRow(row);
                    });
                    //----------------------------------------------------------------------------------------------------------------------
                    //                                        FIN TEST METHODE
                    //----------------------------------------------------------------------------------------------------------------------
                    gridoldprojet.startup();
                });
            });            
        </script>
        <script>
                function addDate(d, nb) {// additionne nb jours à une date
                    var d1 = d.getTime(), d2 = new Date();
                    d1 += 24 * 3600 * 1000 * nb
                    d2.setTime(d1)
                    return d2
                }
        </script>
    </fieldset>
    <table>
        <tr>
            <td><div style="background-color: darkgreen;height: 10px;width: 25px;border: 1px solid black; "></div></td>
            <td><label style="width:378px;margin-left: 5px"  ><?php echo TXT_DATENONDEPASSE; ?></label></td>
        </tr>
        <tr>
            <td><div style="background-color: darkgoldenrod;height: 10px;width: 25px;border: 1px solid black; "></div></td>
            <td><label style="width:378px;margin-left: 5px"  ><?php echo TXT_DATEPROCHE ?></label></td>
        </tr>
        <tr>
            <td><div style="background-color: red;height: 10px;width: 25px;border: 1px solid black; "></div></td>
            <td><label style="width:378px;margin-left: 5px"  ><?php echo TXT_DATEDEPASSE ?></label></td>
        </tr>
    </table>
    <?php include 'html/footer.html'; ?>
</div>

