<?php

session_start();
include 'decide-lang.php';
include('pdf/fpdf.php');
include_once 'pdf/WriteHTML.php';
$pdf = new PDF_HTML();

$pdf->AddPage();
$pdf->SetTitle(utf8_decode(TXT_TITREPDF));
include_once 'class/Manager.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
//Récupération de l'idProjet
include_once 'outils/toolBox.php';
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    echo '<script>window.location.replace("erreurlogin.php")</script>';
}
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              PHASE 1
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
include 'phase1PDF.php';
$pdf->Ln();
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//Formatage du cadre en fonction du nombre de ligne de manière à pouvoir afficher le libellé contexte
$t = 144;
//PROJET PHASE 2
$idstatutaccepte = $manager->getSingle2("select idstatutprojet from statutprojet where libellestatutprojet=?", 'Acceptée pour expertise');
$resultprojetphase2 = $manager->getListbyArray("SELECT libellestatutprojet,verrouidentifiee,commentaireprojet,reussite,refinterneprojet,idautrethematique_autrethematique,idautresourcefinancement_autresourcefinancement,libellethematique,libellesourcefinancement,libelleperiodicite,attachementdesc,contactscentraleaccueil,datedebuttravaux,dureeprojet,
centralepartenaireprojet,nbplaque,partenaire1,nbrun, emailrespdevis, reussite,libelletype,typeformation,nbheure,descriptiftechnologique,nbeleve    
FROM  projet,periodicite,sourcefinancement,thematique, concerne ,typeprojet, statutprojet WHERE  idprojet_projet = idprojet AND	idstatutprojet = idstatutprojet_statutprojet and idstatutprojet_statutprojet =? and idperiodicite_periodicite=idperiodicite
and idprojet=? AND idtypeprojet = idtypeprojet_typeprojet and idsourcefinancement_sourcefinancement = idsourcefinancement and idthematique = idthematique_thematique;", array($idstatutaccepte, $idprojet));

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              TRAITEMENT DES COMMENTAIRES
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
if (!empty($resultprojetphase2[0]['commentaireprojet'])) {
    $Commentaire = str_replace("’", "''", $resultprojetphase2[0]['commentaireprojet']);
    $commentaire = str_replace("''", "'", utf8_decode(stripslashes($Commentaire)));
    $pdf->WriteHTML('<b>' . TXT_COMMENTAIREPROJET . '</b> : ');
    $pdf->WriteHTML($commentaire . '<br>');
}

if (!empty($resultprojetphase2[0]['attachementdesc'])) {
    $attachementdesc = $resultprojetphase2[0]['attachementdesc'];
    $pdf->WriteHTML('<b>' . utf8_decode(TXT_ATTACHEMENT) . utf8_decode(' n°2') . '</b>: ');
    $pdf->WriteHTML(utf8_decode($attachementdesc) . '<br>');
}

if (!empty($resultprojetphase2[0]['verrouidentifiee'])) {
    $Verrouidentifiee = str_replace("’", "''", $resultprojetphase2 [0]['verrouidentifiee']);
    $verrouidentifiee = str_replace("''", "'", utf8_decode($Verrouidentifiee));
    $pdf->WriteHTML('<b><u>' . utf8_decode(TXT_VERROUIDENTIFIEE) . ' :</u></b><br>');
    $pdf->WriteHTML(stripslashes($verrouidentifiee) . '<br>');
}

if (!empty($resultprojetphase2[0]['nbplaque'])) {
    $pdf->WriteHTML('<b>' . utf8_decode(TXT_NBPLAQUE) . ': </b>');
    $pdf->WriteHTML(stripslashes($resultprojetphase2 [0]['nbplaque']) . '<br>');
}

if (!empty($resultprojetphase2[0]['nbrun'])) {
    $pdf->WriteHTML('<b>' . utf8_decode(TXT_NBRUN) . ': </b>');
    $pdf->WriteHTML(stripslashes($resultprojetphase2 [0]['nbrun']) . '<br>');
}

if (!empty($resultprojetphase2[0]['contactscentraleaccueil'])) {
    $Contactscentraleaccueil = str_replace("’", "''", $resultprojetphase2 [0]['contactscentraleaccueil']);
    $contactscentraleaccueil = str_replace("''", "'", utf8_decode($Contactscentraleaccueil));
    $pdf->WriteHTML('<b>' . utf8_decode(stripslashes(TXT_CONTACTCENTRALACCUEIL)) . ' :</b> ');
    $pdf->WriteHTML(stripslashes($contactscentraleaccueil) . '<br>');
}

if (!empty($resultprojetphase2[0]['datedebuttravaux'])) {
    $originaldate = $resultprojetphase2[0]['datedebuttravaux'];
    $originalDate = date("d-M-Y", strtotime($originaldate));
    $pdf->WriteHTML(utf8_decode('<b>' . TXT_DATEDEBUTTRAVAUX . ' :</b> '));
    $pdf->WriteHTML($originalDate . '<br>');
}

if (!empty($resultprojetphase2[0]['dureeprojet'])) {
    $pdf->WriteHTML('<b>' . utf8_decode(TXT_DUREEPROJET) . ': </b>');
    $pdf->WriteHTML(stripslashes($resultprojetphase2 [0]['dureeprojet']) . ' ' . utf8_decode($resultprojetphase2[0]['libelleperiodicite']) . '<br>');
}

if (!empty($resultprojetphase2[0]['emailrespdevis'])) {
    $pdf->WriteHTML('<b>' . utf8_decode(TXT_EMAILRESPDEVIS) . ': </b>');
    $pdf->WriteHTML(stripslashes($resultprojetphase2 [0]['emailrespdevis']) . '<br>');
}

if (!empty($resultprojetphase2[0]['reussite'])) {
    $Reussite = str_replace("’", "''", $resultprojetphase2 [0]['reussite']);
    $reussite = str_replace("''", "'", utf8_decode($Reussite));
    $pdf->WriteHTML('<b><u>' . utf8_decode(TXT_REUSSITEESCOMPTE) . ' :</u></b><br>');
    $pdf->WriteHTML(stripslashes($reussite) . '<br>');
}

if (!empty($resultprojetphase2[0]['libelletype'])) {
    $Libelletype = str_replace("’", "''", $resultprojetphase2 [0]['libelletype']);
    $libelletype = str_replace("''", "'", utf8_decode($Libelletype));
    $pdf->WriteHTML('<b>' . utf8_decode(stripslashes(TXT_TYPEPROJET)) . ' :</b> ');
    $pdf->WriteHTML(stripslashes($libelletype) . '<br>');
}

if (!empty($resultprojetphase2[0]['typeformation'])) {
    $Typeformation = str_replace("’", "''", $resultprojetphase2 [0]['typeformation']);
    $typeformation = str_replace("''", "'", utf8_decode($Typeformation));
    $pdf->WriteHTML('<b>' . utf8_decode(stripslashes(TXT_TYPEFORMATION)) . ' :</b> ');
    $pdf->WriteHTML(stripslashes($typeformation) . '<br>');
}

if (!empty($resultprojetphase2[0]['nbheure'])) {
    $pdf->WriteHTML('<b>' . utf8_decode(stripslashes(TXT_NBHEURE)) . ': </b>');
    $pdf->WriteHTML(stripslashes($resultprojetphase2 [0]['nbheure']) . '<br>');
}

if (!empty($resultprojetphase2[0]['nbeleve']) && $resultprojetphase2 [0]['nbeleve'] > 0) {
    $pdf->WriteHTML('<b>' . utf8_decode(stripslashes(TXT_NBELEVE)) . ': </b>');
    $pdf->WriteHTML($resultprojetphase2 [0]['nbeleve'] . '<br>');
}

if (!empty($resultprojetphase2[0]['libellesourcefinancement']) && $resultprojetphase2[0]['libellesourcefinancement'] != 'Autres') {
    $Libellesourcefinancement = str_replace("’", "''", $resultprojetphase2 [0]['libellesourcefinancement']);
    $libellesourcefinancement = str_replace("''", "'", utf8_decode($Libellesourcefinancement));
    $pdf->WriteHTML('<b>' . utf8_decode(stripslashes(TXT_SOURCEFINANCEMENT)) . ' :</b> ');
    $pdf->WriteHTML(stripslashes($libellesourcefinancement) . '<br>');
}

if (!empty($resultprojetphase2[0]['idautresourcefinancement_autresourcefinancement'])) {
    $idautresourcefinancement_autresourcefinancement = $resultprojetphase2[0]['idautresourcefinancement_autresourcefinancement'];
    $autresourcefinancement = $manager->getSingle2("select libelleautresourcefinancement from autresourcefinancement where idautresourcefinancement =?", $idautresourcefinancement_autresourcefinancement);
    if ($idautresourcefinancement_autresourcefinancement != 1) {
        $Libellesourcefinancement = str_replace("’", "''", $autresourcefinancement);
        $libelleautresourcefinancement = str_replace("''", "'", utf8_decode($Libellesourcefinancement));
        $pdf->WriteHTML('<b>' . utf8_decode(stripslashes(TXT_AUTRESOURCEFINANCEMENT)) . ' :</b> ');
        $pdf->WriteHTML(stripslashes($libelleautresourcefinancement) . '<br>');
    }
}

if (!empty($resultprojetphase2[0]['libellethematique']) && $resultprojetphase2[0]['libellethematique'] != 'Autres') {
    $Libellethematique = str_replace("’", "''", $resultprojetphase2 [0]['libellethematique']);
    $libellethematique = str_replace("''", "'", utf8_decode($Libellethematique));
    $pdf->WriteHTML('<b>' . utf8_decode(stripslashes(TXT_THEMATIQUE)) . ' :</b> ');
    $pdf->WriteHTML(stripslashes($libellethematique) . '<br>');
}


if (!empty($resultprojetphase2[0]['idautrethematique_autrethematique'])) {
    $idautrethematique_autrethematique = $resultprojetphase2[0]['idautrethematique_autrethematique'];
    $autrethematique_autrethematique = $manager->getSingle2("select libelleautrethematique from autrethematique where idautrethematique =?", $idautrethematique_autrethematique);
    if ($autrethematique_autrethematique != 1) {
        $Libellethematique = str_replace("’", "''", $autresourcefinancement);
        $libelleautrethematique = str_replace("''", "'", utf8_decode($Libellethematique));
        $pdf->WriteHTML('<b>' . utf8_decode(stripslashes(TXT_AUTRETHEMATIQUE)) . ' :</b> ');
        $pdf->WriteHTML(stripslashes($libelleautrethematique) . '<br>');
    }
}

$nbressource = $manager->getSingle2("select count(idressource_ressource) from ressourceprojet where idprojet_projet=?", $idprojet);
$i = 0;
$pdf->WriteHTML('<b>' . utf8_decode(stripslashes(TXT_RESSOURCES)) . ' :</b> ');
$slibelleressource = '';
while ($i < $nbressource) {
    $resultRessource = $manager->getListbyArray("select idressource_ressource from ressourceprojet where idprojet_projet=?", array($idprojet));
    if ($i == $nbressource - 1) {
        $idRessource = $resultRessource[$i]['idressource_ressource'];
        $resultRessource = $manager->getListbyArray("select libelleressource from ressource where idressource=?", array($idRessource));
        $libelleressource = $resultRessource[0]['libelleressource'];
        $slibelleressource.=utf8_decode($libelleressource);
    } else {
        $idRessource = $resultRessource[$i]['idressource_ressource'];
        $resultRessource = $manager->getListbyArray("select libelleressource from ressource where idressource=?", array($idRessource));
        $libelleressource = $resultRessource[0]['libelleressource'];
        $slibelleressource .=utf8_decode($libelleressource) . ' - ';
    }
    $i++;
}
$pdf->WriteHTML(stripslashes($slibelleressource) . '<br>');

if (!empty($resultprojetphase2[0]['centralepartenaireprojet'])) {
    $Centralepartenaireprojet = str_replace("’", "''", $resultprojetphase2 [0]['centralepartenaireprojet']);
    $centralepartenaireprojet = str_replace("''", "'", utf8_decode($Centralepartenaireprojet));
    $pdf->WriteHTML('<b>' . utf8_decode(stripslashes(TXT_NOMLABOENTREPRISE)) . utf8_decode(' n°1') . ': </b>');
    $pdf->WriteHTML(stripslashes($centralepartenaireprojet) . '<br>');
}
if (!empty($resultprojetphase2[0]['partenaire1'])) {
    $Partenaire1 = str_replace("’", "''", $resultprojetphase2 [0]['partenaire1']);
    $partenaire1 = str_replace("''", "'", utf8_decode($Partenaire1));
    $pdf->WriteHTML('<b>' . utf8_decode(stripslashes(TXT_NOMPARTENAIRE)) . utf8_decode(' n°1') . ': </b>');
    $pdf->WriteHTML(stripslashes($partenaire1) . '<br>');
}


//PARTENAIRE PROJET
$partenaireprojet = $manager->getListbyArray("SELECT  nompartenaire,nomlaboentreprise	FROM projet, partenaireprojet, projetpartenaire	WHERE
  idpartenaire_partenaireprojet = idpartenaire AND idprojet_projet = idprojet and idprojet=?", array($idprojet));
$nbpartenaire = $manager->getSingle2("select count(idpartenaire_partenaireprojet) from projetpartenaire where idprojet_projet=?", $idprojet);
if ($nbpartenaire > 0) {
    $pdf->WriteHTML('<b>' . utf8_decode(stripslashes(TXT_NBPARTENAIRE_PROJET)) . ': </b>');
    $pdf->WriteHTML($nbpartenaire . '<br>');
}
$nompartenaire = '';
$labpartenaire = '';

for ($j = 0; $j < $nbpartenaire; $j++) {
    $h = $j + 1;
    $k = $j + 2;
    $nompartenaires = $partenaireprojet[$j]['nompartenaire'];
    $labpartenaires = $partenaireprojet[$j]['nomlaboentreprise'];
    if (!empty($nompartenaires)) {
        $pdf->WriteHTML('<b>' . utf8_decode(stripslashes(TXT_NOMPARTENAIRE)) . utf8_decode(' n°') . $k . ': </b>');
        $Nompartenaire = str_replace("’", "''", $nompartenaires);
        $nompartenaire = str_replace("''", "'", ($Nompartenaire));
        $pdf->WriteHTML(utf8_decode(stripslashes($nompartenaire)) . ' <br>');
    }
    if (!empty($labpartenaires)) {
        $pdf->WriteHTML('<b>' . utf8_decode(stripslashes(TXT_NOMLABOENTREPRISE)) . utf8_decode(' n°') . $k . ': </b>');
        $Labpartenaire = str_replace("’", "''", $labpartenaires);
        $labpartenaire = str_replace("''", "'", ($Labpartenaire));
        $pdf->WriteHTML(utf8_decode(stripslashes($labpartenaire)) . '  <br>');
    }
}
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              TRAITEMENT DES PERSONNES CENTRALE
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

$personnecentrale = $manager->getListbyArray("SELECT nomaccueilcentrale, prenomaccueilcentrale, mailaccueilcentrale, telaccueilcentrale, connaissancetechnologiqueaccueil, libellequalitedemandeuraca
FROM projet,personneaccueilcentrale,projetpersonneaccueilcentrale,qualitedemandeuraca
WHERE idpersonneaccueilcentrale = idpersonneaccueilcentrale_personneaccueilcentrale AND idprojet_projet = idprojet AND idqualitedemandeuraca = idqualitedemandeuraca_qualitedemandeuraca
and idprojet = ?", array($idprojet));
$nbpersonnecentrale = $manager->getSingle2("select count(idpersonneaccueilcentrale_personneaccueilcentrale) from projetpersonneaccueilcentrale where idprojet_projet=?", $idprojet);
if ($nbpersonnecentrale > 0) {
    $pdf->WriteHTML('<b><u>' . utf8_decode(stripslashes(TXT_PERSONNEACCUEILCENTRALE)) . '</u></b><br>');
    $pdf->WriteHTML('<b>' . utf8_decode(stripslashes(TXT_NBPERSONNE)) . ' : </b>');
    $pdf->WriteHTML('<b>' . utf8_decode(stripslashes($nbpersonnecentrale)) . ' </b> <br>');
    for ($k = 0; $k < $nbpersonnecentrale; $k++) {
        $h = $k + 1;
        $pdf->WriteHTML('<b>' . utf8_decode(stripslashes(TXT_NOM))  .': </b>');
        $pdf->WriteHTML(utf8_decode(stripslashes(str_replace("''","'",$personnecentrale[$k]['nomaccueilcentrale']))) . ' <br>');
        $pdf->WriteHTML('<b>' . utf8_decode(stripslashes(TXT_PRENOM)) . ': </b>');
        $pdf->WriteHTML(utf8_decode(stripslashes(str_replace("''","'",$personnecentrale[$k]['prenomaccueilcentrale']))) . ' <br>');
        $pdf->WriteHTML('<b>' . utf8_decode(stripslashes(TXT_MAIL))  . ': </b>');
        $pdf->WriteHTML(utf8_decode(stripslashes($personnecentrale[$k]['mailaccueilcentrale'])) . ' <br>');
        $pdf->WriteHTML('<b>' . utf8_decode(stripslashes(TXT_TELEPHONE)) . ': </b>');
        $pdf->WriteHTML(utf8_decode(stripslashes($personnecentrale[$k]['telaccueilcentrale'])) . ' <br>');
        $Connaissancetechnologiqueaccueil = str_replace("’", "''", $personnecentrale[$k]['connaissancetechnologiqueaccueil']);
        $connaissancetechnologiqueaccueil = str_replace("''", "'", utf8_decode($Connaissancetechnologiqueaccueil));
        $pdf->WriteHTML('<b><u>' . utf8_decode(stripslashes(TXT_CONNAISSANCETECHNOLOGIQUE)) . ': </u></b><br> ');
        $pdf->WriteHTML(stripslashes($connaissancetechnologiqueaccueil) . ' : <br>');
        $pdf->WriteHTML('<b>' . utf8_decode(stripslashes(TXT_QUALITE)) . ' </b>');
        $pdf->WriteHTML(stripslashes($personnecentrale[$k]['libellequalitedemandeuraca']) . ' <br>');
    }
}

if (!empty($resultprojetphase2[0]['descriptiftechnologique'])) {
    $Descriptiftechnologique = str_replace("’", "''", $resultprojetphase2 [0]['descriptiftechnologique']);
    $descriptiftechnologique = str_replace("''", "'", utf8_decode($Descriptiftechnologique));
    $pdf->WriteHTML('<b><u>' . utf8_decode(TXT_DESCRIPTIFTECHNOLOGIQUE) . ' :</u></b><br>');
    $pdf->WriteHTML(stripslashes($descriptiftechnologique) . '<br>');
}

if (!empty($resultprojetphase2[0]['refinterneprojet'])) {
    $Refinterneprojet = str_replace("’", "''", $resultprojetphase2 [0]['refinterneprojet']);
    $refinterneprojet = str_replace("''", "'", utf8_decode($Refinterneprojet));
    $pdf->WriteHTML('<b>' . utf8_decode(TXT_REFINTERNE) . ' : </b>');
    $pdf->WriteHTML(stripslashes($refinterneprojet) . '<br>');
}



//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              TRAITEMENT DE VOS INFORMATIONS
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
include 'vosinfoPDF.php';
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//nom du fichier de sauvegarde
$pdf->Output($numero, 'I');
BD::deconnecter();
?>
