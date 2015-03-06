<?php

include_once 'class/Manager.php';
include_once 'outils/toolBox.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
$filename = "templates/WordFiletest.rtf";
if (file_exists($filename)) {
    //On ouvre le modele
    $fp = fopen($filename, 'r');
    $content = fread($fp, filesize($filename));
    fclose($fp);
    if (!empty($_GET['idprojet'])) {
        $idprojet=$_GET['idprojet'];
    }elseif(!empty($_GET['numProjet'])){
        $idprojet=$manager->getSingle2("select idprojet from projet where numero=?", $_GET['numProjet']);
    }
        $donnercreateur = $manager->getList2("SELECT nom, prenom,titre,numero,centralepartenaireprojet,datedebuttravaux,contexte,idtutelle_tutelle,idthematique_thematique,idpays_pays
FROM utilisateur, projet, creer WHERE idprojet_projet =idprojet AND idutilisateur_utilisateur = idutilisateur and idprojet=?",$idprojet);
        $nom = utf8_decode($donnercreateur[0]['nom']);
        $prenom = utf8_decode($donnercreateur[0]['prenom']);
        $pays = $manager->getSingle2("select nompaysen  from pays where idpays=?", $donnercreateur[0]['idpays_pays']);
        if (!empty($donnercreateur[0]['idthematique_thematique'])) {
            $thematique = $manager->getSingle2("select libellethematiqueen from thematique where idthematique=?", $donnercreateur[0]['idthematique_thematique']);
        } else {
            $thematique = '';
        }
        $titre = str_replace("– ", "-", str_replace("’", "'", $donnercreateur[0]['titre']));
        $titre = str_replace(" –", "-", $titre);
        $titre = str_replace("œ", "oe", $titre);
        $titre = stripslashes(utf8_decode(str_replace("''", "'", $titre)));
        $objectif = str_replace("– ", "-", str_replace("’", "'", $donnercreateur[0]['contexte']));
        $objectif = str_replace(" –", "-", $objectif);
        $objectif = str_replace("œ", "oe", $objectif);
        $objectif = stripslashes(utf8_decode(str_replace("''", "'", $objectif)));
        $objectif = strip_tags($objectif);
        if (!empty($donnercreateur[0]['datedebuttravaux'])) {
            $datedebuttravaux = $donnercreateur[0]['datedebuttravaux'];
        }
        date_default_timezone_set('Europe/London');
        $datedebuttravaux = date("m-Y");
        $datedebuttravaux = '' . $datedebuttravaux . '';
        $sourcefinancement = "";
        $arraysf = $manager->getlist2("SELECT libellesourcefinancementen FROM projetsourcefinancement,sourcefinancement WHERE idsourcefinancement = idsourcefinancement_sourcefinancement and idprojet_projet=?",$idprojet);
        $nbarraysf = count($arraysf);
        for ($i = 0; $i < $nbarraysf; $i++) {
            $sourcefinancement .= $arraysf[$i]['libellesourcefinancementen'] . ', ';
        }
        $sourcefinancement = substr($sourcefinancement, 0, -2);

        $entity = $manager->getSingle2("select libelletutelleen from tutelle where idtutelle=?", $donnercreateur[0]['idtutelle_tutelle']);
        $collaborator = utf8_decode($donnercreateur[0]['centralepartenaireprojet']);
        $arraycollaborateur = $manager->getList2("SELECT nomlaboentreprise  FROM projetpartenaire, partenaireprojet WHERE  idpartenaire = idpartenaire_partenaireprojet and idprojet_projet=? ",$idprojet);
        $nbarraycollaborateur = count($arraycollaborateur);
        for ($i = 0; $i < $nbarraycollaborateur; $i++) {
            $collaborator.=utf8_decode($arraycollaborateur[$i]['nomlaboentreprise']) . ', ';
        }
        $collaborator = substr($collaborator, 0, -2);

        $technique = "";
        $arraytechnique = $manager->getList2("SELECT libelleressourceen FROM ressource,ressourceprojet WHERE  idressource = idressource_ressource and idprojet_projet=?",$idprojet);
        $nbarraytechnique = count($arraytechnique);
        for ($i = 0; $i < $nbarraytechnique; $i++) {
            $technique .=$arraytechnique[$i]['libelleressourceen'] . ', ';
        }
        $technique = substr($technique, 0, -2);
        $imagecentrale = '<image src="styles/img/Excel-csv.png">';
        $content = str_replace('$titre', $titre, $content);
        $content = str_replace('$imagecentrale', $imagecentrale, $content);
        $content = str_replace('$datedebuttravaux', $datedebuttravaux, $content);
        $content = str_replace("[nom]", $nom, $content);
        $content = str_replace("[prenom]", $prenom, $content);
        $content = str_replace("[entity]", $entity, $content);
        $content = str_replace("[pays]", $pays, $content);
        $content = str_replace("[collaborator]", $collaborator, $content);
        $content = str_replace('$thematique', $thematique, $content);
        $content = str_replace("[sourcefinancement]", $sourcefinancement, $content);
        $content = str_replace("[objectif]", $objectif, $content);
        $content = str_replace("[resultat]", " to be done ", $content);
        $content = str_replace("[valorisation]", "to be done", $content);
        $content = str_replace("[technique]", $technique, $content);



        header("Content-Type: application/msword");
        header('Content-Disposition: attachment; filename=rapport_' . $donnercreateur[0]['numero'] . '.doc');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

        echo $content;
    
}

