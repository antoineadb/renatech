<?php

session_start();
include 'outils/phpPhase2.php'; 
include('decide-lang.php');
include_once 'outils/constantes.php';
include_once 'outils/toolBox.php';
include_once 'class/Cache.php';
define('ROOT',  dirname(__FILE__));
$Cache = new Cache(ROOT.'/cache', 60);

if (isset($_SESSION['pseudo'])) {
        check_authent($_SESSION['pseudo']);
    } else {
         header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
include 'html/header.html';
?>

</script>
        <div id="global">
            <?php include 'html/entete.html';?>            
                <input name="page_precedente" type="hidden" value="<?php echo basename(__FILE__); ?>">            
            <div data-dojo-type="dijit/layout/TabContainer" style="margin-top:100px;width: 1050px;font-size: 1.2em;" doLayout="false" id="MyTabContainer" >
                <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo TXT_DESCRIPTIONSUCCINTE ?>" style="width: auto; height: auto;" ><?php include 'html/vueModifProjetSoutraitance.html'; ?></div>
            <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo TXT_DESCRIPTIONDETAILLE ?>" style="width: auto; height: auto;overflow:hidden;" selected="true" ><?php  include 'html/phase2Soutraitance.html'; ?></div>
            </div>
            <?php  include 'html/footer.html'; ?>
        </div>
    </body>
</html>