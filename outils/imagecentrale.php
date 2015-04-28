<?php
session_start();
include_once '../outils/constantes.php';
include_once '../class/Manager.php';
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
$db = BD::connecter();
$extensions = array('.jpg', '.JPG', '.jpeg', '.JPEG', '.png', '.PNG');
$src = $manager->getSingle2("select logocentrale from rapport where idprojet=?", $_POST['idprojet']);
if (empty($src)) {
    $src = $manager->getSingle2("select adresselogcentrale from sitewebapplication where refsiteweb = (SELECT libellecentrale from centrale,concerne where idcentrale_centrale=idcentrale and idprojet_projet=?)", $_POST['idprojet']);
    $rep = '';
    $arrayInfoImg = getimagesize("../" . $src);
    if ($arrayInfoImg[0] > 160) {
        $f = 160 / $arrayInfoImg[0];
        $width = $f * $arrayInfoImg[0];
        $height = $f * $arrayInfoImg[1];
    } else {
        $width = $arrayInfoImg[0];
        $height = $arrayInfoImg[1];
    }
} else {
    $rep = '/uploadlogo';
    $arrayInfoImg = getimagesize("../uploadlogo/" . $src);
    if ($arrayInfoImg[0] > 160) {
        $f = 160 / $arrayInfoImg[0];
        $width = $f * $arrayInfoImg[0];
        $height = $f * $arrayInfoImg[1];
    } else {
        $width = $arrayInfoImg[0];
        $height = $arrayInfoImg[1];
    }
}

$aleatoire = rand(0, 10000);
?>
<script>
    document.getElementById("restaureLogoCentrale").value = "";
    require(["dijit/form/Button", "dojo/dom", "dojo/domReady!"], function (Button, dom) {
        var myButton = new Button({
            label: "Restore previous",
            onClick: function () {
                require(["dojo/dom-construct"], function(domConstruct){domConstruct.destroy("file2Rapport");});
                document.getElementById("errImg").value = "";
                document.getElementById("restaureLogoCentrale").value = "ok";                
                dom.byId("cibleLogocentrale").innerHTML = "<img src='<?php echo '/' . REPERTOIRE . $rep . '/' . $src; ?>' width='<?php echo $width; ?>' height='<?php echo $height; ?>'  />";
            }
        }, "<?php echo $aleatoire; ?>").startup();
    });
</script>
<?php
$logorapportcentrale = $manager->getSingle2("SELECT r.logocentrale FROM projet p, rapport r WHERE  p.idprojet = r.idprojet and p.numero=?", $_SESSION['numProjet']);
if (is_array($_FILES)) {
    $boutonProgr = '<div id="btnRestore" style="margin-top:-15px;margin-left:196px"><button  id="' . $aleatoire . '" type="button" ></button></div>';
    if (is_uploaded_file($_FILES['logorapportcentrale']['tmp_name'])) {
        $sourcePath = $_FILES['logorapportcentrale']['tmp_name'];
        $targetPath = "../uploadlogo/" . $_FILES['logorapportcentrale']['name'];
        $extensionlogocentrale = strrchr($_FILES['logorapportcentrale']['name'], '.');
        if (move_uploaded_file($sourcePath, $targetPath)) {//CALCUL DES DIMENSIONS DU LOGO                
            $arrayInfoImg = getimagesize($targetPath);
            if (!in_array($extensionlogocentrale, $extensions)) {
                echo '<script>document.getElementById("errImg").value="100";</script>';
                echo '<script>document.getElementById("cibleLogocentrale").style.width="380px";</script>';
                echo '<script>document.getElementById("cibleLogocentrale").style.height="165px";</script>';
                echo '<div style="width:160px;height:130px;padding-top:50px;color:red;font-style:bold">Error!The file must be jpg,jpeg or png</b></div>';
                echo $boutonProgr;
                exit();
            } elseif ($arrayInfoImg[0] > 800) {
                echo '<script>document.getElementById("errImg").value="100";</script>';
                echo '<script>document.getElementById("cibleLogocentrale").style.width="380px";</script>';
                echo '<script>document.getElementById("cibleLogocentrale").style.height="165px";</script>';
                echo '<div style="width:160px;height:130px;padding-top:50px;color:red;font-style:bold">Error! Width > 800px</b></div>';
                echo $boutonProgr;
                exit();
            } elseif ($arrayInfoImg[1] > 630) {
                echo '<script>document.getElementById("errImg").value="100";</script>';
                echo '<script>document.getElementById("cibleLogocentrale").style.width="380px";</script>';
                echo '<script>document.getElementById("cibleLogocentrale").style.height="165px";</script>';
                echo '<div style="width:170px;height:130px;padding-top:50px;color:red;font-style:bold">Error! Height > 630px</b></div>';
                echo $boutonProgr;
                exit();
            } elseif (filesize($targetPath) > 256000) { //250KO                
                echo '<script>document.getElementById("errImg").value="100";</script>';
                echo '<script>document.getElementById("cibleLogocentrale").style.width="380px";</script>';
                echo '<div style="width:170px;height:130px;padding-top:50px;color:red;font-style:bold">Error! weight > 250ko</b></div>';
                echo $boutonProgr;
                exit();
            } else {
                echo '<script>document.getElementById("errImg").value="";</script>';
                echo '<script>document.getElementById("btnRestore").style.marginTop = "15px";</script>';
            }
            if ($arrayInfoImg[0] > 160) {
                $f = 160 / $arrayInfoImg[0];
                $width = 160;
                $height = $f * $arrayInfoImg[1];
            } else {
                $width = $arrayInfoImg[0];
                $height = $arrayInfoImg[1];
            }
        }
        ?>
        <img  src="<?php echo '/' . REPERTOIRE . '/uploadlogo/' . $_FILES['logorapportcentrale']['name']; ?>" width="<?php echo $width . 'px'; ?>" height="<?php echo $height . 'px'; ?>" />
        <?php
        echo $boutonProgr;
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
        <img  src="<?php echo '/' . REPERTOIRE . '/uploadlogo/' . $logorapportcentrale; ?>" width="<?php echo $width . 'px'; ?>" height="<?php echo $height . 'px'; ?>"  />
        <?php
    } else {
        if (isset($_POST['idprojet'])) {
            $idprojet = $_POST['idprojet'];
        } elseif (isset($_SESSION['numProjet'])) {
            $idprojet = $manager->getSingle2("select idprojet from projet where numero=?", $_SESSION['numProjet']);
        }

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
        <img  src="<?php echo '/' . REPERTOIRE . '/' . $logorappport; ?>" width="<?php echo $width . 'px'; ?>" height="<?php echo $height . 'px'; ?>"  />
            <?php
    }
    $db = BD::deconnecter();
}     