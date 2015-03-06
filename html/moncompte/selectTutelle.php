<tr>
    <td valign="middle" style="text-align: left;font-size: 1.2em;height:30px"><div><?php echo TXT_TUTELLE; ?></div></td><td></td>
    <td>
        <?php
        $rowtutelle = $manager->getListbyArray("SELECT  idtutelle,libelletutelle,libelletutelleen FROM tutelle where masquetutelle !=? and idtutelle!=?  order by idtutelle asc;", array('true', $idtutelle));
        $idautre = 'tu' . IDAUTRETUTELLE;
        ?>
        <select name="tutelle" id="tutelle" data-dojo-type="dijit/form/Select"  style="text-align: left;font-size: 1.2em;height:30px;width:340px;"  
                onchange="if (this.value === '<?php echo $idautre; ?>') {
                            afficheAutreAcademique('tr_autretutelle','autreTutelle1','autreTutelle1')
                        } else {
                            masqueAutreAcademique('tr_autretutelle','autreTutelle','autreTutelle1')
                        }">
            <option value="<?php
            if (!empty($idtutelle)) {
                echo 'tu' . $idtutelle;
            }
            ?>" ><?php
                        if ($lang == 'fr') {
                            echo $rowTutelle[0]['libelletutelle'];
                        } else {
                            echo $rowTutelle[0]['libelletutelleen'];
                        }
                        ?></option>   
            <?php
            if (empty($idtutelle)) {
                echo "<option value='tu0'>" . TXT_SELECTTUTELLE . "</option>";
            }
            if ($lang == 'fr') {
                for ($i = 0; $i < count($rowtutelle); $i++) {
                    echo "<option value='" . 'tu' . $rowtutelle[$i]['idtutelle'] . "'>" . removeDoubleQuote($rowtutelle[$i]['libelletutelle']) . "</option>";
                }
            } elseif ($lang == 'en') {
                for ($i = 0; $i < count($rowtutelle); $i++) {
                    echo '<option value="' . 'tu' . ($rowtutelle[$i]['idtutelle']) . '">' . removeDoubleQuote($rowtutelle[$i]['libelletutelleen']) . '</option>';
                }
            }
            ?>
        </select>
    </td>
</tr>