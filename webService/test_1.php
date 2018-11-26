<?php

include_once '../class/Manager.php';
include_once '../outils/constantes.php';
$datas = $manager->getList("SELECT acronyme,idprojet,"
        . " idutilisateur_utilisateur as iddemandeur,"
        . " numero as numero_projet,dateprojet as date_demande,libellecentrale as centrale ,datedebutprojet,idstatutprojet as statut, dureeprojet as duree_projet_mois,"
        . " datedebutprojet + interval  '1 month' * dureeprojet as date_fin_projet, datestatutfini as date_statut_fini"
        . " FROM PROJET "
        . " LEFT JOIN concerne co on co.idprojet_projet=idprojet "
        . " LEFT JOIN creer c on c.idprojet_projet=idprojet "
        . " LEFT JOIN centrale on idcentrale_centrale =idcentrale "
        . " LEFT JOIN statutprojet on idstatutprojet_statutprojet =idstatutprojet "
        . " WHERE datedebutprojet BETWEEN '2017-01-01' AND '2018-12-31' AND confidentiel is not TRUE ");
for($i=0;$i<count($datas);$i++){
    foreach($datas[$i] as $key=>$value){
        if(is_int($key)){            
            unset($datas[$i][$key]);            
        }                
    }
}
$jsonData = json_encode($datas); 
?>
<script src="https://code.jquery.com/jquery-3.1.0.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
            var text1='<?php echo $jsonData; ?>';
            var jsonData = {
                field1: text1
            };
            $.ajax({
                type: "POST",
                url: "https://www.renatech.org/projet-dev/webService/processJson.php",
                data: JSON.stringify(jsonData),
                contentType: "application/json",
                dataType: "json",
                success: function (response) {
                    response.setRequestHeader("Content-Type", "application/json");
                    $("#response").html(JSON.stringify(response));                    
                },
                failure: function (error) {
                    $("#response").html(error);
                }
            });
    });
</script>
<?php
?>