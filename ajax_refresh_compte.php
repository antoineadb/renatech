<?php

session_start();
include_once 'outils/constantes.php';
$keyword = strtoupper($_POST['keyword'] . '%');
include_once 'class/Manager.php';
$db = BD::connecter();
$manager = new Manager($db);
if (isset($_POST['keyword']) && !empty($_POST['keyword'])) {
    $list = $manager->getListbyArray("SELECT * FROM utilisateur LEFT JOIN loginpassword  ON idlogin_loginpassword = idlogin WHERE UPPER(nom) LIKE UPPER(?)", 
            array($keyword));
    foreach ($list as $rs) {
        $nom = str_replace($_POST['keyword'], $_POST['keyword'], $rs['nom']);
        echo '<li  style="margin-left:20px" onclick="set_item(\'' . $rs['nom'] . '\');'
                . '">' . $nom.' - '.$rs['prenom'] . '</li>';
    }
}
?>