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
define('ADMINLOCAL', $manager->getSingle2("SELECT idtypeutilisateur FROM typeutilisateur WHERE libelletype=? ", 'Administrateur local'));
define('ADMINNATIONNAL', $manager->getSingle2("SELECT idtypeutilisateur FROM typeutilisateur WHERE libelletype=? ", 'Administrateur national'));
define('UTILISATEUR', $manager->getSingle2("SELECT idtypeutilisateur FROM typeutilisateur WHERE libelletype=? ", 'utilisateur'));
define('ENATTENTEPHASE2', $manager->getSingle2("SELECT idstatutprojet FROM statutprojet WHERE libellestatutprojet=? ", 'En Attente'));
define('ENATTENTE', $manager->getSingle2("SELECT idstatutprojet FROM statutprojet WHERE libellestatutprojet=? ", 'En Attente'));
define('ENCOURSANALYSE', $manager->getSingle2("SELECT idstatutprojet FROM statutprojet WHERE libellestatutprojeten=? ", 'Current expertise'));
define('TRANSFERERCENTRALE', $manager->getSingle2("SELECT idstatutprojet FROM statutprojet WHERE libellestatutprojet=? ", 'Transférée à une autre centrale'));
define('REFUSE', $manager->getSingle2("SELECT idstatutprojet FROM statutprojet WHERE libellestatutprojet=? ", 'Refusée'));
define('FINI', $manager->getSingle2("SELECT idstatutprojet FROM statutprojet WHERE libellestatutprojet=? ", 'Fini'));
define('CLOTURE', $manager->getSingle2("SELECT idstatutprojet FROM statutprojet WHERE libellestatutprojet=? ", 'Cloturé'));
define('ACCEPTE', $manager->getSingle2("SELECT idstatutprojet FROM statutprojet WHERE libellestatutprojeten =?", 'Current expertise'));
define('ENCOURSREALISATION', $manager->getSingle2("SELECT idstatutprojet FROM statutprojet WHERE libellestatutprojet=? ", 'En cours de réalisation'));
define('NAQAUALITEINDUST', $manager->getSingle2("SELECT idqualitedemandeurindust FROM qualitedemandeurindust WHERE libellequalitedemandeurindust = ? ", 'n/a'));
define('NAQAUALITEACA', $manager->getSingle2("SELECT idqualitedemandeuraca FROM qualitedemandeuraca WHERE libellequalitedemandeuraca = ? ", 'n/a'));
define('NAAUTRETHEMATIQUE', $manager->getSingle2("SELECT idautrethematique FROM autrethematique WHERE libelleautrethematique = ? limit 1", 'n/a'));
define('IDAUTREEMPLOYEUR', $manager->getSingle2("SELECT idemployeur FROM nomemployeur WHERE libelleemployeur = ? ", 'Autres'));
define('NAAUTREEMPLOYEUR', $manager->getSingle2("SELECT idautrenomemployeur FROM autrenomemployeur WHERE libelleautrenomemployeur = ?", 'n/a'));
define('NAAUTRETUTELLE', $manager->getSingle2("SELECT idautrestutelle FROM autrestutelle WHERE libelleautrestutelle =?", 'n/a'));
define('NAAUTREDISCIPLINE', $manager->getSingle2("SELECT idautrediscipline FROM autredisciplinescientifique WHERE libelleautrediscipline =?", 'n/a'));
define('IDAUTREQUALITE', $manager->getSingle2("SELECT idpersonnequalite FROM personnecentralequalite WHERE libellepersonnequalite =?", 'Autres'));
define('IDNAAUTRESQUALITE', $manager->getSingle2("SELECT idpersonnequalite FROM personnecentralequalite WHERE libellepersonnequalite =?", 'n/a'));
define('IDNAAUTREQUALITE', $manager->getSingle2("SELECT idautresqualite FROM autresqualite WHERE libelleautresqualite =?", 'n/a'));
define('NATYPEPROJET', $manager->getSingle2("SELECT idtypeprojet FROM typeprojet WHERE libelletype=?", 'n/a'));
define('TYPEFORMATION', $manager->getSingle2("SELECT idtypeprojet FROM typeprojet WHERE libelletype=?", 'Formation'));
define('PERMANENT', $manager->getSingle2("SELECT idqualitedemandeuraca FROM qualitedemandeuraca WHERE libellequalitedemandeuraca=? ", 'Permanent'));
define('NONPERMANENTINDUST', $manager->getSingle2("SELECT idqualitedemandeurindust FROM qualitedemandeurindust WHERE libellequalitedemandeurindust = ? ", 'Non permanent (CDD ou Stagiaire)'));
define('PERMANENTINDUST', $manager->getSingle2("SELECT idqualitedemandeurindust FROM qualitedemandeurindust WHERE libellequalitedemandeurindust = ? ", 'Permanent'));
define('NONPERMANENT', $manager->getSingle2("SELECT idqualitedemandeuraca FROM qualitedemandeuraca WHERE libellequalitedemandeuraca =?", 'Non permanent (CDD ou Stagiaire)'));
define('MOIS', $manager->getSingle2("SELECT idperiodicite FROM period WHERE libelleperiodicite=?", 'mois'));
define('JOUR', $manager->getSingle2("SELECT idperiodicite FROM period WHERE libelleperiodicite=?", 'jour(s)'));
define('ANNEE', $manager->getSingle2("SELECT idperiodicite FROM period WHERE libelleperiodicite=?", 'année(s)'));
define('REP_ROOT', $_SERVER['DOCUMENT_ROOT'] . '/' . REPERTOIRE);
define('ABSPATH', dirname(__FILE__) . '/');
define('STATCENTRALETYPE', $manager->getSingle2("SELECT idstatistique FROM statistique WHERE libellestatistique=?", 'Projet par centrale et par type'));
define('STATUSERDATE', $manager->getSingle2("SELECT idstatistique FROM statistique WHERE libellestatistiqueen=?", 'Number of users since the beginning by category'));
define('IDSTATNOUVEAUPROJET', $manager->getSingle2("SELECT idstatistique FROM statistique WHERE libellestatistique=?", 'Cumul du nombre de projets déposés'));
define('IDSTATPROJETDATETYPE', $manager->getSingle2("SELECT idstatistique FROM statistique WHERE libellestatistique=?", 'Nombre de projets déposés de 2014 à 2017 par typologie'));
define('IDSTATTYPOLOGIENOUVEAUPROJET', $manager->getSingle2("SELECT idstatistique FROM statistique WHERE libellestatistique=?", 'Cumul des nouveaux projets par typologie'));
define('IDSTATTYPOLOGIEPROJETENCOURS', $manager->getSingle2("SELECT idstatistique FROM statistique WHERE libellestatistique=?", 'Nombre de projet en cours par typologie'));
define('IDREPARTIONPROJETENCOURSTYPE', $manager->getSingle2("SELECT idstatistique FROM statistique WHERE libellestatistique=?", "Répartition des projets en cours par type, cumul par année"));
define('IDSTATPROJETCENTRALETYPE', $manager->getSingle2("SELECT idstatistique FROM statistique WHERE libellestatistique=?", 'Projet par centrale et par type'));
define('IDSTATSF', $manager->getSingle2("SELECT idstatistique FROM statistique WHERE libellestatistique=?", 'Origine des financements des projets en cours'));
define('IDDUREEPROJETENCOURS', $manager->getSingle2("SELECT idstatistique FROM statistique WHERE libellestatistique=?", 'Durée des projets en cours'));
define('IDNEWUSERBYDATE', $manager->getSingle2("SELECT idstatistique FROM statistique WHERE libellestatistique=?", 'Utilisateurs par date'));
define('IDPERMANENTNONPERMANENTBYDATE', $manager->getSingle2("SELECT idstatistique FROM statistique WHERE libellestatistique=?", 'Origine des nouveaux porteurs de projet, cumul par année'));
define('IDNEWUSERBYTYPE', $manager->getSingle2("SELECT idstatistique FROM statistique WHERE libellestatistique=?", 'Utilisateurs par type'));
define('IDSTATRESSOURCE', $manager->getSingle2("SELECT idstatistique FROM statistique WHERE libellestatistique=?", 'Répartition par ressources technologiques des projets en cours'));

define('IDSTATPARTINDUSENCOURS', $manager->getSingle2("SELECT idstatistique FROM statistique WHERE libellestatistique=?", 'Origine des partenaires industriels des projets en cours'));

define('IDORIGINEPORTPORTEURPROJETENCOURS', $manager->getSingle2("SELECT idstatistique FROM statistique WHERE libellestatistique=?", 'Origine des porteurs de projet en cours, cumul par année'));
    define('IDPARTHORSRENATECH', $manager->getSingle2("SELECT idstatistique FROM statistique WHERE libellestatistique=?", 'Cumul du nombre de partenaires hors RENATECH intéréssés par les projets en cours'));
define('IDNBUSERCLEANROOMNEWPROJET', $manager->getSingle2("SELECT idstatistique FROM statistique WHERE libellestatistique=?", 'Nombre de personnes ayant travaillé en salle blanche depuis 2014'));
define('IDNBUSERCLEANROOMRUNNINGPROJET', $manager->getSingle2("SELECT idstatistique FROM statistique WHERE libellestatistique=?", 'Nombre de personnes travaillant en salle blanche'));
define('IDNBRUNNINGPROJECT', $manager->getSingle2("SELECT idstatistique FROM statistique WHERE libellestatistique=?", 'Nombre de projet en cours'));
define('IDNBPORTEURRUNNINGPROJECT', $manager->getSingle2("SELECT idstatistique FROM statistique WHERE libellestatistique=?", 'Nombre de porteur de projet en cours'));
define('IDCENTRALEAUTRE', $manager->getSingle2("SELECT idcentrale FROM centrale WHERE libellecentrale=?", 'Autres'));
define('ACADEMIC', $manager->getSingle2("SELECT idtypeprojet  FROM typeprojet WHERE libelletype=?", 'Académique'));
define('FORMATION', $manager->getSingle2("SELECT idtypeprojet FROM typeprojet WHERE libelletype=?", 'Formation'));
define('ACADEMICPARTENARIAT', $manager->getSingle2("SELECT idtypeprojet  FROM typeprojet WHERE libelletype=?", 'Académique en partenariat avec un industriel'));
define('INDUSTRIEL', $manager->getSingle2("SELECT idtypeprojet  FROM typeprojet WHERE libelletype=?", 'Industriel'));
define('AUTRECENTRALE', $manager->getSingle2("SELECT idcentrale  FROM centrale WHERE libellecentrale=?", 'Autres'));
define('IDAUTRETUTELLE', $manager->getSingle2("SELECT idtutelle  FROM tutelle WHERE libelletutelle=?", 'Autres'));
define('IDAUTRECENTRALE', $manager->getSingle2("SELECT idcentrale  FROM centrale WHERE libellecentrale=?", 'Autres'));
define('IDNACODEUNITE', $manager->getSingle2("SELECT idautrecodeunite  FROM autrecodeunite WHERE libelleautrecodeunite=?", 'n/a'));
define('IDAUTREDISCIPLINE', $manager->getSingle2("SELECT iddiscipline  FROM disciplinescientifique WHERE libellediscipline=?", 'Autres'));
define('IDAUTRESOURCEFINANCEMENT', $manager->getSingle2("SELECT idsourcefinancement FROM sourcefinancement WHERE libellesourcefinancement=? ", 'Autres'));
define('NBSOURCEDEFINACEMENT', $manager->getSingle("SELECT count(idsourcefinancement) FROM sourcefinancement"));
define('NBRESSOURCE', $manager->getSingle("SELECT count(idressource) FROM ressource"));
define('TXT_REGEXPASS', "regExp:'^.*(?=.{6,})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).*$");
define('IDCENTRALEINTERNATIONNAL', $manager->getSingle2("SELECT idregion FROM region WHERE libelleregion=?",'Centrale internationale'));
define('ADRESSEMAILPROJET','projets@renatech.org');

define ('IDFEMTO',$manager->getSingle2("SELECT idcentrale FROM centrale WHERE libellecentrale=?", 'FEMTO-ST'));
define ('IDIEMN',$manager->getSingle2("SELECT idcentrale FROM centrale WHERE libellecentrale=?", 'IEMN'));
define ('IDLAAS',$manager->getSingle2("SELECT idcentrale FROM centrale WHERE libellecentrale=?", 'LAAS'));
define ('IDLTM',$manager->getSingle2("SELECT idcentrale FROM centrale WHERE libellecentrale=?", 'LTM'));
define ('IDC2N',$manager->getSingle2("SELECT idcentrale FROM centrale WHERE libellecentrale=?", 'C2N'));
define ('FEMTO',$manager->getSingle2("SELECT libellecentrale FROM centrale WHERE idcentrale=?", IDFEMTO));
define ('IEMN',$manager->getSingle2("SELECT libellecentrale FROM centrale WHERE idcentrale=?", IDIEMN));
define ('LAAS',$manager->getSingle2("SELECT libellecentrale FROM centrale WHERE idcentrale=?", IDLAAS));
define ('LTM',$manager->getSingle2("SELECT libellecentrale FROM centrale WHERE idcentrale=?", IDLTM));
define ('C2N',$manager->getSingle2("SELECT libellecentrale FROM centrale WHERE idcentrale=?", IDC2N));

define('IDINTEGRATION',$manager->getSingle2("SELECT idressource FROM ressource WHERE libelleressource=?", TXT_INTEGRATION));
define('IDCARACTERISATION',$manager->getSingle2("SELECT idressource FROM ressource WHERE libelleressource=?", TXT_CHARACTERISATIONMETROLOGY));
define('IDGRAVURE',$manager->getSingle2("SELECT idressource FROM ressource WHERE libelleressource=?", TXT_GRAVURE));
define('IDDEPOT',$manager->getSingle2("SELECT idressource FROM ressource WHERE libelleressource=?", TXT_DEPOT));
define('IDCROISSANCE',$manager->getSingle2("SELECT idressource FROM ressource WHERE libelleressource=?", TXT_CROISSANCE));
define('IDLITHOGRAPHY',$manager->getSingle2("SELECT idressource FROM ressource WHERE libelleressource=?", TXT_LITHOGRAPHY));
if($lang=='fr'){
    define('IDGRANDCOMPTE',$manager->getSingle2("SELECT idtypeentreprise FROM typeentreprise WHERE libelletypeentreprise=?", TXT_GRAND_COMPTE));
}else{
    define('IDGRANDCOMPTE',$manager->getSingle2("SELECT idtypeentreprise FROM typeentreprise WHERE libelletypeentrepriseen=?", TXT_GRAND_COMPTE));
}
if($lang=='fr'){
    define('IDGE',$manager->getSingle2("SELECT idtypepartenaire FROM typepartenaire WHERE libelletypepartenairefr=?", TXT_GE));
}else{
    define('IDGE',$manager->getSingle2("SELECT idtypepartenaire FROM typepartenaire WHERE libelletypepartenaireen=?", TXT_GE));
}
if($lang=='fr'){
    define('IDETI',$manager->getSingle2("SELECT idtypepartenaire FROM typepartenaire WHERE libelletypepartenairefr=?", TXT_ETI));
}else{
    define('IDETI',$manager->getSingle2("SELECT idtypepartenaire FROM typepartenaire WHERE libelletypepartenaireen=?", TXT_ETI));
}

if (isset($_SESSION['pseudo'])) {
    define('IDCENTRALEUSER', $manager->getSingle2("SELECT idcentrale_centrale FROM utilisateur, loginpassword WHERE idlogin_loginpassword=idlogin and pseudo=? ", $_SESSION['pseudo']));
    define('LIBELLECENTRALEUSER', $manager->getSingle2("SELECT libellecentrale FROM centrale,utilisateur, loginpassword WHERE idlogin_loginpassword=idlogin and idcentrale_centrale=idcentrale and pseudo=? ", $_SESSION['pseudo']));
    define('IDTYPEUSER', $manager->getSingle2("SELECT idtypeutilisateur_typeutilisateur FROM  loginpassword,utilisateur WHERE idlogin = idlogin_loginpassword and pseudo=?", $_SESSION['pseudo']));                                                
    define('NOMUSER', $manager->getSingle2("SELECT nom FROM utilisateur,loginpassword  WHERE idlogin=idlogin_loginpassword and pseudo=? ", $_SESSION['pseudo']));
    define('PRENOMUSER', $manager->getSingle2("SELECT prenom FROM utilisateur,loginpassword  WHERE idlogin=idlogin_loginpassword and pseudo=? ", $_SESSION['pseudo']));
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
$string = $_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"];
 $domain = explode("/", $string)[0];
$url = "https://".$domain.'/'.explode("/", $string)[1];
define('ADRESSESITE',$url);