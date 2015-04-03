
<?php

if (!empty($_FILES)) {
    $nomFichier = $_FILES['file']['name'];
    $tempRep = $_FILES['file']['tmp_name'];
  
    $error = $_FILES['file']['error'];
    if ($error != 0 || !$tempRep) {
        echo 'Erreur: Le fichier n\'a pas pu être uploadé';
        die();
    }
    if (move_uploaded_file($tempRep, '../uploadlogo' . $nomFichier)) {
        echo 'Chargement du fichier ' . $nomFichier . ' terminé !';
    } else {
        echo 'Une erreur est survenue lors de l\'envoi du fichier';
    }
}