<?php
if(isset($arraypersonnecentrale[$i]['idqualitedemandeuraca'])){
    echo "<option value='" . 'libqualdemaca' . $arraypersonnecentrale[$i]['idqualitedemandeuraca'] . "'>" . $arraypersonnecentrale[$i]['libellequalitedemandeuraca'] . '</option>';
}else{
    echo "<option value='" . 'libqualdemaca0'. "'>" . TXT_SELECTQUALITE . '</option>';
}
for ($k = 0; $k < $nbrow; $k++) {
    if (isset($row[$k]['idqualitedemandeuraca'])) {
        if ($lang == 'fr') {
            echo "<option value='" . 'libqualdemaca' . $row[$k]['idqualitedemandeuraca'] . "'>" . $row[$k]['libellequalitedemandeuraca'] . "</option>";
        } else {
            echo "<option value='" . 'libqualdemaca' . $row[$k]['idqualitedemandeuraca'] . "'>" . $row[$k]['libellequalitedemandeuracaen'] . "</option>";
        }
    }
}
echo '</select></td></tr>';
