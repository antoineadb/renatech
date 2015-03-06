<tr>
    <td valign="middle" style="text-align: left;font-size: 1.2em;height:30px"><div><?php echo TXT_NOMEMPLOYEUR; ?></div></td><td></td>
    <td>
        <?php
        $rowEmployeur = $manager->getListbyArray("SELECT  libelleemployeur,libelleemployeuren,idemployeur FROM nomemployeur where idemployeur!=? and libelleemployeur!=? order by idemployeur asc;", array($idemployeur,'Autres'));
        $nbrowEmployeur = count($rowEmployeur);
        $idautre = 'ne' . IDAUTREEMPLOYEUR;
        $idnomemployeur = 'ne' . $idemployeur;
        if ($lang == 'fr') {
            $nomEmployeur = removeDoubleQuote($manager->getSingle2("SELECT  libelleemployeur FROM nomemployeur where idemployeur=?", $idemployeur));
        } elseif ($lang == 'en') {
            $nomEmployeur = removeDoubleQuote($manager->getSingle2("SELECT  libelleemployeuren FROM nomemployeur where idemployeur=?", $idemployeur));
        }
        ?>
        <select name="nomEmployeur" id="nomEmployeur" data-dojo-type="dijit/form/Select"  style="text-align: left;font-size: 1.2em;height:30px;width:340px;"                    
                onchange="if (this.value === '<?php echo $idautre; ?>') {
                            afficheAutreAcademique('tr_autreemployeur','libelleautreemployeur','libelleautreemployeur1')
                        } else {
                            masqueAutreAcademique('tr_autreemployeur','libelleautreemployeur','libelleautreemployeur1')
                        }">                    
            <option value="<?php
            if (!empty($idnomemployeur)) {
                echo $idnomemployeur;
            }
            ?>" ><?php echo $nomEmployeur; ?></option>
                    <?php
                    if ($idrowemployeur != $rowEmployeur[0]['idemployeur']) {
                        if (empty($rowEmployeur[0]['idemployeur'])) {
                            echo "<option value='ne0'>" . TXT_SELECTEMPLOYEUR . "</option>";
                        }
                    }
                    if ($lang == 'fr') {
                        for ($i = 0; $i < $nbrowEmployeur; $i++) {
                            echo "<option value='" . 'ne' . $rowEmployeur[$i]['idemployeur'] . "'>" . removeDoubleQuote($rowEmployeur[$i]['libelleemployeur']) . "</option>";
                        }
                    } elseif ($lang == 'en') {
                        for ($i = 0; $i < count($rowEmployeur); $i++) {
                            echo '<option value="' . 'ne' . ($rowEmployeur[$i]['idemployeur']) . '">' . removeDoubleQuote($rowEmployeur[$i]['libelleemployeuren']) . '</option>';
                        }
                    }
                    if ($idnomemployeur != $idautre) {
                        echo "<option value=" . $idautre . ">" . TXT_AUTRES . "</option>";
                    }
                    ?>
        </select>
    </td>
</tr>