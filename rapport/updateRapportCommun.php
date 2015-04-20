<?php

$ancienidrapport = $manager->getSingle2("select idrapport from rapport where idprojet=?", $idprojet);
if (!empty($ancienidrapport)) {
    $datemisajour = date("m,d,Y");
    $datecreation = $manager->getSingle2("select datecreation from rapport where idprojet=? ", $idprojet);
    $rapport = new Rapport($ancienidrapport, $title, $author, $entity, $villepays, $instituteinterest, $fundingsource, $collaborator, $thematics, $startingdate, $objectif, $results, $valorization, $technologicalwc, $logo, $logocentrale, $figure, $idprojet, $legend, $datecreation, $datemisajour);
    $manager->updateRapport($rapport, $idprojet);
} else {
    $datemisajour = NULL;
    $datecreation = date("m,d,Y");
    $rapport = new Rapport($idrapport, $title, $author, $entity, $villepays, $instituteinterest, $fundingsource, $collaborator, $thematics, $startingdate, $objectif, $results, $valorization, $technologicalwc, $logo, $logocentrale, $figure, $idprojet, $legend, $datecreation, $datemisajour);
    $manager->addrapport($rapport);
}