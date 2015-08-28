<?php
include_once 'decide-lang.php';
$indexchoix = '/'.REPERTOIRE."/home/".$lang;
$createProjet = '/'.REPERTOIRE."/new_request/".$lang;
$creerprojetphase2 = '/'.REPERTOIRE."/new_project/".$lang;
$controleSuiviProjet = '/'.REPERTOIRE."/controler/controleSuiviProjet.php?lang=".$lang;
$moncompte = '/'.REPERTOIRE.'/moncompte/'.$lang;
$logout = '/'.REPERTOIRE."/index/".$lang."/logout";
include_once 'outils/toolBox.php';
include_once 'class/Manager.php';
$db = BD::connecter();
$manager = new Manager($db);
$idutilisateur= $manager->getSingle2("select idutilisateur from utilisateur,loginpassword where idlogin=idlogin_loginpassword and pseudo=?", $_SESSION['pseudo']);
$rowprojetadmin = $manager->getList2("select idprojet from utilisateurAdministrateur where idutilisateur=?", $idutilisateur);
$nbrowprojetadmin = count($rowprojetadmin);
$controleSuiviProjetadmin = '/'.REPERTOIRE.'/mes_projets_admin/'.$lang;
$parametrage= '/'.REPERTOIRE.'/param/'.$lang;
$manual= '/'.REPERTOIRE.'/manuel/'.$lang;
?>
<div style="z-index: 5;  margin-left: -178px;" >
        
            <ul class="menu" style="width:480px;margin-left:58px">
    <li><a href="<?php echo $indexchoix; ?>" style="font-weight: bold" ><?php echo TXT_ACCUEIL; ?></a></li>    
    <li><a href="#" style="font-weight: bold"><?php echo '<u>'. TXT_PROJET.'</u>'; ?></a>
        <ul style="z-index: 5">
            <li><a href='<?php echo $createProjet;?>' class="documents" style="font-size: 1.1em;font-weight: normal" ><?php echo TXT_CREERPROJET;?></a></li>
            <li><a href="<?php echo $creerprojetphase2;?>" class="messages" style="font-size: 1.1em;font-weight: normal" ><?php echo TXT_DEMANDEPROJET.'<br>';?></a></li>          
            <li><a href="<?php echo $controleSuiviProjet ;?>" class="signout" style="font-size: 1.1em;font-weight: normal" ><?php echo TXT_SUIVIPROJET;?></a></li>
            <?php if($nbrowprojetadmin>0){?>
            <li><a href="<?php echo $controleSuiviProjetadmin ;?>" class="signout" style="font-size: 1.1em;font-weight: normal" ><?php echo TXT_PROJETADMIN;?></a></li>
            <?php }?>
        </ul>
 
    </li>
     <li><a href="#" style="font-weight: bold"><?php echo '<u>'.TXT_DIVERS.'</u>'; ?></a>
        <ul style="z-index: 5;  ">            
            <li><a href="<?php echo $parametrage;?>" class="messages" style="font-size: 1.1em;font-weight: normal" ><?php echo TXT_PARAMETRAGE;?></a></li>
            <li><a href="<?php echo $manual;?>" class="messages" style="font-size: 1.1em;font-weight: normal" ><?php echo TXT_MANUAL;?></a></li>
        </ul>
     <li><a href="<?php echo $moncompte;?>" style="font-weight: bold"><?php echo TXT_COMPTES; ?></a> </li>
    <li><a href="<?php echo $logout; ?>" style="font-weight: bold"><?php echo TXT_DECONNECTER;?></a></li>
    
</ul>
</div>
<?php BD::deconnecter(); ?>