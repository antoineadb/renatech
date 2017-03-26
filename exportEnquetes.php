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
//Récupération de l'idcentrale de l'utilisateur


if (!empty($_POST['annee'])) {
    $anneeExport =$_POST['annee'];
    if($anneeExport==1){
       //$andreq1 = '';
       $andreq2 = '';
    }else{
        //$andreq1 = ' AND EXTRACT(YEAR from dateaffectation )  = ? ';
        $andreq2 = ' AND EXTRACT(YEAR from p.dateprojet ) = ? ';
        //$param = array($anneeExport,$anneeExport,$anneeExport, $anneeExport,$anneeExport);
        $param = array($anneeExport,$anneeExport);
    }
} else {
    $anneeExport = date('Y'); //Année du jour si vide
    //$andreq1 = ' AND EXTRACT(YEAR from dateaffectation )  = ? ';
    $andreq2 = ' AND EXTRACT(YEAR from p.dateprojet ) = ? ';
    //$param = array($anneeExport,$anneeExport,$anneeExport, $anneeExport,$anneeExport);
    //$param = array($anneeExport,$anneeExport,$anneeExport, $anneeExport,$anneeExport);
    $param = array($anneeExport,$anneeExport);
}

//RECUPERATION DE L'IDUTILISATEUR EN FONCTION DU PSEUDO


$manager->exeRequete("drop  table if exists tmpenquete");
$sql="create table tmpenquete as(
      SELECT distinct u.idutilisateur, u.nomresponsable,u.mailresponsable, u.acronymelaboratoire, u.entrepriselaboratoire, u.nom,u.prenom,l.mail
      FROM utilisateur u,projet p,loginpassword l,qualitedemandeurindust q, creer c
      WHERE c.idprojet_projet= p.idprojet and u.idutilisateur=c.idutilisateur_utilisateur
      AND q.idqualitedemandeurindust = u.idqualitedemandeurindust_qualitedemandeurindust 
      AND l.idlogin = u.idlogin_loginpassword
      ".$andreq2."
      union      
      SELECT distinct u.idutilisateur, u.nomresponsable,u.mailresponsable, u.acronymelaboratoire, u.entrepriselaboratoire, u.nom,u.prenom,l.mail
      FROM utilisateur u,projet p,loginpassword l,qualitedemandeuraca q, creer c
      WHERE c.idprojet_projet= p.idprojet and u.idutilisateur=c.idutilisateur_utilisateur
      AND q.idqualitedemandeuraca = u.idqualitedemandeuraca_qualitedemandeuraca
      and q.idqualitedemandeuraca=3
      AND l.idlogin = u.idlogin_loginpassword
       ".$andreq2."
       )
	order by idutilisateur";

/*

$sql = "create table tmpenquete as(
       SELECT distinct on (u.idutilisateur) u.idutilisateur, u.nomresponsable,u.mailresponsable, u.acronymelaboratoire,u.entrepriselaboratoire,u.nom,u.prenom,l.mail
	FROM utilisateur u,utilisateurporteurprojet up,loginpassword l,qualitedemandeuraca q 
	WHERE up.idutilisateur_utilisateur = u.idutilisateur 
	AND  l.idlogin = u.idlogin_loginpassword 
	".$andreq1."
	AND q.idqualitedemandeuraca = u.idqualitedemandeuraca_qualitedemandeuraca 
	and u.idcentrale_centrale is null
        union						
	SELECT distinct on (u.idutilisateur) u.idutilisateur, u.nomresponsable,u.mailresponsable, u.acronymelaboratoire, u.entrepriselaboratoire, u.nom,u.prenom,l.mail
	FROM utilisateur u,utilisateurporteurprojet up,loginpassword l,	qualitedemandeurindust q
	WHERE up.idutilisateur_utilisateur = u.idutilisateur 
	AND l.idlogin = u.idlogin_loginpassword
	".$andreq1."
	AND q.idqualitedemandeurindust = u.idqualitedemandeurindust_qualitedemandeurindust
        union
	SELECT distinct on (u.idutilisateur) u.idutilisateur, u.nomresponsable,u.mailresponsable, u.acronymelaboratoire, u.entrepriselaboratoire, u.nom,u.prenom,l.mail
	FROM creer c,utilisateur u,loginpassword l,qualitedemandeuraca q,projet p
	WHERE u.idutilisateur = c.idutilisateur_utilisateur 
	AND u.idqualitedemandeuraca_qualitedemandeuraca = q.idqualitedemandeuraca 
	".$andreq2."
	AND l.idlogin = u.idlogin_loginpassword 
	AND p.idprojet = c.idprojet_projet
	and u.idcentrale_centrale is null
        union
	SELECT distinct on (u.idutilisateur) u.idutilisateur, u.nomresponsable,u.mailresponsable, u.acronymelaboratoire, u.entrepriselaboratoire,   u.nom, u.prenom, l.mail
	FROM creer c,utilisateur u,loginpassword l,qualitedemandeurindust q,projet p
	WHERE u.idutilisateur = c.idutilisateur_utilisateur 
	".$andreq2."
	AND p.idprojet = c.idprojet_projet
	AND l.idlogin = u.idlogin_loginpassword 
	AND q.idqualitedemandeurindust = u.idqualitedemandeurindust_qualitedemandeurindust
        union
        SELECT distinct on (u.idutilisateur) u.idutilisateur, u.nomresponsable,u.mailresponsable, u.acronymelaboratoire, u.entrepriselaboratoire, u.nom,u.prenom,l.mail
	FROM utilisateur u,loginpassword l,utilisateuradministrateur ua,qualitedemandeuraca q
	WHERE l.idlogin = u.idlogin_loginpassword 
	".$andreq1."
	AND ua.idutilisateur = u.idutilisateur 
	AND q.idqualitedemandeuraca = u.idqualitedemandeuraca_qualitedemandeuraca 
	and u.idcentrale_centrale is null	
)
	order by idutilisateur";
 * 
 */
if($anneeExport==1){
    $manager->exeRequete($sql);
}else{
    $manager->getRequete($sql, $param);
}
$row = $manager->getList("select * from tmpenquete");
$nbrow = count($row);

if ($nbrow != 0) {
// ENREGISTREMENT DES RESULTATS LIGNE PAR LIGNE
    for ($i = 0; $i < $nbrow; $i++) {       
            $nom = cleanForExportOther($row[$i]['nom']);
            $prenom = cleanForExportOther($row[$i]['prenom']);
            $email = $row[$i]['mail'];            
            if (!empty($row[$i]['nomresponsable'])) {
                $nomresponsable = cleanForExportOther($row[$i]['nomresponsable']);
            } else {
                $nomresponsable = '';
            }
            if (!empty($row[$i]['mailresponsable'])) {
                $mailresponsable = $row[$i]['mailresponsable'];
            } else {
                $mailresponsable = '';
            }
            if (!empty($row[$i]['acronymelaboratoire'])) {
                $acronymelaboratoire = cleanForExportOther($row[$i]['acronymelaboratoire']);
            } else {
                $acronymelaboratoire = '';
            }
            if (!empty($row[$i]['entrepriselaboratoire'])) {
                $entrepriselaboratoire = cleanForExportOther($row[$i]['entrepriselaboratoire']);
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
