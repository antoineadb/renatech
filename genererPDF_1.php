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
include_once 'outils/toolBox.php';
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    echo '<script>window.location.replace("erreurlogin.php")</script>';
}
//Récupération de l'idProjet
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

// Rapport sur le projet
//Connexion et requête
$resprojet = $manager->getListbyArray("select titre,dateprojet,acronyme,contexte,confidentiel,description,attachement,numero from projet where idprojet=?", array($idprojet));

$titreProjet = $resprojet[0]['titre'];
$originalDate = $resprojet[0]['dateprojet'];
$acronyme = $resprojet[0]['acronyme'];
if (empty($resprojet[0]['confidentiel'])) {
    $confidentiel = 'Non';
} else {
    $confidentiel = 'Oui';
}

$contexte = utf8_decode(strip_tags($resprojet[0]['contexte']));
//$description = utf8_decode(strip_tags($resprojet[0]['description']));
$description = utf8_decode($resprojet[0]['description']);
$attachement = $resprojet[0]['attachement'];
$numero = $resprojet[0]['numero'];
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(158, 5, utf8_decode(TXT_TITREDOCPDF), 0, 1, 'C', 0, '');
$pdf->SetFont('Arial', '', 6);
//$pdf->Cell(170, 5, '', 0, 1, 'L', 0, '');
//En-tête de la table
$pdf->Cell(34, 5, "Date", 1, 0, 'L', 1);
$pdf->Cell(162, 5, $originalDate, 1, 1, 'Date', '');
$pdf->Cell(34, 5, 'Titre du projet', 1, 0, 'L', 1);
$pdf->Cell(162, 5, utf8_decode($titreProjet), 1, 1, 'Titre', '');
$pdf->Cell(34, 5, "Acronyme", 1, 0, 'L', 1);
$pdf->Cell(162, 5, utf8_decode($acronyme), 1, 1, 'Acronyme', '');
$pdf->Cell(34, 5, "Projet  confidentiel", 1, 0, 'L', 1);
$pdf->Cell(162, 5, $confidentiel, 1, 1, 'confidentiel', '');

$pdf->Cell(34, 5, utf8_decode("Numéro"), 1, 0, 'L', 1);
$pdf->Cell(162, 5, $numero, 1, 1, utf8_decode('Numéro'), '');

if (!empty($attachement)) {
    $pdf->Cell(34, 5, utf8_decode("Pièce jointe"), 1, 0, 'L', 1);
    $pdf->Cell(162, 5, $attachement, 1, 1, 'Date', '');
}
//récupération du statut du projet
$idstatutprojet = $manager->getSingle2("select idstatutprojet_statutprojet from concerne where idprojet_projet=?", $idprojet);
$libelleStatut = utf8_decode($manager->getSingle2("select libellestatutprojet from statutprojet where idstatutprojet=?", $idstatutprojet));
$pdf->Cell(34, 5, "Statut du projet", 1, 0, 'L', 1);
$pdf->Cell(162, 5, $libelleStatut, 1, 1, 'Statut du projet', '');
//Récupération du type de projet
$idtypeprojet = $manager->getSingle2("select idtypeprojet_typeprojet from projet where idprojet=?", $idprojet);
$libelleType = $manager->getSingle2("select libelletype from typeprojet where idtypeprojet=?", $idtypeprojet);
if ($libelleType != 'n/a') {
    $pdf->Cell(34, 5, 'Type de projet', 1, 0, 'L', 1);
    $pdf->Cell(162, 5, ucfirst(strtolower(utf8_decode($libelleType))), 1, 1, 'Type de projet', '');
}
//récupération des centrales
$nbCentrale = $manager->getSingle2("select count(idcentrale_centrale) from concerne where idprojet_projet=?", $idprojet);
$i = 0;
$pdf->Cell(34, 5, utf8_decode("Centrale(s) sélectionnée(s)"), 1, 0, 'L', 1);
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
$pdf->Cell(162, 5, $slibellecentrale, 1, 1, 'libellecentrale');

//Formatage du cadre en fonction du nombre de ligne de manière à pouvoir afficher le libellé contexte
$t = 144;
$pdf->Cell(196, 5, 'Contexte', 1, 1, 'C', '');
for ($i = 0; $i <= 100; $i++) {
    if ((strlen($contexte) > $t * $i) && (strlen($contexte) <= $t * $i)) {
        $pdf->Cell(34, 5 + $i * 10, "Contexte", 1, 0, 'J', 1);
    }
}

$pdf->SetFillColor(255, 255, 255);
$pdf->MultiCell(196, 5, html_entity_decode(stripslashes(str_replace("?", "'", $contexte))), 1, 'J', 'Contexte', '');
$pdf->SetFillColor(234, 234, 234);
//Formatage du cadre en fonction du nombre de ligne de manière à pouvoir afficher le libellé Description
$pdf->Cell(196, 5, 'Description', 1, 1, 'C', '');
for ($i = 0; $i <= 100; $i++) {
    if ((strlen($description) > $t * $i) && (strlen($description) <= $t * $i)) {
       $pdf->Cell(34, 5 + $i * 10, "Description", 1, 0, 'J', 1);
    }
}

$pdf->SetFillColor(255, 255, 255);
$pdf->MultiCell(196, 5, html_entity_decode(stripslashes(str_replace("?", "'", $description))), 1, 'J', 'Description', '');
$pdf->SetFillColor(234, 234, 234);
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              TRAITEMENT DE LA DESCRIPTION
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->WriteHTML('<u>Description</u> :<br>');
$pdf->WriteHTML($description);

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//CAS COMMUM VOS INFORMATIONS
$pdf->Cell(158, 3, '', 0, 1, 'C', 0, '');
$pdf->Cell(158, 3, '', 0, 1, 'C', 0, '');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(158, 5, 'Vos Informations', 0, 1, 'C', 0, '');
//$pdf->Cell(158, 3, '', 0, 1, 'C', 0, '');
$pdf->SetFont('Arial', '', 6);
//DONNEES DEMANDEUR DU PROJET
$donneedemandeur = $manager->getListbyArray("SELECT nom, prenom	FROM creer, projet, utilisateur WHERE idprojet_projet = idprojet AND idutilisateur_utilisateur = idutilisateur and idprojet=?", array($idprojet));
$nomdemandeur = $donneedemandeur[0]['nom'];
$prenomdemandeur = $donneedemandeur[0]['prenom'];
if (!empty($nomdemandeur)) {
    $pdf->Cell(34, 5, utf8_decode("Nom/prénom du demandeur"), 1, 0, 'L', 1);
    $pdf->Cell(162, 5, utf8_decode($nomdemandeur) . '  ' . utf8_decode($prenomdemandeur), 1, 1, 'Nom/prénom du demandeur', '');
}


$resInfo = $manager->getListbyArray("SELECT nom,prenom,adresse,codepostal,ville,telephone,datecreation,idpays_pays FROM utilisateur,loginpassword WHERE idlogin = idlogin_loginpassword and pseudo =?", array($_SESSION['pseudo']));
$originalDatecreation = $resInfo[0]['datecreation'];
$datecreation = date("d-M-Y", strtotime($originalDatecreation));
$nom = $resInfo[0]['nom'];
$prenom = $resInfo[0]['prenom'];
$adresse = $resInfo[0]['adresse'];
$cp = $resInfo[0]['codepostal'];
$ville = utf8_decode($resInfo[0]['ville']);
$tel = $resInfo[0]['telephone'];
if (isset($_SESSION['email'])) {
    $mail = $_SESSION['email'];
} else {
    $mail = $_SESSION['mail'];
}
$idpays = $resInfo[0]['idpays_pays'];
$respays = $manager->getListbyArray("select nompays from pays where idpays=?", array($idpays));
$nompays = $respays[0]['nompays'];
$pdf->Cell(34, 5, utf8_decode("Date de création"), 1, 0, 'L', 1);
$pdf->Cell(162, 5, $datecreation, 1, 1, 'datecreation', '');
$pdf->Cell(34, 5, "Nom", 1, 0, 'L', 1);
$pdf->Cell(162, 5, utf8_decode($nom), 1, 1, 'nom', '');
$pdf->Cell(34, 5, utf8_decode("Prénom"), 1, 0, 'L', 1);
$pdf->Cell(162, 5, utf8_decode($prenom), 1, 1, 'nom', '');
$pdf->Cell(34, 5, "Adresse", 1, 0, 'L', 1);
$pdf->Cell(162, 5, utf8_decode($adresse), 1, 1, 'adresse', '');
$pdf->Cell(34, 5, "Code postale", 1, 0, 'L', 1);
$pdf->Cell(162, 5, $cp, 1, 1, 'cp', '');
$pdf->Cell(34, 5, "Ville", 1, 0, 'L', 1);
$pdf->Cell(162, 5, $ville, 1, 1, 'ville', '');
$pdf->Cell(34, 5, "Pays", 1, 0, 'L', 1);
$pdf->Cell(162, 5, $nompays, 1, 1, 'nompays', '');
$pdf->Cell(34, 5, utf8_decode("Téléphone"), 1, 0, 'L', 1);
$pdf->Cell(162, 5, $tel, 1, 1, 'telephone', '');
$pdf->Cell(34, 5, "E-mail", 1, 0, 'L', 1);
$pdf->Cell(162, 5, $mail, 1, 1, 'email', '');

$idutilisateur = $manager->getSingle2("SELECT idutilisateur FROM utilisateur,loginpassword  WHERE idlogin = idlogin_loginpassword and pseudo =?", $_SESSION['pseudo']);
$idtypeUser = $manager->getSingle2("select idtypeutilisateur_typeutilisateur from utilisateur where idutilisateur=?", $idutilisateur);
$libelleTypeUser = $manager->getSingle2("select libelletype from typeutilisateur where idtypeutilisateur = ?", $idtypeUser);

$pdf->Cell(34, 5, utf8_decode("Vous êtes connecté comme "), 1, 0, 'L', 1);
$pdf->Cell(162, 5, $libelleTypeUser, 1, 1, 'libelletype', '');
//traitement des particularité de cas académiques et industriel
$resUtilisateurType = $manager->getListbyArray("select idqualitedemandeuraca_qualitedemandeuraca,idqualitedemandeurindust_qualitedemandeurindust from utilisateur where idutilisateur=?", array($idutilisateur));
$idqualiteaca = $resUtilisateurType[0]['idqualitedemandeuraca_qualitedemandeuraca'];
$idqualiteindust = $resUtilisateurType[0]['idqualitedemandeurindust_qualitedemandeurindust'];
if (!empty($idqualiteaca)) {
    $resInfoaca = $manager->getListbyArray("select iddiscipline_disciplinescientifique,idautrediscipline_autredisciplinescientifique,idemployeur_nomemployeur,
    idautrenomemployeur_autrenomemployeur,idtutelle_tutelle,idautrestutelle_autrestutelle,idautrecodeunite_autrecodeunite from utilisateur where idutilisateur=?", array($idutilisateur));
    //on est académique
    $iddiscipline = $resInfoaca[0]['iddiscipline_disciplinescientifique'];
    $idautrediscipline = $resInfoaca[0]['idautrediscipline_autredisciplinescientifique'];
    $idemployeur_nomemployeur = $resInfoaca[0]['idemployeur_nomemployeur'];
    $idautrenomemployeur_autrenomemployeur = $resInfoaca[0]['idautrenomemployeur_autrenomemployeur'];

    $idtutelle_tutelle = $resInfoaca[0]['idtutelle_tutelle'];
    $idautrestutelle_autrestutelle = $resInfoaca[0]['idautrestutelle_autrestutelle'];
    // centrale de rattachement
    $idcentrale = $manager->getSingle2("select idcentrale_centrale from utilisateur where idutilisateur=?", $idutilisateur);

    if (!empty($idcentrale)) {
        $rescodeunite = $manager->getListbyArray("select codeunite,libellecentrale,villecentrale from centrale where idcentrale =?", array($idcentrale));
        $codeunite = $rescodeunite[0]['codeunite'];
        $libellecentrale = $rescodeunite[0]['libellecentrale'];
        $villecentrale = utf8_decode($rescodeunite[0]['villecentrale']);
    }
    $disciplineScientifique = $manager->getSingle2("select libellediscipline from disciplinescientifique where iddiscipline =?", $iddiscipline);
    $autrediscipline = $manager->getSingle2("select libelleautrediscipline from autredisciplinescientifique where idautrediscipline =?", $idautrediscipline);
    $employeur = $manager->getSingle2("select libelleemployeur from nomemployeur where idemployeur =?", $idemployeur_nomemployeur);
    $autreemployeur = $manager->getSingle2("select libelleautrenomemployeur from autrenomemployeur where idautrenomemployeur =?", $idautrenomemployeur_autrenomemployeur);
    $tutelle = $manager->getSingle2("select libelletutelle from tutelle where idtutelle =?", $idtutelle_tutelle);
    $autretutelle = $manager->getSingle2("select libelleautrestutelle from autrestutelle where idautrestutelle =?", $idautrestutelle_autrestutelle);
    if ($disciplineScientifique != 'Autres') {
        $pdf->Cell(34, 5, "Discipline scientifique", 1, 0, 'L', 1);
        $pdf->Cell(162, 5, utf8_decode($disciplineScientifique), 1, 1, 'disciplineScientifique', '');
    }
    if ($idautrediscipline != 1) {
        $pdf->Cell(34, 5, "Autres discipline scientifique", 1, 0, 'L', 1);
        $pdf->Cell(162, 5, utf8_decode($autrediscipline), 1, 1, 'libelleautrediscipline', '');
    }
    if ($employeur != 'Autres') {
        $pdf->Cell(34, 5, "Employeur", 1, 0, 'L', 1);
        $pdf->Cell(162, 5, $employeur, 1, 1, 'libelleemployeur', '');
    }
    if ($idautrenomemployeur_autrenomemployeur != 1) {
        $pdf->Cell(34, 5, "Autre employeur", 1, 0, 'L', 1);
        $pdf->Cell(162, 5, $autreemployeur, 1, 1, 'libelleautrenomemployeur', '');
    }
    if ($tutelle != 'Autres') {
        $pdf->Cell(34, 5, "Tutelle", 1, 0, 'L', 1);
        $pdf->Cell(162, 5, utf8_decode($tutelle), 1, 1, 'libelletutelle', '');
    }

    if ($idautrestutelle_autrestutelle != 1) {
        $pdf->Cell(34, 5, "Autre tutelle", 1, 0, 'L', 1);
        $pdf->Cell(162, 5, $autretutelle, 1, 1, 'libelleautrestutelle', '');
    }
    if (!empty($idcentrale)) {
        $pdf->Cell(34, 5, utf8_decode("Code unité"), 1, 0, 'L', 1);
        $pdf->Cell(162, 5, $codeunite, 1, 1, 'codeunite', '');
        $pdf->Cell(34, 5, "Centrale de rattachement", 1, 0, 'L', 1);
        $pdf->Cell(162, 5, $libellecentrale . ' - ' . $villecentrale, 1, 1, 'codeunite', '');
    }
} elseif (!empty($idqualiteindust)) {
    $resinfoindus = $manager->getListbyArray("select nomresponsable,mailresponsable,nomentreprise from utilisateur where idutilisateur=?", array($_SESSION['idutilisateur']));
    $nomresponsable = $resinfoindus[0]['nomresponsable'];
    $mailresponsable = $resinfoindus[0]['mailresponsable'];
    $nomentreprise = $resinfoindus[0]['nomentreprise'];
    $ididtypeentreprise_typeentreprise = $manager->getSingle2("select idtypeentreprise_typeentreprise from appartient where idutilisateur_utilisateur=?", $_SESSION['idutilisateur']);
    $idsecteuractivite_secteuractivite = $manager->getSingle2("select idsecteuractivite_secteuractivite from intervient where idutilisateur_utilisateur=?", $_SESSION['idutilisateur']);
    $libellesecteuractivite = $manager->getSingle2("select libellesecteuractivite from secteuractivite where idsecteuractivite =?", $idsecteuractivite_secteuractivite);
    $libelletypeentreprise = $manager->getSingle2("select libelletypeentreprise from typeentreprise where idtypeentreprise=?", $ididtypeentreprise_typeentreprise);
    if (!empty($nomresponsable)) {
        $pdf->Cell(34, 5, "Nom du responsable", 1, 0, 'L', 1);
        $pdf->Cell(162, 5, $nomresponsable, 1, 1, 'nomresponsable', '');
    }
    if (!empty($mailresponsable)) {
        $pdf->Cell(34, 5, "E-mail du responsable", 1, 0, 'L', 1);
        $pdf->Cell(162, 5, $mailresponsable, 1, 1, 'mailresponsable', '');
    }
    $pdf->Cell(34, 5, "Nom de l'entreprise", 1, 0, 'L', 1);
    $pdf->Cell(162, 5, $nomentreprise, 1, 1, 'nomentreprise', '');
    $pdf->Cell(34, 5, "Type d'entreprise", 1, 0, 'L', 1);
    $pdf->Cell(162, 5, $libelletypeentreprise, 1, 1, 'libelletypeentreprise', '');
    $pdf->Cell(34, 5, utf8_decode("Secteur d'activité"), 1, 0, 'L', 1);
    $pdf->Cell(162, 5, utf8_decode($libellesecteuractivite), 1, 1, 'libellesecteuractivite', '');
}
//nom du fichier de sauvegarde
$pdf->Output($numero, 'I');
BD::deconnecter();

?>
