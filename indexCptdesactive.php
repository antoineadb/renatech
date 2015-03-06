<?php
session_start();
include 'html/header.html';
?>
<div id="global">
    <?php 
        $_SESSION['pseudo']='';
        include 'html/entete.html';    
        ?>
    <div id="contenu">
        <?php include 'html/indexCptdesactive.html'; ?>
        <?php include 'html/footer.html'; ?>
    </div>

</div>
</body>
</html>