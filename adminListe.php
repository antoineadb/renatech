<?php
session_start();
include('decide-lang.php');
include_once 'class/Manager.php';
include_once 'outils/toolBox.php';
include_once 'outils/affichelibelle.php';
include_once 'outils/constantes.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
?>
<div id="global">
    <?php
    include 'html/entete.html';
    include 'html/header.html';
    ?>
    <div style="margin-top: 70px;">
        <?php
        include_once 'class/Cache.php';
        define('ROOT', dirname(__FILE__));
        $cacheBandeau = new Cache(ROOT . '/cache', 60);
        if (internetExplorer() == 'false') {
            $cacheBandeau->inc(ROOT . '/outils/bandeaucentrale.php'); //RECUPERATION DU BANDEAU DEFILANT DANS LE CACHE CACHE
        } else {
            include_once 'outils/bandeaucentrale.php'; //RECUPERATION DU BANDEAU DEFILANT DANS LE CAS D'INTERNET EXPLORER
        }
        if (isset($_SESSION['pseudo'])) {
            check_authent($_SESSION['pseudo']);
        } else {
            header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
        }
        ?>
    </div>

    <div style="color: darkblue;font-size: 1.2em; margin-left: 304px; margin-top: 45px;">
        <?php include 'admin/msgErr.php' ?>    
    </div>

    <script>
        dojo.require("dijit.form.ComboBox");
    </script>
    <?php     include 'admin/choix.php'; ?>    
  <div data-dojo-type="dijit/layout/TabContainer" style="width: 1050px;font-size: 1.2em;margin-top:40px" doLayout="false">        
    <?php if ($ongletcase === true) {?>            
            <div data-dojo-type="dijit/layout/ContentPane" title="  <?php echo '--- ' . TXT_ONGLETADMINCASE . ' ---'; ?> " style="width: 1050px; height: auto;"  data-dojo-props="selected:true">
                  <?php include 'admin/formulaireCaseACocher.php'; ?>
            </div>
            <div data-dojo-type="dijit/layout/ContentPane" title=" <?php echo '--- ' . TXT_ONGLETADMINLISTE1 . ' ---'; ?>  " style=" height: auto;" >
                  <?php include 'admin/formulaireListe1.php'; ?>
            </div><
                <div data-dojo-type="dijit/layout/ContentPane" title=" <?php echo '--- ' . TXT_ONGLETADMINLISTE2 . ' ---'; ?>  " style=" height: auto;" >
                  <?php include 'admin/formulaireListe2.php'; ?>
            </div>
  <?php }elseif ($ongletliste1 == true) { ?>                
            <div data-dojo-type="dijit/layout/ContentPane" title=" <?php echo '--- ' . TXT_ONGLETADMINLISTE1 . ' ---'; ?>  " style=" height: auto;"  data-dojo-props="selected:true">
                  <?php include 'admin/formulaireListe1.php'; ?>
            </div>
            <div data-dojo-type="dijit/layout/ContentPane" title="  <?php echo '--- ' . TXT_ONGLETADMINCASE . ' ---'; ?> " style="width: 1050px; height: auto;" >
                  <?php include 'admin/formulaireCaseACocher.php'; ?>
            </div><            
            <div data-dojo-type="dijit/layout/ContentPane" title=" <?php echo '--- ' . TXT_ONGLETADMINLISTE2 . ' ---'; ?>  " style=" height: auto;" >
                  <?php include 'admin/formulaireListe2.php'; ?>
            </div>
            
  <?php } elseif ($ongletliste2 == true) { ?>                
            <div data-dojo-type="dijit/layout/ContentPane" title="  <?php echo '--- ' . TXT_ONGLETADMINLISTE2 . ' ---'; ?> " style="width: 1050px; height: 500px;" data-dojo-props="selected:true" >
                <?php include 'admin/formulaireListe2.php'; ?>
            </div>
            <div data-dojo-type="dijit/layout/ContentPane" title=" <?php echo '--- ' . TXT_ONGLETADMINLISTE1 . ' ---'; ?>  " style=" height: auto;" >
                <?php include 'admin/formulaireListe1.php'; ?>
            </div>
            <div data-dojo-type="dijit/layout/ContentPane" title="  <?php echo '--- ' . TXT_ONGLETADMINCASE . ' ---'; ?> " style="width: 1050px; height: auto;" >
                <?php include 'admin/formulaireCaseACocher.php'; ?>
            </div>            
  <?php }else{ ?>
            <div data-dojo-type="dijit/layout/ContentPane" title=" <?php echo '--- ' . TXT_ONGLETADMINLISTE1 . ' ---'; ?>  " style=" height: auto;" >
                <?php include 'admin/formulaireListe1.php'; ?>
            </div> <
            <div data-dojo-type="dijit/layout/ContentPane" title="  <?php echo '--- ' . TXT_ONGLETADMINLISTE2 . ' ---'; ?> " style="width: 1050px; height: auto;" >
                <?php include 'admin/formulaireListe2.php'; ?>
            </div>
            <div data-dojo-type="dijit/layout/ContentPane" title="  <?php echo '--- ' . TXT_ONGLETADMINCASE . ' ---'; ?> " style="width: 1050px; height: auto;" >
                <?php include 'admin/formulaireCaseACocher.php'; ?>
            </div>            
      <?php }?>
</div>
<?php include 'html/footer.html'; ?>
            </body>
            </html>
