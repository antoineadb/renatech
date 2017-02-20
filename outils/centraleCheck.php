<?php
include_once 'class/Manager.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
?>
<script> require(["dojo/parser", "dijit/form/Select"]);</script>
<fieldset id="ident">
    <legend>
        <?php echo ' ' . TXT_CHOIXCENTRALE . '*'; ?>
        <?php echo ':'; ?><a class="infoBulle" href="#">&nbsp;<img src='<?php echo '/'.REPERTOIRE; ?>/styles/img/help.gif' height="13px" width="13px"/><span style="width: 580px;border-radius: 5px">
                <?php echo affiche('TXT_AIDECENTRALE'); ?></span></a>
    </legend>
    <?php if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.0') !== FALSE) { ?>
        <div style="margin-top:0px;margin-bottom: 10px">
        <?php } else { ?>
            <div style="margin-top: -20px;margin-bottom: 10px">
            <?php } ?>
            <p style="font-size: 12px;font-style: italic;width: 520px"><?php echo TXT_CHOIXCENTRALE; ?></p>
            <div id='chck'>
                <?php
                $row = $manager->getList("SELECT libelleCentrale,villeCentrale FROM centrale where libellecentrale != 'Autres' and masquecentrale!=TRUE order by libelleCentrale asc");
                for ($i = 0; $i < count($row); $i++) {
                    $libellecentrale = $row[$i][0];
                    $villecentrale = $row[$i][1];
                    echo
                    "<input  class='opt'  type='checkbox' data-dojo-type='dijit/form/CheckBox' id='" . str_replace('-', '', $libellecentrale) . "' "
                    . "name='centrale[]' value='" . $libellecentrale . "'  >
                    <label for = '" . $libellecentrale . "' class='opt' > " . ($libellecentrale) . ' (<i>' . str_replace("''", "'", $villecentrale) . '</i>)' . "</label>";
                    echo '<br>';
                }
                BD::deconnecter();
                ?>
            </div>
        </div>
</fieldset>