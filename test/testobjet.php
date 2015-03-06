<?php


include '../class/Manager.php';
$db = BD::connecter();
$manager = new Manager($db);
$idprojet = $manager->getSingle("select max idprojet from projet")+1;
$projetphase1 = new Projetphase1($idprojet, $titre, $numero, $confidentiel, $description, $dateprojet, $contexte, $idtypeprojet, $attachement, $acronyme);

$projetphase2 = new Projetphase2($contactscentral, $idtypeprojet, $nbHeure, $datedebut, $dureeprojet, $idperiodicite, $centralepartenaireprojet, $idthematique, $idautrethematique, $descriptifTechnologique, $attachementdesc, $verrouidentifiee, $nbplaque, $nbrun, $envoidevis, $emailrespdevis, $reussite, $refinterneprojet, $devtechnologique, $nbeleve, $nomformateur, $partenaire1, $porteurprojet);


BD::deconnecter();
