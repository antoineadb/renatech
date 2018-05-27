<?php
session_start();
include('decide-lang.php');
include_once 'outils/constantes.php';
include_once 'outils/toolBox.php';
include_once 'class/Manager.php';
include_once 'class/Cache.php';
define('ROOT', dirname(__FILE__));
$Cache = new Cache(ROOT . '/cache', 60);
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
    if (!isset($_SESSION['nom'])) {
        $_SESSION['nom'] = $_SESSION['nomConnect'];
    }
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
include 'html/header.html';
$db = BD::connecter();
$manager = new Manager($db);
?>
<script src="<?php echo '/'.REPERTOIRE ?>/js/ajax.js"></script>
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
    <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo 'Résultats de la requête'; ?>" style=" width: 1033px; height:500px" >
        <fieldset id="requete" >
            <table>
                <tr>
                    <td>
                        <div style='width:100px;margin-left:10px'>
                             <a class="infoBulle" href="<?php echo '/' . REPERTOIRE . '/modifBase/exportRequest.php?lang=' . $lang ?>">&nbsp;
                            <img    src='<?php echo "/" . REPERTOIRE; ?>/styles/img/export.png' ><span style="width: 140px;border-radius: 3px"><?php echo 'export au format csv'; ?></span></a>
                         </div>   
                    </td>
                    <td>
                        <input type="button"   label="<?php echo 'Nouvelle requête'; ?>" id="newRequest" data-dojo-Type="dijit.form.Button" onclick="requete()" />
                    </td>
                </tr>
            </table>
            <legend style="color: #5D8BA2;"><?php echo TXT_NBRESULT . ' :' . $_GET['nb']; ?></legend>
            <?php 
                $titresColonne = $manager->getList2("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = ?", 'tmp');
                $structure="structure:[";
                foreach ($titresColonne as $key => $titres) {
                    if($titres[0]=='prenom'){
                        $titre = 'Prénom';
                    }elseif($titres[0]=='codepostal'){
                        $titre = 'Code postal';
                    }elseif($titres[0]=='telephone'){
                        $titre = 'Téléphone';
                    }elseif($titres[0]=='acronymelaboratoire'){
                        $titre = 'Acronyme du laboratoire';
                    }elseif($titres[0]=='entrepriselaboratoire'){
                        $titre = 'Entreprise du laboratoire';
                    }elseif($titres[0]=='mailresponsable'){
                        $titre = 'E-mail du responsable';
                    }elseif($titres[0]=='nomentreprise'){
                        $titre = "Nom de l'entreprise";
                    }elseif($titres[0]=='nomresponsable'){
                        $titre = "Nom du responsable";
                    }else{
                        $titre =$titres[0] ;
                    }
                    $structure .= '{name:'. '"' .ucfirst($titre).'",'.'field:"'.  $titres[0].'",width:"auto"},';
                }
                $structure .="]";               
            ?>
            <script>
                require(["dojox/grid/DataGrid", "dojo/store/Memory", "dojo/data/ObjectStore", "dojo/request", "dojo/domReady!"], function (DataGrid, Memory, ObjectStore, request) {
                    var grid, dataStore;
                    request.get('/<?php echo REPERTOIRE; ?>/tmp/requete.json',
                            {handleAs: "json"}).then(function (data) {
                        dataStore = new ObjectStore({objectStore: new Memory({data: data.items})});
                        grid = new DataGrid({store: dataStore, query: {id: "*"}, queryOptions: {}, 
                            <?php echo $structure; ?>
                        }, "grid");
                        grid.startup();
                    });
                });

            </script>
            <div name="grid" id="grid" style="height:370px;width: auto;margin-top: 10px" ></div>
        </fieldset>  
    </div>
    <?php include 'html/footer.html'; ?>
</div>
<script>
  
    function requete() {
        window.location.replace("<?php echo '/' . REPERTOIRE . '/req/' . $lang; ?>");
    }
    function exportResult(){
        exportRequest('/<?php echo REPERTOIRE; ?>/modifBase/exportRequest.php');
    }
</script>
<?php BD::deconnecter(); ?>
</body>
</html>