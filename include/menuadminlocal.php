<?php
include_once 'decide-lang.php';
$indexchoix = '/' . REPERTOIRE . "/home/" . $lang;
$creerprojetphase2 = '/' . REPERTOIRE . "/new_project/" . $lang;
$controleSuiviProjetRespCentrale = '/' . REPERTOIRE . "/controler/controleSuiviProjetRespCentrale.php?lang=" . $lang;
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
$parametrage= '/'.REPERTOIRE.'/param/'.$lang;
$projetSansDev = '/'.REPERTOIRE.'/noDevProject/'.$lang;
$viderCache = '/'.REPERTOIRE.'/videCache/'.$lang;
$manual= '/'.REPERTOIRE.'/manuel/'.$lang;
$log= '/'.REPERTOIRE.'/log/'.$lang;
$requete = '/'.REPERTOIRE.'/req/'.$lang;
$mail  = '/'.REPERTOIRE.'/relance/'.$lang;
$faisabilite= '/'.REPERTOIRE.'/new_request/'.$lang;
$nbfaisabilite= '/'.REPERTOIRE.'/nb_request/'.$lang;
$custmEmail =  '/'.REPERTOIRE.'/custom_email/'.$lang;
$gestionCentraleProximite = '/'.REPERTOIRE.'/admin_centrale_proximite/'.$lang;
$param_projet = '/'.REPERTOIRE.'/param_projet/'.$lang;
$export_json = '/'.REPERTOIRE.'/export_json/'.$lang;
?>
<div style="z-index: 5;">
    <ul class="menu" style="width:630px;margin-left:58px">
        <li><a href="<?php echo $indexchoix; ?>" style="font-weight: bold"><?php echo TXT_ACCUEIL; ?></a></li> 
        <li><a href="#" ><?php echo '<u>' . TXT_PROJET . '</u>'; ?></a>
            <ul style="z-index: 5">                
                <li><a href="<?php echo $creerprojetphase2; ?>" class="messages" ><?php echo ucfirst(TXT_DEMANDEPROJET) . '<br>'; ?></a></li>
                <li><a href="<?php echo $faisabilite; ?>" class="messages"  ><?php echo ucfirst(TXT_CREERPROJET); ?></a></li>
                <li><a href="<?php echo $controleSuiviProjetRespCentrale; ?>" class="messages"  ><?php echo TXT_LOGINPROJETCENTRALE; ?></a></li>
                <li><a href="<?php echo $projetSansDev; ?>" class="messages"  ><?php echo TXT_PROJETSANSDEV; ?></a></li>
                <li><a href="<?php echo $controleSuiviProjet; ?>" class="messages"  ><?php echo TXT_SUIVIPROJET; ?></a></li>
                <li><a href="<?php echo $deleteprojets; ?>" class="messages"  ><?php echo TXT_DELETEPROJET; ?></a></li>                
                <li><a href="<?php echo $porteurprojet; ?>" class="messages"  ><?php echo TXT_GESTIONPORTEUR; ?></a></li>
                <li><a href="<?php echo $userPorteurProjet; ?>" class="messages"  ><?php echo TXT_VUEUSERPROJET; ?></a></li>
                <li><a href="<?php echo $adminprojet; ?>" class="messages"  ><?php echo TXT_GESTIONADMIN; ?></a></li>
                <li><a href="<?php echo $useradminprojet; ?>" class="messages"  ><?php echo TXT_VUEADMINPROJET; ?></a></li>
                <li><a href="<?php echo $nbfaisabilite;?>" class="messages"  ><?php echo TXT_REQUESTVIEW;?></a></li>
                <li><a href="<?php echo $param_projet;?>" class="messages"  ><?php echo TXT_PARAM_PROJET;?></a></li>
            </ul>

        </li>
        <li><a href="#"><?php echo '<u>' . TXT_EXPORTS . '</u>'; ?></a>
            <ul style="z-index: 5">
                <li><a href="<?php echo $exportdesProjets; ?>" class="messages" ><?php echo TXT_EXPORTDONNEBRUTE; ?></a></li>
                <li><a href="<?php echo $exportBilanProjet; ?>" class="messages"  ><?php echo TXT_EXPORT; ?></a></li>
                <li><a href="<?php echo $exportUtilisateur; ?>" class="messages" ><?php echo TXT_EXPORTUTILISATEURCENTRALE; ?></a></li>
                <li><a href="<?php echo $exportUtilisateurcentrale; ?>" class="messages" ><?php echo TXT_EXPORTTOUSUTILISATEUR; ?></a></li>
                <li><a href="<?php echo $exportRapportWord; ?>" class="messages" ><?php echo TXT_EXPORTREPORT; ?></a></li>
                <li><a href="<?php echo $exportSultan; ?>" class="messages" ><?php echo TXT_EXPORTAPPLISULTAN; ?></a></li>
                <li><a href="<?php echo $export_json; ?>" class="messages" ><?php echo TXT_EXPORTJSON; ?></a></li>
            </ul>

        </li>
        <li><a href="#" ><?php echo '<u>' . TXT_COMPTES . '</u>'; ?></a>
            <ul style="z-index: 5">
                <li><a href="<?php echo $moncompte; ?>" class="messages"  ><?php echo TXT_MONCOMPTE; ?></a></li>
                <li><a href="<?php echo $gestioncompte; ?>" class="messages" ><?php echo ucfirst(TXT_GESTIONCOMPTE); ?></a></li>
                <li><a href="<?php echo $parametrage;?>" class="messages"  ><?php echo TXT_PARAMETRAGE;?></a></li>
            </ul>

        </li>
        <li><a href="#" ><?php echo '<u>'.TXT_DIVERS.'</u>'; ?></a>
        <ul style="z-index: 5;  ">
            <li><a href="<?php echo $statistique;?>" class="messages"  ><?php echo ucfirst(TXT_STATISTIQUE);?></a></li>
            <li><a href="<?php echo $traffic;?>" class="messages"  ><?php echo TXT_TRAFFIC;?></a></li>            
            <li><a href="<?php echo $viderCache;?>" class="messages"  ><?php echo TXT_VIDECACHE;?></a></li>
            <li><a href="<?php echo $manual;?>" class="messages"  ><?php echo TXT_MANUAL;?></a></li>
            <li><a href="<?php echo $log;?>" class="messages"  ><?php echo 'Logs';?></a></li>
            <li><a href="<?php echo $mail;?>" class="messages"  ><?php echo 'Relance par E-Mail';?></a></li>
            <li><a href="<?php echo $custmEmail;?>" class="messages"  > <?php echo TXT_CUSTOMRELANCEEMAIL;?></a></li>
            <li><a href="<?php echo $gestionCentraleProximite;?>" class="messages"  > <?php echo 'Administration des centrales de proximités ';?></a></li>
        </ul> 
    </li>
        <li><a href="<?php echo $logout; ?>" style="font-weight: bold"><?php echo TXT_DECONNECTER; ?></a></li> 
    </ul>
</div>