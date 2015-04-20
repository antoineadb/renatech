<?php
session_start();
include_once '../outils/constantes.php';
include_once '../class/Manager.php';
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
$db = BD::connecter();
$logorapportcentrale = $manager->getSingle2("SELECT r.logocentrale FROM projet p, rapport r WHERE  p.idprojet = r.idprojet and p.numero=?", $_SESSION['numProjet']);
if (is_array($_FILES)) {
    if (is_uploaded_file($_FILES['logorapportcentrale']['tmp_name'])) {
        $sourcePath = $_FILES['logorapportcentrale']['tmp_name'];
        //echo filesize(nomFichierValide($_FILES['logorapportcentrale']['tmp_name']));die;
        
        $targetPath = "../uploadlogo/" . $_FILES['logorapportcentrale']['name'];
        if (move_uploaded_file($sourcePath, $targetPath)) {
            //CALCUL DES DIMENSIONS DU LOGO                
            $arrayInfoImg = getimagesize($targetPath);
            if ($arrayInfoImg[0] > 800){
                echo 'Error! The width of the logo is > 800px';                
                exit();
            }elseif ($arrayInfoImg[1] > 630){
                echo 'Error! The height of the logo is > 630px';
                exit();
            }elseif($arrayInfoImg[1] > 630){
                echo 'Error! The height of the logo is > 630px';
                exit();
            }
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
    } else if (!empty($logorapportcentrale)) {
        $chemin = '/' . REPERTOIRE . '/uploadlogo/' . $logorapportcentrale;
        $arrayInfoImg = getimagesize('../uploadlogo/' . $logorapportcentrale);
        if ($arrayInfoImg[0] > 160) {
            $f = 160 / $arrayInfoImg[0];
            $width = $f * $arrayInfoImg[0];
            $height = $f * $arrayInfoImg[1];
        } else {
            $width = $arrayInfoImg[0];
            $height = $arrayInfoImg[1];
        }
        ?>
        <img src="<?php echo '/' . REPERTOIRE . '/uploadlogo/' . $logorapportcentrale; ?>" width="<?php echo $width . 'px'; ?>" height="<?php echo $height . 'px'; ?>" />
        <?php
    } else {
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

     