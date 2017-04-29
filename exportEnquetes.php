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
        $arrayProjetEncours = $manager->getListbyArray(" 
        select  distinct idprojet from concerne,projet where idprojet_projet=idprojet and idstatutprojet_statutprojet =? and EXTRACT(YEAR from datedebutprojet ) >?
         union 
        select  distinct idprojet from concerne,projet where idprojet_projet=idprojet and idstatutprojet_statutprojet =? and EXTRACT(YEAR from datestatutfini ) >?
        order by idprojet asc;",array(ENCOURSREALISATION,$_POST['annee'],FINI,$_POST['annee']));
    }else{       
       $arrayProjetEncours = $manager->getListbyArray("
           select  distinct idprojet from concerne,projet where idprojet_projet=idprojet and idstatutprojet_statutprojet =? and EXTRACT(YEAR from datedebutprojet ) =?   union 
           select  distinct idprojet from concerne,projet where idprojet_projet=idprojet and idstatutprojet_statutprojet =? and EXTRACT(YEAR from datestatutfini ) =? union
           select  distinct idprojet from concerne,projet where idprojet_projet=idprojet and EXTRACT(YEAR from datedebutprojet ) <? and EXTRACT(YEAR from datestatutfini ) >?
            order by idprojet asc;",array(ENCOURSREALISATION,$_POST['annee'],FINI,$_POST['annee'],$_POST['annee'],$_POST['annee']));       
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
     array_push($elementsUser,  $manager->getList2("select idutilisateur,nomresponsable,mailresponsable,acronymelaboratoire,entrepriselaboratoire,nom,prenom,mail from utilisateur,loginpassword "
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
