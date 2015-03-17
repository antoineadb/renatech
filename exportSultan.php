<?php

session_start();
include 'decide-lang.php';
include 'class/Manager.php';
include_once 'outils/toolBox.php';
include_once 'outils/constantes.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER

if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
    $pseudo = $_SESSION['pseudo'];
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
//------------------------------------------------------------------------
//-----RECUPERATION DU LIBELLE DE LA CENTRALE DU RESPONSABLE CENTRALE-----
//------------------------------------------------------------------------
$idcentrale = $manager->getSingle2("SELECT idcentrale FROM centrale , utilisateur ,loginpassword  WHERE
idcentrale_centrale = idcentrale AND idlogin_loginpassword = idlogin AND pseudo=?", $pseudo);


$data = utf8_decode("Type de projet;thématique;source de financement;Titre du projet;numero;demandeur du projet;Porteur(s) du projet;Administrateur(s) du projet;");
$data .= "\n";
//SUPPRESSION DE LA TABLE TEMPORAIRE SI ELLE EXISTE
//CREATION DE LA TABLE TEMPORAIRE
$row = $manager->getList2("
SELECT p.titre,p.idprojet,p.numero,p.dureeprojet,s.idstatutprojet,u.nom as demandeur,t.libelletype,th.libellethematique
FROM projet p,creer c,utilisateur u,concerne,loginpassword l,centrale ce,statutprojet s, typeprojet t, thematique th
WHERE p.idprojet = c.idprojet_projet AND c.idutilisateur_utilisateur = u.idutilisateur AND concerne.idprojet_projet = p.idprojet AND
concerne.idcentrale_centrale = ce.idcentrale AND l.idlogin = u.idlogin_loginpassword AND s.idstatutprojet = concerne.idstatutprojet_statutprojet
AND t.idtypeprojet=p.idtypeprojet_typeprojet AND th.idthematique=p.idthematique_thematique
AND ce.idcentrale =?", $idcentrale);
$nbrow = count($row);
if ($nbrow == 0) {
    echo ' <script>alert("' . utf8_decode(TXT_PASDEPROJET) . '");window.location.replace("/' . REPERTOIRE . '/compte/' . $lang . '")</script>';
    exit();
} else {
    for ($i = 0; $i < $nbrow; $i++) {
        $arrayadmin = $manager->getList2("SELECT u.nom  FROM utilisateuradministrateur up,utilisateur u,projet p WHERE  up.idprojet = p.idprojet AND up.idutilisateur = u.idutilisateur and p.idprojet=?", $row[$i]['idprojet']);
            $administrateur='';
            for ($index = 0; $index < count($arrayadmin); $index++) {
                if(!empty($arrayadmin[$index]['nom'])){
                    $administrateur.=  $arrayadmin[$index]['nom'].'-';
                }
            }$admin=  cleanForExport(substr(trim($administrateur), 0, -1));
            
      $arrayporteur = $manager->getList2("SELECT u.nom  FROM utilisateurporteurprojet up,utilisateur u,projet p WHERE  up.idprojet_projet = p.idprojet AND up.idutilisateur_utilisateur = u.idutilisateur and p.idprojet=?", $row[$i]['idprojet']);
            $porteur='';
            for ($index = 0; $index < count($arrayporteur); $index++) {
                if(!empty($arrayporteur[$index]['nom'])){
                    $porteur.=  $arrayporteur[$index]['nom'].'-';
                }
            }$porteurs=cleanForExport(substr(trim($porteur), 0, -1));
        $demandeur =  cleanForExportOther($row[$i]['demandeur']);
//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              SOURCE DE FINANCEMENT
//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
       $s_Sourcefinancement = '';        
        $arraysourcefinancement = $manager->getList2("SELECT libellesourcefinancement FROM sourcefinancement,projetsourcefinancement WHERE idsourcefinancement_sourcefinancement = idsourcefinancement AND idprojet_projet =?", $row[$i]['idprojet']);
        $nbarraysf = count($arraysourcefinancement);
        for ($k = 0; $k < $nbarraysf; $k++) {            
            if ($arraysourcefinancement[$k]['libellesourcefinancement'] != 'Autres') {
                $s_Sourcefinancement .= $arraysourcefinancement[$k]['libellesourcefinancement'] . ' / ';
            }
        }
        if ($nbarraysf > 0) {
            $s_Sourcefinancement = cleanForExportOther(substr($s_Sourcefinancement, 0, -2));
        } else {
            $s_Sourcefinancement = '';
        }
        $originalDate = date('d-m-Y');
        $data .= "" .
                cleanForExportOther($row[$i]['libelletype']) . ";" .
                cleanForExportOther($row[$i]['libellethematique']). ";" .
                $s_Sourcefinancement . ";" .
                cleanForExportOther($row[$i]['titre']) . ";" .
                $row[$i]['numero'] . ";" .
                $demandeur.";".
                $porteur . ";" .
                $admin . "\n";
}
        $libcentrale = $manager->getSingle2("SELECT libellecentrale FROM loginpassword,centrale,utilisateur WHERE idlogin_loginpassword = idlogin AND idcentrale_centrale = idcentrale AND pseudo=?", $pseudo);        
// Déclaration du type de contenu
        header("Content-type: application/vnd.ms-excel;charset=UTF-8");
        header("Content-disposition: attachment; filename=exportprojetcentrale_" . $libcentrale . '_' . $originalDate . ".csv");
        print $data;
        exit;
    
}

BD::deconnecter();
