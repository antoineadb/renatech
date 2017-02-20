<?php
session_start();
if(is_file('outils/toolBox.php')){
    include 'outils/toolBox.php';
}elseif(is_file('../outils/toolBox.php')){
    include '../outils/toolBox.php';
}
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
    $projetARelancer = $manager->getListbyArray("
    SELECT distinct on (p.numero) p.idprojet,p.datemaj,p.dureeprojet,p.refinterneprojet,p.numero,p.titre,p.datedebutprojet,u.nom,u.prenom,p.idperiodicite_periodicite,l.mail,
    u.idutilisateur,p.dateenvoiemail   FROM  projet p,utilisateur u,creer cr,centrale ce,concerne co,typeprojet t,statutprojet s, loginpassword l
    WHERE cr.idprojet_projet = p.idprojet AND cr.idutilisateur_utilisateur = u.idutilisateur AND co.idcentrale_centrale = ce.idcentrale AND co.idprojet_projet = p.idprojet AND  t.idtypeprojet = p.idtypeprojet_typeprojet 
    AND l.idlogin = u.idlogin_loginpassword AND s.idstatutprojet = co.idstatutprojet_statutprojet and ce.idcentrale = ? and s.idstatutprojet=? AND trashed =FALSE order by p.numero desc", array(IDCENTRALEUSER, ENCOURSREALISATION));
    $nbprojetARelancer = count($projetARelancer);
    $_SESSION['nbprojet'] = $nbprojetARelancer;
    $fpProjetEncoursRealisation = fopen('tmp/projetarelancer.json', 'w');
    $dataEncoursRealisation = "";
    fwrite($fpProjetEncoursRealisation, '{"items": [');
    for ($i = 0; $i < $nbprojetARelancer; $i++) {
        if (!empty($projetARelancer[$i]['datemaj'])) {
            $datemaj = $projetARelancer[$i]['datemaj'];
        } else {
            $datemaj = '';
        }
        
         if ($projetARelancer[$i]['idperiodicite_periodicite'] == JOUR) {
            $datedepart = strtotime($projetARelancer[$i]['datedebutprojet']);
            $duree = ($projetARelancer[$i]['dureeprojet']);
            $dateFin = date('Y-m-d', strtotime('+' . $duree . 'day', $datedepart));
         }elseif ($projetARelancer[$i]['idperiodicite_periodicite'] == MOIS) {
              $datedepart = strtotime($projetARelancer[$i]['datedebutprojet']);
            $duree = ($projetARelancer[$i]['dureeprojet']);
            $dateFin = date('Y-m-d', strtotime('+' . $duree . 'month', $datedepart));
         }elseif ($projetARelancer[$i]['idperiodicite_periodicite'] == ANNEE) {
              $datedepart = strtotime($projetARelancer[$i]['datedebutprojet']);
            $duree = ($projetARelancer[$i]['dureeprojet']);
            $dateFin = date('Y-m-d', strtotime('+' . $duree . 'year', $datedepart));
         }
        $dataEncoursRealisation = ""
                . '{"datedebutprojet":' . '"' . $projetARelancer[$i]['datedebutprojet'] . '"' . ","
                . '"datemaj":' . '"' . $datemaj . '"' . ","
                . '"numero":' . '"' . $projetARelancer[$i]['numero'] . '"' . ","                
                . '"idprojet":' . '"' . $projetARelancer[$i]['idprojet'] . '"' . ","
                . '"titre":' . '"' . filtredonnee($projetARelancer[$i]['titre']) . '"' . ","
                . '"mail":' . '"' . filtredonnee($projetARelancer[$i]['mail']) . '"' . ","
                . '"idutilisateur":' . '"' . $projetARelancer[$i]['idutilisateur'] . '"' . ","
                . '"nom":' . '"' . filtredonnee($projetARelancer[$i]['nom']).' '.filtredonnee($projetARelancer[$i]['prenom']) . '"' . ","
                . '"datefin":' . '"' . $dateFin . '"' . ","
                . '"dateheureenvoiemail":' . '"' . $projetARelancer[$i]['dateenvoiemail'] . '"' . ","
                . '"refinterneprojet":' . '"' . filtredonnee($projetARelancer[$i]['refinterneprojet']) . '"' . "},";
        fputs($fpProjetEncoursRealisation, $dataEncoursRealisation);
        fwrite($fpProjetEncoursRealisation, '');
    }
    fwrite($fpProjetEncoursRealisation, ']}');
    $json_fileEncoursRealisation = "tmp/projetarelancer.json";
    $jsonEncoursRealisation = file_get_contents($json_fileEncoursRealisation);
    $jsonEncoursRealisation1 = str_replace('},]}', '}]}', $jsonEncoursRealisation);
    file_put_contents($json_fileEncoursRealisation, $jsonEncoursRealisation1);
    fclose($fpProjetEncoursRealisation);
    chmod("tmp/projetarelancer.json", 0777);
    ?>
<div id="global">
    <?php
    include 'html/entete.html';
    ?>
    <div style="padding-top: 75px;">
        <?php include'outils/bandeaucentrale.php'; ?>
    </div>
    <script src='<?php echo '/'.REPERTOIRE; ?>/js/jquery-1.8.0.min.js'></script>
    <form  method="post"  id="formRelance" name="formRelance" 
           action="<?php 
                if(isset($_GET['cpt'])){
                    echo '/'.REPERTOIRE.'/modifBase/modifRelance.php?cpt='.$_GET['cpt'];                
                }else{
                    echo '/'.REPERTOIRE.'/modifBase/modifRelance.php';                   
                }?>" >
        <div style="margin-top:50px;width:1050px" >            
        <fieldset id="ident">
            <legend><?php echo TXT_PROJET;?><a class="infoBulle" href="#">&nbsp;<img src='<?php echo "/" . REPERTOIRE; ?>/styles/img/help.gif' ><span style="width: 322px;">
            <?php echo affiche('TXT_AIDERELANCEEMAIL'); ?></span></a></legend>
            <a id='aideRelance' href="<?php echo '/'.REPERTOIRE.'/downloadManual/aideContextuelleRelance.pdf' ?>" target="_blank">
                <img src="<?php echo '/'.REPERTOIRE; ?>/styles/img/infoStat.png" title="<?php echo TXT_AIDERELANCE; ?>"  >
            </a>
            <div>
            <div style="float: left"><a class="infoBulle" href="<?php echo '/' . REPERTOIRE . '/exportEncoursjson.php?lang=' . $lang ?>">&nbsp;
                    <img    src='<?php echo "/" . REPERTOIRE; ?>/styles/img/export.png' ><span style="width: 375px"><?php echo TXT_EXPORTPROJETCIDESSOUS; ?></span></a></div>
            </div>
            <div id='post'></div>
            <div style="margin-bottom: 75px;margin-top: 33px">
            <div style="float: left;">
                <input type="button" value="delete" label="<?php echo "Efface les dates d'envoi" ;?>" id="delete" name="delete"  data-dojo-type="dijit/form/Button" 
                       onclick="window.location.replace('<?php echo '/' . REPERTOIRE . '/modifBase/effaceDateEnvoiEmail.php?lang=' . $lang ?>')" />               
            </div>            
            <div style="float: right;margin-right: 20px"><input type="submit" value="Envoyer" label="<?php echo TXT_ENVOYERMAIL ;?>" id="EnvoyerEmail" name="Envoyer"  data-dojo-type="dijit/form/Button" style="width: 157px"/></div>
            </div>
            <div id='post' style="margin-bottom: 35px"></div>
            <input type="text" style="display: none" value='<?php if(!empty($_GET['cpt'])){echo $_GET['cpt'];}?>'  id='cpt' name='cpt'/>
            <?php if(isset($_GET['cpt'])&& !empty($_GET['cpt'])){?>
            <div id="msginfo"><?php echo TXT_VOUSAVEZENVOYER.' '. $_GET['cpt'].' '.TXT_MAIL.'s';?></div> 
            <?php }?>
             <?php if(isset($_GET['err'])&& $_GET['err']=='noselection'){?>
            <div id="msginfo"><?php echo TXT_ERREURENOSELECTION;?></div> 
            <?php }?>
            <?php include 'html/vueSuiviProjetRelance.html';?>
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