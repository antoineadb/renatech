<?php

$body = htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_MRSMR9'))), ENT_QUOTES, 'UTF-8') . '<br><br>' . htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_BODYEMAILENATTENTEPHASE01'))), ENT_QUOTES, 'UTF-8') . '<br>' . $commentaire . '<br>' .
        htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_BODYEMAILENATTENTEPHASE02'))), ENT_QUOTES, 'UTF-8') . '<br><br>' . htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_SINCERESALUTATION'))), ENT_QUOTES, 'UTF-8') . '<br><br>' . htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_RESEAURENATECH'))), ENT_QUOTES, 'UTF-8') . '<br><br>' .
        htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_SINCERESALUTATION'))), ENT_QUOTES, 'UTF-8') . '' . htmlentities($centrale, ENT_QUOTES, 'UTF-8') . ' <br> ' . $emailCentrale . '<br><br>' . htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_DONOTREPLY'))), ENT_QUOTES, 'UTF-8') . '<br><br>';
envoieEmail($body, $sujet, $maildemandeur, $mailCC); //envoie de l'email au responsable centrale et au copiste
header('Location: /' . REPERTOIRE . '/Waiting_project1/' . $lang . '/' . $idprojet . '/' . $numprojet . '/' . ENATTENTE);
exit();
