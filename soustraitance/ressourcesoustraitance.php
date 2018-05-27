<script> require(["dojo/parser", "dijit/form/Select"]);</script>					
<fieldset class="phase2" style="width:950px; ">
    <legend>
        <?php echo TXT_CHOIXRESSOURCES . ':'; ?><a class="infoBulle" href="#">&nbsp;<img src='<?php echo '/'.REPERTOIRE; ?>/styles/img/help.gif' height='13px' width='13px'/>
            <span style="text-align: left;padding:10px;width: 300px;border-radius:5px" >
                <?php //MISE EN CACHE DES AIDES
                            if(!$aiderp=$Cache->read('aiderp')){
                                $aiderp=affiche('TXT_AIDERESSOURCES') ;
                                $Cache->write('aiderp',$aiderp);
                            }?><?= $aiderp; ?>
                </span></a>
        <?php echo ':'; ?>
    </legend>
    <div id='chck'>
        <?php //MISE EN CACHE DES AIDES
            if(!$aideintegration=$Cache->read('aideintegration')){
                $aideintegration=affiche('TXT_AIDEINTERGRATION') ;
                $Cache->write('aideintegration',$aideintegration);
            }
            if(!$aidecaracterisation=$Cache->read('aidecaracterisation')){
                $aidecaracterisation=affiche('TXT_AIDECHARACTERISATIONMETROLOGY') ;
                $Cache->write('aidecaracterisation',$aidecaracterisation);
            }
            if(!$aideetching=$Cache->read('aideetching')){
                $aideetching=affiche('TXT_AIDEETCHING') ;
                $Cache->write('aideetching',$aideetching);
            }
            if(!$aidedeposition=$Cache->read('aidedeposition')){
                $aidedeposition=affiche('TXT_AIDEDEPOSITION') ;
                $Cache->write('aidedeposition',$aidedeposition);
            }
            if(!$aidematerialgrowth=$Cache->read('aidematerialgrowth')){
                $aidematerialgrowth=affiche('TXT_AIDEMATERIALGROWTH') ;
                $Cache->write('aidematerialgrowth',$aidematerialgrowth);
            }
            if(!$aidelithography=$Cache->read('aidelithography')){
                $aidelithography=affiche('TXT_AIDELITHOGRAPHY') ;
                $Cache->write('aidelithography',$aidelithography);
            }
            
            ?>
        <?php
        if ($lang == 'fr') {
            $row = $manager->getList("SELECT idressource,libelleressource FROM ressource where  masqueressource!=TRUE order by idressource asc");
            $nbrow = count($row);
            for ($i = 0; $i < $nbrow; $i++) {
                if ($row[$i]['libelleressource'] == TXT_INTEGRATION) {
                    $aide = $aideintegration;
                } elseif ($row[$i]['libelleressource'] == TXT_CHARACTERISATIONMETROLOGY) {
                    $aide = $aidecaracterisation;
                } elseif ($row[$i]['libelleressource'] == TXT_GRAVURE) {
                    $aide = $aideetching;
                } elseif ($row[$i]['libelleressource'] == TXT_DEPOT) {
                    $aide = $aidedeposition;
                } elseif ($row[$i]['libelleressource'] == TXT_CROISSANCE) {
                    $aide = $aidematerialgrowth;
                } elseif ($row[$i]['libelleressource'] == TXT_LITHOGRAPHY) {
                    $aide = $aidelithography;
                } else {
                    $aide = '';
                }
                
                echo "<input  class='opt' type='checkbox' data-dojo-type='dijit/form/CheckBox' id='" . 're' . $row[$i]['idressource'] . "' name='ressource[]' value='" . $row[$i]['libelleressource'] . "' readonly='true' />
                <label for ='" . $row[$i]['libelleressource'] . "' class='opt' > " . str_replace("''", "'", $row[$i]['libelleressource']) . "
                <a class='infoBulle' href='#'>&nbsp;<img src='/".REPERTOIRE."/styles/img/help.gif' height='13px' width='13px' /><span style='width: 250px;border-radius:5px;padding:10px;'>" . $aide . "</span></a></label><br>";
                
            }
        } elseif ($lang == 'en') {
            $row = $manager->getList("SELECT idressource,libelleressourceen FROM ressource where  masqueressource!=TRUE order by idressource asc");
            $nbrow = count($row);
            for ($i = 0; $i < $nbrow; $i++) {
                if ($row[$i]['libelleressourceen'] == TXT_INTEGRATION) {
                    $aide = $aideintegration;
                } elseif ($row[$i]['libelleressourceen'] == TXT_CHARACTERISATIONMETROLOGY) {
                    $aide = $aidecaracterisation;
                } elseif ($row[$i]['libelleressourceen'] == TXT_GRAVURE) {
                    $aide = $aideetching;
                } elseif ($row[$i]['libelleressourceen'] == TXT_DEPOT) {
                    $aide = $aidedeposition;
                } elseif ($row[$i]['libelleressourceen'] == TXT_CROISSANCE) {
                    $aide = $aidematerialgrowth;
                } elseif ($row[$i]['libelleressourceen'] == TXT_LITHOGRAPHY) {
                    $aide = $aidelithography;
                } else {
                    $aide = '';
                }
                echo "<input  class='opt' type='checkbox' data-dojo-type='dijit/form/CheckBox' id='" . 're' . $row[$i]['idressource'] . "' name='ressource[]' value='" . $row[$i]['libelleressourceen'] . "' readonly='true' />
                <label for " . $row[$i]['libelleressourceen'] . " class='opt' > " . str_replace("''", "'", $row[$i]['libelleressourceen']) . "
                <a class='infoBulle' href='#'>&nbsp;<img src='/".REPERTOIRE."/styles/img/help.gif' height='13px' width='13px'/><span style='width: 250px;border-radius:5px;padding:10px;'>" . $aide . "</span></a></label><br>";
            }
        }
        ?>
    </div>
</fieldset>		
