<html> 
    <head>
        <script>
            function _(elmt) {
                return document.getElementById(elmt);
            }
            function uploadFichier() {
                var file = _('file').files[0];
                var data = new FormData();
                data.append('file', file);
                var ajax = new XMLHttpRequest();
                ajax.upload.addEventListener("progress", progressHandler, false);
                ajax.addEventListener("load", completeHandler, false);
                ajax.addEventListener("error", errorHandler, false);
                ajax.addEventListener("abort", abortHandler, false);
                ajax.open("POST", "ajout.php");
                ajax.send(data);
            }
            function progressHandler(event) {
                _('status_bytes').innerHTML = event.loaded + ' bytes uploadé sur ' + event.total;
                var pourcentage = (event.loaded / event.total) * 100;
                _('progressBar').value = Math.round(pourcentage);
                _('status').innerHTML = Math.round(pourcentage) + '% uploadé... Patientez';
            }
            function completeHandler(event) {
                _('status').innerHTML = event.target.responseText;
                _('progressBar').value = 0;
            }
            function errorHandler() {
                _('status').innerHTML = "L'upload a échoué !";
            }
            function abortHandler() {
                _('status').innerHTML = "L'upload a été annulé !";
            }
        </script>
    </head>
<body> 
    <center><h3>Réduire le poids d'une image</h3></center> 
    <h1>Barre de progression dynamique en HTML5</h1>
    <form method="post" enctype="multipart/form-data" action="ajout.php?ordre=ajouter"> 
        <input name='fichier' type='file' id='file'   size='20' style="font-size:9pt; color:#FFFFFF ; background-color: #000000">         
        <input type="button" value="Envoye"  onclick="uploadFichier()"><br /><br />
        <progress id="progressBar" value="0" max="100" style="width: 500px;height: 20px"></progress>
    </form> 
    <h2 id="status"></h2>
    <p id="status_bytes"></p>
</body> 
</html> 
