<?php
session_start();
include('decide-lang.php');
include_once 'class/Manager.php';
include_once 'class/Securite.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include_once 'outils/toolBox.php';
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
include 'html/header.html';
?>
<script src="<?php echo '/' . REPERTOIRE ?>/js/ajax.js"></script>
<div id="global">
    <?php
    include 'html/entete.html';
    ?>
    <div style="padding-top: 75px;">
        <?php include'outils/bandeaucentrale.php'; ?>
    </div>
    <?php
    $row = $manager->getList("select * from demande_faisabilite");
    $fp = fopen('tmp/demandeFaisabilite.json', 'w');
    $data = "";
    fwrite($fp, '{"items": [');
    for ($i = 0; $i < count($row); $i++) {
        $data = ""
                . '{"nom_demandeur":' . '"' . $row[$i]['nom_demandeur'] . '"' . ","
                . '"email_demandeur":' . '"' . $row[$i]['email_demandeur'] . '"' . ","
                . '"objet_demande":' . '"' . $row[$i]['objet_demande'] . '"' . ","
                . '"date_demande":' . '"' . $row[$i]['date_demande'] . '"' . "},";
        fputs($fp, $data);
        fwrite($fp, '');
        $s_centrale = "";
    }
    fwrite($fp, ']}');
    $json_file = "tmp/demandeFaisabilite.json";
    $json1 = file_get_contents($json_file);
    $json = str_replace('},]}', '}]}', $json1);
    file_put_contents($json_file, $json);
    fclose($fp);
    $nb=count($row);
    ?>
    <div style="margin-top:50px;width:1050px" >
        <form  method="post" action="#"  name='logs' >            
            <fieldset id="ident">
                <legend><?php echo TXT_FEASIBILITYREQUEST.': '.$nb; ?></legend>
                <div id="grid" style="height: 350px" ></div>
                <script>
                    var grid, dataStore, store;
                    require([
                        "dojox/grid/DataGrid",
                        "dojo/store/Memory",
                        "dojo/data/ObjectStore",
                        "dojo/request",
                        "dojo/date/stamp",
                        "dojo/date/locale",
                        "dojo/domReady!"
                    ], function (DataGrid, Memory, ObjectStore, request, stamp, locale) {
                        request.get("<?php echo '/' . REPERTOIRE; ?>/tmp/demandeFaisabilite.json", {
                            handleAs: "json"
                        }).then(function (data) {
                            store = new Memory({data: data.items});
                            dataStore = new ObjectStore({objectStore: store});
                            
                            function formatDate(datum) {
                                var d = stamp.fromISOString(datum);
                                return locale.format(d, {selector: 'date', formatLength: 'long'});
                            }

                            grid = new DataGrid({
                                store: dataStore,
                                query: {id: "*"},
                                structure: [
                                    {name: "Nom demandeur", field: "nom_demandeur", width: "auto"},
                                    {name: "Email demandeur", field: "email_demandeur", width: "auto"},    
                                    {name: "Objet de la demande", field: "objet_demande", width: "auto"},
                                    {name: "date de la demande", field: "date_demande", width: "auto", formatter: formatDate}  
                                ]
                            }, "grid");
                            grid.startup();
                        });
                    });
                </script>



            </fieldset>
        </form>
    </div>
    <?php
    include 'html/footer.html';
    BD::deconnecter();
    ?>
</div>
</body>
</html>