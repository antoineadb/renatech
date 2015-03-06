<?php

include_once 'decide-lang.php';
include 'class/email.php';
include_once 'class/Manager.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include_once 'outils/toolBox.php';
include_once 'outils/constantes.php';
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
if (empty($nomresponsable)) {
    $nomResponsable = "";
} else {
    $nomResponsable = str_replace("''", "'", stripslashes($nomresponsable));
    $nomresponsable = TXT_RESPNAMEMAIL . ": " . htmlentities($nomResponsable, ENT_QUOTES, 'UTF-8') . "<br>";
}
if (empty($mailresponsable)) {
    $mailResponsable = "";
} else {
    $mailResponsable = TXT_RESPMAILMAIL . " : " . htmlentities($mailresponsable, ENT_QUOTES, 'UTF-8') . "<br>";
}
if (empty($autretutelle)) {
    $autreTutelle = "";
} else {
    $Autretutelle = str_replace("''", "'", stripslashes($autretutelle));
    $autreTutelle = TXT_AUTRETUTELLE . " : " . htmlentities($Autretutelle, ENT_QUOTES, 'UTF-8') . "<br>";
}
if (empty($autreemployeur)) {
    $autreEmployeur = "";
} else {
    $Autrenomemployeur = str_replace("''", "'", stripslashes($autreemployeur));
    $autreEmployeur = TXT_AUTREEMPLOYEUR . " : " . htmlentities($Autrenomemployeur, ENT_QUOTES, 'UTF-8') . "<br>";
}
if (empty($autrecodeunite)) {
    $autreCodeunite = "";
} else {
    $AutreCodeunite = str_replace("''", "'", stripslashes($autrecodeunite));
    $autreCodeunite = htmlentities(TXT_AUTRECODEUNITE, ENT_QUOTES, 'UTF-8') . " : " . htmlentities($AutreCodeunite, ENT_QUOTES, 'UTF-8') . "<br>";
}
if (empty($autrediscipline)) {
    $autreDiscipline = "";
} else {
    $AutreDiscipline = str_replace("''", "'", stripslashes($autrediscipline));
    $autreDiscipline = TXT_AUTREDISCIPLINE . " : " . htmlentities($AutreDiscipline, ENT_QUOTES, 'UTF-8') . "<br>";
}
if (empty($nomEmployeur)) {
    $nomemployeur = "";
} else {
    $idnomemployeur = substr($nomEmployeur, 2);
    if ($lang == 'fr') {
        $nomemployeur = $manager->getSingle2("select libelleemployeur from nomemployeur where idemployeur=?", $idnomemployeur);
    } elseif ($lang == 'en') {
        $nomemployeur = $manager->getSingle2("select libelleemployeuren from nomemployeur where idemployeur=?", $idnomemployeur);
    }
    $nomEmployeur = str_replace("''", "'", stripslashes($nomemployeur));
    $nomemployeur = TXT_NOMEMPLOYEUR . " : " . htmlentities($nomEmployeur, ENT_QUOTES, 'UTF-8') . "<br>";
}
if (empty($_POST['tutelle'])) {
    $Tutelle = "";
} else {
    $idtutelle = substr($_POST['tutelle'], 2);
    if ($lang == 'fr') {
        $tutelle = $manager->getSingle2("select libelletutelle from tutelle where idtutelle=?", $idtutelle);
    } elseif ($lang == 'en') {
        $tutelle = $manager->getSingle2("select libelletutelleen from tutelle where idtutelle=?", $idtutelle);
    }
    $Tutelle = TXT_TUTELLE . " : " . htmlentities($tutelle, ENT_QUOTES, 'UTF-8') . "<br>";
}
if (empty($codeunite) || $codeunite == 1) {
    $codeUnite = "";
} else {
    $Codeunite = str_replace("''", "'", stripslashes($codeunite));
    $codeUnite = htmlentities(TXT_CODEUNITE, ENT_QUOTES, 'UTF-8') . " : " . htmlentities($Codeunite, ENT_QUOTES, 'UTF-8') . "<br>";
    $infoCentrale = $manager->getList2("select libellecentrale,email1 from centrale where codeunite=?", $codeunite);
    $libelleCentrale = $infoCentrale[0]['libellecentrale'];
    $emailCentrale = $infoCentrale[0]['email1'];
}
if (empty($iddisciplineScientifique)) {
    $disciplinescientifique = "";
} else {
    if ($lang == 'fr') {
        $discipline = $manager->getSingle2("select libellediscipline from disciplinescientifique where iddiscipline=?", $iddisciplineScientifique);
    } elseif ($lang == 'en') {
        $discipline = $manager->getSingle2("select libelledisciplineen from disciplinescientifique where iddiscipline=?", $iddisciplineScientifique);
    }
    $disciplinescientifique = str_replace("''", "'", stripslashes($discipline));
    $Discipline = TXT_DISCIPLINESCIENTIFIQUE . " : " . htmlentities($disciplinescientifique, ENT_QUOTES, 'UTF-8') . "<br>";
}
if (isset($idqualitedemandeuraca_qualitedemandeuraca)) {
    if ($lang == 'fr') {
        $qualiteDemandeuraca = $manager->getSingle2("select libellequalitedemandeuraca from qualitedemandeuraca where idqualitedemandeuraca=?", $idqualitedemandeuraca_qualitedemandeuraca);
    } elseif ($lang == 'en') {
        $qualiteDemandeuraca = $manager->getSingle2("select libellequalitedemandeuracaen from qualitedemandeuraca where idqualitedemandeuraca=?", $idqualitedemandeuraca_qualitedemandeuraca);
    }
} else {
    $qualiteDemandeuraca = '';
}
if (empty($nomLabo)) {
    $nomlabo = "";
} else {
    $NomLabo = str_replace("''", "'", stripslashes($nomLabo));
    $nomlabo = TXT_NOMLABO . " : " . htmlentities($NomLabo, ENT_QUOTES, 'UTF-8') . '<br>';
}
$Adresse = str_replace("''", "'", stripslashes($adresse));
$Nom = str_replace("''", "'", stripslashes($nom));
$Prenom = str_replace("''", "'", stripslashes($prenom));
$Ville = str_replace("''", "'", stripslashes($ville));
$CP = str_replace("''", "'", stripslashes($cp));

if ($typeuser == "academique") {

    $body = "" . htmlentities(affiche('TXT_MRSMR'), ENT_QUOTES, 'UTF-8') . '<br><br>' .
            htmlentities(affiche('TXT_MAILCONTACTMILIEU'), ENT_QUOTES, 'UTF-8') . '<br><br>' . htmlentities(affiche('TXT_MAILCONTACTMILIEU1'), ENT_QUOTES, 'UTF-8') .
            '<br><br>' . htmlentities(affiche('TXT_RESEAURENATECH'), ENT_QUOTES, 'UTF-8') . '<br><br>' . "
" . '<div><u>' . htmlentities(TXT_INFORMATIONUSER, ENT_QUOTES, 'UTF-8') . '</u><br>' . "
" . TXT_PSEUDO . " : " . $pseudo . '<br>' . "
" . TXT_MAIL . " : " . $email . '<br>' . "
" . TXT_NOM . " : " . htmlentities(str_replace("''", "'", stripslashes($Nom)), ENT_QUOTES, 'UTF-8') . "<br>
" . htmlentities(TXT_PRENOM, ENT_QUOTES, 'UTF-8') . " : " . htmlentities($Prenom, ENT_QUOTES, 'UTF-8') . "<br>
" . TXT_ADRESSE . " : " . htmlentities($Adresse, ENT_QUOTES, 'UTF-8') . "<br>
" . TXT_CP . " : " . htmlentities($CP, ENT_QUOTES, 'UTF-8') . "<br>
" . TXT_VILLE . " : " . htmlentities($Ville, ENT_QUOTES, 'UTF-8') . "<br>
" . TXT_PAYS . " : " . stripslashes($pays) . "<br>
" . htmlentities(TXT_TELEPHONE, ENT_QUOTES, 'UTF-8') . " : " . $tel . " <br>
" . TXT_TYPEUTILISATEUR . " : " . htmlentities($_POST['typeuser'], ENT_QUOTES, 'UTF-8') . "<br>
" . $nomresponsable . "" . $mailResponsable . "$autreTutelle
" . $autreEmployeur . "" . $autreCodeunite . "" . $autreDiscipline . "
" . htmlentities(TXT_QUALITEDEMANDEURMAIL, ENT_QUOTES, 'UTF-8') . " : " . htmlentities($qualiteDemandeuraca, ENT_QUOTES, 'UTF-8') . "<br>
" . $nomemployeur . "" . $Tutelle . $codeUnite . "
" . $nomlabo . "" . $Discipline . "<br>
" . "<br><a href='https://www.renatech.org/projet'>" . TXT_RETOUR . "</a></div><br><br>
    <div>" . htmlentities(stripslashes(str_replace("''","'",affiche('TXT_NB'))), ENT_QUOTES, 'UTF-8') . "</div><br>" . htmlentities(stripslashes(str_replace("''","'",affiche('TXT_NB1'))), ENT_QUOTES, 'UTF-8') . "<div><br>" .
            htmlentities(affiche('TXT_DONOTREPLY'), ENT_QUOTES, 'UTF-8') . "</div>";
    "
";
} else {
    $typeEntreprise = $manager->getSingle2("select libelletypeentreprise from typeentreprise where idtypeentreprise =?", substr($_POST['typeEntreprise'], 2));
    $secteurActivite = $manager->getSingle2("select libellesecteuractivite from secteuractivite where idsecteuractivite= ?", substr($_POST['secteurActivite'], 2) . "");
    $body = "" . htmlentities(affiche('TXT_MRSMR'), ENT_QUOTES, 'UTF-8') . '<br><br>' . htmlentities(affiche('TXT_MAILCONTACTMILIEU'), ENT_QUOTES, 'UTF-8') . '<br>' . htmlentities(affiche('TXT_RESEAURENATECH'), ENT_QUOTES, 'UTF-8') . '<br><br>' . "
" . '<div><u>' . htmlentities(TXT_INFORMATIONUSER, ENT_QUOTES, 'UTF-8') . '</u><br>' . "
" . TXT_PSEUDO . " : " . $pseudo . '<br>' . "
" . TXT_MAIL . " : " . $email . '<br>' . "
" . TXT_NOM . " : " . htmlentities($Nom, ENT_QUOTES, 'UTF-8') . "<br>
" . htmlentities(TXT_PRENOM, ENT_QUOTES, 'UTF-8') . " : " . htmlentities($Prenom, ENT_QUOTES, 'UTF-8') . "<br>
" . TXT_ADRESSE . " : " . htmlentities($Adresse, ENT_QUOTES, 'UTF-8') . "<br>
" . TXT_CP . " : " . $cp . "<br>
" . TXT_VILLE . " : " . htmlentities($Ville, ENT_QUOTES, 'UTF-8') . "<br>
" . TXT_PAYS . " : " . $pays . "<br>
" . htmlentities(TXT_TELEPHONE, ENT_QUOTES, 'UTF-8') . " : " . $tel . " <br>
" . TXT_TYPEUTILISATEUR . " : " . htmlentities($_POST['typeuser'], ENT_QUOTES, 'UTF-8') . "<br>
" . TXT_TYPEENTREPRISE . " : " . htmlentities($typeEntreprise, ENT_QUOTES, 'UTF-8') . "<br>
" . TXT_NOMENTREPRISE . " : " . htmlentities(stripslashes($_POST['nomEntreprise']), ENT_QUOTES, 'UTF-8') . "<br>
" . utf8_decode(TXT_SECTEURACTIVITE) . " : " . htmlentities(stripslashes($secteurActivite), ENT_QUOTES, 'UTF-8') . "<br>
" . $nomresponsable . "" . $mailResponsable . "<br>
" . "<br><a href='https://www.renatech.org/projet'>" . TXT_RETOUR . "</a></div>";
}
$sujet = TXT_CONFIRMINSCRIPT . ' :';

$arraymailresponsable = array();
if (!empty($mailresponsable)) {
    array_push($arraymailresponsable, $mailresponsable);
}
$arraymail = array();
array_push($arraymail, $email);
//envoi de l'email au responsable si il existe
if (!empty($arraymailresponsable)) {
    $sujetresponsable = utf8_decode(stripslashes(str_replace("''","'",affiche('TXT_RESPONSABLESUJETEMAIL')))) . '' . utf8_decode($nom) . ' ' . utf8_decode($prenom);
    $bodyResponsable = stripslashes(str_replace("''","'",htmlentities(affiche('TXT_MRSMR'), ENT_QUOTES, 'UTF-8'))) .
            '<br><br>' . htmlentities(stripslashes(str_replace("''","'",affiche('TXT_RESPONSABLEBODYTEMAIL1'))), ENT_QUOTES, 'UTF-8') . 
            ' ' .htmlentities(stripslashes(str_replace("''","'",$nom)), ENT_QUOTES, 'UTF-8') . ' '
            . htmlentities(stripslashes(str_replace("''","'",affiche('TXT_RESPONSABLEBODYTEMAIL2'))), ENT_QUOTES, 'UTF-8') .
            '<br><br>' .htmlentities(stripslashes(str_replace("''","'",affiche('TXT_RESPONSABLEBODYTEMAIL3'))), ENT_QUOTES, 'UTF-8') . 
            ' ' . htmlentities(stripslashes(str_replace("''","'",affiche('TXT_RESPONSABLEBODYTEMAIL4'))), ENT_QUOTES, 'UTF-8') .
                '<br><br>' . htmlentities(stripslashes(str_replace("''","'",affiche('TXT_RESPONSABLEBODYTEMAIL5'))), ENT_QUOTES, 'UTF-8') . 
            ' ' . htmlentities(stripslashes(str_replace("''","'",$nom)), ENT_QUOTES, 'UTF-8') . 
            htmlentities(stripslashes(str_replace("''","'",affiche('TXT_RESPONSABLEBODYTEMAIL6'))), ENT_QUOTES, 'UTF-8') .
            '<br><br>' . htmlentities(stripslashes(str_replace("''","'",affiche('TXT_INFOQUESTION'))), ENT_QUOTES, 'UTF-8') .
            '<br><br>' . htmlentities(stripslashes(str_replace("''","'",affiche('TXT_SINCERESALUTATION'))), ENT_QUOTES, 'UTF-8') .
            '<br><br>' . htmlentities(stripslashes(str_replace("''","'",affiche('TXT_RESPONSABLEBODYTEMAIL9'))), ENT_QUOTES, 'UTF-8');
    if ($codeUnite != "") {
        $bodyResponsable1 = '<br><br>' .  htmlentities(stripslashes(str_replace("''","'",affiche('TXT_RESPONSABLEBODYTEMAIL10'))), ENT_QUOTES, 'UTF-8') . '<br>' . $libelleCentrale . ' - ' . $emailCentrale . '<br><br>';
    } else {
        $bodyResponsable1 = '<br><br>';
    }
    $bodyResponsable2 = "<a href='https://www.renatech.org/projet'>" . TXT_RETOUR . '</a>' . '<br>' . htmlentities(affiche('TXT_DONOTREPLY'), ENT_QUOTES, 'UTF-8');
    sendEmail($bodyResponsable . $bodyResponsable1 . $bodyResponsable2, $sujetresponsable, $mailresponsable);
}

sendEmail($body, $sujet, $email);

