<?php

session_start();
include 'decide-lang.php';
include 'class/Manager.php';
include_once 'outils/toolBox.php';
include_once 'outils/constantes.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER

if (isset($_SESSION['pseudo'])) {    
    check_authent($_SESSION['pseudo']);
    $pseudo=$_SESSION['pseudo'];
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
//------------------------------------------------------------------------
//-----RECUPERATION DU LIBELLE DE LA CENTRALE DU RESPONSABLE CENTRALE-----
//------------------------------------------------------------------------
$libellecentrale = $manager->getSingle2("SELECT libellecentrale FROM centrale , utilisateur ,loginpassword  WHERE
idcentrale_centrale = idcentrale AND idlogin_loginpassword = idlogin AND pseudo=?", $pseudo);


$data = utf8_decode("numéro;Date de la demande;Titre du projet;Mise à jour;référence interne;statut du projet;demandeur du projet;Porteur du projet;Acronyme;Date de fin;Date de fin proche");
$data .= "\n";
//SUPPRESSION DE LA TABLE TEMPORAIRE SI ELLE EXISTE
$manager->exeRequete("drop table if exists tmpcentraletous;");
//CREATION DE LA TABLE TEMPORAIRE
$manager->getRequete("CREATE TABLE tmpcentraletous AS (
SELECT p.titre,p.idprojet,p.datemaj,p.idperiodicite_periodicite,p.acronyme,p.datedebutprojet,p.numero,p.dureeprojet ,u.idutilisateur,p.refinterneprojet,p.dateprojet,ce.libellecentrale,s.idstatutprojet,s.libellestatutprojet,s.libellestatutprojeten,u.nom||' -  '|| u.prenom as demandeur,null as porteur
FROM projet p,creer c,utilisateur u,concerne,loginpassword l,centrale ce,statutprojet s
WHERE p.idprojet = c.idprojet_projet AND c.idutilisateur_utilisateur = u.idutilisateur AND concerne.idprojet_projet = p.idprojet AND
concerne.idcentrale_centrale = ce.idcentrale AND l.idlogin = u.idlogin_loginpassword AND s.idstatutprojet = concerne.idstatutprojet_statutprojet
AND ce.libellecentrale =? 
union
SELECT p.titre,p.idprojet,p.datemaj,p.idperiodicite_periodicite,p.acronyme,p.datedebutprojet,p.numero,p.dureeprojet ,u.idutilisateur,p.refinterneprojet,p.dateprojet,ce.libellecentrale,s.idstatutprojet,s.libellestatutprojet,s.libellestatutprojeten,null as demandeur,u.nom||' -  '|| u.prenom as porteur
FROM projet p ,utilisateur u,concerne c,loginpassword l ,centrale ce,statutprojet s,utilisateurporteurprojet up
WHERE c.idprojet_projet = p.idprojet AND c.idcentrale_centrale = ce.idcentrale AND l.idlogin = u.idlogin_loginpassword
AND s.idstatutprojet = c.idstatutprojet_statutprojet AND up.idprojet_projet = p.idprojet AND up.idutilisateur_utilisateur = u.idutilisateur
AND ce.libellecentrale =? 
union
SELECT p.titre,p.idprojet,p.datemaj,p.idperiodicite_periodicite,p.acronyme,p.datedebutprojet,p.numero,p.dureeprojet ,u.idutilisateur,p.refinterneprojet,p.dateprojet,ce.libellecentrale,s.idstatutprojet,s.libellestatutprojet,s.libellestatutprojeten,u.nom||' -  '|| u.prenom as demandeur,null as porteur
FROM projet p,creer c,utilisateur u,concerne,loginpassword l,centrale ce,statutprojet s
WHERE p.idprojet = c.idprojet_projet AND c.idutilisateur_utilisateur = u.idutilisateur AND concerne.idprojet_projet = p.idprojet AND
concerne.idcentrale_centrale = ce.idcentrale AND l.idlogin = u.idlogin_loginpassword AND s.idstatutprojet = concerne.idstatutprojet_statutprojet
AND ce.libellecentrale =? 
union
SELECT p.titre,p.idprojet,p.datemaj,p.idperiodicite_periodicite,p.acronyme,p.datedebutprojet,p.numero,p.dureeprojet ,u.idutilisateur,p.refinterneprojet,p.dateprojet,ce.libellecentrale,s.idstatutprojet,s.libellestatutprojet,s.libellestatutprojeten,null as demandeur,u.nom||' -  '|| u.prenom as porteur
FROM projet p ,utilisateur u,concerne c,loginpassword l ,centrale ce,statutprojet s,utilisateurporteurprojet up
WHERE c.idprojet_projet = p.idprojet AND c.idcentrale_centrale = ce.idcentrale AND l.idlogin = u.idlogin_loginpassword
AND s.idstatutprojet = c.idstatutprojet_statutprojet AND up.idprojet_projet = p.idprojet AND up.idutilisateur_utilisateur = u.idutilisateur
AND ce.libellecentrale =? 
union
SELECT p.titre,p.idprojet,p.datemaj,p.idperiodicite_periodicite,p.acronyme,p.datedebutprojet,p.numero,p.dureeprojet ,u.idutilisateur,p.refinterneprojet,p.dateprojet,ce.libellecentrale,s.idstatutprojet,s.libellestatutprojet,s.libellestatutprojeten,u.nom||' -  '|| u.prenom as demandeur,null as porteur
FROM projet p,creer c,utilisateur u,concerne,loginpassword l,centrale ce,statutprojet s
WHERE p.idprojet = c.idprojet_projet AND c.idutilisateur_utilisateur = u.idutilisateur AND concerne.idprojet_projet = p.idprojet AND
concerne.idcentrale_centrale = ce.idcentrale AND l.idlogin = u.idlogin_loginpassword AND s.idstatutprojet = concerne.idstatutprojet_statutprojet
AND ce.libellecentrale =? 
union
SELECT p.titre,p.idprojet,p.datemaj,p.idperiodicite_periodicite,p.acronyme,p.datedebutprojet,p.numero,p.dureeprojet ,u.idutilisateur,p.refinterneprojet,p.dateprojet,ce.libellecentrale,s.idstatutprojet,s.libellestatutprojet,s.libellestatutprojeten,null as demandeur,u.nom||' -  '|| u.prenom as porteur
FROM projet p ,utilisateur u,concerne c,loginpassword l ,centrale ce,statutprojet s,utilisateurporteurprojet up
WHERE c.idprojet_projet = p.idprojet AND c.idcentrale_centrale = ce.idcentrale AND l.idlogin = u.idlogin_loginpassword
AND s.idstatutprojet = c.idstatutprojet_statutprojet AND up.idprojet_projet = p.idprojet AND up.idutilisateur_utilisateur = u.idutilisateur
AND ce.libellecentrale =? 
union
SELECT p.titre,p.idprojet,p.datemaj,p.idperiodicite_periodicite,p.acronyme,p.datedebutprojet,p.numero,p.dureeprojet ,u.idutilisateur,p.refinterneprojet,p.dateprojet,ce.libellecentrale,s.idstatutprojet,s.libellestatutprojet,s.libellestatutprojeten,u.nom||' -  '|| u.prenom as demandeur, null as porteur
FROM projet p,creer c,utilisateur u,concerne,loginpassword l,centrale ce,statutprojet s
WHERE p.idprojet = c.idprojet_projet AND c.idutilisateur_utilisateur = u.idutilisateur AND concerne.idprojet_projet = p.idprojet AND
concerne.idcentrale_centrale = ce.idcentrale AND l.idlogin = u.idlogin_loginpassword AND s.idstatutprojet = concerne.idstatutprojet_statutprojet
AND ce.libellecentrale =? 
union
SELECT p.titre,p.idprojet,p.datemaj,p.idperiodicite_periodicite,p.acronyme,p.datedebutprojet,p.numero,p.dureeprojet ,u.idutilisateur,p.refinterneprojet,p.dateprojet,ce.libellecentrale,s.idstatutprojet,s.libellestatutprojet,s.libellestatutprojeten,null as demandeur,u.nom||' -  '|| u.prenom as porteur
FROM projet p ,utilisateur u,concerne c,loginpassword l ,centrale ce,statutprojet s,utilisateurporteurprojet up
WHERE c.idprojet_projet = p.idprojet AND c.idcentrale_centrale = ce.idcentrale AND l.idlogin = u.idlogin_loginpassword
AND s.idstatutprojet = c.idstatutprojet_statutprojet AND up.idprojet_projet = p.idprojet AND up.idutilisateur_utilisateur = u.idutilisateur
AND ce.libellecentrale =? 
union
SELECT p.titre,p.idprojet,p.datemaj,p.idperiodicite_periodicite,p.acronyme,p.datedebutprojet,p.numero,p.dureeprojet ,u.idutilisateur,p.refinterneprojet,p.dateprojet,ce.libellecentrale,s.idstatutprojet,s.libellestatutprojet,s.libellestatutprojeten,u.nom||' -  '|| u.prenom as demandeur, null as porteur
FROM projet p,creer c,utilisateur u,concerne,loginpassword l,centrale ce,statutprojet s
WHERE p.idprojet = c.idprojet_projet AND c.idutilisateur_utilisateur = u.idutilisateur AND concerne.idprojet_projet = p.idprojet AND
concerne.idcentrale_centrale = ce.idcentrale AND l.idlogin = u.idlogin_loginpassword AND s.idstatutprojet = concerne.idstatutprojet_statutprojet
AND ce.libellecentrale =? 
union
SELECT p.titre,p.idprojet,p.datemaj,p.idperiodicite_periodicite,p.acronyme,p.datedebutprojet,p.numero,p.dureeprojet ,u.idutilisateur,p.refinterneprojet,p.dateprojet,ce.libellecentrale,s.idstatutprojet,s.libellestatutprojet,s.libellestatutprojeten,null as demandeur,u.nom||' -  '|| u.prenom as porteur
FROM projet p ,utilisateur u,concerne c,loginpassword l ,centrale ce,statutprojet s,utilisateurporteurprojet up
WHERE c.idprojet_projet = p.idprojet AND c.idcentrale_centrale = ce.idcentrale AND l.idlogin = u.idlogin_loginpassword
AND s.idstatutprojet = c.idstatutprojet_statutprojet AND up.idprojet_projet = p.idprojet AND up.idutilisateur_utilisateur = u.idutilisateur
AND ce.libellecentrale =? 
)", array($libellecentrale, $libellecentrale, $libellecentrale, $libellecentrale, $libellecentrale, $libellecentrale,
    $libellecentrale, $libellecentrale, $libellecentrale, $libellecentrale));
$manager->exeRequete("ALTER TABLE tmpcentraletous ADD COLUMN calcfinprojet date;");
$manager->exeRequete("ALTER TABLE tmpcentraletous ADD COLUMN finprojetproche date;");
$arrayprojet = $manager->getList("select * from tmpcentraletous");
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
                $manager->getRequete("update tmpcentraletous set calcfinprojet=?,finprojetproche=? where idprojet=? ", array($dateFin,$dateFinproche, $arrayprojet[$i]['idprojet']));
            }
        } elseif ($arrayprojet[$i]['idperiodicite_periodicite'] == MOIS) {
            $datedepart = strtotime($arrayprojet[$i]['datedebutprojet']);
            $duree = ($arrayprojet[$i]['dureeprojet']);
            $dateFin = date('Y-m-d', strtotime('+' . $duree . 'month', $datedepart));
            $dureeproche =($duree*30)-15;
            $dateFinproche = date('Y-m-d', strtotime('+' . $dureeproche . 'day', $datedepart));            
            $annee =(int) date('Y',  strtotime($dateFinproche));
            if($annee>1970){
                $manager->getRequete("update tmpcentraletous set calcfinprojet=?,finprojetproche=? where idprojet=? ", array($dateFin,$dateFinproche, $arrayprojet[$i]['idprojet']));
            }
        } elseif ($arrayprojet[$i]['idperiodicite_periodicite'] == ANNEE) {
            $datedepart = strtotime($arrayprojet[$i]['datedebutprojet']);
            $duree = ($arrayprojet[$i]['dureeprojet']);
            $dateFin = date('Y-m-d', strtotime('+' . $duree . 'year', $datedepart));
            $dureeproche =($duree*365)-15;
            $dateFinproche = date('Y-m-d', strtotime('+' . $dureeproche . 'day', $datedepart));
            $annee =(int) date('Y',  strtotime($dateFinproche));
            if($annee>1970){
                $manager->getRequete("update tmpcentraletous set calcfinprojet=?,finprojetproche=? where idprojet=? ", array($dateFin,$dateFinproche, $arrayprojet[$i]['idprojet']));
            }
        }
    }
}

$porteur = '';
$arrayporteur1 = $manager->getList("select distinct numero from tmpcentraletous");
$arrayporteur = array();

foreach ($arrayporteur1 as $key => $value) {
    $arrayporteur = $manager->getList2("select distinct porteur from tmpcentraletous where  numero=?", $value[0]);
    foreach ($arrayporteur as $key1 => $value1) {
        if (!empty($value1[0])) {
            $porteur.= $value1[0] . '  / ';
        }
        $tmpcentraletous = new Tmprecherche(substr(trim($porteur), 0, -1), $value[0]);
        $manager->updateProjetTouscentrale($tmpcentraletous, $value[0]);
    }
    $porteur = '';
}

$row = $manager->getList("select * from (select distinct on(idprojet) *from tmpcentraletous where demandeur is not null)p order by idprojet desc ");
if (count($row) != 0) {
// ENREGISTREMENT DES RESULTATS LIGNE PAR LIGNE
    for ($i = 0; $i < count($row); $i++) {
        $idprojet = $row[$i]['idprojet'];
                
        if (!empty($row[$i]['refinterneprojet'])) {
            $refinterne = $row[$i]['refinterneprojet'];
        } else {
            $refinterne = "";
        }
//numéro;Date de la demande;Titre du projet;Mise à jour;référence interne;statut du projet;demandeur du projet;Porteur du projet;Acronyme;Date de fin;Date de fin proche   
        
        $originalDate = date('d-m-Y');
        $data .= "" .
                $row[$i]['numero'] . ";" .
                $row[$i]['dateprojet'] . ";" .
                str_replace("''", "'", stripslashes(utf8_decode(trim($row[$i]['titre'])))) . ";" .
                $row[$i]['datemaj'] . ";" .
                str_replace("''", "'", stripslashes(utf8_decode(trim($refinterne)))) . ";" .
                str_replace("''", "'", stripslashes(utf8_decode(trim($row[$i]['libellestatutprojet'] )))). ";" .
                str_replace("''", "'", stripslashes(utf8_decode(trim($row[$i]['demandeur'])))) . ";" .
                str_replace("''", "'", stripslashes(utf8_decode(trim($row[$i]['porteur'])))) . ";" .
                str_replace("''", "'", stripslashes(utf8_decode(trim($row[$i]['acronyme'])))) . ";" .
                $row[$i]['calcfinprojet']. ";" .
                $row[$i]['finprojetproche'] . "\n";
                
    }
    $libcentrale= $manager->getSingle2("SELECT libellecentrale FROM loginpassword,centrale,utilisateur WHERE idlogin_loginpassword = idlogin AND idcentrale_centrale = idcentrale AND pseudo=?", $pseudo);
// Déclaration du type de contenu    
    header("Content-type: application/vnd.ms-excel;charset=UTF-8");
    header("Content-disposition: attachment; filename=exportprojetcentrale_" . $libcentrale . '_' . $originalDate . ".csv");
    print $data;
    exit;
} else {    
    echo ' <script>alert("' . utf8_decode(TXT_PASDEPROJET) . '");window.location.replace("/' . REPERTOIRE . '/projet_centrale/' . $lang . '/'.$libellecentrale.'")</script>';
    exit();
}
BD::deconnecter();