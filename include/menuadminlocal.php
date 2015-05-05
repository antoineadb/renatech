<?php
include_once 'decide-lang.php';
$indexchoix = '/' . REPERTOIRE . "/home/" . $lang;
$createProjet = '/' . REPERTOIRE . "/new_request/" . $lang;
$creerprojetphase2 = '/' . REPERTOIRE . "/new_project/" . $lang;
$controleSuiviProjetRespCentrale = '/' . REPERTOIRE . "/controler/controleSuiviProjetRespCentrale.php?lang=" . $lang."&page=1";
$controleSuiviProjet = '/' . REPERTOIRE . "/controler/controleSuiviProjet.php?lang=" . $lang;
$deleteprojets = '/' . REPERTOIRE . "/delete_projet/" . $lang;
$porteurprojet = '/' . REPERTOIRE . "/recherche_projet/" . $lang;
$adminprojet = '/' . REPERTOIRE . '/recherche_projet_admin/' . $lang;
$exportdesProjets = '/' . REPERTOIRE . "/exportdonneebrute/" . $lang;
$exportBilanProjet = '/' . REPERTOIRE . "/exportprojet/" . $lang;
$exportUtilisateur = '/' . REPERTOIRE . "/exportutilisateur/" . $lang;
$exportUtilisateurcentrale = '/' . REPERTOIRE . "/exportTousutilisateur/" . $lang;
$exportSultan='/' . REPERTOIRE . "/exportSultan/" . $lang;
$exportRapportWord='/' . REPERTOIRE . "/exportreport/" . $lang;

$moncompte = '/' . REPERTOIRE . '/moncompte/' . $lang;
$gestioncompte = '/' . REPERTOIRE . "/compteadmin/" . $lang;
$logout = '/' . REPERTOIRE . "/index/" . $lang . "/logout";
$statistique = '/' . REPERTOIRE . '/graph/' . $lang . '/ok';
$userPorteurProjet = '/' . REPERTOIRE . '/userPorteurProjet/' . $lang;
$traceprojet  = '/' . REPERTOIRE . '/traceProjet/' . $lang;
$projetperime = '/' . REPERTOIRE . '/oldProject/' . $lang;
$useradminprojet= '/' . REPERTOIRE . '/useradminprojet/' . $lang;
$traffic= '/'.REPERTOIRE.'/traffic/'.$lang;

?>
<div style="z-index: 5;">
    <ul class="menu" style="width:620px;margin-left:58px">
        <li><a href="<?php echo $indexchoix; ?>" style="font-weight: bold"><?php echo TXT_ACCUEIL; ?></a></li> 
        <li><a href="#" style="font-weight: bold"><?php echo '<u>' . TXT_PROJET . '</u>'; ?></a>
            <ul style="z-index: 5">
                <li><a href='<?php echo $createProjet; ?>' class="documents" style="font-size: 1.1em;font-weight: normal" ><?php echo TXT_CREERPROJET; ?></a></li>
                <li><a href="<?php echo $creerprojetphase2; ?>" class="messages" style="font-size: 1.1em;font-weight: normal" ><?php echo TXT_DEMANDEPROJET . '<br>'; ?></a></li>
                <li><a href="<?php echo $controleSuiviProjetRespCentrale; ?>" class="signout" style="font-size: 1.1em;font-weight: normal" ><?php echo TXT_LOGINPROJETCENTRALE; ?></a></li>
                <li><a href="<?php echo $controleSuiviProjet; ?>" class="signout" style="font-size: 1.1em;font-weight: normal" ><?php echo TXT_SUIVIPROJET; ?></a></li>
                <li><a href="<?php echo $deleteprojets; ?>" class="signout" style="font-size: 1.1em;font-weight: normal" ><?php echo TXT_DELETEPROJET; ?></a></li>                
                <li><a href="<?php echo $porteurprojet; ?>" class="signout" style="font-size: 1.1em;font-weight: normal" ><?php echo TXT_GESTIONPORTEUR; ?></a></li>
                <li><a href="<?php echo $userPorteurProjet; ?>" class="signout" style="font-size: 1.1em;font-weight: normal" ><?php echo TXT_VUEUSERPROJET; ?></a></li>
                <li><a href="<?php echo $adminprojet; ?>" class="signout" style="font-size: 1.1em;font-weight: normal" ><?php echo TXT_GESTIONADMIN; ?></a></li>
                <li><a href="<?php echo $useradminprojet; ?>" class="signout" style="font-size: 1.1em;font-weight: normal" ><?php echo TXT_VUEADMINPROJET; ?></a></li>

            </ul>

        </li>
        <li><a href="#" style="font-weight: bold"><?php echo '<u>' . TXT_EXPORTS . '</u>'; ?></a>
            <ul style="z-index: 5">
                <li><a href="<?php echo $exportdesProjets; ?>" class="documents" style="font-size: 1.1em;font-weight: normal"><?php echo TXT_EXPORTDONNEBRUTE; ?></a></li>
                <li><a href="<?php echo $exportBilanProjet; ?>" class="messages" style="font-size: 1.1em;font-weight: normal"><?php echo TXT_EXPORT; ?></a></li>
                <li><a href="<?php echo $exportUtilisateur; ?>" class="signout" style="font-size: 1.1em;font-weight: normal"><?php echo TXT_EXPORTUTILISATEURCENTRALE; ?></a></li>
                <li><a href="<?php echo $exportUtilisateurcentrale; ?>" class="signout" style="font-size: 1.1em;font-weight: normal"><?php echo TXT_EXPORTTOUSUTILISATEUR; ?></a></li>
                <li><a href="<?php echo $exportRapportWord; ?>" class="signout" style="font-size: 1.1em;font-weight: normal"><?php echo TXT_EXPORTREPORT; ?></a></li>
                <li><a href="<?php echo $exportSultan; ?>" class="signout" style="font-size: 1.1em;font-weight: normal"><?php echo TXT_EXPORTAPPLISULTAN; ?></a></li>
            </ul>

        </li>
        <li><a href="#" style="font-weight: bold"><?php echo '<u>' . TXT_COMPTES . '</u>'; ?></a>
            <ul style="z-index: 5">
                <li><a href="<?php echo $moncompte; ?>" class="documents" style="font-size: 1.1em;font-weight: normal" ><?php echo TXT_MONCOMPTE; ?></a></li>
                <li><a href="<?php echo $gestioncompte; ?>" class="messages" style="font-size: 1.1em;font-weight: normal" ><?php echo TXT_GESTIONCOMPTE; ?></a></li>												
            </ul>

        </li>
        <li><a href="#" style="font-weight: bold"><?php echo '<u>'.TXT_DIVERS.'</u>'; ?></a>
        <ul style="z-index: 5;  ">
            <li><a href="<?php echo $statistique;?>" class="messages" style="font-size: 1.1em;font-weight: normal" ><?php echo TXT_STATISTIQUE;?></a></li>
            <li><a href="<?php echo $traffic;?>" class="messages" style="font-size: 1.1em;font-weight: normal" ><?php echo TXT_TRAFFIC;?></a></li>
        </ul>
 
    </li>
        

        <li><a href="<?php echo $logout; ?>" style="font-weight: bold"><?php echo TXT_DECONNECTER; ?></a></li> 
    </ul>
</div>