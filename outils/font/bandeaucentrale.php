<?php
include_once 'class/Manager.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
?>
<script type="text/javascript" src="<?php echo '/'.REPERTOIRE; ?>/js/src.js"></script>
<?php
$arrayfemto = $manager->getList2("select adressesitewebcentrale,adresselogcentrale from sitewebapplication where refsiteweb=?", 'FEMTO');
$femto = $arrayfemto[0]['adressesitewebcentrale'];
$logofemto = $arrayfemto[0]['adresselogcentrale'];
$arrayief = $manager->getList2("select adressesitewebcentrale,adresselogcentrale from sitewebapplication where refsiteweb=?", 'IEF');
$ief = $arrayief[0]['adressesitewebcentrale'];
$logoief = $arrayief[0]['adresselogcentrale'];
$arrayiemn = $manager->getList2("select adressesitewebcentrale,adresselogcentrale from sitewebapplication where refsiteweb=?", 'IEMN');
$iemn = $arrayiemn[0]['adressesitewebcentrale'];
$logoiemn = $arrayiemn[0]['adresselogcentrale'];
$arraylaas = $manager->getList2("select adressesitewebcentrale,adresselogcentrale from sitewebapplication where refsiteweb=?", 'LAAS');
$laas = $arraylaas[0]['adressesitewebcentrale'];
$logolaas = $arraylaas[0]['adresselogcentrale'];
$arraylpn = $manager->getList2("select adressesitewebcentrale,adresselogcentrale from sitewebapplication where refsiteweb=?", 'LPN');
$lpn = $arraylpn[0]['adressesitewebcentrale'];
$logolpn = $arraylpn[0]['adresselogcentrale'];
$arrayltm = $manager->getList2("select adressesitewebcentrale,adresselogcentrale from sitewebapplication where refsiteweb=?", 'LTM');
$ltm = $arrayltm[0]['adressesitewebcentrale'];
$logoltm = $arrayltm[0]['adresselogcentrale'];
?>
<div class="outerbar">
    <div dojoType="dojox.widget.FisheyeList"  itemWidth="90" itemHeight="33"  itemMaxWidth="180"
         itemMaxHeight="80" orientation="horizontal" effectUnits="2" itemPadding="10" attachEdge="top" labelEdge="bottom" id="fisheye1">
        <div dojoType="dojox.widget.FisheyeListItem" label="<?php echo $femto; ?>		"
             onclick="window.open('<?php echo $femto; ?>')" id="femto"
             iconSrc="<?php echo '/'.REPERTOIRE.'/'.$logofemto; ?>"></div>
        <div dojoType="dojox.widget.FisheyeListItem" onclick="window.open('<?php echo $ief; ?>')" label="<?php echo $ief; ?>" id="ief"
             iconSrc="<?php echo '/'.REPERTOIRE.'/'. $logoief; ?>"></div>
        <div dojoType="dojox.widget.FisheyeListItem" onclick="window.open('<?php echo $iemn; ?>')" id="iemn"
             label="<?php echo $iemn; ?>" iconSrc="<?php echo '/'.REPERTOIRE.'/'. $logoiemn; ?>"></div>
        <div dojoType="dojox.widget.FisheyeListItem" onclick="window.open('<?php echo $laas; ?>')" label ="<?php echo $laas; ?>" id="laas"
             iconSrc="<?php echo '/'.REPERTOIRE.'/'. $logolaas; ?>"></div>
        <div dojoType="dojox.widget.FisheyeListItem" onclick="window.open('<?php echo $lpn; ?>')" id="lpn"
             label="<?php echo  $lpn; ?>" iconSrc="<?php echo '/'.REPERTOIRE.'/'. $logolpn; ?>"></div>
        <div dojoType="dojox.widget.FisheyeListItem" onclick="window.open('<?php echo $ltm; ?>')" label="<?php echo $ltm; ?>" id=""
             iconSrc="<?php echo '/'.REPERTOIRE.'/'. $logoltm; ?>"></div>
    </div>
</div>