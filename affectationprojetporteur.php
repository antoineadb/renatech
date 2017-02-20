<?php
session_start();
include_once 'decide-lang.php';
include_once 'outils/constantes.php';
include_once 'outils/toolBox.php';
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
include 'html/header.html';
?>
<script> require(["dojo/parser", "dijit/form/Select"]);</script>
<div id="global">
    <?php include 'html/entete.html'; ?>       <div style="margin-top: 70px;">
        <?php include_once 'outils/bandeaucentrale.php'; ?>
    </div>
    <div id="navigation">
        <form  method="post" action="<?php echo '/'.REPERTOIRE; ?>/ctrlporteur.php?lang=<?php echo $lang; ?>" id='affectationprojetporteur' name='affectationprojetporteur'   >
            <fieldset id="ident" style="border-color: #5D8BA2;width: 1006px;padding-bottom:10px;padding-top:8px;margin-top:15px" >
                <legend><?php echo TXT_RECHERCHEPORTEUR; ?></legend>
                <input name="page_precedente" type="hidden" value="<?php echo basename(__FILE__); ?>">
                <table>
                    <tr>
                        <td valign="top" style="width: 80px;"><input type="text" style="width: 170px;border-radius: 8px;padding: 3px;" placeholder="<?php echo TXT_NOMUTILISATEUR; ?>" name="nom" id="nom" data-dojo-type="dijit/form/ValidationTextBox" autocomplete="on"/></td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td valign="top" style="width: 100px;text-align: left;"><input type="text" style="width: 170px;border-radius: 8px;padding: 3px;" placeholder="<?php echo TXT_PRENOMUTILISATEUR; ?>" name="prenom" id="prenom" data-dojo-type="dijit/form/ValidationTextBox" autocomplete="on"/></td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td>&nbsp;&nbsp</td>
                        <td valign="bottom" >
                            <input name ="rechercheUserporteur" type="submit"  value="<?php echo TXT_BOUTONRECHERCHE; ?>"  class="maj" onclick="
                                if (document.getElementById('nom').value === '' && document.getElementById('prenom').value === '') {
                                    document.getElementById('nom').value = '*';
                                }"/>
                        </td>
                    </tr>
                </table>
            </fieldset>
            <?php if (isset($_POST['nom']) || isset($_POST['prenom'])) { ?>
                <fieldset id='droit' style="border-color: #5D8BA2;width: 1037px;padding:5px; margin-top: 15px" >
                    <legend>	<?php echo TXT_SELECTEDHOLDER; ?>	</legend>
                    <?php
                    if (isset($numero) && !empty($numero)) {
                        include_once 'class/Manager.php';
                        $db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE	#D4E1EB
                        $manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
                        $array = $manager->getList2("select titre,numero from projet where idprojet=?", $numero);
                        $titre = stripslashes(str_replace("''", "'", substr($array[0]['titre'], 0, 120)));
                        $numprojet = $array[0]['numero'];
                        echo '<div id="fieldprojetporteur" style="margin-left:	0px;" >
                            <table style="border: 1px solid #57758D;clear: both;margin-top: 15px;width: 1037px;border-radius: 5px;">
                                <th style="background-color:#E3ECF2 ;padding-left:6px; color: midnightblue;height:20px;" >' . TXT_SELECTEDPROJET . '</th>
                                <tr><td><div   style="width:920px;text-align:left;color: midnightblue;margin-left: 5px;height:20px;vertical-align: middle" id="copieprojet" name="copieprojet" value="' . $numprojet . '">' . $numprojet . ' - ' . $titre . "..." . '</div></td></tr>
                        </table>
                        </div>';
                        BD::deconnecter();
                    }
                    ?><br>
                    <?php include 'findUserporteur.php'; ?>

                </fieldset>
            <?php } ?>

            <?php
            include 'html/footer.html';
            ?>
        </form>
    </div>
</div>
</body>
</html>
