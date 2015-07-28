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

$rowEfface = $manager->getList2("SELECT p.refinterneprojet,p.idprojet,p.titre,p.acronyme,p.numero,s.libellestatutprojet,s.libellestatutprojeten FROM projet p,statutprojet s,concerne c,loginpassword l,utilisateur u
WHERE s.idstatutprojet = c.idstatutprojet_statutprojet AND c.idprojet_projet = p.idprojet AND c.idcentrale_centrale = u.idcentrale_centrale AND l.idlogin = u.idlogin_loginpassword AND l.pseudo = ? AND p.trashed=TRUE
", $_SESSION['pseudo']);


$fpEfface = fopen('tmp/projetefface.json', 'w');
$dataEfface = "";
fwrite($fpEfface, '{"items": [');

for ($i = 0; $i < count($rowEfface); $i++) {
    if ($lang == 'fr') {
        $dataEfface = "" . '{'
                . '"numero":' . '"' . $rowEfface[$i]['numero'] . '"'  . ","
                . '"refinterneprojet":' . '"' . trim(stripslashes(removeDoubleQuote(trim($rowEfface[$i]['refinterneprojet'])))) . '"' . ","
                . '"titre":' . '"' . trim(stripslashes(removeDoubleQuote(trim($rowEfface[$i]['titre'])))) . '"' . ","
                . '"libellestatutprojet":' . '"' . removeDoubleQuote($rowEfface[$i]['libellestatutprojet']) . '"' . ","
                . '"idprojet":' . '"' . $rowEfface[$i]['idprojet'] . '"' . ","
                . '"acronyme":' . '"' . trim(stripslashes(removeDoubleQuote($rowEfface[$i]['acronyme']))) . '"'
                . "},";
    } else {
        $dataEfface = "" . '{'
                . '"numero":' . '"' . $rowEfface[$i]['numero'] . '"'  . ","
                . '"refinterneprojet":' . '"' . trim(stripslashes(removeDoubleQuote(trim($rowEfface[$i]['refinterneprojet'])))) . '"' . ","
                . '"titre":' . '"' . trim(stripslashes(removeDoubleQuote(trim($rowEfface[$i]['titre'])))) . '"' . ","
                . '"libellestatutprojet":' . '"' . $rowEfface[$i]['libellestatutprojeten'] . '"' . ","
                . '"idprojet":' . '"' . $rowEfface[$i]['idprojet'] . '"' . ","
                . '"acronyme":' . '"' . trim(stripslashes(removeDoubleQuote($rowEfface[$i]['acronyme']))) . '"'
                . "},";
    }

    fputs($fpEfface, $dataEfface);
    fwrite($fpEfface, '');
}

fwrite($fpEfface, ']}');
$json_fileEfface = "tmp/projetefface.json";
$jsonEfface1 = file_get_contents($json_fileEfface);
$jsonEfface = str_replace('},]}', '}]}', $jsonEfface1);
file_put_contents($json_fileEfface, $jsonEfface);
fclose($fpEfface);
chmod('tmp/projetefface.json', 0777);
?>

<style type="text/css">
    #grid {
        width: 78em;
        margin-top: 10px;
    }
    #menuCorbeille div {
     display: inline;
    // margin: 0 1em 0 20em ;
        margin-left: 20px;
     width: 30%;
}
</style>
<div id="global">
    <?php include 'html/entete.html'; ?>
    <div style="margin-top: 70px;">
        <?php include_once 'outils/bandeaucentrale.php'; ?>
    </div>
    <input type="text" id="idprojet" value="" style="display: none">
    <input type="text" id="numero" value="" style="display: none">
    <?php $height = count($rowEfface) * 38; ?>
        <fieldset id="ident" style="margin-top: 30px;border-color: #5D8BA2;width:1025px;padding: 10px;height:<?php echo $height.'px' ?>">
            <legend style="font-size:1.2em;font-weight: 500;margin-left: 20px"><?php echo TXT_DELETEDPROJECT; ?></legend>  
            <div id="menuCorbeille">
                <div style="margin-left: 420px"><?php echo TXT_NOMBREPROJET . ' : ' . count($rowEfface); ?></div>
                <a href="javascript:menuConfirmationEmptyTrash('repEmptyTrash')" style="text-decoration: none"><div style="margin-left: 310px"><?php echo TXT_EMPTYTRASH; ?></div></a>                
            </div>
<script>
    dojo.require("dojox.grid.EnhancedGrid");
    dojo.require("dojox.data.CsvStore");
    dojo.require("dojox.grid.enhanced.plugins.Menu");
    dojo.require("dojo.data.ItemFileWriteStore");
    dojo.require("dijit.Menu");
    dojo.require("dijit.MenuItem");
    dojo.require("dojo.domReady!");
    dojo.require("dojo.request");
    dojo.require("dojox.grid.enhanced.plugins.Pagination");
dojo.ready(function(){
    /*set up data store*/
    var store = new dojo.data.ItemFileWriteStore({url: "<?php echo '/'.REPERTOIRE;?>/tmp/projetefface.json"});
    /*set up layout*/    
    var layout = [[
        { name: "<?php echo TXT_NUMERO ?>", field: "numero", width: "auto"},
        { name: "<?php echo TXT_REFINTERNE ?>", field: "refinterneprojet", width: "190px" },
        { name: "<?php echo TXT_TITREPROJET ?>", field: "titre", width: "190px" },
        { name: "<?php echo TXT_STATUTPROJET ?>", field: "libellestatutprojet", width: "auto"},
        { name: "<?php echo TXT_ACRONYME ?>", field: "acronyme", width: "auto" }
    ]];
    var grid = new dojox.grid.EnhancedGrid({
        id: 'grid',
        store: store,
        structure: layout,
        autoHeight:true,
        rowSelector: '20px',
        plugins: {menus:{headerMenu:"headerMenu", rowMenu:"rowMenu", cellMenu:"cellMenu", selectedRegionMenu:"selectedRegionMenu"},}
    },
      document.createElement('div'));
//----------------------------------------------------------------------------------------------------------------------
//                                              FONCTIONS DE FORMATAGE
//----------------------------------------------------------------------------------------------------------------------
       
       dojo.connect(grid, 'onRowContextMenu', function(e){
            var rowIndex = e.rowIndex;
            var item = grid.getItem(rowIndex);
            var idprojet = item.idprojet;
            var numero = item.numero;
            document.getElementById('idprojet').value=idprojet;
            document.getElementById('numero').value=numero;
        })
    dojo.byId("gridDiv").appendChild(grid.domNode);
    grid.startup();
});
</script>
<script>
function menuConfirmation(e){
    dijit.byId(e).set('title', '<?php echo TXT_CONFIRDELETEPROJET ;?>'+' '+document.getElementById('numero').value+'');
    rep.show();
}
function menuConfirmationEmptyTrash(e){
    dijit.byId(e).set('title', '<?php echo TXT_CONFIRMEMPTYTRASH ;?>');
    repEmptyTrash.show();
}
</script>
<div id="gridDiv">
    <div data-dojo-type="dijit.Menu" id="cellMenu"  style="display: none;width: 120px;" >
        <div data-dojo-type="dijit.MenuItem" onclick="menuConfirmation('rep')" style="text-align: center"><?php echo TXT_SUPP;?></div>
        <div data-dojo-type="dijit/MenuSeparator"></div>
        <div data-dojo-type="dijit.MenuItem" data-dojo-props="onClick:function(){window.location.replace('<?php echo '/'.REPERTOIRE?>/modifBase/restoreProject.php?lang=<?php echo $lang;?>&idprojet='+document.getElementById('idprojet').value+'');}"
             style="text-align: center"><?php echo TXT_RESTAURER;?></div>             
    </div>
</div>

<div data-dojo-type="dijit/Dialog" data-dojo-id="rep" id ='rep'  title="<?php echo TXT_CONFIRMATIONDELPROJET; ?>" data-dojo-props="iconClass:'dijitEditorIcon dijitEditorIconCut', showLabel: true"
     style="width: 400px;margin-left: 20px;   ">
    <table class="dijitDialogPaneContentArea" >
        <tr>
            <td><button type="submit" data-dojo-type="dijit/form/Button"   id="submitYes" 
                        onclick="window.location.replace('<?php echo '/'.REPERTOIRE?>/modifBase/deleteprojet.php?lang=<?php echo $lang;?>&idprojet='+document.getElementById('idprojet').value+'')">
                    <?php echo TXT_OUI; ?></button></td>
            <td><button type="submit" data-dojo-type="dijit/form/Button"  id="submitNo" data-dojo-props="onClick:function(){rep.hide();}">
                    <?php echo TXT_NON; ?></button></td>
            <td><button data-dojo-type="dijit/form/Button" type="submit" data-dojo-props="onClick:function(){rep.hide();}" id="efface"><?php echo TXT_ANNULE; ?></button></td>
        </tr>
    </table>
</div>
<div data-dojo-type="dijit/Dialog" data-dojo-id="repEmptyTrash" id ='repEmptyTrash'  title="<?php echo TXT_CONFIRMATIONDELPROJET; ?>" data-dojo-props="iconClass:'dijitEditorIcon dijitEditorIconCut', showLabel: true"
     style="width: 520px;margin-left: 20px;   ">
    <table class="dijitDialogPaneContentArea" >
        <tr>
            <td><button type="submit" data-dojo-type="dijit/form/Button"   id="submitOui" 
                        onclick="window.location.replace('<?php echo '/'.REPERTOIRE?>/empty_trash/<?php echo $lang;?>')">
                    <?php echo TXT_OUI; ?></button></td>
            <td><button type="submit" data-dojo-type="dijit/form/Button"  id="submitNon" data-dojo-props="onClick:function(){rep.hide();}">
                    <?php echo TXT_NON; ?></button></td>
            <td><button data-dojo-type="dijit/form/Button" type="submit" data-dojo-props="onClick:function(){rep.hide();}" id="delete"><?php echo TXT_ANNULE; ?></button></td>
        </tr>
    </table>
</div>
<script>
    var rowIndex;
    function rowContextMenuEvent (e) {
        rowIndex = e.rowIndex;
    }    
</script>
<script>
    dojo.require("dijit.Dialog");
    function hideDialog() {
        dijit.byId("myDialog").hide();
    }
</script>
</fieldset>
    <?php include 'html/footer.html'; ?>
    </div>
</body>
</html>