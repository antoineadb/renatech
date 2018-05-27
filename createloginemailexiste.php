<?php 
session_start();
include 'html/header.html'; ?>
<div id="global">
    <?php include 'html/entete.html'; ?>
    <fieldset id="createLoginemail">
    <legend style="color: #5D8BA2"><b><?php echo html_entity_decode((TXT_CREATEADMIN)); ?></b></legend>
    <div id="emailExiste"><?php echo TXT_EMAILEXISTE;?><br><br>
        <div>
            <?php 
            $arrayLogin = explode(",",$_SESSION['logindoublon']);
            for ($i = 0; $i < count($arrayLogin); $i++) {
                echo $arrayLogin[$i].'<br>';
            }?>
        </div>
        <br><?php echo TXT_CONFIRMEMAILEXISTE ?>
        <button data-dojo-Type="dijit.form.Button" style="margin-left: 20px" label="<?php echo ucfirst(strtolower(TXT_VALIDER)); ?>">
                <script type="dojo/on" data-dojo-event="click" data-dojo-args="evt">
                    <?php  $_SESSION['validEmail']='Ok'; ?>
                    require(["dojo/dom"], function(dom){
                        window.location.replace("<?php echo '/'.REPERTOIRE;?>/securite/<?php echo $lang;?>");
                    })
                </script>
            
        </button>
        <br><?php echo TXT_CHANGEPSEUDO;?>  
        <button type="button" label="<?php echo ucfirst(strtolower(TXT_INSCRIPTION)); ?>" data-dojo-Type="dijit/form/Button">
                <script type="dojo/on" data-dojo-event="click" data-dojo-args="evt">
                    require(["dojo/dom"], function(dom){
                   window.location.replace("<?php echo '/'.REPERTOIRE;?>/login/<?php echo $lang;?>");
                    })
                </script>
        </button>
    </div>
    </fieldset>
    
    <div>
        <a href="/<?php echo REPERTOIRE . '/' . 'index/'; ?>/<?php echo $lang.'/logout'; ?>" style="text-decoration: none;color: red"><?php echo ' ' . TXT_RETOURACCUEIL; ?></a></div>
        <?php include 'html/footer.html'; ?>
</div>
</body>
</html>