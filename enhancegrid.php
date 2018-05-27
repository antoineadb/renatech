    <!DOCTYPE html>
<html >
<head>

    <link rel="stylesheet" href="../js/dijit/themes/claro/claro.css">
	<style type="text/css">
            @import "../js/dijit/themes/claro/claro.css";
            @import "../js/dojox/grid/enhanced/resources/claro/EnhancedGrid.css";
            @import "../js/dojox/grid/enhanced/resources/EnhancedGrid_rtl.css";
            /*Grid need a explicit width/height by default*/
            #grid {
                width: 100em;
                height: 65em;
            }
	</style>
        
        <?php include_once '../outils/constantes.php'; ?>
	<script>dojoConfig = {parseOnLoad: true}</script>
	<script src='../js/dojo/dojo.js'></script>
        <script src='../js/controle.js'></script>
	<script>
            dojo.require("dojox.grid.EnhancedGrid");
            dojo.require("dojox.grid.enhanced.plugins.Menu");
            dojo.require("dojo.data.ItemFileWriteStore");
            dojo.require("dijit.Menu");
            dojo.require("dijit.MenuItem");
            dojo.require("dojo.date.stamp");
            dojo.require("dojo.date.locale");
            dojo.require("dojo.domReady!");
            dojo.require("dojo.request");
            
            function hrefFormatterNumero(numero,idx) {
                return numero+' - 1111';                
            /*   var item = grid.getItem(idx);
                var centrale = item.libellecentrale;
                var statut = +item.idstatutprojet;
                return "<a  href=\"<?php echo '/'.REPERTOIRE;?>/controler/controlestatutprojet.php?lang=<?php echo $lang; ?>&statut=" + statut + "&numProjet=" + numero +"&centrale=" + centrale + "\">"+ numero + "</a>";
            */
            }
           /* function hrefFormatterPDF(index, idx) {
                var item = grid.getItem(idx);
                var numero = item.numero;
                return "<a  href=\"<?php echo '/'.REPERTOIRE;?>/pdf_project/<?php echo $lang; ?>/" + numero + "\" target='_blank'>" +
                        '<img title="<?php echo TXT_GENERERPDF; ?>" src="<?php echo "/".REPERTOIRE; ?>/styles/img/pdf_icongrid.png" />' + "</a>";
            }*/
                
            /*function formatDate(datum) {
                var d = stamp.fromISOString(datum);
                var annee =  datum.substring(0,4);
                if(annee>1970){
                    return locale.format(d, {selector: 'date', formatLength: 'short'});
                }
            }*/
            
            
dojo.ready(function(){
    /*set up data store*/
    var store = new dojo.data.ItemFileWriteStore({url: "<?php echo '/'.REPERTOIRE;?>/tmp/projetCentrale.json"});
    /*set up layout*/
    var layout = [[
        {name: "<?php echo TXT_NUMERO; ?>", field: "numero", width: "100px", formatter: hrefFormatterNumero},
        {name: "<?php echo TXT_DATEDEMANDE; ?>", field: "dateprojet"},
        {name: "<?php echo TXT_TITREPROJET; ?>", field: "titre", width: "220px"},
        {name: "<?php echo TXT_UPDATE; ?>", field: "datemaj"},
        {name: "<?php echo TXT_REFINTERNE; ?>", field: "refinterneprojet"},
        {name: "<?php echo TXT_STATUTPROJETS; ?>", field: "libellestatutprojet"},
        {name: "<?php echo TXT_DEMANDEUR; ?>", field: "demandeur"},
        {name: " ", field: "imprime", width: "34px"},
        {name: "<?php echo TXT_PORTEURS; ?>", field: "porteur"},
        {name: "<?php echo TXT_ACRONYME; ?>", field: "acronyme"},
        {name: "<?php echo TXT_DATEFIN; ?>", field: "calcfinprojet"},
        {name: "<?php echo TXT_DATEFIN.' proche'; ?>", field: "calcfinproche"}
    ]];

    


    /* create a new grid:*/
    var grid = new dojox.grid.EnhancedGrid({
        id: 'grid',
        store: store,
        structure: layout,
        rowSelector: '20px',        
        plugins: {menus:{headerMenu:"headerMenu", rowMenu:"rowMenu", cellMenu:"cellMenu", selectedRegionMenu:"selectedRegionMenu"}},cellFormatter: true},
      document.createElement('div'));

    /* append the new grid to the div */
    dojo.byId("gridDiv").appendChild(grid.domNode);
//----------------------------------------------------------------------------------------------------------------------
//                                        TEST METHODE
//----------------------------------------------------------------------------------------------------------------------
                    dojo.connect(dijit.byId('grid'), 'onStyleRow', this, function(row) {
                        var item = grid.getItem(row.index);
                        if (item) {                           
                            var datefin = grid.store.getValue(item, "calcfinprojet", null);
                            var datefinProche = grid.store.getValue(item, "calcfinproche", null);
                            if(!empty(datefin)){
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
                                    row.customStyles += "color:red";
                                }else  if(maint >=finp){
                                    row.customStyles += "color:darkgoldenrod";
                                }else{
                                    row.customStyles += "color:darkgreen";
                                }
                            }
                        grid.focus.styleRow(row);
                        grid.edit.styleRow(row);
                    }
                    });
    /* Call startup() to render the grid*/
    grid.startup();
});
	</script>
</head>
<body class="claro">
    <div id="gridDiv">
    <div data-dojo-type="dijit.Menu" id="headerMenu" style="display: none;">
        <div data-dojo-type="dijit.MenuItem">Header Menu Item 1</div>
        <div data-dojo-type="dijit.MenuItem">Header Menu Item 2</div>
        <div data-dojo-type="dijit.MenuItem">Header Menu Item 3</div>
        <div data-dojo-type="dijit.MenuItem">Header Menu Item 4</div>
    </div>
    <div data-dojo-type="dijit.Menu" id="rowMenu"  style="display: none;">
        <div data-dojo-type="dijit.MenuItem">Row Menu Item 1</div>
        <div data-dojo-type="dijit.MenuItem">Row Menu Item 2</div>
        <div data-dojo-type="dijit.MenuItem">Row Menu Item 3</div>
        <div data-dojo-type="dijit.MenuItem">Row Menu Item 4</div>
    </div>
    <div data-dojo-type="dijit.Menu" id="cellMenu"  style="display: none;">
        <div data-dojo-type="dijit.MenuItem">Cell Menu Item 1</div>
        <div data-dojo-type="dijit.MenuItem">Cell Menu Item 2</div>
        <div data-dojo-type="dijit.MenuItem">Cell Menu Item 3</div>
        <div data-dojo-type="dijit.MenuItem">Cell Menu Item 4</div>
    </div>
    <div data-dojo-type="dijit.Menu" id="selectedRegionMenu"  style="display: none;">
        <div data-dojo-type="dijit.MenuItem">Action 1 for Selected Region</div>
        <div data-dojo-type="dijit.MenuItem">Action 2 for Selected Region</div>
        <div data-dojo-type="dijit.MenuItem">Action 3 for Selected Region</div>
        <div data-dojo-type="dijit.MenuItem">Action 4 for Selected Region</div>
    </div>
</div>
</body>
</html>