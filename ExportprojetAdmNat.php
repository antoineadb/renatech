<?php

include_once 'decide-lang.php';
include_once 'class/Manager.php';
include_once 'outils/toolBox.php';
include_once 'outils/constantes.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
/*$data = utf8_decode("Titre du projet;N° de référence interne;Développement technologique;Académique/ Industriel;Interne/Externe;Domaine d'application;Type d'entreprise;National/ International;Acronyme du laboratoire;Ville;Organisme de tutelle;Centrale de proximité pour les académiques;Discipline / Origine scientifique;Nom contact1;Email contact1;Nom contact2;Email contact2;Nom labo1;Nom labo2;Nom labo3;Nom labo4;Année de début;Durée estimée;Date de fin estimée;Date de fin réelle;Durée réelle;Thématique RTB;Ressource1;Ressource2;Ressource3;Ressource4;Ressource5;Ressource6;Type de projet;Sources de financement;Acronyme de financement");
$data .= "\n";*/

$s_partenaire = "";
for ($c = 2; $c <= 10; $c++) {
    $s_partenaire .= "Nom du Laboratoire&Entreprise" . $c . ";";
}
$data = utf8_decode("Titre du projet;N° de référence interne;Développement technologique;Académique/ Industriel;Interne/Externe;Domaine d'application;Type d'entreprise;National/ International;Acronyme du laboratoire;Ville;Organisme de tutelle;Centrale de proximité pour les académiques;Discipline / Origine scientifique;Nom contact1;Email contact1;Nom contact2;Email contact2;Nom du Laboratoire&Entreprise1;" . $s_partenaire . ";Année de début;Durée estimée;Date de fin estimée;Date de fin réelle;Durée réelle;Thématique RTB;Ressource1;Ressource2;Ressource3;Ressource4;Ressource5;Ressource6;Type de projet;Sources de financement;Acronyme de financement;Administrateur des projets; Vue des projets;");
$data .= "Nom du Laboratoire&Entreprise1;" . $s_partenaire . "\n";

//Récupération de l'idcentrale de l'utilisateur

if (!empty($_POST['anneeExport'])) {
    $anneeExport = $_POST['anneeExport'];
} else {
    $anneeExport = date('Y'); //Année du jour si vide
}
$arrayidcentrale = $manager->getList2("select idcentrale from centrale where idcentrale !=? order by idcentrale asc", IDCENTRALEAUTRE);
$nbidcentrale = count($arrayidcentrale);
$z = 0;
for ($i = 0; $i < $nbidcentrale; $i++) {
    $z++;
    $donnee = array($arrayidcentrale[$i]['idcentrale'], $anneeExport, REFUSE, ACCEPTE, $arrayidcentrale[$i]['idcentrale'], $anneeExport, $anneeExport, REFUSE, ACCEPTE, $arrayidcentrale[$i]['idcentrale'], $anneeExport,
        REFUSE, ACCEPTE, $arrayidcentrale[$i]['idcentrale'], $anneeExport, REFUSE, ACCEPTE);
    $manager->exeRequete("drop table if exists tmpcentrale" . $z . "");
//CREATION DE LA TABLE TEMPORAIRE
    $manager->getRequete("create table tmpcentrale" . $z . " as (SELECT p.porteurprojet,p.idprojet,u.nom,u.prenom,u.adresse,u.datecreation, u.ville, u.codepostal,u.telephone,u.nomentreprise,u.mailresponsable,
u.nomresponsable, u.idtypeutilisateur_typeutilisateur,u.idpays_pays,u.idlogin_loginpassword,u.iddiscipline_disciplinescientifique,u.idcentrale_centrale,
u.idqualitedemandeuraca_qualitedemandeuraca,u.idautrestutelle_autrestutelle,u.idemployeur_nomemployeur,u.idtutelle_tutelle,u.idautrediscipline_autredisciplinescientifique,
u.idautrenomemployeur_autrenomemployeur,u.idqualitedemandeurindust_qualitedemandeurindust,u.entrepriselaboratoire,u.idautrecodeunite_autrecodeunite, p.titre,
p.acronyme,p.confidentiel,p.numero,p.dureeprojet,p.datedebuttravaux,p.dateprojet,p.contactscentraleaccueil,p.centralepartenaire,p.nbplaque,p.nbrun,
p.refinterneprojet,p.idtypeprojet_typeprojet,p.idthematique_thematique,p.idperiodicite_periodicite,p.typeformation,
p.nbheure,p.idautrethematique_autrethematique,p.descriptiftechnologique,p.devtechnologique,p.centralepartenaireprojet,p.datestatutcloturer,p.datedebutprojet
FROM projet p,creer c,utilisateur u,concerne co
WHERE p.idprojet = co.idprojet_projet AND c.idprojet_projet = p.idprojet AND u.idutilisateur = c.idutilisateur_utilisateur
AND co.idcentrale_centrale =? AND  datedebutprojet is not null AND  datestatutfini is null  AND  datestatutcloturer is null
AND  datestatutrefuser is null AND EXTRACT(YEAR from datedebutprojet)<=?
AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=?
UNION
SELECT  p.porteurprojet,p.idprojet,u.nom,u.prenom,u.adresse,u.datecreation, u.ville, u.codepostal,u.telephone,u.nomentreprise,u.mailresponsable,
u.nomresponsable, u.idtypeutilisateur_typeutilisateur,u.idpays_pays,u.idlogin_loginpassword,u.iddiscipline_disciplinescientifique,u.idcentrale_centrale,
u.idqualitedemandeuraca_qualitedemandeuraca,u.idautrestutelle_autrestutelle,u.idemployeur_nomemployeur,u.idtutelle_tutelle,u.idautrediscipline_autredisciplinescientifique,
u.idautrenomemployeur_autrenomemployeur,u.idqualitedemandeurindust_qualitedemandeurindust,u.entrepriselaboratoire,u.idautrecodeunite_autrecodeunite, p.titre,
p.acronyme,p.confidentiel,p.numero,p.dureeprojet,p.datedebuttravaux,p.dateprojet,p.contactscentraleaccueil,p.centralepartenaire,p.nbplaque,p.nbrun,
p.refinterneprojet,p.idtypeprojet_typeprojet,p.idthematique_thematique,p.idperiodicite_periodicite,p.typeformation,
p.nbheure,p.idautrethematique_autrethematique,p.descriptiftechnologique,p.devtechnologique,p.centralepartenaireprojet,p.datestatutcloturer,p.datedebutprojet
FROM projet p,creer c,utilisateur u,concerne co
WHERE p.idprojet = co.idprojet_projet AND c.idprojet_projet = p.idprojet AND u.idutilisateur = c.idutilisateur_utilisateur
AND co.idcentrale_centrale =? AND  datestatutfini is not null AND  datestatutcloturer is null AND
EXTRACT(YEAR from datedebutprojet)<=? and EXTRACT(YEAR from datestatutfini) >=?
AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=?
UNION
SELECT  p.porteurprojet,p.idprojet,u.nom,u.prenom,u.adresse,u.datecreation, u.ville, u.codepostal,u.telephone,u.nomentreprise,u.mailresponsable,
u.nomresponsable, u.idtypeutilisateur_typeutilisateur,u.idpays_pays,u.idlogin_loginpassword,u.iddiscipline_disciplinescientifique,u.idcentrale_centrale,
u.idqualitedemandeuraca_qualitedemandeuraca,u.idautrestutelle_autrestutelle,u.idemployeur_nomemployeur,u.idtutelle_tutelle,u.idautrediscipline_autredisciplinescientifique,
u.idautrenomemployeur_autrenomemployeur,u.idqualitedemandeurindust_qualitedemandeurindust,u.entrepriselaboratoire,u.idautrecodeunite_autrecodeunite, p.titre,
p.acronyme,p.confidentiel,p.numero,p.dureeprojet,p.datedebuttravaux,p.dateprojet,p.contactscentraleaccueil,p.centralepartenaire,p.nbplaque,p.nbrun,
p.refinterneprojet,p.idtypeprojet_typeprojet,p.idthematique_thematique,p.idperiodicite_periodicite,p.typeformation,
p.nbheure,p.idautrethematique_autrethematique,p.descriptiftechnologique,p.devtechnologique,p.centralepartenaireprojet,p.datestatutcloturer,p.datedebutprojet
FROM projet p,creer c,utilisateur u,concerne co
WHERE p.idprojet = co.idprojet_projet AND c.idprojet_projet = p.idprojet AND u.idutilisateur = c.idutilisateur_utilisateur
AND co.idcentrale_centrale =? AND  datestatutcloturer is not null
AND EXTRACT(YEAR from datestatutcloturer)=?
AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=?
UNION
SELECT  p.porteurprojet,p.idprojet,u.nom,u.prenom,u.adresse,u.datecreation, u.ville, u.codepostal,u.telephone,u.nomentreprise,u.mailresponsable,
u.nomresponsable, u.idtypeutilisateur_typeutilisateur,u.idpays_pays,u.idlogin_loginpassword,u.iddiscipline_disciplinescientifique,u.idcentrale_centrale,
u.idqualitedemandeuraca_qualitedemandeuraca,u.idautrestutelle_autrestutelle,u.idemployeur_nomemployeur,u.idtutelle_tutelle,u.idautrediscipline_autredisciplinescientifique,
u.idautrenomemployeur_autrenomemployeur,u.idqualitedemandeurindust_qualitedemandeurindust,u.entrepriselaboratoire,u.idautrecodeunite_autrecodeunite, p.titre,
p.acronyme,p.confidentiel,p.numero,p.dureeprojet,p.datedebuttravaux,p.dateprojet,p.contactscentraleaccueil,p.centralepartenaire,p.nbplaque,p.nbrun,
p.refinterneprojet,p.idtypeprojet_typeprojet,p.idthematique_thematique,p.idperiodicite_periodicite,p.typeformation,
p.nbheure,p.idautrethematique_autrethematique,p.descriptiftechnologique,p.devtechnologique,p.centralepartenaireprojet,p.datestatutcloturer,p.datedebutprojet
FROM projet p,creer c,utilisateur u,concerne co
WHERE p.idprojet = co.idprojet_projet AND c.idprojet_projet = p.idprojet AND u.idutilisateur = c.idutilisateur_utilisateur
AND co.idcentrale_centrale =? AND  datestatutrefuser is not null
AND EXTRACT(YEAR from datestatutrefuser)=? 
AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=?
)", $donnee);
}
$manager->exeRequete("drop table if exists tmpcentrale0");
$manager->exeRequete("create table tmpcentrale0 as (SELECT * from tmpcentrale1 union SELECT * from tmpcentrale2 union SELECT * from tmpcentrale3 union  SELECT * from tmpcentrale4  "
        . "union SELECT * from tmpcentrale5 union SELECT * from tmpcentrale6) ");
for ($i = 1; $i <= $nbidcentrale; $i++) {
    $manager->exeRequete("drop table if exists tmpcentrale" . $i . "");
}
$manager->exeRequete("drop table if exists tmpcentrale");
$manager->exeRequete("create table tmpcentrale as(select * from tmpcentrale0 order by idprojet asc )");
$manager->exeRequete("drop table if exists tmpcentrale0");
$nbtotalprojet = $manager->getSingle("select count(idprojet) from tmpcentrale");
//* ------------------------------------------------------------------------------------------------------------*/
//*                                            TRAITEMENT DES LIMITES                                           */
//* ------------------------------------------------------------------------------------------------------------*/
$ordreprojet = $_POST['ordreprojet'];
//*----------------------------------------------LIMITE 500-----------------------------------------------------*/

if ($ordreprojet == 0) {
    $row = $manager->getList("SELECT *   FROM tmpcentrale  ORDER by idprojet asc  FETCH FIRST 300 ROWS ONLY;");
    $nbrow = count($row);
    if ($nbrow == 0) {
        echo ' <script>alert("' . utf8_decode(TXT_PLUSDEPROJET) . ' \n' . TXT_NBPROJET . ' :' . $nbtotalprojet . '");window.location.replace("/' . REPERTOIRE . '/exportBilanProjet.php?lang=' . $lang . '")</script>';
        exit();
    }
} else {
    $id = $manager->getSingle("select idprojet from tmpcentrale LIMIT 1 OFFSET " . ($ordreprojet - 1) . "");
    $row = $manager->getList("SELECT * FROM tmpcentrale where idprojet> " . $id . " ORDER by idprojet asc  FETCH FIRST " . $ordreprojet . " ROWS ONLY");
    $nbrow = count($row);
    if ($nbrow == 0) {
        echo ' <script>alert("' . utf8_decode(TXT_PLUSDEPROJET) . ' \n' . TXT_NBPROJET . ' :' . $nbtotalprojet . '");window.location.replace("/' . REPERTOIRE . '/exportBilanProjet.php?lang=' . $lang . '")</script>';
        exit();
    }
}

$idprojet = $row[$i]['idprojet'];
//  DONNEES INCLUES DANS LA TABLE PARTENAIREPROJET
for ($f = 2; $f <= 10; $f++) {
    ${'rowpartenaireprojet' . $f} = $manager->getList2("SELECT nomlaboentreprise FROM partenaireprojet,projetpartenaire WHERE idpartenaire_partenaireprojet = idpartenaire AND idprojet_projet=? "
            . "order by idpartenaire_partenaireprojet asc limit 1 offset " . ($f - 2) . " ", $idprojet);
    if (!empty(${'rowpartenaireprojet' . $f}[0]['nomlaboentreprise'])) {
        ${'laboentreprise' . $f} = clean(${'rowpartenaireprojet' . $f}[0]['nomlaboentreprise']);
    } else {
        ${'laboentreprise' . $f} = '';
    }
}

$varpartenaires = '';
for ($w = 2; $w <= 10; $w++) {
    $varpartenaires .= utf8_decode(removeDoubleQuote(${'laboentreprise' . $w})) . ";";
}




// ENREGISTREMENT DES RESULTATS LIGNE PAR LIGNE        
for ($i = 0; $i < $nbrow; $i++) {
    $idprojet = $row[$i]['idprojet'];
    include 'outils/communExport.php';
    $data .= "" .
            $titre . ";" .
            $ref . ";" .
            utf8_decode($devtechno) . ";" .
            ucfirst(utf8_decode($libtypeuser)) . ";" .
            utf8_decode($interne_externe) . ";" .
            str_replace("''", "'", stripslashes(utf8_decode($secteuractivite))) . ";" .
            str_replace("''", "'", stripslashes(utf8_decode($typeentreprise))) . ";" .
            $sit . ";" .
            utf8_decode($row[$i]['entrepriselaboratoire']) . ";" .
            utf8_decode($row[$i]['ville']) . ";" .
            utf8_decode($libtutelle) . ";" .
            "" . ";" . //Centrale de proximité pour les académiques
            utf8_decode($libdiscipline) . ";" .
            $nomdemandeur . ";" .
            $maildemandeur . ";" .
            $nomresponsable . ";" .
            $mailresponsable . ";" .
            utf8_decode($centralepartenaireprojet) . ";" .
            $varpartenaires.";".
           //trim(substr(utf8_decode($libellenomlabo), 0, -1)) . ";" .
            $anneedebut . ";" .
            $dureeEstime . ";" .
            $datefinestime . ";" .
            $datefinReelle . ";" .
            $dureeReelle . ";" .
            utf8_decode($libthematique) . ";" .
            $ressource1 . ";" .
            $ressource2 . ";" .
            $ressource3 . ";" .
            $ressource4 . ";" .
            $ressource5 . ";" .
            $ressource6 . ";" .
            str_replace("''", "'", stripslashes(utf8_decode($libelletypeprojet))) . ";" .
            utf8_decode($s_Sourcefinancement) . ";" .
            utf8_decode($s_Acrosourcefinancement) . ";" . "\n";
}
header("Content-type: application/vnd.ms-excel;charset=UTF-8");
header("Content-disposition: attachment; filename=export_projet_" . $originalDate . ".csv");
print $data;
BD::deconnecter();

