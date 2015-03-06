<?php include 'html/header.html'; ?>
<div id="global">
    <?php include 'html/entete.html'; ?>
    <div id="contenu"><?php include 'html/createLoginDoublon.html'; ?></div>
    <div><a href="/<?php echo REPERTOIRE . '/' . 'index.php?'; ?>lang=<?php echo $lang; ?>" style="text-decoration: none;color: red"><?php echo ' ' . TXT_RETOURACCUEIL; ?></a></div>
        <?php include 'html/footer.html'; ?>
</div>
</body>
</html>