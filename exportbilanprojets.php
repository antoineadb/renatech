<?php

session_start();
include 'decide-lang.php';
include 'class/Manager.php';
include_once 'outils/toolBox.php';
include_once 'outils/constantes.php';
if (!empty($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
$data = utf8_decode("Titre du projet;N° de référence interne;Développement technologique;Académique/ Industriel;Interne/Externe;Domaine d'application;Type d'entreprise;National/ International;Acronyme du laboratoire;Ville;Organisme de tutelle;Centrale de proximité pour les académiques;Discipline / Origine scientifique;Nom contact1;Email contact1;Nom contact2;Email contact2;Nom labo1;Nom labo2;Nom labo3;Nom labo4;Année de début;Durée estimée;Date de fin estimée;Date de fin réelle;Durée réelle;Thématique RTB;Ressource1;Ressource2;Ressource3;Ressource4;Ressource5;Ressource6;Type de projet;Sources de financement;Acronyme de financement");
$data .= "\n";
//Récupération de l'idcentrale de l'utilisateur
if (!empty($_SESSION['mail'])) {
    $mail = $_SESSION['mail'];
} elseif (!empty($_SESSION['email'])) {
    $mail = $_SESSION['email'];
}

if (!empty($_POST['anneeExport'])) {
    $anneeExport = $_POST['anneeExport'];
} else {
    $anneeExport = date('Y'); //Année du jour si vide
}

$rowcentrale = $manager->getListbyArray("select idcentrale,libellecentrale from centrale where email1=?", array($mail));
if (!empty($rowcentrale[0]['idcentrale'])) {
    $idcentrale = $rowcentrale[0]['idcentrale'];
}
if (!empty($rowcentrale[0]['libellecentrale'])) {
    $libellecentrale = $rowcentrale[0]['libellecentrale'];
}
if (!empty($_SESSION['pseudo'])) {
    $pseudo = $_SESSION['pseudo'];
}
$idutilisateur = $manager->getSingle2("SELECT idutilisateur FROM loginpassword, utilisateur WHERE idlogin = idlogin_loginpassword and pseudo=?", $pseudo);
//on va récupérer les info de la centrale dans la table utilisateur
if (empty($idcentrale)) {
    $idcentrale = $manager->getSingle2("select idcentrale_centrale from utilisateur where idutilisateur = ?", $idutilisateur);
    $libellecentrale = $manager->getSingle2("select libellecentrale from centrale where idcentrale=?", $idcentrale);
}

//RECUPERATION DE L'IDUTILISATEUR EN FONCTION DU PSEUDO

$idtypeuser = $manager->getSingle2("select idtypeutilisateur_typeutilisateur from utilisateur where idutilisateur=?", $idutilisateur);
$sql="SELECT  p.porteurprojet,p.interneexterne,p.idprojet,u.nom,u.prenom,u.adresse,u.datecreation, u.ville, u.codepostal,u.telephone,u.nomentreprise,u.mailresponsable,
u.nomresponsable, u.idtypeutilisateur_typeutilisateur,u.idpays_pays,u.idlogin_loginpassword,u.iddiscipline_disciplinescientifique,u.idcentrale_centrale,
u.idqualitedemandeuraca_qualitedemandeuraca,u.idautrestutelle_autrestutelle,u.idemployeur_nomemployeur,u.idtutelle_tutelle,u.idautrediscipline_autredisciplinescientifique,
u.idautrenomemployeur_autrenomemployeur,u.idqualitedemandeurindust_qualitedemandeurindust,u.entrepriselaboratoire,u.acronymelaboratoire,u.idautrecodeunite_autrecodeunite, p.titre,
p.acronyme,p.confidentiel,p.numero,p.dureeprojet,p.datedebuttravaux,p.dateprojet,p.contactscentraleaccueil,p.centralepartenaire,p.nbplaque,p.nbrun,
p.refinterneprojet,p.idtypeprojet_typeprojet,p.idthematique_thematique,p.idperiodicite_periodicite,p.typeformation,
p.nbheure,p.idautrethematique_autrethematique,p.descriptiftechnologique,p.devtechnologique,p.centralepartenaireprojet,p.datestatutcloturer,p.datedebutprojet
FROM projet p,creer c,utilisateur u,concerne co
WHERE p.idprojet = co.idprojet_projet AND c.idprojet_projet = p.idprojet AND u.idutilisateur = c.idutilisateur_utilisateur
AND co.idcentrale_centrale =? AND  datedebutprojet is not null AND  datestatutfini is null  AND  datestatutcloturer is null
AND  datestatutrefuser is null AND EXTRACT(YEAR from datedebutprojet)<=?
AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=?
UNION
SELECT  p.porteurprojet,p.interneexterne,p.idprojet,u.nom,u.prenom,u.adresse,u.datecreation, u.ville, u.codepostal,u.telephone,u.nomentreprise,u.mailresponsable,
u.nomresponsable, u.idtypeutilisateur_typeutilisateur,u.idpays_pays,u.idlogin_loginpassword,u.iddiscipline_disciplinescientifique,u.idcentrale_centrale,
u.idqualitedemandeuraca_qualitedemandeuraca,u.idautrestutelle_autrestutelle,u.idemployeur_nomemployeur,u.idtutelle_tutelle,u.idautrediscipline_autredisciplinescientifique,
u.idautrenomemployeur_autrenomemployeur,u.idqualitedemandeurindust_qualitedemandeurindust,u.entrepriselaboratoire,u.acronymelaboratoire,u.idautrecodeunite_autrecodeunite, p.titre,
p.acronyme,p.confidentiel,p.numero,p.dureeprojet,p.datedebuttravaux,p.dateprojet,p.contactscentraleaccueil,p.centralepartenaire,p.nbplaque,p.nbrun,
p.refinterneprojet,p.idtypeprojet_typeprojet,p.idthematique_thematique,p.idperiodicite_periodicite,p.typeformation,
p.nbheure,p.idautrethematique_autrethematique,p.descriptiftechnologique,p.devtechnologique,p.centralepartenaireprojet,p.datestatutcloturer,p.datedebutprojet
FROM projet p,creer c,utilisateur u,concerne co
WHERE p.idprojet = co.idprojet_projet AND c.idprojet_projet = p.idprojet AND u.idutilisateur = c.idutilisateur_utilisateur
AND co.idcentrale_centrale =? AND  datedebutprojet is not null AND  datestatutfini is not null  AND  datestatutcloturer is null
AND  datestatutrefuser is null AND EXTRACT(YEAR from datedebutprojet)<=? and EXTRACT(YEAR from datestatutfini) >=?
AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=?
UNION
SELECT  p.porteurprojet,p.interneexterne,p.idprojet,u.nom,u.prenom,u.adresse,u.datecreation, u.ville, u.codepostal,u.telephone,u.nomentreprise,u.mailresponsable,
u.nomresponsable, u.idtypeutilisateur_typeutilisateur,u.idpays_pays,u.idlogin_loginpassword,u.iddiscipline_disciplinescientifique,u.idcentrale_centrale,
u.idqualitedemandeuraca_qualitedemandeuraca,u.idautrestutelle_autrestutelle,u.idemployeur_nomemployeur,u.idtutelle_tutelle,u.idautrediscipline_autredisciplinescientifique,
u.idautrenomemployeur_autrenomemployeur,u.idqualitedemandeurindust_qualitedemandeurindust,u.entrepriselaboratoire,u.acronymelaboratoire,u.idautrecodeunite_autrecodeunite, p.titre,
p.acronyme,p.confidentiel,p.numero,p.dureeprojet,p.datedebuttravaux,p.dateprojet,p.contactscentraleaccueil,p.centralepartenaire,p.nbplaque,p.nbrun,
p.refinterneprojet,p.idtypeprojet_typeprojet,p.idthematique_thematique,p.idperiodicite_periodicite,p.typeformation,
p.nbheure,p.idautrethematique_autrethematique,p.descriptiftechnologique,p.devtechnologique,p.centralepartenaireprojet,p.datestatutcloturer,p.datedebutprojet
FROM projet p,creer c,utilisateur u,concerne co
WHERE p.idprojet = co.idprojet_projet AND c.idprojet_projet = p.idprojet AND u.idutilisateur = c.idutilisateur_utilisateur
AND co.idcentrale_centrale =? AND  datedebutprojet is not null AND  datestatutfini is not null  AND  datestatutcloturer is not null
AND  datestatutrefuser is null AND EXTRACT(YEAR from datedebutprojet)<=? and EXTRACT(YEAR from datestatutfini) >=?
AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=?
UNION
SELECT  p.porteurprojet,p.interneexterne,p.idprojet,u.nom,u.prenom,u.adresse,u.datecreation, u.ville, u.codepostal,u.telephone,u.nomentreprise,u.mailresponsable,
u.nomresponsable, u.idtypeutilisateur_typeutilisateur,u.idpays_pays,u.idlogin_loginpassword,u.iddiscipline_disciplinescientifique,u.idcentrale_centrale,
u.idqualitedemandeuraca_qualitedemandeuraca,u.idautrestutelle_autrestutelle,u.idemployeur_nomemployeur,u.idtutelle_tutelle,u.idautrediscipline_autredisciplinescientifique,
u.idautrenomemployeur_autrenomemployeur,u.idqualitedemandeurindust_qualitedemandeurindust,u.entrepriselaboratoire,u.acronymelaboratoire,u.idautrecodeunite_autrecodeunite, p.titre,
p.acronyme,p.confidentiel,p.numero,p.dureeprojet,p.datedebuttravaux,p.dateprojet,p.contactscentraleaccueil,p.centralepartenaire,p.nbplaque,p.nbrun,
p.refinterneprojet,p.idtypeprojet_typeprojet,p.idthematique_thematique,p.idperiodicite_periodicite,p.typeformation,
p.nbheure,p.idautrethematique_autrethematique,p.descriptiftechnologique,p.devtechnologique,p.centralepartenaireprojet,p.datestatutcloturer,p.datedebutprojet
FROM projet p,creer c,utilisateur u,concerne co
WHERE p.idprojet = co.idprojet_projet AND c.idprojet_projet = p.idprojet AND u.idutilisateur = c.idutilisateur_utilisateur
AND co.idcentrale_centrale =? AND  datestatutfini is not null AND  datestatutcloturer is null AND
EXTRACT(YEAR from datedebutprojet)<=? and EXTRACT(YEAR from datestatutfini) >=?
AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=?
UNION
SELECT  p.porteurprojet,p.interneexterne,p.idprojet,u.nom,u.prenom,u.adresse,u.datecreation, u.ville, u.codepostal,u.telephone,u.nomentreprise,u.mailresponsable,
u.nomresponsable, u.idtypeutilisateur_typeutilisateur,u.idpays_pays,u.idlogin_loginpassword,u.iddiscipline_disciplinescientifique,u.idcentrale_centrale,
u.idqualitedemandeuraca_qualitedemandeuraca,u.idautrestutelle_autrestutelle,u.idemployeur_nomemployeur,u.idtutelle_tutelle,u.idautrediscipline_autredisciplinescientifique,
u.idautrenomemployeur_autrenomemployeur,u.idqualitedemandeurindust_qualitedemandeurindust,u.entrepriselaboratoire,u.acronymelaboratoire,u.idautrecodeunite_autrecodeunite, p.titre,
p.acronyme,p.confidentiel,p.numero,p.dureeprojet,p.datedebuttravaux,p.dateprojet,p.contactscentraleaccueil,p.centralepartenaire,p.nbplaque,p.nbrun,
p.refinterneprojet,p.idtypeprojet_typeprojet,p.idthematique_thematique,p.idperiodicite_periodicite,p.typeformation,
p.nbheure,p.idautrethematique_autrethematique,p.descriptiftechnologique,p.devtechnologique,p.centralepartenaireprojet,p.datestatutcloturer,p.datedebutprojet
FROM projet p,creer c,utilisateur u,concerne co
WHERE p.idprojet = co.idprojet_projet AND c.idprojet_projet = p.idprojet AND u.idutilisateur = c.idutilisateur_utilisateur
AND co.idcentrale_centrale =? AND  datestatutcloturer is not null
AND EXTRACT(YEAR from datestatutcloturer)=?
AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=?
UNION
SELECT  p.porteurprojet,p.interneexterne,p.idprojet,u.nom,u.prenom,u.adresse,u.datecreation, u.ville, u.codepostal,u.telephone,u.nomentreprise,u.mailresponsable,
u.nomresponsable, u.idtypeutilisateur_typeutilisateur,u.idpays_pays,u.idlogin_loginpassword,u.iddiscipline_disciplinescientifique,u.idcentrale_centrale,
u.idqualitedemandeuraca_qualitedemandeuraca,u.idautrestutelle_autrestutelle,u.idemployeur_nomemployeur,u.idtutelle_tutelle,u.idautrediscipline_autredisciplinescientifique,
u.idautrenomemployeur_autrenomemployeur,u.idqualitedemandeurindust_qualitedemandeurindust,u.entrepriselaboratoire,u.acronymelaboratoire,u.idautrecodeunite_autrecodeunite, p.titre,
p.acronyme,p.confidentiel,p.numero,p.dureeprojet,p.datedebuttravaux,p.dateprojet,p.contactscentraleaccueil,p.centralepartenaire,p.nbplaque,p.nbrun,
p.refinterneprojet,p.idtypeprojet_typeprojet,p.idthematique_thematique,p.idperiodicite_periodicite,p.typeformation,
p.nbheure,p.idautrethematique_autrethematique,p.descriptiftechnologique,p.devtechnologique,p.centralepartenaireprojet,p.datestatutcloturer,p.datedebutprojet
FROM projet p,creer c,utilisateur u,concerne co
WHERE p.idprojet = co.idprojet_projet AND c.idprojet_projet = p.idprojet AND u.idutilisateur = c.idutilisateur_utilisateur
AND co.idcentrale_centrale =? AND  datestatutrefuser is not null
AND EXTRACT(YEAR from datestatutrefuser)=? 
    AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=? order by datecreation  asc";

if ($idtypeuser == ADMINNATIONNAL) {//Administrateur national
//RECUPERATION DU NOM ET DE L'ID DE LA CENTRALE DE L'IDUTILISATEUR EN FONCTION DE L'EMAIL
    $idcentrale = (int) ($_POST['centrale']);
    $donnee = array($idcentrale, $anneeExport,REFUSE,ACCEPTE, $idcentrale, $anneeExport, $anneeExport,REFUSE,ACCEPTE, $idcentrale, $anneeExport,$anneeExport,REFUSE,ACCEPTE, $idcentrale, $anneeExport, $anneeExport,REFUSE,ACCEPTE,
        $idcentrale, $anneeExport,REFUSE,ACCEPTE, $idcentrale, $anneeExport,REFUSE,ACCEPTE,);
    if ($idcentrale != 0) {        
        $row = $manager->getListbyArray($sql, $donnee);
    } else {       
        include_once 'ExportprojetAdmNat.php';
        exit();
    }
} elseif ($idtypeuser == ADMINLOCAL) {
    $donnee = array($idcentrale, $anneeExport, REFUSE,ACCEPTE,$idcentrale, $anneeExport, $anneeExport,REFUSE,ACCEPTE, $idcentrale, $anneeExport,$anneeExport,REFUSE,ACCEPTE, $idcentrale, $anneeExport, $anneeExport, REFUSE,ACCEPTE,
        $idcentrale, $anneeExport,REFUSE,ACCEPTE, $idcentrale, $anneeExport,REFUSE,ACCEPTE);
    $row = $manager->getListbyArray($sql, $donnee);
}
$nbrow = count($row);
if ($nbrow != 0) {
// ENREGISTREMENT DES RESULTATS LIGNE PAR LIGNE
    for ($i = 0; $i < count($row); $i++) {
        $idprojet = $row[$i]['idprojet'];
        include 'outils/communExport.php';     
        $data .= "" .                
                $titre . ";" .
                $ref . ";" .
                utf8_decode($devtechno) . ";" .
                ucfirst(utf8_decode($libtypeuser)) . ";" .
                $interne_externe . ";" .
                str_replace("''", "'", stripslashes(utf8_decode($secteuractivite))) . ";" .
                str_replace("''", "'", stripslashes(utf8_decode($typeentreprise))) . ";" .
                $sit . ";" .
                utf8_decode($row[$i]['acronymelaboratoire']) . ";" .
                utf8_decode($row[$i]['ville']) . ";" .
                utf8_decode($libtutelle) . ";" .
                "" . ";" . //Centrale de proximité pour les académiques
                utf8_decode($libdiscipline) . ";" .
                $nomdemandeur . ";" .
                $maildemandeur . ";" .
                $nomresponsable . ";" .
                $mailresponsable . ";" .
                utf8_decode($centralepartenaireprojet) . ";" .
                trim(substr(utf8_decode($libellenomlabo), 0, -1)) . ";" .
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
// Déclaration du type de contenu
    if ($idtypeuser == ADMINNATIONNAL) {
        header("Content-type: application/vnd.ms-excel;charset=UTF-8");
        header("Content-disposition: attachment; filename=export_projet_" . $originalDate . ".csv");
    } else {
        header("Content-type: application/vnd.ms-excel;charset=UTF-8");
        header("Content-disposition: attachment; filename=export_centrale_" . $libellecentrale . '_' . $originalDate . ".csv");
    }
    print $data;
    
    exit;
} else {
    echo ' <script>alert("' . utf8_decode(TXT_PASDEPROJET) . '");window.location.replace("/' . REPERTOIRE . '/exportBilanProjet.php?lang=' . $lang . '")</script>';
    exit();
}

BD::deconnecter();
