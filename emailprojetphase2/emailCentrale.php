<?php
//TRAITEMENT DU LIBELLE DANS L'EMAIL
    if (!empty($_POST['centraletrs'])) {
    $idcentrale = (int) substr($_POST['centraletrs'], -1);
    $infocentrale = $manager->getList2("select libellecentrale,email1,email2,email3,email4,email5 from centrale where idcentrale=?", $idcentrale);
    $centrale = $infocentrale[0]['libellecentrale'];
    $emailCentrale = '';
    for ($i = 0; $i < count($infocentrale); $i++) {
        for ($j = 0; $j < 4; $j++) {
            if (!empty($infocentrale[$i]['email' . $j])) {
                $emailCentrale .=$infocentrale[$i]['email' . $j] . '<br>';
            }
        }
    }
} else {
        $idcentrale = $manager->getSingle2('SELECT idcentrale FROM concerne,centrale WHERE  idcentrale_centrale = idcentrale and idprojet_projet=?', $idprojet);
        $centrale= $manager->getSingle2('SELECT libellecentrale FROM concerne,centrale WHERE  idcentrale_centrale = idcentrale and idprojet_projet=?', $idprojet);
        $infocentrale = $manager->getList2("select libellecentrale,email1,email2,email3,email4,email5 from centrale where idcentrale=?", $idcentrale);
    $emailCentrale = '';
    for ($i = 0; $i < count($infocentrale); $i++) {
        for ($j = 0; $j < 4; $j++) {
            if (!empty($infocentrale[$i]['email' . $j])) {
                $emailCentrale .=$infocentrale[$i]['email' . $j] . '<br>';
            }
        }
    }
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//                                                                              TRAITEMENT DES EMAILS
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
$emailcentrales = array();

$mailcentrales = $manager->getList2("select email1,email2,email3,email4,email5 from centrale where idcentrale=?", $idcentrale);
for ($i = 0; $i <= count($mailcentrales); $i++) {
    if (!empty($mailcentrales[0][$i])) {
        array_push($emailcentrales, $mailcentrales[0][$i]); //construction d'un tableau d'email des responsable de la centrale
    }
}
$infodemandeur = array($manager->getList2('SELECT mail, mailresponsable FROM creer,loginpassword,utilisateur WHERE idutilisateur_utilisateur = idutilisateur
            AND idlogin_loginpassword = idlogin and idprojet_projet=?', $idprojet));
$maildemandeur = array($infodemandeur[0][0]['mail']); //EMAIL DU DEMANDEUR NE PEUT PAS NE PAS EXISTER
//AJOUT DE L'EMAIL DU RESPONSABLE SI IL EXISTE   
$arrayemailCC = array();
if (!empty($infodemandeur[0][0]['mailresponsable'])) {
    array_push($arrayemailCC, $infodemandeur[0][0]['mailresponsable']); //EMAIL DU RESPONSABLE SI IL EXISTE
    $CC = array_merge($arrayemailCC, $arrayemailCC);
} else {
    $CC = $arrayemailCC;
}
$emailcc = array_merge($emailcentrales, $CC);
$mailCC = array_unique($emailcc);
