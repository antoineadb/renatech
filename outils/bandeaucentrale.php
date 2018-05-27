<?php
include_once 'class/Manager.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
$arrayLibelleCentrale = $manager->getListbyArray("select libellecentrale from centrale where masquecentrale!=? and idcentrale!=? order by libellecentrale asc", array(TRUE, IDCENTRALEAUTRE));
?>
<script type="text/javascript" src="<?php echo '/' . REPERTOIRE; ?>/js/src.js"></script>
<div class="outerbar">    
    <div dojoType="dojox.widget.FisheyeList"   itemWidth="119"  itemHeight="60" itemMaxWidth="200" itemMaxHeight="90" orientation="horizontal" effectUnits="2" itemPadding="10" attachEdge="top" labelEdge="bottom" id="fisheye1">
    <?php        
       foreach ($arrayLibelleCentrale as $key => $libellecentrale) {
            $arrayCentrale = $arrayCentrale = $manager->getList2("select adressesitewebcentrale,adresselogcentrale from sitewebapplication where refsiteweb=?", $libellecentrale[0]);
            if (!empty($arrayCentrale[0]['adressesitewebcentrale']) && !empty($arrayCentrale[0]['adresselogcentrale'])) {
                $centrale = $arrayCentrale[0]['adressesitewebcentrale'];
                $logocentrale = $arrayCentrale[0]['adresselogcentrale'];            
            ?>
               <div dojoType="dojox.widget.FisheyeListItem" label="<?php echo $centrale; ?> " onclick="window.open('<?php echo $centrale; ?>')" id="<?php echo $libellecentrale[0] ?>" iconSrc="<?php echo '/' . REPERTOIRE . '/' . $logocentrale; ?>"></div>
            
                  <?php  }
        }
        ?>
    </div>
</div>
<?php BD::deconnecter();  ?>