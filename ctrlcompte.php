<?php
include_once 'outils/affichelibelle.php';
include_once 'decide-lang.php';
if (isset($_POST['rechercheUser']) && $_POST['rechercheUser'] == TXT_FIND) {
    include 'gestioncompte.php';
}
