<?php
include 'html/header.html';
include_once 'decide-lang.php';
?>
<div id="global">
    <?php include 'html/entete.html'; ?>
    <div id="Contenuindexchoix" style=" margin-top: 35px;">
        <fieldset id="ident" style="border-color: #5D8BA2;width:1007px;font-size: 1.2em">
            <div style="height: 35px;width:50px" id="erreurUpload">
                <label style="color: red;display:block;font-size: 15px; width: 450px">
                    <?php echo TXT_ACCESLIMIT.'<br>'; ?>
                    <a href="<?php echo '/'.REPERTOIRE;?>/index/<?php echo $lang; ?>" ><?php echo TXT_RETOURACCUEIL; ?></a>
                </label>
            </div>
        </fieldset>
        <?php include 'html/footer.html'; ?>
    </div>
</div>
</body>
</html>