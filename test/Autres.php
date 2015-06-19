<?php

include_once '../class/Manager.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER

$arrayAutreId = array(
    array("idpersonne" => 3458, "libelleautre" => "n/d"),
    array("idpersonne" => 3456, "libelleautre" => "n/d"),
    array("idpersonne" => 3455, "libelleautre" => "n/d"),
    array("idpersonne" => 5555, "libelleautre" => "n/d"),
    array("idpersonne" => 5709, "libelleautre" => "Alternant"),
    array("idpersonne" => 5671, "libelleautre" => "Alternant"),
    array("idpersonne" => 5470, "libelleautre" => "Alternant"),
    array("idpersonne" => 5117, "libelleautre" => "CDD"),
    array("idpersonne" => 6427, "libelleautre" => "CDD"),
    array("idpersonne" => 6426, "libelleautre" => "CDD"),
    array("idpersonne" => 6430, "libelleautre" => "CDD"),
    array("idpersonne" => 6518, "libelleautre" => "CDD"),
    array("idpersonne" => 3024, "libelleautre" => "CDD"),
    array("idpersonne" => 3484, "libelleautre" => "CDD"),
    array("idpersonne" => 3306, "libelleautre" => "CDD"),
    array("idpersonne" => 5475, "libelleautre" => "CDD"),
    array("idpersonne" => 6273, "libelleautre" => "CDD"),
    array("idpersonne" => 5486, "libelleautre" => "CDD"),
    array("idpersonne" => 6272, "libelleautre" => "CDD"),
    array("idpersonne" => 5462, "libelleautre" => "CDD"),
    array("idpersonne" => 6243, "libelleautre" => "CDD"),
    array("idpersonne" => 3736, "libelleautre" => "CDD"),
    array("idpersonne" => 4102, "libelleautre" => "CDD"),
    array("idpersonne" => 3054, "libelleautre" => "CDD"),
    array("idpersonne" => 4201, "libelleautre" => "CDD"),
    array("idpersonne" => 3869, "libelleautre" => "CDD"),
    array("idpersonne" => 5119, "libelleautre" => "CDD"),
    array("idpersonne" => 5566, "libelleautre" => "CDD"),
    array("idpersonne" => 5566, "libelleautre" => "CDD"),
    array("idpersonne" => 3305, "libelleautre" => "CDD"),
    array("idpersonne" => 6452, "libelleautre" => "CDD"),
    array("idpersonne" => 5192, "libelleautre" => "CDD"),
    array("idpersonne" => 4233, "libelleautre" => "CDD"),
    array("idpersonne" => 4047, "libelleautre" => "CDD"),
    array("idpersonne" => 2514, "libelleautre" => "Stagiaire"),
    array("idpersonne" => 3746, "libelleautre" => "stagiaire"),
    array("idpersonne" => 3694, "libelleautre" => "Stagiaire"),
    array("idpersonne" => 2701, "libelleautre" => "Stagiaire"),
    array("idpersonne" => 5460, "libelleautre" => "Stagiaire"),
    array("idpersonne" => 3246, "libelleautre" => "Stagiaire"),
    array("idpersonne" => 4959, "libelleautre" => "Stagiaire"),
    array("idpersonne" => 4423, "libelleautre" => "stagiaire"),
    array("idpersonne" => 4231, "libelleautre" => "Stagiaire"),
    array("idpersonne" => 5069, "libelleautre" => "Stagiaire"),
    array("idpersonne" => 3704, "libelleautre" => "Stagiaire"),
    array("idpersonne" => 5779, "libelleautre" => "Stagiaire"),
    array("idpersonne" => 3945, "libelleautre" => "Stagiaire"),
    array("idpersonne" => 3867, "libelleautre" => "Stagiaire"),
    array("idpersonne" => 5210, "libelleautre" => "Stagiaire"),
    array("idpersonne" => 4247, "libelleautre" => "Stagiaire"),
    array("idpersonne" => 5378, "libelleautre" => "stagiaire"),
    array("idpersonne" => 3003, "libelleautre" => "Stagiaire"),
    array("idpersonne" => 4945, "libelleautre" => "Stagiaire"),
    array("idpersonne" => 4752, "libelleautre" => "Stagiaire"),
    array("idpersonne" => 5273, "libelleautre" => "stagiaire"),
    array("idpersonne" => 5022, "libelleautre" => "Stagiaire"),
    array("idpersonne" => 3427, "libelleautre" => "Stagiaire"),
    array("idpersonne" => 4328, "libelleautre" => "Stagiaire"),
    array("idpersonne" => 5127, "libelleautre" => "Stagiaire"),
    array("idpersonne" => 6424, "libelleautre" => "Stagiaire"),
    array("idpersonne" => 5627, "libelleautre" => "n/d"),
    array("idpersonne" => 5704, "libelleautre" => "n/d"),
    array("idpersonne" => 6353, "libelleautre" => "n/d"),
    array("idpersonne" => 3538, "libelleautre" => "n/d"),
    array("idpersonne" => 5504, "libelleautre" => "n/d"),
    array("idpersonne" => 6158, "libelleautre" => "n/d"),
    array("idpersonne" => 4901, "libelleautre" => "n/d"),
    array("idpersonne" => 6144, "libelleautre" => "n/d"),
    array("idpersonne" => 5306, "libelleautre" => "Ingénieur CDD"),
    array("idpersonne" => 6496, "libelleautre" => "Ingénieur CDD"),
    array("idpersonne" => 6148, "libelleautre" => "n/d"),
    array("idpersonne" => 6139, "libelleautre" => "n/d"),
    array("idpersonne" => 6355, "libelleautre" => "n/d"),
    array("idpersonne" => 5703, "libelleautre" => "n/d"),
    array("idpersonne" => 5637, "libelleautre" => "n/d"),
    array("idpersonne" => 6032, "libelleautre" => "n/d"),
    array("idpersonne" => 6166, "libelleautre" => "n/d"),
    array("idpersonne" => 5625, "libelleautre" => "n/d"),
    array("idpersonne" => 5316, "libelleautre" => "n/d"),
    array("idpersonne" => 5862, "libelleautre" => "stagiaire"),
    array("idpersonne" => 6550, "libelleautre" => "stagiaire"),
    array("idpersonne" => 6543, "libelleautre" => "stagaire"),
    array("idpersonne" => 5017, "libelleautre" => "n/d"),
    array("idpersonne" => 6266, "libelleautre" => "stagiaire"),
    array("idpersonne" => 1615, "libelleautre" => "n/d"),
    array("idpersonne" => 3420, "libelleautre" => "n/d"),
    array("idpersonne" => 4668, "libelleautre" => "ITA CDD"),
    array("idpersonne" => 3105, "libelleautre" => "n/d"),
    array("idpersonne" => 3106, "libelleautre" => "n/d"),
    array("idpersonne" => 5843, "libelleautre" => "n/d"),
    array("idpersonne" => 6277, "libelleautre" => "Accueil "),
    array("idpersonne" => 4375, "libelleautre" => "Stagiaire"),
    array("idpersonne" => 4560, "libelleautre" => "Accueil Partenariat"),
    array("idpersonne" => 6283, "libelleautre" => "Accueil "),
    array("idpersonne" => 2797, "libelleautre" => "Accueil"),
    array("idpersonne" => 3669, "libelleautre" => "IT"),
    array("idpersonne" => 6291, "libelleautre" => "n/d"),
    array("idpersonne" => 4060, "libelleautre" => "IT"),
    array("idpersonne" => 4459, "libelleautre" => "n/d"),
    array("idpersonne" => 4490, "libelleautre" => "Accueil Industriel"),
    array("idpersonne" => 6056, "libelleautre" => "Accueil"),
    array("idpersonne" => 6322, "libelleautre" => "Stagiaire"),
    array("idpersonne" => 4816, "libelleautre" => "Accueil"),
    array("idpersonne" => 4406, "libelleautre" => "alternance"),
    array("idpersonne" => 4080, "libelleautre" => "Accueil Partenariat"),
    array("idpersonne" => 4510, "libelleautre" => "IT"),
    array("idpersonne" => 3673, "libelleautre" => "IT"),
    array("idpersonne" => 6242, "libelleautre" => "Accueil"),
    array("idpersonne" => 5254, "libelleautre" => "IT"),
    array("idpersonne" => 3672, "libelleautre" => "Accueil"),
    array("idpersonne" => 5734, "libelleautre" => "Accueil"),
    array("idpersonne" => 3785, "libelleautre" => "Accueil"),
    array("idpersonne" => 4460, "libelleautre" => "Accueil"),
    array("idpersonne" => 5265, "libelleautre" => "Accueil"),
    array("idpersonne" => 5499, "libelleautre" => "Chercheur contractuel"),
    array("idpersonne" => 4444, "libelleautre" => "Accueil"),
    array("idpersonne" => 6057, "libelleautre" => "Accueil Partenariat"),
    array("idpersonne" => 4236, "libelleautre" => "Accueil"),
    array("idpersonne" => 6286, "libelleautre" => "Accueil "),
    array("idpersonne" => 6055, "libelleautre" => "FINI"),
    array("idpersonne" => 4414, "libelleautre" => "Chercheur contractuel"),
    array("idpersonne" => 4078, "libelleautre" => "n/d"),
    array("idpersonne" => 6058, "libelleautre" => "IT"),
    array("idpersonne" => 6013, "libelleautre" => "Chercheur contractuel"),
    array("idpersonne" => 4531, "libelleautre" => "Accueil Partenariat"),
    array("idpersonne" => 2357, "libelleautre" => "alternance"),
    array("idpersonne" => 4597, "libelleautre" => "n/d"),
    array("idpersonne" => 4440, "libelleautre" => "IT"),
    array("idpersonne" => 4096, "libelleautre" => "Accueil"),
    array("idpersonne" => 4494, "libelleautre" => "IT"),
    array("idpersonne" => 6331, "libelleautre" => "Accueil"),
    array("idpersonne" => 1638, "libelleautre" => "Accueil"),
    array("idpersonne" => 5807, "libelleautre" => "IT"),
    array("idpersonne" => 5731, "libelleautre" => "Accueil"),
    array("idpersonne" => 2826, "libelleautre" => "n/d"),
    array("idpersonne" => 6185, "libelleautre" => "CR INP"),
    array("idpersonne" => 6120, "libelleautre" => "Thèse LPICM"),
    array("idpersonne" => 5991, "libelleautre" => "Thèse"),
    array("idpersonne" => 4838, "libelleautre" => "Thèse"),
    array("idpersonne" => 6458, "libelleautre" => "Thèse"),
    array("idpersonne" => 5127, "libelleautre" => "Thèse"),
    array("idpersonne" => 6460, "libelleautre" => "Thèse"),
    array("idpersonne" => 6085, "libelleautre" => "CDD")
);

 for ($i = 0; $i < count($arrayAutreId); $i++) {
    $manager->getRequete("update personneaccueilcentrale set idpersonnequalite = 4 where idpersonneaccueilcentrale=?", array($arrayAutreId[$i]['idpersonne']));
    $manager->getRequete("update personneaccueilcentrale set idqualitedemandeuraca_qualitedemandeuraca = 3 where idpersonneaccueilcentrale=?", array($arrayAutreId[$i]['idpersonne']));
    $idautresqualite = $manager->getSingle("select max(idautresqualite) from autresqualite")+1;
    $libelleautresqualite = $arrayAutreId[$i]['libelleautre'];
    $autreQualite = new Autresqualite($idautresqualite, $libelleautresqualite);
    $manager->addAutresQualite($autreQualite);
    $manager->getRequete("update personneaccueilcentrale set idautresqualite =? where idpersonneaccueilcentrale=?", array($idautresqualite,$arrayAutreId[$i]['idpersonne']));
}

BD::deconnecter();