<?php
if (!empty($_POST['modifcentraleProximite']) && !empty($_POST['idlibellecentraleProximiteactuel']) && !empty($_POST['regioncorrespondante'])) {
    include '../modifBase/modifCentraleProximite.php';
}