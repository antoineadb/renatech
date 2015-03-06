<?php
session_start();
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
<script src="<?php echo '/'.REPERTOIRE ?>/js/ajaxefface.js"></script>
<script src="<?php echo '/'.REPERTOIRE ?>/js/ajax.js"></script>
<script>
function checkautrecentrale(id) {
    require(["dojo/dom", "dijit/registry"], function (dom, registry) {
        var inputs = registry.findWidgets(dom.byId(id));
        centrale = [];
        for (var i = 0, il = inputs.length; i < il; i++) {
            if (inputs[i].checked) {
                centrale.push(inputs[i].value);
            }
        }
    });
    if (centrale.length === 0) {
        return false;
    } else {
        return true;
    }
}
function autrecentrale(id) {
    require(["dojo/dom", "dijit/registry"], function (dom, registry) {
        var inputs = registry.findWidgets(dom.byId(id));
        centrale = [];
        for (var i = 0, il = inputs.length; i < il; i++) {
            if (inputs[i].checked) {
                centrale.push(inputs[i].value);
            }
        }
    });
    if (centrale.length > 0) {
        return centrale;
    }
}

</script>
        <div id="global">
            <?php include 'html/entete.html';?>
            <div style="margin-top: 100px;color:red;font-size: 1.2em;width:1030px;text-align: center" >
                <?php 
                  if(isset($_GET['erreuruploadphase1'])){
                      echo TXT_ERREURUPLOADSUCCINCTE.TXT_ERREURUPLOAD;
                  } elseif(isset($_GET['erreuruploadsizephase1'])){
                      echo TXT_ERREURUPLOADSUCCINCTE.TXT_ERREURTAILLEFICHIER;
                  }elseif(isset($_GET['erreurupload'])){
                      echo TXT_ERREURUPLOADDETAILLE.TXT_ERREURUPLOAD;
                  } elseif(isset($_GET['erreuruploadsize'])){
                      echo TXT_ERREURUPLOADDETAILLE.TXT_ERREURTAILLEFICHIER;
                  }
                ?>
            </div>
            <?php if(isset($_GET['report'])|| isset($_GET['erreuruploadsizerapportfiglog'])|| isset($_GET['erreuruploadsizerapportfig'])|| isset($_GET['erreuruploadsizerapport']) || isset($_GET['erreuruploadsizerapportlog'])||isset($_GET['erreuruploadsizerapportcent'])||isset($_GET['erreuruploadsextension'])){?>
            <div data-dojo-type="dijit/layout/TabContainer" style="margin-top:30px;width: 1050px;font-size: 1.2em;" doLayout="false" id="MyTabContainer" >
                <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo TXT_DESCRIPTIONSUCCINTE ?>" style="width: auto; height: auto;overflow-y: scroll" ><?php include 'html/vueModifProjet.html'; ?></div>
                <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo TXT_DESCRIPTIONDETAILLE ?>" style="width: auto; height: auto;overflow:hidden;"  ><?php  include 'html/phase2.html'; ?></div>
                <?php if(isset($_GET['erreuruploadsizerapportfig'])|| isset($_GET['erreuruploadsizerapportfiglog'])|| isset($_GET['erreuruploadsizerapport']) || isset($_GET['erreuruploadsizerapportlog'])||isset($_GET['erreuruploadsizerapportcent'])||isset($_GET['erreuruploadsextension'])){?>
                <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo 'rapport' ?>"   id="test"   style="width: auto; height: 1900px;overflow:hidden;" selected="true" ><?php  include  'html/rapport.html'; ?></div>
                <?php }else{?>
                <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo 'rapport' ?>"   id="test"   style="width: auto; height: 1820px;overflow:hidden;" selected="true" ><?php  include  'html/rapport.html'; ?></div>
                <?php }?>
            </div>
            <?php }else{ ?>
            <div data-dojo-type="dijit/layout/TabContainer" style="margin-top:30px;width: 1050px;font-size: 1.2em;" doLayout="false" id="MyTabContainer" >
                <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo TXT_DESCRIPTIONSUCCINTE ?>" style="width: auto; height: auto;overflow-y: scroll" ><?php include 'html/vueModifProjet.html'; ?></div>
                <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo TXT_DESCRIPTIONDETAILLE ?>" style="width: auto; height: auto;overflow:hidden;" selected="true" ><?php  include 'html/phase2.html'; ?></div>
            </div>
            <?php } ?>
        <?php include 'html/footer.html'; ?>
        </div>
    </body>
</html>