<?php
include_once 'decide-lang.php';
$indexchoix = '/'.REPERTOIRE."/home/".$lang;
$exportUtilisateur = '/'.REPERTOIRE."/exportutilisateur/".$lang;
$exportBilanProjet = '/'.REPERTOIRE."/exportprojet/".$lang;
$logout = '/'.REPERTOIRE."/index/".$lang."/logout";
$gestioncompte = '/'.REPERTOIRE.'/compteadmin/'.$lang;
$moncompte = '/'.REPERTOIRE.'/moncompte/'.$lang;
$liste = '/'.REPERTOIRE.'/liste/'.$lang;
$libelle = '/'.REPERTOIRE.'/libelle/'.$lang;
$statistique = '/'.REPERTOIRE.'/graph/'.$lang.'/ok';
$controleSuiviTousLesProjets = '/'.REPERTOIRE."/controler/controleSuiviTousLesProjets.php?lang=".$lang;
$traffic= '/'.REPERTOIRE.'/traffic/'.$lang;
$exportenquete ='/'.REPERTOIRE.'/enquete/'.$lang;
$exportRapportWord='/' . REPERTOIRE . "/exportreport/" . $lang;
$Refusedprojects ='/'.REPERTOIRE."/controler/controleProjetRefuser.php?lang=".$lang;
$parametrage= '/'.REPERTOIRE.'/param/'.$lang;
$vueCentraleAN = '/'.REPERTOIRE.'/vueCentraleAN/'.$lang;
$manual= '/'.REPERTOIRE.'/manuel/'.$lang;
$manual= '/'.REPERTOIRE.'/manuel/'.$lang;
$log= '/'.REPERTOIRE.'/log/'.$lang;
$viderCache = '/'.REPERTOIRE.'/videCache/'.$lang;
$nbfaisabilite= '/'.REPERTOIRE.'/nb_request/'.$lang;
$chgtcompte= '/'.REPERTOIRE.'/switch/'.$lang;
?>
<div style="z-index: 5">
    <ul class="menu" style="width:630px;margin-left:58px">
    <li><a href="<?php echo $indexchoix; ?>" style="font-weight: bold" ><?php echo TXT_ACCUEIL; ?></a></li>    
    
    <li><a href="#" style="font-weight: bold"><?php echo '<u>'.TXT_PROJET.'</u>'; ?></a>
       <ul style="z-index: 5;">
            <li><a href="<?php echo $controleSuiviTousLesProjets; ?>" style="font-size: 1.1em;font-weight: normal"><?php echo TXT_VUEPROJET; ?></a></li>
            <li><a href="<?php echo $vueCentraleAN; ?>" style="font-size: 1.1em;font-weight: normal"><?php echo TXT_VUEPROJETCENTRALE; ?></a></li>
            <li><a href="<?php echo $Refusedprojects; ?>" style="font-size: 1.1em;font-weight: normal"><?php echo TXT_VUEPROJETREFUSE; ?></a></li>
            <li><a href="<?php echo $nbfaisabilite;?>" class="messages" style="font-size: 1.1em;font-weight: normal" ><?php echo TXT_REQUESTVIEW;?></a></li>
        </ul>
    </li>    
    <li><a href="#" style="font-weight: bold"><?php echo '<u>'.TXT_EXPORTS.'</u>'; ?></a>
       <ul style="z-index: 5;">            
            <li><a href="<?php echo $exportBilanProjet; ?>" class="messages" style="font-size: 1.1em;font-weight: normal"><?php echo TXT_EXPORT;?></a></li>
            <li><a href="<?php echo $exportUtilisateur; ?>" class="signout" style="font-size: 1.1em;font-weight: normal"><?php echo TXT_EXPORTUTILISATEUR;?></a></li>
            <li><a href="<?php echo $exportenquete; ?>" class="signout" style="font-size: 1.1em;font-weight: normal"><?php echo TXT_EXPORTENQUETE;?></a></li>
            <li><a href="<?php echo $exportRapportWord; ?>" class="signout" style="font-size: 1.1em;font-weight: normal"><?php echo TXT_EXPORTREPORT; ?></a></li>
            <li><a href="<?php echo $manual;?>" class="messages" style="font-size: 1.1em;font-weight: normal" ><?php echo TXT_MANUAL;?></a></li>
        </ul> 
    </li>
    <li><a href="#" style="font-weight: bold"><?php echo '<u>'.TXT_COMPTES.'</u>'; ?></a>
        <ul style="z-index: 5;font-weight: normal">
            <li><a href="<?php echo $moncompte;?>" class="documents" style="font-size: 1.1em;font-weight: normal" ><?php echo TXT_MONCOMPTE;?></a></li>
            <li><a href="<?php echo $gestioncompte;?>" class="messages" style="font-size: 1.1em;font-weight: normal" ><?php echo ucfirst(TXT_GESTIONCOMPTE);?></a></li>
            <li><a href="<?php echo $chgtcompte;?>" class="messages" style="font-size: 1.1em;font-weight: normal" ><?php echo "Changement de compte";?></a></li>
        </ul> 
    </li>
    <li><a href="#" style="font-weight: bold"><?php echo '<u>'.TXT_ADMIN.'</u>'; ?></a>
        <ul style="z-index: 5;  ">
            <li><a href="<?php echo $liste;?>" class="documents" style="font-size: 1.1em;font-weight: normal" ><?php echo ucfirst(TXT_LISTE);?></a></li>
            <li><a href="<?php echo $libelle;?>" class="messages" style="font-size: 1.1em;font-weight: normal" ><?php echo TXT_GESTIONLIBELLE;?></a></li>
            <li><a href="<?php echo $statistique;?>" class="messages" style="font-size: 1.1em;font-weight: normal" ><?php echo ucfirst(TXT_STATISTIQUE);?></a></li>
            <li><a href="<?php echo $traffic;?>" class="messages" style="font-size: 1.1em;font-weight: normal" ><?php echo TXT_TRAFFIC;?></a></li>
            <li><a href="<?php echo $parametrage;?>" class="messages" style="font-size: 1.1em;font-weight: normal" ><?php echo TXT_PARAMETRAGE;?></a></li>
             <li><a href="<?php echo $manual;?>" class="messages" style="font-size: 1.1em;font-weight: normal" ><?php echo TXT_MANUAL;?></a></li>
            <li><a href="<?php echo $log;?>" class="messages" style="font-size: 1.1em;font-weight: normal" ><?php echo 'Logs';?></a></li>
             <li><a href="<?php echo $viderCache;?>" class="messages" style="font-size: 1.1em;font-weight: normal" ><?php echo TXT_VIDECACHE;?></a></li>
        </ul>
 
    </li>
    <li><a href="<?php echo $logout; ?>" style="font-weight: Bold"><?php echo TXT_DECONNECTER;?></a></li>
</ul>
</div>