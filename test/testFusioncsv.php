<?php
include '../class/Manager.php';
include_once '../outils/toolBox.php';
include_once '../outils/constantes.php';

$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER

joinFiles(array('../tmp/tmpLTM.csv','../tmp/tmpLPN.csv','../tmp/tmpIEMN.csv','../tmp/tmpIEF.csv','../tmp/tmpFEMTO.csv','../tmp/tmpLTM.csv'), '../tmp/csvJoin.csv');
