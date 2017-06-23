<?php
session_start();
include 'decide-lang.php';
include_once 'outils/toolBox.php';
include_once 'outils/constantes.php';
include_once 'class/Cache.php';
$cache =  $videCache = new Cache(REP_ROOT . '/cache', 1);
$cache->clear();
if (isset($_SESSION['pseudo'])) {
        check_authent($_SESSION['pseudo']);
    } else {
        header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
    }
include 'html/header.html';
?>
<div id="global">        
    <?php
    include 'html/entete.html'; 
    ?>
    <script>
        function unselect() {
            dijit.byId(datedebut).value = '';
            dijit.byId(datefin).value = '';
        }
    </script>    
    <form data-dojo-type="dijit/form/Form" name="exportProjet" style="font-size:1.2em;" id="exportProjet" method="post" 
          action="<?php echo '/'.REPERTOIRE ?>/controler/controleSuiviProjetRespCentrale.php?lang=<?php echo $lang; ?>"  >
        <script type="dojo/method" data-dojo-event="onSubmit">
            if(this.validate()){
                return true;
            }else{
                alert('<?php echo TXT_MESSAGEERREURCONTACT; ?>');
                return false;
                exit();
            }            
        </script>
    <div style="margin-top: 70px;">
        <?php include_once 'outils/bandeaucentrale.php'; ?>
    </div>
       <?php include_once 'admin/selectCentrale.php'; ?>
    </form>
<?php include 'html/footer.html'; ?>
</div>
</body>
</html>
