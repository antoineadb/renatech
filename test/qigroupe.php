<?php

/* 
 C'est bien connu, le QI d'un groupe est égal au QI de son membre le moins intelligent divisé par le nombre de personnes du groupe (arrondi à l'entier inférieur).
 */
/*

/*Ouvre le fichier et retourne un tableau contenant une ligne par élément*/
$lines = file("input3.txt");
$arraycontent = array();
/*foreach ($lines as $lineNumber => $lineContent){
    $arraycontent[$lineNumber]=$lineContent;
}*/
foreach ($lines as $lineNumber => $lineContent){
    array_push($arraycontent, $lineContent);
}

unset($arraycontent[array_search(0, $arraycontent)]);
//suppression de la 1er valeur du tableau
echo '<pre>';print_r($arraycontent);echo '</pre>';
echo min($arraycontent);
