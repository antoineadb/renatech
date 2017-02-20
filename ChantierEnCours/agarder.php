<?php 

if(isset($_SESSION['pseudo'])){
    $url_parse = parse_url($_SERVER['PHP_SELF']);
    $url_path = explode("/", $url_parse['path']);
    $adresse = $url_path[2];    
?>
    <button onclick="start()">Lancer le décompte</button>
<?php
    $limitInactif = (int) $manager->getSingle2("select tmpcx from loginpassword where pseudo=?", $_SESSION['pseudo']) * 60;         
?>
<script>

var counter = <?php echo $limitInactif;?>;
var intervalId = null;
function action(){
  clearInterval(intervalId);  
    <?php  if ($adresse == 'phase2.php' || $adresse == 'creerprojetphase2.php') {?>
        window.location.replace("/<?php echo REPERTOIRE; ?>/index/<?php echo $lang;?>/logout/toto");  
    <?php }else{?>
        window.location.replace("/<?php echo REPERTOIRE; ?>/index/<?php echo $lang;?>/logout");  
  <?php }?>
  
  document.getElementById("bip").innerHTML = "TERMINE!";
}
function bip(){
  document.getElementById("bip").innerHTML = counter + " sec.";
  counter--;
}
function start(){
  intervalId = setInterval(bip, 1000);
  setTimeout(action, counter * 1000);
}	
</script>
<?php }