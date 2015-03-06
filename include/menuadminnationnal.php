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
?>
<div style="z-index: 5">
    <ul class="menu" style="width:620px;margin-left:58px">
    <li><a href="<?php echo $indexchoix; ?>" style="font-weight: bold" ><?php echo TXT_ACCUEIL; ?></a></li>    
    <li><a href="<?php echo $controleSuiviTousLesProjets; ?>" style="font-weight: bold"><?php echo TXT_PROJET; ?></a></li>
    <li><a href="#" style="font-weight: bold"><?php echo '<u>'.TXT_EXPORTS.'</u>'; ?></a>
       <ul style="z-index: 5;">            
            <li><a href="<?php echo $exportBilanProjet; ?>" class="messages" style="font-size: 1.1em;font-weight: normal"><?php echo TXT_EXPORT;?></a></li>
            <li><a href="<?php echo $exportUtilisateur; ?>" class="signout" style="font-size: 1.1em;font-weight: normal"><?php echo TXT_EXPORTUTILISATEUR;?></a></li>
        </ul> 
    </li>
    <li><a href="#" style="font-weight: bold"><?php echo '<u>'.TXT_COMPTES.'</u>'; ?></a>
        <ul style="z-index: 5;font-weight: normal">
            <li><a href="<?php echo $moncompte;?>" class="documents" style="font-size: 1.1em;font-weight: normal" ><?php echo TXT_MONCOMPTE;?></a></li>
            <li><a href="<?php echo $gestioncompte;?>" class="messages" style="font-size: 1.1em;font-weight: normal" ><?php echo TXT_GESTIONCOMPTE;?></a></li>
        </ul> 
    </li>
    <li><a href="#" style="font-weight: bold"><?php echo '<u>'.TXT_ADMIN.'</u>'; ?></a>
        <ul style="z-index: 5;  ">
            <li><a href="<?php echo $liste;?>" class="documents" style="font-size: 1.1em;font-weight: normal" ><?php echo TXT_LISTE;?></a></li>
            <li><a href="<?php echo $libelle;?>" class="messages" style="font-size: 1.1em;font-weight: normal" ><?php echo TXT_GESTIONLIBELLE;?></a></li>
            <li><a href="<?php echo $statistique;?>" class="messages" style="font-size: 1.1em;font-weight: normal" ><?php echo TXT_STATISTIQUE;?></a></li>
            <li><a href="<?php echo $traffic;?>" class="messages" style="font-size: 1.1em;font-weight: normal" ><?php echo TXT_TRAFFIC;?></a></li>
        </ul>
 
    </li>
    <li><a href="<?php echo $logout; ?>" style="font-weight: Bold"><?php echo TXT_DECONNECTER;?></a></li>
</ul>
</div>