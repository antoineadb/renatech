<?php
session_start();
include_once 'outils/toolBox.php';
include_once 'class/Manager.php';
include 'class/Securite.php';
include_once 'outils/constantes.php';
include_once 'decide-lang.php';
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {  
    header('Location: /' . REPERTOIRE . '/Login_Error/'.$lang);
}
include 'outils/parser.php';
include 'html/header.html';?>
<div id="global">
    <?php include 'html/entete.html'; ?>
    <div id="Contenuindexchoix">
        <?php  
        include 'html/moncompte.html';
        include 'html/footer.html';
        ?>
    </div>
</div>
</body>
</html>