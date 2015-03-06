<?php
$array = array(
    '2' => 'ANR',
    '5' => 'INDUSTRIEL',
    '6' => 'EUROPE',
    '12'=>'toto',
    '25'=>'ttiti'
    
    );

// Cette boucle affiche toutes les clés
// dont la valeur vaut "apple"


echo '<pre>';print_r(array_slice($array, 0));