<?php

if (!empty($ancienidrapport)) {
    $datemisajour = date("m,d,Y");
    $datecreation = $manager->getSingle2("select datecreation from rapport where idprojet=? ", $_GET['idprojet']);
    if(isset($_POST['restaureLogoCentrale'])&& $_POST['restaureLogoCentrale']=='ok'){
        $logocentrale = '';
    }elseif(isset($_POST['restaureLogo'])&& $_POST['restaureLogo']=='ok'){
        $logo = '';
    }
    $rapport = new Rapport($ancienidrapport, $title, $author, $entity, $villepays, $instituteinterest, $fundingsource, $collaborator, $thematics, $startingdate, $objectif, $results, $valorization, $technologicalwc, nomFichierValidesansAccent($logo), nomFichierValidesansAccent($logocentrale), nomFichierValidesansAccent($figure), $_GET['idprojet'], $legend, $datecreation, $datemisajour);            
    $manager->updateRapport($rapport, $_GET['idprojet']);
} else {
    $idrapport = $manager->getSingle("select max(idrapport) from rapport") + 1;
    $datemisajour = NULL;
    $datecreation = date("m,d,Y");
    if(isset($_POST['restaureLogoCentrale'])&& $_POST['restaureLogoCentrale']=='ok'){
        $logocentrale = '';
    }elseif(isset($_POST['restaureLogo'])&& $_POST['restaureLogo']=='ok'){
        $logo = '';
    }
    $rapport = new Rapport($idrapport, $title, $author, $entity, $villepays, $instituteinterest, $fundingsource, $collaborator, $thematics, $startingdate, $objectif, $results, $valorization, $technologicalwc, $logo, nomFichierValidesansAccent($logocentrale), nomFichierValidesansAccent($figure), $_GET['idprojet'], $legend, $datecreation, $datemisajour);        
    $manager->addrapport($rapport);
}