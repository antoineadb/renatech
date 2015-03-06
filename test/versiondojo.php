<!DOCTYPE html>
<html >
<head>

	<link rel="stylesheet" href="../_static/js/dojo/../dijit/themes/claro/claro.css">
	
	<script>dojoConfig = {parseOnLoad: true}</script>
        <script src='../js/dojo/dojo.js'></script>
	
	<script>
dojo.ready(function(){
    dojo.query(".info").attr("innerHTML", dojo.version);
});
	</script>
</head>
<body class="claro">
    <div class="info"></div>
</body>
</html>

