<!DOCTYPE html>
<html >
<head>

					<link rel="stylesheet" href="../js/dijit/themes/claro/claro.css">

	<script>dojoConfig = {parseOnLoad: true}</script>
	<script src='../js/dojo/dojo.js'></script>

	<script>
dojo.require("dijit.form.Button");

dojo.ready(function(){
    var button = new dijit.form.Button({
        label: "Destroy TestNode1",
        onClick: function(){
            dojo.destroy("testnode1");
            dojo.byId("result1").innerHTML = "TestNode1 was destroyed.";
        }
    }, "progButtonNode");

});

dojo.ready(function(){
    var button2 = new dijit.form.Button({
        label: "Create TestNode1",
        onClick: function(){
            dojo.create("div", { innerHTML: "<p>hi</p>" });
            dojo.byId("result1").innerHTML = "TestNode1 was created.";
        }
    }, "progButtonNode2");

});

	</script>
</head>
<body class="claro">
    <div id="testnode1">TestNode 1</div>
<button id="progButtonNode" type="button"></button>
<button id="progButtonNode2" type="button"></button>
<div id="result1"></div>
</body>
</html>