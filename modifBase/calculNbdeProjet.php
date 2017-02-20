<?php

include '../class/Manager.php';
include '../outils/constantes.php';
$db = BD::connecter();
$manager = new Manager($db);

$sql = "SELECT p.porteurprojet,p.interneexterne,p.internationalnational,p.idprojet,u.nom,u.prenom,u.adresse,u.datecreation, u.ville, u.codepostal,u.telephone,u.nomentreprise,u.mailresponsable,
u.nomresponsable, u.idtypeutilisateur_typeutilisateur,u.idpays_pays,u.idlogin_loginpassword,u.iddiscipline_disciplinescientifique,u.idcentrale_centrale,
u.idqualitedemandeuraca_qualitedemandeuraca,u.idautrestutelle_autrestutelle,u.idemployeur_nomemployeur,u.idtutelle_tutelle,u.idautrediscipline_autredisciplinescientifique,
u.idautrenomemployeur_autrenomemployeur,u.idqualitedemandeurindust_qualitedemandeurindust,u.entrepriselaboratoire,u.acronymelaboratoire,u.idautrecodeunite_autrecodeunite, p.titre,
p.acronyme,p.confidentiel,p.numero,p.dureeprojet,p.datedebuttravaux,p.dateprojet,p.contactscentraleaccueil,p.centralepartenaire,p.nbplaque,p.nbrun,
p.refinterneprojet,p.idtypeprojet_typeprojet,p.idthematique_thematique,p.idperiodicite_periodicite,p.typeformation,
p.nbheure,p.idautrethematique_autrethematique,p.descriptiftechnologique,p.devtechnologique,p.centralepartenaireprojet,p.datestatutcloturer,p.datedebutprojet,u.administrateur, u.vueprojetcentrale
FROM projet p,creer c,utilisateur u,concerne co
WHERE p.idprojet = co.idprojet_projet AND c.idprojet_projet = p.idprojet AND u.idutilisateur = c.idutilisateur_utilisateur
AND co.idcentrale_centrale =? AND  datedebutprojet is not null AND  datestatutfini is null  AND  datestatutcloturer is null
AND  datestatutrefuser is null AND EXTRACT(YEAR from datedebutprojet)<=?
AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=? AND trashed =FALSE
UNION
SELECT  p.porteurprojet,p.interneexterne,p.internationalnational,p.idprojet,u.nom,u.prenom,u.adresse,u.datecreation, u.ville, u.codepostal,u.telephone,u.nomentreprise,u.mailresponsable,
u.nomresponsable, u.idtypeutilisateur_typeutilisateur,u.idpays_pays,u.idlogin_loginpassword,u.iddiscipline_disciplinescientifique,u.idcentrale_centrale,
u.idqualitedemandeuraca_qualitedemandeuraca,u.idautrestutelle_autrestutelle,u.idemployeur_nomemployeur,u.idtutelle_tutelle,u.idautrediscipline_autredisciplinescientifique,
u.idautrenomemployeur_autrenomemployeur,u.idqualitedemandeurindust_qualitedemandeurindust,u.entrepriselaboratoire,u.acronymelaboratoire,u.idautrecodeunite_autrecodeunite, p.titre,
p.acronyme,p.confidentiel,p.numero,p.dureeprojet,p.datedebuttravaux,p.dateprojet,p.contactscentraleaccueil,p.centralepartenaire,p.nbplaque,p.nbrun,
p.refinterneprojet,p.idtypeprojet_typeprojet,p.idthematique_thematique,p.idperiodicite_periodicite,p.typeformation,
p.nbheure,p.idautrethematique_autrethematique,p.descriptiftechnologique,p.devtechnologique,p.centralepartenaireprojet,p.datestatutcloturer,p.datedebutprojet,u.administrateur, u.vueprojetcentrale
FROM projet p,creer c,utilisateur u,concerne co
WHERE p.idprojet = co.idprojet_projet AND c.idprojet_projet = p.idprojet AND u.idutilisateur = c.idutilisateur_utilisateur
AND co.idcentrale_centrale =? AND  datedebutprojet is not null AND  datestatutfini is not null  AND  datestatutcloturer is null
AND  datestatutrefuser is null AND EXTRACT(YEAR from datedebutprojet)<=? and EXTRACT(YEAR from datestatutfini) >=?
AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=? AND trashed =FALSE
UNION
SELECT  p.porteurprojet,p.interneexterne,p.internationalnational,p.idprojet,u.nom,u.prenom,u.adresse,u.datecreation, u.ville, u.codepostal,u.telephone,u.nomentreprise,u.mailresponsable,
u.nomresponsable, u.idtypeutilisateur_typeutilisateur,u.idpays_pays,u.idlogin_loginpassword,u.iddiscipline_disciplinescientifique,u.idcentrale_centrale,
u.idqualitedemandeuraca_qualitedemandeuraca,u.idautrestutelle_autrestutelle,u.idemployeur_nomemployeur,u.idtutelle_tutelle,u.idautrediscipline_autredisciplinescientifique,
u.idautrenomemployeur_autrenomemployeur,u.idqualitedemandeurindust_qualitedemandeurindust,u.entrepriselaboratoire,u.acronymelaboratoire,u.idautrecodeunite_autrecodeunite, p.titre,
p.acronyme,p.confidentiel,p.numero,p.dureeprojet,p.datedebuttravaux,p.dateprojet,p.contactscentraleaccueil,p.centralepartenaire,p.nbplaque,p.nbrun,
p.refinterneprojet,p.idtypeprojet_typeprojet,p.idthematique_thematique,p.idperiodicite_periodicite,p.typeformation,
p.nbheure,p.idautrethematique_autrethematique,p.descriptiftechnologique,p.devtechnologique,p.centralepartenaireprojet,p.datestatutcloturer,p.datedebutprojet,u.administrateur, u.vueprojetcentrale
FROM projet p,creer c,utilisateur u,concerne co
WHERE p.idprojet = co.idprojet_projet AND c.idprojet_projet = p.idprojet AND u.idutilisateur = c.idutilisateur_utilisateur
AND co.idcentrale_centrale =? AND  datedebutprojet is not null AND  datestatutfini is not null  AND  datestatutcloturer is not null
AND  datestatutrefuser is null AND EXTRACT(YEAR from datedebutprojet)<=? and EXTRACT(YEAR from datestatutfini) >=?
AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=? AND trashed =FALSE
UNION
SELECT  p.porteurprojet,p.interneexterne,p.internationalnational,p.idprojet,u.nom,u.prenom,u.adresse,u.datecreation, u.ville, u.codepostal,u.telephone,u.nomentreprise,u.mailresponsable,
u.nomresponsable, u.idtypeutilisateur_typeutilisateur,u.idpays_pays,u.idlogin_loginpassword,u.iddiscipline_disciplinescientifique,u.idcentrale_centrale,
u.idqualitedemandeuraca_qualitedemandeuraca,u.idautrestutelle_autrestutelle,u.idemployeur_nomemployeur,u.idtutelle_tutelle,u.idautrediscipline_autredisciplinescientifique,
u.idautrenomemployeur_autrenomemployeur,u.idqualitedemandeurindust_qualitedemandeurindust,u.entrepriselaboratoire,u.acronymelaboratoire,u.idautrecodeunite_autrecodeunite, p.titre,
p.acronyme,p.confidentiel,p.numero,p.dureeprojet,p.datedebuttravaux,p.dateprojet,p.contactscentraleaccueil,p.centralepartenaire,p.nbplaque,p.nbrun,
p.refinterneprojet,p.idtypeprojet_typeprojet,p.idthematique_thematique,p.idperiodicite_periodicite,p.typeformation,
p.nbheure,p.idautrethematique_autrethematique,p.descriptiftechnologique,p.devtechnologique,p.centralepartenaireprojet,p.datestatutcloturer,p.datedebutprojet,u.administrateur, u.vueprojetcentrale
FROM projet p,creer c,utilisateur u,concerne co
WHERE p.idprojet = co.idprojet_projet AND c.idprojet_projet = p.idprojet AND u.idutilisateur = c.idutilisateur_utilisateur
AND co.idcentrale_centrale =? AND  datestatutfini is not null AND  datestatutcloturer is null AND
EXTRACT(YEAR from datedebutprojet)<=? and EXTRACT(YEAR from datestatutfini) >=?
AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=? AND trashed =FALSE
UNION
SELECT  p.porteurprojet,p.interneexterne,p.internationalnational,p.idprojet,u.nom,u.prenom,u.adresse,u.datecreation, u.ville, u.codepostal,u.telephone,u.nomentreprise,u.mailresponsable,
u.nomresponsable, u.idtypeutilisateur_typeutilisateur,u.idpays_pays,u.idlogin_loginpassword,u.iddiscipline_disciplinescientifique,u.idcentrale_centrale,
u.idqualitedemandeuraca_qualitedemandeuraca,u.idautrestutelle_autrestutelle,u.idemployeur_nomemployeur,u.idtutelle_tutelle,u.idautrediscipline_autredisciplinescientifique,
u.idautrenomemployeur_autrenomemployeur,u.idqualitedemandeurindust_qualitedemandeurindust,u.entrepriselaboratoire,u.acronymelaboratoire,u.idautrecodeunite_autrecodeunite, p.titre,
p.acronyme,p.confidentiel,p.numero,p.dureeprojet,p.datedebuttravaux,p.dateprojet,p.contactscentraleaccueil,p.centralepartenaire,p.nbplaque,p.nbrun,
p.refinterneprojet,p.idtypeprojet_typeprojet,p.idthematique_thematique,p.idperiodicite_periodicite,p.typeformation,
p.nbheure,p.idautrethematique_autrethematique,p.descriptiftechnologique,p.devtechnologique,p.centralepartenaireprojet,p.datestatutcloturer,p.datedebutprojet,u.administrateur, u.vueprojetcentrale
FROM projet p,creer c,utilisateur u,concerne co
WHERE p.idprojet = co.idprojet_projet AND c.idprojet_projet = p.idprojet AND u.idutilisateur = c.idutilisateur_utilisateur
AND co.idcentrale_centrale =? AND  datestatutcloturer is not null
AND EXTRACT(YEAR from datestatutcloturer)=?
AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=? AND trashed =FALSE
UNION
SELECT  p.porteurprojet,p.interneexterne,p.internationalnational,p.idprojet,u.nom,u.prenom,u.adresse,u.datecreation, u.ville, u.codepostal,u.telephone,u.nomentreprise,u.mailresponsable,
u.nomresponsable, u.idtypeutilisateur_typeutilisateur,u.idpays_pays,u.idlogin_loginpassword,u.iddiscipline_disciplinescientifique,u.idcentrale_centrale,
u.idqualitedemandeuraca_qualitedemandeuraca,u.idautrestutelle_autrestutelle,u.idemployeur_nomemployeur,u.idtutelle_tutelle,u.idautrediscipline_autredisciplinescientifique,
u.idautrenomemployeur_autrenomemployeur,u.idqualitedemandeurindust_qualitedemandeurindust,u.entrepriselaboratoire,u.acronymelaboratoire,u.idautrecodeunite_autrecodeunite, p.titre,
p.acronyme,p.confidentiel,p.numero,p.dureeprojet,p.datedebuttravaux,p.dateprojet,p.contactscentraleaccueil,p.centralepartenaire,p.nbplaque,p.nbrun,
p.refinterneprojet,p.idtypeprojet_typeprojet,p.idthematique_thematique,p.idperiodicite_periodicite,p.typeformation,
p.nbheure,p.idautrethematique_autrethematique,p.descriptiftechnologique,p.devtechnologique,p.centralepartenaireprojet,p.datestatutcloturer,p.datedebutprojet,u.administrateur, u.vueprojetcentrale
FROM projet p,creer c,utilisateur u,concerne co
WHERE p.idprojet = co.idprojet_projet AND c.idprojet_projet = p.idprojet AND u.idutilisateur = c.idutilisateur_utilisateur
AND co.idcentrale_centrale =? AND  datestatutrefuser is not null
AND EXTRACT(YEAR from datestatutrefuser)=? AND trashed =FALSE
    AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=? order by datecreation  asc";

$idcentrale = (int) ($_GET['centrale']);

if(isset($_GET['anneeExport'])){
    $anneeExport = $_GET['anneeExport'];
}else{
    $anneeExport = date('Y');
}
if ($idcentrale != 0) {
    $donnee = array($idcentrale, $anneeExport, REFUSE, ACCEPTE, CLOTURE, $idcentrale, $anneeExport, $anneeExport, REFUSE, ACCEPTE, CLOTURE, $idcentrale, $anneeExport, $anneeExport, REFUSE, ACCEPTE, CLOTURE, $idcentrale, $anneeExport, $anneeExport, REFUSE, ACCEPTE, CLOTURE,
        $idcentrale, $anneeExport, REFUSE, ACCEPTE, CLOTURE, $idcentrale, $anneeExport, REFUSE, ACCEPTE, CLOTURE);
    $row = $manager->getListbyArray($sql, $donnee);
    echo TXT_NBPROJET.': '.  count($row);
} else {
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
    echo TXT_NBPROJET.': '.$nbtotalprojet;
}




BD::deconnecter();
