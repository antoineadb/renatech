<?php
include_once '../class/Manager.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include_once '../decide-lang.php';
if(isset($_GET['centrale'])&&!empty($_GET['centrale'])){        
    $idcentrale=$manager->getSingle2("select idcentrale from centrale where libellecentrale=?",$_GET['centrale']);
    $arrayCentraleRegion = $manager->getListbyArray("SELECT r.libelleregion, cp.libellecentraleproximite, cp.idcentraleproximite FROM centraleproximite cp,region r,centraleregion cr WHERE cp.idregion = r.idregion "
            . "AND cr.idregion = r.idregion and cr.idcentrale=? and cp.masquecentraleproximite!=?",array($idcentrale,TRUE));
    for ($i = 0; $i < count($arrayCentraleRegion); $i++) {
        $nomcentraleproximite = $arrayCentraleRegion[$i]['libellecentraleproximite'];
        $idcentraleproximite = $arrayCentraleRegion[$i]['idcentraleproximite'];
        echo "<input  class='dijit dijitReset dijitInline dijitCheckBox centPrx'  type='checkbox' data-dojo-type='dijit/form/CheckBox' class='centraleP' id='" .'cp'. $idcentraleproximite . "' " . "name='centrale_Proximite[]' value='" . 'cp'.$idcentraleproximite . "'  >
        <label for = '" .'cp'. $idcentraleproximite . "' class='centraleP' > ".$nomcentraleproximite."</label>";
        echo '<br>';
    }
    echo "<p><u>".TXT_CENTRALESPECIFIQUES."</u></p>";
        $rowspecifique = $manager->getListbyArray("SELECT cp.libellecentraleproximite, cp.idcentraleproximite FROM region r,centraleproximite cp WHERE cp.idregion = r.idregion  "
                . "AND r.libelleregion =? and cp.masquecentraleproximite!=? order by idcentraleproximite asc",array(TXT_CENTRALESPECIFIQUES,TRUE));
        for ($i = 0; $i < count($rowspecifique); $i++) {
            $nomcentraleproximite = $rowspecifique[$i]['libellecentraleproximite'];
            $idcentraleproximite = $rowspecifique[$i]['idcentraleproximite'];
            echo "<input class='dijit dijitReset dijitInline dijitCheckBox centPrx'   type='checkbox' data-dojo-type='dijit/form/CheckBox' id='" .'cp'. $idcentraleproximite . "' " . "name='centrale_Proximite[]' class='centraleP'  value='" . 'cp'.$idcentraleproximite . "'  >
            <label for = '" .'cp'. $idcentraleproximite . "' class='opt' class='centraleP' > ".$nomcentraleproximite."</label>";
            echo '<br>';
        }
    
    
}else {
    echo 'FALSE';    
}

