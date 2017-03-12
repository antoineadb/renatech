<?php

session_start();
include 'decide-lang.php';
include_once 'outils/constantes.php';
include_once 'class/Securite.php';
include_once 'class/Manager.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include_once 'outils/toolBox.php';
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
if (isset($_GET['numProjet'])) {
    $numprojet = $_GET['numProjet'];
    $idprojet = $manager->getSingle2("select idprojet from projet where numero=?", $numprojet);
} elseif (isset($_GET['idprojet'])) {
    $idprojet = $_GET['idprojet'];
}
$monUrl = "http://" . $_SERVER['HTTP_HOST'];

$resprojet = $manager->getList2(
        "select titre,acronyme,numero,confidentiel,description,dateprojet,contexte,commentaire,attachement,attachementdesc,contactscentraleaccueil,datedebuttravaux,dureeprojet,centralepartenaire,verrouidentifiee,nbplaque,nbrun,envoidevis,
emailrespdevis,reussite,refinterneprojet,idtypeprojet_typeprojet,idthematique_thematique,idperiodicite_periodicite,typeformation,nbheure,centralepartenaireprojet,idautrethematique_autrethematique,descriptiftechnologique,
devtechnologique,nbeleve,nomformateur,datedebutprojet,datestatutfini,datestatutcloturer,acrosourcefinancement,partenaire1,porteurprojet,idetat_etatprojet,dureeestime,periodestime,etapeautrecentrale,descriptionautrecentrale,
centraleproximite,datemaj,descriptioncentraleproximite from projet where idprojet=?", $idprojet);

$arraylibelle = array('titre','acronyme','numero','confidentiel','description','dateprojet','contexte','commentaire','attachement','attachementdesc','contactscentraleaccueil','datedebuttravaux','dureeprojet','centralepartenaire','verrouidentifiee','nbplaque','nbrun','envoidevis',
'emailrespdevis','reussite','refinterneprojet','idtypeprojet_typeprojet','idthematique_thematique','idperiodicite_periodicite','typeformation','nbheure','centralepartenaireprojet','idautrethematique_autrethematique','descriptiftechnologique',
'devtechnologique','nbeleve','nomformateur','datedebutprojet','datestatutfini','datestatutcloturer','acrosourcefinancement','partenaire1','porteurprojet','idetat_etatprojet','dureeestime','periodestime','etapeautrecentrale','descriptionautrecentrale',
'centraleproximite','datemaj','descriptioncentraleproximite');
//Définition des variables
for ($i = 0; $i < count($arraylibelle); $i++) {
    if(!empty($resprojet[0][$arraylibelle[$i]])){        
        if(gettype($resprojet[0][$arraylibelle[$i]]) =='string'){
            $$arraylibelle[$i] = stripslashes(strip_tags(removeDoubleQuote(filterEditor(filterEditor2($resprojet[0][$arraylibelle[$i]])))));
        }elseif(gettype($resprojet[0][$arraylibelle[$i]]) =='integer'){
            $$arraylibelle[$i]=$resprojet[0][$arraylibelle[$i]];
        }elseif(gettype($resprojet[0][$arraylibelle[$i]]) =='boolean'){
            $$arraylibelle[$i] = $resprojet[0][$arraylibelle[$i]];
            if($resprojet[0][$arraylibelle[$i]]==1){
                $$arraylibelle[$i]= ucfirst(TXT_OUI);
            }else{
                $$arraylibelle[$i]=ucfirst(TXT_NON);
            }
            
        }
    }else{
        $$arraylibelle[$i]=null;
    }
}

$libelleStatut = removeDoubleQuote($manager->getSingle2("select libellestatutprojet from statutprojet,concerne where idstatutprojet_statutprojet =idstatutprojet and idprojet_projet=?", $idprojet));
$arraylibellecentrale= $manager->getList2("SELECT libellecentrale from centrale,concerne where idcentrale_centrale=idcentrale and idprojet_projet=?", $idprojet);
$rowdemandeur = $manager->getList2("SELECT nom, prenom,mail,telephone,adresse,codepostal,ville,nompays,nomresponsable,mailresponsable,acronymelaboratoire,entrepriselaboratoire FROM utilisateur, projet, creer,loginpassword,pays WHERE idutilisateur_utilisateur = idutilisateur and idlogin=idlogin_loginpassword "
        . "AND idprojet_projet = idprojet and idprojet=? and idpays=idpays_pays", $idprojet);
$demandeur = ($rowdemandeur[0]['nom']) . ' -  ' . ($rowdemandeur[0]['prenom']).' -  ' . $rowdemandeur[0]['mail']. ' <br>  ' .  removeDoubleQuote(($rowdemandeur[0]['adresse'])). ' -  ' . $rowdemandeur[0]['codepostal']. ' -  ' . ($rowdemandeur[0]['ville']).
        ' -  ' . $rowdemandeur[0]['nompays'];
if(!empty($rowdemandeur[0]['nomresponsable'])){
    $sonresponsable = $rowdemandeur[0]['nomresponsable'] . ' -  ' . $rowdemandeur[0]['mailresponsable'];
}
if(!empty($rowdemandeur[0]['acronymelaboratoire'])){
    $acronymelaboratoire = trim($rowdemandeur[0]['acronymelaboratoire']);
}
if(!empty($rowdemandeur[0]['entrepriselaboratoire'])){
    $entrepriselaboratoire = $rowdemandeur[0]['entrepriselaboratoire'];
}
$arrayporteur = $manager->getList2("SELECT nom,prenom,mail,telephone FROM utilisateurporteurprojet,utilisateur,loginpassword WHERE idutilisateur_utilisateur = idutilisateur and idlogin= idlogin_loginpassword
and idprojet_projet=?", $idprojet);
$sPorteur = '';
for ($i = 0; $i < count($arrayporteur); $i++) {
    $sPorteur.=TXT_NOM . ': ' . $arrayporteur[$i]['nom'] . '<br>' . TXT_PRENOM . ': ' . $arrayporteur[$i]['prenom'] . '<br>' . TXT_MAIL . ': ' . $arrayporteur[$i]['mail'] . ' ' . '<br>' . TXT_TELEPHONE . ': ' . $arrayporteur[$i]['telephone'] . '<br>' . '<br>';
}
if(!empty($arrayporteur)){
    $sporteur = substr($sPorteur, 0, -3);
}else{
    $sporteur = substr($sPorteur, 0, -4);
}

if ($lang == 'fr') {
    $libelleType = $manager->getSingle2("select libelletype from typeprojet where idtypeprojet=?", $idtypeprojet_typeprojet);
} elseif ($lang == 'en') {
    $libelleType = $manager->getSingle2("select libelletypeen from typeprojet where idtypeprojet=?", $idtypeprojet_typeprojet);
}
if($lang=='fr'){
    $unitedureeprojet = $manager->getSingle2("select libelleperiodicite from period where idperiodicite = ?", $idperiodicite_periodicite);
}else{
    $unitedureeprojet = $manager->getSingle2("select libelleperiodiciteen from period where idperiodicite = ?", $idperiodicite_periodicite);
}
$arraySourcefinancement = $manager->getList2("SELECT idsourcefinancement,libellesourcefinancement,libellesourcefinancementen,acronymesource FROM   projetsourcefinancement,sourcefinancement
WHERE idsourcefinancement_sourcefinancement = idsourcefinancement  and idprojet_projet=?", $idprojet);
$S_sourcefinancement = '';
$S_acrosourcefinancement = '';
for ($i = 0; $i < count($arraySourcefinancement); $i++) {
    if (!empty($arraySourcefinancement[$i]['idsourcefinancement'])) {
        if ($lang == 'fr') {
            $sourcefinancement = removeDoubleQuote(str_replace("’", "''", $arraySourcefinancement[$i]['libellesourcefinancement']));
            if (!empty($arraySourcefinancement[$i]['acronymesource'])) {
                $acrosource = removeDoubleQuote(str_replace("’", "''", $arraySourcefinancement[$i]['acronymesource']));
                $S_sourcefinancement.=$sourcefinancement . ' -> '.$acrosource.', ';
            }else{
                $S_sourcefinancement.=$sourcefinancement . ', ';
            }
        } elseif ($lang == 'en') {
            $sourcefinancement = removeDoubleQuote(str_replace("’", "''", $arraySourcefinancement[$i]['libellesourcefinancementen']));
            if (!empty($arraySourcefinancement[$i]['acronymesource'])) {
                $acrosource = removeDoubleQuote(str_replace("’", "''", $arraySourcefinancement[$i]['acronymesource']));
                $S_sourcefinancement.=$sourcefinancement . ' -> '.$acrosource.', ';
            }else{
                $S_sourcefinancement.=$sourcefinancement . ', ';
            }
        }
    }
}
$s_sourcefinancement = substr(trim($S_sourcefinancement), 0, -1);

$partenaireprojet = $manager->getList2("SELECT nompartenaire,nomlaboentreprise FROM projet,projetpartenaire,partenaireprojet WHERE  idprojet_projet = projet.idprojet AND
  idpartenaire = idpartenaire_partenaireprojet  and idprojet_projet=?", $idprojet);
$S_nompartenaire = '';
if (!empty($resprojet[0]['centralepartenaireprojet'])) {
    $centralepartenaireprojet = removeDoubleQuote(str_replace("’", "''", $resprojet [0]['centralepartenaireprojet']));
}
for ($i = 0; $i < count($partenaireprojet); $i++) {
    if (!empty($partenaireprojet[$i]['nompartenaire'])) {
        $nompartenaire = removeDoubleQuote(str_replace("’", "''", $partenaireprojet [$i]['nompartenaire']));        
        $S_nompartenaire.=$nompartenaire . ', ';
    }
}
$s_nompartenaire = substr(trim($S_nompartenaire), 0, -1);
$S_nomlaboentreprise = '';
for ($i = 0; $i < count($partenaireprojet); $i++) {
    if (!empty($partenaireprojet[$i]['nomlaboentreprise'])) {
        $nomlaboentreprise = removeDoubleQuote(str_replace("’", "''", $partenaireprojet [$i]['nomlaboentreprise']));
        $S_nomlaboentreprise.=$nomlaboentreprise . ', ';
    }
}
$s_nomlaboentreprise = substr(trim($S_nomlaboentreprise), 0, -1);

if (!empty($resprojet[0]['partenaire1'])) {
    $partenaire1 = removeDoubleQuote(str_replace("’", "''", $resprojet [0]['partenaire1']));
}
if (!empty($partenaire1)) {
    $partenaire = stripslashes(trim($partenaire1)) . ', ' . stripslashes($s_nompartenaire);
} else {
    $partenaire = stripslashes($s_nompartenaire);
}
if($lang=='fr'){
    $libelleThematique = $manager->getSingle2("select libellethematique from thematique where idthematique=?", $idthematique_thematique);
}else{
    $libelleThematique = $manager->getSingle2("select libellethematiqueen from thematique where idthematique=?", $idthematique_thematique);
}

if($lang=='fr'){
    $uniteperiodeestime = $manager->getSingle2("select libelleperiodicite from period where idperiodicite = ?", $periodestime	);
}else{
    $uniteperiodeestime = $manager->getSingle2("select libelleperiodiciteen from period where idperiodicite = ?", $periodestime	);
}

$arraypersonneacceuil = $manager->getList2("SELECT nomaccueilcentrale,prenomaccueilcentrale,idqualitedemandeuraca_qualitedemandeuraca,mailaccueilcentrale FROM projetpersonneaccueilcentrale,personneaccueilcentrale "
        . "WHERE idpersonneaccueilcentrale_personneaccueilcentrale = idpersonneaccueilcentrale AND  idprojet_projet=?", $idprojet);
$personneaccueil = '';


for ($i = 0; $i < count($arraypersonneacceuil); $i++) {    
    if($lang=='fr'){
        $libellequalitedemandeuraca = $manager->getSingle2("select libellequalitedemandeuraca from qualitedemandeuraca where idqualitedemandeuraca =?", $arraypersonneacceuil[$i]['idqualitedemandeuraca_qualitedemandeuraca']);
    }else{
        $libellequalitedemandeuraca = $manager->getSingle2("select libellequalitedemandeuracaen from qualitedemandeuraca where idqualitedemandeuraca =?", $arraypersonneacceuil[$i]['idqualitedemandeuraca_qualitedemandeuraca']);
    }    
    $personneaccueil.=$arraypersonneacceuil[$i]['nomaccueilcentrale'] . ' - ' . $arraypersonneacceuil[$i]['prenomaccueilcentrale'] . ' - '.$libellequalitedemandeuraca.' - '.$arraypersonneacceuil[$i]['mailaccueilcentrale'] . ' - ' ;
}
if (count($arraypersonneacceuil) > 0) {
    $personneaccueil = substr($personneaccueil, 0, -2);
}

$arrayressource = $manager->getList2("SELECT libelleressource,libelleressourceen FROM ressourceprojet,ressource WHERE  idressource_ressource = idressource and idprojet_projet =?", $idprojet);

$slibelleRessource = '';
for ($i = 0; $i < count($arrayressource); $i++) {    
        if($lang == 'fr'){
           $slibelleRessource.= $arrayressource[$i]['libelleressource'].' - ';
        }else{
           $slibelleRessource.= $arrayressource[$i]['libelleressourceen'].' - '; 
        }
}
$slibelleressource = substr($slibelleRessource,0,-2);

$arrayautrecentrale=$manager->getList2("SELECT c.libellecentrale FROM projetautrecentrale p,centrale c WHERE p.idcentrale = c.idcentrale and p.idprojet = ?", $idprojet);
$slibelleautrecentrale='';
for ($i = 0; $i < count($arrayautrecentrale); $i++) {
    $slibelleautrecentrale.=$arrayautrecentrale[$i]['libellecentrale'].', ';
}
$slibelleAutreCentrale = substr($slibelleautrecentrale,0,-2);

$arraycentraleproximite = $manager->getList2("SELECT cp.nom_centrale_proximite FROM projet_centraleproximite pc,centrale_proximite cp WHERE  pc.idcentrale_proximite = cp.idcentrale_proximite and pc.idprojet=?", $idprojet);
$scentraleproximite ='';
for ($i = 0; $i < count($arraycentraleproximite); $i++) {
    $scentraleproximite .=$arraycentraleproximite[$i]['nom_centrale_proximite'].', ';
}
$scentraleProximite =  substr($scentraleproximite, 0.,-2);
if($confidentiel != ucfirst(TXT_OUI)){
   $confidentiel=  ucfirst(TXT_NON); 
}


ob_start();
?>
<style type="text/css">
    *{color:#5D5D5E;}
    table{ vertical-align: top;width:100%;font-size: 10pt;font-family: helvetica;line-height: 5mm;}
    strong{color:#000;}
    p{margin: 0;padding: 0;font-size: 18px;font-weight: bold;}
    hr{height:2px;background: #5D5D5E;border:none}
</style>

<page backtop="1mm" backleft="5mm" backright="5mm" backbottom="1mm" footer="date;heure;page">
    <table>
        <tr>
        <td style="width: 70%; "><img style="width: 100%;"  src="./styles/img/logo-renatech.jpg" ></td>
        <td style="width: 30%; padding-top:20px">
             <?php if(count($arraylibellecentrale)>1){
                 echo TXT_TITREDOCUMENT1;
             }else{                 
                 echo TXT_TITREDOCUMENT0 . ':  <b>' .$arraylibellecentrale[0]['libellecentrale'].'</b>';
             }
                 ?>    
            
        </td>
    </tr>
    </table>
    <br />
    <table  align="center">
        <tr>
        <td><p><?php echo $titre; ?></p></td>        
        </tr>
    </table> <br /><br /> 
    
    <table><tr><td><strong><?php echo TXT_STATUTPROJETS.': ';?></strong><?php echo $libelleStatut; ?><strong style="margin-left: 200px"><?php echo TXT_NUMPROJET.': ';?></strong><?php echo $numero; ?></td></tr>
    <tr><td><strong><?php echo TXT_DEMANDEUR.': ';?></strong><?php echo $demandeur; ?></td></tr><tr><td></td></tr>
                    <?php if(!empty($sonresponsable)){?>
                    <tr><td><strong><?php echo  TXT_NOMMAILRESPON .': ';?></strong><?php echo $sonresponsable; ?></td></tr><tr><td></td></tr>
                    <?php } ?>
                    <?php if(!empty($porteur)){?>
                    <tr><td><strong><?php echo  TXT_PORTEURS.':<br /> ';?></strong><?php echo $sporteur; ?></td></tr><tr><td></td></tr>
                    <?php }
                    if(!empty($acronymelaboratoire)){?>
                        <tr><td><strong><?php echo  TXT_NOMLABO.': ';?></strong><?php echo $acronymelaboratoire; ?></td></tr><tr><td></td></tr>
                    <?php }
                        if(!empty($entrepriselaboratoire)){?>
                            <tr><td><strong><?php echo  TXT_LABODEMANDEUR.': ';?></strong><?php echo $entrepriselaboratoire; ?></td></tr><tr><td></td></tr>
                        <?php }
                    ?>
    </table><br />
    <hr />
<fieldset style="border: none">
    <legend style="border: none"><?php echo '<p>'.TXT_DESCRIPTIONSUCCINTE.': </p>' ?></legend><br />
    <table>
        <tr>
            <td style='width:75%;'>
                <table style="">
                    <tr><td></td></tr><tr><td></td></tr>
                    <?php if(!empty($acronyme)){?>
                    <tr><td><strong><?php echo TXT_ACRONYME.': ';?></strong><?php echo $acronyme; ?></td></tr><tr><td></td></tr>
                    <?php } ?>                    
                    <tr><td><strong><?php echo TXT_PROJETCONFIDENTIEL.': ';?></strong><?php echo $confidentiel; ?></td></tr><tr><td></td></tr>
                     <tr><td><strong><?php echo TXT_CONTEXTESCIENTIFIQUE.': ';?></strong><?php echo '<br />'.$contexte; ?></td></tr><tr><td></td></tr>
                     <tr><td><strong><?php echo TXT_DESCRIPTIFTRAVAIL.': ';?></strong><?php echo '<br />'.$description; ?></td></tr><tr><td></td></tr>
                      <?php if(!empty($attachement)){?>
                    <tr><td><strong><?php echo  TXT_PIECEJOINTE .': ';?></strong><?php echo $attachement; ?></td></tr><tr><td></td></tr>
                    <?php }  ?>
                </table>
            </td>            
        </tr>
    </table>   
</fieldset>
<br /> 
</page>
<page backtop="1mm" backleft="5mm" backright="2mm" backbottom="1mm" footer="date;heure;page">
        <table>
        <tr>
        <td style="width: 70%; "><img style="width: 100%;"  src="./styles/img/logo-renatech.jpg" ></td>
        <td style="width: 30%; padding-top:20px">
             <?php if(count($arraylibellecentrale)>1){
                 echo TXT_TITREDOCUMENT1;
             }else{                 
                 echo TXT_TITREDOCUMENT0 . ':  <b>' .$arraylibellecentrale[0]['libellecentrale'].'</b>';
             }
                 ?>    
            
        </td>
    </tr>
    </table>
    <br />
    <table  align="center">
        <tr>
        <td><p><?php echo $titre; ?></p></td>        
        </tr>
    </table> <br /><br />  
    <table><tr><td><strong><?php echo TXT_STATUTPROJETS.': ';?></strong><?php echo $libelleStatut; ?><strong style="margin-left: 200px"><?php echo TXT_NUMPROJET.': ';?></strong><?php echo $numero; ?></td></tr>
    <tr><td><strong><?php echo TXT_DEMANDEUR.': ';?></strong><?php echo $demandeur; ?></td></tr><tr><td></td></tr>
                    <?php if(!empty($sonresponsable)){?>
                    <tr><td><strong><?php echo  TXT_NOMMAILRESPON .': ';?></strong><?php echo $sonresponsable; ?></td></tr><tr><td></td></tr>
                    <?php } ?>
                    <?php if(!empty($porteur)){?>
                    <tr><td><strong><?php echo  TXT_PORTEURS.':<br /> ';?></strong><?php echo $sporteur; ?></td></tr><tr><td></td></tr>
                    <?php }
                    if(!empty($acronymelaboratoire)){?>
                        <tr><td><strong><?php echo  TXT_NOMLABO.': ';?></strong><?php echo $acronymelaboratoire; ?></td></tr><tr><td></td></tr>
                    <?php }?>
    </table><br />
    <hr />
<fieldset style="border: none">
    <legend style="border: none"><?php echo '<p>'.TXT_DESCRIPTIONDETAILLE.': </p>' ?></legend><br />
    <table>
        <tr>
            <td style='width:75%;'>
                <table>
                    
                    <?php if(!empty($refinterneprojet)){?>
                    <tr><td><strong><?php echo TXT_REFINTERNE.': ';?></strong><?php echo $refinterneprojet; ?></td></tr><tr><td></td></tr>
                    <?php } ?>
                    <?php if(!empty($contactscentraleaccueil)){?>
                    <tr><td><strong><?php echo  TXT_CONTACTCENTRALACCUEIL .': ';?></strong><?php echo $contactscentraleaccueil; ?></td></tr><tr><td></td></tr>
                    <?php } ?>
                    <?php if($libelleType != NATYPEPROJET){?>
                        <tr><td><strong><?php echo  TXT_TYPEPROJET.': ';?></strong><?php echo $libelleType; ?></td></tr><tr><td></td></tr>
                        <?php if($idtypeprojet_typeprojet==TYPEFORMATION){?>
                            <tr><td><strong><?php echo  TXT_NBELEVE.': ';?></strong><?php echo $nbeleve; ?></td></tr><tr><td></td></tr>
                            <tr><td><strong><?php echo  TXT_NBHEURE.': ';?></strong><?php echo $nbheure; ?></td></tr><tr><td></td></tr>
                            <tr><td><strong><?php echo  TXT_NOMFORMATEUR.': ';?></strong><?php echo $nomformateur; ?></td></tr><tr><td></td></tr>
                        <?php } ?>
                    <?php } ?>
               
                  <?php  if(!empty($dureeprojet)){?>
                    <tr><td><strong><?php echo  TXT_DUREEPROJET .': ';?></strong><?php echo $dureeprojet.' '.$unitedureeprojet; ?></td></tr><tr><td></td></tr>
                    <?php } ?>
                    <?php if(!empty($arraySourcefinancement)){?>
                    <tr><td><strong><?php echo  TXT_FINANCEMENTPROJET .':<br /> ';?></strong><?php echo $s_sourcefinancement; ?></td></tr><tr><td></td></tr>
                    <?php } ?>
                    <?php if(!empty($partenaire)){?>
                    <tr><td><strong><?php echo  TXT_AUTREPARTENAIRE .':<br /> ';?></strong><?php echo $partenaire.'<br>'.trim($centralepartenaireprojet).', '.$s_nomlaboentreprise; ?></td></tr><tr><td></td></tr>
                    <?php } ?>
                    <?php if(!empty($libelleThematique)){?>
                    <tr><td><strong><?php echo  TXT_THEMATIQUE .': ';?></strong><?php echo $libelleThematique; ?></td></tr><tr><td></td></tr>
                    <?php } ?> 
                    <?php if(!empty($datedebuttravaux)){?>
                    <tr><td><strong><?php echo  TXT_DATEDEBUTTRAVAUX .': ';?></strong><?php echo $datedebuttravaux; ?></td></tr><tr><td></td></tr>
                    <?php } ?> 
                    <?php if(!empty($dureeestime)){?>
                    <tr><td><strong><?php echo  TXT_DUREETRAVAUXTECNO .': ';?></strong><?php echo $dureeestime.' '.$uniteperiodeestime; ?></td></tr><tr><td></td></tr>
                    <?php } ?> 
                    <?php if(!empty($personneaccueil)){?>
                    <tr><td><strong><?php echo  TXT_PERSONNEACCUEILCENTRALE .': <br />';?></strong><?php echo $personneaccueil; ?></td></tr><tr><td></td></tr>
                    <?php } ?> 
                    <?php if(!empty($arrayressource)){?>
                    <tr><td><strong><?php echo  TXT_RESSOURCES .': <br />';?></strong><?php echo $slibelleressource; ?></td></tr><tr><td></td></tr>
                    <?php }  ?>
                    <?php if(!empty($descriptiftechnologique)){?>
                    <tr><td><strong><?php echo  TXT_DESCRIPTIFTECHNOLOGIQUE .': <br />';?></strong><?php echo $descriptiftechnologique; ?></td></tr><tr><td></td></tr>
                    <?php }  ?>
                </table>
            </td>
        </tr>
    </table>
</fieldset><br /><br />           
    <table>
                     <?php if(!empty($attachementdesc)){?>
                    <tr><td><strong><?php echo  TXT_PIECEJOINTE .': ';?></strong><?php echo $attachementdesc; ?></td></tr><tr><td></td></tr>
                    <?php }  ?>
                     <?php if(!empty($verrouidentifiee)){?>
                    <tr><td><strong><?php echo  TXT_VERROUIDENTIFIEE  .': <br />';?></strong><?php echo $verrouidentifiee; ?></td></tr><tr><td></td></tr>
                    <?php }  ?>
                    <?php if(!empty($arrayautrecentrale)){?>
                    <tr><td><strong><?php echo  TXT_AUTRESCENTRALES  .': ';?></strong><?php echo $slibelleAutreCentrale; ?></td></tr><tr><td></td></tr>
                        <?php if(!empty($descriptionautrecentrale)){?>
                            <tr><td><strong><?php echo  TXT_DESCRIPTETAPE  .' <br />';?></strong><?php echo $descriptionautrecentrale; ?></td></tr><tr><td></td></tr>
                        <?php }  ?>
                    <?php }  ?>
                    <?php if(!empty($arraycentraleproximite)){?>
                    <tr><td><strong><?php echo  TXT_CENTRALEPROXIMITE  .': ';?></strong><?php echo $scentraleProximite; ?></td></tr><tr><td></td></tr>
                        <?php if(!empty($descriptioncentraleproximite)){?>
                            <tr><td><strong><?php echo  TXT_DESCRIPTCENTRALEROXIMITE  .': <br />';?></strong><?php echo $descriptioncentraleproximite; ?></td></tr><tr><td></td></tr>
                        <?php }  ?>
                    <?php }  ?>
                       <?php if(!empty($nbplaque)){?>
                    <tr><td><strong><?php echo  TXT_NBPLAQUE  .': ';?></strong><?php echo $nbplaque; ?></td></tr><tr><td></td></tr>
                    <?php }  ?>
                    <?php if(!empty($nbrun)){?>
                    <tr><td><strong><?php echo  TXT_NBRUN  .': ';?></strong><?php echo $nbrun; ?></td></tr><tr><td></td></tr>
                    <?php }  ?>
                    <?php if(!empty($reussite)){?>
                    <tr><td><strong><?php echo  TXT_REUSSITEESCOMPTE  .': <br />';?></strong><?php echo $reussite; ?></td></tr><tr><td></td></tr>
                    <?php }
                    if(!empty($emailrespdevis)){?>
                    <tr><td><strong><?php echo  TXT_ENVOIDEVIS  .': ';?></strong><?php echo $envoidevis; ?></td></tr><tr><td></td></tr>
                    <tr><td><strong><?php echo  TXT_EMAILRESPDEVIS     .': <br />';?></strong><?php echo $emailrespdevis; ?></td></tr><tr><td></td></tr>
                    <?php }
                    ?>        
    </table>    
</page>
<?php 
$content = str_replace('à', '&agrave;', ob_get_clean());
//$content = ob_get_clean();
include_once 'html2pdf/html2pdf.class.php';

try {
    $pdf=new HTML2PDF('P','A4',$lang);    
    $pdf->pdf->SetDisplayMode('fullwidth');
    $pdf->writeHTML($content);
    $pdf->Output($numprojet.'.pdf');
} catch (HTML2PDF_exception $exc) {
    die($exc);
}

BD::deconnecter();