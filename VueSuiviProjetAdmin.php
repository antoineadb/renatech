<?php
session_start();
include_once('decide-lang.php');
include_once 'outils/constantes.php';
include_once 'outils/toolBox.php';
include_once 'class/Manager.php';
include_once 'class/Cache.php';
define('ROOT', dirname(__FILE__));
$Cache = new Cache(ROOT . '/cache', 60);
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}

$db = BD::connecter();
$manager = new Manager($db);
$pseudo = $_SESSION['pseudo'];
if (isset($_SESSION['mail'])) {
    $mail = $_SESSION['mail'];
    $_SESSION['email'] = $mail;
} else {
    $mail = $_SESSION['email'];
    $_SESSION['email'] = $mail;
}
$idacademiqueInterne = $manager->getSingle2("SELECT idqualitedemandeuraca_qualitedemandeuraca FROM  loginpassword,utilisateur WHERE idlogin = idlogin_loginpassword and idcentrale_centrale is not null and pseudo = ?", $pseudo);
nomEntete($mail, $pseudo);
// VERIFICATION DE L'EXISTENCE DE L'UTILISATEUR
$idLogin = $manager->getSingle2("SELECT idlogin FROM loginpassword where pseudo=?", $pseudo);
$_SESSION['idutilisateur'] = $manager->getSingle2("SELECT idutilisateur   FROM utilisateur where idlogin_loginpassword=?", $idLogin);
$identite = $manager->getListbyArray("SELECT nom,prenom,adresse,codepostal,ville,telephone,fax FROM utilisateur where idutilisateur=?", array($_SESSION['idutilisateur']));
$_SESSION['nom'] = $identite[0][0];
$_SESSION['prenom'] = $identite[0][1];
$_SESSION['adresse'] = $identite[0][2];
$_SESSION['codepostale'] = $identite[0][3];
$_SESSION['ville'] = $identite[0][4];
$_SESSION['tel'] = $identite[0][5];
$_SESSION['fax'] = $identite[0][6];

//---------------------------------------------------------------------------------------------------------------------------------------------------
//                              CREATION DU PROJET JSON EN COURS
//---------------------------------------------------------------------------------------------------------------------------------------------------
    $manager->exeRequete("drop table if exists tmptousmesprojetadmin;");
//CREATION DE LA TABLE TEMPORAIRE
    $manager->getRequete("create table tmptousmesprojetadmin as (
SELECT p.idprojet,p.titre,p.acronyme,s.libellestatutprojet,s.idstatutprojet,p.numero,p.dateprojet,c.libellecentrale,l.pseudo
FROM utilisateur u,projet p,utilisateuradministrateur ua,concerne co,statutprojet s,centrale c,loginpassword l
WHERE ua.idprojet = p.idprojet AND ua.idutilisateur = u.idutilisateur AND co.idprojet_projet = p.idprojet AND co.idstatutprojet_statutprojet = s.idstatutprojet AND co.idcentrale_centrale = c.idcentrale AND
  l.idlogin = u.idlogin_loginpassword AND l.pseudo=?)", array($pseudo));
    
        $rowEncours = $manager->getList("
   select distinct on (idprojet,libellestatutprojet) idprojet,acronyme,idstatutprojet,libellestatutprojet,numero,titre,dateprojet,libellecentrale from tmptousmesprojetadmin  order by idprojet asc");
        $fpEncours = fopen('tmp/projetEncoursadmin.json', 'w');
        $dataEncours = "";
        $s_centrale='';
        fwrite($fpEncours, '{"items": [');
        $nbarrayuserprojet=count($rowEncours);
        for ($i = 0; $i < $nbarrayuserprojet; $i++) {
            $arraycentraleencours = $manager->getList2("select libellecentrale from tmptousmesprojetadmin where idprojet=?", $rowEncours[$i]['idprojet']);
            $nbcentrale = count($arraycentraleencours);
            for ($j = 0; $j < $nbcentrale; $j++) {
                $s_centrale .=$arraycentraleencours[$j]['libellecentrale'] . ' - ';
            }
            $libellecentrale = substr(trim($s_centrale), 0, -1);
            $dataEncours = ""
                    . '{"numero":' . '"' . $rowEncours[$i]['numero'] . '"' . ","
                    . '"dateprojet":' . '"' . $rowEncours[$i]['dateprojet'] . '"' . ","
                    . '"idstatutprojet":' . '"' . $rowEncours[$i]['idstatutprojet'] . '"' . ","
                    . '"titre":' . '"' . filtredonnee($rowEncours[$i]['titre']) . '"' . ","
                    . '"acronyme":' . '"' . filtredonnee($rowEncours[$i]['acronyme']) . '"'
                    . "," . '"libellestatutprojet":' . '"' . str_replace("''", "'", $rowEncours[$i]['libellestatutprojet']) . '"'
                    . "," . '"idprojet":' . '"' . $rowEncours[$i]['idprojet'] . '"'
                    . "," . '"word":' . '"' . TXT_RAPPORT . '"'
                    . "," . '"imprime":' . '"' . TXT_PDF . '"'
                    . "," . '"academiqueinterne":' . '"' . 'TRUE' . '"'
                    . "," . '"libellecentrale":' . '"' . $libellecentrale . '"' . "},";
            fputs($fpEncours, $dataEncours);
            fwrite($fpEncours, '');
            $s_centrale = "";
        }
   
    fwrite($fpEncours, ']}');
    $json_fileEncours ="tmp/projetEncoursadmin.json";
    $jsonEncours1 = file_get_contents($json_fileEncours);
    $jsonEncours = str_replace('},]}', '}]}', $jsonEncours1);
    file_put_contents($json_fileEncours, $jsonEncours);
    fclose($fpEncours);
    chmod('tmp/projetEncoursadmin.json', 0777);
include 'html/header.html';
?>
<div id="global">
    <?php include 'html/entete.html'; ?>
    <div style="padding-top: 75px;">
        <?php
        if (internetExplorer() == 'false') {
            $Cache->inc(ROOT . '/outils/bandeaucentrale.php'); //RECUPERATION DU BANDEAU DEFILANT DANS LE CACHE CACHE            
        } else {
            include 'outils/bandeaucentrale.php'; //RECUPERATION DU BANDEAU DEFILANT DANS LE CAS D'INTERNET EXPLORER
        }
        ?>
    </div>
    <fieldset id="ident" style="border-color: #5D8BA2;height: 450px;margin-top: 20px;" >
        <legend style="color: #5D8BA2;font-size: 1.2em"><b><?php echo 'Mes projets en gestion';
        ; ?></b></legend>
        <div style="height: 400px;">
            <div style="text-align:center;font-size:12px;"><?php echo TXT_NBPROJET . ' <b>' . $nbarrayuserprojet . '</b>'; ?></div>
            <div style="width:1012px;text-align: center;;margin-bottom:10px;font-size: 1.2em;font-weight: bolder "  ><?php echo ''; ?></div>
           <div id="gridencoursadmin" ></div>
        </div>
        <script>
            var gridencoursadmin, dataStore, store;
            require([
                "dojox/grid/DataGrid",
                "dojo/store/Memory",
                "dojo/data/ObjectStore",
                "dojo/request",
                "dojo/date/stamp",
                "dojo/date/locale",
                "dojo/domReady!"
            ], function(DataGrid, Memory, ObjectStore, request, stamp, locale) {
                request.get("<?php echo '/' . REPERTOIRE; ?>/tmp/projetEncoursadmin.json", {
                    handleAs: "json"
                }).then(function(data) {
                    store = new Memory({data: data.items});
                    dataStore = new ObjectStore({objectStore: store});
                    function hrefFormatter(numero, idx) {
                        var item = gridencoursadmin.getItem(idx);//idx = index
                        var centrale = item.libellecentrale;//libelle centrale
                        var statut = +item.idstatutprojet;//id statut
                        if (statut === +'<?php echo REFUSE; ?>' || statut === +'<?php echo CLOTURE; ?>') {
                            return numero;
                        } else {
                            return "<a  href=\"<?php echo '/' . REPERTOIRE; ?>/controler/controlestatutprojet.php?lang=<?php echo $lang; ?>&statut=" + statut + "&numProjet=" + numero + "&centrale=" + centrale + "\">" + numero + "</a>";
                        }
                    }
                    function hrefFormatterPDF(index, idx) {
                        var item = gridencoursadmin.getItem(idx);
                        var numero = item.numero;
                        var idprojet = item.idprojet;
                        return "<a  href=\"<?php echo '/' . REPERTOIRE; ?>/pdf_project/<?php echo $lang; ?>/" + numero + "\" target='_blank'>" + '<img title="<?php echo TXT_GENERERPDF; ?>" src="<?php echo '/' . REPERTOIRE; ?>/styles/img/pdf_icongrid.png" />' + "</a>";
                    }
                    function formatDate(datum) {
                        var d = stamp.fromISOString(datum);
                        return locale.format(d, {selector: 'date', formatLength: 'long'});
                    }
                    function formatterLibelle(index, idx) {
                        var item = gridencoursadmin.getItem(idx);
                        return item.libellecentrale;
                    }

                    gridencoursadmin = new DataGrid({
                        store: dataStore,
                        query: {id: "*"},
                        structure: [
                            {name: "<?php echo TXT_NUMERO; ?>", field: "numero", width: "auto", formatter: hrefFormatter},
                            {name: "<?php echo TXT_DATEDEMANDE; ?>", field: "dateprojet", width: "auto", formatter: formatDate},
                            {name: "<?php echo TXT_TITREPROJET; ?>", field: "titre", width: "auto"},
                            {name: " ", field: "imprime", width: "34px", formatter: hrefFormatterPDF},
                            {name: "<?php echo TXT_ACRONYME; ?>", field: "acronyme", width: "auto"},
                            {name: "<?php echo TXT_STATUTPROJET; ?>", field: "libellestatutprojet", width: "auto"},
                            {name: "<?php echo TXT_CENTRALS; ?>", field: "", width: "auto", formatter: formatterLibelle}

                        ]
                    }, "gridencoursadmin");
                    gridencoursadmin.startup();
                });
            });
        </script>


    </fieldset>
<?php include 'html/footer.html'; ?>
</div>
</body>
</html>