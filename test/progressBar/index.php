<!DOCTYPE html>
<html lang="fr">
	<head>
		<title>Barre de progression dynamique en HTML5</title>
		<meta charset="UTF-8" />
		<script>
			function _(elmt){
				return document.getElementById(elmt);
			}
			function uploadFichier(){
				var file = _('file').files[0];
				var data =  new FormData();
				data.append('file', file);
				var ajax = new XMLHttpRequest();
				ajax.upload.addEventListener("progress", progressHandler, false);
				ajax.addEventListener("load", completeHandler, false);
				ajax.addEventListener("error", errorHandler, false);
				ajax.addEventListener("abort", abortHandler, false);
				ajax.open("POST", "upload.php");
				ajax.send(data);
			}
			function progressHandler(event){
				_('status_bytes').innerHTML = event.loaded + ' bytes uploadé sur ' + event.total;
				var pourcentage = (event.loaded / event.total ) * 100;
				_('progressBar').value = Math.round(pourcentage);
				_('status').innerHTML = Math.round(pourcentage) + '% uploadé... Patientez';
			}
			function completeHandler(event){
				_('status').innerHTML = event.target.responseText;
				_('progressBar').value = 0;
			}
			function errorHandler(){
				_('status').innerHTML =  "L'upload a échoué !";
			}
			function abortHandler(){
				_('status').innerHTML =  "L'upload a été annulé !";
			}
		</script>
	</head>
	<body>
		<h1>Barre de progression dynamique en HTML5</h1>
		<form method="post" enctype="multipart/form-data">
			<input type="file" id="file" name="file" /> <br/>
			<input type="button" value="Uploader le fichier" onclick="uploadFichier()" /> <br/>
			<progress id="progressBar" value="0" max="100" style="width:500px"></progress>
		</form>

		<h2 id="status"></h2>
		<p id="status_bytes"></p>
	</body>
</html>