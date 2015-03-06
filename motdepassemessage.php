<?php
    include('decide-lang.php');
    include 'html/header.html';
    
?>
<div id="global">
<?php  include 'html/entete.html';?> 
<br>
<fieldset id="fielmotpassemessage" >
    <legend style="color: #5D8BA2;"><?php echo TXT_RETROUVEMOTPASSE; ?></legend>
    <div id="motpassemessage" ><?php echo TXT_MOTPASSEMESSAGE; ?></div>
    <a href="<?php echo '/'.REPERTOIRE;?>/index/<?php echo $lang; ?>"> <?php echo TXT_RETOURPAGE;?></a>
</fieldset>
<?php  include 'html/footer.html'; ?>
</div>
</body>
</html>