<?php include '../outils/affichelibelle.php';?>
<!DOCTYPE HTML>
<html lang="en">
    <script src="../js/dojo/dojo.js"></script>
	<head>
		<meta charset="utf-8">		
		<style>
			p { padding:0; margin:0; }
		</style>
		<link rel="stylesheet" href="../js/dijit/themes/claro/claro.css" media="screen">		
	</head>
	<body class="claro">
            <br><br><br>
            <div style="width: 970px;;font-size:14px;margin-bottom:20px" class="loginlabel"><?php echo substr(affiche('TXT_ACCUEIL1'),0,371); ?>
                <a href="#" id="acceuil1" style="text-decoration:none"><?php echo "  ... la suite ...    "?></a>
		<div class="dijitHidden">
			<div data-dojo-type="dijit/Tooltip" data-dojo-props="connectId:'acceuil1'">
				<p style="width:600px;"><?php echo substr(affiche('TXT_ACCUEIL1'),372,500); ?></p>
				<br style="clear:both;">
			</div>
		</div>
	</div>	
		
		<script>
			require(["dijit/Tooltip", "dojo/parser", "dojo/domReady!"], function(Tooltip, parser){
				// Change the tooltip positions
				Tooltip.defaultPosition = ["after-centered","below"];
				parser.parse();
			});
		</script>
	</body>
</html>
