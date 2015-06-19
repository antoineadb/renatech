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
$idcentrale = $manager->getSingle2("SELECT idcentrale_centrale FROM loginpassword,utilisateur WHERE idlogin = idlogin_loginpassword and pseudo =?", $_SESSION['pseudo']);
$arrayuserprojet = $manager->getList2("SELECT p.acronyme,u.idutilisateur,u.nom,u.prenom,p.titre,p.numero,p.refinterneprojet,ua.dateaffectation FROM utilisateur u,utilisateuradministrateur ua,projet p,concerne c
WHERE ua.idprojet= p.idprojet AND ua.idutilisateur = u.idutilisateur AND c.idprojet_projet = p.idprojet and c.idcentrale_centrale=? AND trashed =FALSE", $idcentrale);
$fprow = fopen('tmp/projetAdmin.json', 'w');
$datauserprojet = "";
$nbarrayuserprojet = count($arrayuserprojet);
fwrite($fprow, '{"items": [');
for ($i = 0; $i < $nbarrayuserprojet; $i++) {
    $datauserprojet = "" . '{"dateaffectation":' . '"' . $arrayuserprojet[$i]['dateaffectation'] . '"' . "," .
            '"nom":' . '"' . $arrayuserprojet[$i]['nom'] . '"' . "," 
            . '"prenom":' . '"' . $arrayuserprojet[$i]['prenom'] . '"' . ","
            . '"idutilisateur":' . '"' . $arrayuserprojet[$i]['idutilisateur'] . '"' . ","
            . '"titre":' . '"' . filtredonnee($arrayuserprojet[$i]['titre']) . '"' . ","
            . '"numero":' . '"' . $arrayuserprojet[$i]['numero'] . '"' . ","
            . '"acronyme":' . '"' . trim($arrayuserprojet[$i]['acronyme']) . '"' . ","
            . '"refinterneprojet":' . '"' . filtredonnee($arrayuserprojet[$i]['refinterneprojet']) . '"' . "},";

    fputs($fprow, $datauserprojet);
    fwrite($fprow, '');
}
fwrite($fprow, ']}');
$json_fileuserProjet = "tmp/projetAdmin.json";
$jsonuseradmin1 = file_get_contents($json_fileuserProjet);
$jsonUseradmin = str_replace('},]}', '}]}', $jsonuseradmin1);
file_put_contents($json_fileuserProjet, $jsonUseradmin);
fclose($fprow);
chmod('tmp/projetAdmin.json', 0777);
?>
<div id="global">
    <?php include 'html/entete.html'; ?>
    <div style="margin-top: 70px;">
        <?php include_once 'outils/bandeaucentrale.php'; ?>
    </div>        
        <fieldset id="ident" style="margin-top: 30px;border-color: #5D8BA2;width:1025px;padding: 10px;height:435px;">
            <legend style="font-size:1.2em;font-weight: 500"><?php echo TXT_TITREVUEPROJETADMIN; ?></legend>
            <div id="gridprojetAdmin" style="height: 420px" ></div>       
        <script>
            var gridprojetAdmin, dataStore, store;
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
                request.get("<?php echo '/' . REPERTOIRE; ?>/tmp/projetAdmin.json", {                
                    handleAs: "json"
                }).then(function(data) {
                    store = new Memory({data: data.items});
                    dataStore = new ObjectStore({objectStore: store});

                    function formatDate(datum) {
                        var d = stamp.fromISOString(datum);
                        return locale.format(d, {selector: 'date', formatLength: 'long'});
                    }
                function hrefFormatter(ident, idx) {
                    var item = gridprojetAdmin.getItem(idx);
                    var idutilisateur = item.idutilisateur;
                    return "<a  href=\"<?php echo '/'.REPERTOIRE; ?>/affecte_AdminProjet/<?php echo $lang; ?>/" + idutilisateur + "\">" + ident + "</a>";
                }
                    gridprojetAdmin = new DataGrid({
                        store: dataStore,
                        query: {id: "*"},
                        structure: [
                            {name: "<?php echo TXT_DATEAFFECTATION; ?>", field: "dateaffectation", width: "120px", formatter: formatDate},
                            {name: "<?php echo TXT_NOM; ?>", field: "nom", width: "110px",formatter:hrefFormatter},
                            {name: "<?php echo TXT_PRENOM; ?>", field: "prenom", width: "110px",formatter:hrefFormatter},
                            {name: "<?php echo TXT_ACRONYME; ?>", field: "acronyme", width: "120px"},
                            {name: "<?php echo TXT_NUMERO; ?>", field: "numero", width: "120px"},
                            {name: "<?php echo TXT_REFINTERNE; ?>", field: "refinterneprojet", width: "120px"},
                            {name: "<?php echo TXT_TITREPROJET; ?>", field: "titre", width: "auto"}
                        ]
                    }, "gridprojetAdmin");
                    gridprojetAdmin.startup();
                });
            });
        </script>
         </fieldset>
    <?php include 'html/footer.html'; ?>
</div>