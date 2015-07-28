<?php
if (!empty($_GET['action']) && ($_GET['action'] == 'logout')) {
    $_SESSION = array();
    session_destroy();
    $_SESSION['pseudo'] = '';
}
include 'html/header.html';
include_once 'outils/toolBox.php';
include_once 'decide-lang.php';
?><div id="global">
    <?php include 'html/entete.html'; 
include_once 'include/connexion.html';
?>
    <div id="messageupdatemotpasse" style="display: block;color: red;margin-top: 10px;text-align: center;"><?php echo TXT_MISEAJOURMOTPASSE; ?></div>    
    <?php
    if (!empty($_SESSION['admin'])) {
        unset($_SESSION['admin']);
    }
    include 'html/identification.html';
    include 'html/footer.html'; ?>
				
</div>     
</body>
</html>
