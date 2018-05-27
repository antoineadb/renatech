<tr>
    <td valign="middle" style="text-align: left;font-size: 1.2em;height:30px"><div><?php echo TXT_DISCIPLINESCIENTIFIQUE; ?> </div></td><td></td>
    <td>
        <?php
        $rowDiscipline = $manager->getListbyArray("SELECT  libelledisciplineen,libellediscipline,iddiscipline FROM disciplinescientifique where masquediscipline !=? and iddiscipline!=? "
                . "order by iddiscipline asc;", array('true', $iddiscipline));
        $idautre = 'di' . IDAUTREDISCIPLINE;
        ?>
        <select name="discipline" id="discipline" data-dojo-type="dijit/form/Select"  style="text-align: left;font-size: 1.2em;height:30px;width:340px;"  
                onchange="if (this.value === '<?php echo $idautre; ?>') {
                            afficheAutreAcademique('tr_autrediscipline','autrediscipline','autrediscipline1')
                        } else {
                            masqueAutreAcademique('tr_autrediscipline','autrediscipline','autrediscipline1')
                        }">
            <option value="<?php if (!empty($iddiscipline)) {
            echo 'di' . $iddiscipline;
        } ?>" ><?php if ($lang == 'fr') {
                echo $rowdiscipline[0]['libellediscipline'];
            } else {
                echo $rowdiscipline[0]['libelledisciplineen'];
            } ?></option>
            <?php
            if (empty($iddiscipline)) {
                echo "<option value='di0'>" . TXT_SELECTDISCIPLINE . "</option>";
            }
            if ($lang == 'fr') {
                for ($i = 0; $i < count($rowDiscipline); $i++) {
                    echo "<option value='" . 'di' . $rowDiscipline[$i]['iddiscipline'] . "'>" . removeDoubleQuote($rowDiscipline[$i]['libellediscipline']) . "</option>";
                }
            } elseif ($lang == 'en') {
                for ($i = 0; $i < count($rowDiscipline); $i++) {
                    echo '<option value="' . 'di' . ($rowDiscipline[$i]['iddiscipline']) . '">' . removeDoubleQuote($rowDiscipline[$i]['libelledisciplineen']) . '</option>';
                }
            }
            ?>
        </select>
    </td>
</tr>