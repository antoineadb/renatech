<?php
session_start();
include_once 'class/Manager.php';
include_once 'outils/toolBox.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include 'decide-lang.php';
include 'outils/constantes.php';

if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
include 'html/header.html';
$libellecentrale=$manager->getSingle2("SELECT libellecentrale	FROM centrale,utilisateur,loginpassword WHERE idcentrale = idcentrale_centrale AND idlogin = idlogin_loginpassword AND pseudo=?",$_SESSION['pseudo']);
?>
<div id="global">
    <?php include 'html/entete.html'; ?>
    <div style="margin-top: 70px;">
        <?php include_once 'outils/bandeaucentrale.php'; ?>
    </div>
    <div style="margin-top:15px;width:1050px" id="navigation">
        <form  method="post" action="<?php echo '/' . REPERTOIRE ?>/trace/<?php echo $lang; ?>" id='gestionporteur' name='gestionporteur' >
            <fieldset id="findProjetporteur" style="display: block;height:50px;border-color: #5D8BA2;padding-bottom:20px;padding-top: 8px;border:solid 1px #5D8BA2;color:midnightblue;font-family:verdana;border-radius: 5px;" >
                <legend style="margin-left: 20px;font-size: 1.2em"><?php echo TXT_CHOIXPROJET; ?></legend>
                <table>
                    <tr>
                        <td valign="top" style="width: 105px;padding-top: 3px">
                            <input type="dijit/form/DateTextBox" style="width: 260px;border-radius: 3px;padding-left: 22px;height:24px;font-size: 1.2em;margin-left:20px" placeholder="<?php echo TXT_DATEPROJET; ?>" name="dateprojet" id="dateprojet" data-dojo-type="dijit/form/DateTextBox" autocomplete="on"/></td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td valign="top" style="width: 105px;"><input type="text" style="width: 260px;border-radius: 8px;padding: 3px;height:24px;font-size:1.2em" name="numprojet" placeholder="<?php echo TXT_NUMPROJET; ?>" id="numprojet" data-dojo-type="dijit/form/ValidationTextBox" autocomplete="on"/></td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td>&nbsp;&nbsp;&nbsp;</td>
                        <td valign="bottom" >
                            <input name ="rechercheProjet" type="submit"  value="<?php echo TXT_CHOIXPROJET; ?>"  class="maj" onclick="
                                    if (document.getElementById('dateprojet').value === '' && document.getElementById('numprojet').value === '') {
                                        document.getElementById('numprojet').value = '*';
                                    }"  >
                        </td>
                    </tr>
                </table>
            </fieldset>
            <?php
            if (!empty($_POST['dateprojet']) || !empty($_POST['numprojet'])) {
                echo '<fieldset id="new" style="border-color: #5D8BA2;width: 1050px;padding:4px; margin-top: 15px" > <legend>' . TXT_CHOIXPROJET . '</legend> ';
                include 'findProjettrace.php';
                echo '</fieldset>';
            }

            if (isset($_GET['idprojet'])) {
                $arrayprojet = $manager->getList2("SELECT ce.libellecentrale,co.commentaireprojet,p.numero,p.titre,p.acronyme,p.refinterneprojet,p.dateprojet,p.datedebutprojet,p.datestatutfini,p.datestatutcloturer,
                    s.libellestatutprojet,co.idstatutprojet_statutprojet,u.nom,u.prenom,u.nomresponsable,u.mailresponsable,co.datestatutrefuser,p.contexte,p.description,p.descriptiftechnologique,verrouidentifiee,reussite FROM projet p,concerne co ,centrale ce,statutprojet s,creer cr,utilisateur u WHERE  p.idprojet = co.idprojet_projet AND co.idcentrale_centrale = ce.idcentrale 
                    AND s.idstatutprojet = co.idstatutprojet_statutprojet AND cr.idprojet_projet = p.idprojet AND cr.idutilisateur_utilisateur = u.idutilisateur and p.idprojet=?", $_GET['idprojet']);
                ?>
            <fieldset id="ident" style="margin-top: 20px">
                    <legend style="font-size:1.2em">
                        <a style="text-decoration: none" href='<?php echo "/".REPERTOIRE; ?>/controler/controlestatutprojet.php?lang=<?php echo $_GET["lang"];?>&numProjet=<?php echo $arrayprojet[0]['numero'];?>&centrale=<?php echo $libellecentrale;?>'><?php echo TXT_NUMPROJET.': '.$arrayprojet[0]['numero']?></a>
                    </legend>
                    <?php
                    $datedemande=new DateTime($arrayprojet[0]['dateprojet']) ;
                    echo '<b><u>'.TXT_DATEDEMANDE . ':</b></u>  ' . $datedemande->format('d-m-Y'). '<br>';
                    echo '<b><u>'.TXT_NOMDEMANDEUR . ' - ' . TXT_PRENOM . ':</b></u>  ' . $arrayprojet[0]['nom'] . ' - ' . $arrayprojet[0]['prenom'] . '<br>';
                    if (!empty($arrayprojet[0]['nomresponsable'])) {
                            echo TXT_NOMRESPONSABLE . ':<b></u>  ' . $arrayprojet[0]['nomresponsable'].'</b><br>';
                    }
                    if (!empty($arrayprojet[0]['mailresponsable'])) {
                        echo '<b><u>'.TXT_EMAILRESPONSABLE . ':</b></u>  ' . $arrayprojet[0]['mailresponsable'].'<br>';                   
                    }                    
                    echo '<b><u>'.TXT_TITREPROJET . ':</b></u>  ' . filtredonnee($arrayprojet[0]['titre']) . '<br>';
                    if (!empty($arrayprojet[0]['acronyme'])) {
                        $acronyme = trim(filtredonnee($arrayprojet[0]['acronyme']));
                    }
                    if (!empty($acronyme)) {
                        echo '<b><u>'.TXT_ACRONYME . ':</b></u>  ' . $acronyme . '<br>';
                    }
                    if (!empty($arrayprojet[0]['refinterneprojet'])) {
                        $refinterneprojet = trim(filtredonnee($arrayprojet[0]['refinterneprojet']));
                    }
                    if (!empty($refinterneprojet)) {
                        echo '<b><u>'.TXT_REFINTERNE . ':</b></u>  ' . $refinterneprojet . '<br>';
                    }
                    if (!empty($arrayprojet[0]['commentaireprojet'])) {
                        echo '<b><u>'.TXT_COMMENTAIREPROJET . ':</b></u> ' . filtredonnee($arrayprojet[0]['commentaireprojet']) . '<br>';
                    }                    
                    ?>                        
                    <table summary="" style=" border-collapse:collapse;width:70%;margin-top:25px">                        
                                    <tr>                                        
                                        <th style="border:1px solid black;width:20%;padding-left: 5px;background-color: lightgray;font-weight: bold"><?php echo TXT_CENTRALESELECTONNEE; ?></th>
                                        <th style="border:1px solid black;width:20%;padding-left: 5px;background-color: lightgray;font-weight: bold"><?php echo 'Date'; ?></th>
                                        <th style="border:1px solid black;width:20%;padding-left: 5px;background-color: lightgray;font-weight: bold"><?php echo TXT_STATUTPROJETS; ?></th>
                                    </tr>
                                    <?php for ($i = 0; $i < count($arrayprojet); $i++) { 
                                        if (!empty($arrayprojet[$i]['dateprojet'])&& $arrayprojet[$i]['idstatutprojet_statutprojet']!=ENATTENTE) {?>
                                    <tr>
                                        <td style="border:1px solid black;width:20%;padding-left: 5px"><?php echo $arrayprojet[$i]['libellecentrale']; ?></td>    
                                        <td style="border:1px solid black;width:20%;padding-left: 5px;"><?php 
                                            $date=new DateTime($arrayprojet[$i]['dateprojet']) ;echo $date->format('d-m-Y'); ?>
                                        </td>
                                        <td style="border:1px solid black;width:20%;padding-left: 5px;"><?php echo "En attente"; ?></td>
                                    </tr>
                                        <?php } 
                                        if (!empty($arrayprojet[$i]['datedebutprojet'])&& $arrayprojet[$i]['idstatutprojet_statutprojet']!=ENCOURSREALISATION&& $arrayprojet[$i]['idstatutprojet_statutprojet']!=ENCOURSANALYSE) {?>
                                         <tr>
                                            <td style="border:1px solid black;width:20%;padding-left: 5px"><?php echo $arrayprojet[$i]['libellecentrale']; ?></td>    
                                            <td style="border:1px solid black;width:20%;padding-left: 5px;"><?php 
                                                $date=new DateTime($arrayprojet[$i]['datedebutprojet']) ;echo $date->format('d-m-Y'); ?>
                                            </td>
                                            <td style="border:1px solid black;width:20%;padding-left: 5px;"><?php echo "Accepté pour expertise"; ?></td>
                                        </tr>
                                        <?php }?>                                    
                                    <tr>
                                        <td style="border:1px solid black;width:20%;padding-left: 5px"><?php echo filtredonnee($arrayprojet[$i]['libellecentrale']); ?></td>                                        
                                        <?php if (!empty($arrayprojet[$i]['dateprojet'])&& $arrayprojet[$i]['idstatutprojet_statutprojet']==ACCEPTE ) {?>
                                        <td style="border:1px solid black;width:20%;padding-left: 5px;"><?php 
                                        $date=new DateTime($arrayprojet[$i]['dateprojet']) ;echo $date->format('d-m-Y');?></td>
                                        <?php  }elseif (!empty($arrayprojet[$i]['datedebutprojet'])&& $arrayprojet[$i]['idstatutprojet_statutprojet']==ENCOURSREALISATION ) {?>
                                        <td style="border:1px solid black;width:20%;padding-left: 5px;"><?php 
                                        $date=new DateTime($arrayprojet[$i]['datedebutprojet']) ;echo $date->format('d-m-Y'); ?></td>                                         
                                        <?php }
                                        if (!empty($arrayprojet[$i]['dateprojet'])&& $arrayprojet[$i]['idstatutprojet_statutprojet']==ENATTENTE) {?>
                                        <td style="border:1px solid black;width:20%;padding-left: 5px;"><?php 
                                        $date=new DateTime($arrayprojet[$i]['dateprojet']) ;echo $date->format('d-m-Y'); ?></td>
                                        <?php }
                                        if (!empty($arrayprojet[$i]['dateprojet'])&& $arrayprojet[$i]['idstatutprojet_statutprojet']==ENCOURSANALYSE) {?>
                                        <td style="border:1px solid black;width:20%;padding-left: 5px;"><?php 
                                        $date=new DateTime($arrayprojet[$i]['dateprojet']) ;echo $date->format('d-m-Y'); ?></td>
                                        <?php }
                                        if (!empty($arrayprojet[$i]['datestatutfini'])){?>
                                        <td style="border:1px solid black;width:20%;padding-left: 5px;"><?php 
                                        $date=new DateTime($arrayprojet[$i]['datestatutfini']) ;echo $date->format('d-m-Y'); ?></td>
                                        <?php }
                                        if (!empty($arrayprojet[$i]['datestatutcloturer'])){?>
                                        <td style="border:1px solid black;width:20%;padding-left: 5px;"><?php 
                                        $date=new DateTime($arrayprojet[$i]['datestatutcloturer']) ;echo $date->format('d-m-Y'); ?></td>
                                        <?php }
                                        if (!empty($arrayprojet[$i]['datestatutrefuser'])){?>
                                        <td style="border:1px solid black;width:20%;padding-left: 5px;"><?php
                                        $date=new DateTime($arrayprojet[$i]['datestatutrefuser']) ;echo $date->format('d-m-Y');?></td>
                                        <?php }?>
                                        <td style="border:1px solid black;width:20%;padding-left: 5px;"><?php echo $arrayprojet[$i]['libellestatutprojet']; ?></td>
                                    </tr>
                                 <?php }?>
                                    </table>
                    
                    <?php
                     if (!empty($arrayprojet[0]['contexte'])) {
                        echo '<br><b><u>'.TXT_CONTEXTESCIENTIFIQUE . ':</u><br></b> ' . filtredonnee($arrayprojet[0]['contexte']) . '<br>';
                    }
                    if (!empty($arrayprojet[0]['description'])) {
                        echo '<br><u><b>'.TXT_DESCRIPTION . ':</u><br></b> ' . filtredonnee($arrayprojet[0]['description']) . '<br>';
                    }
                     if (!empty($arrayprojet[0]['descriptiftechnologique'])) {
                        echo '<br><u><b>'.TXT_DESCRIPTIFTECHNOLOGIQUE . ':</b></u><br> ' . filtredonnee($arrayprojet[0]['descriptiftechnologique']) . '<br>';
                    }
                    if (!empty($arrayprojet[0]['verrouidentifiee'])) {
                        echo '<br><u><b>'.TXT_VERROUIDENTIFIEE . ':</b></u><br> ' . filtredonnee($arrayprojet[0]['verrouidentifiee']) . '<br>';
                    }
                    if (!empty($arrayprojet[0]['reussite'])) {
                        echo '<br><u><b>'.TXT_REUSSITEESCOMPTE . ':</b></u><br> ' . filtredonnee($arrayprojet[0]['reussite']) . '<br>';
                    }
                    ?>
                </fieldset><?php
            }
            ?>
            <?php include 'html/footer.html'; ?>
        </form>