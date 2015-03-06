<?php

session_start();
include 'decide-lang.php';
include('pdf/fpdf.php');
include_once 'pdf/WriteHTML.php';
include_once 'outils/constantes.php';
$pdf = new PDF_HTML();
$pdf->AddPage();
$pdf->SetTitle(utf8_decode(TXT_TITREPDF));
include_once 'class/Manager.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include_once 'outils/toolBox.php';
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
if (isset($_GET['numProjet'])) {
    $numprojet = $_GET['numProjet'];
    $idprojet = $manager->getSingle2("select idprojet from projet where numero=?", $numprojet);
} elseif (isset($_GET['idprojet'])) {
    $idprojet = $_GET['idprojet'];
}
//Police et couleurs
$pdf->SetLineWidth(.2);
//Restauration de la police et des couleurs

$pdf->SetFillColor(234, 234, 234);
$pdf->SetTextColor(0);
$pdf->AliasNbPages();
$resprojet = $manager->getListbyArray("select titre,dureeprojet,verrouidentifiee,reussite,refinterneprojet,nbplaque,nbrun,emailrespdevis,datedebuttravaux,descriptiftechnologique,idthematique_thematique,idtypeprojet_typeprojet,contactscentraleaccueil,centralepartenaireprojet,partenaire1,"
        . "idperiodicite_periodicite,dateprojet,datedebutprojet,acronyme,contexte,confidentiel,description,attachement,attachementdesc,numero from projet where idprojet=?", array($idprojet));
$titreproj = str_replace("’", "''", $resprojet[0]['titre']);
$titreProjet = str_replace("''", "'", utf8_decode($titreproj));
$originaldatedemande = $resprojet[0]['dateprojet'];
$originalDatedemande = date("d-M-Y", strtotime($originaldatedemande));
$originalDate = $resprojet[0]['datedebutprojet'];
$originaldate = date("d-M-Y", strtotime($originalDate));
$Acronyme = str_replace("’", "''", $resprojet[0]['acronyme']);
$acronyme = str_replace("''", "'", utf8_decode(stripslashes(trim($Acronyme))));
$dureeprojet = $resprojet[0]['dureeprojet'];
if ($lang == 'fr') {
    $unite = $manager->getSingle2("select libelleperiodicite from period where idperiodicite=?", $resprojet[0]['idperiodicite_periodicite']);
} elseif ($lang == 'en') {
    $unite = $manager->getSingle2("select libelleperiodiciteen from period where idperiodicite=?", $resprojet[0]['idperiodicite_periodicite']);
}
if (empty($resprojet[0]['confidentiel'])) {
    $confidentiel = 'Non';
} else {
    $confidentiel = 'Oui';
}
$attachement = $resprojet[0]['attachement'];
$attachementdesc = $resprojet[0]['attachementdesc'];
$numero = $resprojet[0]['numero'];
$commentaires = str_replace("’", "''", $manager->getSingle2("select commentaireprojet from concerne where idprojet_projet=?", $idprojet));
$comment = str_replace("''", "'", utf8_decode(stripslashes(trim($commentaires))));
$contactCentralAccueil = str_replace("’", "''", $resprojet[0]['contactscentraleaccueil']);
$contactcentralAccueil = str_replace("''", "'", $contactCentralAccueil);
$contactcentralaccueil = stripslashes(trim($contactCentralAccueil));
$datedebuttravaux = date("d-M-Y", strtotime($resprojet[0]['datedebuttravaux']));

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//         
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->SetFont('Times', '', 12);
$pdf->WriteHTML('<b>' . TXT_TITREPROJET . ': ' . '</b>');
$pdf->WriteHTML($titreProjet . '.  ');
$pdf->ln();
if (!empty($acronyme)) {
    $pdf->WriteHTML(utf8_decode('<b>' . TXT_ACRONYME . ' :</b> '));
    $pdf->WriteHTML($acronyme);
    $pdf->ln();
}
$pdf->WriteHTML(utf8_decode('<b>' . TXT_NUMROPROJET . ' :</b> '));
$pdf->WriteHTML($numero);
$pdf->ln();
$pdf->WriteHTML(utf8_decode('<b>' . TXT_PROJETCONFIDENTIEL . ' :</b> '));
$pdf->WriteHTML($confidentiel);
$pdf->ln();
if (!empty($attachement)) {
    $pdf->WriteHTML(utf8_decode('<b>' .TXT_PIECEJOINTE. ' :</b> '));
    $pdf->WriteHTML($attachement);
    $pdf->ln();
}
$idstatutprojet = $manager->getSingle2("select idstatutprojet_statutprojet from concerne where idprojet_projet=?", $idprojet);
$libelleStatut = utf8_decode($manager->getSingle2("select libellestatutprojet from statutprojet where idstatutprojet=?", $idstatutprojet));
//Récupération du type de projet
$idtypeprojet = $manager->getSingle2("select idtypeprojet_typeprojet from projet where idprojet=?", $idprojet);
$idna = $manager->getSingle2("select idtypeprojet from typeprojet where libelletype=?", 'n/a');
if ($lang == 'fr') {
    $libelleType = utf8_decode($manager->getSingle2("select libelletype from typeprojet where idtypeprojet=?", $idtypeprojet));
} elseif ($lang == 'en') {
    $libelleType = utf8_decode($manager->getSingle2("select libelletypeen from typeprojet where idtypeprojet=?", $idtypeprojet));
}
$pdf->WriteHTML(utf8_decode('<b>' . TXT_STATUTPROJETS . ' :</b> '));
$pdf->WriteHTML(str_replace("''", "'",$libelleStatut));
$pdf->ln();


if (!empty($resprojet[0]['refinterneprojet'])) {
    $Refinterneprojet = str_replace("’", "''", $resprojet [0]['refinterneprojet']);
    $refinterneprojet = str_replace("''", "'", utf8_decode($Refinterneprojet));
    $pdf->WriteHTML('<b>' . utf8_decode(TXT_REFINTERNE) . ' : </b>');
    $pdf->WriteHTML(stripslashes($refinterneprojet));
    $pdf->ln();
}

if (!empty($comment)) {
    $pdf->WriteHTML(utf8_decode('<b>' . TXT_COMMENTAIREPROJET . ' :</b> '));
    $pdf->WriteHTML($comment);
    $pdf->ln();
}
if (!empty($contactcentralaccueil)) {
    $pdf->WriteHTML(utf8_decode('<b>' . TXT_CONTACTCENTRALACCUEIL . ' :</b> '));
    $pdf->WriteHTML(utf8_decode($contactcentralaccueil));
    $pdf->ln();
}
if ($idtypeprojet != $idna) {
    $pdf->WriteHTML(utf8_decode('<b>' . TXT_TYPEPROJET . ' :</b> '));
    $pdf->WriteHTML($libelleType);
    $pdf->ln();
}
$pdf->ln();
$pdf->WriteHTML('<b>' . TXT_DEMANDEUR . '</b>');
$pdf->ln();
$rowdemandeur = $manager->getList2("SELECT nom, prenom,mail,telephone,adresse,codepostal,ville,nompays,nomresponsable,mailresponsable FROM utilisateur, projet, creer,loginpassword,pays WHERE idutilisateur_utilisateur = idutilisateur and idlogin=idlogin_loginpassword "
        . "AND idprojet_projet = idprojet and idprojet=? and idpays=idpays_pays", $idprojet);
$demandeur = TXT_NOM . ': ' . $rowdemandeur[0]['nom'] . '<br>' . TXT_PRENOM . ': ' . $rowdemandeur[0]['prenom'] . '<br>' . TXT_MAIL . ': ' . $rowdemandeur[0]['mail'] . ': ' . '<br>' . TXT_TELEPHONE . ': ' . $rowdemandeur[0]['telephone'] . '<br>' . '<br>';
$Demandeur = $rowdemandeur[0]['nom'] . ' -  ' . $rowdemandeur[0]['prenom']. ' -  ' .str_replace("''","'" ,$rowdemandeur[0]['adresse']). ' -  ' . $rowdemandeur[0]['codepostal']. ' -  ' . $rowdemandeur[0]['ville']. ' -  ' . $rowdemandeur[0]['nompays'];
$pdf->WriteHTML(utf8_decode($Demandeur));
$pdf->ln();
$pdf->ln();
if(!empty($rowdemandeur[0]['nomresponsable'])){
    $pdf->WriteHTML('<b>' . TXT_NOMMAILRESPON . '</b>');
    $sonresponsable = $rowdemandeur[0]['nomresponsable'] . ' -  ' . $rowdemandeur[0]['mailresponsable'];
    $pdf->ln();
    $pdf->WriteHTML(utf8_decode($sonresponsable));
    $pdf->ln();
    $pdf->ln();
}

$pdf->WriteHTML('<b>' . TXT_RESPONSABLESPROJET . '</b>');
$pdf->ln();
$arrayporteur = $manager->getList2("SELECT nom,prenom,mail,telephone FROM utilisateurporteurprojet,utilisateur,loginpassword WHERE idutilisateur_utilisateur = idutilisateur and idlogin= idlogin_loginpassword
and idprojet_projet=?", $idprojet);
$sPorteur = '';
for ($i = 0; $i < count($arrayporteur); $i++) {
    $sPorteur.=TXT_NOM . ': ' . $arrayporteur[$i]['nom'] . '<br>' . TXT_PRENOM . ': ' . $arrayporteur[$i]['prenom'] . '<br>' . TXT_MAIL . ': ' . $arrayporteur[$i]['mail'] . ': ' . '<br>' . TXT_TELEPHONE . ': ' . $arrayporteur[$i]['telephone'] . '<br>' . '<br>';
}
$sPorteur = $demandeur . substr($sPorteur, 0, -1);

if(!empty($arrayporteur)){
    $sporteur = substr($sPorteur, 0, -3);
}else{
    $sporteur = substr($sPorteur, 0, -4);
}

$pdf->WriteHTML(utf8_decode($sporteur));
$pdf->ln();
$pdf->ln();
$arraypersonneacceuil = $manager->getList2("SELECT nomaccueilcentrale,prenomaccueilcentrale
FROM projetpersonneaccueilcentrale,personneaccueilcentrale,qualitedemandeuraca WHERE idpersonneaccueilcentrale_personneaccueilcentrale = idpersonneaccueilcentrale AND idqualitedemandeuraca_qualitedemandeuraca = idqualitedemandeuraca
and idprojet_projet=?", $idprojet);
if (count($arraypersonneacceuil) > 0) {
    $pdf->WriteHTML('<b>' . TXT_PERSONNECENTRALE . '</b> :');
    $pdf->WriteHTML('  ');
    $pdf->ln();
}

$personneaccueil = '';
for ($i = 0; $i < count($arraypersonneacceuil); $i++) {
    $personneaccueil.=$arraypersonneacceuil[$i]['nomaccueilcentrale'] . '  ' . $arraypersonneacceuil[$i]['prenomaccueilcentrale'] . ',';
}
if (count($arraypersonneacceuil) > 0) {
    $pdf->WriteHTML(substr(utf8_decode($personneaccueil), 0, -1));
    $pdf->ln();
    $pdf->ln();
}
if (!empty($resprojet[0]['datedebutprojet'])) {
    $pdf->WriteHTML(utf8_decode('<b>' . TXT_DATEDEBUTPROJET . ' :</b> '));
    $pdf->WriteHTML($originaldate);
    $pdf->ln();
} else {
    $pdf->WriteHTML(utf8_decode('<b>' . TXT_DATEDEMANDE . ' :</b> '));
    $pdf->WriteHTML($originalDatedemande);
    $pdf->ln();
}

if (!empty($resprojet[0]['datedebuttravaux'])) {
    $pdf->WriteHTML(utf8_decode('<b>' . TXT_DATEDEBUTTRAVAUX . ' :</b> '));
    $pdf->WriteHTML($datedebuttravaux);
    $pdf->ln();
}
$pdf->ln();
$pdf->WriteHTML('<b>' . utf8_decode(TXT_DUREEPROJET . ' :</b> '));
$pdf->WriteHTML($dureeprojet . ' ');
$pdf->WriteHTML(utf8_decode($unite));
$pdf->ln();
$pdf->ln();
if (!empty($resprojet[0]['contexte'])) {
    $arrayCar = array('à','û','–','è');
    $arrayCarcorrige = array('à','û','-','è');
    $tabCar = array("\r");
    $contexte0 = filterEditor(stripTaggsbr(str_replace("é", "é",$resprojet[0]['contexte'])));
    $contexte1 = str_replace("ç","ç",$contexte0);
    $contexte2 = str_replace($tabCar, array(), $contexte1);
    $contexte3 = ltrim(rtrim(str_replace(array(chr(13)),  '', $contexte2)));
    $contexte4 = str_replace($arrayCar, $arrayCarcorrige, $contexte3);
    $contexte5 = str_replace('€', utf8_encode(chr(128)), $contexte4);
    $contexte6 =  utf8_decode(stripslashes(str_replace("''", "'",$contexte5)));
    $contexte =  str_replace('&quot;', '"',$contexte6);
    
    
    $pdf->WriteHTML('<b>' . TXT_DESCRIPTIONSOMMAIRE . '</b> :');
    $pdf->SetFont('Times', '', 11);
    $pdf->WriteHTML('<i>(' . TXT_OBJECTIF . '/' . TXT_CONTEXTESCIENTIFIQUE . ')</i>');
    $pdf->SetFont('Times', '', 12);
    $pdf->ln();
    $pdf->WriteHTML(stripslashes(trim(($contexte))));
    $pdf->ln();
}
$pdf->ln();
if (!empty($resprojet[0]['description'])) {
    
    $arrayCar = array('à','û','–','è');
    $arrayCarcorrige = array('à','û','-','è');
    $tabCar = array("\r");
    $description0 = stripTaggsbr(str_replace("é", "é",$resprojet[0]['description']));
    $description1 = str_replace("ç","ç",$description0);
    $description2 = str_replace($tabCar, array(), $description1);
    $description3 = ltrim(rtrim(str_replace(array(chr(13)),  '', $description2)));
    $description4 = str_replace($arrayCar, $arrayCarcorrige, $description3);
    $description5 = str_replace('€', utf8_encode(chr(128)), $description4);
    $description6 =  utf8_decode(stripslashes(str_replace("''", "'",$description5)));
    $description =  str_replace('&quot;', '"',$description6);
    
//    $description0 = filterEditor($resprojet[0]['description']);
//    $description1 = str_replace("’", "''", $description0);
//    $description2=  htmlspecialchars_decode($description1);
    //$description = str_replace("''", "'", utf8_decode($description6));    
    $pdf->WriteHTML('<b>' . utf8_decode(TXT_DESCRIPTIFTRAVAIL) . '</b> :');
    $pdf->ln();
    $pdf->WriteHTML(stripslashes(trim($description)));    
}
$pdf->ln();


//
$pdf->WriteHTML('<b>' . utf8_decode(TXT_USEDTECHNO) . ':</b>');
$nbressource = $manager->getSingle2("select count(idressource_ressource) from ressourceprojet where idprojet_projet=?", $idprojet);
$i = 0;
$pdf->ln();
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
        $slibelleressource .=utf8_decode($libelleressource) . ' , ';
    }
    $i++;
}
$pdf->WriteHTML(stripslashes($slibelleressource));
$pdf->ln();
$pdf->ln();

$partenaireprojet = $manager->getList2("SELECT nompartenaire,nomlaboentreprise FROM projet,projetpartenaire,partenaireprojet WHERE  idprojet_projet = projet.idprojet AND
  idpartenaire = idpartenaire_partenaireprojet  and idprojet_projet=?", $idprojet);
$pdf->WriteHTML('<b>' . TXT_AUTREPARTENAIRE . '</b>');
$pdf->ln();
$S_nompartenaire = '';
if (!empty($resprojet[0]['centralepartenaireprojet'])) {
    $Centralepartenaireprojet = str_replace("’", "''", $resprojet [0]['centralepartenaireprojet']);
    $centralepartenaireprojet = str_replace("''", "'", utf8_decode($Centralepartenaireprojet));
}
for ($i = 0; $i < count($partenaireprojet); $i++) {
    if (!empty($partenaireprojet[$i]['nompartenaire'])) {
        $nomPartenaire = str_replace("’", "''", $partenaireprojet [$i]['nompartenaire']);
        $nompartenaire = str_replace("''", "'", utf8_decode($nomPartenaire));
        $S_nompartenaire.=$nompartenaire . ', ';
    }
}
$s_nompartenaire = substr(trim($S_nompartenaire), 0, -1);

$S_nomlaboentreprise = '';
for ($i = 0; $i < count($partenaireprojet); $i++) {
    if (!empty($partenaireprojet[$i]['nomlaboentreprise'])) {
        $nomLaboentreprise = str_replace("’", "''", $partenaireprojet [$i]['nomlaboentreprise']);
        $nomlaboentreprise = str_replace("''", "'", utf8_decode($nomLaboentreprise));
        $S_nomlaboentreprise.=$nomlaboentreprise . ', ';
    }
}
$s_nomlaboentreprise = substr(trim($S_nomlaboentreprise), 0, -1);
if (!empty($centralepartenaireprojet)) {
    $pdf->WriteHTML(stripslashes($centralepartenaireprojet) . ', ' . stripslashes($s_nomlaboentreprise));
} else {
    $pdf->WriteHTML(stripslashes($s_nomlaboentreprise));
}

if (!empty($resprojet[0]['partenaire1'])) {
    $Partenaire1 = str_replace("’", "''", $resprojet [0]['partenaire1']);
    $partenaire1 = str_replace("''", "'", utf8_decode($Partenaire1));
}
$pdf->ln();
if (!empty($partenaire1)) {
    $partenaire = stripslashes(trim($partenaire1)) . ', ' . stripslashes($s_nompartenaire);
} else {
    $partenaire = stripslashes($s_nompartenaire);
}
$pdf->WriteHTML($partenaire);
$pdf->ln();
$pdf->ln();
$pdf->WriteHTML('<b>' . utf8_decode(TXT_FINANCEMENTPROJET) . ': </b>');
$pdf->ln();
$arraySourcefinancement = $manager->getList2("SELECT idsourcefinancement,libellesourcefinancement,libellesourcefinancementen,acronymesource FROM   projetsourcefinancement,sourcefinancement
WHERE idsourcefinancement_sourcefinancement = idsourcefinancement  and idprojet_projet=?", $idprojet);
$S_sourcefinancement = '';
$S_acrosourcefinancement = '';
for ($i = 0; $i < count($arraySourcefinancement); $i++) {
    if (!empty($arraySourcefinancement[$i]['idsourcefinancement'])) {
        if ($lang == 'fr') {
            $Sourcefinancement = str_replace("’", "''", $arraySourcefinancement[$i]['libellesourcefinancement']);
            $sourcefinancement = str_replace("''", "'", $Sourcefinancement);
            $S_sourcefinancement.=$sourcefinancement . ', ';
        } elseif ($lang == 'en') {
            $Sourcefinancement = str_replace("’", "''", $arraySourcefinancement[$i]['libellesourcefinancementen']);
            $sourcefinancement = str_replace("''", "'", $Sourcefinancement);
            $S_sourcefinancement.=$sourcefinancement . ', ';
        }
    }
    if (!empty($arraySourcefinancement[$i]['acronymesource'])) {
        $acroSource = str_replace("’", "''", $arraySourcefinancement[$i]['acronymesource']);
        $acrosource = str_replace("''", "'", $acroSource);
        $S_acrosourcefinancement.=$acrosource . ', ';
    }
}
$s_sourcefinancement = substr(trim(utf8_decode($S_sourcefinancement)), 0, -1);
$s_acrosourcefinancement = substr(trim(utf8_decode($S_acrosourcefinancement)), 0, -1);
$pdf->WriteHTML(stripslashes($s_sourcefinancement));
$pdf->ln();
$pdf->ln();
if (!empty($s_acrosourcefinancement)) {
    $pdf->WriteHTML('<b>' . utf8_decode(TXT_ACROSOURCECFINANCEMENT) . ': </b>');
    $pdf->ln();
    $pdf->WriteHTML(stripslashes($s_acrosourcefinancement));
    $pdf->ln();
    $pdf->ln();
}
$libelleThematique = $manager->getSingle2("select libellethematique from thematique where idthematique=?", $resprojet[0]['idthematique_thematique']);

if (!empty($libelleThematique) && $libelleThematique != 'Autres') {
    $Libellethematique = str_replace("’", "''", $libelleThematique);
    $libellethematique = str_replace("''", "'", utf8_decode($Libellethematique));
    $pdf->WriteHTML('<b>' . utf8_decode(stripslashes(TXT_THEMATIQUE) . '(s)') . ' :</b> ');
    $pdf->ln();
    $pdf->WriteHTML(stripslashes($libellethematique));
    $pdf->ln();
    $pdf->ln();
}
$pdf->ln();
if (!empty($resprojet[0]['descriptiftechnologique'])) {
    $descriptiftechnologique0 = filterEditor($resprojet[0]['descriptiftechnologique']);
    $descriptiftechnologique1 = str_replace("’", "''", $descriptiftechnologique0);
    $descriptiftechnologique2 = htmlspecialchars_decode($descriptiftechnologique1);
    $descriptiftechnologique = str_replace("''", "'", utf8_decode($descriptiftechnologique2));
    $pdf->WriteHTML('<b>' . TXT_DESCRIPTIFTECHNOLOGIQUE . '</b> :');
    $pdf->ln();
    $pdf->WriteHTML(stripslashes(trim($descriptiftechnologique)));
    $pdf->ln();
}
$pdf->ln();
$monUrl = "http://" . $_SERVER['HTTP_HOST'];
if (!empty($attachementdesc)) {
    $pdf->WriteHTML(utf8_decode('<b>' . TXT_AUTRE . ' ' . strtolower(TXT_PIECEJOINTE) . ' :</b> '));
    $pdf->ln();
    $pdf->WriteHTML('<a href="' . $monUrl . dirname($_SERVER['PHP_SELF']) . '/uploaddesc/' . $attachementdesc . '" target="_blank"  id="attachementdesc">' . $attachementdesc . '</a>');
    $pdf->ln();
}
$pdf->ln();
if (!empty($resprojet[0]['verrouidentifiee'])) {
    $verrouidentifiee0 = filterEditor($resprojet[0]['descriptiftechnologique']);
    $verrouidentifiee1 = str_replace("’", "''", $verrouidentifiee0);
    $verrouidentifiee2 = htmlspecialchars_decode($verrouidentifiee1);
    $verrouidentifiee = str_replace("''", "'", utf8_decode($verrouidentifiee2));
    $pdf->WriteHTML('<b>' . utf8_decode(TXT_VERROUIDENTIFIEE) . '</b> :');
    $pdf->ln();
    $pdf->WriteHTML(stripslashes($verrouidentifiee));
    $pdf->ln();
}
$pdf->ln();

if (!empty($resprojet[0]['nbplaque'])) {
    $pdf->WriteHTML('<b>' . utf8_decode(TXT_NBPLAQUE) . ': </b>');
    $pdf->WriteHTML(stripslashes($resprojet [0]['nbplaque']));
    $pdf->ln();
}
$pdf->ln();
if (!empty($resprojet[0]['nbrun'])) {
    $pdf->WriteHTML('<b>' . utf8_decode(TXT_NBRUN) . ': </b>');
    $pdf->WriteHTML(stripslashes($resprojet [0]['nbrun']));
    $pdf->ln();
}
$pdf->ln();
if (!empty($resprojet[0]['emailrespdevis'])) {
    $pdf->WriteHTML('<b>' . utf8_decode(TXT_EMAILRESPDEVIS) . ': </b>');
    $pdf->ln();
    $pdf->WriteHTML(stripslashes($resprojet [0]['emailrespdevis']));
    $pdf->ln();
}
$pdf->ln();
if (!empty($resprojet[0]['reussite'])) {
    $reussite0 = filterEditor($resprojet [0]['reussite']);
    $reussite1 = str_replace("’", "''", $reussite0);
    $reussite2 = htmlspecialchars_decode($reussite1);
    $reussite = str_replace("''", "'", utf8_decode($reussite2));
    $pdf->WriteHTML('<b>' . utf8_decode(TXT_REUSSITEESCOMPTE) . ' :</b>');
    $pdf->ln();
    $pdf->WriteHTML(stripslashes($reussite));
    $pdf->ln();
}
$pdf->Output($numero, 'I');
BD::deconnecter();
