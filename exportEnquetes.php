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
$data = utf8_decode("Nom;Prénom;E-Mail;Nom du responsable; Mail du reponsable;Acronyme du laboratoire; Nom de l'entreprise ou du laboratoire");
$data .= "\n";

if (isset($_POST['annee'])&& !empty($_POST['annee'])) {
        if($_POST['annee']==1){
            $annee = '1970';
            $arrayProjetEncours=$manager->getListbyArray("SELECT p.idprojet FROM projet p,creer c,utilisateur u,concerne co
                WHERE p.idprojet = co.idprojet_projet AND c.idprojet_projet = p.idprojet AND u.idutilisateur = c.idutilisateur_utilisateur
                AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=? AND trashed =FALSE
                AND  datedebutprojet is not null AND  datestatutfini is null  AND  datestatutcloturer is null
                AND  datestatutrefuser is null 
                UNION
                SELECT  p.idprojet
                FROM projet p,creer c,utilisateur u,concerne co
                WHERE p.idprojet = co.idprojet_projet AND c.idprojet_projet = p.idprojet AND u.idutilisateur = c.idutilisateur_utilisateur
                AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=? AND trashed =FALSE
                AND  datedebutprojet is not null AND  datestatutfini is not null  AND  datestatutcloturer is null
                AND  datestatutrefuser is null  and EXTRACT(YEAR from datestatutfini) >=?
                UNION
                SELECT  p.idprojet
                FROM projet p,creer c,utilisateur u,concerne co
                WHERE p.idprojet = co.idprojet_projet AND c.idprojet_projet = p.idprojet AND u.idutilisateur = c.idutilisateur_utilisateur
                AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=? AND trashed =FALSE
                AND  datestatutrefuser is null  and EXTRACT(YEAR from datestatutfini) >=?
                AND datedebutprojet is not null AND  datestatutfini is not null  AND  datestatutcloturer is not null
                UNION
                SELECT  p.idprojet
                FROM projet p,creer c,utilisateur u,concerne co
                WHERE p.idprojet = co.idprojet_projet AND c.idprojet_projet = p.idprojet AND u.idutilisateur = c.idutilisateur_utilisateur
                AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=? AND trashed =FALSE
                AND  datestatutfini is not null AND  datestatutcloturer is null AND EXTRACT(YEAR from datestatutfini) >=?
                UNION
                SELECT  p.idprojet
                FROM projet p,creer c,utilisateur u,concerne co
                WHERE p.idprojet = co.idprojet_projet AND c.idprojet_projet = p.idprojet AND u.idutilisateur = c.idutilisateur_utilisateur
                AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=? AND trashed =FALSE
                AND  datestatutcloturer is not null 
                UNION
                SELECT  p.idprojet
                FROM projet p,creer c,utilisateur u,concerne co
                WHERE p.idprojet = co.idprojet_projet AND c.idprojet_projet = p.idprojet AND u.idutilisateur = c.idutilisateur_utilisateur
                AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=? AND trashed =FALSE
                AND  datestatutrefuser is not null  ",array(
                    REFUSE,ACCEPTE,CLOTURE,
                    REFUSE,ACCEPTE,CLOTURE,$annee,
                    REFUSE,ACCEPTE,CLOTURE,$annee,
                    REFUSE,ACCEPTE,CLOTURE,$annee,
                    REFUSE,ACCEPTE,CLOTURE,
                    REFUSE,ACCEPTE,CLOTURE)); 
            
        }else{
       $arrayProjetEncours  = $manager->getListbyArray("SELECT p.idprojet
            FROM projet p,creer c,utilisateur u,concerne co
            WHERE p.idprojet = co.idprojet_projet AND c.idprojet_projet = p.idprojet AND u.idutilisateur = c.idutilisateur_utilisateur
            AND  datedebutprojet is not null AND  datestatutfini is null  AND  datestatutcloturer is null
            AND  datestatutrefuser is null AND EXTRACT(YEAR from datedebutprojet)<=?
            AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=? AND trashed =FALSE
            UNION
            SELECT  p.idprojet
            FROM projet p,creer c,utilisateur u,concerne co
            WHERE p.idprojet = co.idprojet_projet AND c.idprojet_projet = p.idprojet AND u.idutilisateur = c.idutilisateur_utilisateur
            AND  datedebutprojet is not null AND  datestatutfini is not null  AND  datestatutcloturer is null
            AND  datestatutrefuser is null AND EXTRACT(YEAR from datedebutprojet)<=? and EXTRACT(YEAR from datestatutfini) >=?
            AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=? AND trashed =FALSE
            UNION
            SELECT  p.idprojet
            FROM projet p,creer c,utilisateur u,concerne co
            WHERE p.idprojet = co.idprojet_projet AND c.idprojet_projet = p.idprojet AND u.idutilisateur = c.idutilisateur_utilisateur
            AND  datedebutprojet is not null AND  datestatutfini is not null  AND  datestatutcloturer is not null
            AND  datestatutrefuser is null AND EXTRACT(YEAR from datedebutprojet)<=? and EXTRACT(YEAR from datestatutfini) >=?
            AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=? AND trashed =FALSE
            UNION
            SELECT  p.idprojet
            FROM projet p,creer c,utilisateur u,concerne co
            WHERE p.idprojet = co.idprojet_projet AND c.idprojet_projet = p.idprojet AND u.idutilisateur = c.idutilisateur_utilisateur
            AND  datestatutfini is not null AND  datestatutcloturer is null AND
            EXTRACT(YEAR from datedebutprojet)<=? and EXTRACT(YEAR from datestatutfini) >=?
            AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=? AND trashed =FALSE
            UNION
            SELECT  p.idprojet
            FROM projet p,creer c,utilisateur u,concerne co
            WHERE p.idprojet = co.idprojet_projet AND c.idprojet_projet = p.idprojet AND u.idutilisateur = c.idutilisateur_utilisateur
            AND  datestatutcloturer is not null 
            AND EXTRACT(YEAR from datestatutcloturer)=?    AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=? AND trashed =FALSE
            UNION
            SELECT  p.idprojet
            FROM projet p,creer c,utilisateur u,concerne co
            WHERE p.idprojet = co.idprojet_projet AND c.idprojet_projet = p.idprojet AND u.idutilisateur = c.idutilisateur_utilisateur
            AND  datestatutrefuser is not null AND EXTRACT(YEAR from datestatutrefuser)=? AND trashed =FALSE
                AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=? ", 
               array(
                   $_POST['annee'],REFUSE,ACCEPTE,CLOTURE,
                   $_POST['annee'],$_POST['annee'],REFUSE,ACCEPTE,CLOTURE,
                   $_POST['annee'],$_POST['annee'],REFUSE,ACCEPTE,CLOTURE,
                   $_POST['annee'],$_POST['annee'],REFUSE,ACCEPTE,CLOTURE,
                   $_POST['annee'],REFUSE,ACCEPTE,CLOTURE,
                   $_POST['annee'],REFUSE,ACCEPTE,CLOTURE)); 
        }
    
} else {
    echo ' <script>alert("Vous devez choisir une année!");window.location.replace("/' . REPERTOIRE . '/enquete/' . $lang . '")</script>';
    exit();
}
$arrayUtilisateur = array();
foreach ($arrayProjetEncours as $idProjet){
    array_push($arrayUtilisateur, $manager->getSingle2("select idutilisateur from utilisateur, creer where idutilisateur_utilisateur=idutilisateur and idcentrale_centrale is null AND idprojet_projet=?",$idProjet['idprojet']));
}
// Création d'un tableau de d'idutilisateur académique externe et industriel qui sont porteur d'un projet qui est en cours de réalisation sur l'année sélectionnée
$arrayPorteur = array();
foreach ($arrayProjetEncours as $idProjet){
    array_push($arrayPorteur, $manager->getSingle2("select idutilisateur_utilisateur from utilisateurporteurprojet,utilisateur where idutilisateur_utilisateur=idutilisateur and idcentrale_centrale is null"
            . " and idprojet_projet=?",$idProjet['idprojet']));
}
$arrayIdUtilisateur = array_unique(array_merge(array_filter($arrayUtilisateur),array_filter($arrayPorteur)));
$nbrow = count($arrayIdUtilisateur);
$elementsUser =array();
foreach ($arrayIdUtilisateur as $idUser){   
     array_push($elementsUser,  $manager->getList2("select distinct on (mail) mail,idutilisateur,nomresponsable,mailresponsable,acronymelaboratoire,entrepriselaboratoire,nom,prenom from utilisateur,loginpassword "
             . "where idlogin =idlogin_loginpassword  and idutilisateur = ?",$idUser));
}

if ($nbrow != 0) {
// ENREGISTREMENT DES RESULTATS LIGNE PAR LIGNE
    for ($i = 0; $i < $nbrow; $i++) {       
            $nom = cleanForExportOther($elementsUser[$i][0]['nom']);
            $prenom = cleanForExportOther($elementsUser[$i][0]['prenom']);
            $email = $elementsUser[$i][0]['mail'];            
            if (!empty($elementsUser[$i][0]['nomresponsable'])) {
                $nomresponsable = cleanForExportOther($elementsUser[$i][0]['nomresponsable']);
            } else {
                $nomresponsable = '';
            }
            if (!empty($elementsUser[$i][0]['mailresponsable'])) {
                $mailresponsable = $elementsUser[$i][0]['mailresponsable'];
            } else {
                $mailresponsable = '';
            }
            if (!empty($elementsUser[$i][0]['acronymelaboratoire'])) {
                $acronymelaboratoire = cleanForExportOther($elementsUser[$i][0]['acronymelaboratoire']);
            } else {
                $acronymelaboratoire = '';
            }
            if (!empty($elementsUser[$i][0]['entrepriselaboratoire'])) {
                $entrepriselaboratoire = cleanForExportOther($elementsUser[$i][0]['entrepriselaboratoire']);
            } else {
                $entrepriselaboratoire = '';
            }
            $data .= "" .
                    $nom . ";" .
                    $prenom . ";" .
                    $email . ";" .
                    $nomresponsable . ";" .
                    $mailresponsable . ";" .
                    $acronymelaboratoire . ";" .
                    $entrepriselaboratoire . ";" . "\n";
        }
     
// Déclaration du type de contenu
    $originalDate = date('d-m-Y');
    header("Content-type: application/vnd.ms-excel;charset=UTF-8");
    header("Content-disposition: attachment; filename=export_" . $originalDate . ".csv");

    print $data;

    exit;
} else {
    echo ' <script>alert("' . TXT_PASDEDONNEE . '");window.location.replace("/' . REPERTOIRE . '/enquete/' . $lang . '")</script>';
    exit();
}

BD::deconnecter();
