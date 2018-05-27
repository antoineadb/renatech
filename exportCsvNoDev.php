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
    $pseudo = $_SESSION['pseudo'];
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}

$libellecentrale = $manager->getSingle2("SELECT libellecentrale FROM centrale , utilisateur ,loginpassword  WHERE idcentrale_centrale = idcentrale AND idlogin_loginpassword = idlogin AND pseudo=?", $pseudo);

$data = utf8_decode("Project;BTR Platform;Project leader /Entity / Town;Activities supported;Project number;Thematics");
$data .= "\n";
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


$nbarrayprojet = count($arrayNoDev);
$ressources = "";
if ($nbarrayprojet != 0) {
    for ($i = 0; $i < $nbarrayprojet; $i++) {
        $arrayRessources = $manager->getList2("SELECT  libelleressourceen FROM ressource,ressourceprojet WHERE idressource = idressource_ressource and idprojet_projet =?", $arrayNoDev[$i]['idprojet']);
        for ($j = 0; $j < count($arrayRessources); $j++) {
            $ressources.=$arrayRessources[$j]['libelleressourceen'] . ' / ';
        }
        $ressources = substr($ressources, 0, -2);
        //Entity
        $arrayEntity = $manager->getList2("SELECT codeunite,libelleautrecodeunite FROM utilisateur,centrale,autrecodeunite WHERE idcentrale_centrale = idcentrale AND idautrecodeunite = idautrecodeunite_autrecodeunite "
                . "and idutilisateur=?", $arrayNoDev[$i]['idutilisateur']);

        if ($arrayNoDev[$i]['libellethematiqueen'] != 'Others') {
            $thematic = $arrayNoDev[$i]['libellethematiqueen'];
        } else {
            $thematic = $manager->getSingle2("select libelleautrethematique from autrethematique where idautrethematique=?", $arrayNoDev[$i]['idautrethematique_autrethematique']);
        }
        if (isset($arrayEntity[0]['libelleautrecodeunite']) && $arrayEntity[0]['libelleautrecodeunite'] != 'n/a') {
            $libelleEntity = ' / ' . $arrayEntity[0]['libelleautrecodeunite'];
        } elseif (isset($arrayEntity[0]['codeunite']) && !empty($arrayEntity[0]['codeunite'])) {
            $libelleEntity = ' / ' . $arrayEntity[0]['codeunite'];
        } else {
            $libelleEntity = ' / ' . "No entity";
        }
        $originalDate = date('d-m-Y');
          $ville =$manager->getSingle2("SELECT ville FROM creer,utilisateur WHERE  idutilisateur_utilisateur = idutilisateur and idprojet_projet = ?", $arrayNoDev[$i]['idprojet']);

        $projetLeader = removeDoubleQuote(stripslashes(trim($arrayNoDev[$i]['nom']))) . ' - ' . removeDoubleQuote(stripslashes(trim($arrayNoDev[$i]['prenom']))) . ' - ' . removeDoubleQuote(stripslashes(trim($libelleEntity))) .
            ' / ' . $ville;
        
        $data .= "" .
                removeDoubleQuote(stripslashes(utf8_decode(trim($arrayNoDev[$i]['titre'])))) . ";" .
                $libellecentrale . ";" .
                removeDoubleQuote(stripslashes(utf8_decode(trim($projetLeader)))) . ";" .
                $ressources . ";" .
                $arrayNoDev[$i]['numero'] . ";" .
                 removeDoubleQuote(stripslashes(utf8_decode(trim($thematic)))) . "\n";
        $ressources = "";
    }

    $libcentrale = $manager->getSingle2("SELECT libellecentrale FROM loginpassword,centrale,utilisateur WHERE idlogin_loginpassword = idlogin AND idcentrale_centrale = idcentrale AND pseudo=?", $pseudo);
// Déclaration du type de contenu    
    header("Content-type: application/vnd.ms-excel;charset=UTF-8");
    header("Content-disposition: attachment; filename=exportprojetsansDev_" . $libcentrale . '_' . $originalDate . ".csv");
    print $data;
    exit;
} else {
    echo ' <script>alert("' . utf8_decode(TXT_PASDEPROJET) . '");window.location.replace("/' . REPERTOIRE . '/noDevProject/' . $lang . '")</script>';
    exit();
}
BD::deconnecter();
