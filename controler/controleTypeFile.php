<?php
include_once '../outils/constantes.php';
if(isset($_POST['annee'])&&!empty($_POST['annee'])){
    $annee = $_POST['annee'];
}else{
    $annee = date('Y');
}
       
if(isset($_POST['typefile'])&& $_POST['typefile']=='excel'){
    header('Location: /' . REPERTOIRE . '/exportCsvNoDev.php?lang=' . $lang.'&annee='.$annee);
}elseif(isset($_POST['typefile'])&&$_POST['typefile']=='word'){
     header('Location: /' . REPERTOIRE . '/templates/exportWordNoDEv.php?lang=' . $lang.'&annee='.$annee);
}

