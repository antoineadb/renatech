<?php

session_start();
include_once '../outils/constantes.php';
include '../decide-lang.php';
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}

$jsonASuppr = '[';
foreach ($_POST as $value) {
    $jsonASuppr .= $value;
}
$nbEmail = 40;
$jsonASuppr .= ']';
$jsonSuppr = str_replace("}{", "},{", $jsonASuppr);
$jsonSupprParsed = json_decode($jsonSuppr, true);
include '../class/email.php';
// -----------------------------------------------------------------------------------
//  RECUPERATION DU FICHIER JSON DE DE LA LISTE DES PROJETS EN COURS DE REALISATION
//  QUE JE TRANSFORME EN TABLEAU
// -----------------------------------------------------------------------------------
if (is_file('../tmp/projetarelancer.json')) {
    $json0 = file_get_contents("../tmp/projetarelancer.json");
    $json1 = str_replace('},]}', '}]}', $json0);
    $json2 = str_replace('{"items": ', '', $json1);
    $json = str_replace(']}', ']', $json2);
    $parsed_json = json_decode($json, true);
    $nblignes = count($parsed_json);
// -----------------------------------------------------------------------------------
//          CONSTRUCTION D'UN FICHIER JSON DES PROJETS A NE PAS RELANCER PAR EMAIL
// -----------------------------------------------------------------------------------   
    $fpProjetASupprimer = fopen('../tmp/projetASupprimer.json', 'w');
    $dataProjetAsupprimer = "";
    fwrite($fpProjetASupprimer, '[');
    for ($i = 0; $i < count($jsonSupprParsed); $i++) {
        $post = array_values($jsonSupprParsed[0]);
        $dataProjetAsupprimer = '{"datedebutprojet"' . ':"' . $jsonSupprParsed[$i]['datedebutprojet'] .
                '","datemaj":"' . $jsonSupprParsed[$i]['datemaj'] .
                '","numero":"' . $jsonSupprParsed[$i]['numero'] .
                '","idprojet":"' . $jsonSupprParsed[$i]['idprojet'] .
                '","titre":"' . $jsonSupprParsed[$i]['titre'] .
                '","mail":"' . $jsonSupprParsed[$i]['mail'] .
                '","nom":"' . $jsonSupprParsed[$i]['nom'] .
                '","idutilisateur":"' . $jsonSupprParsed[$i]['idutilisateur'] .
                '","dateheureenvoiemail":"' . $jsonSupprParsed[$i]['dateheureenvoiemail'] .
                '","datefin":"' . $jsonSupprParsed[$i]['datefin'] .
                '","refinterneprojet":"' . $jsonSupprParsed[$i]['refinterneprojet'] . '"';
        fputs($fpProjetASupprimer, $dataProjetAsupprimer);
        fwrite($fpProjetASupprimer, '},');
    }
    fwrite($fpProjetASupprimer, ']');
    $json_fileASupprimer0 = "../tmp/projetASupprimer.json";
    $json_projetASupprimer1 = file_get_contents($json_fileASupprimer0);
    $json_projetASupprimer = str_replace('},]', '}]', $json_projetASupprimer1);
    file_put_contents($json_fileASupprimer0, $json_projetASupprimer);
    fclose($fpProjetASupprimer);
    chmod("../tmp/projetASupprimer.json", 0777);
// -----------------------------------------------------------------------------------
//                                    TRANSFORMATION  DU FICHIER JSON EN TABLEAU
// -----------------------------------------------------------------------------------
    if (is_file('../tmp/projetASupprimer.json')) {
        $valeurASupprimer = file_get_contents("../tmp/projetASupprimer.json");
        $arrayValeurASupprimer = json_decode($valeurASupprimer, true);
    }
// -----------------------------------------------------------------------------------
//                                  CREATION D'UN TABLEAU D'ECART DES 2 TABLEAUX
// -----------------------------------------------------------------------------------    
    $emailAEnvoyer = arrayDiff2dim($arrayValeurASupprimer, $parsed_json);
// -----------------------------------------------------------------------------------
//                                             PREPARATION DE L'ENVOI DE L'EMAIL
// -----------------------------------------------------------------------------------
    $body = htmlentities(affiche('TXT_MRSMR'), ENT_QUOTES, 'UTF-8') . "<br><br>" .
            htmlentities(removeDoubleQuote(affiche('TXT_RAPPELMAIL0'), ENT_QUOTES, 'UTF-8')) . "<br><br>" .
            htmlentities(removeDoubleQuote(affiche('TXT_RAPPELMAIL1'), ENT_QUOTES, 'UTF-8')) . "<br>" .
            htmlentities(removeDoubleQuote(affiche('TXT_RAPPELMAIL2'), ENT_QUOTES, 'UTF-8')) . "<br><br>" .
            htmlentities(removeDoubleQuote(affiche('TXT_REMERCIEMENT'), ENT_QUOTES, 'UTF-8')) . "<br><br>" .
            htmlentities(affiche('TXT_SINCERESALUTATION'), ENT_QUOTES, 'UTF-8') . "<br><br>" .
            htmlentities(affiche('TXT_RESEAURENATECH'), ENT_QUOTES, 'UTF-8') . "<br><br>" .
            htmlentities(TXT_RETOUR, ENT_QUOTES, 'UTF-8') . "<br><br>" .
            htmlentities(affiche('TXT_DONOTREPLY'), ENT_QUOTES, 'UTF-8');


// construction d'un tableau de numero de projet qui sont coché, on ne dois pas envoyer d'email à ces projets
    $arrayNumeroCoche = array();
    for ($i = 0; $i < $_SESSION['nbprojet']; $i++) {
        if (isset($_POST[$i])) {
            array_push($arrayNumeroCoche, $_POST[$i]);
        }
    }
    $nbEmailEnvoyer = count($emailAEnvoyer);
    $condition = boucleTempo($nbEmailEnvoyer, $nbEmail);
    if ($condition[0] >= 1) {//pour le cas ou on tout cocher sauf < 5 valeurs, dans les autre cas $condition[0]=5
        if (!isset($_GET['cpt'])) {//cas ou on a pas encore envoyer une salves d'email
            for ($i = 0; $i < $condition[0]; $i++) {
                if (isset($emailAEnvoyer[$i]['numero'])) {//on vérifie que le numéro existe
                    $sujet = utf8_decode(TXT_NUMPROJET . ' ' . $emailAEnvoyer[$i]['numero']); //on définis l'email
                    $envoiEmailUtilisateur = new ProjetDateEnvoiEmail(date("Y-m-d H:i:s"), $emailAEnvoyer[$i]['idprojet']);
                    $manager->updateDateenvoiemail($envoiEmailUtilisateur, $emailAEnvoyer[$i]['idprojet']); //on met à jour le champs date envoi de l'email de la table projet
                    sendEmail($body, $sujet, $emailAEnvoyer[$i]['mail']);//on envoi l'email
                }
            }
            header('Location: /' . REPERTOIRE . '/relance/' . $lang . '/' . $condition[0]);
        } else {//cas ou on a déja envoyer une ou plusieurs salves d'email
            /*  il faut récupérer l'index correspondsant à la valeur $_GET['cpt'] dans la table $condition array([0]=>20,[1]=>40,[2]=>60,[3]=>80...) et l'incrémenter de +1  */
            $key = array_search($_GET['cpt'], $condition) + 1;
            if (!isset($condition[$key])) {
                $key = array_search($_GET['cpt'], $condition);
            }
            for ($i = $_GET['cpt']; $i < $condition[$key]; $i++) {
                if (isset($emailAEnvoyer[$i]['numero'])) {
                    $sujet = utf8_decode(TXT_NUMPROJET . ' ' . $emailAEnvoyer[$i]['numero']);
                    $envoiEmailUtilisateur = new ProjetDateEnvoiEmail(date("Y-m-d H:i:s"), $emailAEnvoyer[$i]['idprojet']);
                    $manager->updateDateenvoiemail($envoiEmailUtilisateur, $emailAEnvoyer[$i]['idprojet']);
                    sendEmail($body, $sujet, $emailAEnvoyer[$i]['mail']);
                }
            }
            if ($condition[$key] > $_SESSION['nbprojet']) {
                header('Location: /' . REPERTOIRE . '/relance/' . $lang . '/' . $_SESSION['nbprojet']);
            } else {
                header('Location: /' . REPERTOIRE . '/relance/' . $lang . '/' . $condition[$key]);
            }
        }
    } else {
        header('Location: /' . REPERTOIRE . '/relance/' . $lang . '/' . 'noselection');
    }
} else {
    header('Location: /' . REPERTOIRE . '/relance/' . $lang . '/err');
}
/**
  Madame, Monsieur,
  Nous vous rappelons que vous avez un projet dans l'application RENATECH en référence ci-dessus.
  Pour le bon fonctionnement de l'application nous vous demandons de mettre à jour régulièrement votre projet.
  Pour tout besoin d'information sur le fonctionnement de l'application, dans le menu « Divers/Manuel utilisateur » vous pouvez télécharger une notice explicative du fonctionnement de l'application.
  En vous remerciant pour votre confiance au réseau RENATECH. En cas de nouveau projet, merci de bien vouloir remplir une nouvelle demande
  Sincères salutations,
  Le réseau Renatech.
  Retour sur la plateforme Renatech
  Merci de ne pas répondre à cette adresse.
 */
