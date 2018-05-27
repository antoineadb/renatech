<?php
session_start();
include 'class/Securite.php';
include_once 'decide-lang.php';
include_once 'class/Manager.php';
include_once 'outils/constantes.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
if (isset($_POST['page_precedente']) && $_POST['page_precedente'] == 'createLogin.html') {
    $_SESSION['page_precedente'] = $_POST['page_precedente'];
    if (isset($_POST['email1'])) {
        $_SESSION['mail'] = Securite::bdd($_POST['email1']);
        unset($_SESSION['email']);
    }
    if (isset($_POST['mot_de_passe_1'])) {
        $_SESSION['mot_de_passe_1'] = sha1($_POST['mot_de_passe_1']);
        unset($_SESSION['passe']);
    }
    if (isset($_POST['pseudo'])) {
        $_SESSION['pseudo'] = Securite::bdd($_POST['pseudo']);
    }
    
    $_SESSION['lastLoad'] = time();
} else {
    if (!isset($_SESSION['page_precedente'])) {
        header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
        exit();
    }
}
//2EME IL FAUT VERIFIER QUE LE LOGIN (PSEUDO) N'EXISTE PAS DEJA DANS LA BASE DE DONNEE SI IL EXISTE ON SORT (IL DOIT ETRE UNIQUE)
if (isset($_POST['pseudo'])) {
    $idLoginpassword = $manager->getSingle2("SELECT idlogin from loginpassword where pseudo=?", $_POST['pseudo']);
    if (!empty($idLoginpassword)) {
        header('Location: /' . REPERTOIRE . '/loginDblErr/' . $lang);
        exit(); //FIN DE LA VERIFICATION
    }
}
//1ER  VERIFICATION QUE L'EMAIL UTILISE N'EST PAS DEJA DANS LA TABLE LOGINPASSWORD ET DANS LE LE CHAMP EMAIL1 DE LA TABLE CENTRALE
if (empty($_SESSION['validEmail'])) {
    if (!empty($_POST['email1'])) {
        for ($i = 1; $i < 6; $i++) {
            $idrespcentrale = $manager->getSingle2("select idcentrale from centrale where email" . $i . "=?", $_POST['email1']);            
            if (!empty($idrespcentrale)) {//SI IL N'EST PAS VIDE ON VERIFIE QU'IL N'EST PAS DEJA DANS CREEE
                $emailloginpassword = $manager->getSingle2("select idlogin from loginpassword where mail=?", $_POST['email1']);
                if (!empty($emailloginpassword)) {
                    header('Location: /' . REPERTOIRE . '/loginadmnErr/' . $lang . '/' . $idrespcentrale);
                    exit(); //FIN DE LA VERIFICATION
                }
            }
        }
        $idadminNational = $manager->getSingle2("select idadminnational from adminnational where emailadminnational=?", $_POST['email1']);
        if(!empty($idadminNational)){            
            header('Location: /' . REPERTOIRE . '/loginadmnNatErr/' . $lang . '/' . $idadminNational);
            exit(); //FIN DE LA VERIFICATION
        }
        
        if (empty($idrespcentrale)) {
            $_SESSION['logindoublon'] = '';
            $arraylogin = $manager->getList2("select pseudo  from loginpassword where mail=?", $_POST['email1']);
            if (count($arraylogin) > 0) {
                if (count($arraylogin) == 1) {
                    $_SESSION['logindoublon'] = $arraylogin[0]['pseudo'];
                    header('Location: /' . REPERTOIRE . '/emailDblErr/' . $lang);
                } else {
                    $login = '';
                    for ($i = 0; $i < count($arraylogin); $i++) {
                        $login.=$arraylogin[$i]['pseudo'] . ', ';
                    }
                    $_SESSION['logindoublon'] = substr($login, 0, -2);
                    header('Location: /' . REPERTOIRE . '/emailDblErr/' . $lang);
                }
            }
        }
    }
}

include 'html/header.html';
?>
<div id="global">
    <?php include 'html/entete.html'; ?>
    <fieldset id="ident" style="border-color: #5D8BA2;width:1006px;padding-left: 20px;font-size:1.2em; margin-top: 50px;">
        <legend style="color: #5D8BA2;"><?php echo TXT_CAPTCHASECU; ?>
            <a class="infoBulle" href="#">&nbsp;<img src='<?php echo "/" . REPERTOIRE ?>/styles/img/help.gif'/><span style="width: 280px;text-align: center;border-radius:5px"><?php echo affiche('TXT_AIDECAPTCHA'); ?></span></a></legend>
        <div>
            <form method="post" action="<?php echo $_SERVER['PHP_SELF'] . '?lang=' . $lang; ?>">
                <p style="text-align: center"><!-- Image dynamique -->
                    <img src="<?php echo '/' . REPERTOIRE ?>/captcha/captcha.php"  alt="Captcha" id="captcha" style="width: 205px;" /><!-- (JavaScript) Recharge l'image car elle n'existe pas dans le cache du navigateur, grâce à l'id généré  -->
                    <img src="<?php echo '/' . REPERTOIRE ?>/captcha/reload.png" alt="Recharger l'image" title="Recharger l'image" style="cursor:pointer;position:relative;top:-15px;height:38px;width:38px"
                         onclick="document.images.captcha.src = '<?php echo "/" . REPERTOIRE ?>/captcha/captcha.php?id=' + Math.round(Math.random(0) * 1000)" />
                </p>
                <p style="text-align: center">
                    <?php
                    $class = 'empty'; // Si le formulaire a été soumis
                    if (isset($_REQUEST['submit'])) {// Si l'utilisateur a bien entré un code
                        if (!empty($_REQUEST['code'])) {// Conversion en majuscules
                            $code = strtoupper($_REQUEST['code']); // Cryptage et comparaison avec la valeur stockée dans $_SESSION['captcha']
                            if (md5($code) == $_SESSION['captcha']) {
                                $class = 'correct';// Le code est bon
                                 $_SESSION['validEmail']='';
                                echo '<script>window.location.replace("/'.REPERTOIRE.'/modifBase/insertLogin.php?lang=' . $lang . '")</script>';
                            }else{
                                $class = 'incorrect'; // Le code est erroné
                            }
                        } else {
                            $class = 'incorrect'; // Aucun code
                        }
                    }
                    echo '<input name="code" class="' . $class . '"  data-dojo-type="dijit/form/ValidationTextBox"  style="margin-left: 46px;   width: 204px;" />';
                    ?>
                    <input type="submit" name="submit" label="<?php echo TXT_VERIFIER ?>" data-dojo-Type="dijit.form.Button" >
                </p>
            </form>
        </div>
    </fieldset>
    <?php include 'html/footer.html'; ?>
</div>
</body>
</html>