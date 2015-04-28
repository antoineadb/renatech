<?php
session_start();
include_once '../outils/constantes.php';
include_once '../class/Manager.php';
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
$db = BD::connecter();
$extensions = array('.jpg', '.JPG', '.jpeg', '.JPEG', '.png', '.PNG');
$logorapport = $manager->getSingle2("SELECT r.logo FROM projet p, rapport r WHERE  p.idprojet = r.idprojet and p.numero=?", $_SESSION['numProjet']);

if (empty($logorapport)) {
    $logorapport = $manager->getSingle2("select adresselogcentrale from sitewebapplication where refsiteweb = (SELECT libellecentrale from centrale,concerne where idcentrale_centrale=idcentrale and idprojet_projet=?)", $_POST['idprojet']);
    $rep = '';
    $arrayInfoImg = getimagesize("../" . $logorapport);
    if ($arrayInfoImg[0] > 160) {
        $f = 160 / $arrayInfoImg[0];
        $width = 160;
        $height = $f * $arrayInfoImg[1];
    } else {
        $width = $arrayInfoImg[0];
        $height = $arrayInfoImg[1];
    }
} else {
    $rep = '/uploadlogo';
    $arrayInfoImg = getimagesize("../uploadlogo/" . $logorapport);
    if ($arrayInfoImg[0] > 160) {
        $f = 160 / $arrayInfoImg[0];
        $width = 160;
        $height = $f * $arrayInfoImg[1];
    } else {
        $width = $arrayInfoImg[0];
        $height = $arrayInfoImg[1];
    }
}

$aleatoire = rand(0, 10000);
?>
<script>
    document.getElementById("restaureLogo").value = "";
    require(["dijit/form/Button", "dojo/dom", "dojo/domReady!"], function (Button, dom) {        
        var myButton = new Button({
            label: "Restore previous",
            onClick: function () {
                require(["dojo/dom-construct"], function(domConstruct){domConstruct.destroy("fileRapport");});
                document.getElementById("errImg").value = "";
                document.getElementById("restaureLogo").value = "ok";
                dom.byId("cibleLogo").innerHTML = "<img src='<?php echo '/' . REPERTOIRE . $rep . '/' . $logorapport; ?>' width='<?php echo $width; ?>' height='<?php echo $height; ?>'  />";
            }
        }, "<?php echo $aleatoire; ?>").startup();

    });

</script>
<?php
if (is_array($_FILES)) {
    $boutonProgr = '<div id="btnRestore2" style="margin-bottom:-35px;margin-left:196px"><button  id="' . $aleatoire . '" type="button" ></button></div>';
    if (is_uploaded_file($_FILES['logorapport']['tmp_name'])) {
        $sourcePath = $_FILES['logorapport']['tmp_name'];
        $targetPath = "../uploadlogo/" . $_FILES['logorapport']['name'];
        $extensionlogo = strrchr($_FILES['logorapport']['name'], '.');
        if (move_uploaded_file($sourcePath, $targetPath)) {//CALCUL DES DIMENSIONS DU LOGO                
            $arrayInfoImg = getimagesize($targetPath);
            if (!in_array($extensionlogo, $extensions)) {
                echo '<script>document.getElementById("errImg").value="100";</script>';
                echo '<script>document.getElementById("cibleLogo").style.width="380px";</script>';
                echo '<script>require(["dojo/dom-construct"], function(domConstruct){domConstruct.destroy("fileRapport");});</script>';
                echo '<br><br><b style="color:red">The file must be jpg,jpeg or png</b><br><br>';
                echo $boutonProgr;
                exit();
            } elseif ($arrayInfoImg[0] > 800) {
                echo '<script>document.getElementById("errImg").value="100";</script>';
                echo '<script>document.getElementById("cibleLogo").style.width="380px";</script>';
                echo '<script>require(["dojo/dom-construct"], function(domConstruct){domConstruct.destroy("fileRapport");});</script>';
                echo '<br><br><b style="color:red">Error! Width > 800px</b><br><br>';
                echo $boutonProgr;
                exit();
            } elseif ($arrayInfoImg[1] > 630) {
                echo '<script>document.getElementById("errImg").value="100";</script>';
                echo '<script>document.getElementById("cibleLogo").style.width="380px";</script>';
                echo '<script>require(["dojo/dom-construct"], function(domConstruct){domConstruct.destroy("fileRapport");});</script>';
                echo '<br><br><b style="color:red">Error! Height > 630px</b><br><br>';
                echo $boutonProgr;
                exit();
            } elseif (filesize($targetPath) > 256000) { //250KO                
                echo '<script>document.getElementById("errImg").value="100";</script>';
                echo '<script>document.getElementById("cibleLogo").style.width="380px";</script>';
                echo '<script>require(["dojo/dom-construct"], function(domConstruct){domConstruct.destroy("fileRapport");});</script>';
                echo '<br><br><b style="color:red">Error! weight > 250ko</b><br><br>';
                echo $boutonProgr;
                exit();
            } else {
                echo '<script>document.getElementById("errImg").value="";</script>';
                echo '<script>document.getElementById("btnRestore2").style.marginTop = "15px";</script>';
            }
            if ($arrayInfoImg[0] > 160) {
                $f = 160 / $arrayInfoImg[0];
                $width = 160;
                $height = $f * $arrayInfoImg[1];
            } else {
                $width = $arrayInfoImg[0];
                $height = $arrayInfoImg[1];
            }
            ?>
            <img id='imgLogo' src="<?php echo '/' . REPERTOIRE . '/uploadlogo/' . $_FILES['logorapport']['name']; ?>" width="<?php echo $width . 'px'; ?>" height="<?php echo $height . 'px'; ?>" />
            <?php
             echo $boutonProgr;
        }
    } else if (!empty($logorapport)) {
        if(is_file('../'.$logorapport.'')){
            $arrayInfoImg = getimagesize('../' . $logorapport);
            if ($arrayInfoImg[0] > 160) {
                $f = 160 / $arrayInfoImg[0];
                $width = $f * $arrayInfoImg[0];
                $height = $f * $arrayInfoImg[1];
            } else {
                $width = $arrayInfoImg[0];
                $height = $arrayInfoImg[1];                
            }
            ?><img id='imgLogo' src="<?php echo '/' . REPERTOIRE . '/' . $logorapport; ?>" width="<?php echo $width . 'px'; ?>" height="<?php echo $height . 'px'; ?>" />  <?php
        }else{
            $arrayInfoImg = getimagesize('../uploadlogo/' . $logorapport);
            if ($arrayInfoImg[0] > 160) {
                $f = 160 / $arrayInfoImg[0];
                $width = $f * $arrayInfoImg[0];
                $height = $f * $arrayInfoImg[1];
            } else {
                $width = $arrayInfoImg[0];
                $height = $arrayInfoImg[1];
            }
            ?><img id='imgLogo' src="<?php echo '/' . REPERTOIRE . '/uploadlogo/' . $logorapport; ?>" width="<?php echo $width . 'px'; ?>" height="<?php echo $height . 'px'; ?>" />  <?php
        }
    } else {
        $idprojet = $manager->getSingle2("select idprojet from projet where numero=?", $_SESSION['numProjet']);
        $logorapport = $manager->getSingle2("select adresselogcentrale from sitewebapplication where refsiteweb = (SELECT libellecentrale from centrale,concerne where idcentrale_centrale=idcentrale and idprojet_projet=?)", $idprojet);
        $chemin = '/' . REPERTOIRE . '/' . $logorapport;
        $arrayInfoImg = getimagesize('../' . $logorapport);
        if ($arrayInfoImg[0] > 160) {
            $f = 160 / $arrayInfoImg[0];
            $width = $f * $arrayInfoImg[0];
            $height = $f * $arrayInfoImg[1];
        } else {
            $width = $arrayInfoImg[0];
            $height = $arrayInfoImg[1];
        }
        ?>
        <img id='imgLogo' src="<?php echo '/' . REPERTOIRE . '/uploadlogo/' . $logorapport; ?>" width="<?php echo $width . 'px'; ?>" height="<?php echo $height . 'px'; ?>" />
        <?php
    }
}
$db = BD::deconnecter();
