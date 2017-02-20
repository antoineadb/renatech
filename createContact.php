<?php
session_start();
include_once 'outils/constantes.php';
include_once 'outils/toolBox.php';
include 'decide-lang.php';
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
include 'html/header.html';
?>
<div id="global">
    <?php include 'html/entete.html'; ?>
    <div style="display: block;" id="erreurcontact">
        <?php
        if (!empty($_GET['msgerreuradmlocal'])) {
            echo '  <label style="color: #FF0000;display: block;font-size: 1.2em;font-weight: bold; margin-bottom: -34px;margin-top: 20px; text-align: center;width: 950px;">' . TXT_MESSAGEERREURCONTACTINSERT . '</label>';
        }
        ?>
    </div>
    <?php
    include 'html/createContact.html';
    include 'html/footer.html';
    ?>
</div>
</body>
</html>