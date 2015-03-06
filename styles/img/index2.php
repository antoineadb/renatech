<?php session_start(); ?>
<!-- [EN-TETE] -->
 <!-- Formulaire $_SERVER['PHP_SELF'] signifie que le traitement du formulaire se fait sur la même page -->
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <p>
        <!-- Image dynamique -->
        <img src="captcha.png" alt="Captcha" id="captcha" />
 
        <!-- (JavaScript) Recharge l'image car elle n'existe pas dans le cache du navigateur, grâce à l'id généré  -->
        <img src="reload.png" alt="Recharger l'image" title="Recharger l'image" style="cursor:pointer;position:relative;top:-7px;" onclick="document.images.captcha.src='captcha.php?id='+Math.round(Math.random(0)*1000)" />
    </p>
    <p>
        <?php
        $class = 'empty';
        // Si le formulaire a été soumis
        if(isset($_REQUEST['submit'])) {
            // Si l'utilisateur a bien entré un code
            if (!empty($_REQUEST['code'])) {
                // Conversion en majuscules
                $code = strtoupper($_REQUEST['code']);
 
                // Cryptage et comparaison avec la valeur stockée dans $_SESSION['captcha']
                if( md5($code) == $_SESSION['captcha'] ) {
                    $class = 'correct'; // Le code est bon
                } else {
                    $class = 'incorrect'; // Le code est erroné
                }
            } else {
                $class = 'incorrect'; // Aucun code
            }
        }
        echo '<input name="code" class="' . $class . '" type="text" />';
        ?>
        <input type="submit" name="submit" value="V&eacute;rifier" />
    </p>
</form>
 
<!-- [PIED DE PAGE] -->