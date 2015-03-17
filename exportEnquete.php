<?php
session_start();
include_once 'class/Manager.php';
include_once 'outils/constantes.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include('decide-lang.php');
include_once 'outils/toolBox.php';
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
include 'html/header.html';
?>
<div id="global">
    <?php include 'html/entete.html'; ?>
    <div style="margin-top: 70px;">
        <?php include_once 'outils/bandeaucentrale.php'; ?>
    </div>
    <div style="margin-top:40px;width:1050px" >
        <form  method="post" action="<?php echo '/' . REPERTOIRE ?>/exportEnquetes.php?lang=<?php echo $lang; ?>" id='exportenquete' name='exportenquete' onsubmit="if (document.getElementById('msgerreur')) {
                    document.getElementById('msgerreur').style.display = 'none'
                }">
            <fieldset id="ident" style="border-color: #5D8BA2;width: 1008px;padding-bottom:30px;padding-top:10px;font-size:1.2em" >
                    <legend><?php echo TXT_EXPORTENQUETE; ?></legend>
                    <?php $row = $manager->getList("select distinct on (EXTRACT(YEAR from datedebutprojet)) EXTRACT(YEAR from datedebutprojet) as annee  from projet "
                            . "where EXTRACT(YEAR from datedebutprojet) is not null AND EXTRACT(YEAR from datedebutprojet) >2012 order by annee desc");  ?>
                <table>
                    <tr>
                        <td>
                            <select  id="annee" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'annee',value: '',required:false,placeHolder: '<?php echo TXT_SELECTDATE; ?>'"
                                     style="margin-left:50px;margin-top:25px;margin-bottom: 15px" >
                                <?php
                                for ($i = 0; $i < count($row); $i++) {
                                    echo '<option value="' . ($row[$i]['annee']) . '">' . $row[$i]['annee'] . '</option>';
                                }echo '<option value="' . 1 . '">' . TXT_TOUS . '</option>';
                                ?>
                            </select>
                        </td>
                        <td><button type="submit" data-dojo-type="dijit/form/Button" style="margin-left:10px;margin-top:25px;margin-bottom: 15px"><?php echo TXT_VALIDER; ?></button>
                            <a class="infoBulle" href="#">&nbsp;<img src='<?php echo "/".REPERTOIRE; ?>/styles/img/help.gif' height="13px" width="13px"/>
                            <span style="text-align: left;padding:10px;width: 340px;border-radius:5px" >
                            <?php echo TXT_AIDEEXOORTENQUETE; ?></span></a></td>

                    </tr>

                </table>	

            </fieldset>
            <?php include 'html/footer.html'; ?>
        </form>
    </div>
</div>



