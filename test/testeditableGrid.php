<!DOCTYPE html>
<html >
    <head>

        <link rel="stylesheet" href="../js/dojo/dijit/themes/claro/claro.css">
        <style type="text/css">
            @import "../js/dojo/resources/dojo.css";
            @import "../js/dijit/themes/claro/claro.css";
            @import "../js/dojox/grid/enhanced/resources/claro/EnhancedGrid.css";
            @import "../js/dojox/grid/enhanced/resources/EnhancedGrid_rtl.css";

            /*Grid need a explicit width/height by default*/
            #grid {
                width:550px;
                height:350px;
            }
        </style>
        <script>dojoConfig = {parseOnLoad: true}</script>
        <script src='../js/dojo/dojo.js'></script>

        <script>
            dojo.require("dojox.grid.EnhancedGrid");
            dojo.require("dojo.data.ItemFileWriteStore");
            dojo.require("dojox.grid.enhanced.plugins.IndirectSelection");

            dojo.ready(function() {
                /*set up data store*/
                var data = {
                    identifier: 'id',
                    items: []
                };
                var data_list = [
                <?php include "../tmp/test1.json"; ?>];
                for (var i = 0, l = data_list.length; i <= data_list.length; i++) {
                    data.items.push(dojo.mixin({id: i + 1}, data_list[i % l]));
                }
                var store = new dojo.data.ItemFileWriteStore({data: data});
                var layout = [[
                        //{name: 'id', field: 'col1',width: "25px"},
                        {name: 'Numéro', field: 'col2', width: "65px"},
                        {name: 'Titre', field: 'col3', width: "280px"},
                        {name: 'Statut', field: 'col4', width: "150px"}
                    ]];

                /* create a new grid:*/
                var grid = new dojox.grid.EnhancedGrid({
                    id: 'grid',
                    store: store,
                    structure: layout,
                    rowSelector: '20px',
                    plugins: {indirectSelection: {headerSelector: true, width: "20px", styles: "text-align: center;"}}},
                document.createElement('div'));
                dojo.byId("gridDiv").appendChild(grid.domNode); 
                grid.startup();
            });

        </script>

    </head>
    <body class="claro"> 
        <div id="gridDiv" ></div>
        <p>
            <input type="button" data-dojo-id="button1" value='get Selected Items' onclick="
                    console.log(dojo.byId('gridDiv')   );
                    /*var items = grid.selection.getSelected();
                    for (i = 0; i < items.length; i++) {
                        console.log(items[i]['col3']);
                        console.log(items[i]['col4']);
                    }*/
                   ">

        </p>
       
    </body>
</html>