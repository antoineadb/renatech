<?php
session_start();
include 'outils/toolBox.php';
include 'outils/requeteRelance.php';
include 'outils/constantes.php';
include 'decide-lang.php';
include 'class/Securite.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER

if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
include 'html/header.html';
$dateMoins3mois = date('Y-m-d', strtotime('-3 month'));
$sql = "
    SELECT distinct on (p.numero) p.idprojet,p.datemaj,p.dureeprojet,p.refinterneprojet,p.numero,p.titre,p.datedebutprojet,u.nom,u.prenom,p.idperiodicite_periodicite,l.mail,
    u.idutilisateur,p.dateenvoiemail , p.interneexterne
    FROM projet p
    LEFT JOIN creer cr ON cr.idprojet_projet = p.idprojet
    LEFT JOIN utilisateur u ON cr.idutilisateur_utilisateur = u.idutilisateur
    LEFT JOIN concerne co  ON co.idprojet_projet = p.idprojet
    LEFT JOIN centrale ce  ON ce.idcentrale = co.idcentrale_centrale
    
    LEFT JOIN typeprojet t ON t.idtypeprojet = p.idtypeprojet_typeprojet 
    LEFT JOIN statutprojet s ON s.idstatutprojet = co.idstatutprojet_statutprojet 
    LEFT JOIN loginpassword l ON l.idlogin = u.idlogin_loginpassword 
    WHERE u.idcentrale_centrale is not null     
    AND trashed =FALSE";
    $projetExport = $manager->getList($sql);
$nbprojetAExporter = count($projetExport);
$_SESSION['nbprojet'] = $nbprojetAExport;
$fpProjetEncoursRealisation = fopen('tmp/exportLims.json', 'w');
$dataEncoursRealisation = "";
fwrite($fpProjetEncoursRealisation, '{"items": [');
for ($i = 0; $i < $nbprojetAExporter; $i++) {
    if (!empty($projetExport[$i]['datemaj'])) {
        $datemaj = $projetExport[$i]['datemaj'];
    } else {
        $datemaj = '';
    }
   if ($projetExport[$i]['idperiodicite_periodicite'] == JOUR) {
        $datedepart = strtotime($projetExport[$i]['datedebutprojet']);
        $duree = ($projetExport[$i]['dureeprojet']);
        $dateFin = date('Y-m-d', strtotime('+' . $duree . 'day', $datedepart));
    } elseif ($projetExport[$i]['idperiodicite_periodicite'] == MOIS) {
        $datedepart = strtotime($projetExport[$i]['datedebutprojet']);
        $duree = ($projetExport[$i]['dureeprojet']);
        $dateFin = date('Y-m-d', strtotime('+' . $duree . 'month', $datedepart));
    } elseif ($projetExport[$i]['idperiodicite_periodicite'] == ANNEE) {
        $datedepart = strtotime($projetExport[$i]['datedebutprojet']);
        $duree = ($projetExport[$i]['dureeprojet']);
        $dateFin = date('Y-m-d', strtotime('+' . $duree . 'year', $datedepart));
    }
    $dataEncoursRealisation = ""
            . '{"datedebutprojet":' . '"' . $projetExport[$i]['datedebutprojet'] . '"' . ","
            . '"datemaj":' . '"' . $datemaj . '"' . ","
            . '"numero":' . '"' . $projetExport[$i]['numero'] . '"' . ","
            . '"idprojet":' . '"' . $projetExport[$i]['idprojet'] . '"' . ","
            . '"titre":' . '"' . filtredonnee($projetExport[$i]['titre']) . '"' . ","
            . '"mail":' . '"' . filtredonnee($projetExport[$i]['mail']) . '"' . ","
            . '"idutilisateur":' . '"' . $projetExport[$i]['idutilisateur'] . '"' . ","
            . '"nom":' . '"' . filtredonnee($projetExport[$i]['nom']) . ' ' . filtredonnee($projetExport[$i]['prenom']) . '"' . ","
            . '"datefin":' . '"' . $dateFin . '"' . ","
            . '"dateheureenvoiemail":' . '"' . $projetExport[$i]['dateenvoiemail'] . '"' . ","
            . '"refinterneprojet":' . '"' . filtredonnee($projetExport[$i]['refinterneprojet']) . '"' . "},";
    fputs($fpProjetEncoursRealisation, $dataEncoursRealisation);
    fwrite($fpProjetEncoursRealisation, '');
}
fwrite($fpProjetEncoursRealisation, ']}');
$jsonEncoursRealisation = @file_get_contents($fpProjetEncoursRealisation);
$jsonEncoursRealisation1 = str_replace('},]}', '}]}', $jsonEncoursRealisation);
@file_put_contents($fpProjetEncoursRealisation, $jsonEncoursRealisation1);
fclose($fpProjetEncoursRealisation);
chmod("tmp/exportLims.json", 0777);
?>
<div id="global">
    <?php
    include 'html/entete.html';
    ?>
    <div style="padding-top: 75px;">
        <?php include'outils/bandeaucentrale.php'; ?>
    </div>   
    <script src='<?php echo '/' . REPERTOIRE; ?>/js/jquery-1.8.0.min.js'></script>
    <form  method="post"  id="formRelance" name="formRelance" 
           action="<?php
        if (isset($_GET['cpt'])) {
            echo '/' . REPERTOIRE . '/modifBase/modifRelance.php?cpt=' . $_GET['cpt'];
        } else {
            echo '/' . REPERTOIRE . '/modifBase/modifRelance.php';
        }
        ?>" >
        <input type='submit' id='btnOui' style="display: none" />
        <div style="margin-top:50px;width:1050px" >            
            <fieldset id="ident">
                <legend><?php echo TXT_PROJET; ?><a class="infoBulle" href="#">&nbsp;<img src='<?php echo "/" . REPERTOIRE; ?>/styles/img/help.gif' ><span style="width: 322px;">
                            <?php echo affiche('TXT_AIDERELANCEEMAIL'); ?></span></a></legend>
                <a id='aideRelance' href="<?php echo '/' . REPERTOIRE . '/downloadManual/aideContextuelleRelance.pdf' ?>" target="_blank">
                    <img src="<?php echo '/' . REPERTOIRE; ?>/styles/img/infoStat.png" title="<?php echo TXT_AIDERELANCE; ?>"  >
                </a>
                <div>
                    <div style="float: left"><a class="infoBulle" href="<?php echo '/' . REPERTOIRE . '/exportEncoursjson.php?lang='.$lang;?> <?php if(isset($_GET['chx'])){echo "&chx=".$_GET['chx'];} ?>">&nbsp;
                            <img    src='<?php echo "/" . REPERTOIRE; ?>/styles/img/export.png' ><span style="width: 375px"><?php echo TXT_EXPORTPROJETCIDESSOUS; ?></span></a></div>
                </div>
                <div id='post'></div>
                <div style="margin-bottom: 75px;margin-top: 33px">
                    <div style="float: left;">
                        <input type="button" value="delete" label="<?php echo "Efface les dates d'envoi"; ?>" id="delete" name="delete"  data-dojo-type="dijit/form/Button" 
                               onclick="window.location.replace('<?php echo '/' . REPERTOIRE . '/modifBase/effaceDateEnvoiEmail.php?lang=' . $lang ?>')" />               
                    </div>
                    <div style="margin-left:20px;float:left;margin-top: 7px">
                        <div style="float:left"><?php echo TXT_TYPEPROJET ?></div>
                        <select name="interneExterne" id="interneExterne" data-dojo-type="dijit/form/FilteringSelect" style="width:120px;margin-left:20px;"
                                
                                onChange="window.location.replace('<?php echo '/' . REPERTOIRE . '/relance/'.$lang.'/c/';?>'+this.value+'')" >
                                
                            <option  value='3' <?php if (isset($_GET['chx']) && $_GET['chx'] == 3) {echo 'selected = \'selected\'';} ?> contenteditable="true"><?php echo TXT_TOUS; ?>  </option>
                            <option  value='1' <?php if (isset($_GET['chx']) && $_GET['chx'] == 1) {echo 'selected = \'selected\'';} ?> contenteditable="true" 
                                     ><?php echo TXT_INTERNE; ?>  </option>
                            <option  value='2' <?php if (isset($_GET['chx']) && $_GET['chx'] == 2) {echo 'selected = \'selected\'';} ?> contenteditable="true" 
                                     > <?php echo TXT_EXTERNE; ?>  
                            </option>
                        </select>
                    </div>
                    <div style="float: right;margin-right: 20px"><input type="button" value="Envoyer" label="<?php echo TXT_ENVOYERMAIL; ?>" id="EnvoyerEmail" name="Envoyer"  
                                                                        data-dojo-type="dijit/form/Button" style="width: 157px" onclick="repSendMail.show();"   /></div>
                </div>
                <div data-dojo-type="dijit/Dialog" data-dojo-id="repSendMail" title="<?php echo TXT_SENDMAILCONFIRM;?>" style="width: 410px;margin-left: 20px;   ">
                <table class="dijitDialogPaneContentArea">
                    <tr>
                        <td><button   data-dojo-type="dijit/form/Button"  id="delOui" data-dojo-props="onClick:function(){sendEmail();}"><?php echo TXT_OUI ;?></button></td><td>
                        <td><button  data-dojo-type="dijit/form/Button"  id="delNon"  data-dojo-props="onClick:function(){cancelSend();}"><?php echo TXT_NON ;?></button></td>                                                           
                    </tr>
                </table>
                    
                    <script>
                    function cancelSend(){
                        repSendMail.hide();
                    }
                    function sendEmail(){
                        document.getElementById("btnOui").click(); ;
                    }
                    
                    </script>
                </div>
                <input style="display:none" type="submit" value="" name="submit" id="submitId"/>
                <input type="text" style="display: none" value='<?php if (!empty($_GET['cpt'])) {echo $_GET['cpt'];} ?>'  id='cpt' name='cpt'/>

                <?php                
                $nbEmailInterne = $manager->getSinglebyArray("
                        SELECT count(p.idprojet) FROM projet p
                        LEFT JOIN creer cr ON cr.idprojet_projet = p.idprojet
                        LEFT JOIN utilisateur u ON cr.idutilisateur_utilisateur = u.idutilisateur
                        LEFT JOIN concerne co  ON co.idprojet_projet = p.idprojet
                        LEFT JOIN centrale ce  ON ce.idcentrale = co.idcentrale_centrale
                        LEFT JOIN typeprojet t ON t.idtypeprojet = p.idtypeprojet_typeprojet 
                        LEFT JOIN statutprojet s ON s.idstatutprojet = co.idstatutprojet_statutprojet 
                        LEFT JOIN loginpassword l ON l.idlogin = u.idlogin_loginpassword 
                        WHERE ce.idcentrale =? AND (s.idstatutprojet=? or  s.idstatutprojet=?)
                        AND trashed =FALSE AND u.idcentrale_centrale IS NOT NULL AND p.devtechnologique=TRUE AND p.dateenvoiemail =?", array(IDCENTRALEUSER, ENCOURSREALISATION,ENCOURSANALYSE, date('Y-m-d')));
                $nbEmailExterne=$manager->getSinglebyArray("
                    SELECT count(p.idprojet) FROM projet p
                        LEFT JOIN creer cr ON cr.idprojet_projet = p.idprojet
                        LEFT JOIN utilisateur u ON cr.idutilisateur_utilisateur = u.idutilisateur
                        LEFT JOIN concerne co  ON co.idprojet_projet = p.idprojet
                        LEFT JOIN centrale ce  ON ce.idcentrale = co.idcentrale_centrale
                        LEFT JOIN typeprojet t ON t.idtypeprojet = p.idtypeprojet_typeprojet 
                        LEFT JOIN statutprojet s ON s.idstatutprojet = co.idstatutprojet_statutprojet 
                        LEFT JOIN loginpassword l ON l.idlogin = u.idlogin_loginpassword 
                        WHERE ce.idcentrale =? AND (s.idstatutprojet=? or  s.idstatutprojet=?)
                        AND trashed =FALSE AND u.idcentrale_centrale NOT NULL AND p.devtechnologique=TRUE AND p.dateenvoiemail =?", array(IDCENTRALEUSER, ENCOURSREALISATION,ENCOURSANALYSE, date('Y-m-d')));                        
                
                $nbEmailEnvoyer=$nbEmailInterne+$nbEmailExterne;
                ?>                
                <input type="hidden" name="nbEmailInterne" value="<?php echo $nbEmailInterne; ?>">
                <input type="hidden" name="nbEmailExterne" value="<?php echo $nbEmailExterne; ?>">
                <?php if (isset($_GET['cpt']) && !empty($_GET['cpt'])) { ?>            
                    <?php if ($nbEmailEnvoyer <= $_GET['cpt']) { ?>
                        <div id="msginfo"><?php echo TXT_VOUSAVEZENVOYER . ' ' . $nbEmailEnvoyer . ' ' . TXT_MAIL . 's'; ?></div> 
                    <?php } elseif (isset($_GET['chx']) && $_GET['chx'] == 1) { ?>
                        <div id="msginfo"><?php echo TXT_VOUSAVEZENVOYER . ' ' . $nbEmailInterne . ' ' . TXT_MAIL . 's'; ?></div> 
                    <?php } elseif (isset($_GET['chx']) && $_GET['chx'] == 2) { ?>
                        <div id="msginfo"><?php echo TXT_VOUSAVEZENVOYER . ' ' . $nbEmailExterne . ' ' . TXT_MAIL . 's'; ?></div>     
                    <?php } else { ?>
                        <div id="msginfo"><?php echo TXT_VOUSAVEZENVOYER . ' ' . $nbEmailEnvoyer . ' ' . TXT_MAIL . 's'; ?></div>     
                    <?php } ?>
    <?php if (isset($_GET['err']) && $_GET['err'] == 'noselection') { ?>
                        <div id="msginfo"><?php echo TXT_ERREURENOSELECTION; ?></div> 
                <?php } ?> 
            <?php } ?>

            <?php include 'html/vueExportLims.html'; ?>
                <div id='test'></div><div id='deselect'></div><div id='idprojetDeselect'></div>
            </fieldset>           
<?php
include 'html/footer.html';
BD::deconnecter();
?></div>
    </form>    
</div>
</body>
</html>
<script>