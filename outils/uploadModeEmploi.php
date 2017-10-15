<?php
include_once '../outils/constantes.php';
define('MAXSIZE', 2048000);
define('REPERTOIREMANUEL', '../downloadManual/');
$extensions = array('.PDF', '.pdf');
if (!empty($_FILES)) {    
    $extensionlogocentrale = strrchr(nomFichierValidesansAccent($_FILES['file']['name']), '.');
    if (filesize($_FILES['file']['tmp_name']) > MAXSIZE) {
        echo '<div id="errSizeFigure" style="  color: red;font-weight: bold;width: 360px;margin-left:20px">Error the file size is above 4Mo</b></div>';
        exit();
    } elseif (!in_array($extensionlogocentrale, $extensions)) {
        echo '<div id="errExtensionFigure" style="  color: red;font-weight: bold;width: 360px;margin-left:20px">Error!The file must be pdf</b></div>';
        exit();
    }
    $folder = REPERTOIREMANUEL . nomFichierValidesansAccent($_FILES['file']['name']);
    $tmName = $_FILES['file']['tmp_name'];
    $fileModeEmploi = basename($tmName);
    $fichierModeEmploi = basename(nomFichierValidesansAccent($_FILES['file']['name']));
    if (move_uploaded_file($_FILES['file']['tmp_name'], REPERTOIREMANUEL . $fichierModeEmploi)) {
        chmod(REPERTOIREMANUEL . $fichierModeEmploi, 0777);
        unlink(REPERTOIREMANUEL . "ModeEmploiDemandeProjetRENATECH.pdf");
        rename (REPERTOIREMANUEL. $fichierModeEmploi ,REPERTOIREMANUEL."ModeEmploiDemandeProjetRENATECH.pdf");
    }
}