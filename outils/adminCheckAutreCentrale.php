<?php if(isset($_SESSION['idTypeUser'])&& $_SESSION['idTypeUser']==ADMINLOCAL){
    $idcentrale=$manager->getIdCentraleFromLogin($_SESSION['pseudo']);
    $libellecentrale = 'lbl'.str_replace("-","",$manager->getSingle2("select libellecentrale from centrale where idcentrale=?",$idcentrale));
?>
  <script>
        var selectedCentrale =<?php echo 'ce'.$idcentrale; ?>;
        var libellecentrale = <?php echo $libellecentrale;?>;
        dijit.byId(selectedCentrale).set('disabled',true);
        document.getElementById(libellecentrale).style.textDecoration  ='line-through';
    </script>
<?php } ?>