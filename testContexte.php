<?php
include 'decide-lang.php';
include('pdf/fpdf.php');
include_once 'pdf/WriteHTML.php';
include_once 'outils/constantes.php';
$pdf = new PDF_HTML();
$pdf->AddPage();
$pdf->SetTitle(utf8_decode(TXT_TITREPDF));
include_once 'class/Manager.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
include_once 'outils/toolBox.php';

$contexte = $manager->getSingle2("select contexte from projet where idprojet=?", 1178);
$arrayCar = array('à','û','–','è');
$arrayCarcorrige = array('à','û','-','è');
$tabCar = array("\r");
$contexte0 = stripTaggsbr(str_replace("é", "é",$contexte));
$contexte1 = str_replace("ç","ç",$contexte0);
$contexte2 = str_replace($tabCar, array(), $contexte1);
$contexte3 = ltrim(rtrim(str_replace(array(chr(13)),  '', $contexte2)));
$contexte4 = str_replace($arrayCar, $arrayCarcorrige, $contexte3);
$contexte5 = str_replace('€', utf8_encode(chr(128)), $contexte4);
$contexte6 =  utf8_decode(stripslashes(str_replace("''", "'",$contexte5)));
$pdf->SetFontSize(12);
$pdf->SetFont('Times');
$pdf->WriteHTML($contexte6);
$pdf->ln();
$pdf->Output(1, 'I');

