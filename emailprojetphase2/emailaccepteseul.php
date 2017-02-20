<?php

if (isset($_POST['page_precedente']) && $_POST['page_precedente'] == 'modifProjetRespCentrale.html') {//Viens de la demande de faisabilité    
    /*
      Madame, Monsieur,
      Suite à l'analyse de votre demande de soutien auprès du réseau Renatech, nous vous informons que votre projet a été accepté pour expertise.
      Afin de mener à bien cette phase d'expertise, nous vous demandons de bien vouloir compléter les informations que vous nous avez transmises en remplissant la partie 2 de la demande sur le site Renatech
      Si vous souhaitez plus d'informations, n'hésitez pas à contacter la centrale sélectionnée.
      Nous vous rappelons d'autre part que si votre projet se concrétise avec l'une des centrales de notre réseau, vous devrez insérer dans vos présentations le logo RENATECH et ajouter dans la partie « acknowledgement » des publications la phrase suivante : « This work was partly supported by the French RENATECH network ».
      Sincères salutations,
      Le réseau Renatech.
      Adresse email de la centrale:XXX
      antoineadb@gmail.com
      Retour sur la plateforme Renatech
      Merci de ne pas répondre à cette adresse.
     */
    $body = htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_MRSMR'))), ENT_QUOTES, 'UTF-8') . '<br><br>' . htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_BODYEMAILPHASE20'))), ENT_QUOTES, 'UTF-8') .
            '<br>' . htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_BODYEMAILPHASE21'))), ENT_QUOTES, 'UTF-8') . "  <a href='https://www.renatech.org/projet'>" .
            str_replace("''", "'", stripslashes(affiche('TXT_BODYEMAILPHASE22'))) . "</a>" . '<br><br>' . htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_BODYEMAILPHASE23'))), ENT_QUOTES, 'UTF-8') . '<br><br>' .
            htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_RAPPELINSERTLOGO'))), ENT_QUOTES, 'UTF-8') . '<br><br>' . htmlentities(affiche('TXT_SINCERESALUTATION'), ENT_QUOTES, 'UTF-8') . '<br><br>' . htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_RESEAURENATECH'))), ENT_QUOTES, 'UTF-8') . '<br><br>' .
            htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_RESPONSABLEBODYTEMAIL10'))), ENT_QUOTES, 'UTF-8') . ' ' . htmlentities($centrale, ENT_QUOTES, 'UTF-8') . '<br>' . $emailCentrale . '<br><br>' .
            '<a href="https://www.renatech.org/projet">' . htmlentities(TXT_RETOUR, ENT_QUOTES, 'UTF-8') . '<a>' . '<br><br>      ' .
            htmlentities(str_replace("''", "'", stripslashes(affiche('TXT_DONOTREPLY'))), ENT_QUOTES, 'UTF-8') . '<br><br>';
    
    $sMailCc = '';
    for ($i = 0; $i < count($mailCC); $i++) {
        $sMailCc.=$mailCC[$i] . ',';
    }
    $sMailCC = substr($sMailCc, 0, -1);
    $centrale = $manager->getSingle2("SELECT libellecentrale FROM concerne,centrale WHERE idcentrale = idcentrale_centrale and idprojet_projet=?", $idprojet);
    $nomPrenomDemandeur = $manager->getList2("SELECT nom, prenom FROM creer,utilisateur WHERE idutilisateur_utilisateur = idutilisateur and idprojet_projet = ?", $idprojet);
    $idcentrale = $manager->getSingle2("select idcentrale_centrale from concerne where idprojet_projet=?", $idprojet);
    createLogInfo(NOW, 'Projet soumis dans la centrale ' . $centrale . ' : E-mail demandeur: ' . $maildemandeur[0] . ' : ' . ' E-mail copie : ' . $sMailCC . ' : n°: ' . $numprojet, 'Demandeur: ' . $nomPrenomDemandeur[0]['nom'] .
            ' ' . $nomPrenomDemandeur[0]['prenom'], TXT_ACCEPTE, $manager,$idcentrale);
    envoieEmail($body, $sujet, $maildemandeur, $mailCC); //envoie de l'email au responsable centrale et au copiste
}