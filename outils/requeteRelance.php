<?php

$sql="SELECT distinct on (p.numero) p.idprojet,p.datemaj,p.dureeprojet,p.refinterneprojet,p.numero,p.titre,p.datedebutprojet,u.nom,u.prenom,p.idperiodicite_periodicite,l.mail,
    u.idutilisateur,p.dateenvoiemail , p.interneexterne
    FROM  projet p,utilisateur u,creer cr,centrale ce,concerne co,typeprojet t,statutprojet s, loginpassword l   ";
$sql1="AND cr.idutilisateur_utilisateur = u.idutilisateur AND co.idcentrale_centrale = ce.idcentrale AND co.idprojet_projet = p.idprojet  AND  t.idtypeprojet = p.idtypeprojet_typeprojet 
    AND l.idlogin = u.idlogin_loginpassword AND s.idstatutprojet = co.idstatutprojet_statutprojet and ce.idcentrale = ? AND (s.idstatutprojet=? OR s.idstatutprojet=?) AND p.datemaj <?
    AND trashed =FALSE AND p.devtechnologique=TRUE ";
$sqlExterneInterne = $sql. " WHERE cr.idprojet_projet = p.idprojet AND cr.idprojet_projet = p.idprojet  ".$sql1;

$sqlInterne="
    SELECT distinct on (p.numero) p.idprojet,p.datemaj,p.dureeprojet,p.refinterneprojet,p.numero,p.titre,p.datedebutprojet,u.nom,u.prenom,p.idperiodicite_periodicite,l.mail,
    u.idutilisateur,p.dateenvoiemail , p.interneexterne
    FROM projet p
    LEFT JOIN creer cr ON cr.idprojet_projet = p.idprojet
    LEFT JOIN utilisateur u ON cr.idutilisateur_utilisateur = u.idutilisateur
    LEFT JOIN concerne co  ON co.idprojet_projet = p.idprojet
    LEFT JOIN centrale ce  ON ce.idcentrale = co.idcentrale_centrale
    
    LEFT JOIN typeprojet t ON t.idtypeprojet = p.idtypeprojet_typeprojet 
    LEFT JOIN statutprojet s ON s.idstatutprojet = co.idstatutprojet_statutprojet 
    LEFT JOIN loginpassword l ON l.idlogin = u.idlogin_loginpassword 
    WHERE u.idcentrale_centrale is not null 
    AND ce.idcentrale = ?
    AND (s.idstatutprojet=? OR s.idstatutprojet=?) 
    AND p.datemaj < ?
    AND trashed =FALSE AND p.devtechnologique=TRUE";

$sqlExterne="
    SELECT distinct on (p.numero) p.idprojet,p.datemaj,p.dureeprojet,p.refinterneprojet,p.numero,p.titre,p.datedebutprojet,u.nom,u.prenom,p.idperiodicite_periodicite,l.mail, u.idutilisateur,p.dateenvoiemail , 
    p.interneexterne FROM projet p
    LEFT JOIN creer cr ON cr.idprojet_projet = p.idprojet
    LEFT JOIN utilisateur u ON cr.idutilisateur_utilisateur = u.idutilisateur
    LEFT JOIN concerne co  ON co.idprojet_projet = p.idprojet
    LEFT JOIN centrale ce  ON ce.idcentrale = co.idcentrale_centrale
    LEFT JOIN typeprojet t ON t.idtypeprojet = p.idtypeprojet_typeprojet 
    LEFT JOIN statutprojet s ON s.idstatutprojet = co.idstatutprojet_statutprojet 
    LEFT JOIN loginpassword l ON l.idlogin = u.idlogin_loginpassword 
    WHERE u.idcentrale_centrale IS NULL   
    and ce.idcentrale = ?
    AND (s.idstatutprojet=? OR s.idstatutprojet=?) 
    AND p.datemaj <?
    AND trashed =FALSE 
    AND p.devtechnologique=TRUE";
define('SQLINTERNE',$sqlInterne);
define('SQLEXTERNE',$sqlExterne);
define('SQLINTERNEEXTERNE',$sqlExterneInterne);