<?php
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
$autres = 'sf' . IDAUTRESOURCEFINANCEMENT;
?>
<fieldset id="identsf" style="border-color: #5D8BA2;width:952px; margin-left: 20px;padding-left: 10px;margin-top:20px">
    <legend>
        <?php echo ' ' . TXT_SOURCEFINANCEMENT . '*'; ?><a class="infoBulle" href="#">&nbsp;<img src='<?php echo '/' . REPERTOIRE; ?>/styles/img/help.gif' height="13px" width="13px"/>
            <span style="text-align: left;padding:10px;width: 700px;border-radius:5px" >
                <?php
                //MISE EN CACHE DES AIDES
                if (!$aidesf = $Cache->read('aidesf')) {
                    $aidesf = affiche('TXT_AIDESOURCEFINANCEMENT');
                    $Cache->write('aidesf', $aidesf);
                }
                ?><?= $aidesf; ?>
            </span></a>
    </legend>
    <div style='margin-left: 20px;padding-bottom: 10px;'><i><?php echo TXT_CADREINSTITUTIONNEL; ?></i></div>
        <?php if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.0') !== FALSE) { ?>
        <?php } else { ?>
        <div style="margin-top: -20px;margin-bottom: 10px">
            <?php } ?>
            <div class="sfg"><br />
            <?php
            $row = $manager->getList("SELECT distinct idsourcefinancement,libellesourcefinancement,libellesourcefinancementen FROM sourcefinancement where masquesourcefinancement!=TRUE order by idsourcefinancement asc");
            $nbrow = count($row);
            for ($i = 0; $i < $nbrow; $i++) {
                $idsourcefinancement = $row[$i]['idsourcefinancement'];
                $libellesourcefinancement = $row[$i]['libellesourcefinancement'];
                $libellesourcefinancementen = $row[$i]['libellesourcefinancementen'];
                ?>
                <div style="margin-left: 20px;margin-right: 5px;" name='<?php echo 'sf' . $idsourcefinancement; ?>' dojoType="dijit.form.CheckBox"  id="<?php echo 'sf' . $idsourcefinancement; ?>" 
                     class='<?php if ($libellesourcefinancement == TXT_AUTRES || $libellesourcefinancementen == TXT_AUTRES) {echo TXT_NATUREFINANCEMENT;} else {echo TXT_ACROSRCFINANCEMENT;}?>' >
                    <script type="dojo/method" event="onClick" args="evt">
                        if(this.checked && dojo.byId("<?php echo 'sf' . $idsourcefinancement; ?>").id	==='<?php echo $autres ?>'){
                            dijit.byId("acronymesourcesf<?php echo IDAUTRESOURCEFINANCEMENT; ?>").domNode.style.display='inline-block';
                        }
                        if( dijit.byId('<?php echo $autres; ?>').checked===false) {
                            dijit.byId("acronymesourcesf<?php echo IDAUTRESOURCEFINANCEMENT; ?>").domNode.style.display = 'none';
                        }
                        if (this.checked){
                            dijit.byId("acronymesourcesf<?php echo $idsourcefinancement; ?>").domNode.style.display = 'inline-block';
                        }else{
                            dijit.byId("acronymesourcesf<?php echo $idsourcefinancement; ?>").domNode.style.display = 'none';
                        }                        
                        dijit.byId('acronymesourcesf<?php echo $idsourcefinancement; ?>').set('placeHolder', dijit.byId('<?php echo 'sf' . $idsourcefinancement; ?>').class);
                    </script>
                </div>                
                <?php if ($lang == 'fr') {echo removeDoubleQuote($row[$i]['libellesourcefinancement']);} elseif ($lang == 'en') {echo removeDoubleQuote($row[$i]['libellesourcefinancementen']);}?>
                <?php if (!empty($idprojet)) {$acronSF = stripslashes(removeDoubleQuote($manager->getSinglebyArray("select acronymesource from projetsourcefinancement where idprojet_projet =? and idsourcefinancement_sourcefinancement =?", array($idprojet, $idsourcefinancement))));}?>
                <input id="acronymesourcesf<?php echo $idsourcefinancement; ?>" type="text" name="acronymesourcesf<?php echo $idsourcefinancement; ?>" autocomplete="on" data-dojo-type="dijit/form/ValidationTextBox"
                       placeholder="" style="width:310px;display: none;margin-left:20px" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" 
                       value="<?php if (!empty($acronSF)) {echo stripslashes(removeDoubleQuote($acronSF));}?>"
                       data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð%&µ° /’:0-9\042\'()_ ,.-]+'"  maxlength="500"><br />
        
                <?php
            }
            if (!empty($idprojet)) {//RECUPERE ET COCHE LES  SOURCE DE FINANCEMENTS
                $projetsource = $manager->getList2("select idsourcefinancement_sourcefinancement from projetsourcefinancement where idprojet_projet=?", $idprojet);
                $nbprojetsource = count($projetsource);
                for ($i = 0; $i < $nbprojetsource; $i++) {
                    if (!empty($projetsource[$i]['idsourcefinancement_sourcefinancement']) && $projetsource[$i]['idsourcefinancement_sourcefinancement'] !== IDAUTRESOURCEFINANCEMENT) {
                        echo "<script>dojo.byId('sf" . $projetsource[$i]['idsourcefinancement_sourcefinancement'] . "').setAttribute('checked', true);"
                        . "dojo.byId('acronymesourcesf" . $projetsource[$i]['idsourcefinancement_sourcefinancement'] . "').  style.display = 'inline-block';</script>";
                    } elseif ($projetsource[$i]['idsourcefinancement_sourcefinancement'] === IDAUTRESOURCEFINANCEMENT) {
                        echo "<script>dojo.byId('" . $autres . "').setAttribute('checked', true);"
                        . "dojo.byId('acronymesourcesf" . $projetsource[$i]['idsourcefinancement_sourcefinancement'] . "').  style.display = 'inline-block';</script>";
                    }
                }
            }
            BD::deconnecter();
            ?>
        </div>
    </div>
</fieldset>
