<?php
session_start();
include_once '../outils/constantes.php';
if (is_array($_FILES)) {
    if (is_uploaded_file($_FILES['logorapportcentrale']['tmp_name'])) {
        $sourcePath = $_FILES['logorapportcentrale']['tmp_name'];
        $targetPath = "../uploadlogo/" . $_FILES['logorapportcentrale']['name'];
        if (move_uploaded_file($sourcePath, $targetPath)) {
            //CALCUL DES DIMENSIONS DU LOGO                
            $arrayInfoImg = getimagesize($targetPath);
            if ($arrayInfoImg[0] > 160) {
                $f = 160 / $arrayInfoImg[0];
                $width = $f * $arrayInfoImg[0];
                $height = $f * $arrayInfoImg[1];
            } else {
                $width = $arrayInfoImg[0];
                $height = $arrayInfoImg[1];
            }
            ?>
            <img src="<?php echo '/' . REPERTOIRE . '/uploadlogo/' . $_FILES['logorapportcentrale']['name']; ?>" width="<?php echo $width . 'px'; ?>" height="<?php echo $height . 'px'; ?>" />
            <?php
        }
    } else {
        include_once '../class/Manager.php';
        $manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
        $db = BD::connecter();
        $idprojet = $manager->getSingle2("select idprojet from projet where numero=?", $_SESSION['numProjet']);
        $logorappport = $manager->getSingle2("select adresselogcentrale from sitewebapplication where refsiteweb = (SELECT libellecentrale from centrale,concerne where idcentrale_centrale=idcentrale and idprojet_projet=?)", $idprojet);
        $chemin = '/' . REPERTOIRE . '/' . $logorappport;
        $arrayInfoImg = getimagesize('../' . $logorappport);
        if ($arrayInfoImg[0] > 160) {
            $f = 160 / $arrayInfoImg[0];
            $width = $f * $arrayInfoImg[0];
            $height = $f * $arrayInfoImg[1];
        } else {
            $width = $arrayInfoImg[0];
            $height = $arrayInfoImg[1];
        }    
    ?>
    <img src="<?php echo '/' . REPERTOIRE . '/' . $logorappport; ?>" width="<?php echo $width . 'px'; ?>" height="<?php echo $height . 'px'; ?>" />
    <?php
    }
    $db = BD::deconnecter();
}

     