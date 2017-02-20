<?php
session_start();
include_once('decide-lang.php');
include 'html/header.html';
include_once 'class/Manager.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
?>
<script> require(["dojo/parser", "dijit/form/Select"]);</script>
<div id="globalfinduser">
    <?php include 'html/entete.html'; ?>
    <br>
    <div style="margin-left: 25px" id="navigation">
        <?php
        include_once 'outils/toolBox.php';
        $_SESSION['lang'] = $lang;
        if (isset($_SESSION['pseudo'])) {
            check_authent($_SESSION['pseudo']);
            $idutilisateur = $manager->getSingle2("SELECT idtypeutilisateur_typeutilisateur FROM loginpassword,utilisateur
            WHERE loginpassword.idlogin = utilisateur.idlogin_loginpassword and pseudo=?", $_SESSION['pseudo']);
            
        } else {
            echo '<script>window.location.replace("erreurlogin.php")</script>';
        }
        ?>
        <form  method="post" action="ctrldroitAdminNationnal.php" id='gestiondroit' name='gestiondroit' >
            <fieldset id="ident" style="border-color: #5D8BA2;width: 505px;padding-bottom:10px;padding-top:8px" >
                <legend><?php echo TXT_RECHERCHEUSER; ?></legend>
                <input name="page_precedente" type="hidden" value="<?php echo basename(__FILE__); ?>">
                <table>
                    <tr>
                        <td valign="top" style="width: 80px;"><input type="text" style="width: 170px;border-radius: 8px;padding: 3px;" placeholder="<?php echo TXT_NOMUTILISATEUR; ?>" name="nom" id="nom" data-dojo-type="dijit/form/ValidationTextBox" autocomplete="on"/></td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td valign="top" style="width: 100px;text-align: left;"><input type="text" style="width: 170px;border-radius: 8px;padding: 3px;" placeholder="<?php echo TXT_PRENOMUTILISATEUR; ?>" name="prenom" id="prenom" data-dojo-type="dijit/form/ValidationTextBox" autocomplete="on"/></td>
                        <td>&nbsp;&nbsp</td>
                        <td>&nbsp;&nbsp</td>
                        <td valign="bottom" >
                            <input name ="recherche" type="submit"  value="<?php echo TXT_BOUTONRECHERCHE; ?>"  class="maj" onclick="
                                if(document.getElementById('nom').value=='' && document.getElementById('prenom').value==''){document.getElementById('nom').value='*';}"/>
                        </td>
                    </tr>
                </table>
            </fieldset>
            <?php
            if ($idutilisateur == 1) {
                $requete = "SELECT libelletype,libelletype as libeltype FROM typeutilisateur where idtypeutilisateur =4 or idtypeutilisateur=5 or idtypeutilisateur=6";
            } elseif ($idutilisateur == 4) {
                $requete = "SELECT libelletype,libelletype as libeltype FROM typeutilisateur where idtypeutilisateur =5 or idtypeutilisateur=6";
            }
            $chemin = "tmp";
            $nomselect = "selectlibelletypeutilisateur.json";
            $libelleattribut1 = "libelletype";
            $libelleattribut2 = "libeltype";
            creerJson($requete, $chemin, $nomselect, $libelleattribut1, $libelleattribut2);
            ?>
            <fieldset id='fielddroit' style="display: none;border-color: #5D8BA2;margin-top:15px;width: 525px;padding:10px 10px 10px 10px;border:solid 1px #5D8BA2;color:midnightblue;font-family:verdana;border-radius: 5px;" >
                <legend><?php echo TXT_SELECTEDUSER; ?></legend>
                <input name="page_precedente" type="hidden" value="<?php echo basename(__FILE__); ?>">
                <table>
                    <tr>
                        <td valign="bottom" ><div style="height: 21px;color: midnightblue;font-weight: bold" id="copienom" name="copienom" onchange="document.getElemntById(copieNom).value=this.value;"></div></td>
                        <td valign="top"> &nbsp;&nbsp;&nbsp;</td>
                        <td valign="top" style="width: 80px;"><?php echo TXT_NOUVEAUDROIT; ?><div id='nouveauRole' name='nouveauRole'></div></td>
                        <td valign="top" >&nbsp;&nbsp;&nbsp;</td>
                        <td valign="bottom"><button  data-dojo-type="dijit/form/Button" name="updateRole" type="submit" ><?php echo TXT_MAJ; ?></button></td>
                    </tr>
                </table>
            </fieldset>
            <input type="text" id ="copieNom" name="copieNom" style="display: none"/>
            <input type="text" id ="copieNouveauRole" name="copieNouveauRole" style="display: none"/>
        </form>
        <?php
        if (!empty($_POST['nom']) || !empty($_POST['prenom']) || isset($_GET['idutilisateur'])) {
            if (isset($_GET['erreur'])) {
                echo "<div id='erreur' style='width:525px; margin-top:15px;text-align:center;color:red'>" . $_GET['erreur'] . "</div>";
            }
            ?>
            <fieldset id='droit' style="border-color: #5D8BA2;width: 535px;padding:5px; margin-top: 15px" >
                <legend><?php echo TXT_CHOIXUSER; ?></legend>
                <?php include 'findUserAdminNationnal.php'; ?>
            </fieldset><?php } include 'html/footer.html'; ?>
    </div>
</div>
</body>
</html>
<script>
    require(["dijit/form/Select","dojo/data/ObjectStore","dojo/store/Memory"],
    function(Select, ObjectStore, Memory){
        var store = new Memory({
            data:	<?php require 'tmp/selectlibelletypeutilisateur.json'; ?>	});
       
        var os = new ObjectStore({ objectStore: store });
        var s = new Select({
            store: os,placeholder: 'texte',
            required: true
        }, "nouveauRole");s.startup();
        s.on("change", function(){
            var valeur =dijit.byId('nouveauRole').get('value')
            document.gestiondroit.copieNouveauRole.value = valeur;
        })})
</script>