<?php
include_once 'class/Manager.php';
include_once 'outils/constantes.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
if (isset($_GET['numProjet'])) {
    $numProjet = $_GET['numProjet'];
    $idprojet = $manager->getSingle2("select idprojet from projet where numero=?", $numProjet);
} elseif (isset($_GET['idprojet'])) {
    $idprojet=$_GET['idprojet'];
    $numProjet = $manager->getSingle2("select numero from projet where idprojet=?", $idprojet);
}
if (isset($_SESSION['pseudo'])) {
    $idtypeutilisateur = $manager->getSingle2("SELECT idtypeutilisateur_typeutilisateur FROM loginpassword, utilisateur WHERE idlogin = idlogin_loginpassword AND  pseudo =?", $_SESSION['pseudo']);
    $idlocal = $manager->getSingle2("SELECT idutilisateur FROM loginpassword, utilisateur WHERE idlogin = idlogin_loginpassword AND  pseudo =?", $_SESSION['pseudo']);
}

if (!empty($idprojet)) {
    $idprojet = $manager->getSingle2("select idprojet from projet where numero=?", $numProjet);
    $row = $manager->getList2("SELECT acronyme,nbrun,nbplaque,nbheure,nbeleve,contactscentraleaccueil,typeformation,dureeprojet,datedebuttravaux,descriptionautrecentrale,descriptioncentraleproximite,etapeautrecentrale
    ,idperiodicite_periodicite,idthematique_thematique,dureeestime,periodestime
    ,idautrethematique_autrethematique,idtypeprojet_typeprojet,descriptiftechnologique,nomformateur,emailrespdevis,reussite
    ,verrouidentifiee,centralepartenaireprojet,acrosourcefinancement,partenaire1 FROM projet where idprojet =?", $idprojet);

    $idtypena = $manager->getSingle2("select idtypeprojet from typeprojet where libelletype=?", 'n/a');
    if (!empty($row[0]['idtypeprojet_typeprojet']) && $row[0]['idtypeprojet_typeprojet'] != $idtypena) {
        $libelletype = $manager->getSingle2("select libelletype from typeprojet where idtypeprojet=?", $row[0]['idtypeprojet_typeprojet']);
        $libelletypeen = $manager->getSingle2("select libelletypeen from typeprojet where idtypeprojet=?", $row[0]['idtypeprojet_typeprojet']);
    } else {
        $libelletype = '';
    }
    //TYPE FORMATION
    $idtypeformationna = $manager->getSingle2("select idtypeformation from typeformation where libelletypeformation = ?", 'n/a');
    if ($row[0]['idtypeprojet_typeprojet'] == FORMATION) {
        $idtypeformation = $manager->getSingle2("select idtypeformation from projettypeprojet where idprojet=?", $idprojet);
        if ($lang == 'fr') {
            $libelletypeformation = $manager->getSingle2("select libelletypeformation from typeformation where idtypeformation =?", $idtypeformation);
        } elseif ($lang == 'en') {
            $libelletypeformation = $manager->getSingle2("select libelletypeformationen from typeformation where idtypeformation=?", $idtypeformation);
        }
    }
    if (!empty($row[0]['acronyme'])) {
        $acronyme = $row[0]['acronyme'];
    } else {
        $acronyme = '';
    }
    if (!empty($row[0]['contactscentraleaccueil'])) {
        $contactscentraleaccueil = $row[0]['contactscentraleaccueil'];
    } else {
        $contactscentraleaccueil = '';
    }
    if (!empty($row[0]['typeformation'])) {
        $typeformation = $row[0]['typeformation'];
    } else {
        $typeformation = '';
    }
    if (!empty($row[0]['datedebuttravaux'])) {
        $datedebuttravaux = $row[0]['datedebuttravaux'];
    } else {
        $datedebuttravaux = date('Y-m-d');
    }
    if (!empty($row[0]['dureeprojet'])) {
        $dureeprojet = $row[0]['dureeprojet'];
    } else {
        $dureeprojet = '';
    }
    if (!empty($row[0]['idperiodicite_periodicite'])) {
        $libelleperiodicite = $manager->getSingle2("select libelleperiodicite from period where idperiodicite=?", $row[0]['idperiodicite_periodicite']);
        $libelleperiodiciteen = $manager->getSingle2("select libelleperiodiciteen from period where idperiodicite=?", $row[0]['idperiodicite_periodicite']);
    } else {
        $libelleperiodicite = '';
        $libelleperiodiciteen = '';
    }
    if (!empty($row[0]['dureeestime'])) {
        $dureeestime = $row[0]['dureeestime'];
    } else {
        $dureeestime = '';
    }
    if (!empty($row[0]['periodestime'])) {
        $libelleperiodestime = $manager->getSingle2("select libelleperiodicite from period where idperiodicite=?", $row[0]['periodestime']);
        $libelleperiodestimeen = $manager->getSingle2("select libelleperiodiciteen from period where idperiodicite=?", $row[0]['periodestime']);
    } else {
        $libelleperiodestime = '';
        $libelleperiodestimeen = '';
    }
    if (!empty($row[0]['idthematique_thematique'])) {
        $libellethematique = $manager->getSingle2("select libellethematique from thematique where idthematique=?", $row[0]['idthematique_thematique']);
        $libellethematiqueen = $manager->getSingle2("select libellethematiqueen from thematique where idthematique=?", $row[0]['idthematique_thematique']);
    } else {
        $libellethematique = '';
        $libellethematiqueen = '';
    }
    if (!empty($row[0]['idautrethematique_autrethematique'])) {
        $libelleautrethematique = $manager->getSingle2("select libelleautrethematique from autrethematique where idautrethematique=? ", $row[0]['idautrethematique_autrethematique']);
    } else {
        $libelleautrethematique = '';
    }

    if (!empty($row[0]['descriptiftechnologique'])) {
        $descriptiftechnologique = stripslashes(str_replace("''", "'", $row[0]['descriptiftechnologique']));
    } else {
        $descriptiftechnologique = '';
    }
    if (!empty($row[0]['emailrespdevis'])) {
        $emailrespdevis = $row[0]['emailrespdevis'];
    }
    if (!empty($row[0]['verrouidentifiee'])) {
        $verrouidentifiee = stripslashes(str_replace("''", "'", $row[0]['verrouidentifiee']));
    } else {
        $verrouidentifiee = '';
    }

    if (!empty($row[0]['descriptionautrecentrale'])) {
        $descriptionautrecentrale = stripslashes(str_replace("''", "'", $row[0]['descriptionautrecentrale']));
    } else {
        $descriptionautrecentrale = '';
    }

    if (!empty($row[0]['descriptioncentraleproximite'])) {
        $descriptioncentraleproximite = stripslashes(str_replace("''", "'", $row[0]['descriptioncentraleproximite']));
    } else {
        $descriptioncentraleproximite = '';
    }

    if (!empty($row[0]['reussite'])) {
        $reussite = stripslashes(str_replace("''", "'", $row[0]['reussite']));
    } else {
        $reussite = '';
    }
    $refinterneprojet = $manager->getSingle2("select refinterneprojet from projet where idprojet=?", $idprojet);
    if (!empty($row[0]['nbplaque'])) {
        $nbplaque = $row[0]['nbplaque'];
    } else {
        $nbplaque = '';
    }
    if (!empty($row[0]['nbrun'])) {
        $nbrun = $row[0]['nbrun'];
    } else {
        $nbrun = '';
    }
    if (!empty($row[0]['nbheure'])) {
        $nbheure = $row[0]['nbheure'];
    } else {
        $nbheure = '';
    }
    if (!empty($row[0]['nbeleve'])) {
        $nbeleve = $row[0]['nbeleve'];
    } else {
        $nbeleve = '';
    }
    if (!empty($row[0]['centralepartenaireprojet'])) {
        $centralepartenaireprojet = str_replace("''", "'", $row[0]['centralepartenaireprojet']);
    } else {
        $centralepartenaireprojet = '';
    }
    if (!empty($row[0]['nomformateur'])) {
        $nomformateur = $row[0]['nomformateur'];
    } else {
        $nomformateur = '';
    }
    if (!empty($row[0]['acrosourcefinancement'])) {
        $acrosourcefinancement = $row[0]['acrosourcefinancement'];
    } else {
        $acrosourcefinancement = '';
    }
    if (!empty($row[0]['partenaire1'])) {
        $partenaire1 = str_replace("''", "'", $row[0]['partenaire1']);
    } else {
        $partenaire1 = '';
    }
    $porteurprojet = $manager->getSingle2("select porteurprojet from projet where idprojet=?", $idprojet);
    if (!empty($_GET['statut'])) {
        $idstatutprojet = $_GET['statut'];
    } else {
        $idstatutprojet = $manager->getSingle2("select idstatutprojet_statutprojet from concerne where idprojet_projet=?", $idprojet);
    }
    
    $idcentraleconnecte = $manager->getSingle2("select idcentrale_centrale from utilisateur,loginpassword where
    idlogin=idlogin_loginpassword and pseudo=?", $_SESSION['pseudo']);
    $idcentraleaffectationaccepte = $manager->getSinglebyArray("select idcentrale_centrale from concerne,projet where idprojet=idprojet_projet
    and idstatutprojet_statutprojet=? and idprojet_projet=?", array(ACCEPTE, $idprojet));
    $idcentraleaffectationfini = $manager->getSinglebyArray("select idcentrale_centrale from concerne,projet where idprojet=idprojet_projet
    and idstatutprojet_statutprojet=? and idprojet_projet=?", array(FINI, $idprojet));
    $idcentraleaffectationcloture = $manager->getSinglebyArray("select idcentrale_centrale from concerne,projet where idprojet=idprojet_projet
    and idstatutprojet_statutprojet=? and idprojet_projet=? ", array(CLOTURE, $idprojet));
    $idcentraleaffectationencours = $manager->getSinglebyArray("select idcentrale_centrale from concerne,projet where idprojet=idprojet_projet
    and idstatutprojet_statutprojet=? and idprojet_projet=? ", array(ENCOURSREALISATION, $idprojet));

    if (!empty($idcentraleaffectationaccepte) && $idcentraleconnecte != $idcentraleaffectationaccepte) {
        $bool = 'false';
        $bool1 = '';
    } elseif (!empty($idcentraleaffectationfini) && $idcentraleconnecte != $idcentraleaffectationfini) {
        $bool = 'true';
        $bool1 = 'true';
    } elseif (!empty($idcentraleaffectationcloture) && $idcentraleconnecte != $idcentraleaffectationcloture) {
        $bool = 'true';
        $bool1 = 'true';
    } elseif (!empty($idcentraleaffectationencours) && $idcentraleconnecte != $idcentraleaffectationencours) {
        $bool = 'false';
        $bool1 = '';
    }
    $idcentraleaccueil = $manager->getSingle2("SELECT idcentrale_centrale FROM loginpassword, utilisateur  WHERE idlogin = idlogin_loginpassword and pseudo=?", $_SESSION['pseudo']);
    $arrayidcentrale = $manager->getList2("SELECT co.idcentrale_centrale, p.idcentrale FROM concerne co, projetautrecentrale p WHERE co.idprojet_projet = p.idprojet and p.idprojet=?", $idprojet);
  
    $centraleselectionnees=$manager->getList2("select idcentrale_centrale from concerne where idprojet_projet=?",$idprojet);

    $arrayporteur = $manager->getList2("SELECT nom,prenom FROM utilisateurporteurprojet,utilisateur WHERE idutilisateur_utilisateur = idutilisateur and idprojet_projet=?", $idprojet);
    $sPorteur = '';
    $nbarrayporteur = count($arrayporteur);
    for ($i = 0; $i < $nbarrayporteur; $i++) {
        $sPorteur.=$arrayporteur[$i]['nom'] . ' ' . $arrayporteur[$i]['prenom'] . ' / ';
    }
    $rowResult = $manager->getListbyArray("SELECT idutilisateur,nom, prenom, entrepriselaboratoire FROM utilisateur, projet, creer WHERE idutilisateur_utilisateur = idutilisateur AND idprojet_projet = idprojet and idprojet=?", array($idprojet));
    $demandeur = $rowResult[0]['nom'] . ' ' . $rowResult[0]['prenom'];
    $entrepriselaboratoireo = $rowResult[0]['entrepriselaboratoire'];
    $sPorteur = $demandeur . ' / ' . substr($sPorteur, 0, -1);
    $sporteur = substr($sPorteur, 0, -2);
    $iddemandeur = $rowResult[0]['idutilisateur'];
    $arrayadminprojet = $manager->getList2("Select nom,prenom from utilisateur u,utilisateuradministrateur ua where ua.idutilisateur=u.idutilisateur and ua.idprojet=?", $idprojet);
    $nbarrayadminprojet = count($arrayadminprojet);
    $_sadminprojet = '';
    for ($i = 0; $i < $nbarrayadminprojet; $i++) {
        $_sadminprojet.=$arrayadminprojet[$i]['nom'] . ' - ' . $arrayadminprojet[$i]['prenom'] . '<br>';
    }
    $listeadminprojet = substr($_sadminprojet, 0, -1);
}
