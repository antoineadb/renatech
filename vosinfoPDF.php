<?php

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              TRAITEMENT DE VOS INFORMATIONS
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->Ln();
$pdf->Ln();
$pdf->SetFont('Arial', '', 12);
$pdf->WriteHTML('<b><u>' . utf8_decode(TXT_VOSINFO) . ' :</u></b><br>');
$pdf->Ln();
$pdf->SetFont('Arial', '', 6);
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              TRAITEMENT NOM ET PRENOM DEMANDEUR DU PROJET
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
$donneedemandeur =$manager->getList2("SELECT nom,mail,prenom,codepostal,entrepriselaboratoire,acronymelaboratoire,adresse,idpays_pays,idtypeutilisateur_typeutilisateur,iddiscipline_disciplinescientifique,idautrediscipline_autredisciplinescientifique,nomentreprise,mailresponsable,nomresponsable,telephone,datecreation,ville,
idtutelle_tutelle,idemployeur_nomemployeur,idautrestutelle_autrestutelle,idqualitedemandeuraca_qualitedemandeuraca,idqualitedemandeurindust_qualitedemandeurindust,idautrecodeunite_autrecodeunite,entrepriselaboratoire,acronymelaboratoire,idutilisateur_utilisateur,idautrenomemployeur_autrenomemployeur
FROM utilisateur,loginpassword,centrale,creer WHERE idlogin = idlogin_loginpassword AND idutilisateur_utilisateur = idutilisateur and idprojet_projet=?", $idprojet);
//echo '<pre>';print_r($donneedemandeur);echo '</pre>';/*
$nomdemandeur = $donneedemandeur[0]['nom'];
$prenomdemandeur = $donneedemandeur[0]['prenom'];
$idutilisateur = $donneedemandeur[0]['idutilisateur_utilisateur']; 
if (!empty($donneedemandeur[0]['acronymelaboratoire'])) {
    $acronymelaboratoire = $donneedemandeur[0]['acronymelaboratoire'];
} else {
    $acronymelaboratoire = '';
}
if (!empty($donneedemandeur[0]['entrepriselaboratoire'])) {
    $entrepriselaboratoire = $donneedemandeur[0]['entrepriselaboratoire'];
} else {
    $entrepriselaboratoire = '';
}
if (!empty($nomdemandeur)) {
    $pdf->WriteHTML(utf8_decode('<b>' . TXT_DEMANDEUR . ':</b>  '));
    $pdf->WriteHTML(utf8_decode($nomdemandeur) . '  ' . utf8_decode($prenomdemandeur) . '<br>');
}

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              TRAITEMENT DATE DE CREATION
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
$datecreation = date("d-M-Y", strtotime($donneedemandeur[0]['datecreation']));
$pdf->WriteHTML(utf8_decode('<b>' . TXT_CREATEDATE . ':</b>  '));
$pdf->WriteHTML(utf8_decode($datecreation) . '<br>');
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              TRAITEMENT NOM PRENOM ADRESSE VILLE CODE POSTAL TELEPHONE COURRIEL TYPE CONNEXION
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
$pdf->WriteHTML(utf8_decode('<b>' . TXT_NOM . ':</b>  '));
$pdf->WriteHTML(utf8_decode($donneedemandeur[0]['nom']) . '<br>');

$pdf->WriteHTML(utf8_decode('<b>' . TXT_PRENOM . ':</b>  '));
$pdf->WriteHTML(utf8_decode($donneedemandeur[0]['prenom']) . '<br>');

$Adresse = str_replace("’", "''", $donneedemandeur[0]['adresse']);
$adresse = str_replace("''", "'", $Adresse);
$pdf->WriteHTML(utf8_decode('<b>' . TXT_ADRESSE . ':</b>  '));
$pdf->WriteHTML(utf8_decode($adresse) . '<br>');

$pdf->WriteHTML(utf8_decode('<b>' . TXT_CP . ':</b>  '));
$pdf->WriteHTML(utf8_decode($donneedemandeur[0]['codepostal']) . '<br>');

$Ville = str_replace("’", "''", $donneedemandeur[0]['ville']);
$ville = str_replace("''", "'", utf8_decode($Ville));
$pdf->WriteHTML(utf8_decode('<b>' . TXT_VILLE . ':</b>  '));
$pdf->WriteHTML(utf8_decode($ville) . '<br>');

$pdf->WriteHTML(utf8_decode('<b>' . TXT_PAYS . ':</b>  '));
$nompays = $manager->getSingle2("select nompays from pays where idpays=?", $donneedemandeur[0]['idpays_pays']);
$pdf->WriteHTML(utf8_decode($nompays) . '<br>');

$pdf->WriteHTML(utf8_decode('<b>' . TXT_TELEPHONE . ':</b>  '));
$pdf->WriteHTML(utf8_decode($donneedemandeur[0]['telephone']) . '<br>');

$pdf->WriteHTML(utf8_decode('<b>' . TXT_EMAIL . ':</b>  '));
$pdf->WriteHTML(utf8_decode($donneedemandeur[0]['mail']) . '<br>');
$libelleTypeUser = $manager->getSingle2("select libelletype from typeutilisateur where idtypeutilisateur = ?", $donneedemandeur[0]['idtypeutilisateur_typeutilisateur']);

$pdf->WriteHTML(utf8_decode('<b>' . TXT_CONNECT . ':</b>  '));
$pdf->WriteHTML(utf8_decode($libelleTypeUser) . '<br>');

$pdf->WriteHTML(utf8_decode('<b>' . TXT_NOMENTELABO . ' :</b> '));
$pdf->WriteHTML($entrepriselaboratoire . '<br>');

$pdf->WriteHTML(utf8_decode('<b>' . TXT_NOMLABO . ' :</b> '));
$pdf->WriteHTML($acronymelaboratoire . '<br>');
//traitement des particularité de cas académiques et industriel
$idqualiteaca = $donneedemandeur[0]['idqualitedemandeuraca_qualitedemandeuraca'];
$idqualiteindust = $donneedemandeur[0]['idqualitedemandeurindust_qualitedemandeurindust'];

if (!empty($idqualiteaca)) {
    //on est académique
    $iddiscipline = $donneedemandeur[0]['iddiscipline_disciplinescientifique'];
    $idautrediscipline = $donneedemandeur[0]['idautrediscipline_autredisciplinescientifique'];
    $idemployeur_nomemployeur = $donneedemandeur[0]['idemployeur_nomemployeur'];
    $idautrenomemployeur_autrenomemployeur = $donneedemandeur[0]['idautrenomemployeur_autrenomemployeur'];

    $idtutelle_tutelle = $donneedemandeur[0]['idtutelle_tutelle'];
    $idautrestutelle_autrestutelle = $donneedemandeur[0]['idautrestutelle_autrestutelle'];
    // centrale de rattachement
    $idcentrale = $manager->getSingle2("select idcentrale_centrale from utilisateur where idutilisateur=?", $idutilisateur);

    if (!empty($idcentrale)) {
        $rescodeunite = $manager->getListbyArray("select codeunite,libellecentrale,villecentrale from centrale where idcentrale =?", array($idcentrale));
        $codeunite = $rescodeunite[0]['codeunite'];
        $libellecentrale = $rescodeunite[0]['libellecentrale'];
    }
    $disciplineScientifique = $manager->getSingle2("select libellediscipline from disciplinescientifique where iddiscipline =?", $iddiscipline);
    $autrediscipline = $manager->getSingle2("select libelleautrediscipline from autredisciplinescientifique where idautrediscipline =?", $idautrediscipline);
    $employeur =str_replace("''", "'", utf8_decode($manager->getSingle2("select libelleemployeur from nomemployeur where idemployeur =?", $idemployeur_nomemployeur)));
    $autreemployeur = $manager->getSingle2("select libelleautrenomemployeur from autrenomemployeur where idautrenomemployeur =?", $idautrenomemployeur_autrenomemployeur);
    $tutelle =str_replace("''", "'", utf8_decode($manager->getSingle2("select libelletutelle from tutelle where idtutelle =?", $idtutelle_tutelle)));
    $autretutelle = $manager->getSingle2("select libelleautrestutelle from autrestutelle where idautrestutelle =?", $idautrestutelle_autrestutelle);
    if ($disciplineScientifique != 'Autres') {
        $DisciplineScientifique = str_replace("’", "''", $disciplineScientifique);
        $discipline = str_replace("''", "'", utf8_decode($DisciplineScientifique));
        $pdf->WriteHTML(utf8_decode('<b>' . TXT_DISCIPLINESCIENTIFIQUE . ' :</b> '));
        $pdf->WriteHTML($discipline . '<br>');
    }
    if ($idautrediscipline != 1) {
        $AutredisciplineScientifique = str_replace("’", "''", $autrediscipline);
        $autrediscipline = str_replace("''", "'", utf8_decode($AutredisciplineScientifique));
        $pdf->WriteHTML(utf8_decode('<b>' . TXT_AUTREDISCIPLINE . ' :</b> '));
        $pdf->WriteHTML(stripslashes($autrediscipline) . '<br>');
    }
    if ($employeur != 'Autres') {
        $pdf->WriteHTML(utf8_decode('<b>' . TXT_NOMEMPLOYEUR . ' :</b> '));
        $pdf->WriteHTML($employeur . '<br>');
    }
    if ($idautrenomemployeur_autrenomemployeur != 1) {
        $pdf->WriteHTML(utf8_decode('<b>' . TXT_AUTREEMPLOYEUR . ' :</b> '));
        $pdf->WriteHTML(stripslashes(str_replace("''", "'", utf8_decode($autreemployeur))) . '<br>');
    }
    if ($tutelle != 'Autres') {
        $pdf->WriteHTML(utf8_decode('<b>' . TXT_TUTELLE . ' :</b> '));
        $pdf->WriteHTML($tutelle . '<br>');
    }

    if ($idautrestutelle_autrestutelle != 1) {
        $pdf->WriteHTML(utf8_decode('<b>' . TXT_AUTRETUTELLE . ' :</b> '));
        $pdf->WriteHTML(stripslashes(str_replace("''", "'", utf8_decode($autretutelle))) . '<br>'); 
    }
    if (!empty($idcentrale)) {
        $pdf->WriteHTML(utf8_decode('<b>' . TXT_CODEUNITE . ' :</b> '));
        $pdf->WriteHTML($codeunite . '<br>');
        $pdf->WriteHTML(utf8_decode('<b>' . TXT_CENTRALERAT . ' :</b> '));
        $pdf->WriteHTML($libellecentrale . '<br>');
    }
} elseif (!empty($idqualiteindust)) {
    $resinfoindus = $manager->getList2("select nomresponsable,mailresponsable,nomentreprise from utilisateur where idutilisateur=?", $idutilisateur);
    $nomresponsable = $resinfoindus[0]['nomresponsable'];
    $mailresponsable = $resinfoindus[0]['mailresponsable'];
    $nomentreprise = $resinfoindus[0]['nomentreprise'];
    $ididtypeentreprise_typeentreprise = $manager->getSingle2("select idtypeentreprise_typeentreprise from appartient where idutilisateur_utilisateur=?", $idutilisateur);
    $idsecteuractivite_secteuractivite = $manager->getSingle2("select idsecteuractivite_secteuractivite from intervient where idutilisateur_utilisateur=?", $idutilisateur);
    $libellesecteuractivite =str_replace("''", "'", utf8_decode($manager->getSingle2("select libellesecteuractivite from secteuractivite where idsecteuractivite =?", $idsecteuractivite_secteuractivite)));
    $libelletypeentreprise = $manager->getSingle2("select libelletypeentreprise from typeentreprise where idtypeentreprise=?", $ididtypeentreprise_typeentreprise);
    if (!empty($nomresponsable)) {
        $pdf->WriteHTML(utf8_decode('<b>' . TXT_NOMRESPONSABLE . ' :</b> '));
        $pdf->WriteHTML($nomresponsable . '<br>');
    }
    if (!empty($mailresponsable)) {
        $pdf->WriteHTML(utf8_decode('<b>' . TXT_RESPMAILMAIL . ' :</b> '));
        $pdf->WriteHTML($mailresponsable . '<br>');
    }
    $pdf->WriteHTML(utf8_decode('<b>' . stripslashes(TXT_NOMENTELABO) . ' :</b> '));
    $pdf->WriteHTML($nomentreprise . '<br>');
    
    $pdf->WriteHTML(utf8_decode('<b>' . stripslashes(TXT_TYPEENTREPRISE) . ' :</b> '));
    $pdf->WriteHTML($libelletypeentreprise . '<br>');
    
    $pdf->WriteHTML(utf8_decode('<b>' . stripslashes(TXT_SECTEURACTIVITE) . ' :</b> '));
    $pdf->WriteHTML($libellesecteuractivite . '<br>');
}

BD::deconnecter();
?>
