<?php 
$chemin ='/'.REPERTOIRE.'/js/ajax.js';
?>
<script src='<?php echo $chemin; ?>'></script>
<tr>
    <td valign="middle" style="text-align: left;font-size: 1.2em;height:30px"><div><?php echo TXT_CODEUNITE; ?> </div></td><td></td>
    <td>
        <?php 
        if(!empty($idcodeunite)){
            $rowcodeunite = $manager->getListbyArray("SELECT  idcentrale,libellecentrale,codeunite FROM centrale where masquecentrale =? and codeunite is not null and idcentrale!=? order by idcentrale asc;", array('FALSE',$idcodeunite));
            $libellecodeunite = $manager->getSingle2('select codeunite from centrale where idcentrale=? ', $idcodeunite);        
        }else{
            $rowcodeunite = $manager->getListbyArray("SELECT  idcentrale,libellecentrale,codeunite FROM centrale where masquecentrale =? and codeunite is not null order by idcentrale asc;", array('FALSE'));
        }
        
        ?>
        <select name="codeunite" id="codeunite" data-dojo-type="dijit/form/Select"  style="text-align: left;font-size: 1.2em;height:30px;width:340px;"  
                onchange="afficheNomCentrale('/<?php echo REPERTOIRE; ?>/outils/affichecentrale.php?lang=<?php echo $lang; ?>&id=' + this.value + '')" >

            <?php
                if (!empty($idcodeunite)) {
                    if ($rowcodeunite[0]['idcentrale'] != $idcodeunite) {
                        echo "<option value='" . 'cu' . $idcodeunite . "'>" . removeDoubleQuote($libellecodeunite) . "</option>";
                    }
                    for ($i = 0; $i < count($rowcodeunite); $i++) {
                        echo "<option value='" . 'cu' . $rowcodeunite[$i]['idcentrale'] . "'>" . removeDoubleQuote($rowcodeunite[$i]['codeunite']) . "</option>";
                    }
                }elseif(empty($idcodeunite)&& $idautrecodeunite == null){
                    $rowcodeunite = $manager->getListbyArray("SELECT  idcentrale,libellecentrale,codeunite FROM centrale where masquecentrale =? and codeunite is not null order by idcentrale asc;", array('FALSE'));                    
                    echo "<option value='" . 'aucun' . "'>" . TXT_AUCUN . "</option>";
                    for ($i = 0; $i < count($rowcodeunite); $i++) {
                        echo "<option value='" . 'cu' . $rowcodeunite[$i]['idcentrale'] . "'>" . removeDoubleQuote($rowcodeunite[$i]['codeunite']) . "</option>";
                    }
                    echo "<option value='" . 'cu' . AUTRECENTRALE . "'>" . TXT_AUTRES . "</option>";
                }else{                    
                    echo "<option value='" . 'cu' . AUTRECENTRALE . "'>" . TXT_AUTRES . "</option>";
                    echo "<option value='" . 'aucun' . "'>" . TXT_AUCUN . "</option>";
                    for ($i = 0; $i < count($rowcodeunite); $i++) {                        
                        echo "<option value='" . 'cu' . $rowcodeunite[$i]['idcentrale'] . "'>" . removeDoubleQuote($rowcodeunite[$i]['codeunite']) . "</option>";
                    }
                /*}else{
                    $rowcodeunite = $manager->getListbyArray("SELECT  idcentrale,libellecentrale,codeunite FROM centrale where masquecentrale =? and codeunite is not null order by idcentrale asc;", array('FALSE'));                    
                    echo "<option value='" . 'aucun' . "'>" . TXT_AUCUN . "</option>";
                    for ($i = 0; $i < count($rowcodeunite); $i++) {
                        echo "<option value='" . 'cu' . $rowcodeunite[$i]['idcentrale'] . "'>" . removeDoubleQuote($rowcodeunite[$i]['codeunite']) . "</option>";
                    }*/
                }            
            //echo "<option value='" . 'cu' . AUTRECENTRALE . "'>" . TXT_AUTRES . "</option>";
            if (!empty($idcodeunite)&& $idcodeunite != IDAUTRECENTRALE) {
                echo "<option value='" . 'aucun' . "'>" . TXT_AUCUN . "</option>";
                echo "<option value='" . 'cu' . AUTRECENTRALE . "'>" . TXT_AUTRES . "</option>";
            }
            ?>
        </select>
    </td>
</tr> 