<!DOCTYPE HTML>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <script language="javascript" type="text/javascript">
	function affiche_chargement(){
            var Obj  = document.getElementById( 'd_input'); // Recup du DIV
            var Html = ""; // le nouveau contenu
            Html += "<img src='../styles/img/loading.gif' alt='Chargement en cours. \n Veuillez patienter ...'/>";
            Obj.innerHTML = Html; // ecriture dans le DIV
	} 
    </script>
    <link rel="stylesheet" href="../styles/style.css" media="screen">
    </head>
    <body class="claro">
        <form action="#" onsubmit="affiche_chargement()">
        <table><tr><td id="d_input"></td></tr></table>
        <input type="submit" value="valide"/>
        </form>
    </body>
</html>