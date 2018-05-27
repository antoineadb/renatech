<?php

include_once '../class/Manager.php';
include_once '../outils/constantes.php';
include_once '../outils/toolBox.php';
$db = BD::connecter();
$manager = new Manager($db);
$idprojet=104;
$titreProjet='Video Imaging of Biological and Bioinspired Nanosystems';
$numProjet='P-13-00085';
$acronyme='ViBBNANO';
$confidentiel=false;
$descriptif='n/a';
$contexte="développement d'une nouvelle génération de sondes pour la microscopie à force atomique basées sur des micro et nanosystèmes";
$dateprojet='2013-05-30';
$idtypeprojet_typeprojet=2;
$attachement='';
$projet = new Projetphase1($idprojet, $titreProjet, $numProjet, $confidentiel, $descriptif, $dateprojet, $contexte, $idtypeprojet_typeprojet, $attachement, $acronyme);
try {
    $manager->addProjetphase1($projet);
} catch (Exception $exc) {
    echo $exc->getTraceAsString();
}

$creer = new Creer(460, 104);
try {
    $manager->addCreer($creer);   
} catch (Exception $exc) {
    echo $exc->getTraceAsString().'<br>'.'Erreur sur addCreer';
}
$concerne = new Concerne(3, 104, 3, '');
try {
    $manager->addConcerne($concerne);
} catch (Exception $exc) {
    echo $exc->getTraceAsString().'<br>'.'Erreur sur addConcerne';
}





$contactCentralAccueil='';
$nbHeure=null;
$dateDebutTravaux='2012-02-15';
$dureeprojet=6;
$idperiodicite=2;
$centralepartenaireprojet=false;
$idthematique_thematique=2;
$idautrethematique_autrethematique=1;
$descriptifTechnologique='';
$attachementdesc='';
$verrouidentifie='';
$nbPlaque=null;
$nbRun=null;
$devis=null;
$mailresp=null;
$reussite='';
$refinterne='13007';
$devtechnologique=true;
$nbeleve=0;
$nomformateur='';
$partenaire1='';
$porteurprojet=true;
$dureeestime=6;
$periodestime=2;
$descriptionautrecentrale='';
$etapeautrecentrale=false;
$centrale_proximite=false;
$descriptioncentraleproximite='';
$projetphase2 = new Projetphase2($contactCentralAccueil, $idtypeprojet_typeprojet, $nbHeure, $dateDebutTravaux, $dureeprojet, $idperiodicite, $centralepartenaireprojet, $idthematique_thematique, $idautrethematique_autrethematique, $descriptifTechnologique, $attachementdesc, $verrouidentifie, $nbPlaque, $nbRun, $devis, $mailresp, $reussite, $refinterne, $devtechnologique, $nbeleve, $nomformateur, $partenaire1, $porteurprojet, $dureeestime, $periodestime, $descriptionautrecentrale, $etapeautrecentrale, $centrale_proximite, $descriptioncentraleproximite);
try {
    $manager->updateProjetphase2($projetphase2, $idprojet);
} catch (Exception $exc) {
    echo $exc->getTraceAsString().'<br>'.'Erreur sur updateProjetphase2';
}





$db = BD::deconnecter();

