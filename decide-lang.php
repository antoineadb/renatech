<?php

if (isset($_GET['lang'])) {
    $lang = $_GET['lang'];
} elseif(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {// si aucune langue n'est déclarée on tente de reconnaitre la langue par défaut du navigateur
    $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
}else{
    $lang ='fr';
}

if ($lang == 'fr') {
    include_once('lang/fr-lang.php');
} else {
    include_once('lang/en-lang.php');
}