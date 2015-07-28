<?php
session_start();
include_once 'outils/constantes.php';
include_once 'outils/toolBox.php';
include_once 'decide-lang.php';
if (isset($_SESSION['pseudo'])) {
        check_authent($_SESSION['pseudo']);
    } else {
         header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
    }
include 'html/header.html';
?>
<div id="global">
    <?php include 'html/entete.html'; ?>
           <div style="margin-top: 70px;">
        <?php include_once 'outils/bandeaucentrale.php'; ?>
    </div>
    <fieldset id="ident" style="border-color: #5D8BA2;margin-top: 25px;"  >        
        <legend style="font-size: 1.2em"><?php echo TXT_DELETEPROJET.' ' ;?><a class="infoBulle" href="#"><img src='<?php echo "/".REPERTOIRE; ?>/styles/img/help.gif' height="13px" width="13px"/>
                                        <span style="text-align: left;padding:10px;width: 230px;border-radius:5px" >
                                            <?php echo TXT_SUPPRESSIONPROJET;?></span></a> </legend>
        
        <div style="   height: 30px;margin-top: -5px;text-align: center;width: auto;" ><?php echo TXT_PROJETSENATTENTE. ' - '.TXT_PROJETSACCEPTE.' - '.TXT_CONSULTPROJET ;?></div>
    <?php 
            if(isset($_GET['idprojet'])){
                $numero = $manager->getSingle2("select numero from projet where idprojet=?",$_GET['idprojet']);?><div style="margin-left:350px;"><?php echo TXT_PROJETNUM.' '.$numero.' '.TXT_RESTORED ;?></div> 
    <?php   }?>
    <?php 
            if(isset($_GET['numero'])){
                ?><div style="margin-left:350px;"><?php echo TXT_PROJETNUM.' '.$_GET['numero'].' '.TXT_HASBEENDELETED ;?></div> 
    <?php   }?>           
                
        <div style="margin-left: 950px;margin-top:-40px">
            <?php            
            $idcentrale=$manager->getSingle2("SELECT distinct(u.idcentrale_centrale) FROM loginpassword,concerne c,utilisateur u WHERE idlogin = idlogin_loginpassword AND u.idcentrale_centrale = c.idcentrale_centrale "
                    . "and pseudo=?",$_SESSION['pseudo']);
            $arrayTrashed = $manager->getList2("SELECT   idprojet FROM projet,concerne WHERE idprojet = idprojet_projet and idcentrale_centrale=? and trashed = TRUE",$idcentrale); 
            if(!empty($arrayTrashed)){?>
            <span id="progmenu">
                <a href="<?php echo '/'.REPERTOIRE.'/view_trash_project/'.$lang; ?>" title="<?php echo TXT_OPENTRASH ;?>">
                    <img src="<?php echo '/'.REPERTOIRE; ?>/styles/img/corbeille-pleine.png"  >
                </a>
            </span>
            <script>
            function menuConfirmationEmptyTrash(e){
                dijit.byId(e).set('title', '<?php echo TXT_CONFIRMEMPTYTRASH ;?>');
                repEmptyTrash.show();
            }
            </script>
            <script>
                require([
                    "dijit/Menu",
                    "dijit/MenuItem",
                    "dijit/CheckedMenuItem",
                    "dijit/MenuSeparator",
                    "dijit/PopupMenuItem",
                    "dojo/domReady!"
                ], function(Menu, MenuItem){
                    var pMenu;
                    pMenu = new Menu({
                        targetNodeIds: ["progmenu"],
                    });
                    pMenu.addChild(new MenuItem({
                        label: "<?php echo TXT_OPEN; ?>",
                        onClick: function(){
                            window.location.replace('<?php echo "/".REPERTOIRE;?>/view_trash_project/<?php echo $lang ?>');
                        }
                    }));
                    pMenu.addChild(new MenuItem({
                        label: "<?php echo TXT_EMPTYTRASH; ?>",
                        onClick: function(){
                            dijit.byId(repEmptyTrash).set('title', '<?php echo TXT_CONFIRMEMPTYTRASH ;?>');
                            repEmptyTrash.show();
                        }
                    }));    
                    pMenu.startup();
                });
            </script>
            <?php }else{ ?>
        <img src="<?php echo '/'.REPERTOIRE; ?>/styles/img/corbeille-vide.png"  >
            <?php }?>
        </div>    
        <div data-dojo-type="dijit/layout/TabContainer" style="width: 1009px;margin-top:5px" doLayout="false">
            <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo TXT_PROJETENATTENTE; ?>" style="width: auto; height: auto;" selected="true" >
                <?php include 'findprojecttodelete.php'; ?>
            </div>
            <div data-dojo-type="dijit/layout/ContentPane" title="<?php echo TXT_PROJETMEMETITRE; ?>" style="width: auto; height: auto;" >
                <?php include 'findduplicateproject.php'; ?>
            </div>    
    </div><input type="text" id="results" style="display: none" >
    </fieldset>
    <div data-dojo-type="dijit/Dialog" data-dojo-id="repEmptyTrash" id ='repEmptyTrash'  title="<?php echo TXT_CONFIRMATIONDELPROJET; ?>" data-dojo-props="iconClass:'dijitEditorIcon dijitEditorIconCut', showLabel: true"
        style="width: 520px;margin-left: 20px;   ">
       <table class="dijitDialogPaneContentArea" >
           <tr>
               <td><button type="submit" data-dojo-type="dijit/form/Button"   id="sOui" 
                           onclick="window.location.replace('<?php echo '/'.REPERTOIRE?>/empty_trash/<?php echo $lang;?>')">
                       <?php echo TXT_OUI; ?></button></td>
               <td><button type="submit" data-dojo-type="dijit/form/Button"  id="sNon" data-dojo-props="onClick:function(){rep.hide();}">
                       <?php echo TXT_NON; ?></button></td>
               <td><button data-dojo-type="dijit/form/Button" type="submit" data-dojo-props="onClick:function(){rep.hide();}" id="delete"><?php echo TXT_ANNULE; ?></button></td>
           </tr>
       </table>
   </div>
    
    <?php include 'html/footer.html'; ?>
</div>
</body>
</html>


    