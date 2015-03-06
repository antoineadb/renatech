<!DOCTYPE html>
<html >
    <head>

        <link rel="stylesheet" href="../js/dojo/dijit/themes/claro/claro.css">
        <?php include '../decide-lang.php'; ?>
        <style type="text/css">
            @import "../js/dojo/resources/dojo.css";
            @import "../js/dijit/themes/claro/claro.css";
            @import "../js/dojox/grid/enhanced/resources/claro/EnhancedGrid.css";
            @import "../js/dojox/grid/enhanced/resources/EnhancedGrid_rtl.css";
            @import "../js/dojox/grid/resources/claroGrid.css";
            /*Grid need a explicit width/height by default*/
            #grid {
                width:550px;
                height:350px;
            }
        </style>
        <script>dojoConfig = {parseOnLoad: true}</script>
        <script src='../js/dojo/dojo.js'></script>

        <script>
            require(['dojo/_base/array', 'dojo/_base/lang', 'dojo/_base/event', 'dojo/on', 'dojox/grid/DataGrid', 'dojo/data/ItemFileWriteStore', 'dijit/form/Button', 'dojo/dom', 'dojo/parser', 'dojo/domReady!'],
                    function(array, lang, event, on, DataGrid, ItemFileWriteStore, Button, dom, parser) {
                        parser.parse();
                        var data = {
                            identifier: 'id',
                            items: []
                        };
                        var data_list = [<?php include "../tmp/toutprojetCentrale.json"; ?>];
                        for (var i = 0, l = data_list.length; i < data_list.length; i++) {
                            data.items.push(lang.mixin({id: i + 1}, data_list[i % l]));
                        }
                        var store = new ItemFileWriteStore({data: data});
                        var layout = [[
                                {'name': '<?php echo utf8_decode(TXT_NUMROPROJET); ?>', 'field': 'col2', 'width': "100px"},
                                {'name': '<?php echo utf8_decode(TXT_TITREPROJET); ?>', 'field': 'col3', 'width': "280px"},
                                {'name': '<?php echo utf8_decode(TXT_STATUTPROJETS); ?>', 'field': 'col4', 'width': "150px"}
                            ]];
                        grid = new DataGrid({
                            id: 'grid',
                            store: store,
                            structure: layout,
                            rowSelector: '20px'});
                        grid.placeAt('gridDiv');
                        grid.startup();
                    });

        </script>
    </head>
    <body class="claro">
        <p>
            Select a single row or multiple rows in the Grid (click on the Selector on the left side of each row).
            After that, a click on the Button "get all Selected Items" will show you each attribute/value of the
            selected rows.
        </p>

        <div id="gridDiv"></div>

        <p>
            <input type="button" data-dojo-id="button1" value='get all Selected Items' onclick="
                    var items = grid.selection.getSelected();
                    for (i = 0; i < items.length; i++) {
                        console.log(items[i]['col2']);
                    }
                   ">

        </p>
    </body>
</html>