<?php

session_start();
include '../decide-lang.php';
include_once '../class/Chiffrement.php';
include '../class/Manager.php';
include_once '../outils/toolBox.php';
$db = BD::connecter();
$manager = new Manager($db);
if (isset($_POST['pseudo'])) {
    $pseudo = pg_escape_string($_POST['pseudo']);
    $_SESSION['pseudo'] = $pseudo;
} elseif (isset($_SESSION['pseudo'])) {
    $pseudo = $_SESSION['pseudo'];
}
if (isset($_POST['email'])) {
    $mail = pg_escape_string($_POST['email']);
    $_SESSION['mail'] = $mail;
} elseif (isset($_SESSION['email'])) {
    $mail = $_SESSION['email'];
    $_SESSION['mail'] = $mail;
} else {
    $mail = $_SESSION['mail'];
    $_SESSION['mail'] = $mail;
}

nomEntete($mail, $pseudo);
// VERIFICATION DE L'EXISTENCE DE L'UTILISATEUR
$idLogin = $manager->getSingle2("SELECT idlogin FROM loginpassword where pseudo=?", $pseudo);

if (!empty($idLogin)) {
    $_SESSION['idutilisateur'] = $manager->getSingle2("SELECT idutilisateur   FROM utilisateur where idlogin_loginpassword=?", $idLogin);
}
if (!empty($_SESSION['idutilisateur'])) {
    $identite = $manager->getListbyArray("SELECT nom,prenom,adresse,codepostal,ville,telephone,fax FROM utilisateur where idutilisateur=?", array($_SESSION['idutilisateur']));
    $_SESSION['nom'] = $identite[0][0];
    $_SESSION['prenom'] = $identite[0][1];
    $_SESSION['adresse'] = $identite[0][2];
    $_SESSION['codepostale'] = $identite[0][3];
    $_SESSION['ville'] = $identite[0][4];
    $_SESSION['tel'] = $identite[0][5];
    $_SESSION['fax'] = $identite[0][6];
}

if (empty($idLogin) || $idLogin = "") {
    //L'UTILISATEUR N'EXISTE PAS
    header('location: ../loginErreurProjet.php?lang=' . $lang . '');
} else {
//---------------------------------------------------------------------------------------------------------------------------------------------------
//                              CREATION DU PROJET JSON EN COURS
//---------------------------------------------------------------------------------------------------------------------------------------------------
    $manager->exeRequete("drop table if exists tmptousmesprojet;");
//CREATION DE LA TABLE TEMPORAIRE
    $manager->getRequete("create table tmptousmesprojet as
    (
 SELECT p.idprojet,s.libellestatutprojet,p.numero,p.titre,p.datedebutprojet,null as nomAffecte,null as prenomAffecte,ce.libellecentrale
  FROM  projet p,utilisateur u,creer cr,centrale ce,concerne co,typeprojet t,statutprojet s, loginpassword l
  WHERE cr.idprojet_projet = p.idprojet AND cr.idutilisateur_utilisateur = u.idutilisateur AND co.idcentrale_centrale = ce.idcentrale AND
  co.idprojet_projet = p.idprojet AND  t.idtypeprojet = p.idtypeprojet_typeprojet AND  l.idlogin = u.idlogin_loginpassword AND   l.pseudo =?
  AND s.idstatutprojet = co.idstatutprojet_statutprojet 
union
SELECT idprojet,s.libellestatutprojet,numero,titre,datedebutprojet,u1.nom,u1.prenom,ce.libellecentrale
FROM projet,utilisateur u,creer,centrale ce,concerne,typeprojet,statutprojet s,utilisateurporteurprojet,utilisateur u1 , loginpassword l
WHERE idtypeprojet_typeprojet = typeprojet.idtypeprojet AND u.idutilisateur = creer.idutilisateur_utilisateur AND
  creer.idprojet_projet = idprojet AND concerne.idcentrale_centrale = idcentrale AND concerne.idprojet_projet = idprojet AND
  concerne.idstatutprojet_statutprojet = s.idstatutprojet AND utilisateurporteurprojet.idprojet_projet = idprojet AND
  utilisateurporteurprojet.idutilisateur_utilisateur = u1.idutilisateur AND  l.idlogin = u.idlogin_loginpassword AND  l.pseudo =?

order by idprojet asc
);", array($pseudo, $pseudo));

    $rowEncours = $manager->getList("
   select  idprojet,libellestatutprojet,numero,titre,datedebutprojet,nomaffecte,prenomaffecte,libellecentrale from tmptousmesprojet 
    where idprojet not in (select idprojet from tmptousmesprojet where nomaffecte is not null)
    union 
    select idprojet,libellestatutprojet,numero,titre,datedebutprojet,nomaffecte,prenomaffecte,libellecentrale from tmptousmesprojet where nomaffecte is not null 
    order by idprojet asc
");

    $fpEncours = fopen('../tmp/projetEncours.json', 'w');
    $dataEncours = "";
    fwrite($fpEncours, '{"items": [');
    for ($i = 0; $i < count($rowEncours); $i++) {
        $dataEncours = "" . '{"numero":' . '"' . $rowEncours[$i]['numero'] . '"' . "," . '"datedebutprojet":' . '"' . $rowEncours[$i]['datedebutprojet'] . '"' . "," . '"titre":' . '"' .
                filtredonnee($rowEncours[$i]['titre']) . '"' . "," . '"libellestatutprojet":' . '"' . $rowEncours[$i]['libellestatutprojet'] . '"'
                . "," . '"nomaffecte":' . '"' . filtredonnee($rowEncours[$i]['nomaffecte']) . ' ' . filtredonnee($rowEncours[$i]['prenomaffecte']) . '"'
                . "," . '"libellecentrale":' . '"' . $rowEncours[$i]['libellecentrale'] . '"' . "},";
        fputs($fpEncours, $dataEncours);
        fwrite($fpEncours, '');
    }
    fwrite($fpEncours, ']}');
    $json_fileEncours = "../tmp/projetEncours.json";
    $jsonEncours1 = file_get_contents($json_fileEncours);
    $jsonEncours = str_replace('},]}', '}]}', $jsonEncours1);
    file_put_contents($json_fileEncours, $jsonEncours);
    fclose($fpEncours);
    chmod('../tmp/projetEncours.json', 0777);
    $_SESSION['email'] = $mail;
    $_SESSION['pseudo'] = $pseudo;
}
//---------------------------------------------------------------------------------------------------------------------------------------------------
//                      CREATION DU PROJET JSON EN ATTENTE
//---------------------------------------------------------------------------------------------------------------------------------------------------
$idstatutenattente = $manager->getSingle2('select idstatutprojet from statutprojet where libellestatutprojet=? ', 'En attente');
$rowEnattente = $manager->getListbyArray("SELECT  p.numero,p.dateprojet,p.titre,s.libellestatutprojet,ce.libellecentrale
FROM projet p,concerne co,utilisateur u,centrale ce,loginpassword l,creer cr,statutprojet s
WHERE co.idprojet_projet = p.idprojet AND   co.idcentrale_centrale = ce.idcentrale AND  co.idstatutprojet_statutprojet = s.idstatutprojet AND
u.idutilisateur = cr.idutilisateur_utilisateur AND  l.idlogin = u.idlogin_loginpassword AND cr.idprojet_projet = p.idprojet  AND s.idstatutprojet=? and  l.pseudo=?
union
SELECT p.numero,p.dateprojet,p.titre,s.libellestatutprojet,ce.libellecentrale
FROM utilisateurporteurprojet up,statutprojet s,projet p,utilisateur u,concerne co,centrale ce,loginpassword l
WHERE up.idprojet_projet = p.idprojet AND up.idutilisateur_utilisateur = u.idutilisateur AND s.idstatutprojet = co.idstatutprojet_statutprojet AND
	co.idprojet_projet = p.idprojet AND  ce.idcentrale = co.idcentrale_centrale AND  l.idlogin = u.idlogin_loginpassword AND  s.idstatutprojet=? and  l.pseudo=?
", array($idstatutenattente, $pseudo, $idstatutenattente, $pseudo));


$fpEnattente = fopen('../tmp/projetEnattente.json', 'w');
$dataEnattente = "";
fwrite($fpEnattente, '{"items": [');
for ($i = 0; $i < count($rowEnattente); $i++) {

    $dataEnattente = "" . '{"numero":' . '"' . $rowEnattente[$i]['numero'] . '"' . "," . '"dateprojet":' . '"' . $rowEnattente[$i]['dateprojet'] . '"' . "," . '"titre":' . '"' .
            filtredonnee($rowEnattente[$i]['titre']) . '"' . "," . '"libellestatutprojet":' . '"' . $rowEnattente[$i]['libellestatutprojet'] . '"'
            . "," . '"libellecentrale":' . '"' . $rowEnattente[$i]['libellecentrale'] . '"' . "},";
    fputs($fpEnattente, $dataEnattente);
    fwrite($fpEnattente, '');
}
fwrite($fpEnattente, ']}');
$json_fileenattente = "../tmp/projetEnattente.json";
$jsonenattente1 = file_get_contents($json_fileenattente);
$jsonenattente = str_replace('},]}', '}]}', $jsonenattente1);
file_put_contents($json_fileenattente, $jsonenattente);
fclose($fpEnattente);
chmod('../tmp/projetEnattente.json', 0777);
//---------------------------------------------------------------------------------------------------------------------------------------------------
//                          CREATION DU PROJET JSON EN COURS D'ANALYSE
//---------------------------------------------------------------------------------------------------------------------------------------------------
$idstatutencoursanalyse = $manager->getSinglebyArray('select idstatutprojet from statutprojet where libellestatutprojet like ? and libellestatutprojet like ? ', array('%En cours%', '%analyse%'));
$rowEncoursAnalyse = $manager->getListbyArray("SELECT  p.numero,p.dateprojet,p.titre,s.libellestatutprojet,ce.libellecentrale
FROM projet p,concerne co,utilisateur u,centrale ce,loginpassword l,creer cr,statutprojet s
WHERE co.idprojet_projet = p.idprojet AND   co.idcentrale_centrale = ce.idcentrale AND  co.idstatutprojet_statutprojet = s.idstatutprojet AND
u.idutilisateur = cr.idutilisateur_utilisateur AND  l.idlogin = u.idlogin_loginpassword AND cr.idprojet_projet = p.idprojet AND  s.idstatutprojet=? and  l.pseudo=?
union
SELECT p.numero,p.dateprojet,p.titre,s.libellestatutprojet,ce.libellecentrale
FROM utilisateurporteurprojet up,statutprojet s,projet p,utilisateur u,concerne co,centrale ce,loginpassword l
WHERE up.idprojet_projet = p.idprojet AND up.idutilisateur_utilisateur = u.idutilisateur AND s.idstatutprojet = co.idstatutprojet_statutprojet AND
co.idprojet_projet = p.idprojet AND  ce.idcentrale = co.idcentrale_centrale AND  l.idlogin = u.idlogin_loginpassword AND  s.idstatutprojet=? and  l.pseudo=?

", array($idstatutencoursanalyse, $pseudo, $idstatutencoursanalyse, $pseudo));
if (!empty($rowEncoursAnalyse[0]['description '])) {
    $description = $rowEncoursAnalyse[0]['description '];
} else {
    $description = '';
}

$fpEncoursAnalyse = fopen('../tmp/projetAnalyse.json', 'w');
$dataEncoursAnalyse = "";
fwrite($fpEncoursAnalyse, '{"items": [');
for ($i = 0; $i < count($rowEncoursAnalyse); $i++) {
    $dataEncoursAnalyse = "" . '{"dateProjet":' . '"' . $rowEncoursAnalyse[$i]['dateprojet'] . '"' . "," . '"numero":' . '"' . $rowEncoursAnalyse[$i]['numero'] . '"' . "," . '"titre":' . '"' .
            filtredonnee($rowEncoursAnalyse[$i]['titre']) . '"' . "," . '"libellestatutprojet":' . '"' . $rowEncoursAnalyse[$i]['libellestatutprojet'] . '"' . "," . '"description":' . '"' . $description . '"'
            . "," . '"libellecentrale":' . '"' . $rowEncoursAnalyse[$i]['libellecentrale'] . '"' . "},";
    fputs($fpEncoursAnalyse, $dataEncoursAnalyse);
    fwrite($fpEncoursAnalyse, '');
}
fwrite($fpEncoursAnalyse, ']}');
$json_fileencoursanalyse = "../tmp/projetAnalyse.json";
$jsonencoursanalyse1 = file_get_contents($json_fileencoursanalyse);
$jsonencoursanalyse = str_replace('},]}', '}]}', $jsonencoursanalyse1);
file_put_contents($json_fileencoursanalyse, $jsonencoursanalyse);
fclose($fpEncoursAnalyse);
chmod('../tmp/projetAnalyse.json', 0777);



//---------------------------------------------------------------------------------------------------------------------------------------------------
//                                 CREATION DU PROJET JSON REFUSE
//---------------------------------------------------------------------------------------------------------------------------------------------------
$idstatuterefuse = $manager->getSingle2('select idstatutprojet from statutprojet where libellestatutprojet=? ', 'Refusée');
$rowRefuse = $manager->getListbyArray("SELECT  p.numero,p.dateprojet,p.titre,s.libellestatutprojet,ce.libellecentrale
FROM projet p,concerne co,utilisateur u,centrale ce,loginpassword l,creer cr,statutprojet s
WHERE co.idprojet_projet = p.idprojet AND   co.idcentrale_centrale = ce.idcentrale AND  co.idstatutprojet_statutprojet = s.idstatutprojet AND
u.idutilisateur = cr.idutilisateur_utilisateur AND  l.idlogin = u.idlogin_loginpassword  AND cr.idprojet_projet = p.idprojet AND  s.idstatutprojet=? and  l.pseudo=?
union
SELECT p.numero,p.dateprojet,p.titre,s.libellestatutprojet,ce.libellecentrale
FROM utilisateurporteurprojet up,statutprojet s,projet p,utilisateur u,concerne co,centrale ce,loginpassword l
WHERE up.idprojet_projet = p.idprojet AND up.idutilisateur_utilisateur = u.idutilisateur AND s.idstatutprojet = co.idstatutprojet_statutprojet AND
co.idprojet_projet = p.idprojet AND  ce.idcentrale = co.idcentrale_centrale AND  l.idlogin = u.idlogin_loginpassword AND  s.idstatutprojet=? and  l.pseudo=?

", array($idstatuterefuse, $pseudo, $idstatuterefuse, $pseudo));


$fpRefuse = fopen('../tmp/projetRefuse.json', 'w');
$dataRefuse = "";
fwrite($fpRefuse, '{"items": [');
for ($i = 0; $i < count($rowRefuse); $i++) {
    $dataRefuse = "" . '{"dateprojet":' . '"' . $rowRefuse[$i]['dateprojet'] . '"' . "," . '"numero":' . '"' . $rowRefuse[$i]['numero'] . '"' . "," . '"titre":' . '"' .
            filtredonnee($rowRefuse[$i]['titre']) . '"' . "," . '"libellestatutprojet":' . '"' . $rowRefuse[$i]['libellestatutprojet'] . '"' .
            "," . '"libellecentrale":' . '"' . $rowRefuse[$i]['libellecentrale'] . '"' . "},";
    fputs($fpRefuse, $dataRefuse);
    fwrite($fpRefuse, '');
}
fwrite($fpRefuse, ']}');
$json_filerefuse = "../tmp/projetRefuse.json";
$jsonrefuse1 = file_get_contents($json_filerefuse);
$jsonrefuse = str_replace('},]}', '}]}', $jsonrefuse1);
file_put_contents($json_filerefuse, $jsonrefuse);
fclose($fpRefuse);
chmod('../tmp/projetRefuse.json', 0777);
//---------------------------------------------------------------------------------------------------------------------------------------------------
//	CREATION DU PROJET JSON ACCEPTE
//---------------------------------------------------------------------------------------------------------------------------------------------------
$idstatutaccepte = $manager->getSingle2('select idstatutprojet from statutprojet where libellestatutprojet=? ', 'Acceptée pour expertise');
$manager->exeRequete("drop table if exists tmpaccepte;");
//CREATION DE LA TABLE TEMPORAIRE
$manager->getRequete("create table tmpaccepte as
    (
 SELECT p.idprojet,s.libellestatutprojet,p.numero,p.titre,p.dateprojet,null as nomAffecte,null as prenomAffecte,ce.libellecentrale
  FROM  projet p,utilisateur u,creer cr,centrale ce,concerne co,typeprojet t,statutprojet s, loginpassword l
  WHERE cr.idprojet_projet = p.idprojet AND cr.idutilisateur_utilisateur = u.idutilisateur AND co.idcentrale_centrale = ce.idcentrale AND
  co.idprojet_projet = p.idprojet AND  t.idtypeprojet = p.idtypeprojet_typeprojet AND  l.idlogin = u.idlogin_loginpassword AND  s.idstatutprojet =? AND l.pseudo =?
  AND s.idstatutprojet = co.idstatutprojet_statutprojet 
union
SELECT idprojet,s.libellestatutprojet,numero,titre,dateprojet,u1.nom,u1.prenom,ce.libellecentrale
FROM projet,utilisateur u,creer,centrale ce,concerne,typeprojet,statutprojet s,utilisateurporteurprojet,utilisateur u1 , loginpassword l
WHERE idtypeprojet_typeprojet = typeprojet.idtypeprojet AND u.idutilisateur = creer.idutilisateur_utilisateur AND
  creer.idprojet_projet = idprojet AND concerne.idcentrale_centrale = idcentrale AND concerne.idprojet_projet = idprojet AND
  concerne.idstatutprojet_statutprojet = s.idstatutprojet AND utilisateurporteurprojet.idprojet_projet = idprojet AND
  utilisateurporteurprojet.idutilisateur_utilisateur = u1.idutilisateur AND  l.idlogin = u.idlogin_loginpassword AND  s.idstatutprojet =? AND l.pseudo =?
order by idprojet asc
);", array($idstatutaccepte, $pseudo, $idstatutaccepte, $pseudo));

$rowAccepte = $manager->getList("
   select  idprojet,libellestatutprojet,numero,titre,dateprojet,nomaffecte,prenomaffecte,libellecentrale from tmpaccepte 
    where idprojet not in (select idprojet from tmpaccepte where nomaffecte is not null)
    union 
    select idprojet,libellestatutprojet,numero,titre,dateprojet,nomaffecte,prenomaffecte,libellecentrale from tmpaccepte where nomaffecte is not null 
    order by idprojet asc
");
$fpAccepte = fopen('../tmp/projetAccepte.json', 'w');
$dataAccepte = "";
fwrite($fpAccepte, '{"items": [');
for ($i = 0; $i < count($rowAccepte); $i++) {
    $dataAccepte = "" . '{"dateprojet":' . '"' . $rowAccepte[$i]['dateprojet'] . '"' . "," . '"numero":' . '"' . $rowAccepte[$i]['numero'] . '"' . "," . '"titre":' . '"' .
            filtredonnee($rowAccepte[$i]['titre']) . '"' . "," . '"libellestatutprojet":' . '"' . $rowAccepte[$i]['libellestatutprojet'] . '"'
            . "," . '"nomaffecte":' . '"' . filtredonnee($rowAccepte[$i]['nomaffecte']) . ' ' . filtredonnee($rowAccepte[$i]['prenomaffecte']) . '"'
            . "," . '"libellecentrale":' . '"' . $rowAccepte[$i]['libellecentrale'] . '"' . "},";
    fputs($fpAccepte, $dataAccepte);
    fwrite($fpAccepte, '');
}
fwrite($fpAccepte, ']}');
$json_fileAccepte = "../tmp/projetAccepte.json";
$jsonaccepte = file_get_contents($json_fileAccepte);
$jsonAccepte = str_replace('},]}', '}]}', $jsonaccepte);
file_put_contents($json_fileAccepte, $jsonAccepte);
fclose($fpAccepte);
chmod('../tmp/projetAccepte.json', 0777);
//---------------------------------------------------------------------------------------------------------------------------------------------------
//	CREATION DU PROJET JSON FINI
//---------------------------------------------------------------------------------------------------------------------------------------------------
$idstatutfini = $manager->getSingle2('select idstatutprojet from statutprojet where libellestatutprojet=? ', 'Fini');
$manager->exeRequete("drop table if exists tmpfini;");
$manager->getRequete("create table tmpfini as(
 SELECT p.idprojet,s.libellestatutprojet,p.numero,p.titre,p.datedebutprojet,null as nomAffecte,null as prenomAffecte,ce.libellecentrale
  FROM  projet p,utilisateur u,creer cr,centrale ce,concerne co,typeprojet t,statutprojet s, loginpassword l
  WHERE cr.idprojet_projet = p.idprojet AND cr.idutilisateur_utilisateur = u.idutilisateur AND co.idcentrale_centrale = ce.idcentrale AND
  co.idprojet_projet = p.idprojet AND  t.idtypeprojet = p.idtypeprojet_typeprojet AND  l.idlogin = u.idlogin_loginpassword AND  s.idstatutprojet =? AND l.pseudo =?
  AND s.idstatutprojet = co.idstatutprojet_statutprojet 
union
SELECT idprojet,s.libellestatutprojet,numero,titre,datedebutprojet,u1.nom,u1.prenom,ce.libellecentrale
FROM projet,utilisateur u,creer,centrale ce,concerne,typeprojet,statutprojet s,utilisateurporteurprojet,utilisateur u1 , loginpassword l
WHERE idtypeprojet_typeprojet = typeprojet.idtypeprojet AND u.idutilisateur = creer.idutilisateur_utilisateur AND
  creer.idprojet_projet = idprojet AND concerne.idcentrale_centrale = idcentrale AND concerne.idprojet_projet = idprojet AND
  concerne.idstatutprojet_statutprojet = s.idstatutprojet AND utilisateurporteurprojet.idprojet_projet = idprojet AND
  utilisateurporteurprojet.idutilisateur_utilisateur = u1.idutilisateur AND  l.idlogin = u.idlogin_loginpassword AND  s.idstatutprojet =? AND l.pseudo =?
order by idprojet asc
);", array($idstatutfini, $pseudo, $idstatutfini, $pseudo));
$rowFini = $manager->getList("select  idprojet,libellestatutprojet,numero,titre,datedebutprojet,nomaffecte,prenomaffecte,libellecentrale from tmpfini
    where idprojet not in (select idprojet from tmpfini where nomaffecte is not null)
    union 
    select idprojet,libellestatutprojet,numero,titre,datedebutprojet,nomaffecte,prenomaffecte,libellecentrale from tmpfini where nomaffecte is not null 
    order by idprojet asc");

$fpFini = fopen('../tmp/projetFini.json', 'w');
$dataFini = "";
fwrite($fpFini, '{"items": [');
for ($i = 0; $i < count($rowFini); $i++) {
    $dataFini = "" . '{"datedebutprojet":' . '"' . $rowFini[$i]['datedebutprojet'] . '"' . "," . '"numero":' . '"' . $rowFini[$i]['numero'] . '"' . "," . '"titre":' . '"' .
            filtredonnee($rowFini[$i]['titre']) . '"' . "," . '"libellestatutprojet":' . '"' . $rowFini[$i]['libellestatutprojet'] . '"'
            . "," . '"nomaffecte":' . '"' . filtredonnee($rowFini[$i]['nomaffecte']) . ' ' . filtredonnee($rowFini[$i]['prenomaffecte']) . '"'
            . "," . '"libellecentrale":' . '"' . $rowFini[$i]['libellecentrale'] . '"' . "},";
    fputs($fpFini, $dataFini);
    fwrite($fpFini, '');
}
fwrite($fpFini, ']}');
$json_fileFini = "../tmp/projetFini.json";
$jsonfini = file_get_contents($json_fileFini);
$jsonFini = str_replace('},]}', '}]}', $jsonfini);
file_put_contents($json_fileFini, $jsonFini);
fclose($fpFini);
chmod('../tmp/projetFini.json', 0777);


//---------------------------------------------------------------------------------------------------------------------------------------------------
//		CREATION DU PROJET JSON CLOTURE
//---------------------------------------------------------------------------------------------------------------------------------------------------
$idstatutcloture = $manager->getSingle2('select idstatutprojet from statutprojet where libellestatutprojet=? ', 'Cloturé');

$manager->getRequete("create table tmpcloturer as
    (
 SELECT p.idprojet,s.libellestatutprojet,p.numero,p.titre,p.datedebutprojet,null as nomAffecte,null as prenomAffecte,ce.libellecentrale
  FROM  projet p,utilisateur u,creer cr,centrale ce,concerne co,typeprojet t,statutprojet s, loginpassword l
  WHERE cr.idprojet_projet = p.idprojet AND cr.idutilisateur_utilisateur = u.idutilisateur AND co.idcentrale_centrale = ce.idcentrale AND
  co.idprojet_projet = p.idprojet AND  t.idtypeprojet = p.idtypeprojet_typeprojet AND  l.idlogin = u.idlogin_loginpassword AND  s.idstatutprojet =? AND l.pseudo =?
  AND s.idstatutprojet = co.idstatutprojet_statutprojet 
union
SELECT idprojet,s.libellestatutprojet,numero,titre,datedebutprojet,u1.nom,u1.prenom,ce.libellecentrale
FROM projet,utilisateur u,creer,centrale ce,concerne,typeprojet,statutprojet s,utilisateurporteurprojet,utilisateur u1 , loginpassword l
WHERE idtypeprojet_typeprojet = typeprojet.idtypeprojet AND u.idutilisateur = creer.idutilisateur_utilisateur AND
  creer.idprojet_projet = idprojet AND concerne.idcentrale_centrale = idcentrale AND concerne.idprojet_projet = idprojet AND
  concerne.idstatutprojet_statutprojet = s.idstatutprojet AND utilisateurporteurprojet.idprojet_projet = idprojet AND
  utilisateurporteurprojet.idutilisateur_utilisateur = u1.idutilisateur AND  l.idlogin = u.idlogin_loginpassword AND  s.idstatutprojet =? AND l.pseudo =?
order by idprojet asc
);", array($idstatutcloture, $pseudo, $idstatutcloture, $pseudo));

$rowCloturer = $manager->getList("select  idprojet,libellestatutprojet,numero,titre,datedebutprojet,nomaffecte,prenomaffecte,libellecentrale from tmpcloturer
    where idprojet not in (select idprojet from tmpcloturer where nomaffecte is not null)
    union 
    select idprojet,libellestatutprojet,numero,titre,datedebutprojet,nomaffecte,prenomaffecte,libellecentrale from tmpcloturer where nomaffecte is not null 
    order by idprojet asc");


$fpCloturer = fopen('../tmp/projetCloturer.json', 'w');
$dataCloturer = "";
fwrite($fpCloturer, '{"items": [');
for ($i = 0; $i < count($rowCloturer); $i++) {
    $dataCloturer = "" . '{"datedebutprojet":' . '"' . $rowCloturer[$i]['datedebutprojet'] . '"' . "," . '"numero":' . '"' . $rowCloturer[$i]['numero'] . '"' . "," . '"titre":' . '"' .
            filtredonnee($rowCloturer[$i]['titre']) . '"' . "," . '"libellestatutprojet":' . '"' . $rowCloturer[$i]['libellestatutprojet'] . '"'
            . "," . '"nomaffecte":' . '"' . filtredonnee($rowCloturer[$i]['nomaffecte']) . ' ' . filtredonnee($rowCloturer[$i]['prenomaffecte']) . '"'
            . "," . '"libellecentrale":' . '"' . $rowCloturer[$i]['libellecentrale'] . '"' . "},";
    fputs($fpCloturer, $dataCloturer);
    fwrite($fpCloturer, '');
}
fwrite($fpCloturer, ']}');
$json_fileCloturer = "../tmp/projetCloturer.json";
$jsoncloturer = file_get_contents($json_fileCloturer);
$jsonCloturer = str_replace('},]}', '}]}', $jsoncloturer);
file_put_contents($json_fileCloturer, $jsonCloturer);
fclose($fpCloturer);
chmod('../tmp/projetCloturer.json', 0777);


//---------------------------------------------------------------------------------------------------------------------------------------------------
//	CREATION DU PROJET JSON EN COURS DE REALISATION
//---------------------------------------------------------------------------------------------------------------------------------------------------
$idstatutencoursrealisation = $manager->getSingle2('select idstatutprojet from statutprojet where libellestatutprojet=? ', 'En cours de réalisation');
$manager->exeRequete("drop table if exists tmpencoursrealisation;");
$manager->getRequete("create table tmpencoursrealisation as
    (
 SELECT p.idprojet,s.libellestatutprojet,p.numero,p.titre,p.datedebutprojet,null as nomAffecte,null as prenomAffecte,ce.libellecentrale
  FROM  projet p,utilisateur u,creer cr,centrale ce,concerne co,typeprojet t,statutprojet s, loginpassword l
  WHERE cr.idprojet_projet = p.idprojet AND cr.idutilisateur_utilisateur = u.idutilisateur AND co.idcentrale_centrale = ce.idcentrale AND
  co.idprojet_projet = p.idprojet AND  t.idtypeprojet = p.idtypeprojet_typeprojet AND  l.idlogin = u.idlogin_loginpassword AND  s.idstatutprojet =? AND l.pseudo =?
  AND s.idstatutprojet = co.idstatutprojet_statutprojet 
union
SELECT idprojet,s.libellestatutprojet,numero,titre,datedebutprojet,u1.nom,u1.prenom,ce.libellecentrale
FROM projet,utilisateur u,creer,centrale ce,concerne,typeprojet,statutprojet s,utilisateurporteurprojet,utilisateur u1 , loginpassword l
WHERE idtypeprojet_typeprojet = typeprojet.idtypeprojet AND u.idutilisateur = creer.idutilisateur_utilisateur AND
  creer.idprojet_projet = idprojet AND concerne.idcentrale_centrale = idcentrale AND concerne.idprojet_projet = idprojet AND
  concerne.idstatutprojet_statutprojet = s.idstatutprojet AND utilisateurporteurprojet.idprojet_projet = idprojet AND
  utilisateurporteurprojet.idutilisateur_utilisateur = u1.idutilisateur AND  l.idlogin = u.idlogin_loginpassword AND  s.idstatutprojet =? AND l.pseudo =?
order by idprojet asc
);", array($idstatutencoursrealisation, $pseudo, $idstatutencoursrealisation, $pseudo));

$rowEncoursRealisation = $manager->getList("select  idprojet,libellestatutprojet,numero,titre,datedebutprojet,nomaffecte,prenomaffecte,libellecentrale from tmpencoursrealisation
    where idprojet not in (select idprojet from tmpencoursrealisation where nomaffecte is not null)
    union 
    select idprojet,libellestatutprojet,numero,titre,datedebutprojet,nomaffecte,prenomaffecte,libellecentrale from tmpencoursrealisation where nomaffecte is not null 
    order by idprojet asc");


$fpEncoursRealisation = fopen('../tmp/projetEnCoursRealisation.json', 'w');
$dataEncoursRealisation = "";
fwrite($fpEncoursRealisation, '{"items": [');
for ($i = 0; $i < count($rowEncoursRealisation); $i++) {
    $dataEncoursRealisation = "" . '{"datedebutprojet":' . '"' . $rowEncoursRealisation[$i]['datedebutprojet'] . '"' . "," . '"numero":' . '"' . $rowEncoursRealisation[$i]['numero'] . '"' . "," . '"titre":' . '"' .
            filtredonnee($rowEncoursRealisation[$i]['titre']) . '"' . "," . '"libellestatutprojet":' . '"' . $rowEncoursRealisation[$i]['libellestatutprojet'] . '"'
            . "," . '"nomaffecte":' . '"' . filtredonnee($rowEncoursRealisation[$i]['nomaffecte']) . ' ' . filtredonnee($rowEncoursRealisation[$i]['prenomaffecte']) . '"'
            . "," . '"libellecentrale":' . '"' . $rowEncoursRealisation[$i]['libellecentrale'] . '"' . "},";
    fputs($fpEncoursRealisation, $dataEncoursRealisation);
    fwrite($fpEncoursRealisation, '');
}
fwrite($fpEncoursRealisation, ']}');
$json_fileEncoursRealisation = "../tmp/projetEnCoursRealisation.json";
$jsonEncoursrealisation = file_get_contents($json_fileEncoursRealisation);
$jsonEncoursRealisation = str_replace('},]}', '}]}', $jsonEncoursrealisation);
file_put_contents($json_fileEncoursRealisation, $jsonEncoursRealisation);
fclose($fpEncoursRealisation);
chmod('../tmp/projetEnCoursRealisation.json', 0777);




header('location:../VueSuiviProjet.php?lang=' . $lang . '');
BD::deconnecter();
?>