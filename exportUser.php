<?php

session_start();
include 'decide-lang.php';
include 'class/Manager.php';
include_once 'outils/toolBox.php';
include_once 'outils/constantes.php';
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
$originalDate = date('d-m-Y');
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
$idcentrale = $manager->getSingle2("SELECT idcentrale_centrale FROM loginpassword,utilisateur WHERE idlogin = idlogin_loginpassword and pseudo=?", $_SESSION['pseudo']);
$idtypeuser = $manager->getSingle2("SELECT idtypeutilisateur_typeutilisateur FROM loginpassword,utilisateur WHERE idlogin = idlogin_loginpassword and pseudo=?", $_SESSION['pseudo']);
$data = utf8_decode("" . TXT_DATEINSCRIPTION . ";" . TXT_LOGIN . ";" . TXT_NOM . ";" . TXT_PRENOM . ";" . TXT_ADRESSE . ";" . TXT_MAIL . ";" . TXT_PAYS . ";" . TXT_NOMRESPONSABLE . ";" . TXT_EMAILRESPONSABLE . ";" . TXT_TYPEUTILISATEUR . ";" . TXT_CENTRALE . ";" . TXT_CODEUNITE . ";"
        . TXT_AUTRECODEUNITE . ";" . TXT_NOMENTELABO . "; " . TXT_NOMENTREPRISE . ";" . TXT_QUALITE . ";" . TXT_USER . ";" . TXT_NOMEMPLOYEUR . ";" . TXT_TUTELLE . ";" . TXT_DISCIPLINESCIENTIFIQUE . ";" . TXT_NBPROJETCREER . ";" . TXT_NBPROJETAFFECTER .
        ";" . TXT_STATUTCOMPTE . ";" . TXT_NOMEQUIPE);
$data .= "\n";
if ($idtypeuser == ADMINNATIONNAL) {
    $acainterne = "
SELECT nom,prenom,idutilisateur,mail,pseudo,adresse,codepostal,ville,datecreation,telephone,nomresponsable,mailresponsable,idtypeutilisateur_typeutilisateur,idcentrale_centrale,
idautrecodeunite_autrecodeunite,entrepriselaboratoire,nomentreprise,idqualitedemandeuraca_qualitedemandeuraca,idemployeur_nomemployeur,idtutelle_tutelle,idautrestutelle_autrestutelle,
idautrediscipline_autredisciplinescientifique,idautrenomemployeur_autrenomemployeur,idqualitedemandeurindust_qualitedemandeurindust,idlogin_loginpassword,
iddiscipline_disciplinescientifique,idpays_pays,acronymelaboratoire,fax,actif,nomequipe 
FROM utilisateur,loginpassword WHERE idlogin_loginpassword = idlogin and idqualitedemandeuraca_qualitedemandeuraca is not null and idcentrale_centrale is not null";
    $acaexterne = "
SELECT nom,prenom,idutilisateur,mail,pseudo,adresse,codepostal,ville,datecreation,telephone,nomresponsable,mailresponsable,idtypeutilisateur_typeutilisateur,idcentrale_centrale,
idautrecodeunite_autrecodeunite,entrepriselaboratoire,nomentreprise,idqualitedemandeuraca_qualitedemandeuraca,idemployeur_nomemployeur,idtutelle_tutelle,idautrestutelle_autrestutelle,
idautrediscipline_autredisciplinescientifique,idautrenomemployeur_autrenomemployeur,idqualitedemandeurindust_qualitedemandeurindust,idlogin_loginpassword,
iddiscipline_disciplinescientifique,idpays_pays,acronymelaboratoire,fax,actif,nomequipe
FROM utilisateur,loginpassword WHERE idlogin_loginpassword = idlogin and idqualitedemandeuraca_qualitedemandeuraca is not null and idcentrale_centrale is null";
    $industriel = "
SELECT nom,prenom,idutilisateur,mail,pseudo,adresse,codepostal,ville,datecreation,telephone,nomresponsable,mailresponsable,idtypeutilisateur_typeutilisateur,idcentrale_centrale,
idautrecodeunite_autrecodeunite,entrepriselaboratoire,nomentreprise,idqualitedemandeuraca_qualitedemandeuraca,idemployeur_nomemployeur,idtutelle_tutelle,idautrestutelle_autrestutelle,
idautrediscipline_autredisciplinescientifique,idautrenomemployeur_autrenomemployeur,idqualitedemandeurindust_qualitedemandeurindust,idlogin_loginpassword,
iddiscipline_disciplinescientifique,idpays_pays,acronymelaboratoire,fax,actif,nomequipe
FROM utilisateur,loginpassword WHERE idlogin_loginpassword = idlogin and idqualitedemandeurindust_qualitedemandeurindust is not null";

    if (isset($_POST['academiqueinterne']) && isset($_POST['academiqueexterne']) && isset($_POST['industriel'])) {//ACADEMIQUE INTERNE + EXTERNE+INDUSTRIEL
        $row = $manager->getList($acainterne . " union " . $acaexterne . " union " . $industriel . " order by idutilisateur asc;");
    } elseif (isset($_POST['academiqueinterne']) && isset($_POST['academiqueexterne']) && !isset($_POST['industriel'])) {//ACADEMIQUE INTERNE + EXTERNE
        $row = $manager->getList($acainterne . " union " . $acaexterne . " order by idutilisateur asc;");
    } elseif (!isset($_POST['academiqueinterne']) && isset($_POST['academiqueexterne']) && isset($_POST['industriel'])) {//ACADEMIQUE EXTERNE+INDUSTRIEL
        $row = $manager->getList($acaexterne . " union " . $industriel . " order by idutilisateur asc;");
    } elseif (isset($_POST['academiqueinterne']) && !isset($_POST['academiqueexterne']) && isset($_POST['industriel'])) {//ACADEMIQUE EXTERNE+INDUSTRIEL
        $row = $manager->getList($acainterne . " union " . $industriel . " order by idutilisateur asc;");
    } elseif (isset($_POST['academiqueinterne']) && !isset($_POST['academiqueexterne']) && !isset($_POST['industriel'])) {//ACADEMIQUE INTERNE
        $row = $manager->getList($acainterne . " order by idutilisateur asc;");
    } elseif (!isset($_POST['academiqueinterne']) && isset($_POST['academiqueexterne']) && !isset($_POST['industriel'])) {//ACADEMIQUE EXTERNE
        $row = $manager->getList($acaexterne . " order by idutilisateur asc;");
    } elseif (!isset($_POST['academiqueinterne']) && !isset($_POST['academiqueexterne']) && isset($_POST['industriel'])) {//INDUSTRIEL
        $row = $manager->getList($industriel . " order by idutilisateur asc;");
    } elseif (!isset($_POST['academiqueinterne']) && !isset($_POST['academiqueexterne']) && !isset($_POST['industriel'])) {//PAS DE CASE COCHER
        header('location:/' . REPERTOIRE . '/exportUtilisateur.php?lang=' . $lang . '&msgerr');
    }
} elseif ($idtypeuser == ADMINLOCAL) {
    $acaexternecentrale = "SELECT  u.idutilisateur,l.pseudo, l.mail,c.idcentrale_centrale,u.nom,u.prenom,p.idprojet,u.adresse,u.codepostal,u.ville,u.datecreation,u.nomresponsable,u.mailresponsable,u.telephone,u.idtypeutilisateur_typeutilisateur,
u.idautrenomemployeur_autrenomemployeur,u.entrepriselaboratoire,u.nomentreprise,u.idqualitedemandeuraca_qualitedemandeuraca,u.idtutelle_tutelle,u.idemployeur_nomemployeur,u.idautrestutelle_autrestutelle,u.idautrediscipline_autredisciplinescientifique,
u.iddiscipline_disciplinescientifique,u.idlogin_loginpassword,u.idpays_pays,u.idautrecodeunite_autrecodeunite,u.acronymelaboratoire,u.idqualitedemandeurindust_qualitedemandeurindust,l.actif,l.nomequipe
FROM utilisateur u,utilisateurporteurprojet up,concerne c,loginpassword l,projet p
WHERE up.idprojet_projet = p.idprojet AND up.idutilisateur_utilisateur = u.idutilisateur AND c.idprojet_projet = p.idprojet AND l.idlogin = u.idlogin_loginpassword and u.idcentrale_centrale is null and c.idcentrale_centrale=? and u.idqualitedemandeurindust_qualitedemandeurindust is null
UNION
SELECT u.idutilisateur,l.pseudo,l.mail,co.idcentrale_centrale,u.nom,u.prenom,p.idprojet,u.adresse,u.codepostal,u.ville,u.datecreation,u.nomresponsable,u.mailresponsable,u.telephone,u.idtypeutilisateur_typeutilisateur,
u.idautrenomemployeur_autrenomemployeur,u.entrepriselaboratoire,u.nomentreprise,u.idqualitedemandeuraca_qualitedemandeuraca,u.idtutelle_tutelle,u.idemployeur_nomemployeur,u.idautrestutelle_autrestutelle,u.idautrediscipline_autredisciplinescientifique,
  u.iddiscipline_disciplinescientifique,u.idlogin_loginpassword,u.idpays_pays,u.idautrecodeunite_autrecodeunite,u.acronymelaboratoire,u.idqualitedemandeurindust_qualitedemandeurindust,l.actif,l.nomequipe
  FROM utilisateur u,concerne co,loginpassword l,projet p,creer c
  WHERE co.idprojet_projet = p.idprojet AND  l.idlogin = u.idlogin_loginpassword AND  c.idutilisateur_utilisateur = u.idutilisateur and u.idcentrale_centrale is null AND  c.idprojet_projet = p.idprojet and co.idcentrale_centrale=? and u.idqualitedemandeurindust_qualitedemandeurindust is null
  ";
    $acainternecentrale = "SELECT  u.idutilisateur,l.pseudo, l.mail,c.idcentrale_centrale,u.nom,u.prenom,p.idprojet,u.adresse,u.codepostal,u.ville,u.datecreation,u.nomresponsable,u.mailresponsable,u.telephone,u.idtypeutilisateur_typeutilisateur,
u.idautrenomemployeur_autrenomemployeur,u.entrepriselaboratoire,u.nomentreprise,u.idqualitedemandeuraca_qualitedemandeuraca,u.idtutelle_tutelle,u.idemployeur_nomemployeur,u.idautrestutelle_autrestutelle,u.idautrediscipline_autredisciplinescientifique,
u.iddiscipline_disciplinescientifique,u.idlogin_loginpassword,u.idpays_pays,u.idautrecodeunite_autrecodeunite,u.acronymelaboratoire,u.idqualitedemandeurindust_qualitedemandeurindust,l.actif,l.nomequipe
FROM utilisateur u,utilisateurporteurprojet up,concerne c,loginpassword l,projet p
WHERE up.idprojet_projet = p.idprojet AND up.idutilisateur_utilisateur = u.idutilisateur AND c.idprojet_projet = p.idprojet AND l.idlogin = u.idlogin_loginpassword and u.idcentrale_centrale is not null and c.idcentrale_centrale=?
UNION
SELECT u.idutilisateur,l.pseudo,l.mail,co.idcentrale_centrale,u.nom,u.prenom,p.idprojet,u.adresse,u.codepostal,u.ville,u.datecreation,u.nomresponsable,u.mailresponsable,u.telephone,u.idtypeutilisateur_typeutilisateur,
u.idautrenomemployeur_autrenomemployeur,u.entrepriselaboratoire,u.nomentreprise,u.idqualitedemandeuraca_qualitedemandeuraca,u.idtutelle_tutelle,u.idemployeur_nomemployeur,u.idautrestutelle_autrestutelle,u.idautrediscipline_autredisciplinescientifique,
  u.iddiscipline_disciplinescientifique,u.idlogin_loginpassword,u.idpays_pays,u.idautrecodeunite_autrecodeunite,u.acronymelaboratoire,u.idqualitedemandeurindust_qualitedemandeurindust,l.actif,l.nomequipe
  FROM utilisateur u,concerne co,loginpassword l,projet p,creer c
  WHERE co.idprojet_projet = p.idprojet AND  l.idlogin = u.idlogin_loginpassword AND  c.idutilisateur_utilisateur = u.idutilisateur and u.idcentrale_centrale is not null AND  c.idprojet_projet = p.idprojet and co.idcentrale_centrale=?";

    $industrielcentrale = "SELECT u.idutilisateur,l.pseudo,l.mail,co.idcentrale_centrale,u.nom,u.prenom,p.idprojet,u.adresse,u.codepostal,u.ville,u.datecreation,u.nomresponsable,u.mailresponsable,u.telephone,u.idtypeutilisateur_typeutilisateur,
u.idautrenomemployeur_autrenomemployeur,u.entrepriselaboratoire,u.nomentreprise,u.idqualitedemandeuraca_qualitedemandeuraca,u.idtutelle_tutelle,u.idemployeur_nomemployeur,u.idautrestutelle_autrestutelle,u.idautrediscipline_autredisciplinescientifique,
  u.iddiscipline_disciplinescientifique,u.idlogin_loginpassword,u.idpays_pays,u.idautrecodeunite_autrecodeunite,u.acronymelaboratoire,u.idqualitedemandeurindust_qualitedemandeurindust,l.actif,l.nomequipe
  FROM utilisateur u,concerne co,loginpassword l,projet p,creer c
  WHERE co.idprojet_projet = p.idprojet AND  l.idlogin = u.idlogin_loginpassword AND  c.idutilisateur_utilisateur = u.idutilisateur and u.idqualitedemandeurindust_qualitedemandeurindust is not null  AND  c.idprojet_projet = p.idprojet
  and co.idcentrale_centrale=?";
    $acainterne = "SELECT nom,prenom,idutilisateur,mail,pseudo,adresse,codepostal,ville,datecreation,telephone,nomresponsable,mailresponsable,idtypeutilisateur_typeutilisateur,idcentrale_centrale,
  idautrecodeunite_autrecodeunite,entrepriselaboratoire,nomentreprise,idqualitedemandeuraca_qualitedemandeuraca,idemployeur_nomemployeur,idtutelle_tutelle,idautrestutelle_autrestutelle,
  idautrediscipline_autredisciplinescientifique,idautrenomemployeur_autrenomemployeur,idqualitedemandeurindust_qualitedemandeurindust,idlogin_loginpassword,
  iddiscipline_disciplinescientifique,idpays_pays,acronymelaboratoire,fax,actif,nomequipe FROM utilisateur,loginpassword WHERE idlogin_loginpassword = idlogin and idqualitedemandeuraca_qualitedemandeuraca is not null and idcentrale_centrale is not null
		and idcentrale_centrale =?";
    $acaexterne = "SELECT nom,prenom,idutilisateur,mail,pseudo,adresse,codepostal,ville,datecreation,telephone,nomresponsable,mailresponsable,idtypeutilisateur_typeutilisateur,idcentrale_centrale,
  idautrecodeunite_autrecodeunite,entrepriselaboratoire,nomentreprise,idqualitedemandeuraca_qualitedemandeuraca,idemployeur_nomemployeur,idtutelle_tutelle,idautrestutelle_autrestutelle,
  idautrediscipline_autredisciplinescientifique,idautrenomemployeur_autrenomemployeur,idqualitedemandeurindust_qualitedemandeurindust,idlogin_loginpassword,
  iddiscipline_disciplinescientifique,idpays_pays,acronymelaboratoire,fax,actif,nomequipe
FROM utilisateur,loginpassword WHERE idlogin_loginpassword = idlogin and idqualitedemandeuraca_qualitedemandeuraca is not null and idcentrale_centrale is null";
    $industriel = "SELECT nom,prenom,idutilisateur,mail,pseudo,adresse,codepostal,ville,datecreation,telephone,nomresponsable,mailresponsable,idtypeutilisateur_typeutilisateur,idcentrale_centrale,
  idautrecodeunite_autrecodeunite,entrepriselaboratoire,nomentreprise,idqualitedemandeuraca_qualitedemandeuraca,idemployeur_nomemployeur,idtutelle_tutelle,idautrestutelle_autrestutelle,
  idautrediscipline_autredisciplinescientifique,idautrenomemployeur_autrenomemployeur,idqualitedemandeurindust_qualitedemandeurindust,idlogin_loginpassword,
  iddiscipline_disciplinescientifique,idpays_pays,acronymelaboratoire,fax,actif,nomequipe
FROM utilisateur,loginpassword WHERE idlogin_loginpassword = idlogin and idqualitedemandeurindust_qualitedemandeurindust is not null";

    if (isset($_POST['allacademiqueinterne']) && isset($_POST['allacademiqueexterne']) && isset($_POST['allindustriel'])) {//ACADEMIQUE INTERNE +EXTERNE + INDUSTRIEL
        $manager->exeRequete("drop table if exists tmpusercentrale;");
        $manager->getRequete("create table tmpusercentrale as( " . $acainterne . " union " . $acaexterne . " union " . $industriel . " )order by idutilisateur asc", array($idcentrale));
        $row = $manager->getList("select distinct on (idutilisateur) * from tmpusercentrale order by idutilisateur asc;");
    } if (isset($_POST['allacademiqueinterne']) && isset($_POST['allacademiqueexterne']) && !isset($_POST['allindustriel'])) {//ACADEMIQUE INTERNE +EXTERNE
        $manager->exeRequete("drop table if exists tmpusercentrale;");
        $manager->getRequete("create table tmpusercentrale as( " . $acainterne . " union " . $acaexterne . " )order by idutilisateur asc", array($idcentrale));
        $row = $manager->getList("select distinct on (idutilisateur) * from tmpusercentrale order by idutilisateur asc;");
    } if (!isset($_POST['allacademiqueinterne']) && isset($_POST['allacademiqueexterne']) && isset($_POST['allindustriel'])) {//ACADEMIQUE EXTERNE + INDUSTRIEL
        $manager->exeRequete("drop table if exists tmpusercentrale;");
        $manager->getRequete("create table tmpusercentrale as( " . $acaexterne . " union " . $industriel . " )order by idutilisateur asc", array());
        $row = $manager->getList("select distinct on (idutilisateur) * from tmpusercentrale order by idutilisateur asc;");
    } if (isset($_POST['allacademiqueinterne']) && !isset($_POST['allacademiqueexterne']) && isset($_POST['allindustriel'])) {//ACADEMIQUE INTERNE  + INDUSTRIEL
        $manager->exeRequete("drop table if exists tmpusercentrale;");
        $manager->getRequete("create table tmpusercentrale as( " . $acainterne . " union " . $industriel . " )order by idutilisateur asc", array($idcentrale));
        $row = $manager->getList("select distinct on (idutilisateur) * from tmpusercentrale order by idutilisateur asc;");
    } elseif (isset($_POST['allacademiqueinterne']) && !isset($_POST['allacademiqueexterne']) && !isset($_POST['allindustriel'])) {//ACADEMIQUE INTERNE SEUL
        $manager->exeRequete("drop table if exists tmpusercentrale;");
        $manager->getRequete("create table tmpusercentrale as (" . $acainterne . ");", array($idcentrale));
        $row = $manager->getList("select distinct on (idutilisateur) * from tmpusercentrale order by idutilisateur asc;");
    } elseif (!isset($_POST['allacademiqueinterne']) && isset($_POST['allacademiqueexterne']) && !isset($_POST['allindustriel'])) {//ALLACADEMIQUE EXTERNE SEUL
        $manager->exeRequete("drop table if exists tmpusercentrale;");
        $manager->getRequete("create table tmpusercentrale as (" . $acaexterne . ");", array());
        $row = $manager->getList("select distinct on (idutilisateur) * from tmpusercentrale order by idutilisateur asc;");
    } elseif (!isset($_POST['allacademiqueinterne']) && !isset($_POST['allacademiqueexterne']) && isset($_POST['allindustriel'])) {//ALLINDUSTRIEL SEUL
        $manager->exeRequete("drop table if exists tmpusercentrale;");
        $manager->getRequete("create table tmpusercentrale as(" . $industriel . ");", array());
        $row = $manager->getList("select distinct on (idutilisateur) * from tmpusercentrale order by idutilisateur asc;");
    } elseif (!isset($_POST['allacademiqueinterne']) && !isset($_POST['allacademiqueexterne']) && !isset($_POST['allindustriel']) && !isset($_POST['academiqueexterne']) && !isset($_POST['industriel']) && !isset($_POST['academiqueinterne'])) {//PAS DE CASE COCHER
        header('location:/' . REPERTOIRE . '/exportTousUtilisateur.php?lang=' . $lang . '&msgerr');
    }
    if (isset($_POST['academiqueinterne']) && isset($_POST['academiqueexterne']) && isset($_POST['industriel'])) {//ACADEMIQUE INTERNE +EXTERNE + INDUSTRIEL
        $manager->exeRequete("drop table if exists tmpusercentrale;");
        $manager->getRequete("create table tmpusercentrale as(" . $acainternecentrale . " union " . $industrielcentrale . " union " . $acaexternecentrale . ");", array($idcentrale, $idcentrale, $idcentrale, $idcentrale, $idcentrale));
        $row = $manager->getList("select distinct on (idutilisateur) * from tmpusercentrale order by idutilisateur asc;");
    } elseif (isset($_POST['academiqueinterne']) && !isset($_POST['academiqueexterne']) && isset($_POST['industriel'])) {//ACADEMIQUE INTERNE  + INDUSTRIEL
        $manager->exeRequete("drop table if exists tmpusercentrale;");
        $manager->getRequete("create table tmpusercentrale as(" . $acainternecentrale . " union " . $industrielcentrale . ");", array($idcentrale, $idcentrale, $idcentrale));
        $row = $manager->getList("select distinct on (idutilisateur) * from tmpusercentrale order by idutilisateur asc;");
    } elseif (isset($_POST['academiqueinterne']) && isset($_POST['academiqueexterne']) && !isset($_POST['industriel'])) {//ACADEMIQUE INTERNE +EXTERNE
        $manager->exeRequete("drop table if exists tmpusercentrale;");
        $manager->getRequete("create table tmpusercentrale as(" . $acainternecentrale . " union  " . $acaexternecentrale . ");", array($idcentrale, $idcentrale, $idcentrale, $idcentrale));
        $row = $manager->getList("select distinct on (idutilisateur) * from tmpusercentrale order by idutilisateur asc;");
    } elseif (!isset($_POST['academiqueinterne']) && isset($_POST['academiqueexterne']) && isset($_POST['industriel'])) {//ACADEMIQUE EXTERNE + INDUSTRIEL
        $manager->exeRequete("drop table if exists tmpusercentrale;");
        $manager->getRequete("create table tmpusercentrale as(" . $industrielcentrale . " union " . $acaexternecentrale . ");", array($idcentrale, $idcentrale, $idcentrale));
        $row = $manager->getList("select distinct on (idutilisateur) * from tmpusercentrale order by idutilisateur asc;");
    } elseif (isset($_POST['academiqueinterne']) && isset($_POST['academiqueexterne']) && isset($_POST['industriel'])) {//ACADEMIQUE INTERNE +EXTERNE + INDUSTRIEL
        $manager->exeRequete("drop table if exists tmpusercentrale;");
        $manager->getRequete("create table tmpusercentrale as(" . $acainternecentrale . " union " . $industrielcentrale . " union " . $acaexternecentrale . ");", array($idcentrale, $idcentrale, $idcentrale, $idcentrale, $idcentrale));
        $row = $manager->getList("select distinct on (idutilisateur) * from tmpusercentrale order by idutilisateur asc;");
    } elseif (isset($_POST['academiqueinterne']) && !isset($_POST['academiqueexterne']) && !isset($_POST['industriel'])) {//ACADEMIQUE INTERNE
        $manager->exeRequete("drop table if exists tmpusercentrale;");
        $manager->getRequete("create table tmpusercentrale as(" . $acainternecentrale . ");", array($idcentrale, $idcentrale));
        $row = $manager->getList("select distinct on (idutilisateur) * from tmpusercentrale order by idutilisateur asc;");
    } elseif (!isset($_POST['academiqueinterne']) && isset($_POST['academiqueexterne']) && !isset($_POST['industriel'])) {//ACADEMIQUE EXTERNE
        $manager->exeRequete("drop table if exists tmpusercentrale;");
        $manager->getRequete("create table tmpusercentrale as(" . $acaexternecentrale . ");", array($idcentrale, $idcentrale));
        $row = $manager->getList("select distinct on (idutilisateur) * from tmpusercentrale order by idutilisateur asc;");
    } elseif (!isset($_POST['academiqueinterne']) && !isset($_POST['academiqueexterne']) && isset($_POST['industriel'])) {//INDUSTRIEL
        $manager->exeRequete("drop table if exists tmpusercentrale;");
        $manager->getRequete("create table tmpusercentrale as(" . $industrielcentrale . ");", array($idcentrale));
        $row = $manager->getList("select distinct on (idutilisateur) * from tmpusercentrale order by idutilisateur asc;");
    }
}
// ENREGISTREMENT DES RESULTATS LIGNE PAR LIGNE
$nbrow = count($row);
for ($i = 0; $i < $nbrow; $i++) {
    $idpays = $row[$i]['idpays_pays'];
    $nompays = $manager->getSingle("select nompays from pays where idpays=" . $idpays . "");
    if ($row[$i]['idqualitedemandeuraca_qualitedemandeuraca'] > 0) {
        $libqualite = $manager->getSingle2("select libellequalitedemandeuraca from qualitedemandeuraca where idqualitedemandeuraca=?", $row[$i]['idqualitedemandeuraca_qualitedemandeuraca']);
        $libtypeuser = TXT_ACADEMIQUE;
    } else {
        $libqualite = $manager->getSingle2("select libellequalitedemandeurindust from qualitedemandeurindust where idqualitedemandeurindust=?", $row[$i]['idqualitedemandeurindust_qualitedemandeurindust']);
        $libtypeuser = TXT_INDUSTRIEL;
    }
    $idautre = $manager->getSingle2("select idtutelle from tutelle where libelletutelle=?", 'Autres');
    if (isset($row[$i]['idtutelle_tutelle']) && $row[$i]['idtutelle_tutelle'] != $idautre) {
        $libtutelle = $manager->getSingle2("select libelletutelle from tutelle where idtutelle =?", $row[$i]['idtutelle_tutelle']);
    } else {
        $libtutelle = $manager->getSingle2("select libelleautrestutelle from autrestutelle where idautrestutelle = ?", $row[$i]['idautrestutelle_autrestutelle']);
    }
    if (isset($row[$i]['idtypeutilisateur_typeutilisateur'])) {
        $libelletypeutilisateur = $manager->getSingle2("select libelletype from typeutilisateur where idtypeutilisateur=?", $row[$i]['idtypeutilisateur_typeutilisateur']);
    }
//TRAITEMENT DU CODE UNITE
    if (!empty($row[$i]['idcentrale_centrale'])) {
        $libellecentrale = $manager->getSingle2("select libellecentrale from centrale where idcentrale=?", $row[$i]['idcentrale_centrale']);
        $codeunite = $manager->getSingle2("select codeunite from centrale where idcentrale=?", $row[$i]['idcentrale_centrale']);
    } else {
        $codeunite = '';
        $libellecentrale = '';
    }
    if (isset($row[$i]['idautrecodeunite_autrecodeunite']) && $row[$i]['idautrecodeunite_autrecodeunite'] != 1) {
        $autrecodeunite = $manager->getSingle2("select libelleautrecodeunite from autrecodeunite where idautrecodeunite=?", $row[$i]['idautrecodeunite_autrecodeunite']);
    } else {
        $autrecodeunite = '';
    }
    $idautreDis = $manager->getSingle2("select iddiscipline from disciplinescientifique where libellediscipline=?", 'Autres');
    if (isset($row[$i]['iddiscipline_disciplinescientifique']) && $row[$i]['iddiscipline_disciplinescientifique'] != $idautreDis) {///AUTRES
        $libdiscipline = $manager->getSingle2("select libellediscipline from disciplinescientifique where  iddiscipline = ?", $row[$i]['iddiscipline_disciplinescientifique']);
    } else {
        $libdiscipline = $manager->getSingle2("select libelleautrediscipline from autredisciplinescientifique where idautrediscipline = ?", $row[$i]['idautrediscipline_autredisciplinescientifique']);
    }
//NOM EMPLOYEUR
    $idautrennom = $manager->getSingle2("select idemployeur from nomemployeur where libelleemployeur =?", 'Autres');
    if (isset($row[$i]['idemployeur_nomemployeur']) && $row[$i]['idemployeur_nomemployeur'] != $idautrennom) {// Autres
        $nomemployeur = $manager->getSingle2("select libelleemployeur from nomemployeur where idemployeur=?", $row[$i]['idemployeur_nomemployeur']);
    } else {
        $nomemployeur = $manager->getSingle2("select libelleautrenomemployeur from autrenomemployeur where idautrenomemployeur=?", $row[$i]['idautrenomemployeur_autrenomemployeur']);
    }
    if (isset($row[$i]['entrepriselaboratoire']) && !empty($row[$i]['entrepriselaboratoire'])) {
        $entrepriselaboratoire = $row[$i]['entrepriselaboratoire'];
    } else {
        $entrepriselaboratoire = "";
    }
    if (!empty($row[$i]['nomentreprise'])) {
        $nomentreprise = $row[$i]['nomentreprise'];
    } else {
        $nomentreprise = "";
    }
    $nbprojetcreer = $manager->getSingle2("select count(idprojet_projet) from creer where idutilisateur_utilisateur=?", $row[$i]['idutilisateur']);
    $nbprojetaffecter = $manager->getSingle2("select count(idprojet_projet) from utilisateurporteurprojet where idutilisateur_utilisateur=?", $row[$i]['idutilisateur']);

    $Adresse = cleanString($row[$i]['adresse']) . ' ,Ville= ' . cleanString($row[$i]['ville']) . ', CP= ' . $row[$i]['codepostal'] . ",Tel= " . $row[$i]['telephone'];
    $adresse = str_replace(";", ",", $Adresse);

    if ($row[$i]['actif'] == 1) {
        $statut = 'Actif';
    } else {
        $statut = utf8_decode('Désactivé');
    }
//DATE POUR LE NOM DU FICHIER CSV DE SORTIE
    $data .= "" . removeDoubleQuote($row[$i]['datecreation']) . ";" .
            cleanString($row[$i]['pseudo']) . ";" .
            cleanString($row[$i]['nom']) . ";" .
            cleanString($row[$i]['prenom']) . ";" .
            $adresse . ";" .
            removeDoubleQuote($row[$i]['mail']) . ";" .
            utf8_decode($nompays) . ";" .
            cleanString($row[$i]['nomresponsable']) . ";" .
            cleanString($row[$i]['mailresponsable']) . ";" .
            utf8_decode($libelletypeutilisateur) . ";" .
            utf8_decode($libellecentrale) . ";" .
            utf8_decode($codeunite) . ";" .
            cleanString($autrecodeunite) . ";" .
            cleanString($entrepriselaboratoire) . ";" .
            cleanString($nomentreprise) . ";" .
            utf8_decode($libqualite) . ";" .
            utf8_decode($libtypeuser) . ";" .
            cleanString($nomemployeur) . ";" .
            cleanString($libtutelle) . ";" .
            cleanString($libdiscipline) . ";" .
            $nbprojetcreer . ";" .
            $nbprojetaffecter . ";"
            . $statut . ";"
            . cleanString($row[$i]['nomequipe']) . "\n";
}
// Déclaration du type de contenu
header("Content-type: application/vnd.ms-excel;charset=UTF-8");
header("Content-disposition: attachment; filename=export_utilisateur_" . $originalDate . ".csv");
print $data;
exit;
BD::deconnecter();
