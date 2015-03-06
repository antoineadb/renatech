<?php
session_start();
include('decide-lang.php');
include_once 'outils/constantes.php';
include_once 'outils/toolBox.php';
if (!empty($_GET['page_precedente'])){
    if($_GET['page_precedente'] == 'updatemotpasse.php' || $_GET['page_precedente']=='updatemoncompte.php') {
        include 'html/header.html';    
?>
<div id="global">
     <?php include 'html/entete.html'; ?>
     <div id="contenu">
	  <?php include 'html/moncompteMessageUpdate.html'; ?>
     </div>
     <?php include 'html/footer.html'; ?>
</div>
</body>
</html>

    <?php }
}else{
    header('Location: /' . REPERTOIRE . '/Login_Error/'.$lang);
}
