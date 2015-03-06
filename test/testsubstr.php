<?php

$string = '< Résumé (compréhensible par un non expert)';
if (substr($string, 0, 1) == "< ") {
    echo substr($string, 1);
} else {
    echo $string;
}
        