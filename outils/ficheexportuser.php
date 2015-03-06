<?php

session_start();
include '../decide-lang.php';
include '../class/Manager.php';
include_once '../outils/toolBox.php';
include_once '../outils/constantes.php';

if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location:/' . REPERTOIRE . '/Login_Error/' . $lang);
}

