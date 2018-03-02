<?php
    
$repertoire = explode("/", $_SERVER["PHP_SELF"]);
if ($repertoire[1] == 'projet-dev') {
    include_once '/var/www/rtb/html/' . $repertoire[1] . '/class/Manager.php';
} elseif ($repertoire[1] == 'projet') {
    include_once '/var/www/rtb/html/' . $repertoire[1] . '/class/Manager.php';
} elseif ($repertoire[1] == 'projet-test') {
    include_once '/var/www/rtb/html/' . $repertoire[1] . '/class/Manager.php';
} elseif ($repertoire[1] == 'projet-archive') {
    include_once '/var/www/rtb/html/' . $repertoire[1] . '/class/Manager.php';
} elseif ($repertoire[1] == 'projet-prod') {
    include_once '/var/www/rtb/html/' . $repertoire[1] . '/class/Manager.php';
}


$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
define('REPERTOIRE', $repertoire[1]);
define('ADMINLOCAL', $manager->getSingle2("select idtypeutilisateur from typeutilisateur where libelletype=? ", 'Administrateur local'));
define('ADMINNATIONNAL', $manager->getSingle2("select idtypeutilisateur from typeutilisateur where libelletype=? ", 'Administrateur national'));
define('UTILISATEUR', $manager->getSingle2("select idtypeutilisateur from typeutilisateur where libelletype=? ", 'utilisateur'));
define('ENATTENTEPHASE2', $manager->getSingle2("select idstatutprojet from statutprojet where libellestatutprojet=? ", 'En Attente'));
define('ENATTENTE', $manager->getSingle2("select idstatutprojet from statutprojet where libellestatutprojet=? ", 'En Attente'));
define('ENCOURSANALYSE', $manager->getSingle2("select idstatutprojet from statutprojet where libellestatutprojeten=? ", 'Current expertise'));
define('TRANSFERERCENTRALE', $manager->getSingle2("select idstatutprojet from statutprojet where libellestatutprojet=? ", 'Transférée à une autre centrale'));
define('REFUSE', $manager->getSingle2("select idstatutprojet from statutprojet where libellestatutprojet=? ", 'Refusée'));
define('FINI', $manager->getSingle2("select idstatutprojet from statutprojet where libellestatutprojet=? ", 'Fini'));
define('CLOTURE', $manager->getSingle2("select idstatutprojet from statutprojet where libellestatutprojet=? ", 'Cloturé'));
define('ACCEPTE', $manager->getSingle2("select idstatutprojet from statutprojet where libellestatutprojeten =?", 'Current expertise'));
define('ENCOURSREALISATION', $manager->getSingle2("select idstatutprojet from statutprojet where libellestatutprojet=? ", 'En cours de réalisation'));
define('NAQAUALITEINDUST', $manager->getSingle2("select idqualitedemandeurindust from qualitedemandeurindust where libellequalitedemandeurindust = ? ", 'n/a'));
define('NAQAUALITEACA', $manager->getSingle2("select idqualitedemandeuraca from qualitedemandeuraca where libellequalitedemandeuraca = ? ", 'n/a'));
define('NAAUTRETHEMATIQUE', $manager->getSingle2("select idautrethematique from autrethematique where libelleautrethematique = ? limit 1", 'n/a'));
define('IDAUTREEMPLOYEUR', $manager->getSingle2("select idemployeur from nomemployeur where libelleemployeur = ? ", 'Autres'));
define('NAAUTREEMPLOYEUR', $manager->getSingle2("select idautrenomemployeur from autrenomemployeur where libelleautrenomemployeur = ?", 'n/a'));
define('NAAUTRETUTELLE', $manager->getSingle2("select idautrestutelle from autrestutelle where libelleautrestutelle =?", 'n/a'));
define('NAAUTREDISCIPLINE', $manager->getSingle2("select idautrediscipline from autredisciplinescientifique where libelleautrediscipline =?", 'n/a'));
define('IDAUTREQUALITE', $manager->getSingle2("select idpersonnequalite from personnecentralequalite where libellepersonnequalite =?", 'Autres'));
define('IDNAAUTRESQUALITE', $manager->getSingle2("select idpersonnequalite from personnecentralequalite where libellepersonnequalite =?", 'n/a'));
define('IDNAAUTREQUALITE', $manager->getSingle2("select idautresqualite from autresqualite where libelleautresqualite =?", 'n/a'));
define('NATYPEPROJET', $manager->getSingle2("select idtypeprojet from typeprojet where libelletype=?", 'n/a'));
define('TYPEFORMATION', $manager->getSingle2("select idtypeprojet from typeprojet where libelletype=?", 'Formation'));
define('PERMANENT', $manager->getSingle2("select idqualitedemandeuraca from qualitedemandeuraca where libellequalitedemandeuraca=? ", 'Permanent'));
define('NONPERMANENTINDUST', $manager->getSingle2("select idqualitedemandeurindust from qualitedemandeurindust where libellequalitedemandeurindust = ? ", 'Non permanent (CDD ou Stagiaire)'));
define('PERMANENTINDUST', $manager->getSingle2("select idqualitedemandeurindust from qualitedemandeurindust where libellequalitedemandeurindust = ? ", 'Permanent'));
define('NONPERMANENT', $manager->getSingle2("select idqualitedemandeuraca from qualitedemandeuraca where libellequalitedemandeuraca =?", 'Non permanent (CDD ou Stagiaire)'));
define('MOIS', $manager->getSingle2("select idperiodicite from period where libelleperiodicite=?", 'mois'));
define('JOUR', $manager->getSingle2("select idperiodicite from period where libelleperiodicite=?", 'jour(s)'));
define('ANNEE', $manager->getSingle2("select idperiodicite from period where libelleperiodicite=?", 'année(s)'));
define('REP_ROOT', $_SERVER['DOCUMENT_ROOT'] . '/' . REPERTOIRE);
define('ABSPATH', dirname(__FILE__) . '/');
define('STATCENTRALETYPE', $manager->getSingle2("select idstatistique from statistique where libellestatistique=?", 'Projet par centrale et par type'));
define('STATUSERDATE', $manager->getSingle2("select idstatistique from statistique where libellestatistiqueen=?", 'Number of users since the beginning by category'));
define('IDSTATNOUVEAUPROJET', $manager->getSingle2("select idstatistique from statistique where libellestatistique=?", 'Cumul du nombre de projets déposés'));
define('IDSTATPROJETDATETYPE', $manager->getSingle2("select idstatistique from statistique where libellestatistique=?", 'Répartition des nouveaux projets par type, cumul par année'));
define('IDSTATTYPOLOGIENOUVEAUPROJET', $manager->getSingle2("select idstatistique from statistique where libellestatistique=?", 'Cumul des nouveaux projets par typologie'));
define('IDSTATTYPOLOGIEPROJETENCOURS', $manager->getSingle2("select idstatistique from statistique where libellestatistique=?", 'Nombre de projet en cours par typologie'));
define('IDREPARTIONPROJETENCOURSTYPE', $manager->getSingle2("select idstatistique from statistique where libellestatistique=?", "Répartition des projets en cours par type, cumul par année"));
define('IDSTATPROJETCENTRALETYPE', $manager->getSingle2("select idstatistique from statistique where libellestatistique=?", 'Projet par centrale et par type'));
define('IDSTATSF', $manager->getSingle2("select idstatistique from statistique where libellestatistique=?", 'Origine des financements des projets en cours'));
define('IDDUREEPROJETENCOURS', $manager->getSingle2("select idstatistique from statistique where libellestatistique=?", 'Durée des projets en cours'));
define('IDNEWUSERBYDATE', $manager->getSingle2("select idstatistique from statistique where libellestatistique=?", 'Utilisateurs par date'));
define('IDPERMANENTNONPERMANENTBYDATE', $manager->getSingle2("select idstatistique from statistique where libellestatistique=?", 'Origine des nouveaux porteurs de projet, cumul par année'));
define('IDNEWUSERBYTYPE', $manager->getSingle2("select idstatistique from statistique where libellestatistique=?", 'Utilisateurs par type'));
define('IDSTATRESSOURCE', $manager->getSingle2("select idstatistique from statistique where libellestatistique=?", 'Répartition par ressources technologiques des projets en cours'));
define('IDORIGINEPORTPORTEURPROJETENCOURS', $manager->getSingle2("select idstatistique from statistique where libellestatistique=?", 'Origine des porteurs de projet en cours, cumul par année'));
    define('IDPARTHORSRENATECH', $manager->getSingle2("select idstatistique from statistique where libellestatistique=?", 'Cumul du nombre de partenaires hors RENATECH intéréssés par les projets en cours'));
define('IDNBUSERCLEANROOMNEWPROJET', $manager->getSingle2("select idstatistique from statistique where libellestatistique=?", 'Nombre de personnes ayant travaillé en salle blanche depuis 2014'));
define('IDNBUSERCLEANROOMRUNNINGPROJET', $manager->getSingle2("select idstatistique from statistique where libellestatistique=?", 'Nombre de personnes travaillant en salle blanche'));
define('IDNBRUNNINGPROJECT', $manager->getSingle2("select idstatistique from statistique where libellestatistique=?", 'Nombre de projet en cours'));
define('IDNBPORTEURRUNNINGPROJECT', $manager->getSingle2("select idstatistique from statistique where libellestatistique=?", 'Cumul du nombre de porteur de projet en cours'));
define('IDCENTRALEAUTRE', $manager->getSingle2("select idcentrale from centrale where libellecentrale=?", 'Autres'));
define('ACADEMIC', $manager->getSingle2("select idtypeprojet  from typeprojet where libelletype=?", 'Académique'));
define('FORMATION', $manager->getSingle2("select idtypeprojet from typeprojet where libelletype=?", 'Formation'));
define('ACADEMICPARTENARIAT', $manager->getSingle2("select idtypeprojet  from typeprojet where libelletype=?", 'Académique en partenariat avec un industriel'));
define('INDUSTRIEL', $manager->getSingle2("select idtypeprojet  from typeprojet where libelletype=?", 'Industriel'));
define('AUTRECENTRALE', $manager->getSingle2("select idcentrale  from centrale where libellecentrale=?", 'Autres'));
define('IDAUTRETUTELLE', $manager->getSingle2("select idtutelle  from tutelle where libelletutelle=?", 'Autres'));
define('IDAUTRECENTRALE', $manager->getSingle2("select idcentrale  from centrale where libellecentrale=?", 'Autres'));
define('IDNACODEUNITE', $manager->getSingle2("select idautrecodeunite  from autrecodeunite where libelleautrecodeunite=?", 'n/a'));
define('IDAUTREDISCIPLINE', $manager->getSingle2("select iddiscipline  from disciplinescientifique where libellediscipline=?", 'Autres'));
define('IDAUTRESOURCEFINANCEMENT', $manager->getSingle2("select idsourcefinancement from sourcefinancement where libellesourcefinancement=? ", 'Autres'));
define('NBSOURCEDEFINACEMENT', $manager->getSingle("select count(idsourcefinancement) from sourcefinancement"));
define('NBRESSOURCE', $manager->getSingle("select count(idressource) from ressource"));
define('TXT_REGEXPASS', "regExp:'^.*(?=.{6,})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).*$");
define('IDCENTRALEINTERNATIONNAL', $manager->getSingle2("select idregion from region where libelleregion=?",'Centrale internationale'));

define ('IDFEMTO',$manager->getSingle2("select idcentrale from centrale where libellecentrale=?", 'FEMTO-ST'));
define ('IDIEMN',$manager->getSingle2("select idcentrale from centrale where libellecentrale=?", 'IEMN'));
define ('IDLAAS',$manager->getSingle2("select idcentrale from centrale where libellecentrale=?", 'LAAS'));
define ('IDLTM',$manager->getSingle2("select idcentrale from centrale where libellecentrale=?", 'LTM'));
define ('IDC2N',$manager->getSingle2("select idcentrale from centrale where libellecentrale=?", 'C2N'));
define ('FEMTO',$manager->getSingle2("select libellecentrale from centrale where idcentrale=?", IDFEMTO));
define ('IEMN',$manager->getSingle2("select libellecentrale from centrale where idcentrale=?", IDIEMN));
define ('LAAS',$manager->getSingle2("select libellecentrale from centrale where idcentrale=?", IDLAAS));
define ('LTM',$manager->getSingle2("select libellecentrale from centrale where idcentrale=?", IDLTM));
define ('C2N',$manager->getSingle2("select libellecentrale from centrale where idcentrale=?", IDC2N));

define('IDINTEGRATION',$manager->getSingle2("select idressource from ressource where libelleressource=?", TXT_INTEGRATION));
define('IDCARACTERISATION',$manager->getSingle2("select idressource from ressource where libelleressource=?", TXT_CHARACTERISATIONMETROLOGY));
define('IDGRAVURE',$manager->getSingle2("select idressource from ressource where libelleressource=?", TXT_GRAVURE));
define('IDDEPOT',$manager->getSingle2("select idressource from ressource where libelleressource=?", TXT_DEPOT));
define('IDCROISSANCE',$manager->getSingle2("select idressource from ressource where libelleressource=?", TXT_CROISSANCE));
define('IDLITHOGRAPHY',$manager->getSingle2("select idressource from ressource where libelleressource=?", TXT_LITHOGRAPHY));


if (isset($_SESSION['pseudo'])) {
    define('IDCENTRALEUSER', $manager->getSingle2("select idcentrale_centrale from utilisateur, loginpassword where idlogin_loginpassword=idlogin and pseudo=? ", $_SESSION['pseudo']));
    define('LIBELLECENTRALEUSER', $manager->getSingle2("select libellecentrale from centrale,utilisateur, loginpassword where idlogin_loginpassword=idlogin and idcentrale_centrale=idcentrale and pseudo=? ", $_SESSION['pseudo']));
    define('IDTYPEUSER', $manager->getSingle2("SELECT idtypeutilisateur_typeutilisateur FROM  loginpassword,utilisateur WHERE idlogin = idlogin_loginpassword and pseudo=?", $_SESSION['pseudo']));                                                
    define('NOMUSER', $manager->getSingle2("select nom from utilisateur,loginpassword  where idlogin=idlogin_loginpassword and pseudo=? ", $_SESSION['pseudo']));
    define('PRENOMUSER', $manager->getSingle2("select prenom from utilisateur,loginpassword  where idlogin=idlogin_loginpassword and pseudo=? ", $_SESSION['pseudo']));
}

define('NOW', strtotime(date("d-m-Y H:i:s")));
define('REGEX_TYPE', "regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð%&:0-9\\133\\134\\135\\042\'’/=°()_ ,.-]+'");
define('REGEX_TYPESTRING', "regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð%&:\'’/=°()_ ,.-]+'");                            
define('REGEX_TYPESTRINGLISTE', "regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð%&:0-9\'’/=°()_ ><,.-]+'");
define('REGEX_TEL', "regExp:'[a-zA-Z0-9àáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð%&:0-9\\133\\134\\135\\042\'’/=°/+()_ ,.-]+'");
define('REGEX_ACCUEIL1',"regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð0-9€$!\_\«\»\’’ ,\.\'\-\:\/]+'");                         
define("REGEX_ACCUEIL","regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð0-9_ ,\.\•\'\-\:\/]+'");
define("REGEX_ACCUEIL_CENTRALE","regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð%&:0-9\042\'()_ ,.-]+");

function couleurGraph($idcentrale){
    if ($idcentrale== IDFEMTO){
        return $couleur='#7cb5ec';
    }elseif ($idcentrale== IDIEMN){
        return $couleur='#FFBC75';
    }elseif ($idcentrale== IDLAAS){
        return $couleur= '#A9FF97';
    }elseif ($idcentrale== IDLTM){
        return $couleur='#90B1D8';
    }elseif ($idcentrale== IDC2N){                    
        return $couleur='#C3FFFF';
    }  
}
function couleurGraphLib($libcentrale){
    if ($libcentrale== FEMTO || $libcentrale== FEMTO){
        return $couleur='#7cb5ec';
    }elseif ($libcentrale== IEMN){
        return $couleur='#FFBC75';
    }elseif ($libcentrale== LAAS){
        return $couleur= '#A9FF97';
    }elseif ($libcentrale== LTM){
        return $couleur='#90B1D8';
    }elseif ($libcentrale== C2N){                    
        return $couleur='#C3FFFF';
    }  
}

function couleurRessource($idressource){
    if ($idressource== IDINTEGRATION){
        return $couleur='#ff0000';
    }elseif ($idressource== IDCARACTERISATION){
        return $couleur='#dd33dd';
    }elseif ($idressource== IDGRAVURE){
        return $couleur= '#050e5e';
    }elseif ($idressource== IDDEPOT){
        return $couleur='#edef4f';
    }elseif ($idressource== IDCROISSANCE){          
        return $couleur='#0f7a0d';
    }elseif ($idressource== IDLITHOGRAPHY){
        return $couleur='#00ffc3';
    }
        
}

function anneeStatistique($d,$f){
    $annee= array();
    $depart = intval($d);
    $fin = intval($f);
    if($depart==$fin){
        array_push($annee, $depart);
    }else{                
        for ($i = 0; $i < ($fin-$depart)+1; $i++) {
            array_push($annee, ($depart+$i));
        }
    }
    return $annee;
}