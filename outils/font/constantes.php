<?php
$repertoire = explode("/", $_SERVER["PHP_SELF"]);
if ($repertoire[1] == 'projet-dev') {
    include_once '/var/www/rtb/html/' . $repertoire[1] . '/class/Manager.php';
} elseif ($repertoire[1] == 'projet') {
    include_once '/var/www/rtb/html/' . $repertoire[1] . '/class/Manager.php';
} elseif ($repertoire[1] == 'projet-test') {
    include_once '/var/www/rtb/html/' . $repertoire[1] . '/class/Manager.php';
} elseif ($repertoire[1] == 'renatech') {
    include_once '/home/sultan/www/' . $repertoire[1] . '/class/Manager.php'; //SULTAN
}elseif ($repertoire[1] == 'renatechdev') {
    include_once '/home/sultandev/www/' . $repertoire[1] . '/class/Manager.php'; //SULTAN
}


$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
define('REPERTOIRE',$repertoire[1]);
define('ADMINLOCAL', $manager->getSingle2("select idtypeutilisateur from typeutilisateur where libelletype=? ", 'Administrateur local'));
define('ADMINNATIONNAL', $manager->getSingle2("select idtypeutilisateur from typeutilisateur where libelletype=? ", 'Administrateur national'));
define('UTILISATEUR', $manager->getSingle2("select idtypeutilisateur from typeutilisateur where libelletype=? ", 'utilisateur'));
define('ENATTENTE', $manager->getSingle2("select idstatutprojet from statutprojet where libellestatutprojet=? ", 'En attente'));
define('TRANSFERERCENTRALE', $manager->getSingle2("select idstatutprojet from statutprojet where libellestatutprojet=? ", 'Transférée à une autre centrale'));
define('REFUSE', $manager->getSingle2("select idstatutprojet from statutprojet where libellestatutprojet=? ", 'Refusée'));
define('FINI', $manager->getSingle2("select idstatutprojet from statutprojet where libellestatutprojet=? ", 'Fini'));
define('CLOTURE', $manager->getSingle2("select idstatutprojet from statutprojet where libellestatutprojet=? ", 'Cloturé'));
define('ACCEPTE', $manager->getSingle2("select idstatutprojet from statutprojet where libellestatutprojeten =?",'Current expertise'));
define('ENCOURSREALISATION', $manager->getSingle2("select idstatutprojet from statutprojet where libellestatutprojet=? ",'En cours de réalisation'));
define('ENCOURSANALYSE',$manager->getSingle2("select idstatutprojet from statutprojet where libellestatutprojeten =? ","Being analyzed"));
define('NAQAUALITEINDUST', $manager->getSingle2("select idqualitedemandeurindust from qualitedemandeurindust where libellequalitedemandeurindust = ? ",'n/a'));
define('PERMANENT', $manager->getSingle2("select idqualitedemandeuraca from qualitedemandeuraca where libellequalitedemandeuraca=? ",'Permanent'));
define('NONPERMANENTINDUST', $manager->getSingle2("select idqualitedemandeurindust from qualitedemandeurindust where libellequalitedemandeurindust = ? ",'Non permanent (CDD ou Stagiaire)'));
define('NONPERMANENT', $manager->getSingle2("select idqualitedemandeuraca from qualitedemandeuraca where libellequalitedemandeuraca =?",'Non permanent (CDD ou Stagiaire)'));
define ('MOIS',$manager->getSingle2("select idperiodicite from period where libelleperiodicite=?",'mois'));
define ('JOUR',$manager->getSingle2("select idperiodicite from period where libelleperiodicite=?",'jour(s)'));
define ('ANNEE',$manager->getSingle2("select idperiodicite from period where libelleperiodicite=?",'année(s)'));
define ('REP_ROOT',$_SERVER['DOCUMENT_ROOT'].'/'.REPERTOIRE);//REPERTOIRE /var/www/rtb/html/projet-dev/outils/
define('ABSPATH', dirname(__FILE__) . '/');
define('FORMATION', $manager->getSingle2("select idtypeprojet from typeprojet where libelletype=?",	'Formation'));
define('STATCENTRALETYPE', $manager->getSingle2("select idstatistique from statistique where libellestatistique=?",	'Projet par centrale et par type'));
define('STATUSERDATE', $manager->getSingle2("select idstatistique from statistique where libellestatistique=?",	'Utilisateur par date'));
define('IDSTATPROJETDATE', $manager->getSingle2("select idstatistique from statistique where libellestatistique=?",'Projet par date ou statut'));
define('IDSTATPROJETDATETYPE', $manager->getSingle2("select idstatistique from statistique where libellestatistique=?",'Projet par date et par type'));
define('IDSTATPROJETCENTRALETYPE', $manager->getSingle2("select idstatistique from statistique where libellestatistique=?",'Projet par centrale et par type'));
define('IDSTATSF', $manager->getSingle2("select idstatistique from statistique where libellestatistique=?",'Source de financement par date'));
define('IDSTATRESSOURCE', $manager->getSingle2("select idstatistique from statistique where libellestatistique=?",'Ressource par date'));
define('IDCENTRALEAUTRE', $manager->getSingle2("select idcentrale from centrale where libellecentrale=?",	'Autres'));

