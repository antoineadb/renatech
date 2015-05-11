<?php

$donneePorteur = $manager->getList2("SELECT nom,mail,mailresponsable,nomresponsable,dateaffectation FROM projet,utilisateurporteurprojet, utilisateur,loginpassword WHERE idprojet_projet = idprojet AND idutilisateur_utilisateur = idutilisateur AND idlogin = idlogin_loginpassword and idprojet=? order by dateaffectation  asc limit 1", $idprojet);
if (!empty($donneePorteur[0]['nom'])) {
    $nomdemandeur = str_replace("''", "''", utf8_decode($donneePorteur[0]['nom']));
    $maildemandeur = $donneePorteur[0]['mail'];
    if (!empty($donneePorteur[0]['nomresponsable'])) {
        $nomresponsable = str_replace("''", "''", utf8_decode($donneePorteur[0]['nomresponsable']));
    } else {
        $nomresponsable = '';
    }
    if (!empty($donneePorteur[0]['mailresponsable'])) {
        $mailresponsable = $donneePorteur[0]['mailresponsable'];
    } else {
        $mailresponsable = '';
    }
} else {
    $arraydemandeur = $manager->getList2("SELECT nom,  mail, mailresponsable, nomresponsable FROM creer,utilisateur,loginpassword WHERE idutilisateur_utilisateur = idutilisateur
                AND idlogin = idlogin_loginpassword and idprojet_projet=? ", $idprojet);
    $nomdemandeur = str_replace("''", "''", utf8_decode($arraydemandeur[0]['nom']));
    $maildemandeur = $arraydemandeur[0]['mail'];
    if (!empty($arraydemandeur[0]['nomresponsable'])) {
        $nomresponsable = str_replace("''", "''", utf8_decode($arraydemandeur[0]['nomresponsable']));
    } else {
        $nomresponsable = '';
    }
    if (!empty($arraydemandeur[0]['mailresponsable'])) {
        $mailresponsable = $arraydemandeur[0]['mailresponsable'];
    } else {
        $mailresponsable = '';
    }
}
//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              SOURCE DE FINANCEMENT
//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
$s_Sourcefinancement = '';
$arraySF = array();
$arraysourcefinancement = $manager->getList2("SELECT libellesourcefinancement FROM sourcefinancement,projetsourcefinancement WHERE idsourcefinancement_sourcefinancement = idsourcefinancement AND idprojet_projet =?", $idprojet);
$arrayacrosourcef = $manager->getList2("SELECT acronymesource FROM projetsourcefinancement WHERE idprojet_projet =?", $idprojet);
$nbarraysf = count($arraysourcefinancement);
for ($k = 0; $k < $nbarraysf; $k++) {
    array_push($arraySF, $arraysourcefinancement[$k]['libellesourcefinancement']);
    if ($arraysourcefinancement[$k]['libellesourcefinancement'] != 'Autres') {
        $s_Sourcefinancement .= stripslashes(str_replace("’", "'", str_replace("''", "'", $arraysourcefinancement[$k]['libellesourcefinancement']))) . ' / ';
    } else {
        $s_Sourcefinancement .= stripslashes(str_replace("’", "'", str_replace("''", "'", $arrayacrosourcef[$k]['acronymesource']))) . ' / ';
    }
}
if ($nbarraysf > 0) {
    $s_Sourcefinancement = substr(trim($s_Sourcefinancement), 0, -1);
} else {
    $s_Sourcefinancement = '';
}

//-------------------------------------------------------------------------------------------------------------------------------
// INTERNE/EXTERNE
//-------------------------------------------------------------------------------------------------------------------------------
$interneExterne = $manager->getSingle2("SELECT idcentrale_centrale FROM  utilisateur,creer WHERE  idutilisateur_utilisateur = utilisateur.idutilisateur and idprojet_projet = ?", $idprojet);
$porteur = $row[$i]['porteurprojet'];

if(!empty($row[$i]['interneexterne'])){
    if($row[$i]['interneexterne']=='I'){
        $interne_externe= 'Interne';
    }elseif ($row[$i]['interneexterne']=='E'){
        $interne_externe= 'Externe';
    }    
    
} elseif (!empty($interneExterne) && $porteur == TRUE) {
    $interne_externe = 'Interne';
} else {
    $interne_externe = 'Externe';
}
$idpays = $row[$i]['idpays_pays'];
$nompays = $manager->getSingle2("select nompays from pays where idpays=?", $idpays);
// SI SOURCE DE FINANCEMENT = EUROPE ALORS INTERNATIONAL

if(!empty($row[$i]['internationalnational'])){
    if($row[$i]['internationalnational']=='I'){
        $sit= 'International';
    }elseif ($row[$i]['internationalnational']=='N'){
        $sit= 'National';
    }    
    
} elseif (in_array('Europe', $arraySF)) {
    $sit = 'International';
} else {
    if ($nompays == 'France') {
        $sit = 'National';
    } else {
        $idsituation = $manager->getSingle2("select idsituation_situationgeographique from pays where idpays=?", $idpays);
        $sit = $manager->getSingle2("select libellesituationgeo from situationgeographique where idsituation = ?", $idsituation);
        if ($sit == 'Europe') {
            $sit = 'International';
        }
    }
}
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// GESTION DU TYPE DE PROJET ACADEMIQUE / INDUSTRIEL
// Type PROJET si Industriel ou académique en partenariat avec un industriel alors le projet est INDUSTRIEL
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------        
if ($row[$i]['idqualitedemandeuraca_qualitedemandeuraca'] > 0) {
    $libqualite = $manager->getSingle2("select libellequalitedemandeuraca from qualitedemandeuraca where idqualitedemandeuraca=?", $row[$i]['idqualitedemandeuraca_qualitedemandeuraca']);
} else {
    $libqualite = $manager->getSingle2("select libellequalitedemandeurindust from qualitedemandeurindust where idqualitedemandeurindust=?", $row[$i]['idqualitedemandeurindust_qualitedemandeurindust']);
}


//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// GESTION DE LA TUTELLE
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------                
if (!empty($row[$i]['idtutelle_tutelle'])) {
    if ($row[$i]['idtutelle_tutelle'] != 6) {     //Autres
        $libtutelle = $manager->getSingle2("select libelletutelle from tutelle where idtutelle =?", $row[$i]['idtutelle_tutelle']);
    } else {
        $idautretutelle = $row[$i]['idautrestutelle_autrestutelle'];
        $libtutelle = $manager->getSingle2("select libelleautrestutelle from autrestutelle where idautrestutelle = ?", $idautretutelle);
    }
} else {
    $libtutelle = "";
}

if (!empty($row[$i]['iddiscipline_disciplinescientifique'])) {
    if ($row[$i]['iddiscipline_disciplinescientifique'] != 7) {///AUTRES
        $libdiscipline = $manager->getSingle2("select libellediscipline from disciplinescientifique where  iddiscipline = ?", $row[$i]['iddiscipline_disciplinescientifique']);
    } else {
        $libdiscipline = $manager->getSingle2("select libelleautrediscipline from autredisciplinescientifique where idautrediscipline = ?", $row[$i]['idautrediscipline_autredisciplinescientifique']);
    }
} else {
    $libdiscipline = "";
}

//THEMATIQUE
$libthematique = '';
if (!empty($row[$i]['idthematique_thematique'])) {
    if ($row[$i]['idthematique_thematique'] != 6) {///AUTRES
        $libthematique = $manager->getSingle2("select libellethematique from thematique where  idthematique = ?", $row[$i]['idthematique_thematique']);
    } else {
        $libthematique = $manager->getSingle2("select libelleautrethematique from autrethematique where idautrethematique = ?", $row[$i]['idautrethematique_autrethematique']);
    }
} else {
    $libthematique = "";
}


$devtechno = $row[$i]['devtechnologique']; //TYPE BOOLEAN DONC OUI OU NON
if ($devtechno == 1) {
    $devtechno = TXT_AVECDEV;
} else {
    $devtechno = TXT_SANSDEV;
}
$dureeEstime = '';
if (!empty($row[$i]['dureeprojet'])) {
    $duree = $row[$i]['dureeprojet'];
    $idperiodicite = $row[$i]['idperiodicite_periodicite'];

    switch ($idperiodicite) {
        case 1://jour
            $dureeEstime = ceil($duree / 30);
            break;
        case 2://Mois
            $dureeEstime = ceil($duree);
            break;
        case 3://Année
            $dureeEstime = ceil($duree * 12);
            break;
        default:
            break;
    }
}
$annee = '';
if (!empty($row[$i]['datedebutprojet'])) {
    //RECUPERATION DE L'ANNEE DE LA DATE DE DEBUT DE PROJET
    $annee = date('Y', strtotime($row[$i]['datedebutprojet']));
}
$dateDepart = $row[$i]['datedebutprojet'];
$datefinestime = "";
if (!empty($dateDepart)) {
    //durée à rajouter en mois;
    if (!empty($dureeEstime)) {
        $dateDepartTimestamp = strtotime($dateDepart);
        $datefinestime = date('m-Y', strtotime('+' . $dureeEstime . ' month', $dateDepartTimestamp)); //mmaaaa
    } else {
        $datefinestime = "";
    }
}
if (!empty($row[$i]['datestatutcloturer'])) {
    $datefinReelle = $row[$i]['datestatutcloturer'];
    $ecart = strtotime($datefinReelle) - strtotime($dateDepart);
    $dureeReelle = ceil(intval($ecart / 86400) / 29);
} else {
    $datefinReelle = "";
    $dureeReelle = "";
}
if (!empty($dateDepart)) {
    $dateDepartTimestamp = strtotime($dateDepart);
    $anneedebut = date('Y', $dateDepartTimestamp);
}
//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              TYPE DE PROJET
//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
if (!empty($row[$i]['idtypeprojet_typeprojet'])) {
    $libelletypeprojet = $manager->getSingle2("select libelletype from typeprojet where idtypeprojet=?", $row[$i]['idtypeprojet_typeprojet']);
    if ($row[$i]['idtypeprojet_typeprojet'] == INDUSTRIEL || $row[$i]['idtypeprojet_typeprojet'] == ACADEMICPARTENARIAT) {
        $libtypeuser = TXT_INDUSTRIEL;
    } else {
        $libtypeuser = TXT_ACADEMIQUE;
    }
    //TRAITEMENT DES TYPE DE FORMATIONS
    if (!empty($row[$i]['typeformation'])) {
        $typeformation = str_replace("''", "'", html_entity_decode(stripslashes($row[$i]['typeformation'])));
    } else {
        $typeformation = '';
    }
//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              TRAITEMENT DU NOMBRE D'HEURE DE FORMATION
//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    if (!empty($row[$i]['nbheure'])) {
        $nbheure = $row[$i]['nbheure'];
    } else {
        $nbheure = '';
    }
//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              TRAITEMENT DU NOMBRE D'ELEVE
//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    $nbeleve = $manager->getSingle2("select count(idprojet_projet) from projetformation where idprojet_projet=?", $idprojet);
    if ($nbeleve == 0) {
        $nbeleve = '';
    }
}
//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              TRAITEMENT DU NOMBRE DE PLAQUES ET DE RUN
//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
$nbplaque = '';

if (!empty($row[$i]['nbplaque'])) {
    $nbplaque = $row[$i]['nbplaque'];
    if ($nbplaque == 0) {
        $nbplaque = '';
    }
}
$nbrun = '';
if (!empty($row[$i]['nbrun'])) {
    $nbrun = $row[$i]['nbrun'];
    if ($nbrun == 0) {
        $nbrun = '';
    }
}
$centralepartenaireprojet = '';
if (!empty($row[$i]['centralepartenaireprojet'])) {
    $centralepartenaireprojet = str_replace("''", "'", html_entity_decode(stripslashes($row[$i]['centralepartenaireprojet'])));
} else {
    $centralepartenaireprojet = '';
}

//DOMAINE APPLICATION POUR LES INDUSTRIELS
$secteuractivite = "";
$typeentreprise = "";
if ($row[$i]['idqualitedemandeurindust_qualitedemandeurindust'] > 0) {
    //ON EST UTILISATEUR INDUSTRIEL
    $secteuractivite = $manager->getSingle2("SELECT s.libellesecteuractivite FROM intervient i,utilisateur u,creer c, secteuractivite s,projet p
        WHERE   i.idutilisateur_utilisateur = u.idutilisateur AND c.idutilisateur_utilisateur = u.idutilisateur AND c.idprojet_projet = p.idprojet
        AND s.idsecteuractivite = i.idsecteuractivite_secteuractivite and idprojet=?", $idprojet);
//TYPE D'ENTREPRISE
    $typeentreprise = $manager->getSingle2("SELECT t.libelletypeentreprise FROM  typeentreprise t,appartient a,projet p,creer c
                WHERE t.idtypeentreprise = a.idtypeentreprise_typeentreprise AND c.idprojet_projet = p.idprojet AND c.idutilisateur_utilisateur = a.idutilisateur_utilisateur AND p.idprojet  =?", $idprojet);
}



if (!empty($row[$i]['contactscentraleaccueil'])) {
    $contactscentraleaccueil = $row[$i]['contactscentraleaccueil'];
} else {
    $contactscentraleaccueil = "";
}


//--------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              RECUPERATION DES RESSOURCES
//--------------------------------------------------------------------------------------------------------------------------------------------

$ressource1 = $manager->getsingle2("SELECT idressource FROM ressourceprojet,ressource,projet WHERE idprojet_projet = idprojet AND idressource_ressource = idressource and idprojet = ?
            and idressource_ressource=1", $idprojet);
if (!empty($ressource1)) {
    $ressource1 = 1;
} else {
    $ressource1 = 0;
}
$ressource2 = $manager->getsingle2("SELECT idressource FROM ressourceprojet,ressource,projet WHERE idprojet_projet = idprojet AND idressource_ressource = idressource and idprojet = ?
            and idressource_ressource=2", $idprojet);
if (!empty($ressource2)) {
    $ressource2 = 1;
} else {
    $ressource2 = 0;
}
$ressource3 = $manager->getsingle2("SELECT idressource FROM ressourceprojet,ressource,projet WHERE idprojet_projet = idprojet AND idressource_ressource = idressource and idprojet = ?
            and idressource_ressource=3", $idprojet);
if (!empty($ressource3)) {
    $ressource3 = 1;
} else {
    $ressource3 = 0;
}
$ressource4 = $manager->getsingle2("SELECT idressource FROM ressourceprojet,ressource,projet WHERE idprojet_projet = idprojet AND idressource_ressource = idressource and idprojet = ?
            and idressource_ressource=4", $idprojet);
if (!empty($ressource4)) {
    $ressource4 = 1;
} else {
    $ressource4 = 0;
}
$ressource5 = $manager->getsingle2("SELECT idressource FROM ressourceprojet,ressource,projet WHERE idprojet_projet = idprojet AND idressource_ressource = idressource and idprojet = ?
            and idressource_ressource=5", $idprojet);
if (!empty($ressource5)) {
    $ressource5 = 1;
} else {
    $ressource5 = 0;
}
$ressource6 = $manager->getsingle2("SELECT idressource FROM ressourceprojet,ressource,projet WHERE idprojet_projet = idprojet AND idressource_ressource = idressource and idprojet = ?
            and idressource_ressource=6", $idprojet);
if (!empty($ressource6)) {
    $ressource6 = 1;
} else {
    $ressource6 = 0;
}


//CONFIDENTIEL
$confidentiel = $row[$i]['confidentiel'];
if ($confidentiel == 't') {
    $confidentiel = TXT_OUI;
} else {
    $confidentiel = TXT_NON;
}
//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              ACRONYME OU REFERENCE INTERNE
//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
$refinterne = utf8_decode($row[$i]['refinterneprojet']);
if (!empty($refinterne)) {
    $ref = trim($refinterne);
} else {
    $ref = "";
}

//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                         ACRONYME     SOURCE DE FINANCEMENT
//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
$s_Acrosourcefinancement = '';
$arrayacrosourcefinancement = $manager->getList2("SELECT acronymesource FROM projetsourcefinancement WHERE idprojet_projet =?", $idprojet);
$nbacrosf = count($arrayacrosourcefinancement);
for ($l = 0; $l < $nbacrosf; $l++) {
    if (!empty($arrayacrosourcefinancement[$l]['acronymesource'])) {
        $s_Acrosourcefinancement .= stripslashes(str_replace("’", "'", str_replace("''", "'", $arrayacrosourcefinancement[$l]['acronymesource']))) . ' / ';
    }
}
if ($nbacrosf > 0) {
    $s_Acrosourcefinancement = substr(trim($s_Acrosourcefinancement), 0, -1);
} else {
    $s_Acrosourcefinancement = '';
}

//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              NOM DU 1ER PARTENAIRE
//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//NOM DU LABO 1-->4
$libellenomlabo = '';
$arraynomlabo = $manager->getListbyArray("SELECT nomlaboentreprise FROM partenaireprojet,projetpartenaire WHERE idpartenaire_partenaireprojet = idpartenaire and idprojet_projet=?", array($idprojet));
for ($j = 0; $j < 3; $j++) {
    if (!empty($arraynomlabo[$j]['nomlaboentreprise'])) {
        $libellenomlabo .= $arraynomlabo[$j]['nomlaboentreprise'] . ";";
    } else {
        $libellenomlabo .= '' . ";";
    }
}
//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              TITRE
//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
$titre0 = str_replace("’", "'", stripslashes((($row[$i]['titre']))));
$titre1 = str_replace("''", "'", utf8_decode($titre0));
$titre = trim($titre1);
//echo '$libellenomlabo = '.$libellenomlabo;exit();
//DATE POUR LE NOM DU FICHIER CSV DE SORTIE
$originalDate = date('d-m-Y');
