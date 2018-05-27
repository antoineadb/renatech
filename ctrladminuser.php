<?php
if (isset($_POST['rechercheProjet'])) {
    include 'administrateurprojet.php';
} elseif (isset($_POST['recchercheUseradmin']) && !empty($_POST['copieProjet'])) {
        $numero=$_POST['copieProjet'];
    include 'affectationprojetadmin.php';
} else {
    include 'administrateurprojet.php';
}