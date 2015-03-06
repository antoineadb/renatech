<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">        
        <link rel="stylesheet" href="../js/dijit/themes/claro/claro.css" media="screen">
        <link rel="stylesheet" href="demo.css">
    </head>
    <body class="claro">        
        <fieldset id="dev" style="border-color: #5D8BA2;width:1005px;border-radius:5px;padding-bottom:20px">
            <legend style="margin-left: 20px;">Sélection des projets</legend>
            <div>
                <input id="topping1" type="radio" name="topping" value="500 premiers" checked
                       data-dojo-type="dijit/form/RadioButton" style="margin-left:10px">
                <label for="topping1" style="margin-right:10px">les 500 premiers projets</label>
                
                <input id="topping2" type="radio" name="topping" value="de 501 à 1000"
                       data-dojo-type="dijit/form/RadioButton" >
                <label for="topping2" style="margin-right:10px">du 501 à 1000 projets</label>
            
                <input id="topping3" type="radio" name="topping" value="de 1001 à 1500"
                       data-dojo-type="dijit/form/RadioButton" >
                <label for="topping3" style="margin-right:10px">du 1001 à 1500 projets</label>
                <input id="topping4" type="radio" name="topping" value=">1501"
                       data-dojo-type="dijit/form/RadioButton" >
                <label for="topping4" style="margin-right:10px">du  1501 projets aux autres projets</label>
            </div>
        </fieldset>
        
<!--        <button id="summaryBtn">choix</button>
        <script src="../js/dojo/dojo.js"></script>
        <script>
            require(["dijit/form/RadioButton", "dojo/on", "dijit/registry", "dojo/parser", "dojo/domReady!"], function(RadioButton, on, registry, parser) {
                parser.parse();
                on(document.getElementById("summaryBtn"), "click", function(evt) {
                    var toppings = [];
                    if (registry.byId("topping1").get("checked")) {
                        toppings.push(registry.byId("topping1").get("value"));
                    }if (registry.byId("topping2").get("checked")) {
                        toppings.push(registry.byId("topping2").get("value"));
                    }if (registry.byId("topping3").get("checked")) {
                        toppings.push(registry.byId("topping3").get("value"));
                    }if (registry.byId("topping4").get("checked")) {
                        toppings.push(registry.byId("topping4").get("value"));
                    }alert("choix: " + toppings.join(", "));
                });
            });

        </script>-->
    </body>
</html>