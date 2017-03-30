<?php
include_once 'Chiffrement.php';
if (is_file('../outils/affichelibelle.php')) {
    include_once '../outils/affichelibelle.php';
} elseif (is_file('outils/affichelibelle.php')) {
    include_once 'outils/affichelibelle.php';
}

function str_to_utf8($str) {
    if (mb_detect_encoding($str, 'UTF-8', true) === false) {
        $str = utf8_encode($str);
    } else {
        $str = utf8_decode($str);
    }
    return $str;
}

class BD {
    public static $connexion = null;
    public static $dsn;
    public static $username;
    public static $passwd;

    public static function connecter() {
        $username = Chiffrement::decrypt('WWjgTm10g1166PBvO7iW7g==');
        $passwd = Chiffrement::decrypt('dx07jTMsCC+oUXKCdZjayg==');
        $repertoire = explode('/', $_SERVER['PHP_SELF']);

        if ($repertoire[1] == 'projet-dev' || $repertoire[1] == 'projet-prod') {
            $dsn = Chiffrement::decrypt("f5OWYoqO9uAEEsudTqVbfratnjOZiXJDlS+GS9fdHjM+yn2phrKnHwfE7wEWVZnv4cgXRUiJWZXBkBaraCmbeA==");
        } elseif ($repertoire[1] == 'projet-test') {
            $dsn = Chiffrement::decrypt('f5OWYoqO9uAEEsudTqVbfratnjOZiXJDlS+GS9fdHjM+yn2phrKnHwfE7wEWVZnvPzcD+VlYxo5S3deQVYqp2g==');
        } elseif ($repertoire[1] == 'projet') {
            $dsn = Chiffrement::decrypt('f5OWYoqO9uAEEsudTqVbfratnjOZiXJDlS+GS9fdHjM+yn2phrKnHwfE7wEWVZnvs4pdvpEib2BLskZMZZDWNQ==');
        } elseif ($_SERVER['SERVER_NAME'] == 'sultan.alwaysdata.net') {//SERVEUR DE TEST A SUPPRIMER
            $username = Chiffrement::decrypt('aeRhTyxkmqSqpwzjXPxmvQ==');
            $passwd = Chiffrement::decrypt('JRcxYfSnMGMHAdb3g9xSTQ==');
            $dsn = Chiffrement::decrypt("f5OWYoqO9uAEEsudTqVbfryBBfNAGX79mdQt0KCvr4TH0bQXINWuYvUan+3w7YDH4wOyKmk+UAhiF1sgYa+0i9oFOBwWXrA0Dp2cOj/2y6E=");
        } elseif ($_SERVER['SERVER_NAME'] == 'sultandev.alwaysdata.net') {//SERVEUR DE TEST A SUPPRIMER
            $username = "Fw7DZ1hR9FuV1Ns+MxGcBw==";
            $passwd = "3fEpqrO0ES38ODx0vVPr1w==";
            $dsn = "f5OWYoqO9uAEEsudTqVbfryBBfNAGX79mdQt0KCvr4TH0bQXINWuYvUan+3w7YDHhuugOpaS7w6t1tWBXANYBTPkaJSSWc3iHOsjqkUYn/Y=";
        }
        if (is_file('../outils/toolBox.php')) {
            include_once '../outils/toolBox.php';
        } elseif (is_file('outils/toolBox.php')) {
            include_once 'outils/toolBox.php';
        }
        showError($_SERVER['PHP_SELF']);

        try {
            self::$connexion = new PDO($dsn, $username, $passwd);
        } catch (PDOException $ex) {
            ?>
            <div id="global" style="margin-top: -8px;margin-left:472px;">
                <div><img alt="" src="styles/img/BannerHaut.gif" style="border:1px solid #CCCCCC;border-radius: 5px;" />
                </div>
                <table>
                    <tr><td><br><br></td></tr>
                    <tr>
                        <td>
                            <fieldset id='droit' style="border-color: #5D8BA2;width:1003px;padding:20px 20px 20px 20px;border:solid 1px #5D8BA2;color:midnightblue;font-family:verdana;
                                      -moz-border-radius: 5px;-khtml-border-radius: 5px;-webkit-border-radius: 5px;border-radius: 5px 5px 5px 5px;">
                                <legend style="font-size:1.2em;color: red">
                                    <?php
                                    if (isset($_GET['lang'])) {
                                        $lang = $_GET['lang'];
                                    } else {// si aucune langue n'est déclarée on tente de reconnaitre la langue par défaut du navigateur
                                        $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
                                    }
                                    echo 'Error!';
                                    ?>
                                </legend>

                                <div style="font-size:1.1em">
                                    <?php
                                    echo 'Problem connecting to the database';
                                    ?>
                                </div>
                            </fieldset>
                        </td>
                    </tr>
                </table>
                <p id="footer" style="font-size: 11px; font-style: italic;width: 1013px;"><?php echo TXT_VERSION . '<br>'; ?>
            </div>
            </body>
            </html>
            <?php
        }
        return self::$connexion;
    }

    public static function deconnecter() {
        if (!is_null(self::$connexion)) {
            self::$connexion = null;
        }
    }

}
