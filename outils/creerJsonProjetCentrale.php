<?php
include_once '../class/Manager.php';
include_once '../outils/constantes.php';

$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
    $pseudo = $_SESSION['pseudo'];
    $libellecentrale = $manager->getSingle2("SELECT libellecentrale FROM centrale , utilisateur ,loginpassword  WHERE idcentrale_centrale = idcentrale AND idlogin_loginpassword = idlogin AND pseudo=?", $pseudo);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
//------------------------------------------------------------------------
//-----RECUPERATION DU LIBELLE DE LA CENTRALE DU RESPONSABLE CENTRALE-----
//------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------------------------------------------------------------
//                                                                  PROJET CENTRALE TOUS
//-----------------------------------------------------------------------------------------------------------------------------------------------
//SUPPRESSION DE LA TABLE TEMPORAIRE SI ELLE EXISTE
$manager->exeRequete("drop table if exists tmptous;");
//CREATION DE LA TABLE TEMPORAIRE
$manager->getRequete("CREATE TABLE tmptous AS (
SELECT p.titre,p.idprojet,p.datemaj,p.idperiodicite_periodicite,p.acronyme,p.datedebutprojet,p.numero,p.dureeprojet ,u.idutilisateur,p.refinterneprojet,p.dateprojet,ce.libellecentrale,s.idstatutprojet,s.libellestatutprojet,s.libellestatutprojeten,u.nom||' -  '|| u.prenom as demandeur,null as porteur
FROM projet p,creer c,utilisateur u,concerne,loginpassword l,centrale ce,statutprojet s
WHERE p.idprojet = c.idprojet_projet AND c.idutilisateur_utilisateur = u.idutilisateur AND concerne.idprojet_projet = p.idprojet AND
concerne.idcentrale_centrale = ce.idcentrale AND l.idlogin = u.idlogin_loginpassword AND s.idstatutprojet = concerne.idstatutprojet_statutprojet
AND ce.libellecentrale =? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?
union
SELECT p.titre,p.idprojet,p.datemaj,p.idperiodicite_periodicite,p.acronyme,p.datedebutprojet,p.numero,p.dureeprojet ,u.idutilisateur,p.refinterneprojet,p.dateprojet,ce.libellecentrale,s.idstatutprojet,s.libellestatutprojet,s.libellestatutprojeten,null as demandeur,u.nom||' -  '|| u.prenom as porteur
FROM projet p ,utilisateur u,concerne c,loginpassword l ,centrale ce,statutprojet s,utilisateurporteurprojet up
WHERE c.idprojet_projet = p.idprojet AND c.idcentrale_centrale = ce.idcentrale AND l.idlogin = u.idlogin_loginpassword
AND s.idstatutprojet = c.idstatutprojet_statutprojet AND up.idprojet_projet = p.idprojet AND up.idutilisateur_utilisateur = u.idutilisateur
AND ce.libellecentrale =? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?
union
SELECT p.titre,p.idprojet,p.datemaj,p.idperiodicite_periodicite,p.acronyme,p.datedebutprojet,p.numero,p.dureeprojet ,u.idutilisateur,p.refinterneprojet,p.dateprojet,ce.libellecentrale,s.idstatutprojet,s.libellestatutprojet,s.libellestatutprojeten,u.nom||' -  '|| u.prenom as demandeur,null as porteur
FROM projet p,creer c,utilisateur u,concerne,loginpassword l,centrale ce,statutprojet s
WHERE p.idprojet = c.idprojet_projet AND c.idutilisateur_utilisateur = u.idutilisateur AND concerne.idprojet_projet = p.idprojet AND
concerne.idcentrale_centrale = ce.idcentrale AND l.idlogin = u.idlogin_loginpassword AND s.idstatutprojet = concerne.idstatutprojet_statutprojet
AND ce.libellecentrale =? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?
union
SELECT p.titre,p.idprojet,p.datemaj,p.idperiodicite_periodicite,p.acronyme,p.datedebutprojet,p.numero,p.dureeprojet ,u.idutilisateur,p.refinterneprojet,p.dateprojet,ce.libellecentrale,s.idstatutprojet,s.libellestatutprojet,s.libellestatutprojeten,null as demandeur,u.nom||' -  '|| u.prenom as porteur
FROM projet p ,utilisateur u,concerne c,loginpassword l ,centrale ce,statutprojet s,utilisateurporteurprojet up
WHERE c.idprojet_projet = p.idprojet AND c.idcentrale_centrale = ce.idcentrale AND l.idlogin = u.idlogin_loginpassword
AND s.idstatutprojet = c.idstatutprojet_statutprojet AND up.idprojet_projet = p.idprojet AND up.idutilisateur_utilisateur = u.idutilisateur
AND ce.libellecentrale =? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?
union
SELECT p.titre,p.idprojet,p.datemaj,p.idperiodicite_periodicite,p.acronyme,p.datedebutprojet,p.numero,p.dureeprojet ,u.idutilisateur,p.refinterneprojet,p.dateprojet,ce.libellecentrale,s.idstatutprojet,s.libellestatutprojet,s.libellestatutprojeten,u.nom||' -  '|| u.prenom as demandeur,null as porteur
FROM projet p,creer c,utilisateur u,concerne,loginpassword l,centrale ce,statutprojet s
WHERE p.idprojet = c.idprojet_projet AND c.idutilisateur_utilisateur = u.idutilisateur AND concerne.idprojet_projet = p.idprojet AND
concerne.idcentrale_centrale = ce.idcentrale AND l.idlogin = u.idlogin_loginpassword AND s.idstatutprojet = concerne.idstatutprojet_statutprojet
AND ce.libellecentrale =? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?
union
SELECT p.titre,p.idprojet,p.datemaj,p.idperiodicite_periodicite,p.acronyme,p.datedebutprojet,p.numero,p.dureeprojet ,u.idutilisateur,p.refinterneprojet,p.dateprojet,ce.libellecentrale,s.idstatutprojet,s.libellestatutprojet,s.libellestatutprojeten,null as demandeur,u.nom||' -  '|| u.prenom as porteur
FROM projet p ,utilisateur u,concerne c,loginpassword l ,centrale ce,statutprojet s,utilisateurporteurprojet up
WHERE c.idprojet_projet = p.idprojet AND c.idcentrale_centrale = ce.idcentrale AND l.idlogin = u.idlogin_loginpassword
AND s.idstatutprojet = c.idstatutprojet_statutprojet AND up.idprojet_projet = p.idprojet AND up.idutilisateur_utilisateur = u.idutilisateur
AND ce.libellecentrale =? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?
union
SELECT p.titre,p.idprojet,p.datemaj,p.idperiodicite_periodicite,p.acronyme,p.datedebutprojet,p.numero,p.dureeprojet ,u.idutilisateur,p.refinterneprojet,p.dateprojet,ce.libellecentrale,s.idstatutprojet,s.libellestatutprojet,s.libellestatutprojeten,u.nom||' -  '|| u.prenom as demandeur, null as porteur
FROM projet p,creer c,utilisateur u,concerne,loginpassword l,centrale ce,statutprojet s
WHERE p.idprojet = c.idprojet_projet AND c.idutilisateur_utilisateur = u.idutilisateur AND concerne.idprojet_projet = p.idprojet AND
concerne.idcentrale_centrale = ce.idcentrale AND l.idlogin = u.idlogin_loginpassword AND s.idstatutprojet = concerne.idstatutprojet_statutprojet
AND ce.libellecentrale =? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?
union
SELECT p.titre,p.idprojet,p.datemaj,p.idperiodicite_periodicite,p.acronyme,p.datedebutprojet,p.numero,p.dureeprojet ,u.idutilisateur,p.refinterneprojet,p.dateprojet,ce.libellecentrale,s.idstatutprojet,s.libellestatutprojet,s.libellestatutprojeten,null as demandeur,u.nom||' -  '|| u.prenom as porteur
FROM projet p ,utilisateur u,concerne c,loginpassword l ,centrale ce,statutprojet s,utilisateurporteurprojet up
WHERE c.idprojet_projet = p.idprojet AND c.idcentrale_centrale = ce.idcentrale AND l.idlogin = u.idlogin_loginpassword
AND s.idstatutprojet = c.idstatutprojet_statutprojet AND up.idprojet_projet = p.idprojet AND up.idutilisateur_utilisateur = u.idutilisateur
AND ce.libellecentrale =? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?
union
SELECT p.titre,p.idprojet,p.datemaj,p.idperiodicite_periodicite,p.acronyme,p.datedebutprojet,p.numero,p.dureeprojet ,u.idutilisateur,p.refinterneprojet,p.dateprojet,ce.libellecentrale,s.idstatutprojet,s.libellestatutprojet,s.libellestatutprojeten,u.nom||' -  '|| u.prenom as demandeur, null as porteur
FROM projet p,creer c,utilisateur u,concerne,loginpassword l,centrale ce,statutprojet s
WHERE p.idprojet = c.idprojet_projet AND c.idutilisateur_utilisateur = u.idutilisateur AND concerne.idprojet_projet = p.idprojet AND
concerne.idcentrale_centrale = ce.idcentrale AND l.idlogin = u.idlogin_loginpassword AND s.idstatutprojet = concerne.idstatutprojet_statutprojet
AND ce.libellecentrale =? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?
union
SELECT p.titre,p.idprojet,p.datemaj,p.idperiodicite_periodicite,p.acronyme,p.datedebutprojet,p.numero,p.dureeprojet ,u.idutilisateur,p.refinterneprojet,p.dateprojet,ce.libellecentrale,s.idstatutprojet,s.libellestatutprojet,s.libellestatutprojeten,null as demandeur,u.nom||' -  '|| u.prenom as porteur
FROM projet p ,utilisateur u,concerne c,loginpassword l ,centrale ce,statutprojet s,utilisateurporteurprojet up
WHERE c.idprojet_projet = p.idprojet AND c.idcentrale_centrale = ce.idcentrale AND l.idlogin = u.idlogin_loginpassword
AND s.idstatutprojet = c.idstatutprojet_statutprojet AND up.idprojet_projet = p.idprojet AND up.idutilisateur_utilisateur = u.idutilisateur
AND ce.libellecentrale =? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=? and idstatutprojet_statutprojet!=?
)", array($libellecentrale,FINI,REFUSE,CLOTURE, $libellecentrale,FINI,REFUSE,CLOTURE, $libellecentrale,FINI,REFUSE,CLOTURE, $libellecentrale,FINI,REFUSE,CLOTURE, $libellecentrale, FINI,REFUSE,CLOTURE,$libellecentrale,
    FINI,REFUSE,CLOTURE,$libellecentrale,FINI,REFUSE,CLOTURE, $libellecentrale,FINI,REFUSE,CLOTURE, $libellecentrale,FINI,REFUSE,CLOTURE, $libellecentrale,FINI,REFUSE,CLOTURE));
$manager->exeRequete("ALTER TABLE tmptous ADD COLUMN calcfinprojet date;");
$manager->exeRequete("ALTER TABLE tmptous ADD COLUMN finprojetproche date;");
$arrayprojet = $manager->getList("select * from tmptous");
$nbarrayprojet = count($arrayprojet);
for ($i = 0; $i < $nbarrayprojet; $i++) {    
      if($arrayprojet[$i]['idstatutprojet']!=REFUSE&& $arrayprojet[$i]['idstatutprojet']!=FINI          
            && $arrayprojet[$i]['idstatutprojet']!=CLOTURE&& $arrayprojet[$i]['idstatutprojet']!=ACCEPTE&& $arrayprojet[$i]['idstatutprojet']!=ENATTENTEPHASE2){
        if ($arrayprojet[$i]['idperiodicite_periodicite'] == JOUR) {
            $datedepart = strtotime($arrayprojet[$i]['datedebutprojet']);
            $duree = ($arrayprojet[$i]['dureeprojet']);
            $dateFin = date('Y-m-d', strtotime('+' . $duree . 'day', $datedepart));
            $dureeproche =$duree-15;
            $dateFinproche = date('Y-m-d', strtotime('+' . $dureeproche . 'day', $datedepart));
            $annee =(int) date('Y',  strtotime($dateFinproche));            
            if($annee>1970){
                $manager->getRequete("update tmptous set calcfinprojet=?,finprojetproche=? where idprojet=? ", array($dateFin,$dateFinproche, $arrayprojet[$i]['idprojet']));
            }
        } elseif ($arrayprojet[$i]['idperiodicite_periodicite'] == MOIS) {
            $datedepart = strtotime($arrayprojet[$i]['datedebutprojet']);
            $duree = ($arrayprojet[$i]['dureeprojet']);
            $dateFin = date('Y-m-d', strtotime('+' . $duree . 'month', $datedepart));
            $dureeproche =($duree*30)-15;
            $dateFinproche = date('Y-m-d', strtotime('+' . $dureeproche . 'day', $datedepart));            
            $annee =(int) date('Y',  strtotime($dateFinproche));
            if($annee>1970){
                $manager->getRequete("update tmptous set calcfinprojet=?,finprojetproche=? where idprojet=? ", array($dateFin,$dateFinproche, $arrayprojet[$i]['idprojet']));
            }
        } elseif ($arrayprojet[$i]['idperiodicite_periodicite'] == ANNEE) {
            $datedepart = strtotime($arrayprojet[$i]['datedebutprojet']);
            $duree = ($arrayprojet[$i]['dureeprojet']);
            $dateFin = date('Y-m-d', strtotime('+' . $duree . 'year', $datedepart));
            $dureeproche =($duree*365)-15;
            $dateFinproche = date('Y-m-d', strtotime('+' . $dureeproche . 'day', $datedepart));
            $annee =(int) date('Y',  strtotime($dateFinproche));
            if($annee>1970){
                $manager->getRequete("update tmptous set calcfinprojet=?,finprojetproche=? where idprojet=? ", array($dateFin,$dateFinproche, $arrayprojet[$i]['idprojet']));
            }
        }
    }
}

$porteur = '';
$arrayporteur1 = $manager->getList("select distinct numero from tmptous");
$arrayporteur = array();

foreach ($arrayporteur1 as $key => $value) {
    $arrayporteur = $manager->getList2("select distinct porteur from tmptous where  numero=?", $value[0]);
    foreach ($arrayporteur as $key1 => $value1) {
        if (!empty($value1[0])) {
            $porteur.= $value1[0] . '  / ';
        }
        $tmptous = new Tmprecherche(substr(trim($porteur), 0, -1), $value[0]);
        $manager->updateProjetcentrale($tmptous, $value[0]);
    }
    $porteur = '';
}
$row = $manager->getList("select * from (select distinct on(numero) *from tmptous where demandeur is not null)p order by idprojet desc ");
$fprow = fopen('../tmp/projetCentrale.json', 'w');
$datausercompte = "";
fwrite($fprow, '{"items": [');
$nbrow = count($row);

for ($i = 0; $i < $nbrow; $i++) {
    if (!empty($row[$i]['datemaj'])) {
            $datemaj = $row[$i]['datemaj'];
        }else{
            $datemaj = '';
        }
    if ($lang == 'fr') {        
        $datausercompte = "" . '{"numero":' . '"' . $row[$i]['numero'] . '"' . "," .
                '"dateprojet":' . '"' . $row[$i]['dateprojet'] . '"' . ","
                . '"idprojet":' . '"' . $row[$i]['idprojet'] . '"' . ","
                . '"datemaj":' . '"' . $datemaj . '"' . ","
                . '"titre":' . '"' . filtredonnee($row[$i]['titre']) . '"' . ","
                . '"refinterneprojet":' . '"' . filtredonnee($row[$i]['refinterneprojet']) . '"' . ","
                . '"libellestatutprojet":' . '"' . str_replace("''", "'", $row[$i]['libellestatutprojet']) . '"' . ","
                . '"idstatutprojet":' . '"' . str_replace("''", "'", $row[$i]['idstatutprojet']) . '"' . ","
                . '"idutilisateur":' . '"' . $row[$i]['idutilisateur'] . '"' . ","
                . '"demandeur":' . '"' . filtredonnee(ucfirst($row[$i]['demandeur'])) . '"' . ","
                . '"porteur":' . '"' . filtredonnee(ucfirst($row[$i]['porteur'])) . '"' . ","
                . '"acronyme":' . '"' . filtredonnee($row[$i]['acronyme']) . '"' . ","
                . '"libellecentrale":' . '"' . $row[$i]['libellecentrale'] . '"' . ","
                . '"calcfinproche":' . '"' . $row[$i]['finprojetproche'] . '"' . ","
                . '"calcfinprojet":' . '"' . $row[$i]['calcfinprojet'] . '"' . ","
                . '"imprime":' . '"' . TXT_PDF . '"' . "},";
        fputs($fprow, $datausercompte);
        fwrite($fprow, '');
    } elseif ($lang == 'en') {
        $datausercompte = "" . '{"numero":' . '"' . $row[$i]['numero'] . '"' . "," .
                '"dateprojet":' . '"' . $row[$i]['dateprojet'] . '"' . ","
                . '"idprojet":' . '"' . $row[$i]['idprojet'] . '"' . ","
                . '"datemaj":' . '"' . $datemaj . '"' . ","
                . '"titre":' . '"' . filtredonnee($row[$i]['titre']) . '"' . ","
                . '"refinterneprojet":' . '"' . filtredonnee($row[$i]['refinterneprojet']) . '"' . ","
                . '"libellestatutprojet":' . '"' . str_replace("''", "'", $row[$i]['libellestatutprojeten']) . '"' . ","
                . '"idstatutprojet":' . '"' . str_replace("''", "'", $row[$i]['idstatutprojet']) . '"' . ","
                . '"idutilisateur":' . '"' . $row[$i]['idutilisateur'] . '"' . ","
                . '"demandeur":' . '"' . filtredonnee(ucfirst($row[$i]['demandeur'])) . '"' . ","
                . '"porteur":' . '"' . filtredonnee(ucfirst($row[$i]['porteur'])) . '"' . ","
                . '"acronyme":' . '"' . filtredonnee($row[$i]['acronyme']) . '"' . ","
                . '"libellecentrale":' . '"' . $row[$i]['libellecentrale'] . '"' . ","
                . '"calcfinproche":' . '"' . $row[$i]['finprojetproche'] . '"' . ","
                . '"calcfinprojet":' . '"' . $row[$i]['calcfinprojet'] . '"' . ","
                . '"imprime":' . '"' . TXT_PDF . '"' . "},";
        fputs($fprow, $datausercompte);
        fwrite($fprow, '');
    }
}
fwrite($fprow, ']}');
$json_fileprojet = "../tmp/projetCentrale.json";
$json_fileRecherche1 = file_get_contents($json_fileprojet);
$json_fileRecherche = str_replace('},]}', '}]}', $json_fileRecherche1);
file_put_contents($json_fileprojet, $json_fileRecherche);
fclose($fprow);
chmod('../tmp/projetCentrale.json', 0777);