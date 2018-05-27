<!DOCTYPE HTML>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <script>
            var maxprogress = 250;   // total à atteindre
            var actualprogress = 0;  // valeur courante
            var itv = 0;  // id pour setinterval
            function prog(){
                if (actualprogress >= maxprogress){
                    clearInterval(itv);
                    return;
                }
                //var progressnum = document.getElementById("progressnum");
                var indicator = document.getElementById("indicator");
                actualprogress += 1;
                indicator.style.width = actualprogress + "px";
                //progressnum.innerHTML = actualprogress;
                if (actualprogress === maxprogress){
                    clearInterval(itv);
                }
            }
        </script>

        <title>Demo: Loading Overlay (Step 2)</title>

        <link rel="stylesheet" href="../styles/style.css" media="screen">

        <!-- load dojo and provide config via data attribute -->

    </head>
    <body class="claro">
        <div id="pwidget"><div id="progressbar"><div id="indicator"></div></div></div>
        <input type="button" name="Submit" value="Lancer la progression"
               onclick="itv = setInterval(prog, 100)" />
        <input type="button" name="Submit" value="Stopper"
               onclick="clearInterval(itv)" />	
    </body>
</html>