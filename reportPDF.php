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
if (isset($_GET['idprojet'])) {
    $idprojet = $_GET['idprojet'];
}
$resreport = $manager->getList2("select * from rapport where idprojet=?",$idprojet);

$titleReport = str_replace("’", "''", $resreport[0]['title']);
$title = filterEditor(str_replace("''", "'", ($titleReport)));
$author= filterEditor(str_replace("''", "'", ($resreport[0]['author'])));
$entity= substr(filterEditor(str_replace("''", "'", ($resreport[0]['entity']))),0,80);
$villepays= substr(filterEditor(str_replace("''", "'", ($resreport[0]['villepays']))),0,60);
$instituteinterest=substr(filterEditor(str_replace("''", "'", ($resreport[0]['instituteinterest']))),0,50);
$fundingsource=substr(filterEditor(($resreport[0]['fundingsource'])),0,80);
$collaborator=substr(filterEditor(str_replace("''", "'", ($resreport[0]['collaborator']))),0,70);
$thematics=filterEditor(str_replace("''", "'", ($resreport[0]['thematics'])));
$startingdate=$resreport[0]['startingdate'];
$technologicalwc = substr(filterEditor(str_replace("''", "'", ($resreport[0]['technologicalwc']))),0,110);
if (!empty($resreport[0]['objectif'])) {
    $arrayCar = array('à','û','–','è');
    $arrayCarcorrige = array('à','û','-','è');
    $tabCar = array("\r");
    $objectif0 = filterEditor(stripTaggsbr(str_replace("é", "é",$resreport[0]['objectif'])));
    $objectif1 = str_replace("ç","ç",$objectif0);
    $objectif2 = str_replace($tabCar, array(), $objectif1);
    $objectif3 = ltrim(rtrim(str_replace(array(chr(13)),  '', $objectif2)));
    $objectif4 = str_replace($arrayCar, $arrayCarcorrige, $objectif3);
    $objectif5 = str_replace('€', utf8_encode(chr(128)), $objectif4);
    $objectif6 =  (stripslashes(str_replace("''", "'",$objectif5)));
    $objectif =  substr($objectif6,0,1140);
}else{
    $objectif ='';
}

if (!empty($resreport[0]['results'])) {
    $arrayCar = array('à','û','–','è');
    $arrayCarcorrige = array('à','û','-','è');
    $tabCar = array("\r");
    $results0 = filterEditor(stripTaggsbr(str_replace("é", "é",$resreport[0]['results'])));
    $results1 = str_replace("ç","ç",$results0);
    $results2 = str_replace($tabCar, array(), $results1);
    $results3 = ltrim(rtrim(str_replace(array(chr(13)),  '', $results2)));
    $results4 = str_replace($arrayCar, $arrayCarcorrige, $results3);
    $results5 = str_replace('€', utf8_encode(chr(128)), $results4);
    $results6 = stripslashes(str_replace("''", "'",$results5));
    $results =  substr($results6,0,1010);
}else{
    $results =  '';
}

if (!empty($resreport[0]['valorization'])) {
    $arrayCar = array('à','û','–','è');
    $arrayCarcorrige = array('à','û','-','è');
    $tabCar = array("\r");
    $valorization0 = filterEditor(stripTaggsbr(str_replace("é", "é",$resreport[0]['valorization'])));
    $valorization1 = str_replace("ç","ç",$valorization0);
    $valorization2 = str_replace($tabCar, array(), $valorization1);
    $valorization3 = ltrim(rtrim(str_replace(array(chr(13)),  '', $valorization2)));
    $valorization4 = str_replace($arrayCar, $arrayCarcorrige, $valorization3);
    $valorization5 = str_replace('€', utf8_encode(chr(128)), $valorization4);
    $valorization6 =  (stripslashes(str_replace("''", "'",$valorization5))); 
    $valorization =  substr($valorization6,0,330); 
}else{
    $valorization =  ''; 
}
if (!empty($resreport[0]['logocentrale'])) {
    $logocentrale = './uploadlogo/'.$resreport[0]['logocentrale'];
}else{
    $logocentrale='./'.$manager->getSingle2("select adresselogcentrale from sitewebapplication where refsiteweb = (SELECT libellecentrale from centrale,concerne where idcentrale_centrale=idcentrale and idprojet_projet=?)", $idprojet);
}
if (!empty($resreport[0]['logo'])) {
    $logo = './uploadlogo/'.$resreport[0]['logo'];
}else{
    $logo='./'.$manager->getSingle2("select adresselogcentrale from sitewebapplication where refsiteweb = (SELECT libellecentrale from centrale,concerne where idcentrale_centrale=idcentrale and idprojet_projet=?)", $idprojet);
}
if (!empty($resreport[0]['figure'])) {
    $figure = './uploadlogo/'.$resreport[0]['figure'];
}else{
    $figure='';
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

<page backtop="1mm" backleft="5mm" backright="2mm" backbottom="1mm" footer="date;heure">
    <table>
        <tr>
        <td style="width: 75%; "><img style="width: 100%;"  src="./styles/img/logo-renatech.jpg" ></td>
        <td style="width: 25%; ">            
            <img style="width: 65%;max-height: 400px;max-width: 350px" align="right"  src="<?php echo $logocentrale; ?>"  >            
        </td>
    </tr>
    </table>
    <br><br>
    <table  align="center" style="margin-top: -10px">
        <tr>
            <td><p><?php echo str_replace("''","'",$title); ?></p></td>        
        </tr>
    </table>    
    <hr>
<table>
    <tr>
        <td style='width:75%;'>
            <table>
                <tr><td><strong>Authors: </strong><?php echo $author; ?></td></tr>
                <tr><td><strong>Entity: </strong><?php echo $entity; ?></td></tr>
                <tr><td><strong>Town, Country: </strong><?php echo $villepays; ?></td></tr>
                <tr><td><strong>CNRS institute of interest: </strong><?php echo $instituteinterest; ?></td></tr>
                <tr><td><strong>Funding source: </strong><?php echo $fundingsource; ?></td></tr>
                <tr><td><strong >Collaborators in the project or consortium :</strong><?php echo $collaborator; ?></td></tr>
            </table>
        </td>    
        <td style='width:25%;padding-left:-80px ' >
            <?php             
                //CALCUL DES DIMENSIONS DU LOGO                
                $arrayInfoImg =getimagesize($logo);
                if($arrayInfoImg[0]>150){
                    $fWidth = 150/$arrayInfoImg[0];
                    $fHeight = 75/$arrayInfoImg[1];
                    $width = $fWidth*$arrayInfoImg[0];
                    $height = $fHeight*$arrayInfoImg[1];
                }else{
                    $width = 0.6*$arrayInfoImg[0];
                    $height = 0.6*$arrayInfoImg[1];
                }
            ?>
            <table>
                <tr>
                    <td>
                        <img   height="<?php echo $height; ?>" width="<?php echo $width; ?>" src="<?php echo $logo; ?>">
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>   
    <hr>
    <table>
        <tr>
        <td style="width:70%"><strong>BTR thematics : </strong><?php echo $thematics; ?></td>
        <td style="width:30%"><strong>Starting date:  </strong><?php echo date("Y-  m-d", strtotime($startingdate)); ?></td>        
        </tr>
    </table>
    <hr>
     <table>
        <tr>
        <td><strong>Project objectives : </strong><?php echo $objectif; ?></td> 
        </tr>
    </table>
    <hr>
    <table style="">
        <tr>
            <td style='width: 100%'><strong>Results : </strong><?php echo $results; ?></td></tr><tr><td>
            <?php if($figure!=''){             
                //CALCUL DES DIMENSIONS DE LA FIGURE
                $arrayInfoImg =getimagesize($figure);
                if($arrayInfoImg[1]>200){//si Height >200px
                    $facteur = 200/$arrayInfoImg[1];
                    $height = $facteur*$arrayInfoImg[1];                    
                }else{
                    $height =$arrayInfoImg[1];
                }
                
                if($arrayInfoImg[1]>250){//si Width >700px
                    $facteurW = 250/$arrayInfoImg[1];
                    $width  =$facteurW*$arrayInfoImg[0];
                    $height =$facteurW*$arrayInfoImg[1];
                }else{
                    $width = $arrayInfoImg[0];
                    $height =$arrayInfoImg[1];
                }//echo '$height = '.$height.'<br>'.'$width = '.$width;
            ?>
            <img   align="left"  src="<?php echo $figure; ?>"  height="<?php echo $height;?>" width="<?php echo $width;?>">
            <?php } ?>
        </td>
        </tr>
    </table>
    <hr>
      <table>
        <tr>
        <td><strong>Valorization : </strong><br><?php echo $valorization; ?></td> 
        </tr>
    </table>
    <hr>
    <table>
        <tr>
        <td><strong>Technological work conducted in the renatech network : </strong><?php echo $technologicalwc; ?></td> 
        </tr>
    </table>
</page>

<?php 
$content = ob_get_clean();
include_once 'html2pdf/html2pdf.class.php';
try {
    $pdf=new HTML2PDF('P','A4','en');
    $pdf->pdf->SetDisplayMode('fullpage');
    $pdf->writeHTML($content);
    $pdf->Output('report.pdf');
} catch (HTML2PDF_exception $exc) {
    die($exc);
}

BD::deconnecter();