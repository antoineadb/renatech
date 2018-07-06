<?php
include_once 'decide-lang.php';
$indexchoix = '/'.REPERTOIRE."/home/".$lang;
$logout = '/'.REPERTOIRE."/index/".$lang."/logout";
$gestioncompte = '/'.REPERTOIRE.'/compteadmin/'.$lang;
$moncompte = '/'.REPERTOIRE.'/moncompte/'.$lang;
$liste = '/'.REPERTOIRE.'/liste/'.$lang;
$libelle = '/'.REPERTOIRE.'/libelle/'.$lang;
$traffic= '/'.REPERTOIRE.'/traffic/'.$lang;
$parametrage= '/'.REPERTOIRE.'/param/'.$lang;
$log= '/'.REPERTOIRE.'/log/'.$lang;
$viderCache = '/'.REPERTOIRE.'/videCache/'.$lang;
$adminAppli  = '/' . REPERTOIRE . "/adminApplication/" . $lang;
$manual= '/'.REPERTOIRE.'/manuel/'.$lang;

?>
<div style="z-index: 5">
    <ul class="menu" style="width:480px;margin-left:58px">
    <li><a href="<?php echo $indexchoix; ?>" style="font-weight: bold" ><?php echo TXT_ACCUEIL; ?></a></li>         
    <li><a href="#" style="font-weight: bold"><?php echo '<u>'.TXT_COMPTES.'</u>'; ?></a>
        <ul style="z-index: 5;font-weight: normal">
            <li><a href="<?php echo $moncompte;?>" class="documents" style="font-size: 1.1em;font-weight: normal" ><?php echo TXT_MONCOMPTE;?></a></li>
            <li><a href="<?php echo $gestioncompte;?>" class="messages" style="font-size: 1.1em;font-weight: normal" ><?php echo ucfirst(TXT_GESTIONCOMPTE);?></a></li>
            <li><a href="<?php echo $parametrage;?>" class="messages" style="font-size: 1.1em;font-weight: normal" ><?php echo TXT_PARAMETRAGE;?></a></li>
        </ul> 
    </li>
    <li><a href="#" style="font-weight: bold"><?php echo '<u>'.TXT_ADMIN.'</u>'; ?></a>
        <ul style="z-index: 5;  ">
            <li><a href="<?php echo $liste;?>" class="documents" style="font-size: 1.1em;font-weight: normal" ><?php echo ucfirst(TXT_LISTE);?></a></li>
            <li><a href="<?php echo $libelle;?>" class="messages" style="font-size: 1.1em;font-weight: normal" ><?php echo TXT_GESTIONLIBELLE;?></a></li>            
            <li><a href="<?php echo $traffic;?>" class="messages" style="font-size: 1.1em;font-weight: normal" ><?php echo TXT_TRAFFIC;?></a></li>            
            <li><a href="<?php echo $manual;?>" class="messages" style="font-size: 1.1em;font-weight: normal" ><?php echo TXT_MANUAL;?></a></li>
            <li><a href="<?php echo $log;?>" class="messages" style="font-size: 1.1em;font-weight: normal" ><?php echo 'Logs';?></a></li>
            <li><a href="<?php echo $viderCache;?>" class="messages" style="font-size: 1.1em;font-weight: normal" ><?php echo TXT_VIDECACHE;?></a></li>
            <li><a href="<?php echo $adminAppli;?>" class="messages" style="font-size: 1.1em;font-weight: normal" ><?php echo TXT_ADMINAPPLI;?></a></li>
        </ul>
 
    </li>
    <li><a href="<?php echo $logout; ?>" style="font-weight: Bold"><?php echo TXT_DECONNECTER;?></a></li>
</ul>
</div>