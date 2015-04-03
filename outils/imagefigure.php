<?php
session_start();
include_once '../outils/constantes.php';
include_once '../class/Manager.php';
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
$db = BD::connecter();
$figure = $manager->getSingle2("SELECT r.figure FROM projet p, rapport r WHERE  p.idprojet = r.idprojet and p.numero=?", $_SESSION['numProjet']);
if (is_array($_FILES)) {
    if (is_uploaded_file($_FILES['figure']['tmp_name'])) {
        $sourcePath = $_FILES['figure']['tmp_name'];
        $targetPath = "../uploadlogo/" . $_FILES['figure']['name'];
        if (move_uploaded_file($sourcePath, $targetPath)) {
            //CALCUL DES DIMENSIONS DU LOGO                
            $arrayInfoImg = getimagesize($targetPath);
            if ($arrayInfoImg[0] > 1000) {
                $f = 1000 / $arrayInfoImg[0];
                $width = $f * $arrayInfoImg[0];
                $height = $f * $arrayInfoImg[1];
            } else {
                $width = $arrayInfoImg[0];
                $height = $arrayInfoImg[1];
            }
            ?>
            <img src="<?php echo '/' . REPERTOIRE . '/uploadlogo/' . $_FILES['figure']['name']; ?>" width="<?php echo $width . 'px'; ?>" height="<?php echo $height . 'px'; ?>" />
            <?php
        }
    } elseif (!empty($figure)) {
        $arrayInfoImg = getimagesize('../uploadlogo/'  . $figure);
        if ($arrayInfoImg[0] > 160) {
            $f = 160 / $arrayInfoImg[0];
            $width = $f * $arrayInfoImg[0];
            $height = $f * $arrayInfoImg[1];
        } else {
            $width = $arrayInfoImg[0];
            $height = $arrayInfoImg[1];
        }
        ?>
        <img src="<?php echo '/' . REPERTOIRE . '/uploadlogo/' . $figure; ?>" width="<?php echo $width . 'px'; ?>" height="<?php echo $height . 'px'; ?>" />
        <?php
    }
}
BD::deconnecter();
