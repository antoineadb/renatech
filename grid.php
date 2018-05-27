<script>
var myStore, dataStore, grid;
    require([
            "dojo/store/JsonRest",
            "dojo/store/Memory",
            "dojo/store/Cache",
            "dojox/grid/DataGrid",
            "dojo/data/ObjectStore",
            "dojo/query",
            "dijit/form/Button",
            "dojo/domReady!"
        ], function (JsonRest, Memory, Cache, DataGrid, ObjectStore, query) {
            myStore = Cache(JsonRest({ target: "/Blog/Action/", idProperty: "Id" }), Memory({ idProperty: "Id" }));
            grid = new DataGrid({
                store: dataStore = ObjectStore({ objectStore: myStore }),
                structure: [
                    { name: "Blog Id", field: "Id", width: "50px", },
                    { name: "Name", field: "Name", width: "200px",classes:"Name" },
                    { name: "Phone Number", field: "Phone Number", width: "200px",classes:"test" }
                ]
            }, "grid");
            grid.startup();
            dojo.query("body").addClass("claro");
            grid.canSort = function () { return false };
        });
</script>	
<script type="text/javascript" src="dojo/dojo.js" data-dojo-config="parseOnLoad: true">
       dojo.connect(grid, 'onStyleRow', this, function (row) {
        var item = grid.getItem(row.index);
        if (row.index == 0) {
            row.customClasses = "highlightRow";
            row.customStyles += 'background-color:#FFB93F;';
        }

    });
</script>
	
<script type="text/javascript" src="../js/dojo/dojo.js" data-dojo-config="parseOnLoad: true"></script>
 <script type="text/javascript">
       dojo.connect(grid, 'onStyleRow', this, function (row) {
        var item = grid.getItem(row.index);
        if (row.index == 0) {
            row.customClasses = "highlightRow";
            row.customStyles += 'background-color:#FFB93F;';
        }

    });
</script>
	
<div data-dojo-type="dojox.grid.DataGrid" data-dojo-props="???">
  <script type="dojo/method" event="onStyleRow" args="row">
        var item = grid.getItem(row.index);
        if (row.index == 0) {
            row.customClasses = "highlightRow";
            row.customStyles += 'background-color:#FFB93F;';
        } else {
             this.inherited(arguments);
        }
  </script>
</div>
