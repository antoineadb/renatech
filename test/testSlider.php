<!DOCTYPE HTML>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Demo: Horizontal and Vertical Sliders with Rules and RuleLabels</title>	
        <link rel="stylesheet" href="../js/dijit/themes/claro/claro.css" media="screen">
</head>
<body class="claro">
<div style="width: 400px;margin-left: 200px;margin-top:50px">
	<input id="hslider" type="range" value="30" data-dojo-type="dijit/form/HorizontalSlider" data-dojo-props="minimum: 30,maximum: 240,showButtons: false,discreteValues: 30">
	<div data-dojo-type="dijit/form/HorizontalRule" data-dojo-props="container: 'bottomDecoration',count: 3" style="height: 5px; margin: 0 12px;"></div>
	<ol data-dojo-type="dijit/form/HorizontalRuleLabels"data-dojo-props="container: 'bottomDecoration'" style="height: 1em; font-weight: bold;">
		<li>low</li>
		<li>normal</li>
		<li>high</li>
	</ol>
	<div style="padding-top: 10px; text-align: center;">Value: <strong id="decValue"></strong></div>
</div>
<script src="../js/dojo/dojo.js" data-dojo-config="isDebug: true, async: true"></script>
<script>
	require(["dijit/form/HorizontalSlider", "dijit/form/HorizontalRuleLabels", "dijit/form/HorizontalRule",  "dijit/form/Button", "dojo/dom-construct", "dojo/aspect", "dijit/registry", "dojo/parser", "dojo/dom", "dojo/domReady!"],
			function(HorizontalSlider,  HorizontalRuleLabels, HorizontalRule,  Button, domConstruct,    aspect, registry, parser, dom){
				parser.parse();
				var hSlider = registry.byId("hslider"), label = dom.byId("decValue");
				function updateHorizontalLabel() {
					label.innerHTML = Math.round(hSlider.get("value"));
				}
				aspect.after(hSlider, "onChange", updateHorizontalLabel, true);
				updateHorizontalLabel();
				
			});

</script>
</body>
</html>
