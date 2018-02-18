<?php

session_start();
include_once '../class/Manager.php';
include_once '../outils/toolBox.php';
include_once '../outils/constantes.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
$filename = './templateprojetNodev.html';
$filename2 = './templateTitre.html';
$arraycentrale = $manager->getList2("SELECT libellecentrale,idcentrale FROM centrale,loginpassword,utilisateur WHERE idlogin = idlogin_loginpassword AND  idcentrale_centrale = idcentrale AND pseudo=?", $_SESSION['pseudo']);
if (isset($_GET['annee']) && !empty($_GET['annee']) && $_GET['annee'] != -1) {
    $annee = $_GET['annee'];
} elseif (isset($_GET['annee']) && !empty($_GET['annee']) && $_GET['annee'] == -1) {
    $annee = "ALL";
}
header("Content-Type:application/vnd.openxmlformats-officedocument.wordprocessingml.document");
header('Content-Disposition: attachment; filename=rapport_' . time() . '_' . $arraycentrale[0]['libellecentrale'] . '.doc');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

$sql="SELECT distinct idprojet,p.idautrethematique_autrethematique,titre,libellethematiqueen,libellethematique,prenom,idutilisateur,nom,numero   FROM projet p
    LEFT JOIN thematique on idthematique = p.idthematique_thematique  LEFT JOIN creer cr on cr.idprojet_projet = p.idprojet  LEFT JOIN utilisateur on idutilisateur_utilisateur = idutilisateur 
    LEFT JOIN concerne co on co.idprojet_projet = p.idprojet WHERE co.idcentrale_centrale =?  AND trashed =FALSE and co.idstatutprojet_statutprojet in(?,?,?)  AND p.idprojet not in (select idprojet from rapport)";
if(isset($_GET['annee']) && ($_GET['annee'])!=-1){
    $dateChx = (int) $_GET['annee'];
    $arrayNoDev =$manager->getListbyArray("
                SELECT distinct idprojet,p.idautrethematique_autrethematique,titre,libellethematiqueen,libellethematique,prenom,idutilisateur,nom,numero
                FROM projet p 
                LEFT JOIN thematique  on idthematique =idthematique_thematique
                LEFT JOIN concerne co  on p.idprojet = co.idprojet_projet
                LEFT JOIN creer c  on p.idprojet = c.idprojet_projet
                LEFT JOIN utilisateur u on u.idutilisateur = c.idutilisateur_utilisateur 
                WHERE  
                co.idcentrale_centrale =? AND  datedebutprojet is not null AND  datestatutfini is null  AND  datestatutcloturer is null
                AND  datestatutrefuser is null AND EXTRACT(YEAR from datedebutprojet)<=?
                AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=? AND trashed =FALSE
                AND p.idprojet not in (select idprojet from rapport)
                UNION
                SELECT distinct idprojet,p.idautrethematique_autrethematique,titre,libellethematiqueen,libellethematique,prenom,idutilisateur,nom,numero
                FROM projet p
                LEFT JOIN thematique  on idthematique =idthematique_thematique
                LEFT JOIN concerne co  on p.idprojet = co.idprojet_projet
                LEFT JOIN creer c  on p.idprojet = c.idprojet_projet
                LEFT JOIN utilisateur u on u.idutilisateur = c.idutilisateur_utilisateur 
                WHERE 
                co.idcentrale_centrale =? AND  datedebutprojet is not null AND  datestatutfini is not null  AND  datestatutcloturer is null
                AND  datestatutrefuser is null AND EXTRACT(YEAR from datedebutprojet)<=? and EXTRACT(YEAR from datestatutfini) >=?
                AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=? AND trashed =FALSE
                AND p.idprojet not in (select idprojet from rapport)
                 UNION
                SELECT distinct idprojet,p.idautrethematique_autrethematique,titre,libellethematiqueen,libellethematique,prenom,idutilisateur,nom,numero
                FROM projet p
                LEFT JOIN thematique  on idthematique =idthematique_thematique
                LEFT JOIN concerne co  on p.idprojet = co.idprojet_projet
                LEFT JOIN creer c  on p.idprojet = c.idprojet_projet
                LEFT JOIN utilisateur u on u.idutilisateur = c.idutilisateur_utilisateur 
                WHERE co.idcentrale_centrale =? AND  datedebutprojet is not null AND  datestatutfini is not null  AND  datestatutcloturer is not null
                AND  datestatutrefuser is null AND EXTRACT(YEAR from datedebutprojet)<=? and EXTRACT(YEAR from datestatutfini) >=?
                AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=? AND trashed =FALSE
                AND p.idprojet not in (select idprojet from rapport)
                 UNION                
                SELECT distinct idprojet,p.idautrethematique_autrethematique,titre,libellethematiqueen,libellethematique,prenom,idutilisateur,nom,numero
                FROM projet p
                LEFT JOIN thematique  on idthematique =idthematique_thematique
                LEFT JOIN concerne co  on p.idprojet = co.idprojet_projet
                LEFT JOIN creer c  on p.idprojet = c.idprojet_projet
                LEFT JOIN utilisateur u on u.idutilisateur = c.idutilisateur_utilisateur 
                WHERE co.idcentrale_centrale =? AND  datestatutfini is not null AND  datestatutcloturer is null 
                AND EXTRACT(YEAR from datedebutprojet)<=? and EXTRACT(YEAR from datestatutfini) >=?
                AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=? AND trashed =FALSE
                AND p.idprojet not in (select idprojet from rapport)
                UNION
                SELECT distinct idprojet,p.idautrethematique_autrethematique,titre,libellethematiqueen,libellethematique,prenom,idutilisateur,nom,numero
                FROM projet p
                LEFT JOIN thematique  on idthematique =idthematique_thematique
                LEFT JOIN concerne co  on p.idprojet = co.idprojet_projet
                LEFT JOIN creer c  on p.idprojet = c.idprojet_projet
                LEFT JOIN utilisateur u on u.idutilisateur = c.idutilisateur_utilisateur 
                WHERE co.idcentrale_centrale =? AND  datestatutcloturer is not null
                AND EXTRACT(YEAR from datestatutcloturer)=?
                AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=? AND trashed =FALSE
                AND p.idprojet not in (select idprojet from rapport)
                UNION                
                SELECT distinct idprojet,p.idautrethematique_autrethematique,titre,libellethematiqueen,libellethematique,prenom,idutilisateur,nom,numero
                FROM projet p
                LEFT JOIN thematique  on idthematique =idthematique_thematique
                LEFT JOIN concerne co  on p.idprojet = co.idprojet_projet
                LEFT JOIN creer c  on p.idprojet = c.idprojet_projet
                LEFT JOIN utilisateur u on u.idutilisateur = c.idutilisateur_utilisateur 
                WHERE co.idcentrale_centrale =? AND  datestatutrefuser is not null
                AND EXTRACT(YEAR from datestatutrefuser)=? AND trashed =FALSE
                AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=? 
                AND p.idprojet not in (select idprojet from rapport)
                        order by idprojet  asc",
                array(IDCENTRALEUSER,$dateChx,REFUSE,ACCEPTE,CLOTURE,
                    IDCENTRALEUSER,$dateChx,$dateChx,REFUSE,ACCEPTE,CLOTURE, 
                    IDCENTRALEUSER,$dateChx,$dateChx,REFUSE,ACCEPTE,CLOTURE,
                    IDCENTRALEUSER,$dateChx,$dateChx,REFUSE,ACCEPTE,CLOTURE,
                    IDCENTRALEUSER,$dateChx,REFUSE,ACCEPTE,CLOTURE,
                    IDCENTRALEUSER,$dateChx,REFUSE,ACCEPTE,CLOTURE));   
    
}elseif(isset($_GET['annee']) && ($_GET['annee'])==-1){
    $arrayNoDev =$manager->getListbyArray("SELECT distinct idprojet,p.idautrethematique_autrethematique,titre,libellethematiqueen,libellethematique,prenom,idutilisateur,nom,numero
                FROM projet p 
                LEFT JOIN thematique  on idthematique =idthematique_thematique
                LEFT JOIN concerne co  on p.idprojet = co.idprojet_projet
                LEFT JOIN creer c  on p.idprojet = c.idprojet_projet
                LEFT JOIN utilisateur u on u.idutilisateur = c.idutilisateur_utilisateur 
                WHERE  
                co.idcentrale_centrale =? AND  datedebutprojet is not null AND  datestatutfini is null  AND  datestatutcloturer is null
                AND  datestatutrefuser is null
                AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=? AND trashed =FALSE
                AND p.idprojet not in (select idprojet from rapport)
                UNION
                SELECT distinct idprojet,p.idautrethematique_autrethematique,titre,libellethematiqueen,libellethematique,prenom,idutilisateur,nom,numero
                FROM projet p
                LEFT JOIN thematique  on idthematique =idthematique_thematique
                LEFT JOIN concerne co  on p.idprojet = co.idprojet_projet
                LEFT JOIN creer c  on p.idprojet = c.idprojet_projet
                LEFT JOIN utilisateur u on u.idutilisateur = c.idutilisateur_utilisateur 
                WHERE  co.idcentrale_centrale =? AND  datedebutprojet is not null AND  datestatutfini is not null  AND  datestatutcloturer is null
                AND  datestatutrefuser is null 
                AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=? AND trashed =FALSE
                AND p.idprojet not in (select idprojet from rapport)
                 UNION
                SELECT distinct idprojet,p.idautrethematique_autrethematique,titre,libellethematiqueen,libellethematique,prenom,idutilisateur,nom,numero
                FROM projet p
                LEFT JOIN thematique  on idthematique =idthematique_thematique
                LEFT JOIN concerne co  on p.idprojet = co.idprojet_projet
                LEFT JOIN creer c  on p.idprojet = c.idprojet_projet
                LEFT JOIN utilisateur u on u.idutilisateur = c.idutilisateur_utilisateur 
                WHERE co.idcentrale_centrale =? AND  datedebutprojet is not null AND  datestatutfini is not null  AND  datestatutcloturer is not null
                AND  datestatutrefuser is null 
                AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=? AND trashed =FALSE
                AND p.idprojet not in (select idprojet from rapport)
                 UNION                
                SELECT distinct idprojet,p.idautrethematique_autrethematique,titre,libellethematiqueen,libellethematique,prenom,idutilisateur,nom,numero
                FROM projet p
                LEFT JOIN thematique  on idthematique =idthematique_thematique
                LEFT JOIN concerne co  on p.idprojet = co.idprojet_projet
                LEFT JOIN creer c  on p.idprojet = c.idprojet_projet
                LEFT JOIN utilisateur u on u.idutilisateur = c.idutilisateur_utilisateur 
                WHERE co.idcentrale_centrale =? AND  datestatutfini is not null AND  datestatutcloturer is null 
                AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=? AND trashed =FALSE
                AND p.idprojet not in (select idprojet from rapport)
                UNION
                SELECT distinct idprojet,p.idautrethematique_autrethematique,titre,libellethematiqueen,libellethematique,prenom,idutilisateur,nom,numero
                FROM projet p
                LEFT JOIN thematique  on idthematique =idthematique_thematique
                LEFT JOIN concerne co  on p.idprojet = co.idprojet_projet
                LEFT JOIN creer c  on p.idprojet = c.idprojet_projet
                LEFT JOIN utilisateur u on u.idutilisateur = c.idutilisateur_utilisateur 
                WHERE co.idcentrale_centrale =? AND  datestatutcloturer is not null

                AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=? AND trashed =FALSE
                AND p.idprojet not in (select idprojet from rapport)
                UNION                
                SELECT distinct idprojet,p.idautrethematique_autrethematique,titre,libellethematiqueen,libellethematique,prenom,idutilisateur,nom,numero
                FROM projet p
                LEFT JOIN thematique  on idthematique =idthematique_thematique
                LEFT JOIN concerne co  on p.idprojet = co.idprojet_projet
                LEFT JOIN creer c  on p.idprojet = c.idprojet_projet
                LEFT JOIN utilisateur u on u.idutilisateur = c.idutilisateur_utilisateur 
                WHERE co.idcentrale_centrale =? AND  datestatutrefuser is not null
                 AND trashed =FALSE
                AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=? AND idstatutprojet_statutprojet !=? 
                AND p.idprojet not in (select idprojet from rapport)
		order by idprojet  asc",array(
                    IDCENTRALEUSER,REFUSE,ACCEPTE,CLOTURE,
                    IDCENTRALEUSER,REFUSE,ACCEPTE,CLOTURE,
                    IDCENTRALEUSER,REFUSE,ACCEPTE,CLOTURE,
                    IDCENTRALEUSER,REFUSE,ACCEPTE,CLOTURE,
                    IDCENTRALEUSER,REFUSE,ACCEPTE,CLOTURE,
                    IDCENTRALEUSER,REFUSE,ACCEPTE,CLOTURE));
}


$nbarraynoDEV = count($arrayNoDev);
if (!file_exists($filename2)) {
    echo "Undefined file";
    exit();
}
if (!empty($arraycentrale[0]['libellecentrale'])) {
    $btrPlateforme = $arraycentrale[0]['libellecentrale'];
}
//On ouvre le modele
$fp = fopen($filename2, 'r');
$content2 = fread($fp, filesize($filename2));
fclose($fp);

$content2 = str_replace('##ANNEE##', $annee, $content2);

echo utf8_decode($content2);

for ($i = 0; $i < $nbarraynoDEV; $i++) {
    if (!file_exists($filename)) {
        echo "Undefined file";
        exit();
    }
//On ouvre le modele
    $fp = fopen($filename, 'r');
    $content = fread($fp, filesize($filename));
    fclose($fp);

    if (!empty($arrayNoDev[$i]['titre'])) {
        $titre = $arrayNoDev[$i]['titre'];
    }

    $arrayEntity = $manager->getList2("SELECT codeunite,libelleautrecodeunite FROM utilisateur,centrale,autrecodeunite WHERE idcentrale_centrale = idcentrale AND idautrecodeunite = idautrecodeunite_autrecodeunite "
            . "and idutilisateur=?", $arrayNoDev[$i]['idutilisateur']);
    if (isset($arrayEntity[0]['libelleautrecodeunite']) && $arrayEntity[0]['libelleautrecodeunite'] != 'n/a') {
        $libelleEntity = $arrayEntity[0]['libelleautrecodeunite'];
    } elseif (isset($arrayEntity[0]['codeunite']) && !empty($arrayEntity[0]['codeunite'])) {
        $libelleEntity = $arrayEntity[0]['codeunite'];
    } else {
        $libelleEntity = "No entity";
    }
    $ville =$manager->getSingle2("SELECT ville FROM creer,utilisateur WHERE  idutilisateur_utilisateur = idutilisateur and idprojet_projet = ?", $arrayNoDev[$i]['idprojet']);

    $projetLeader = removeDoubleQuote(stripslashes(trim($arrayNoDev[$i]['nom']))) . ' - ' . removeDoubleQuote(stripslashes(trim($arrayNoDev[$i]['prenom']))) . ' - ' . removeDoubleQuote(stripslashes(trim($libelleEntity))) .
            ' / ' . removeDoubleQuote(stripslashes(trim($ville)));
    

    $arrayRessources = $manager->getList2("SELECT  libelleressourceen FROM ressource,ressourceprojet WHERE idressource = idressource_ressource and idprojet_projet =?", $arrayNoDev[$i]['idprojet']);
    $activiteSupported = "";
    for ($j = 0; $j < count($arrayRessources); $j++) {
        $activiteSupported.=$arrayRessources[$j]['libelleressourceen'] . ' / ';
    }

    $activiteSupported = substr($activiteSupported, 0, -2);


    if ($arrayNoDev[$i]['libellethematiqueen'] != 'Others') {
        $thematic = $arrayNoDev[$i]['libellethematiqueen'];
    } else {
        $thematic = $manager->getSingle2("select libelleautrethematique from autrethematique where idautrethematique=?", $arrayNoDev[$i]['idautrethematique_autrethematique']);
    }



    $content = str_replace('##Project##', $titre, $content);
    $content = str_replace('##BTRPlateforme##', $btrPlateforme, $content);
    $content = str_replace('##ProjetLeader##', $projetLeader, $content);
    $content = str_replace('##ActiviteSupported##', $activiteSupported, $content);
    $content = str_replace('##Thematic##', trim($thematic), $content);

    for ($j = 10; $j < $nbarraynoDEV; $j = $j + 10) {
        switch ($i) {
            case $j:
                echo utf8_decode($content2);
                break;
        }
    }
    echo utf8_decode($content);
}
