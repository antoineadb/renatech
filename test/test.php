<?php
function nomFichierValide($chaineNonValide)
{
  $chaineNonValide0 = preg_replace('`\s+`', '_', trim($chaineNonValide));
  $chaineNonValide1 = str_replace("'", "_", $chaineNonValide0);
  $chaineNonValide2 = preg_replace('`_+`', '_', trim($chaineNonValide1));
  $chaineValide=strtr($chaineNonValide2,
"ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ",
                        "aaaaaaaaaaaaooooooooooooeeeeeeeecciiiiiiiiuuuuuuuuynn")
;
  return ($chaineValide);
}

$string = 'le nom du fichier.pdf';
echo 'avant '.$string.'<br>';
echo 'après '. nomFichierValide($string);