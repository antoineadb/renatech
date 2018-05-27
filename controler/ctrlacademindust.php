<?php
if(isset($_POST['typeuser'])){
    if ($_POST['typeuser']=='academique') {
        include '..\contactAcademique.php';
    }elseif ($_POST['typeuser']=='industriel'){
        include '..\contactIndustriel.php';;
    }
}
		