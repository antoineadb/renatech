<?php

if (isset($_POST['rechercheProjet'])) {
    include 'porteurprojet.php';
} elseif (isset($_POST['rechercheUserporteur']) && !empty($_POST['copieProjet'])) {
        $numero=$_POST['copieProjet'];
    include 'affectationprojetporteur.php';
} else {
    include 'porteurprojet.php';
}
