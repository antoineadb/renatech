<?php
include_once 'class/Manager.php';
include_once 'decide-lang.php';
include_once 'outils/constantes.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include_once 'outils/toolBox.php';

if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
    $pseudo = $_SESSION['pseudo'];
    $idcentrale_centrale = $manager->getSingle2("SELECT idcentrale_centrale FROM loginpassword,utilisateur WHERE idlogin = idlogin_loginpassword and pseudo=?", $pseudo);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
if (isset($_POST['nom'])) {
    if (!empty($_POST['nom']) && $_POST['nom'] != '*') {
        $nom = pg_escape_string($_POST['nom']);
        $req = "    select nom,prenom,idutilisateur,datecreation from utilisateur where lower(nom) like ?  and idcentrale_centrale=?
                    union
                    select nom,prenom,idutilisateur,datecreation from utilisateur where lower(nom) like ?  and idqualitedemandeurindust_qualitedemandeurindust is not null
                    union
                    select nom,prenom,idutilisateur,datecreation from utilisateur where lower(nom) like ? and idcentrale_centrale is null";

        $param = array('%' . strtolower($nom) . '%', $idcentrale_centrale, '%' . strtolower($nom) . '%', '%' . strtolower($nom) . '%');
    } else {
        $req = "
                select nom,prenom,idutilisateur,datecreation from utilisateur where  idcentrale_centrale=?
                union
                select nom,prenom,idutilisateur,datecreation from utilisateur where  idqualitedemandeurindust_qualitedemandeurindust is not null
                union
                select nom,prenom,idutilisateur,datecreation from utilisateur where idcentrale_centrale is null ";
        $param = array($idcentrale_centrale);
    }
}
if (isset($_POST['prenom'])) {
    if (!empty($_POST['prenom'])) {
        $prenom = $_POST['prenom'];
        $req = "    select nom,prenom,idutilisateur,datecreation from utilisateur where lower(prenom) like  ? and idcentrale_centrale=?
                    union
                    select nom,prenom,idutilisateur,datecreation from utilisateur where lower(prenom) like ?  and idqualitedemandeurindust_qualitedemandeurindust is not null
                    union
                    select nom,prenom,idutilisateur,datecreation from utilisateur where lower(prenom) like ? and idcentrale_centrale is null 
                    ";
        $param = array('%' . strtolower($prenom) . '%', $idcentrale_centrale, '%' . strtolower($prenom) . '%', '%' . strtolower($prenom) . '%');
    }
}

if (isset($_POST['nom']) && isset($_POST['prenom'])) {
    if (!empty($_POST['nom']) && !empty($_POST['prenom'])) {
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $req = "    select nom,prenom,idutilisateur,datecreation from utilisateur where lower(nom) like ? and lower(prenom) like ? and idcentrale_centrale=?
                    union
                    select nom,prenom,idutilisateur,datecreation from utilisateur where lower(nom) like ? and lower(prenom) like ?  and idqualitedemandeurindust_qualitedemandeurindust is not null
                    union
                    select nom,prenom,idutilisateur,datecreation from utilisateur where lower(nom) like ? and lower(prenom) like ? and idqualitedemandeuraca_qualitedemandeuraca is null 
                ";
        $param = array(
            '%' . strtolower($nom) . '%', '%' . strtolower($prenom) . '%', $idcentrale_centrale, '%' . strtolower($nom) . '%', '%' . strtolower($prenom) . '%','%' . strtolower($nom) . '%', '%' . strtolower($prenom) . '%');
    }
}

$row = $manager->getListbyArray($req, $param);

$fprow = fopen('tmp/userCompte.json', 'w');
$datausercompte = "";
fwrite($fprow, '{"items": [');
for ($i = 0; $i < count($row); $i++) {
    $datausercompte = "" . '{"datecreation":' . '"' . $row[$i]['datecreation'] . '"' . "," .
            '"idutilisateur":' . '"' . $row[$i]['idutilisateur'] . '"' . "," .
            '"nom":' . '"' . trim(stripslashes(str_replace("''", "'", trim($row[$i]['nom'])))) . '"' . "," . '"prenom":' . '"' . $row[$i]['prenom'] . '"' . "},";
    fputs($fprow, $datausercompte);
    fwrite($fprow, '');
}
fwrite($fprow, ']}');
$json_fileuserCompte = "tmp/userCompte.json";
$jsonusercompte1 = file_get_contents($json_fileuserCompte);
$jsonUsercompte = str_replace('},]}', '}]}', $jsonusercompte1);
file_put_contents($json_fileuserCompte, $jsonUsercompte);
fclose($fprow);
chmod('tmp/userCompte.json', 0777);

$i = count($row);
if ($i == 0) {
    echo TXT_NORESULT;
} else {
    echo '<div style="text-align:center;width: auto;height:25px" >' . TXT_NBRESULT . ' :' . $i . '</div>';
}
?>
<div style="height: 350px;">
    <div id="grideuserporteur" ></div>
    <script>
        var grideuserporteur, dataStore, store;
        require([
            "dojox/grid/DataGrid",
            "dojo/store/Memory",
            "dojo/data/ObjectStore",
            "dojo/request",
            "dojo/domReady!"
        ], function(DataGrid, Memory, ObjectStore, request) {
            request.get("<?php echo '/'.REPERTOIRE; ?>/tmp/userCompte.json", {
                handleAs: "json"
            }).then(function(data) {
                store = new Memory({data: data.items});
                dataStore = new ObjectStore({objectStore: store});

                function hrefFormatter(ident, idx) {
                    var item = grideuserporteur.getItem(idx);
                    var idutilisateur = item.idutilisateur;
                    return "<a  href=\"<?php echo '/'.REPERTOIRE; ?>/modifBase/updateAffectProjet.php?lang=<?php echo $lang; ?>&idutilisateur=" + idutilisateur + "\">" + ident + "</a>";
                }
                grideuserporteur = new DataGrid({
                    store: dataStore,
                    query: {id: "*"},
                    structure: [
                        {name: "<?php echo TXT_CREATEDATE;?>", field: "datecreation", width: "auto", formatter: hrefFormatter},
                        {name: "<?php echo TXT_NOM;?>", field: "nom", width: "auto", formatter: hrefFormatter},
                        {name: "<?php echo TXT_PRENOM;?>", field: "prenom", width: "auto", formatter: hrefFormatter},
                    ]
                }, "grideuserporteur");
                grideuserporteur.startup();
            });
        });
    </script>
</div>
<?php BD::deconnecter(); ?>
