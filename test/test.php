<?php
include '../class/Manager.php';
$db = BD::connecter();
$manager = new Manager($db);

$userAdmin = new UtilisateurAdministrateur(1, 1);
$manager->updateUtilisateurAdministrateur($userAdmin, 1);
