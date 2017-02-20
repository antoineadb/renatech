<?php
session_start();
include_once 'outils/toolBox.php';
include_once 'outils/constantes.php';
include 'decide-lang.php';
include_once 'class/Manager.php';
$db = BD::connecter();
$manager = new Manager($db);
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
include 'html/header.html';
?>
<div id="global">
    <?php
    include 'html/entete.html';
    ?>
     <div style="margin-top: 70px;">
    <?php include_once 'outils/bandeaucentrale.php'; ?>
            </div> 
    <script>        
        require(["dojo/ready","dojo/parser","dijit/form/RadioButton","dijit/form/Button"], function(ready){
            ready(function(){});
        });
    </script>
    <?php   ?>
    <form data-dojo-type="dijit/form/Form" name="exportProjet" id="exportProjet" method="post" action="<?php echo '/'.REPERTOIRE ;?>/templates/exportWord.php?lang=<?php echo $lang; ?>"  >
        <script type="dojo/on" data-dojo-event="submit">                
                 dijit.byId('anneerapport').value = '';
            dijit.byId('radioOne').set('radioOne',true);
                if (this.validate()){
                    return true;
                }else{
                    alert("<?php echo TXT_ERR; ?>");
                    return false;
                }
        </script>
        <fieldset id="export" style="  border-color: #5d8ba2;height: 80px;margin-top: 50px;padding-bottom: 40px;padding-top: 10px;width: 1017px;"  >
            <legend style="color: #5D8BA2;font-size: 1.2em"><b><?php echo TXT_EXPORTREPORT; ?></b>
                <a class="infoBulle" href="#"><img src='<?php echo '/'.REPERTOIRE ?>/styles/img/help.gif' height="13px" width="13px"/><span style="width: 250px"><?php echo TXT_AIDEREPORT; ?></span></a>
            </legend>
            <div style="color: darkblue;font-size: 1.1em;margin-bottom: 13px;text-align: center;font-style: italic"><?php echo TXT_AIDEEXPORTREPORT ?></div>
            <table>
                <tr>                   
                    <td>
                        <label></label>
                        <select  id="anneerapport" data-dojo-type="dijit/form/FilteringSelect"  data-dojo-props="name: 'annee',value: '',required:false,placeHolder: '<?php echo TXT_SELECTYEAR; ?>'" 
                                 style="width: 220px;margin-left:35px;height: 25px;font-size: 1.2em" >
                                     <?php
                                     $row = $manager->getList("select distinct  EXTRACT(YEAR from datecreation) as anneeCreation  from rapport where EXTRACT(YEAR from datecreation)>2012");
                                     for ($i = 0; $i < count($row); $i++) {
                                         echo '<option value="' . $row[$i]['anneecreation'] . '">' . $row[$i]['anneecreation'] . '</option>';
                                     }echo '<option value=1>' . TXT_TOUS . '</option>';
                                     ?>
                        </select>
                        <?php 
                            if($_SESSION['idTypeUser']==ADMINNATIONNAL){                             
                            $libellecentrale = $manager->getListbyArray("select idcentrale,libellecentrale from centrale where idcentrale!=? and masquecentrale!=? order by libellecentrale asc",array(IDCENTRALEAUTRE,TRUE)); 
                            ?>
                            <select id="centrale" name="centrale" data-dojo-type="dijit/form/FilteringSelect" style="width:240px;height:25px;font-size:1.2em;margin-right: 20px;margin-left: 20px"
                                    data-dojo-props="  value: '' , placeHolder: '<?php echo TXT_SELECTCENTRALE; ?>',required:'required'" >
                                        <?php                                       
                                        for ($i = 0; $i < count($libellecentrale); $i++) {
                                            echo "<option value='" . $libellecentrale[$i]['idcentrale'] . "'>" . $libellecentrale[$i]['libellecentrale'] . "</option>";
                                        }
                                        ?>
                            </select>
                            
                        <?php } ?>
                    </td>                    
                    <td valign="middle" style="text-align: left;font-size: 1.2em;padding-right: 20px"><?php echo 'Choix du format:' ?>
                    <input type="radio" data-dojo-type="dijit/form/RadioButton" name="ext" id="radioOne" checked value=".doc"/> .doc
                    <input type="radio" data-dojo-type="dijit/form/RadioButton" name="ext" id="radioTwo" value=".rtf"/> .rtf</td>
                    <td><input type="submit"   label="<?php echo TXT_ENVOYER; ?>" data-dojo-Type="dijit.form.Button" data-dojo-type="dijit/form/Button" style="margin-left: 35px;height:28px;text-align: center;font-size: 1.2em" /></td></tr>
            </table>

        </fieldset>

    </form>
    <?php 
    include 'html/footer.html'; 
    BD::deconnecter();?>
</div>

</body>
</html>
