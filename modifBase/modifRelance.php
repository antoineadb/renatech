<?php

session_start();
include_once '../outils/constantes.php';
include '../decide-lang.php';
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
$jsonASuppr = '[';
foreach ($_POST as $value) {
    $jsonASuppr .= $value;
}

$nbEmail = 25;
$jsonASuppr .= ']';
$jsonSuppr = str_replace("}{", "},{", $jsonASuppr);
$jsonSupprParsed = json_decode($jsonSuppr, true);
include '../class/email.php';
$dateMoins3mois = date('Y-m-d', strtotime('-3 month'));

$sqlInterne ="
    SELECT distinct on (p.numero) p.idprojet,p.datemaj,p.dureeprojet,p.refinterneprojet,p.numero,p.titre,p.datedebutprojet,u.nom,u.prenom,p.idperiodicite_periodicite,l.mail, 
    u.idutilisateur,p.dateenvoiemail , p.interneexterne FROM projet p,utilisateur u,creer cr,centrale ce,concerne co,typeprojet t,statutprojet s, loginpassword l WHERE u.idcentrale_centrale IS NOT NULL 
    AND porteurprojet =TRUE AND cr.idprojet_projet = p.idprojet AND cr.idprojet_projet = p.idprojet AND cr.idutilisateur_utilisateur = u.idutilisateur AND co.idcentrale_centrale = ce.idcentrale 
    AND co.idprojet_projet = p.idprojet AND t.idtypeprojet = p.idtypeprojet_typeprojet AND l.idlogin = u.idlogin_loginpassword AND s.idstatutprojet = co.idstatutprojet_statutprojet and ce.idcentrale = ? 
    AND (s.idstatutprojet=? OR s.idstatutprojet=?) AND p.datemaj <? AND p.interneexterne is null AND trashed =FALSE AND p.devtechnologique=TRUE
    UNION
    SELECT distinct on (p.numero) p.idprojet,p.datemaj,p.dureeprojet,p.refinterneprojet,p.numero,p.titre,p.datedebutprojet,u.nom,u.prenom,p.idperiodicite_periodicite,l.mail, u.idutilisateur,p.dateenvoiemail ,
    p.interneexterne FROM projet p,utilisateur u,creer cr,centrale ce,concerne co,typeprojet t,statutprojet s, loginpassword l 
    WHERE  cr.idprojet_projet = p.idprojet AND cr.idprojet_projet = p.idprojet AND cr.idutilisateur_utilisateur = u.idutilisateur AND co.idcentrale_centrale = ce.idcentrale 
    AND co.idprojet_projet = p.idprojet AND t.idtypeprojet = p.idtypeprojet_typeprojet AND l.idlogin = u.idlogin_loginpassword AND s.idstatutprojet = co.idstatutprojet_statutprojet and ce.idcentrale = ? 
    AND (s.idstatutprojet=? OR s.idstatutprojet=?) AND p.datemaj <? AND p.interneexterne ='I' AND trashed =FALSE AND p.devtechnologique=TRUE";

$sqlExterne ="
   SELECT distinct on (p.numero) p.idprojet,p.datemaj,p.dureeprojet,p.refinterneprojet,p.numero,p.titre,p.datedebutprojet,u.nom,u.prenom,p.idperiodicite_periodicite,l.mail, u.idutilisateur,p.dateenvoiemail , 
    p.interneexterne FROM projet p,utilisateur u,creer cr,centrale ce,concerne co,typeprojet t,statutprojet s, loginpassword l WHERE (u.idcentrale_centrale IS NULL OR p.porteurprojet =FALSE)  
    AND cr.idprojet_projet = p.idprojet AND cr.idprojet_projet = p.idprojet AND cr.idutilisateur_utilisateur = u.idutilisateur AND co.idcentrale_centrale = ce.idcentrale AND co.idprojet_projet = p.idprojet 
    AND t.idtypeprojet = p.idtypeprojet_typeprojet AND l.idlogin = u.idlogin_loginpassword AND s.idstatutprojet = co.idstatutprojet_statutprojet and ce.idcentrale = ? AND (s.idstatutprojet=? OR s.idstatutprojet=?) 
    AND p.datemaj <? AND p.interneexterne is null AND trashed =FALSE AND p.devtechnologique=TRUE
    UNION
    SELECT distinct on (p.numero) p.idprojet,p.datemaj,p.dureeprojet,p.refinterneprojet,p.numero,p.titre,p.datedebutprojet,u.nom,u.prenom,p.idperiodicite_periodicite,l.mail, u.idutilisateur,p.dateenvoiemail , 
    p.interneexterne FROM projet p,utilisateur u,creer cr,centrale ce,concerne co,typeprojet t,statutprojet s, loginpassword l WHERE  cr.idprojet_projet = p.idprojet AND cr.idprojet_projet = p.idprojet 
    AND cr.idutilisateur_utilisateur = u.idutilisateur AND co.idcentrale_centrale = ce.idcentrale AND co.idprojet_projet = p.idprojet AND t.idtypeprojet = p.idtypeprojet_typeprojet 
    AND l.idlogin = u.idlogin_loginpassword AND s.idstatutprojet = co.idstatutprojet_statutprojet and ce.idcentrale = ? AND (s.idstatutprojet=? OR s.idstatutprojet=?) AND p.datemaj <? AND p.interneexterne ='E'
    AND trashed =FALSE AND p.devtechnologique=TRUE";

$sqlExterneInterne  ="SELECT distinct on (p.numero) p.idprojet,p.datemaj,p.dureeprojet,p.refinterneprojet,p.numero,p.titre,p.datedebutprojet,u.nom,u.prenom,p.idperiodicite_periodicite,l.mail,
    u.idutilisateur,p.dateenvoiemail   FROM  projet p,utilisateur u,creer cr,centrale ce,concerne co,typeprojet t,statutprojet s, loginpassword l 
    WHERE cr.idprojet_projet = p.idprojet AND cr.idprojet_projet = p.idprojet 
    AND cr.idutilisateur_utilisateur = u.idutilisateur AND co.idcentrale_centrale = ce.idcentrale AND co.idprojet_projet = p.idprojet  AND  t.idtypeprojet = p.idtypeprojet_typeprojet 
    AND l.idlogin = u.idlogin_loginpassword AND s.idstatutprojet = co.idstatutprojet_statutprojet and ce.idcentrale = ?   AND (s.idstatutprojet=? OR s.idstatutprojet=?)  AND p.datemaj <? AND trashed =FALSE AND p.devtechnologique=TRUE";

if (isset($_POST['interneExterne']) && $_POST['interneExterne'] == 1) {
    $sql =$sqlInterne;
    $projetARelancer = $manager->getListbyArray($sql, array(IDCENTRALEUSER, ENCOURSREALISATION,ENCOURSANALYSE, $dateMoins3mois,IDCENTRALEUSER, ENCOURSREALISATION,ENCOURSANALYSE, $dateMoins3mois));
} elseif (isset($_POST['interneExterne']) && $_POST['interneExterne'] == 2) {
    $sql =$sqlExterne;
    $projetARelancer = $manager->getListbyArray($sql, array(IDCENTRALEUSER, ENCOURSREALISATION,ENCOURSANALYSE, $dateMoins3mois,IDCENTRALEUSER, ENCOURSREALISATION,ENCOURSANALYSE, $dateMoins3mois));
}else{    
    $sql =$sqlExterneInterne;
    $projetARelancer = $manager->getListbyArray($sql, array(IDCENTRALEUSER, ENCOURSREALISATION,ENCOURSANALYSE, $dateMoins3mois));
}


$nbprojetARelancer = count($projetARelancer);
// -----------------------------------------------------------------------------------
//  RECUPERATION DU FICHIER JSON DE DE LA LISTE DES PROJETS EN COURS DE REALISATION
//  QUE JE TRANSFORME EN TABLEAU
// -----------------------------------------------------------------------------------
$fpProjetEncoursRealisation = fopen('../tmp/projetarelancer.json', 'w');
$dataEncoursRealisation = "";
fwrite($fpProjetEncoursRealisation, '{"items": [');
for ($i = 0; $i < $nbprojetARelancer; $i++) {
     if (!empty($projetARelancer[$i]['datemaj'])) {
        $datemaj = $projetARelancer[$i]['datemaj'];
    } else {
        $datemaj = '';
    }

    if ($projetARelancer[$i]['idperiodicite_periodicite'] == JOUR) {
        $datedepart = strtotime($projetARelancer[$i]['datedebutprojet']);
        $duree = ($projetARelancer[$i]['dureeprojet']);
        $dateFin = date('Y-m-d', strtotime('+' . $duree . 'day', $datedepart));
    } elseif ($projetARelancer[$i]['idperiodicite_periodicite'] == MOIS) {
        $datedepart = strtotime($projetARelancer[$i]['datedebutprojet']);
        $duree = ($projetARelancer[$i]['dureeprojet']);
        $dateFin = date('Y-m-d', strtotime('+' . $duree . 'month', $datedepart));
    } elseif ($projetARelancer[$i]['idperiodicite_periodicite'] == ANNEE) {
        $datedepart = strtotime($projetARelancer[$i]['datedebutprojet']);
        $duree = ($projetARelancer[$i]['dureeprojet']);
        $dateFin = date('Y-m-d', strtotime('+' . $duree . 'year', $datedepart));
    }
    $dataEncoursRealisation = ""
            . '{"datedebutprojet":' . '"' . $projetARelancer[$i]['datedebutprojet'] . '"' . ","
            . '"datemaj":' . '"' . $datemaj . '"' . ","
            . '"numero":' . '"' . $projetARelancer[$i]['numero'] . '"' . ","
            . '"idprojet":' . '"' . $projetARelancer[$i]['idprojet'] . '"' . ","
            . '"titre":' . '"' . filtredonnee($projetARelancer[$i]['titre']) . '"' . ","
            . '"mail":' . '"' . filtredonnee($projetARelancer[$i]['mail']) . '"' . ","
            . '"idutilisateur":' . '"' . $projetARelancer[$i]['idutilisateur'] . '"' . ","
            . '"nom":' . '"' . filtredonnee($projetARelancer[$i]['nom']) . ' ' . filtredonnee($projetARelancer[$i]['prenom']) . '"' . ","
            . '"datefin":' . '"' . $dateFin . '"' . ","
            . '"dateheureenvoiemail":' . '"' . $projetARelancer[$i]['dateenvoiemail'] . '"' . ","
            . '"refinterneprojet":' . '"' . filtredonnee($projetARelancer[$i]['refinterneprojet']) . '"' . "},";
    fputs($fpProjetEncoursRealisation, $dataEncoursRealisation);
    fwrite($fpProjetEncoursRealisation, '');
}
fwrite($fpProjetEncoursRealisation, ']}');
$jsonEncoursRealisation = file_get_contents("../tmp/projetarelancer.json");
$jsonEncoursRealisation1 = str_replace('},]}', '}]}', $jsonEncoursRealisation);
file_put_contents("../tmp/projetarelancer.json", $jsonEncoursRealisation1);
fclose($fpProjetEncoursRealisation);
chmod("../tmp/projetarelancer.json", 0777);
// -----------------------------------------------------------------------------------
//                                    TRANSFORMATION  DU FICHIER JSON EN TABLEAU
// -----------------------------------------------------------------------------------*/
    if (is_file('../tmp/projetarelancer.json')) {
        $emailATraiter = file_get_contents("../tmp/projetarelancer.json");
        $emailAEnvoyer = json_decode($emailATraiter, true);
    }
    $bodyCorps = $manager->getList2("SELECT  libellefrancais,libelleanglais FROM libelleapplication WHERE reflibelle=?","TXT_RELANCEEMAIL_".IDCENTRALEUSER); 
    if($bodyCorps==null){
        $bodyCorps = $manager->getList2("SELECT libellefrancais,libelleanglais FROM libelleapplication WHERE reflibelle=?","TXT_RELANCEEMAIL"); 
    }
    if($lang=='fr'){
        $body= utf8_decode($bodyCorps[0]['libellefrancais']);
    }else{
        $body= $bodyCorps[0]['libelleanglais'];
    }

// construction d'un tableau de numero de projet qui sont coché, on ne dois pas envoyer d'email à ces projets
    $arrayNumeroCoche = array();
    for ($i = 0; $i < $_SESSION['nbprojet']; $i++) {
        if (isset($_POST[$i])) {
            array_push($arrayNumeroCoche, $_POST[$i]);
        }
    }
    
    $condition = boucleTempo($nbprojetARelancer, $nbEmail);
    if (!isset($_GET['cpt'])) {//cas ou on a pas encore envoyer une salves d'email           
        for ($i = 0; $i < $nbEmail; $i++) {
            if (isset($emailAEnvoyer['items'][$i]['numero'])) {//on vérifie que le numéro existe
                $sujet = utf8_decode(TXT_NUMPROJET . ' ' . $emailAEnvoyer['items'][$i]['numero']); //on définis l'email
                $envoiEmailUtilisateur = new ProjetDateEnvoiEmail(date("Y-m-d H:i:s"), $emailAEnvoyer['items'][$i]['idprojet']);
                $manager->updateDateenvoiemail($envoiEmailUtilisateur, $emailAEnvoyer['items'][$i]['idprojet']); //on met à jour le champs date envoi de l'email de la table projet                
                sendEmail($body, $sujet, $emailAEnvoyer['items'][$i]['mail']);//on envoi l'email
            }
        }

        if (isset($_POST['interne']) && !isset($_POST['externe'])) {
            header('Location: /' . REPERTOIRE . '/relance/' . $lang . '/c/1/' . $condition[0]);
        } elseif (!isset($_POST['interne']) && isset($_POST['externe'])) {
            header('Location: /' . REPERTOIRE . '/relance/' . $lang . '/c/2/' . $condition[0]);
        } else {
            header('Location: /' . REPERTOIRE . '/relance/' . $lang . '/' . $condition[0]);
        }
    } else {//cas ou on a déja envoyer une ou plusieurs salves d'email
        /*  il faut récupérer l'index correspondsant à la valeur $_GET['cpt'] dans la table $condition array([0]=>20,[1]=>40,[2]=>60,[3]=>80...) et l'incrémenter de +1  */
        
        $key = array_search($_GET['cpt'], $condition) + 1;
        if (!isset($condition[$key])) {
            $key = array_search($_GET['cpt'], $condition);
        }
        for ($i = $_GET['cpt']; $i < $condition[$key]; $i++) {
            if (isset($emailAEnvoyer['items'][$i]['numero'])) {
                $sujet = utf8_decode(TXT_NUMPROJET . ' ' . $emailAEnvoyer['items'][$i]['numero']);
                $envoiEmailUtilisateur = new ProjetDateEnvoiEmail(date("Y-m-d H:i:s"), $emailAEnvoyer['items'][$i]['idprojet']);
                $manager->updateDateenvoiemail($envoiEmailUtilisateur, $emailAEnvoyer['items'][$i]['idprojet']);                
                sendEmail($body, $sujet, $emailAEnvoyer[$i]['mail']);
            }
        }
    }
    
    $nbEmailInterne = $manager->getSinglebyArray("SELECT count(p.idprojet) FROM projet p,utilisateur u,creer cr,centrale ce,concerne co,typeprojet t,statutprojet s, loginpassword l 
                    WHERE cr.idprojet_projet = p.idprojet AND cr.idutilisateur_utilisateur = u.idutilisateur AND co.idcentrale_centrale = ce.idcentrale AND co.idprojet_projet = p.idprojet 
                    AND t.idtypeprojet = p.idtypeprojet_typeprojet AND l.idlogin = u.idlogin_loginpassword AND s.idstatutprojet = co.idstatutprojet_statutprojet AND ce.idcentrale = ?AND s.idstatutprojet=? 
                    AND trashed =FALSE AND u.idcentrale_centrale IS NOT NULL AND porteurprojet =TRUE AND p.devtechnologique=TRUE AND p.dateenvoiemail =?", array(IDCENTRALEUSER, ENCOURSREALISATION, date('Y-m-d')));

    $nbEmailExterne = $manager->getSinglebyArray("SELECT count(p.idprojet) FROM projet p,utilisateur u,creer cr,centrale ce,concerne co,typeprojet t,statutprojet s, loginpassword l 
                    WHERE cr.idprojet_projet = p.idprojet AND cr.idutilisateur_utilisateur = u.idutilisateur AND co.idcentrale_centrale = ce.idcentrale AND co.idprojet_projet = p.idprojet 
                    AND t.idtypeprojet = p.idtypeprojet_typeprojet AND l.idlogin = u.idlogin_loginpassword AND s.idstatutprojet = co.idstatutprojet_statutprojet AND ce.idcentrale = ? AND s.idstatutprojet=?
                    AND trashed =FALSE AND (u.idcentrale_centrale IS NULL  OR  p.porteurprojet =FALSE) AND p.devtechnologique=TRUE AND p.dateenvoiemail =? ", array(IDCENTRALEUSER, ENCOURSREALISATION, date('Y-m-d')));
    switch (intval($_POST['interneExterne'])) {
        case 1:
            header('Location: /' . REPERTOIRE . '/relance/' . $lang . '/c/1/' .$nbEmailInterne);
            break;
        case 2:
            header('Location: /' . REPERTOIRE . '/relance/' . $lang . '/c/2/' . $nbEmailExterne);
            break;
        case 3:
            header('Location: /' . REPERTOIRE . '/relance/' . $lang . '/' . ($nbEmailExterne+$nbEmailInterne));
            break;
        default :
            header('Location: /' . REPERTOIRE . '/relance/' . $lang . '/' . ($nbEmailExterne+$nbEmailInterne));
    }
/*} else {
    header('Location: /' . REPERTOIRE . '/relance/' . $lang . '/err');
}*/
/**
  Madame, Monsieur,
  Nous vous rappelons que vous avez un projet dans l'application RENATECH en référence ci-dessus.
  Pour le bon fonctionnement de l'application nous vous demandons de mettre à jour régulièrement votre projet.
  Pour tout besoin d'information sur le fonctionnement de l'application, dans le menu « Divers/Manuel utilisateur » vous pouvez télécharger une notice explicative du fonctionnement de l'application.
  En vous remerciant pour votre confiance au réseau RENATECH. En cas de nouveau projet, merci de bien vouloir remplir une nouvelle demande
  Sincères salutations,
  Le réseau Renatech.
  Retour sur la plateforme Renatech
  Merci de ne pas répondre à cette adresse.
 */
