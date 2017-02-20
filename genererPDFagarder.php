<?php
    session_start();
    $filename = "tcpdf/tcpdf.php";
    if (file_exists($filename)) {
        require_once($filename);
    } else {
        print "Le fichier $filename n'existe pas\n";
    }
    // create new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetCreator('Antoine');
    $pdf->SetAuthor('MOI');
    $pdf->SetTitle('nom');
    $pdf->SetSubject('nomProjet');
    $pdf->SetKeywords('test');
    $pdf->setFontSubsetting(true);
    $pdf->SetFont('dejavusans', '', 14, '', true);
    $pdf->AddPage();
    // Set some content to print
    $mail = $_SESSION['mail'];
    $nom =  stripslashes($_SESSION['nom']);
    $prenom = stripslashes( $_SESSION['prenom']);
    $adresse =stripslashes(  $_SESSION['adresse']);
    $numProjet =$_SESSION['numProjet'];
    if(isset($_SESSION['descriptif'])){
        $descriptif =stripslashes( $_SESSION['descriptif']);
    }else{
        $descriptif='pas de donnée';
    }
    $dateProjet = $_SESSION['dateProjet'];
    date_default_timezone_set('Europe/London');
    $date = date("Y") ;
    if(isset($_SESSION['contexte'])){
        $contexte = stripslashes( $_SESSION['contexte']);
    }else{
        $contexte='pas de donnée';
    }
    //$centrale=$_SESSION['centrale'];
    $titre=$_SESSION['titreProjet'];
    $titre = stripslashes($titre);
    $html = 
'<!DOCTYPE html>
    <html>          
    <head>
    <title></title>	    	
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">    	
    </head>	
    <body>			
        <table>
            <tr>
            <th style="width: 70px"><img src="styles/img/logopdf.jpg">
            </th>
            <th style="width: 350px"></th>
            <th style="width: 100px"></th>
            </tr>
            <tr>
            <th align="left"><img src="styles/img/logo_cnrs.png">
            </th>
            <td align="center" style=" font-size: 30px;font-family: fantasy; font-weight:bold;">
            <h3>Renatech contact form '.$date.'</h3>
            </td>
            </tr>
        </table>		
        <table border= "1px"  style="padding-left: 100px">
            <tr>
            <td style="font-size: 30px; color: #005C8D;font-family: fantasy; font-weight:bold;">
                    Cette fiche est a adresser &agrave; : 
                    <br>pour informer du possible d&eacute;marrage d\'un projet exog&egrave;ne
            </td>
            <tr>			
	</table>	
        <div align="center" style="font-size: 30px; color: #005C8D; font-family: fantasy;font-weight:bold;">Acronym,Name of the project</div>			
	<table align="center"  border="1" style="width: 920px; border-spacing:0px ;border-width: 0px;">	 
            <tr>
                <th style="text-align: left;padding-left:20px ; font-size: 30px;font-family: fantasy; "> Author , Affiliation, email address :
                        '.$nom.' '.$prenom.' '.$mail.' <br>
                CNRS institute of interest :<br>
                Funding source:<br>
                Collaborators in the project or consortium :</th>
                <th style="width: 70px; height:70px;"></th>
            </tr>
	</table>
            <table border="1" style="width: 740px">
                <tr>
                    <th style="text-align: left;padding-left:20px ; font-size: 30px;font-family: fantasy;"> RENATECH thematics :</th>
                    <th style="width: 160px; height:20px;text-align: left;padding-left:20px;font-size: 30px;font-family: fantasy;">Starting date (mm-yyyy) :ou (yyyy)  '.$dateProjet.'</th>
		</tr>
	</table>
		<table border="1" style="width:530px;">
		<tr>
                <th  valign="top" style=" height:160px;text-align: left;padding-left:20px;font-size: 30px;font-family: fantasy;">Project objectives :
                '.$descriptif.'</th>
		</tr>		
	</table>
	<table border="1" style="width:530px;">
            <tr>
            <th  valign="top" style=" height:160px;text-align: left;padding-left:20px;font-size: 30px;font-family: fantasy;">Results (figures and text):</th>
            </tr>		
	</table>
	<table border="1" style="width:530px;">
            <tr>
                    <th  valign="top" style=" height:110px;text-align: left;padding-left:20px;font-size: 30px;font-family: fantasy;">Valorisation, Publications, patents,.. (5 lines max) :</th>
            </tr>		
	</table>	
            <table border="1" style="width:530px;">
            <tr>
            <th  valign="top" style=" height:90px;text-align: left;padding-left:20px;font-size: 30px;font-family: fantasy;">
                    RENATECH network contribution:<br>
                    manpower contribution <br>
                    List the main techniques used 
            </th>
            </tr>		
	</table>
        
</body>
</html>
		';
$pdf->writeHTML($html, true, false, true, false, '');
$pathOut = "tmp/";
$fileName = "projet.pdf";
ob_clean();
//$pdf->Output($pathOut . $fileName, "I");
$pdf->Output($pathOut . $fileName, "D");
$pdf->Output($pathOut . $fileName, "D");
//$pdf->Output($pathOut . $fileName, "F");
?>