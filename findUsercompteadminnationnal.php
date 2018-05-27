<?php
include_once 'decide-lang.php';
include_once 'class/Manager.php';
include_once 'outils/constantes.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER

if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
    $idcentrale_centrale = $manager->getSingle2("SELECT idcentrale_centrale FROM loginpassword,utilisateur WHERE idlogin = idlogin_loginpassword and pseudo=?", $_SESSION['pseudo']);
} else {
   header('Location: /' . REPERTOIRE . '/erreurlogin.php?lang=' . $lang);
}
if (isset($_POST['nom'])) {
    if (!empty($_POST['nom']) && $_POST['nom'] != '*') {
        $nom = $_POST['nom'];
        $req = "select * from (
        SELECT distinct u.idutilisateur, u.nom, u.prenom,null as libellecentrale, t.libelletype,u.idqualitedemandeuraca_qualitedemandeuraca,u.idqualitedemandeurindust_qualitedemandeurindust 
        FROM   utilisateur u,typeutilisateur t 
        WHERE u.idtypeutilisateur_typeutilisateur = t.idtypeutilisateur and idqualitedemandeuraca_qualitedemandeuraca is null and lower(u.nom) like lower(?)
        union
        SELECT distinct u.idutilisateur, u.nom, u.prenom,c.libellecentrale,  t.libelletype,u.idqualitedemandeuraca_qualitedemandeuraca,u.idqualitedemandeurindust_qualitedemandeurindust 
        FROM   utilisateur u,typeutilisateur t,centrale c
        WHERE u.idtypeutilisateur_typeutilisateur = t.idtypeutilisateur and u.idcentrale_centrale = c.idcentrale and lower(u.nom) like lower(?)
        union
        SELECT distinct u.idutilisateur, u.nom, u.prenom,null as libellecentrale, t.libelletype,u.idqualitedemandeuraca_qualitedemandeuraca,u.idqualitedemandeurindust_qualitedemandeurindust 
        FROM   utilisateur u,typeutilisateur t
        WHERE u.idtypeutilisateur_typeutilisateur = t.idtypeutilisateur and idqualitedemandeuraca_qualitedemandeuraca is not null and idcentrale_centrale is null and lower(u.nom) like lower(?)
        )as foo
        order by idutilisateur asc ";
        $param = array($nom, $nom, $nom);
    } else {
        $req = "select * from (
        SELECT distinct u.idutilisateur, u.nom, u.prenom,null as libellecentrale, t.libelletype,u.idqualitedemandeuraca_qualitedemandeuraca,u.idqualitedemandeurindust_qualitedemandeurindust FROM   utilisateur u,typeutilisateur t WHERE u.idtypeutilisateur_typeutilisateur = t.idtypeutilisateur and idqualitedemandeuraca_qualitedemandeuraca is null
        union
        SELECT distinct u.idutilisateur, u.nom, u.prenom,c.libellecentrale,  t.libelletype,u.idqualitedemandeuraca_qualitedemandeuraca,u.idqualitedemandeurindust_qualitedemandeurindust FROM   utilisateur u,typeutilisateur t,centrale c
        WHERE u.idtypeutilisateur_typeutilisateur = t.idtypeutilisateur and u.idcentrale_centrale = c.idcentrale
        union
        SELECT distinct u.idutilisateur, u.nom, u.prenom,null as libellecentrale, t.libelletype,u.idqualitedemandeuraca_qualitedemandeuraca,u.idqualitedemandeurindust_qualitedemandeurindust FROM   utilisateur u,typeutilisateur t
        WHERE u.idtypeutilisateur_typeutilisateur = t.idtypeutilisateur and idqualitedemandeuraca_qualitedemandeuraca is not null and idcentrale_centrale is null
        )as foo
        order by idutilisateur asc ";
        $param = array();
    }
}
if (isset($_POST['prenom'])) {
    if (!empty($_POST['prenom'])) {
        $prenom = $_POST['prenom'];

        $req = "select * from (
        SELECT distinct u.idutilisateur, u.nom, u.prenom,null as libellecentrale, t.libelletype,u.idqualitedemandeuraca_qualitedemandeuraca,u.idqualitedemandeurindust_qualitedemandeurindust 
        FROM   utilisateur u,typeutilisateur t 
        WHERE u.idtypeutilisateur_typeutilisateur = t.idtypeutilisateur and idqualitedemandeuraca_qualitedemandeuraca is null and lower(u.prenom) like lower(?)
        union
        SELECT distinct u.idutilisateur, u.nom, u.prenom,c.libellecentrale,  t.libelletype,u.idqualitedemandeuraca_qualitedemandeuraca,u.idqualitedemandeurindust_qualitedemandeurindust 
        FROM   utilisateur u,typeutilisateur t,centrale c
        WHERE u.idtypeutilisateur_typeutilisateur = t.idtypeutilisateur and u.idcentrale_centrale = c.idcentrale and lower(u.prenom) like lower(?)
        union
        SELECT distinct u.idutilisateur, u.nom, u.prenom,null as libellecentrale, t.libelletype,u.idqualitedemandeuraca_qualitedemandeuraca,u.idqualitedemandeurindust_qualitedemandeurindust 
        FROM   utilisateur u,typeutilisateur t
        WHERE u.idtypeutilisateur_typeutilisateur = t.idtypeutilisateur and idqualitedemandeuraca_qualitedemandeuraca is not null and idcentrale_centrale is null and lower(u.prenom) like lower(?)
        )as foo
        order by idutilisateur asc ";
        $param = array($prenom, $prenom, $prenom);
    }
}

if (isset($_POST['nom'])&&!empty($_POST['nom']) && isset($_POST['prenom'])&&!empty($_POST['prenom'])) {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $req = "select * from (
        SELECT distinct u.idutilisateur, u.nom, u.prenom,null as libellecentrale, t.libelletype,u.idqualitedemandeuraca_qualitedemandeuraca,u.idqualitedemandeurindust_qualitedemandeurindust 
        FROM   utilisateur u,typeutilisateur t 
        WHERE u.idtypeutilisateur_typeutilisateur = t.idtypeutilisateur and idqualitedemandeuraca_qualitedemandeuraca is null and lower(u.nom) like lower(?) and lower(u.prenom) like lower(?)
        union
        SELECT distinct u.idutilisateur, u.nom, u.prenom,c.libellecentrale,  t.libelletype,u.idqualitedemandeuraca_qualitedemandeuraca,u.idqualitedemandeurindust_qualitedemandeurindust 
        FROM   utilisateur u,typeutilisateur t,centrale c
        WHERE u.idtypeutilisateur_typeutilisateur = t.idtypeutilisateur and u.idcentrale_centrale = c.idcentrale and lower(u.nom) like lower(?) and lower(u.prenom) like lower(?)
        union
        SELECT distinct u.idutilisateur, u.nom, u.prenom,null as libellecentrale, t.libelletype,u.idqualitedemandeuraca_qualitedemandeuraca,u.idqualitedemandeurindust_qualitedemandeurindust 
        FROM   utilisateur u,typeutilisateur t
        WHERE u.idtypeutilisateur_typeutilisateur = t.idtypeutilisateur and idqualitedemandeuraca_qualitedemandeuraca is not null and idcentrale_centrale is null and lower(u.nom) like lower(?) and lower(u.prenom) like lower(?)
        )as foo
        order by idutilisateur asc ";
    $param = array($nom,$prenom,$nom, $prenom,$nom, $prenom);
}
if (isset($_GET['idutilisateur'])) {
    //$req = "select nom,prenom,idtypeutilisateur_typeutilisateur,idutilisateur,datecreation from utilisateur where idutilisateur =? and idtypeutilisateur_typeutilisateur <>1 and idtypeutilisateur_typeutilisateur <>4 and idcentrale_centrale=?";
    $param = array($_GET['idutilisateur'], $idcentrale_centrale);
}
$row = $manager->getListbyArray($req, $param);

$fprow = fopen('tmp/userCompteadminnationnal.json', 'w');
$datauserCompteadminnationnal = "";
fwrite($fprow, '{"items": [');
for ($i = 0; $i < count($row); $i++) {
    $datauserCompteadminnationnal = "" . '{"libelletype":' . '"' . $row[$i]['libelletype'] . '"' . "," . '"libellecentrale":' . '"' . $row[$i]['libellecentrale'] . '"' . "," .
            '"idqualitedemandeurindust_qualitedemandeurindust":' . '"' . $row[$i]['idqualitedemandeurindust_qualitedemandeurindust'] . '"' . "," .
            '"idqualitedemandeuraca_qualitedemandeuraca":' . '"' . $row[$i]['idqualitedemandeuraca_qualitedemandeuraca'] . '"' . "," .
            '"idutilisateur":' . '"' . $row[$i]['idutilisateur'] . '"' . "," .
            '"nom":' . '"' . trim(stripslashes(str_replace("''", "'", $row[$i]['nom']))) . '"' . "," . '"prenom":' . '"' . $row[$i]['prenom'] . '"' . "},";
    fputs($fprow, $datauserCompteadminnationnal);
    fwrite($fprow, '');
}
fwrite($fprow, ']}');
$json_fileuserCompteadminnationnal = "tmp/userCompteadminnationnal.json";
$jsonuserCompteadminnationnal1 = file_get_contents($json_fileuserCompteadminnationnal);
$jsonuserCompteadminnationnal = str_replace('},]}', '}]}', $jsonuserCompteadminnationnal1);
file_put_contents($json_fileuserCompteadminnationnal, $jsonuserCompteadminnationnal);
fclose($fprow);
chmod('tmp/userCompteadminnationnal.json', 0777);

$i = count($row);
if ($i == 0) {
    echo TXT_NORESULT;
} else {
    echo '<div style="text-align:center;width: auto;height:25px" >' . TXT_NBRESULT . ' :' . $i . '</div>';
}
?>
<div style="height: 350px;">
    <div id="grideuserCompteadminnationnal" ></div>
    <script>
        var grideuserCompteadminnationnal, dataStore, store;
        require([
            "dojox/grid/DataGrid",
            "dojo/store/Memory",
            "dojo/data/ObjectStore",
            "dojo/request",
            "dojo/domReady!"
        ], function(DataGrid, Memory, ObjectStore, request){
            request.get("tmp/userCompteadminnationnal.json", {
                handleAs: "json"
            }).then(function(data){
                store = new Memory({ data: data.items });
                dataStore = new ObjectStore({ objectStore: store });
               
                function hrefFormatterNom(nom,idx){
                    var item = grideuserCompteadminnationnal.getItem(idx);
                    var idqualiteaca =item.idqualitedemandeuraca_qualitedemandeuraca;
                    var valeuidqualiteindust =item.idqualitedemandeurindust_qualitedemandeurindust;
                    var iduser = item.idutilisateur;
                    return "<a  href=\"gestioncompteadminnationnal.php?lang=<?php echo $lang; ?>&iduser="+iduser+"&idqualiteaca=" + idqualiteaca+"&idqualiteindust= "+valeuidqualiteindust+" \">"+nom+"</a>";    
                }
                function hrefFormatterPrenom(prenom,idx){
                    var item = grideuserCompteadminnationnal.getItem(idx);
                    var idqualiteaca =item.idqualitedemandeuraca_qualitedemandeuraca;
                    var valeuidqualiteindust =item.idqualitedemandeurindust_qualitedemandeurindust;
                    var iduser = item.idutilisateur;
                    return "<a  href=\"gestioncompteadminnationnal.php?lang=<?php echo $lang; ?>&iduser="+iduser+"&idqualiteaca=" + idqualiteaca+"&idqualiteindust= "+valeuidqualiteindust+" \">"+prenom+"</a>";    
                }
                
                function hrefFormatterCentrale(centrale,idx){
                    var item = grideuserCompteadminnationnal.getItem(idx);
                    var idqualiteaca =item.idqualitedemandeuraca_qualitedemandeuraca;
                    var valeuidqualiteindust =item.idqualitedemandeurindust_qualitedemandeurindust;
                    var iduser = item.idutilisateur;
                    return "<a  href=\"gestioncompteadminnationnal.php?lang=<?php echo $lang; ?>&iduser="+iduser+"&idqualiteaca=" + idqualiteaca+"&idqualiteindust= "+valeuidqualiteindust+" \">"+centrale+"</a>";    
                }
                 
                grideuserCompteadminnationnal = new DataGrid({
                    store: dataStore,
                    query: { id: "*" },
                    structure: [
                        
                        { name: "<?php echo TXT_NOM; ?>", field: "nom", width: "auto" ,formatter: hrefFormatterNom},
                        { name: "<?php echo TXT_PRENOM; ?>", field: "prenom", width: "auto",formatter: hrefFormatterPrenom},
                        { name: "<?php  echo TXT_CENTRALE; ?>", field: "libellecentrale", width: "auto" ,formatter: hrefFormatterCentrale},
                        { name: "<?php echo  TXT_DROITACTUEL; ?>", field: "libelletype", width: "auto" },
                    ]
                }, "grideuserCompteadminnationnal");
                grideuserCompteadminnationnal.startup();
            });
        });
    </script>
</div>
<?php
BD::deconnecter();
?>