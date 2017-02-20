<?php

session_start();
include 'decide-lang.php';
include 'class/Manager.php';
include_once 'outils/toolBox.php';
include_once 'outils/constantes.php';
if (isset($_SESSION['pseudo'])) {
    check_authent($_SESSION['pseudo']);
} else {
    header('Location: /' . REPERTOIRE . '/Login_Error/' . $lang);
}
$originalDate = date('d-m-Y');
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
$idcentrale = $manager->getSingle2("SELECT idcentrale_centrale FROM loginpassword,utilisateur WHERE idlogin = idlogin_loginpassword and pseudo=?", $_SESSION['pseudo']);              
$data = utf8_decode("" . TXT_NOM . ";" . TXT_PRENOM . ";" . TXT_MAIL . ";" . TXT_CONNAISSANCETECHNOLOGIQUE . ";" . TXT_TELEPHONE . ";" . TXT_TYPEUTILISATEUR . ";"
        . TXT_QUALITE . ";" . TXT_AUTRESQUALITE . ";".TXT_NUMPROJECT.";".TXT_REFINTERNE.";");
$data .= "\n";
if (IDTYPEUSER == ADMINLOCAL) {
    $row = $manager->getListbyArray("SELECT distinct on(pac.mailaccueilcentrale) pac.nomaccueilcentrale,pac.prenomaccueilcentrale,pac.mailaccueilcentrale,pac.connaissancetechnologiqueaccueil,pac.telaccueilcentrale,
        q.libellequalitedemandeuraca,pcq.libellepersonnequalite,aq.libelleautresqualite,p.numero,p.refinterneprojet
  FROM projet p,personneaccueilcentrale pac,projetpersonneaccueilcentrale ppac,qualitedemandeuraca q,personnecentralequalite pcq,autresqualite aq,concerne c
WHERE ppac.idprojet_projet = p.idprojet AND ppac.idpersonneaccueilcentrale_personneaccueilcentrale = pac.idpersonneaccueilcentrale AND q.idqualitedemandeuraca = pac.idqualitedemandeuraca_qualitedemandeuraca AND pcq.idpersonnequalite = pac.idpersonnequalite AND
  aq.idautresqualite = pac.idautresqualite AND  c.idprojet_projet = p.idprojet AND c.idcentrale_centrale = ?", array($idcentrale));
    $nbrow = count($row);
    for ($i = 0; $i < $nbrow; $i++) {
        $connaissancetechnologiqueaccueil = strip_tags(preg_replace("#\n|\t|\r#"," ",$row[$i]['connaissancetechnologiqueaccueil']));
        if($row[$i]['libellepersonnequalite']=='n/a' || $row[$i]['libellepersonnequalite']=='n/d' ){
            $libellepersonnequalite=  '';
        }else{
            $libellepersonnequalite=  removeDoubleQuote($row[$i]['libellepersonnequalite']);
        }
          if($row[$i]['libelleautresqualite']=='n/a' || $row[$i]['libelleautresqualite']=='n/d'|| $row[$i]['libelleautresqualite']=='n\_a' ){
            $libelleautresqualite=  '';
        }else{
            $libelleautresqualite= utf8_decode (removeDoubleQuote($row[$i]['libelleautresqualite']));
        }
        
        
        $data .= "".
                utf8_decode (removeDoubleQuote($row[$i]['nomaccueilcentrale'])) . ";" .
                utf8_decode (removeDoubleQuote($row[$i]['prenomaccueilcentrale'])) . ";" .
                removeDoubleQuote($row[$i]['mailaccueilcentrale']) . ";" .
                utf8_decode(removeDoubleQuote(stripslashes($connaissancetechnologiqueaccueil))).";".
                removeDoubleQuote($row[$i]['telaccueilcentrale']) . ";" .                
                removeDoubleQuote($row[$i]['libellequalitedemandeuraca']). ";" .
                $libellepersonnequalite . ";" .
                $libelleautresqualite .";".
                $row[$i]['numero'] .";".
                $row[$i]['refinterneprojet'] . "\n";
    }
    header("Content-type: application/vnd.ms-excel;charset=UTF-8");
    header("Content-disposition: attachment; filename=export_utilisateur_accueil_centrale_" . $originalDate . ".csv");
    print $data;
    exit;
}else{
    $row = $manager->getList("SELECT distinct on(lower(pac.mailaccueilcentrale)) pac.nomaccueilcentrale,pac.prenomaccueilcentrale,pac.mailaccueilcentrale,pac.connaissancetechnologiqueaccueil,pac.telaccueilcentrale,
        q.libellequalitedemandeuraca,pcq.libellepersonnequalite,aq.libelleautresqualite,p.numero,p.refinterneprojet
  FROM projet p,personneaccueilcentrale pac,projetpersonneaccueilcentrale ppac,qualitedemandeuraca q,personnecentralequalite pcq,autresqualite aq
WHERE ppac.idprojet_projet = p.idprojet AND ppac.idpersonneaccueilcentrale_personneaccueilcentrale = pac.idpersonneaccueilcentrale AND q.idqualitedemandeuraca = pac.idqualitedemandeuraca_qualitedemandeuraca AND pcq.idpersonnequalite = pac.idpersonnequalite AND
  aq.idautresqualite = pac.idautresqualite");
    $nbrow = count($row);
    for ($i = 0; $i < $nbrow; $i++) {
        $connaissancetechnologiqueaccueil = strip_tags(preg_replace("#\n|\t|\r#"," ",$row[$i]['connaissancetechnologiqueaccueil']));
        if($row[$i]['libellepersonnequalite']=='n/a' || $row[$i]['libellepersonnequalite']=='n/d' ){
            $libellepersonnequalite=  '';
        }else{
            $libellepersonnequalite=  removeDoubleQuote($row[$i]['libellepersonnequalite']);
        }
          if($row[$i]['libelleautresqualite']=='n/a' || $row[$i]['libelleautresqualite']=='n/d'|| $row[$i]['libelleautresqualite']=='n\_a' ){
            $libelleautresqualite=  '';
        }else{
            $libelleautresqualite= utf8_decode (removeDoubleQuote($row[$i]['libelleautresqualite']));
        }
        
        
        $data .= "".
                utf8_decode (removeDoubleQuote($row[$i]['nomaccueilcentrale'])) . ";" .
                utf8_decode (removeDoubleQuote($row[$i]['prenomaccueilcentrale'])) . ";" .
                removeDoubleQuote($row[$i]['mailaccueilcentrale']) . ";" .
                utf8_decode($connaissancetechnologiqueaccueil). ";" .
                removeDoubleQuote($row[$i]['telaccueilcentrale']) . ";" .                
                removeDoubleQuote($row[$i]['libellequalitedemandeuraca']). ";" .
                $libellepersonnequalite . ";" .
                $libelleautresqualite . ";".
                $row[$i]['numero'] .";".
                $row[$i]['refinterneprojet'] . "\n";
    }
    header("Content-type: application/vnd.ms-excel;charset=UTF-8");
    header("Content-disposition: attachment; filename=export_utilisateur_accueil_centrale_" . $originalDate . ".csv");
    print $data;
    exit;
}
BD::deconnecter();
