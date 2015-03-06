<?php

session_start();
include_once '../class/Chiffrement.php';
include_once '../class/Manager.php';
include_once '../decide-lang.php';
include_once '../outils/constantes.php';
include_once '../outils/toolBox.php';
$db = BD::connecter();
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
$manager = new Manager($db);
if (isset($_SESSION['email'])) {
    $mail = $_SESSION['email'];
    $_SESSION['mail'] = $mail;
} else {
    $mail = $_SESSION['mail'];
    $_SESSION['mail'] = $mail;
}
$pseudo = $_SESSION['pseudo'];
$_SESSION['pseudo'] = $pseudo;
nomEntete($mail, $pseudo);
// VERIFICATION DE L'EXISTENCE DE L'UTILISATEUR
$idLogin = $manager->getSingle2("SELECT idlogin FROM loginpassword where pseudo=?", $pseudo);
$_SESSION['idutilisateur'] = $manager->getSingle2("SELECT idutilisateur   FROM utilisateur where idlogin_loginpassword=?", $idLogin);
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
//-----------------------------------------------------------------------------------------------------------------------------------------------
//                                                                  PROJET CENTRALE TOUS
//-----------------------------------------------------------------------------------------------------------------------------------------------
$manager->exeRequete("drop table if exists tmptouscentrale;");
$manager->exeRequete("CREATE TABLE tmptouscentrale AS (SELECT p.numero,p.datemaj,p.dureeprojet,p.idperiodicite_periodicite,p.datedebutprojet,p.titre,p.dateprojet,ce.libellecentrale,s.idstatutprojet,s.libellestatutprojet,s.libellestatutprojeten,p.idprojet,u.nom,u.nomentreprise,u.entrepriselaboratoire
FROM  projet p,utilisateur u,creer cr,centrale ce,concerne co,typeprojet t,statutprojet s
WHERE cr.idprojet_projet = p.idprojet AND cr.idutilisateur_utilisateur = u.idutilisateur AND co.idcentrale_centrale = ce.idcentrale AND
  co.idprojet_projet = p.idprojet AND  t.idtypeprojet = p.idtypeprojet_typeprojet
  AND s.idstatutprojet = co.idstatutprojet_statutprojet  order by p.idprojet)");
$manager->exeRequete("ALTER TABLE tmptouscentrale ADD COLUMN calcfinprojet date;");
$manager->exeRequete("ALTER TABLE tmptouscentrale ADD COLUMN finprojetproche date;");
$rowprojet=$manager->getList("select * from tmptouscentrale");
//-----------------------------------------------------------------------------------------------------------------------------------------------
//                                                                  REMPLISSAGE DES DATE DE FIN DE PROJET ET DE PROJET PROCHE
//-----------------------------------------------------------------------------------------------------------------------------------------------

$nbarrayprojet = count($rowprojet);
for ($i = 0; $i < $nbarrayprojet; $i++) {
    if($rowprojet[$i]['idstatutprojet']!=ENATTENTE && $rowprojet[$i]['idstatutprojet']!=ENCOURSANALYSE&& $rowprojet[$i]['idstatutprojet']!=REFUSE&& $rowprojet[$i]['idstatutprojet']!=FINI
            && $rowprojet[$i]['idstatutprojet']!=CLOTURE&& $rowprojet[$i]['idstatutprojet']!=ACCEPTE){
        if ($rowprojet[$i]['idperiodicite_periodicite'] == JOUR) {
            $datedepart = strtotime($rowprojet[$i]['datedebutprojet']);
            $duree = ($rowprojet[$i]['dureeprojet']);
            $dateFin = date('Y-m-d', strtotime('+' . $duree . 'day', $datedepart));
            $dureeproche =$duree-15;
            $dateFinproche = date('Y-m-d', strtotime('+' . $dureeproche . 'day', $datedepart));
            $annee =(int) date('Y',  strtotime($dateFinproche));            
            if($annee>1970){
                $manager->getRequete("update tmptouscentrale set calcfinprojet=?,finprojetproche=? where idprojet=? ", array($dateFin,$dateFinproche, $rowprojet[$i]['idprojet']));
            }
        } elseif ($rowprojet[$i]['idperiodicite_periodicite'] == MOIS) {
            $datedepart = strtotime($rowprojet[$i]['datedebutprojet']);
            $duree = ($rowprojet[$i]['dureeprojet']);
            $dateFin = date('Y-m-d', strtotime('+' . $duree . 'month', $datedepart));
            $dureeproche =($duree*30)-15;
            $dateFinproche = date('Y-m-d', strtotime('+' . $dureeproche . 'day', $datedepart));            
            $annee =(int) date('Y',  strtotime($dateFinproche));
            if($annee>1970){
                $manager->getRequete("update tmptouscentrale set calcfinprojet=?,finprojetproche=? where idprojet=? ", array($dateFin,$dateFinproche, $rowprojet[$i]['idprojet']));
            }
        } elseif ($rowprojet[$i]['idperiodicite_periodicite'] == ANNEE) {
            $datedepart = strtotime($rowprojet[$i]['datedebutprojet']);
            $duree = ($rowprojet[$i]['dureeprojet']);
            $dateFin = date('Y-m-d', strtotime('+' . $duree . 'year', $datedepart));
            $dureeproche =($duree*365)-15;
            $dateFinproche = date('Y-m-d', strtotime('+' . $dureeproche . 'day', $datedepart));
            $annee =(int) date('Y',  strtotime($dateFinproche));
            if($annee>1970){
                $manager->getRequete("update tmptouscentrale set calcfinprojet=?,finprojetproche=? where idprojet=? ", array($dateFin,$dateFinproche, $rowprojet[$i]['idprojet']));
            }
        }
    }
}


//-----------------------------------------------------------------------------------------------------------------------------------------------
//                                                                  FIN REMPLISSAGE DES DATE DE FIN DE PROJET ET DE PROJET PROCHE
//-----------------------------------------------------------------------------------------------------------------------------------------------
$rowproject=$manager->getList("select * from tmptouscentrale");
//CREATION DU PROJET JSON EN COURS
$fpprojet = fopen('../tmp/projetTousCentrales.json', 'w');
fwrite($fpprojet, '{"items": [');
for ($i = 0; $i < count($rowproject); $i++) {
      if (!empty($rowproject[$i]['datemaj'])) {
        $datemaj = $rowproject[$i]['datemaj'];
    } else {
        $datemaj = '';
    }
    $dataprojet = "" .
            '{"numero":' . '"' . $rowproject[$i]['numero'] . '"'
            . "," . '"idprojet":' . '"' . $rowproject[$i]['idprojet'] . '"'
            . "," . '"dateprojet":' . '"' . $rowproject[$i]['dateprojet'] . '"'
            . "," . '"titre":' . '"' . filtredonnee($rowproject[$i]['titre']) . '"'
            . "," . '"datemaj":' . '"' . $datemaj . '"'
            . "," . '"dureeprojet":' . '"' . $rowproject[$i]['dureeprojet'] . '"'
            . "," . '"datedebutprojet":' . '"' . $rowproject[$i]['datedebutprojet'] . '"'
            . "," . '"libellestatutprojet":' . '"' . $rowproject[$i]['libellestatutprojet'] . '"'
            . "," . '"nom":' . '"' . filtredonnee($rowproject[$i]['nom']) . '"'
            . "," . '"imprime":' . '"' . TXT_PDF . '"'
            . "," . '"calcfinproche":' . '"' . $rowproject[$i]['finprojetproche'] . '"'
            . "," . '"calcfinprojet":' . '"' . $rowproject[$i]['calcfinprojet'] . '"'
            . "," . '"nomentreprise":' . '"' . filtredonnee($rowproject[$i]['nomentreprise']) . '"'
            . "," . '"entrepriselaboratoire":' . '"' . filtredonnee($rowproject[$i]['entrepriselaboratoire']) . '"'
            . "," . '"libellecentrale":' . '"' . $rowproject[$i]['libellecentrale'] . '"' . "},";
    fputs($fpprojet, $dataprojet);
    fwrite($fpprojet, '');
}
fwrite($fpprojet, ']}');
$json_fileprojet = "../tmp/projetTousCentrales.json";
$jsonprojet1 = file_get_contents($json_fileprojet);
$jsonprojet = str_replace('},]}', '}]}', $jsonprojet1);
file_put_contents($json_fileprojet, $jsonprojet);
fclose($fpprojet);
chmod('../tmp/projetTousCentrales.json', 0777);
$_SESSION['email'] = $mail;
$_SESSION['pseudo'] = $pseudo;
header('location:/' . REPERTOIRE . '/projet_centrale/' . $lang);
BD::deconnecter();
