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
$row = $manager->getListbyArray("select p.idprojet,p.dateprojet,p.numero,p.acronyme,p.titre,co.idstatutprojet_statutprojet,p.refinterneprojet from projet p,concerne co where co.idprojet_projet = p.idprojet and co.idcentrale_centrale=? 
and co.idstatutprojet_statutprojet=? and trashed = FALSE
union select p.idprojet,p.dateprojet,p.numero,p.acronyme,p.titre,co.idstatutprojet_statutprojet,p.refinterneprojet from projet p,concerne co where co.idprojet_projet = p.idprojet and co.idcentrale_centrale=?
and co.idstatutprojet_statutprojet=? and trashed = FALSE
union select p.idprojet,p.dateprojet,p.numero,p.acronyme,p.titre,co.idstatutprojet_statutprojet,p.refinterneprojet from projet p,concerne co where co.idprojet_projet = p.idprojet and co.idcentrale_centrale=? 
and co.idstatutprojet_statutprojet=? and trashed = FALSE
union select p.idprojet,p.dateprojet,p.numero,p.acronyme,p.titre,co.idstatutprojet_statutprojet,p.refinterneprojet from projet p,concerne co where co.idprojet_projet = p.idprojet and co.idcentrale_centrale=? 
and co.idstatutprojet_statutprojet=? and trashed = FALSE order by dateprojet desc",
        array($idcentrale, ENATTENTE,$idcentrale,ENCOURSANALYSE,$idcentrale,ACCEPTE,$idcentrale,ENATTENTEPHASE2));
$fprow = fopen('tmp/projetsEnattentecentrale.json', 'w');
$datausercompte = "";
fwrite($fprow, '{"items": [');
for ($i = 0; $i < count($row); $i++) {
    $libellestatut = $manager->getSingle2("SELECT libellestatutprojet FROM statutprojet where idstatutprojet=?", $row[$i]['idstatutprojet_statutprojet']);
    $statutprojet=  str_replace("''","'",$manager->getSingle2("select libellestatutprojet  from statutprojet where idstatutprojet =?", $row[$i]['idstatutprojet_statutprojet']));
    $datausercompte = "" . '{"dateprojet":' . '"' . $row[$i]['dateprojet'] . '"' . "," .
            '"numero":' . '"' . $row[$i]['numero'] . '"' . "," . '"idprojet":' . '"' . $row[$i]['idprojet'] . '"' . ","
            . '"acronyme":' . '"' . filtredonnee($row[$i]['acronyme']) . '"' . ","
            . '"libellestatut":' . '"' . $libellestatut . '"' . ","
            . '"titre":' . '"' . filtredonnee($row[$i]['titre']) . '"' . ","
            . '"statut":' . '"' . $statutprojet . '"' . ","
            . '"refinterne":' . '"' . filtredonnee($row[$i]['refinterneprojet']) . '"' . ","
            . '"supprime":' . '"' . TXT_SUPPRPROJET . '"' . "},";

    fputs($fprow, $datausercompte);
    fwrite($fprow, '');
}
fwrite($fprow, ']}');
$json_fileuserCompte = "tmp/projetsEnattentecentrale.json";
$jsonusercompte1 = file_get_contents($json_fileuserCompte);
$jsonUsercompte = str_replace('},]}', '}]}', $jsonusercompte1);
file_put_contents($json_fileuserCompte, $jsonUsercompte);
fclose($fprow);
chmod('tmp/projetsEnattentecentrale.json', 0777);
if (count($row) == 0) {
    echo TXT_NORESULT;
} else {
    echo '<div style="text-align:center;width: auto;height:25px" >' . TXT_NOMBREPROJET . ' : ' . count($row) . '</div>';
}
?> 
<div style="height: 500px;" >
    <div id="gridprojetenattente" ></div>
    <script>
        var gridprojetenattente, dataStore, store;
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
            request.get("<?php echo '/'.REPERTOIRE; ?>/tmp/projetsEnattentecentrale.json", {
                handleAs: "json"
            }).then(function(data) {
                store = new Memory({data: data.items});
                dataStore = new ObjectStore({objectStore: store});

                function formatDate(datum) {
                    var d = stamp.fromISOString(datum);
                    return locale.format(d, {selector: 'date', formatLength: 'long'});
                }
                function deleteProjet(enleve, idx) {
                    var item = gridprojetenattente.getItem(idx);
                    var idprojet = item.idprojet;
                    return '<button class="btnSuppr"  onClick="rep.show();"  title="<?php echo TXT_SUPPRESSIONPROJET ;?>" ><?php echo TXT_EFFACE;?></button>';
                }
                gridprojetenattente = new DataGrid({
                    store: dataStore,
                    query: {id: "*"},
                    structure: [
                        {name: "<?php echo TXT_DATEDEMANDE; ?>", field: "dateprojet", width: "137px", formatter: formatDate},
                        {name: "<?php echo TXT_NUMERO; ?>", field: "numero", width: "75px"},
                        {name: " ", field: "supprime", width: "80px", formatter: deleteProjet},
                        {name: "<?php echo TXT_ACRONYME; ?>", field: "acronyme", width: "90px"},
                        {name: "<?php echo TXT_REFINTERNE; ?>", field: "refinterne", width: "80px"},
                        {name: "<?php echo TXT_TITREPROJET; ?>", field: "titre", width: "auto"},
                        {name: "<?php echo TXT_STATUTPROJETS; ?>", field: "statut", width: "120px"}
                        
                    ]
                }, "gridprojetenattente");
                gridprojetenattente.startup();
                gridprojetenattente.on("RowClick", function(evt) {
                    var idx = evt.rowIndex;
                    var item = gridprojetenattente.getItem(idx);
                    var idprojet = item.idprojet;
                    var numero = item.numero;
                    document.getElementById("results").value=idprojet;
                    dijit.byId("rep").set('title', "<?php echo TXT_CONFIRDELPROJET; ?>"+' '+numero);
            }, true);
        });
        });
        </script>
</div>
<div data-dojo-type="dijit/Dialog" data-dojo-id="rep" id ='rep' title="" style="width: 500px;margin-left: 20px;"  >
    <table class="dijitDialogPaneContentArea">
        <tr>
            <td><button type="submit" data-dojo-type="dijit/form/Button"  id="submitOui" data-dojo-props="onClick:function(){window.location.replace('<?php echo '/'.REPERTOIRE?>/modifBase/trashedProject.php?lang=<?php echo $lang;?>&idprojet='+document.getElementById('results').value+'')}">
                    <?php echo TXT_OUI; ?></button></td>
            <td><button type="submit" data-dojo-type="dijit/form/Button"  id="submitNon" data-dojo-props="onClick:function(){rep.hide();}">
                    <?php echo TXT_NON; ?></button></td>
            <td><button data-dojo-type="dijit/form/Button" type="submit" data-dojo-props="onClick:function(){rep.hide();}" id="cancel"><?php echo TXT_ANNULE; ?></button></td>
        </tr>
    </table>
</div>

<?php
BD::deconnecter();
