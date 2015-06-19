<?php

session_start();
include_once '../class/Chiffrement.php';
include '../class/Manager.php';
include_once '../outils/toolBox.php';
include_once '../decide-lang.php';
include_once '../outils/constantes.php';
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
$db = BD::connecter();
$manager = new Manager($db);
$pseudo = $_SESSION['pseudo'];
if (isset($_SESSION['mail'])) {
    $mail = $_SESSION['mail'];
    $_SESSION['email'] = $mail;
} else {
    $mail = $_SESSION['email'];
    $_SESSION['email'] = $mail;
}
$idstatut = $manager->getSingle2("SELECT idtypeutilisateur_typeutilisateur FROM loginpassword,utilisateur WHERE  idlogin = idlogin_loginpassword and pseudo=?", $pseudo);
$idacademiqueInterne = $manager->getSingle2("SELECT idqualitedemandeuraca_qualitedemandeuraca FROM  loginpassword,utilisateur WHERE idlogin = idlogin_loginpassword and idcentrale_centrale is not null and pseudo = ?", $pseudo);
nomEntete($mail, $pseudo);
// VERIFICATION DE L'EXISTENCE DE L'UTILISATEUR
$idLogin = $manager->getSingle2("SELECT idlogin FROM loginpassword where pseudo=?", $pseudo);
$_SESSION['idutilisateur'] = $manager->getSingle2("SELECT idutilisateur   FROM utilisateur where idlogin_loginpassword=?", $idLogin);
$identite = $manager->getListbyArray("SELECT nom,prenom,adresse,codepostal,ville,telephone,fax FROM utilisateur where idutilisateur=?", array($_SESSION['idutilisateur']));
$_SESSION['nom'] = $identite[0][0];
$_SESSION['prenom'] = $identite[0][1];
$_SESSION['adresse'] = $identite[0][2];
$_SESSION['codepostale'] = $identite[0][3];
$_SESSION['ville'] = $identite[0][4];
$_SESSION['tel'] = $identite[0][5];
$_SESSION['fax'] = $identite[0][6];

//---------------------------------------------------------------------------------------------------------------------------------------------------
//                              CREATION DU PROJET JSON EN COURS
//---------------------------------------------------------------------------------------------------------------------------------------------------
    $manager->exeRequete("drop table if exists tmptousmesprojet;");
//CREATION DE LA TABLE TEMPORAIRE
    $manager->getRequete("create table tmptousmesprojet as (
SELECT p.idprojet,p.datedebutprojet,p.acronyme,s.libellestatutprojet,s.idstatutprojet,p.titre,p.numero,p.dateprojet,ce.libellecentrale
FROM projet p,concerne co,utilisateur u,loginpassword l,centrale ce,statutprojet s,creer cr
WHERE  co.idprojet_projet = p.idprojet AND  co.idcentrale_centrale = ce.idcentrale AND co.idstatutprojet_statutprojet = s.idstatutprojet AND
  l.idlogin = u.idlogin_loginpassword AND cr.idutilisateur_utilisateur = u.idutilisateur AND cr.idprojet_projet = p.idprojet AND l.pseudo=? AND trashed =FALSE
  union
SELECT p.idprojet,p.datedebutprojet,p.acronyme,s.libellestatutprojet,s.idstatutprojet,p.titre,p.numero,p.dateprojet,ce.libellecentrale
FROM projet p,concerne co,utilisateur u,loginpassword l,centrale ce,statutprojet s,utilisateurporteurprojet up
WHERE  co.idprojet_projet = p.idprojet AND  co.idcentrale_centrale = ce.idcentrale AND co.idstatutprojet_statutprojet = s.idstatutprojet AND
  l.idlogin = u.idlogin_loginpassword AND up.idutilisateur_utilisateur = u.idutilisateur AND p.idprojet = up.idprojet_projet AND l.pseudo=? AND trashed =FALSE )", array($pseudo, $pseudo));
    // SI ACADEMIQUE INTERNE ET DANB UNE CENTRALE
    //if (!empty($idacademiqueInterne)) {
        $s_centrale = "";
        $rowEncours = $manager->getList("
   select distinct on (idprojet,libellestatutprojet) idprojet,acronyme,idstatutprojet,libellestatutprojet,numero,titre,dateprojet,libellecentrale from tmptousmesprojet  order by idprojet desc");
        $fpEncours = fopen('../tmp/projetEncours.json', 'w');
        $dataEncours = "";
        fwrite($fpEncours, '{"items": [');
        for ($i = 0; $i < count($rowEncours); $i++) {
            if($rowEncours[$i]['idstatutprojet']==REFUSE){
                $arraycentraleencours = $manager->getListbyArray("select libellecentrale from tmptousmesprojet where idprojet=? and idstatutprojet=?", array($rowEncours[$i]['idprojet'],REFUSE));
                $nbcentrale = count($arraycentraleencours);
                for ($j = 0; $j < $nbcentrale; $j++) {
                    $s_centrale .=$arraycentraleencours[$j]['libellecentrale'] . ' - ';
                }
                $libellecentrale = substr(trim($s_centrale), 0, -1);
            }elseif($rowEncours[$i]['idstatutprojet']==ENCOURSANALYSE){
                $arraycentraleencours = $manager->getListbyArray("select libellecentrale from tmptousmesprojet where idprojet=? and idstatutprojet=?", array($rowEncours[$i]['idprojet'],ENCOURSANALYSE));
                $nbcentrale = count($arraycentraleencours);
                for ($j = 0; $j < $nbcentrale; $j++) {
                    $s_centrale .=$arraycentraleencours[$j]['libellecentrale'] . ' - ';
                }
                $libellecentrale = substr(trim($s_centrale), 0, -1);
            }elseif($rowEncours[$i]['idstatutprojet']==ENCOURSREALISATION){
                $arraycentraleencours = $manager->getListbyArray("select libellecentrale from tmptousmesprojet where idprojet=? and idstatutprojet=?", array($rowEncours[$i]['idprojet'],ENCOURSREALISATION));
                $nbcentrale = count($arraycentraleencours);
                for ($j = 0; $j < $nbcentrale; $j++) {
                    $s_centrale .=$arraycentraleencours[$j]['libellecentrale'] . ' - ';
                }
                $libellecentrale = substr(trim($s_centrale), 0, -1);
            }elseif($rowEncours[$i]['idstatutprojet']==ACCEPTE){
                $arraycentraleencours = $manager->getListbyArray("select libellecentrale from tmptousmesprojet where idprojet=? and idstatutprojet=?", array($rowEncours[$i]['idprojet'],ACCEPTE));
                $nbcentrale = count($arraycentraleencours);
                for ($j = 0; $j < $nbcentrale; $j++) {
                    $s_centrale .=$arraycentraleencours[$j]['libellecentrale'] . ' - ';
                }
                $libellecentrale = substr(trim($s_centrale), 0, -1);
            }elseif($rowEncours[$i]['idstatutprojet']==ENATTENTE){
                $arraycentraleencours = $manager->getListbyArray("select libellecentrale from tmptousmesprojet where idprojet=? and idstatutprojet=?", array($rowEncours[$i]['idprojet'],ENATTENTE));
                $nbcentrale = count($arraycentraleencours);
                for ($j = 0; $j < $nbcentrale; $j++) {
                    $s_centrale .=$arraycentraleencours[$j]['libellecentrale'] . ' - ';
                }
                $libellecentrale = substr(trim($s_centrale), 0, -1);
            } elseif($rowEncours[$i]['idstatutprojet']==FINI){
                $arraycentraleencours = $manager->getListbyArray("select libellecentrale from tmptousmesprojet where idprojet=? and idstatutprojet=?", array($rowEncours[$i]['idprojet'],FINI));
                $nbcentrale = count($arraycentraleencours);
                for ($j = 0; $j < $nbcentrale; $j++) {
                    $s_centrale .=$arraycentraleencours[$j]['libellecentrale'] . ' - ';
                }
                $libellecentrale = substr(trim($s_centrale), 0, -1);
            }elseif($rowEncours[$i]['idstatutprojet']==CLOTURE){
                $arraycentraleencours = $manager->getListbyArray("select libellecentrale from tmptousmesprojet where idprojet=? and idstatutprojet=?", array($rowEncours[$i]['idprojet'],CLOTURE));
                $nbcentrale = count($arraycentraleencours);
                for ($j = 0; $j < $nbcentrale; $j++) {
                    $s_centrale .=$arraycentraleencours[$j]['libellecentrale'] . ' - ';
                }            
                $libellecentrale = substr(trim($s_centrale), 0, -1);
            }elseif($rowEncours[$i]['idstatutprojet']==ENATTENTEPHASE2){
                $arraycentraleencours = $manager->getListbyArray("select libellecentrale from tmptousmesprojet where idprojet=? and idstatutprojet=?", array($rowEncours[$i]['idprojet'],ENATTENTEPHASE2));
                $nbcentrale = count($arraycentraleencours);
                for ($j = 0; $j < $nbcentrale; $j++) {
                    $s_centrale .=$arraycentraleencours[$j]['libellecentrale'] . ' - ';
                }            
                $libellecentrale = substr(trim($s_centrale), 0, -1);
            }
                        
            
            $nomcentrale = $manager->getSinglebyArray("select libellecentrale from tmptousmesprojet where idprojet=? limit 1", array($rowEncours[$i]['idprojet']));
            
            $dataEncours = ""
                    . '{"numero":' . '"' . $rowEncours[$i]['numero'] . '"' . ","
                    . '"dateprojet":' . '"' . $rowEncours[$i]['dateprojet'] . '"' . ","
                    . '"idstatutprojet":' . '"' . $rowEncours[$i]['idstatutprojet'] . '"' . ","
                    . '"titre":' . '"' . filtredonnee($rowEncours[$i]['titre']) . '"' . ","
                    . '"acronyme":' . '"' . filtredonnee($rowEncours[$i]['acronyme']) . '"'
                    . "," . '"libellestatutprojet":' . '"' . str_replace("''", "'", $rowEncours[$i]['libellestatutprojet']) . '"'
                    . "," . '"idprojet":' . '"' . $rowEncours[$i]['idprojet'] . '"'
                    . "," . '"imprime":' . '"' . TXT_PDF . '"'
                    . "," . '"academiqueinterne":' . '"' . 'TRUE' . '"'
                    . "," . '"nomcentrale":' . '"' . $nomcentrale . '"'
                    . "," . '"libellecentrale":' . '"' . $libellecentrale . '"' . "},";
            fputs($fpEncours, $dataEncours);
            fwrite($fpEncours, '');
            $s_centrale = "";
        }    
    fwrite($fpEncours, ']}');
    $json_fileEncours ="../tmp/projetEncours.json";
    $jsonEncours1 = file_get_contents($json_fileEncours);
    $jsonEncours = str_replace('},]}', '}]}', $jsonEncours1);
    file_put_contents($json_fileEncours, $jsonEncours);
    fclose($fpEncours);
    chmod('../tmp/projetEncours.json', 0777);
    $_SESSION['email'] = $mail;
    $_SESSION['pseudo'] = $pseudo;

//---------------------------------------------------------------------------------------------------------------------------------------------------
//                      CREATION DU PROJET JSON EN ATTENTE
//---------------------------------------------------------------------------------------------------------------------------------------------------

$manager->exeRequete("drop table if exists tmpprojetenattente;");
$manager->getRequete("create table tmpprojetenattente as (
    SELECT ce.idcentrale,ce.libellecentrale,p.idprojet,p.datedebutprojet,p.acronyme,s.libellestatutprojet,s.idstatutprojet,p.numero,p.titre,p.dateprojet,u.nom,u.prenom
FROM projet p,concerne co,creer cr,centrale ce,utilisateur u,loginpassword l,statutprojet s
WHERE   co.idcentrale_centrale = ce.idcentrale AND  co.idprojet_projet = p.idprojet AND  cr.idprojet_projet = p.idprojet AND  cr.idutilisateur_utilisateur = u.idutilisateur AND
  u.idlogin_loginpassword = l.idlogin AND  s.idstatutprojet = co.idstatutprojet_statutprojet and  l.pseudo =? and s.idstatutprojet=? AND trashed =FALSE
union  
SELECT ce.idcentrale,ce.libellecentrale,p.idprojet,p.datedebutprojet,p.acronyme,s.libellestatutprojet,s.idstatutprojet,p.numero,p.titre,p.dateprojet,u.nom,u.prenom
FROM projet p,concerne co,creer cr,centrale ce,utilisateur u,loginpassword l,statutprojet s
WHERE   co.idcentrale_centrale = ce.idcentrale AND  co.idprojet_projet = p.idprojet AND  cr.idprojet_projet = p.idprojet AND  cr.idutilisateur_utilisateur = u.idutilisateur AND
  u.idlogin_loginpassword = l.idlogin AND  s.idstatutprojet = co.idstatutprojet_statutprojet and  l.pseudo =? and s.idstatutprojet=? AND trashed =FALSE
)", array($pseudo, ENATTENTE,$pseudo, ENATTENTEPHASE2));
if (!empty($idacademiqueInterne)) {
    $rowEnattente = $manager->getList("select distinct on (idprojet) idprojet,acronyme,idcentrale,libellestatutprojet,libellecentrale,idstatutprojet,numero,titre,dateprojet from tmpprojetenattente  order by idprojet asc");
    $fpEnattente = fopen('../tmp/projetEnattente.json', 'w');
    $dataEnattente = '';
    fwrite($fpEnattente, '{"items": [');
    for ($i = 0; $i < count($rowEnattente); $i++) {
        $dataEnattente = "" . '{"numero":' . '"' . $rowEnattente[$i]['numero'] . '"' . "," . '"dateprojet":' . '"' . $rowEnattente[$i]['dateprojet'] . '"' . "," . '"titre":' . '"' .
                filtredonnee($rowEnattente[$i]['titre']) . '"' . "," . '"acronyme":' . '"' . $rowEnattente[$i]['acronyme'] . '"'
                . "," . '"libellestatutprojet":' . '"' . $rowEnattente[$i]['libellestatutprojet'] . '"'
                . "," . '"idstatutprojet":' . '"' . $rowEnattente[$i]['idstatutprojet'] . '"'
                . "," . '"libellecentrale":' . '"' . $rowEnattente[$i]['libellecentrale'] . '"'
                . "," . '"idcentrale":' . '"' . $rowEnattente[$i]['idcentrale'] . '"' . "},";
        fputs($fpEnattente, $dataEnattente);
        fwrite($fpEnattente, '');
    }
} else {
    $rowEnattente = $manager->getList("select distinct on (idprojet) idprojet,acronyme,idcentrale,libellestatutprojet,libellecentrale,idstatutprojet,numero,titre,dateprojet from tmpprojetenattente  order by idprojet asc");
    $fpEnattente = fopen('../tmp/projetEnattente.json', 'w');
    $dataEnattente = "";
    fwrite($fpEnattente, '{"items": [');
    $nbrowenattente = count($rowEnattente);
    for ($i = 0; $i < $nbrowenattente; $i++) {
        $dataEnattente = ""
                . '{"numero":' . '"' . $rowEnattente[$i]['numero'] . '"' . ","
                . '"dateprojet":' . '"' . $rowEnattente[$i]['dateprojet'] . '"'
                . "," . '"titre":' . '"' . filtredonnee($rowEnattente[$i]['titre']) . '"'
                . "," . '"acronyme":' . '"' . $rowEnattente[$i]['acronyme'] . '"'
                . "," . '"idstatutprojet":' . '"' . $rowEnattente[$i]['idstatutprojet'] . '"'
                . "," . '"libellestatutprojet":' . '"' . $rowEnattente[$i]['libellestatutprojet'] . '"'
                . "," . '"libellecentrale":' . '"' . $rowEnattente[$i]['libellecentrale'] . '"'
                . "," . '"idcentrale":' . '"' . $rowEnattente[$i]['idcentrale'] . '"' . "},";
        fputs($fpEnattente, $dataEnattente);
        fwrite($fpEnattente, '');
    }
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

$manager->exeRequete("drop table if exists tmpprojetencoursanalyse;");
$manager->getRequete("create table tmpprojetencoursanalyse as (SELECT  distinct on(p.idprojet) p.idprojet,co.idstatutprojet_statutprojet,ce.libellecentrale,p.acronyme,p.titre,p.numero,p.dateprojet
FROM projet p,concerne co,creer cr,centrale ce,utilisateur u,loginpassword l
WHERE co.idprojet_projet = p.idprojet AND  cr.idprojet_projet = p.idprojet AND cr.idutilisateur_utilisateur = u.idutilisateur AND   ce.idcentrale = co.idcentrale_centrale AND
u.idlogin_loginpassword = l.idlogin AND l.pseudo=? AND  co.idstatutprojet_statutprojet=? AND trashed =FALSE)", array($pseudo, ENCOURSANALYSE));
$rowEncoursAnalyse = $manager->getList("select  idprojet,acronyme,libellecentrale,numero,titre,dateprojet,idstatutprojet_statutprojet from tmpprojetencoursanalyse order by idprojet asc");
$fpEncoursAnalyse = fopen('../tmp/projetAnalyse.json', 'w');
$dataEncoursAnalyse = "";
fwrite($fpEncoursAnalyse, '{"items": [');
for ($i = 0; $i < count($rowEncoursAnalyse); $i++) {
    $dataEncoursAnalyse = ""
            . '{"dateProjet":' . '"' . $rowEncoursAnalyse[$i]['dateprojet'] . '"'
            . "," . '"numero":' . '"' . $rowEncoursAnalyse[$i]['numero'] . '"'
            . "," . '"idstatutprojet":' . '"' . $rowEncoursAnalyse[$i]['idstatutprojet_statutprojet'] . '"'
            . "," . '"titre":' . '"' . filtredonnee($rowEncoursAnalyse[$i]['titre']) . '"'
            . "," . '"acronyme":' . '"' . $rowEncoursAnalyse[$i]['acronyme'] . '"'
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

$rowRefuse = $manager->getListbyArray("SELECT  distinct on(p.numero,s.libellestatutprojet) p.numero,co.commentaireprojet, p.acronyme,p.dateprojet,p.titre,s.libellestatutprojet,ce.libellecentrale
FROM projet p,concerne co,utilisateur u,centrale ce,loginpassword l,creer cr,statutprojet s
WHERE co.idprojet_projet = p.idprojet AND   co.idcentrale_centrale = ce.idcentrale AND  co.idstatutprojet_statutprojet = s.idstatutprojet AND
u.idutilisateur = cr.idutilisateur_utilisateur AND  l.idlogin = u.idlogin_loginpassword  AND cr.idprojet_projet = p.idprojet AND  s.idstatutprojet=? and  l.pseudo=? AND trashed =FALSE
union
SELECT  distinct on(p.numero,s.libellestatutprojet) p.numero,co.commentaireprojet, p.acronyme,p.dateprojet,p.titre,s.libellestatutprojet,ce.libellecentrale
FROM utilisateurporteurprojet up,statutprojet s,projet p,utilisateur u,concerne co,centrale ce,loginpassword l
WHERE up.idprojet_projet = p.idprojet AND up.idutilisateur_utilisateur = u.idutilisateur AND s.idstatutprojet = co.idstatutprojet_statutprojet AND
co.idprojet_projet = p.idprojet AND  ce.idcentrale = co.idcentrale_centrale AND  l.idlogin = u.idlogin_loginpassword AND  s.idstatutprojet=? and  l.pseudo=? AND trashed =FALSE

", array(REFUSE, $pseudo, REFUSE, $pseudo));


$fpRefuse = fopen('../tmp/projetRefuse.json', 'w');
$dataRefuse = "";
fwrite($fpRefuse, '{"items": [');
for ($i = 0; $i < count($rowRefuse); $i++) {
    $dataRefuse = ""
            . '{"dateprojet":' . '"' . $rowRefuse[$i]['dateprojet'] . '"' . ","
            . '"numero":' . '"' . $rowRefuse[$i]['numero'] . '"' . ","
            . '"titre":' . '"' . filtredonnee($rowRefuse[$i]['titre']) . '"' . ","
            . '"commentaire":' . '"' . strip_tags(filtredonnee($rowRefuse[$i]['commentaireprojet'])) . '"' . ","
            . '"acronyme":' . '"' . $rowRefuse[$i]['acronyme'] . '"' . "},";
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
//	CREATION DU PROJET JSON EN COURS D'EXPERTISE
//---------------------------------------------------------------------------------------------------------------------------------------------------

$manager->exeRequete("drop table if exists tmpaccepte;");
//CREATION DE LA TABLE TEMPORAIRE
$manager->getRequete("create table tmpaccepte as (
SELECT ce.libellecentrale,p.idprojet,p.acronyme,p.titre,p.numero,p.dateprojet,p.datedebutprojet,co.idstatutprojet_statutprojet
FROM  projet p,concerne co,creer cr,centrale ce,utilisateur u,loginpassword l
WHERE co.idprojet_projet = p.idprojet AND  cr.idprojet_projet = p.idprojet AND   cr.idutilisateur_utilisateur = u.idutilisateur AND
  ce.idcentrale = co.idcentrale_centrale AND   u.idlogin_loginpassword = l.idlogin and l.pseudo =? and co.idstatutprojet_statutprojet = ? AND trashed =FALSE);", array($pseudo, ACCEPTE));

$rowAccepte = $manager->getList("select  distinct on(idprojet) idprojet,idstatutprojet_statutprojet,acronyme,numero,titre,dateprojet,libellecentrale from tmpaccepte order by idprojet asc");
$fpAccepte = fopen('../tmp/projetAccepte.json', 'w');
$dataAccepte = "";
fwrite($fpAccepte, '{"items": [');
for ($i = 0; $i < count($rowAccepte); $i++) {
    $dataAccepte = "" . '{'
            . '"dateprojet":' . '"' . $rowAccepte[$i]['dateprojet'] . '"'
            . "," . '"numero":' . '"' . $rowAccepte[$i]['numero'] . '"'
            . "," . '"idstatutprojet":' . '"' . $rowAccepte[$i]['idstatutprojet_statutprojet'] . '"'
            . "," . '"titre":' . '"' . filtredonnee($rowAccepte[$i]['titre']) . '"'
            . "," . '"acronyme":' . '"' . filtredonnee($rowAccepte[$i]['acronyme']) . '"'
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

$manager->exeRequete("drop table if exists tmpfini;");
$manager->getRequete("create table tmpfini as(
 SELECT p.idprojet, p.acronyme,p.datedebutprojet, s.libellestatutprojet,p.numero, p.titre, p.dateprojet,null as nomAffecte,null as prenomAffecte
FROM projet p,concerne co ,utilisateur u,utilisateurporteurprojet up,loginpassword l,statutprojet s
WHERE  co.idprojet_projet = p.idprojet AND up.idprojet_projet = p.idprojet
AND up.idutilisateur_utilisateur = u.idutilisateur AND  s.idstatutprojet = co.idstatutprojet_statutprojet
and l.idlogin = u.idlogin_loginpassword AND s.idstatutprojet = co.idstatutprojet_statutprojet
and  l.pseudo =? and s.idstatutprojet=? AND trashed =FALSE
 union
 SELECT p.idprojet,p.acronyme,p.datedebutprojet,s.libellestatutprojet,p.numero,p.titre,p.dateprojet,null as nomAffecte,null as prenomAffecte
FROM projet p,concerne co,utilisateur u,loginpassword l,statutprojet s,creer cr
WHERE co.idprojet_projet = p.idprojet AND l.idlogin = u.idlogin_loginpassword
AND s.idstatutprojet = co.idstatutprojet_statutprojet and s.idstatutprojet = co.idstatutprojet_statutprojet
AND cr.idutilisateur_utilisateur = u.idutilisateur
and cr.idprojet_projet = p.idprojet and l.pseudo =? AND s.idstatutprojet=? AND trashed =FALSE
union
 SELECT p.idprojet,p.acronyme,p.datedebutprojet,s.libellestatutprojet,p.numero,p.titre,p.dateprojet,null as nomAffecte,null as prenomAffecte
FROM projet p,concerne co,utilisateur u,loginpassword l,statutprojet s,creer cr
WHERE  co.idprojet_projet = p.idprojet AND l.idlogin = u.idlogin_loginpassword
AND s.idstatutprojet = co.idstatutprojet_statutprojet and s.idstatutprojet = co.idstatutprojet_statutprojet
AND cr.idutilisateur_utilisateur = u.idutilisateur
and cr.idprojet_projet = p.idprojet and l.pseudo =? AND s.idstatutprojet=? AND trashed =FALSE
 union
 SELECT   p.idprojet,p.acronyme,p.datedebutprojet,s.libellestatutprojet,p.numero,p.titre,p.dateprojet,u1.nom,u1.prenom
FROM projet p,concerne co,utilisateur u,loginpassword l,statutprojet s,typeprojet tp,creer cr,utilisateur u1,utilisateurporteurprojet up
WHERE co.idprojet_projet = p.idprojet AND l.idlogin = u.idlogin_loginpassword
AND s.idstatutprojet = co.idstatutprojet_statutprojet AND tp.idtypeprojet = p.idtypeprojet_typeprojet
AND cr.idutilisateur_utilisateur = u.idutilisateur AND  cr.idprojet_projet = p.idprojet AND  up.idprojet_projet = p.idprojet
AND   up.idutilisateur_utilisateur = u1.idutilisateur and  l.pseudo =? AND s.idstatutprojet=? AND trashed =FALSE
order by idprojet asc);", array($pseudo, FINI, $pseudo, FINI, $pseudo, FINI, $pseudo, FINI));


$rowFini = $manager->getList("select  distinct on (idprojet) libellestatutprojet,acronyme,numero,titre,datedebutprojet,nomaffecte,prenomaffecte from tmpfini
    ");

$fpFini = fopen('../tmp/projetFini.json', 'w');
$dataFini = "";
fwrite($fpFini, '{"items": [');
for ($i = 0; $i < count($rowFini); $i++) {
    $dataFini = "" . '{"datedebutprojet":' . '"' . $rowFini[$i]['datedebutprojet'] . '"' . "," . '"numero":' . '"' . $rowFini[$i]['numero'] . '"' . "," .
            '"titre":' . '"' . filtredonnee($rowFini[$i]['titre']) . '"' . "," . '"acronyme":' . '"' . $rowFini[$i]['acronyme'] . '"' . "},";
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

$manager->exeRequete("drop table if exists tmpcloturer;");
$manager->getRequete("create table tmpcloturer as
    (SELECT p.idprojet,p.datestatutcloturer, p.acronyme, s.libellestatutprojet,p.numero, p.titre, p.dateprojet,null as nomAffecte,null as prenomAffecte
FROM projet p,concerne co ,utilisateur u,utilisateurporteurprojet up,loginpassword l,statutprojet s,centrale ce
WHERE co.idcentrale_centrale = u.idcentrale_centrale AND co.idprojet_projet = p.idprojet AND up.idprojet_projet = p.idprojet
AND up.idutilisateur_utilisateur = u.idutilisateur AND  s.idstatutprojet = co.idstatutprojet_statutprojet
and l.idlogin = u.idlogin_loginpassword AND s.idstatutprojet = co.idstatutprojet_statutprojet AND ce.idcentrale = co.idcentrale_centrale
and  l.pseudo =? and s.idstatutprojet=? AND trashed =FALSE
 union
 SELECT p.idprojet,p.datestatutcloturer,p.acronyme,s.libellestatutprojet,p.numero,p.titre,p.dateprojet,null as nomAffecte,null as prenomAffecte
FROM projet p,concerne co,utilisateur u,loginpassword l,statutprojet s,centrale ce,creer cr
WHERE co.idcentrale_centrale = u.idcentrale_centrale AND co.idprojet_projet = p.idprojet AND l.idlogin = u.idlogin_loginpassword
AND s.idstatutprojet = co.idstatutprojet_statutprojet and s.idstatutprojet = co.idstatutprojet_statutprojet
AND ce.idcentrale = co.idcentrale_centrale AND cr.idutilisateur_utilisateur = u.idutilisateur
and cr.idprojet_projet = p.idprojet and l.pseudo =? AND s.idstatutprojet=? AND trashed =FALSE
 union
 SELECT   p.idprojet,p.datestatutcloturer,p.acronyme,s.libellestatutprojet,p.numero,p.titre,p.dateprojet,u1.nom,u1.prenom
FROM projet p,concerne co,utilisateur u,loginpassword l,statutprojet s,centrale ce,typeprojet tp,creer cr,utilisateur u1,utilisateurporteurprojet up
WHERE co.idcentrale_centrale = ce.idcentrale AND co.idprojet_projet = p.idprojet AND l.idlogin = u.idlogin_loginpassword
AND s.idstatutprojet = co.idstatutprojet_statutprojet AND ce.idcentrale = u.idcentrale_centrale AND tp.idtypeprojet = p.idtypeprojet_typeprojet
AND cr.idutilisateur_utilisateur = u.idutilisateur AND  cr.idprojet_projet = p.idprojet AND  up.idprojet_projet = p.idprojet
AND   up.idutilisateur_utilisateur = u1.idutilisateur and  l.pseudo =? AND s.idstatutprojet=? AND trashed =FALSE
 union
 SELECT   p.idprojet,p.datestatutcloturer,p.acronyme,s.libellestatutprojet,p.numero,p.titre,p.dateprojet,null as nom,null as prenom
FROM projet p,concerne co,utilisateur u,loginpassword l,statutprojet s,centrale ce,typeprojet tp,creer cr
WHERE co.idprojet_projet = p.idprojet AND l.idlogin = u.idlogin_loginpassword
AND s.idstatutprojet = co.idstatutprojet_statutprojet AND tp.idtypeprojet = p.idtypeprojet_typeprojet
AND cr.idutilisateur_utilisateur = u.idutilisateur AND  cr.idprojet_projet = p.idprojet
AND   l.pseudo =? AND s.idstatutprojet=? AND trashed =FALSE
);", array($pseudo, CLOTURE, $pseudo, CLOTURE, $pseudo, CLOTURE, $pseudo, CLOTURE));

$rowCloturer = $manager->getList("select  idprojet,acronyme,libellestatutprojet,numero,titre,datestatutcloturer,nomaffecte,prenomaffecte from tmpcloturer
    where idprojet not in (select idprojet from tmpcloturer where nomaffecte is not null)
    union 
    select idprojet,acronyme,libellestatutprojet,numero,titre,datestatutcloturer,nomaffecte,prenomaffecte from tmpcloturer where nomaffecte is not null
    order by idprojet asc");


$fpCloturer = fopen('../tmp/projetCloturer.json', 'w');
$dataCloturer = "";
fwrite($fpCloturer, '{"items": [');
for ($i = 0; $i < count($rowCloturer); $i++) {    
    $dataCloturer = "" . '{"datestatutcloturer":' . '"' . $rowCloturer[$i]['datestatutcloturer'] . '"' . "," 
            . '"numero":' . '"' . $rowCloturer[$i]['numero'] . '"' . ","
            . '"titre":' . '"' . filtredonnee($rowCloturer[$i]['titre']) . '"' . ","             
            . '"acronyme":' . '"' . $rowCloturer[$i]['acronyme'] . '"' . "},";
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
$manager->exeRequete("drop table if exists tmpencoursrealisation;");
$manager->getRequete("create table tmpencoursrealisation as
    (SELECT p.idprojet,p.acronyme,s.libellestatutprojet,p.datedebutprojet,co.idstatutprojet_statutprojet,p.titre,p.numero,p.dateprojet,ce.libellecentrale
FROM concerne co, projet p,utilisateur u,creer cr,statutprojet s,centrale ce,loginpassword l
WHERE p.idprojet = co.idprojet_projet AND cr.idprojet_projet = p.idprojet AND cr.idutilisateur_utilisateur = u.idutilisateur AND
  s.idstatutprojet = co.idstatutprojet_statutprojet AND ce.idcentrale = co.idcentrale_centrale AND
  l.idlogin = u.idlogin_loginpassword AND l.pseudo=? and co.idstatutprojet_statutprojet=? AND trashed =FALSE
union
SELECT p.idprojet,p.acronyme,s.libellestatutprojet,p.datedebutprojet,co.idstatutprojet_statutprojet,p.titre,p.numero,p.dateprojet,ce.libellecentrale
FROM projet p,utilisateurporteurprojet up,concerne co,utilisateur u,statutprojet s,centrale ce,loginpassword l
WHERE p.idprojet = up.idprojet_projet AND co.idprojet_projet = p.idprojet AND u.idutilisateur = up.idutilisateur_utilisateur AND
  s.idstatutprojet = co.idstatutprojet_statutprojet AND ce.idcentrale = co.idcentrale_centrale AND l.idlogin = u.idlogin_loginpassword and l.pseudo=? and co.idstatutprojet_statutprojet=? AND trashed =FALSE
);", array($pseudo, ENCOURSREALISATION, $pseudo, ENCOURSREALISATION));

if (!empty($idacademiqueInterne)) {
    $rowEncoursRealisation = $manager->getList("select distinct on (idprojet) idprojet,acronyme,libellestatutprojet,idstatutprojet_statutprojet,numero,titre,datedebutprojet,libellecentrale from tmpencoursrealisation");
    $fpEncoursRealisation = fopen('../tmp/projetEnCoursRealisation.json', 'w');
    $dataEncoursRealisation = "";
    fwrite($fpEncoursRealisation, '{"items": [');
    for ($i = 0; $i < count($rowEncoursRealisation); $i++) {
        $dataEncoursRealisation = "" . '{"datedebutprojet":' . '"' . $rowEncoursRealisation[$i]['datedebutprojet'] . '"' . "," . '"numero":' . '"' . $rowEncoursRealisation[$i]['numero'] . '"' .
                "," . '"titre":' . '"' . filtredonnee($rowEncoursRealisation[$i]['titre']) . '"' .
                "," . '"acronyme":' . '"' . filtredonnee($rowEncoursRealisation[$i]['acronyme']) . '"' .
                "," . '"idstatutprojet":' . '"' . $rowEncoursRealisation[$i]['idstatutprojet_statutprojet'] . '"' .
                "," . '"libellestatutprojet":' . '"' . $rowEncoursRealisation[$i]['libellestatutprojet'] . '"'
                . "," . '"libellecentrale":' . '"' . $rowEncoursRealisation[$i]['libellecentrale'] . '"' . "},";
        fputs($fpEncoursRealisation, $dataEncoursRealisation);
        fwrite($fpEncoursRealisation, '');
    }
} else {
    $rowEncoursRealisation = $manager->getList("select distinct on (idprojet) idprojet,acronyme,libellestatutprojet,idstatutprojet_statutprojet,numero,titre,datedebutprojet,libellecentrale from tmpencoursrealisation");
    $fpEncoursRealisation = fopen('../tmp/projetEnCoursRealisation.json', 'w');
    $dataEncoursRealisation = "";
    fwrite($fpEncoursRealisation, '{"items": [');
    for ($i = 0; $i < count($rowEncoursRealisation); $i++) {
        $libellecentrale = $manager->getSingle2("SELECT libellecentrale FROM centrale,concerne WHERE  idcentrale_centrale = idcentrale and idprojet_projet=?", $rowEncoursRealisation[$i]['idprojet']);
        $dataEncoursRealisation = "" . '{"datedebutprojet":' . '"' . $rowEncoursRealisation[$i]['datedebutprojet'] . '"' . "," . '"numero":' . '"' . $rowEncoursRealisation[$i]['numero'] . '"' . "," . '"titre":' . '"' .
                filtredonnee($rowEncoursRealisation[$i]['titre']) . '"' . "," . '"libellestatutprojet":' . '"' . $rowEncoursRealisation[$i]['libellestatutprojet'] . '"'
                . "," . '"acronyme":' . '"' . filtredonnee($rowEncoursRealisation[$i]['acronyme']) . '"'
                . "," . '"idstatutprojet":' . '"' . $rowEncoursRealisation[$i]['idstatutprojet_statutprojet'] . '"'
                . "," . '"libellecentrale":' . '"' . $libellecentrale . '"' . "},";
        fputs($fpEncoursRealisation, $dataEncoursRealisation);
        fwrite($fpEncoursRealisation, '');
    }
}


fwrite($fpEncoursRealisation, ']}');
$json_fileEncoursRealisation = "../tmp/projetEnCoursRealisation.json";
$jsonEncoursrealisation = file_get_contents($json_fileEncoursRealisation);
$jsonEncoursRealisation = str_replace('},]}', '}]}', $jsonEncoursrealisation);
file_put_contents($json_fileEncoursRealisation, $jsonEncoursRealisation);
fclose($fpEncoursRealisation);
chmod('../tmp/projetEnCoursRealisation.json', 0777);
header('Location: /' . REPERTOIRE . '/mes_projets/' . $lang);
BD::deconnecter();
