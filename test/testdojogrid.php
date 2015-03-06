<!DOCTYPE html>
<html >    
<head>
    <title></title>
    <link rel="stylesheet" href="../js/dijit/themes/claro/claro.css">
        <?php include_once '../outils/constantes.php'; ?>
	<script>dojoConfig = {parseOnLoad: true}</script>
	<script src='../js/dojo/dojo.js'></script>
        <script src='../js/controle.js'></script>
</head>	
<body>
<div id="grid" ></div>
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
    ], function(DataGrid, Memory, ObjectStore, request, stamp, locale) {
        request.get("<?php echo '/'.REPERTOIRE;?>/tmp/projetCentrale.json", {
            handleAs: "json"}).then(function(data) {
            store = new Memory({data: data.items});
            dataStore = new ObjectStore({objectStore: store});
            function hrefFormatterNumero(numero, idx) {
                var item = grid.getItem(idx);
                var centrale = item.libellecentrale;               
                var statut = +item.idstatutprojet;
                if (statut === +'<?php echo REFUSE; ?>' || statut === +'<?php echo CLOTURE; ?>') {
                    return numero;
                } else {
                    return "<a  href=\"<?php echo '/'.REPERTOIRE;?>/controler/controlestatutprojet.php?lang=<?php echo $lang; ?>&statut=" + statut + "&numProjet=" + numero +"&centrale=" + centrale + "\">"+ numero + "</a>";
                }
            }
            function hrefFormatterPDF(index, idx) {
                var item = grid.getItem(idx);
                var numero = item.numero;
                return "<a  href=\"<?php echo '/'.REPERTOIRE;?>/pdf_project/<?php echo $lang; ?>/" + numero + "\" target='_blank'>" +
                        '<img title="<?php echo TXT_GENERERPDF; ?>" src="<?php echo "/".REPERTOIRE; ?>/styles/img/pdf_icongrid.png" />' + "</a>";
            }     
            function formatDate(datum) {
                var d = stamp.fromISOString(datum);
                var annee =  datum.substring(0,4);
                if(annee>1970){
                    return locale.format(d, {selector: 'date', formatLength: 'short'});
                }
            }
            grid = new DataGrid({
                store: dataStore,
                query: {id: "*"},
                structure: [
                    {name: "<?php echo TXT_NUMERO; ?>", field: "numero", width: "100px", formatter: hrefFormatterNumero},
                    {name: "<?php echo TXT_DATEDEMANDE; ?>", field: "dateprojet", width: "80px", formatter: formatDate},
                    {name: "<?php echo TXT_TITREPROJET; ?>", field: "titre", width: "220px"},                    
                    {name: "<?php echo TXT_UPDATE; ?>", field: "datemaj", width: "80px", formatter: formatDate},
                    {name: "<?php echo TXT_REFINTERNE; ?>", field: "refinterneprojet", width: "90px"},
                    {name: "<?php echo TXT_STATUTPROJETS; ?>", field: "libellestatutprojet", width: "105px"},
                    {name: "<?php echo TXT_DEMANDEUR; ?>", field: "demandeur", width: "140px"},
                    {name: " ", field: "imprime", width: "34px", formatter: hrefFormatterPDF},
                    {name: "<?php echo TXT_PORTEURS; ?>", field: "porteur", width: "300px"},
                    {name: "<?php echo TXT_ACRONYME; ?>", field: "acronyme", width: "80px"},                    
                    {name: "<?php echo TXT_DATEFIN; ?>", field: "calcfinprojet", width: "120px", formatter: formatDate},
                    {name: "<?php echo TXT_DATEFIN.' proche'; ?>", field: "calcfinproche", width: "120px", formatter: formatDate}
                ]
            }, "grid");
            
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
            
            grid.startup();
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
</body>
</html> 