<?php
include_once 'decide-lang.php';
include 'class/email.php';
include_once 'class/Manager.php';
include_once 'outils/constantes.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include_once 'outils/toolBox.php';
if (!empty($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
if (!empty($_GET['idprojet'])) {
    $idprojet = $_GET['idprojet'];
} elseif (!empty($_SESSION['idprojet'])) {
    $idprojet = $_SESSION['idprojet'];
}
$numprojet = $manager->getSingle2("select numero from projet where idprojet=?",$idprojet);
//récupération des adresses mail et nom des centrale
//CENTRALE SOURCE      
//TRAITEMENT DU LIBELLE DANS L'EMAIL

if (!empty($_SESSION['idcentrale'])) {
    $idcentrale = $_SESSION['idcentrale'];
    $infocentrale = $manager->getList2("select libellecentrale,email1,email2,email3,email4,email5 from centrale where idcentrale=?", $_SESSION['idcentrale']);
    $nbinfocentrale=count($infocentrale);				
    $centrale = $infocentrale[0]['libellecentrale'];
    $emailCentrale = '';
    for ($i = 0; $i < count($infocentrale); $i++) {
        for ($j = 0; $j < 4; $j++) {
            if (!empty($infocentrale[$i]['email' . $j])) {
                $emailCentrale .=$infocentrale[$i]['email' . $j] . '<br>';
            }
        }
    }
} elseif (!empty($_POST['centraletrs'])) {
    $idcentrale = (int) substr($_POST['centraletrs'], -1);
    $infocentrale = $manager->getList2("select libellecentrale,email1,email2,email3,email4,email5 from centrale where idcentrale=?", $idcentrale);
    $centrale = $infocentrale[0]['libellecentrale'];
    $emailCentrale = '';
    for ($i = 0; $i < count($infocentrale); $i++) {
        for ($j = 0; $j < 4; $j++) {
            if (!empty($infocentrale[$i]['email' . $j])) {
                $emailCentrale .=$infocentrale[$i]['email' . $j] . '<br>';
            }
        }
    }
} else {
    $idcentrale = $manager->getSingle2('SELECT idcentrale FROM loginpassword,centrale,utilisateur WHERE idlogin_loginpassword = idlogin AND idcentrale_centrale = idcentrale and pseudo=?', $_SESSION['pseudo']);
    if(!empty($idcentrale)){
        $infocentrale = $manager->getList2("select libellecentrale,email1,email2,email3,email4,email5 from centrale where idcentrale=?", $idcentrale);
    }else{
        $centrale = $manager->getSingle2('SELECT idcentrale FROM concerne,centrale WHERE  idcentrale_centrale = idcentrale and idprojet_projet=?', $idprojet);
        $infocentrale = $manager->getList2("select libellecentrale,email1,email2,email3,email4,email5 from centrale where idcentrale=?", $idcentrale);
    }
    $emailCentrale = '';
    for ($i = 0; $i < count($infocentrale); $i++) {
        for ($j = 0; $j < 4; $j++) {
            if (!empty($infocentrale[$i]['email' . $j])) {
                $emailCentrale .=$infocentrale[$i]['email' . $j] . '<br>';
            }
        }
    }
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              TRAITEMENT DES EMAILS
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
$emailcentrales = array();
$mailcentrales = $manager->getList2("select email1,email2,email3,email4,email5 from centrale where idcentrale=?", $idcentrale);
for ($i = 0; $i <= count($mailcentrales); $i++) {
    if (!empty($mailcentrales[0][$i])) {
        array_push($emailcentrales, $mailcentrales[0][$i]); //construction d'un tableau d'email des responsable de la centrale
    }
}
$infodemandeur = array($manager->getList2('SELECT mail, mailresponsable FROM creer,loginpassword,utilisateur WHERE idutilisateur_utilisateur = idutilisateur
            AND idlogin_loginpassword = idlogin and idprojet_projet=?', $idprojet));
$maildemandeur = array($infodemandeur[0][0]['mail']); //EMAIL DU DEMANDEUR NE PEUT PAS NE PAS EXISTER
//AJOUT DE L'EMAIL DU RESPONSABLE SI IL EXISTE   
$arrayemailCC = array();
if (!empty($infodemandeur[0][0]['mailresponsable'])) {
    array_push($arrayemailCC, $infodemandeur[0][0]['mailresponsable']); //EMAIL DU RESPONSABLE SI IL EXISTE
    $CC = array_merge($arrayemailCC, $arrayemailCC);
} else {
    $CC = $arrayemailCC;
}
$emailcc = array_merge($emailcentrales, $CC);
$mailCC = array_unique($emailcc);
if (!empty($_POST['comment'])) {
    $commentaires = str_replace("\\", "", $_POST['comment']);
    $commentaire = htmlentities(affiche('TXT_COMMENTSTATUT'), ENT_QUOTES, 'UTF-8') . ' ' . htmlentities(strip_tags(str_replace("''", "'", $commentaires)), ENT_QUOTES, 'UTF-8');
} elseif (!empty($_POST['commentairephase2Valeur'])) {
    $commentaires = str_replace("\\", "", $_POST['commentairephase2Valeur']);
    $commentaire = htmlentities(affiche('TXT_COMMENTSTATUT'), ENT_QUOTES, 'UTF-8') . ' ' . htmlentities(strip_tags(str_replace("''", "'",$commentaires)), ENT_QUOTES, 'UTF-8');
} else {
    $commentaire = '';
}
$titreProjet = $manager->getSingle("select titre from projet where numero='" . $numprojet . "'");
$titre = str_replace("''", "'", utf8_decode($titreProjet));
if (!empty($_POST['statutProjet'])) {
    $idStatut = (int) substr($_POST['statutProjet'], -1);
} elseif (!empty($_SESSION['idstatutprojet'])) {
    $idStatut = (int) substr($_SESSION['idstatutprojet'], -1);
} else {
    $idStatut = $manager->getSingle2("select idstatutprojet_statutprojet from concerne where idprojet_projet=?", $idprojet);
}
$txtbodyref = utf8_decode(affiche('TXT_BOBYREF'));
$sujet =  utf8_decode(TXT_DEMANDEPROJET) . ' : ' . utf8_decode($titre) . ' ' . $txtbodyref . ' ' . $numprojet;
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              CAS DE LA CREATION D'UN PROJET EN PHASE2
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
if(isset($_POST['creerprojetphase2'])&&$_POST['creerprojetphase2']=='oui'){
    $creeeprojetphase2=TRUE;
}elseif (isset($_POST['save']) && $_POST['save']=='non' && isset($_POST['maj']) && $_POST['maj']=='non') {
    $creeeprojetphase2=TRUE;
}

if($creeeprojetphase2==TRUE){//EMAILPROJETPHASE2
$bodyref = utf8_decode((affiche('TXT_BOBYREF')));
$sujet = TXT_DEMANDEPROJET . ' : ' . $titre . ' ' . $txtbodyref . ' ' . $numprojet;
$centraleaccueil = $manager->getSingle2("select libellecentrale from concerne,centrale where idcentrale_centrale=idcentrale and idprojet_projet=?", $idprojet);
$body = affiche('TXT_BODYEMAILPROJET0') . '<br><br>' . htmlentities(stripslashes(str_replace("''","'",affiche('TXT_DEMANDEPROJETPHASE2'))), ENT_QUOTES, 'UTF-8') . '<br><br>' . htmlentities(stripslashes(str_replace("''","'",affiche('TXT_BODYEMAILPROJET2'))), ENT_QUOTES, 'UTF-8') .
        '<br><br>' . utf8_decode(stripslashes(str_replace("''","'",affiche('TXT_BODYEMAILPROJET3')))) .
        '<br><br>' . utf8_decode(stripslashes(str_replace("''","'",affiche('TXT_INFOQUESTION')))) .
        '<br><br>' . utf8_decode(stripslashes(str_replace("''","'",affiche('TXT_SINCERESALUTATION')))) .
        '<br><br>' . utf8_decode(stripslashes(str_replace("''","'",affiche('TXT_RESEAURENATECH')))) .
        '<br><br>' . utf8_decode(stripslashes(str_replace("''","'",affiche('TXT_BODYEMAILPROJET4')))) .  ' ' . $centraleaccueil . '<br>' . $emailCentrale . '<br>' .
        "<a href='https://www.renatech.org/projet' >" . TXT_RETOUR . '</a><br>    ' .
        '<br><br>' . utf8_decode(stripslashes(str_replace("''","'",affiche('TXT_DONOTREPLY')))); 
    envoieEmail($body, $sujet, $maildemandeur, $mailCC); //envoie de l'email au responsable centrale et au copiste
    
    if (!empty($_POST['etapeautrecentrale']) && $_POST['etapeautrecentrale'] == 'TRUE') {//EMAILPROJETPHASE2 AVEC UNE AUTRE CENTRALE  
        include 'outils/envoiEmailAutreCentrale.php';    
        header('Location: /' . REPERTOIRE . '/project/' . $lang . '/' . $idprojet . '/' . $nbpersonnecentrale . '/' . $idcentrale);
    }
}elseif
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              TRAITEMENT DU STATUT ACCEPTE
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

    ($idStatut === ACCEPTE && isset($_POST['creer_phase2'])) {//PROJET PROVENENANT DE LA CREATION D'UNE DEMANDE DE PROJET PHASE1+PHASE2
    $body = htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_MRSMR'))), ENT_QUOTES, 'UTF-8') . '<br><br>' . (htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_BODYEMAILPHASE200'))), ENT_QUOTES, 'UTF-8')) . '<br>' . htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_BODYEMAILPHASE221'))), ENT_QUOTES, 'UTF-8') . ' ' .
            "<a href='https://www.renatech.org/projet'>" . affiche('TXT_BODYEMAILPHASE220') . "</a>" . '<br><br>' . htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_BODYEMAILPHASE230'))), ENT_QUOTES, 'UTF-8') . '<br><br>' .
            htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_RAPPELINSERTLOGO0'))), ENT_QUOTES, 'UTF-8') . '<br><br>' . htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_SINCERESALUTATION'))), ENT_QUOTES, 'UTF-8') . '<br><br>' . htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_RESEAURENATECH'))), ENT_QUOTES, 'UTF-8') . '<br><br>' .
            htmlentities(affiche('TXT_RESPONSABLEBODYTEMAIL10'), ENT_QUOTES, 'UTF-8') . ' ' . htmlentities($centrale, ENT_QUOTES, 'UTF-8') . '<br>' . $emailCentrale . '<br><br>' .
            '<a href="https://www.renatech.org/projet">' . htmlentities(TXT_RETOUR, ENT_QUOTES, 'UTF-8') . '<a>' . '<br><br>      ' .
            htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_DONOTREPLY'))), ENT_QUOTES, 'UTF-8') . '<br><br>';   
    envoieEmail($body, $sujet, $maildemandeur, $mailCC); //envoie de l'email au responsable centrale et au copiste    
    //test si autre centrale    
    if (!empty($_POST['etapeautrecentrale']) && $_POST['etapeautrecentrale'] == 'TRUE') {
        $libellecentrale = array();
        $sLibellecentrale = '';
        $arrayid = $manager->getList2("select idcentrale from projetautrecentrale where idprojet=?", $idprojet);
        for ($i = 0; $i < count($arrayid); $i++) {
            $arraylibellecentrale = $manager->getList2("SELECT libellecentrale FROM centrale WHERE idcentrale = ?", $arrayid[$i]['idcentrale']);
            if (!empty($arraylibellecentrale[0]['libellecentrale'])) {
                array_push($libellecentrale, $arraylibellecentrale[0]['libellecentrale']); //construction d'un tableau d'email            
                $sLibellecentrale.=$arraylibellecentrale[0]['libellecentrale'] . ', ';
            }
        }
        $slibellecentrale = substr($sLibellecentrale, 0, -2);
        $emailcentrales = array();
        $sEmailcentrale = '';

        for ($i = 0; $i < count($arrayid); $i++) {
            $mailcentrale = $manager->getList2("SELECT email1,email2,email3,email4,email5 FROM centrale WHERE idcentrale = ? ", $arrayid[$i]['idcentrale']);
            for ($j = 0; $j <= 5; $j++) {
                if (!empty($mailcentrale[0][$j])) {
                    array_push($emailcentrales, $mailcentrale[0][$j]); //construction d'un tableau d'email            
                    $sEmailcentrale.=$mailcentrale[0][$j] . '<br>';
                }
            }
        }
//DESTINATAIRE
        $maildestinataire = $emailcentrales;
        $mailDemandeur = array_merge($maildemandeur, $mailCC);
        $semailcentrale = '<br>' . substr($sEmailcentrale, 0, -1);

        $body = utf8_decode(htmlentities(stripslashes(str_replace("''", "'", affiche('TXT_MRSMR'))), ENT_QUOTES, 'UTF-8')) . '<br><br>' .
                htmlentities(stripslashes(str_replace("''", "'", affiche('TXT_BODYEMAILDEANDESOUTIENT'))), ENT_QUOTES, 'UTF-8') . ': ' . $numprojet . ' ' .
                htmlentities(stripslashes(str_replace("''", "'", affiche('TXT_BODYEMAILDEPOSECENTRALE'))), ENT_QUOTES, 'UTF-8') . ': ' .
                htmlentities($slibellecentrale, ENT_QUOTES, 'UTF-8')  . ', ' .htmlentities(stripslashes(str_replace("''", "'", affiche('TXT_ETAPETECHNO')))).'<br><br>'.
                htmlentities(stripslashes(str_replace("''", "'", affiche('TXT_BODYEMAILDESCENTRALPART'))), ENT_QUOTES, 'UTF-8') . '<br>' .
                htmlentities(stripslashes(str_replace("''", "'", strip_tags($descriptionautrecentrale))), ENT_QUOTES, 'UTF-8') . '<br><br>' .                
                htmlentities(stripslashes(str_replace("''", "'", affiche('TXT_bodyEMAILCENTRALPART'))), ENT_QUOTES, 'UTF-8') . '<br><br>' .
                htmlentities(stripslashes(str_replace("''", "'", affiche('TXT_SINCERESALUTATION'))), ENT_QUOTES, 'UTF-8') . '<br><br>' .
                htmlentities(stripslashes(str_replace("''", "'", affiche('TXT_RESEAURENATECH'))), ENT_QUOTES, 'UTF-8') . '<br><br>' .
                htmlentities(stripslashes(str_replace("''", "'", affiche('TXT_ADRESSEEMAILPART'))), ENT_QUOTES, 'UTF-8') .
                $semailcentrale . '<br><br><br><br>' .
                "<a href='https://www.renatech.org/projet' >" . TXT_RETOUR . '</a><br><br><br>' .
                htmlentities(stripslashes(str_replace("''", "'", affiche('TXT_DONOTREPLY'))), ENT_QUOTES, 'UTF-8');
        $sujet = utf8_decode(TXT_PROJETNUM) . $numprojet;
        if (isset($_POST['integerspinner']) && !empty($_POST['integerspinner'])) {
            $nbpersonnecentrale = $_POST['integerspinner'];
        } else {
            $nbpersonnecentrale = 0;
        }   
        envoieEmail($body, $sujet, $maildestinataire, $mailDemandeur);
    }
    header('Location: /' . REPERTOIRE . '/project/' . $lang . '/' . $idprojet . '/' . $nbpersonnecentrale . '/' . $idcentrale);
    exit();
} elseif ($idStatut === ACCEPTE){
    $body = htmlentities(str_replace("''","'",stripslashes(affiche('TXT_MRSMR'))), ENT_QUOTES, 'UTF-8') . '<br><br>' . htmlentities(str_replace("''","'", stripslashes(affiche('TXT_BODYEMAILPHASE20'))), ENT_QUOTES, 'UTF-8') . '<br>' . htmlentities(str_replace("''","'",stripslashes(affiche('TXT_BODYEMAILPHASE21'))), ENT_QUOTES, 'UTF-8') .
            "  <a href='https://www.renatech.org/projet'>" . str_replace("''","'",stripslashes(affiche('TXT_BODYEMAILPHASE22'))) . "</a>" . '<br><br>' .htmlentities(str_replace("''","'", stripslashes(affiche('TXT_BODYEMAILPHASE23'))), ENT_QUOTES, 'UTF-8') . '<br><br>' .
            htmlentities(str_replace("''","'", stripslashes(affiche('TXT_RAPPELINSERTLOGO'))), ENT_QUOTES, 'UTF-8') . '<br><br>' . htmlentities(affiche('TXT_SINCERESALUTATION'), ENT_QUOTES, 'UTF-8') . '<br><br>' . htmlentities(str_replace("''","'",stripslashes(affiche('TXT_RESEAURENATECH'))), ENT_QUOTES, 'UTF-8') . '<br><br>' .
            htmlentities(str_replace("''","'",stripslashes(affiche('TXT_RESPONSABLEBODYTEMAIL10'))), ENT_QUOTES, 'UTF-8') . ' ' . htmlentities($centrale, ENT_QUOTES, 'UTF-8') . '<br>' . $emailCentrale . '<br><br>' .
            '<a href="https://www.renatech.org/projet">' . htmlentities(TXT_RETOUR, ENT_QUOTES, 'UTF-8') . '<a>' . '<br><br>      ' . htmlentities(str_replace("''","'",stripslashes(affiche('TXT_DONOTREPLY'))), ENT_QUOTES, 'UTF-8') . '<br><br>';    
    envoieEmail($body, $sujet, $maildemandeur, $mailCC); //envoie de l'email au responsable centrale et au copiste
            //test si autre centrale    
    if (!empty($_POST['etapeautrecentrale']) && $_POST['etapeautrecentrale'] == 'TRUE') {
        $libellecentrale = array();
        $sLibellecentrale = '';
        $arrayid = $manager->getList2("select idcentrale from projetautrecentrale where idprojet=?", $idprojet);
        for ($i = 0; $i < count($arrayid); $i++) {
            $arraylibellecentrale = $manager->getList2("SELECT libellecentrale FROM centrale WHERE idcentrale = ?", $arrayid[$i]['idcentrale']);
            if (!empty($arraylibellecentrale[0]['libellecentrale'])) {
                array_push($libellecentrale, $arraylibellecentrale[0]['libellecentrale']); //construction d'un tableau d'email            
                $sLibellecentrale.=$arraylibellecentrale[0]['libellecentrale'] . ', ';
            }
        }
        $slibellecentrale = substr($sLibellecentrale, 0, -2);
        $emailcentrales = array();
        $sEmailcentrale = '';

        for ($i = 0; $i < count($arrayid); $i++) {
            $mailcentrale = $manager->getList2("SELECT email1,email2,email3,email4,email5 FROM centrale WHERE idcentrale = ? ", $arrayid[$i]['idcentrale']);
            for ($j = 0; $j <= 5; $j++) {
                if (!empty($mailcentrale[0][$j])) {
                    array_push($emailcentrales, $mailcentrale[0][$j]); //construction d'un tableau d'email            
                    $sEmailcentrale.=$mailcentrale[0][$j] . '<br>';
                }
            }
        }
//DESTINATAIRE
        $maildestinataire = $emailcentrales;
        $semailcentrale = '<br>' . substr($sEmailcentrale, 0, -1);
        $body = utf8_decode(htmlentities(stripslashes(str_replace("''", "'", affiche('TXT_MRSMR'))), ENT_QUOTES, 'UTF-8')) . '<br><br>' .
                htmlentities(stripslashes(str_replace("''", "'", affiche('TXT_BODYEMAILDEANDESOUTIENT'))), ENT_QUOTES, 'UTF-8') . ': ' . $numprojet . ' ' .
                htmlentities(stripslashes(str_replace("''", "'", affiche('TXT_BODYEMAILDEPOSECENTRALE'))), ENT_QUOTES, 'UTF-8') . ': ' .
                htmlentities($slibellecentrale, ENT_QUOTES, 'UTF-8')  . ', ' .htmlentities(stripslashes(str_replace("''", "'", affiche('TXT_ETAPETECHNO')))).' '.
                htmlentities(stripslashes(str_replace("''", "'", strip_tags(affiche('TXT_ETAPE')))), ENT_QUOTES, 'UTF-8').'<br><br>'.
                htmlentities(stripslashes(str_replace("''", "'", affiche('TXT_BODYEMAILDESCENTRALPART'))), ENT_QUOTES, 'UTF-8') . '<br>' .                
                htmlentities(stripslashes(str_replace("''", "'", strip_tags($descriptionautrecentrale))), ENT_QUOTES, 'UTF-8') . '<br><br>' .                
                htmlentities(stripslashes(str_replace("''", "'", affiche('TXT_bodyEMAILCENTRALPART'))), ENT_QUOTES, 'UTF-8') . '<br><br>' .
                htmlentities(stripslashes(str_replace("''", "'", affiche('TXT_SINCERESALUTATION'))), ENT_QUOTES, 'UTF-8') . '<br><br>' .
                htmlentities(stripslashes(str_replace("''", "'", affiche('TXT_RESEAURENATECH'))), ENT_QUOTES, 'UTF-8') . '<br><br>' .
                htmlentities(stripslashes(str_replace("''", "'", affiche('TXT_ADRESSEEMAILPART'))), ENT_QUOTES, 'UTF-8') .
                $semailcentrale . '<br><br><br><br>' .
                "<a href='https://www.renatech.org/projet' >" . TXT_RETOUR . '</a><br><br><br>' .
                htmlentities(stripslashes(str_replace("''", "'", affiche('TXT_DONOTREPLY'))), ENT_QUOTES, 'UTF-8');        
        $sujet = utf8_decode(TXT_PROJETNUM) . $numprojet;
        if (isset($_POST['integerspinner']) && !empty($_POST['integerspinner'])) {
            $nbpersonnecentrale = $_POST['integerspinner'];
        } else {
            $nbpersonnecentrale = 0;
        }
        envoieEmail($body, $sujet, $maildestinataire, $maildemandeur);
    header('Location: /' . REPERTOIRE . '/accepted_project/' . $lang . '/' . $idprojet . '/' . ACCEPTE.'/'.$numprojet);
    exit();
    }header('Location: /' . REPERTOIRE . '/accepted_project/' . $lang . '/' . $idprojet . '/' . ACCEPTE.'/'.$numprojet);
}elseif ($idStatut == REFUSE) {
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              TRAITEMENT DU STATUT PROJET REFUSEE
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    $body = htmlentities(str_replace("''","'",stripslashes(affiche('TXT_MRSMR'))), ENT_QUOTES, 'UTF-8') . '<br><br>' . htmlentities(str_replace("''","'",stripslashes(affiche('TXT_BODYEMAILREFUSEPHASE20'))), ENT_QUOTES, 'UTF-8') . '<br><br>' . $commentaire . '<br><br>' .
            htmlentities(str_replace("''","'",stripslashes(affiche('TXT_BODYEMAILREFUSEPHASE21'))), ENT_QUOTES, 'UTF-8') . '<br><br>' . htmlentities(str_replace("''","'",stripslashes(affiche('TXT_SINCERESALUTATION'))), ENT_QUOTES, 'UTF-8') . '<br><br>' . htmlentities(str_replace("''","'",stripslashes(affiche('TXT_RESEAURENATECH'))), ENT_QUOTES, 'UTF-8') . '<br><br>' .
            htmlentities(str_replace("''","'",stripslashes(affiche('TXT_RESPONSABLEBODYTEMAIL10'))), ENT_QUOTES, 'UTF-8') . '' . htmlentities($centrale, ENT_QUOTES, 'UTF-8') . ' <br> ' . $emailCentrale . '<br>' .
            htmlentities(str_replace("''","'",stripslashes(affiche('TXT_RESEAURENATECH'))), ENT_QUOTES, 'UTF-8') . "<br><br><a href='https://www.renatech.org/projet' >" . TXT_RETOUR . '</a><br><br>    '
            . htmlentities(str_replace("''","'",stripslashes(affiche('TXT_DONOTREPLY'))), ENT_QUOTES, 'UTF-8') . '<br><br>';
    $txtbodyref = html_entity_decode((htmlentities(affiche('TXT_BOBYREF'), ENT_QUOTES, 'UTF-8')));
    $infodemandeur = array($manager->getList2('SELECT mail, mailresponsable FROM creer,loginpassword,utilisateur WHERE idutilisateur_utilisateur = idutilisateur 
            AND idlogin_loginpassword = idlogin and idprojet_projet=?', $idprojet));
    $idcentrale= $manager->getSingle2("SELECT idcentrale_centrale	FROM utilisateur,loginpassword WHERE idlogin = idlogin_loginpassword and pseudo =?",$_SESSION['pseudo']);
    if(empty($idcentrale)){        
        $idcentrale= $manager->getSingle2("SELECT idcentrale_centrale FROM utilisateuradministrateur,concerne WHERE idprojet = idprojet_projet and idprojet=?",$idprojet);
    }
    envoieEmail($body, $sujet, $maildemandeur, $mailCC); //envoie de l'email au responsable centrale et au copiste  
    header('Location: /' . REPERTOIRE . '/RefusedProject/' . $lang . '/' . $idprojet . '/' . $numprojet . '/' . $idcentrale . '/' . rand(0, 100000));
    exit();
} elseif ($idStatut == ENATTENTE) {
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              TRAITEMENT DU STATUT PROJET EN ATTENTE  
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    $body = htmlentities(str_replace("''","'",stripslashes(affiche('TXT_MRSMR9'))), ENT_QUOTES, 'UTF-8') . '<br><br>' . htmlentities(str_replace("''","'",stripslashes(affiche('TXT_BODYEMAILENATTENTEPHASE01'))), ENT_QUOTES, 'UTF-8') . '<br>' . $commentaire . '<br>' .
            htmlentities(str_replace("''","'",stripslashes(affiche('TXT_BODYEMAILENATTENTEPHASE02'))), ENT_QUOTES, 'UTF-8') . '<br><br>' . htmlentities(str_replace("''","'",stripslashes(affiche('TXT_SINCERESALUTATION'))), ENT_QUOTES, 'UTF-8') . '<br><br>' . htmlentities(str_replace("''","'",stripslashes(affiche('TXT_RESEAURENATECH'))), ENT_QUOTES, 'UTF-8') . '<br><br>' .
            htmlentities(str_replace("''","'",stripslashes(affiche('TXT_SINCERESALUTATION'))), ENT_QUOTES, 'UTF-8') . '' . htmlentities($centrale, ENT_QUOTES, 'UTF-8') . ' <br> ' . $emailCentrale . '<br><br>' . htmlentities(str_replace("''","'",stripslashes(affiche('TXT_DONOTREPLY'))), ENT_QUOTES, 'UTF-8') . '<br><br>';
    envoieEmail($body, $sujet, $maildemandeur, $mailCC); //envoie de l'email au responsable centrale et au copiste
    header('Location: /' . REPERTOIRE . '/Waiting_project1/' . $lang . '/' . $idprojet . '/' . $numprojet . '/' . ENATTENTE);
    exit();
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              TRAITEMENT DU STATUT TRANSFERER A UNE AUTRE CENTRALE
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
}elseif ($idStatut == TRANSFERERCENTRALE) {
    if(isset($_POST['centraletrs'])){        
        $libellecentraledestination = $manager->getSingle2("select libellecentrale from centrale where idcentrale=?", $_POST['centraletrs']);
        //CENTRALE DESTINATION
    $emailcentraledestinations = array();
    $mailcentrales = $manager->getList2("select email1,email2,email3,email4,email5 from centrale where idcentrale=?", $_POST['centraletrs']);
    $infocentraledest = $manager->getList2("select email1,email2,email3,email4,email5 from centrale where idcentrale=?", $_POST['centraletrs']);
        $emailCentrale = '';
        for ($i = 0; $i < count($infocentraledest); $i++) {
            for ($j = 0; $j < 4; $j++) {
                if (!empty($infocentraledest[$i]['email' . $j])) {
                    $emailCentrale .=$infocentraledest[$i]['email' . $j] . '<br>';
                }
            }
        }    
    }elseif(isset($_POST['centrale'])){
        //CENTRALE DESTINATION
        $emailcentraledestinations = array();
        $mailcentrales = $manager->getList2("select email1,email2,email3,email4,email5 from centrale where idcentrale=?", $_POST['centrale']);
        $libellecentraledestination = $manager->getSingle2("select libellecentrale from centrale where idcentrale=?", $_POST['centrale']);
        
        $infocentraledest = $manager->getList2("select email1,email2,email3,email4,email5 from centrale where idcentrale=?", $_POST['centrale']);
        $emailCentrale = '';
        for ($i = 0; $i < count($infocentraledest); $i++) {
            for ($j = 0; $j < 4; $j++) {
                if (!empty($infocentraledest[$i]['email' . $j])) {
                    $emailCentrale .=$infocentraledest[$i]['email' . $j] . '<br>';
                }
            }
        }    
        
    }    
    for ($i = 0; $i <= 5; $i++) {
        if (!empty($mailcentrales[0][$i])) {
            array_push($emailcentraledestinations, $mailcentrales[0][$i]); //construction d'un tableau d'email des responsable de la centrale
        }
    }    
    //DEMANDEUR
    $infodemandeur = array($manager->getList2("SELECT mail,mailresponsable FROM creer,loginpassword,utilisateur WHERE  idlogin = idlogin_loginpassword
            AND  idutilisateur = idutilisateur_utilisateur and   idprojet_projet =?", $idprojet));
    $maildemandeur = array($infodemandeur[0][0]['mail']); //EMAIL DU DEMANDEUR NE PEUT PAS NE PAS EXISTER
    if (!empty($infodemandeur[0][0]['mailresponsable'])) {
        array_push($arrayemailCC, $infodemandeur[0][0]['mailresponsable']); //EMAIL DU RESPONSABLE SI IL EXISTE
        $CC = $arrayemailCC;
    } else {
        $CC = $arrayemailCC;
    }
    $CC = array_merge($arrayemailCC, $emailcentraledestinations); //CENTRALE DESTINATION
    $cc = array_merge($mailCC, $CC); //CENTRALE SOURCE
    $mailCC = array_unique($cc);
    $body = utf8_decode(str_replace("''","'",stripslashes(affiche('TXT_MRSMR')))) . '<br><br>' . utf8_decode(str_replace("''","'",stripslashes(affiche('TXT_BODYEMAILTRSFPHASE20')))) . '<br><br>' .
            utf8_decode(str_replace("''","'",stripslashes(affiche('TXT_BODYEMAILPHASE21')))) . " <a href='https://www.renatech.org/projet'>" . utf8_decode(str_replace("''","'",stripslashes(affiche('TXT_FINSUJETMAILRESPONSABLE')))) . "</a>" .
            '<br><br>' . htmlentities(str_replace("''","'",stripslashes(affiche('TXT_BODYEMAILTRSFPHASE22')))) . '<br><br>' .
            utf8_decode(str_replace("''","'",stripslashes(affiche('TXT_RAPPELINSERTLOGO1')))) . '<br><br>' . utf8_decode(str_replace("''","'",stripslashes(affiche('TXT_SINCERESALUTATION')))) . '<br><br>' . utf8_decode(str_replace("''","'",stripslashes(affiche('TXT_RESEAURENATECH')))) .
            '<br><br>' . utf8_decode(affiche('TXT_EMAILADDRESSCENTRAL')) . ' ' .  utf8_decode($libellecentraledestination) . ' <br> ' . $emailCentrale . '<br><br>' .
            '<br><br><a href="https://www.renatech.org/projet">' . utf8_decode(TXT_RETOUR) . '<a>' . '<br><br>' .
            utf8_decode(str_replace("''","'",stripslashes(affiche('TXT_DONOTREPLY'))));
    $EmailCC = array_values($mailCC); //Renumérotation des index
    envoieEmail($body, $sujet, $maildemandeur, $EmailCC); //envoie de l'email au responsable centrale et au copiste
    header('Location: /' . REPERTOIRE . '/transfered_project/' . $lang . '/' . $idprojet . '/' . $numprojet . '/' . $idcentrale);
    exit();
}