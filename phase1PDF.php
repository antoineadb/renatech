<?php
if (isset($_GET['numProjet'])) {
    $numprojet = $_GET['numProjet'];
    $idprojet = $manager->getSingle2("select idprojet from projet where numero=?", $numprojet);
} elseif (isset($_GET['idprojet'])) {
    $idprojet = $_GET['idprojet'];
} elseif (isset($_SESSION['idprojet'])) {
    $idprojet = $_SESSION['idprojet'];
}
//Police et couleurs
$pdf->SetLineWidth(.2);
//Restauration de la police et des couleurs
$pdf->SetFont('Arial', '', 6);
$pdf->SetFillColor(234, 234, 234);
$pdf->SetTextColor(0);

$resprojet = $manager->getListbyArray("select titre,dateprojet,acronyme,contexte,confidentiel,description,attachement,numero from projet where idprojet=?", array($idprojet));

$titreproj = str_replace("’", "''", $resprojet[0]['titre']);
$titreProjet = str_replace("''", "'", utf8_decode($titreproj));
$originaldate = $resprojet[0]['dateprojet'];
$originalDate = date("d-M-Y", strtotime($originaldate));
$Acronyme = str_replace("’", "''", $resprojet[0]['acronyme']);
$acronyme = str_replace("''", "'", utf8_decode(stripslashes($Acronyme)));
if (empty($resprojet[0]['confidentiel'])) {
    $confidentiel = 'Non';
} else {
    $confidentiel = 'Oui';
}

$attachement = $resprojet[0]['attachement'];
$numero = $resprojet[0]['numero'];

$pdf->Ln();
$pdf->Ln();
$pdf->SetFont('Arial', '', 12);
$pdf->WriteHTML('<b><u>' . utf8_decode(TXT_TITREDOCPDF) . ' :</u></b><br>');
$pdf->Ln();
$pdf->SetFont('Arial', '', 6);

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              TRAITEMENT DE LA DATE
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->WriteHTML(utf8_decode('<b>' . TXT_DATEPROJET . ' :</b> '));
$pdf->WriteHTML($originalDate . '<br>');

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              TRAITEMENT DU TITRE
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->WriteHTML(utf8_decode('<b>' . TXT_TITREPROJET . ' :</b> '));
$pdf->WriteHTML($titreProjet . '<br>');
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              TRAITEMENT DE L'ACRONYME
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->WriteHTML(utf8_decode('<b>' . TXT_ACRONYME . ' :</b> '));
$pdf->WriteHTML($acronyme . '<br>');
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              TRAITEMENT DU CONFIDENTIEL
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->WriteHTML(utf8_decode('<b>' . TXT_PROJETCONFIDENTIEL . ' :</b> '));
$pdf->WriteHTML($confidentiel . '<br>');
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              TRAITEMENT DU NUMERO
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->WriteHTML(utf8_decode('<b>' . TXT_NUMROPROJET . ' :</b> '));
$pdf->WriteHTML($numero . '<br>');
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              TRAITEMENT DE LA PIECE JOINTE
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
if (!empty($attachement)) {
    $pdf->WriteHTML(utf8_decode('<b>' . TXT_PIECEJOINTE . ' :</b> '));
    $pdf->WriteHTML($attachement . '<br>');
}

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              TRAITEMENT DU STATUT DU PROJET
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
$idstatutprojet = $manager->getSingle2("select idstatutprojet_statutprojet from concerne where idprojet_projet=?", $idprojet);
$libelleStatut = utf8_decode($manager->getSingle2("select libellestatutprojet from statutprojet where idstatutprojet=?", $idstatutprojet));
//Récupération du type de projet
$idtypeprojet = $manager->getSingle2("select idtypeprojet_typeprojet from projet where idprojet=?", $idprojet);
$libelleType = $manager->getSingle2("select libelletype from typeprojet where idtypeprojet=?", $idtypeprojet);

if ($libelleType != 'n/a') {
    $pdf->WriteHTML(utf8_decode('<b>' . TXT_STATUTPROJETS . ' :</b> '));
    $pdf->WriteHTML($libelleStatut . '<br>');
} else {
    $pdf->WriteHTML(utf8_decode('<b>' . TXT_STATUTPROJETS . ' :</b> '));
    $pdf->WriteHTML($libelleStatut . '<br>');
}

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              TRAITEMENT DU LIBELLE DE LA CENTRALE
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
$nbCentrale = $manager->getSingle2("select count(idcentrale_centrale) from concerne where idprojet_projet=?", $idprojet);
$i = 0;
$slibellecentrale = '';
while ($i < $nbCentrale) {
    $resultCentrale = $manager->getListbyArray("select idcentrale_centrale from concerne where idprojet_projet=?", array($idprojet));
    if ($i == $nbCentrale - 1) {
        $idCentrale = $resultCentrale[$i]['idcentrale_centrale'];
        $resultLibelleCentrale = $manager->getListbyArray("select libellecentrale from centrale where idcentrale=?", array($idCentrale));
        $libellecentrale = $resultLibelleCentrale[0]['libellecentrale'];
        $slibellecentrale.=$libellecentrale;
    } else {
        $idCentrale = $resultCentrale[$i]['idcentrale_centrale'];
        $resultLibelleCentrale = $manager->getListbyArray("select libellecentrale from centrale where idcentrale=?", array($idCentrale));
        $libellecentrale = $resultLibelleCentrale[0]['libellecentrale'];
        $slibellecentrale .=$libellecentrale . ' - ';
    }
    $i++;
}
$pdf->WriteHTML(utf8_decode('<b>' . TXT_CENTRALESELECTONNEE . ':</b> '));
$pdf->WriteHTML($slibellecentrale . '<br>');
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              TRAITEMENT DU CONTEXTE
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
if (!empty($resprojet[0]['contexte'])) {
    $Contexte = str_replace("’", "''",$resprojet[0]['contexte']);
    $contexte = str_replace("''", "'", utf8_decode($Contexte));
    $pdf->WriteHTML('<u><b>' . TXT_CONTEXTESCIENTIFIQUE . '</b> :</u><br>');
    $pdf->WriteHTML( stripslashes($contexte) .'<br>');
}


//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              TRAITEMENT DE LA DESCRIPTION
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
if (!empty($resprojet[0]['description'])) {
    $Description = str_replace("’", "''", $resprojet[0]['description']);
    $description = str_replace("''", "'", utf8_decode($Description));
    $pdf->WriteHTML('<b><u>' . TXT_DESCRIPTION . ' :</u></b><br>');
    $pdf->WriteHTML(stripslashes($description));
}
?>
