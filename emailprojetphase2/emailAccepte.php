<?php

$body = htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_MRSMR'))), ENT_QUOTES, 'UTF-8') . '<br><br>' . (htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_BODYEMAILPHASE200'))), ENT_QUOTES, 'UTF-8')) . '<br>' . htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_BODYEMAILPHASE221'))), ENT_QUOTES, 'UTF-8') . ' ' .
        "<a href='https://www.renatech.org/projet'>" . affiche('TXT_BODYEMAILPHASE220') . "</a>" . '<br><br>' . htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_BODYEMAILPHASE230'))), ENT_QUOTES, 'UTF-8') . '<br><br>' .
        htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_RAPPELINSERTLOGO0'))), ENT_QUOTES, 'UTF-8') . '<br><br>' . htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_SINCERESALUTATION'))), ENT_QUOTES, 'UTF-8') . '<br><br>' . htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_RESEAURENATECH'))), ENT_QUOTES, 'UTF-8') . '<br><br>' .
        htmlentities(affiche('TXT_RESPONSABLEBODYTEMAIL10'), ENT_QUOTES, 'UTF-8') . ' ' . htmlentities($centrale, ENT_QUOTES, 'UTF-8') . '<br>' . $emailCentrale . '<br><br>' .
        '<a href="https://www.renatech.org/projet">' . htmlentities(TXT_RETOUR, ENT_QUOTES, 'UTF-8') . '<a>' . '<br><br>      ' .
        htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_DONOTREPLY'))), ENT_QUOTES, 'UTF-8') . '<br><br>';
envoieEmail($body, $sujet, $maildemandeur, $mailCC); //envoie de l'email au responsable centrale et au copiste 