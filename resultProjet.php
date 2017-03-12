<?php
session_start();
include_once 'outils/toolBox.php';
include_once 'outils/constantes.php';
include_once 'decide-lang.php';
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
include 'html/header.html';
include 'outils/parser.php';
?>
<div id="global">
    <?php include 'html/entete.html'; ?>
    <div style="margin-top:80px;font-size: 1.2em;width:1050px">
        <div>
            <?php
            include_once 'class/Manager.php';
            
            $db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
            $manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
            echo '<table><tr><td  style="border: 1px solid;width:1050px;height:35px;color:#CE043A" align=center><div  style="height:20px "><b>' . TXT_MESSAGEPROJETENCOURS . '</b></div></td><tr></table>';
            ?>
            <?php
            if (isset($_GET['idprojet'])) {
                $idprojet = $_GET['idprojet'];
                $_SESSION['idprojet'] = $idprojet;
            } else {
                $idprojet = $_SESSION['idprojet'];
            }
            $donneeprojet = $manager->getListbyArray("select numero,titre from projet where idprojet=?", array($idprojet));
            $numprojet = $donneeprojet[0]['numero'];
            $titreprojet = $donneeprojet[0]['titre'];
            ?>
            <p><?php echo TXT_RESULTDEMANDEPROJET . '<br>'; ?> </p>
            <p><?php echo TXT_RESULTTITREPROJET . '' . str_replace("''", "'", $titreprojet); ?></p>
            <p>
                <?php
                if (isset($idprojet)) {
                    echo '<table><tr><td style="width:280px">' . TXT_NUMEROPROJET . ' :' . '</td><td  style="border: 2px solid;width:150px;height:35px" align=center><div  style="height:17px "><b>' . $numprojet . '</b></div></td><tr></table>';
                }
                ?></p>
            <?php echo TXT_NOMBRECENTRALE . ':'; ?>
            <p>
                <?php
                //Récupération des libellés des centrales
                $idcentraleprojet = $manager->getdataArray("select idcentrale_centrale from concerne where idprojet_projet='" . $idprojet . "'");
                $idcentraleprojetObject = array();
                $string = '';
                for ($j = 0; $j < count($idcentraleprojet); $j++) {

                    $idcentraleprojetObject[] = $idcentraleprojet[$j];
                    $libellecentrale = $manager->getdataArray("select libellecentrale from centrale where idcentrale='" . $idcentraleprojet[$j] . "'");
                    $libellecentraleObject = array();

                    for ($k = 0; $k < count($libellecentrale); $k++) {
                        $libellecentraleObject[] = $libellecentrale[$k];
                        $string .=$libellecentraleObject[$k] . ' - ';
                    }
                }echo substr($string, 0, -2); //ENLEVE LE DERNIER CARACTERE
                ?>
            </p>
            <?php
            //CONSTRUCTION D'UN TABLEAU D'OBJET D'EMAIL DES CENTRALES
            if (!empty($_SESSION['idcentrale'])) {
                $tabmail = array();
                for ($i = 0; $i < count($_SESSION['idcentrale']); $i++) {
                    $tabEmail = $manager->getListObjet("select email1,email2,email3,email4,email5 from centrale where idcentrale='" . $_SESSION['idcentrale'][$i] . "'");
                    $tabmail[] = get_object_vars($tabEmail); //CONTRUCTION D'UN TABLEAU SIMPLE CONTENANT LES EMAILS DES CENTTRALES
                    $_SESSION['emailCentrale'] = $tabmail;
                }
            }
            $idcentrale = $manager->getSingle2("SELECT idcentrale_centrale FROM loginpassword,utilisateur WHERE idlogin = idlogin_loginpassword and pseudo=?", $_SESSION['pseudo']);
            $statut = $manager->getSinglebyArray("SELECT  co.idstatutprojet_statutprojet FROM utilisateur u,creer cr,concerne co,projet p,loginpassword l
WHERE  u.idlogin_loginpassword = l.idlogin AND cr.idutilisateur_utilisateur = u.idutilisateur AND p.idprojet = co.idprojet_projet AND p.idprojet = cr.idprojet_projet AND p.idprojet =? and l.pseudo =?", array($idprojet, $_SESSION['pseudo']));
            if (!empty($idcentrale)) {
                ?>
                <p><a href="<?php echo '/'.REPERTOIRE;?>/update_Waiting_projectI/<?php echo $lang; ?>/<?php echo $idprojet . '/' . $numprojet . '/' . $statut . '/' . $idcentrale.'/'.rand(0,10000); ?>"><?php echo TXT_RESULTMODIFDEMANDE; ?></a></p>
            <?php } else { ?>
                <p><a href="<?php echo '/'.REPERTOIRE;?>/update_Waiting_projectE/<?php echo $lang; ?>/<?php echo $idprojet . '/' . $numprojet . '/' . $statut.'/'.rand(0,10000);; ?>"><?php echo TXT_RESULTMODIFDEMANDE; ?></a></p>
            <?php } ?>
        </div>
        <script>
            function verifjs() {
                return confirm("<?php echo TXT_RESULTMESSAGECONFIRM; ?>", 'toto');
            }
        </script>
        <?php echo TXT_OU ;?><br><br>
        <script>require(["dojo/ready", "dijit/form/Button", "dojo/dom"], function(ready, Button, dom) {
                ready(function() {
                    var myButton = new Button({
                        label: "<?php echo TXT_RESULTFINDEMANDE; ?>", id: "btnValideDemande",
                        onClick: function() {
                            if (verifjs()) {
                                window.location = '<?php echo "/".REPERTOIRE;?>/modifBase/updateStatutProjet.php?lang=<?php echo $lang; ?>&idprojet=<?php echo $idprojet; ?>&page_precedente=<?php echo basename(__FILE__); ?>';
                            }
                        }}, "progButtonNode");
                });
            });
        </script>
        <button id="progButtonNode" type="button"></button><br><br>
        <div><br><br>
            <div id="result2"></div>
        </div><br>
        <a href='indexchoix.php?lang=<?php echo $lang; ?>'><?php echo TXT_RETOUR; ?></a>
<?php include 'html/footer.html'; ?>
    </div>
</div>
</body>
</html>
<?php
unset ($_SESSION['idcentrale']);
BD::deconnecter();