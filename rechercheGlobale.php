<?php
session_start();
include 'html/header.html';
include_once 'class/Manager.php';
include_once 'decide-lang.php';
include_once './outils/constantes.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
?>
<div id="global">
    <?php include 'html/entete.html'; ?><div style="padding-top: 60px;"><?php include 'outils/bandeaucentrale.php'; ?></div>
    <div id="rech">
        <?php
        $pseudo = $_SESSION['pseudo'];
        $idtypeutilisateur = $manager->getSingle2("SELECT idtypeutilisateur_typeutilisateur FROM loginpassword,utilisateur WHERE idlogin = idlogin_loginpassword and pseudo= ?", $pseudo);
        if (isset($_POST['rechercheglobale'])) {
            $rechercheglobale = trim($_POST['rechercheglobale']);
        }
        if (!empty($rechercheglobale) && strlen($rechercheglobale) > 2) {
            $idcentrale = $manager->getSingle2("SELECT idcentrale FROM loginpassword, centrale,utilisateur WHERE idlogin = idlogin_loginpassword AND
                    idcentrale = idcentrale_centrale and pseudo=?", $pseudo);
            if ($idtypeutilisateur == ADMINLOCAL) {
                $manager->exeRequete("drop table if exists TMPRECHERCHE");
                $manager->getRequete("CREATE TABLE TMPRECHERCHE AS (
                        SELECT p.titre,p.idprojet,p.numero,u.idutilisateur,p.refinterneprojet,p.dateprojet,ce.libellecentrale,s.libellestatutprojet,u.nom||' -  '|| u.prenom as demandeur,null as porteur
                        FROM projet p,creer c,utilisateur u,concerne,loginpassword l,centrale ce,statutprojet s
                        WHERE p.idprojet = c.idprojet_projet AND c.idutilisateur_utilisateur = u.idutilisateur AND concerne.idprojet_projet = p.idprojet AND
                        concerne.idcentrale_centrale = ce.idcentrale AND l.idlogin = u.idlogin_loginpassword AND s.idstatutprojet = concerne.idstatutprojet_statutprojet
                        AND ce.idcentrale =? and lower(p.numero) like lower(?) AND trashed =FALSE
                        union
                        SELECT p.titre,p.idprojet,p.numero,u.idutilisateur,p.refinterneprojet,p.dateprojet,ce.libellecentrale,s.libellestatutprojet,null as demandeur,u.nom||' -  '|| u.prenom as porteur
                        FROM projet p ,utilisateur u,concerne c,loginpassword l ,centrale ce,statutprojet s,utilisateurporteurprojet up
                        WHERE c.idprojet_projet = p.idprojet AND c.idcentrale_centrale = ce.idcentrale AND l.idlogin = u.idlogin_loginpassword
                        AND s.idstatutprojet = c.idstatutprojet_statutprojet AND up.idprojet_projet = p.idprojet AND up.idutilisateur_utilisateur = u.idutilisateur
                        AND ce.idcentrale =? and lower(p.numero) like lower(?) AND trashed =FALSE
                        union
                        SELECT p.titre,p.idprojet,p.numero,u.idutilisateur,p.refinterneprojet,p.dateprojet,ce.libellecentrale,s.libellestatutprojet,u.nom||' -  '|| u.prenom as demandeur,null as porteur
                        FROM projet p,creer c,utilisateur u,concerne,loginpassword l,centrale ce,statutprojet s
                        WHERE p.idprojet = c.idprojet_projet AND c.idutilisateur_utilisateur = u.idutilisateur AND concerne.idprojet_projet = p.idprojet AND
                        concerne.idcentrale_centrale = ce.idcentrale AND l.idlogin = u.idlogin_loginpassword AND s.idstatutprojet = concerne.idstatutprojet_statutprojet
                        AND ce.idcentrale =? and lower(p.titre) like lower(?) AND trashed =FALSE
                        union
                        SELECT p.titre,p.idprojet,p.numero,u.idutilisateur,p.refinterneprojet,p.dateprojet,ce.libellecentrale,s.libellestatutprojet,null as demandeur,u.nom||' -  '|| u.prenom as porteur
                        FROM projet p ,utilisateur u,concerne c,loginpassword l ,centrale ce,statutprojet s,utilisateurporteurprojet up
                        WHERE c.idprojet_projet = p.idprojet AND c.idcentrale_centrale = ce.idcentrale AND l.idlogin = u.idlogin_loginpassword
                        AND s.idstatutprojet = c.idstatutprojet_statutprojet AND up.idprojet_projet = p.idprojet AND up.idutilisateur_utilisateur = u.idutilisateur
                        AND ce.idcentrale =? and lower(p.titre) like lower(?) AND trashed =FALSE
                        union
                        SELECT p.titre,p.idprojet,p.numero,u.idutilisateur,p.refinterneprojet,p.dateprojet,ce.libellecentrale,s.libellestatutprojet,u.nom||' -  '|| u.prenom as demandeur,null as porteur
                        FROM projet p,creer c,utilisateur u,concerne,loginpassword l,centrale ce,statutprojet s
                        WHERE p.idprojet = c.idprojet_projet AND c.idutilisateur_utilisateur = u.idutilisateur AND concerne.idprojet_projet = p.idprojet AND
                        concerne.idcentrale_centrale = ce.idcentrale AND l.idlogin = u.idlogin_loginpassword AND s.idstatutprojet = concerne.idstatutprojet_statutprojet
                        AND ce.idcentrale =? and lower(p.description) like lower(?) AND trashed =FALSE
                        union
                        SELECT p.titre,p.idprojet,p.numero,u.idutilisateur,p.refinterneprojet,p.dateprojet,ce.libellecentrale,s.libellestatutprojet,null as demandeur,u.nom||' -  '|| u.prenom as porteur
                        FROM projet p ,utilisateur u,concerne c,loginpassword l ,centrale ce,statutprojet s,utilisateurporteurprojet up
                        WHERE c.idprojet_projet = p.idprojet AND c.idcentrale_centrale = ce.idcentrale AND l.idlogin = u.idlogin_loginpassword
                        AND s.idstatutprojet = c.idstatutprojet_statutprojet AND up.idprojet_projet = p.idprojet AND up.idutilisateur_utilisateur = u.idutilisateur
                        AND ce.idcentrale =? and lower(p.description) like lower(?) AND trashed =FALSE
                        union
                        SELECT p.titre,p.idprojet,p.numero,u.idutilisateur,p.refinterneprojet,p.dateprojet,ce.libellecentrale,s.libellestatutprojet,u.nom||' -  '|| u.prenom as demandeur, null as porteur
                        FROM projet p,creer c,utilisateur u,concerne,loginpassword l,centrale ce,statutprojet s
                        WHERE p.idprojet = c.idprojet_projet AND c.idutilisateur_utilisateur = u.idutilisateur AND concerne.idprojet_projet = p.idprojet AND
                        concerne.idcentrale_centrale = ce.idcentrale AND l.idlogin = u.idlogin_loginpassword AND s.idstatutprojet = concerne.idstatutprojet_statutprojet
                        AND ce.idcentrale =? and lower(u.nom) like lower(?) AND trashed =FALSE
                        union
                        SELECT p.titre,p.idprojet,p.numero,u.idutilisateur,p.refinterneprojet,p.dateprojet,ce.libellecentrale,s.libellestatutprojet,u.nom||' -  '|| u.prenom as demandeur, null as porteur
                        FROM projet p,creer c,utilisateur u,concerne,loginpassword l,centrale ce,statutprojet s
                        WHERE p.idprojet = c.idprojet_projet AND c.idutilisateur_utilisateur = u.idutilisateur AND concerne.idprojet_projet = p.idprojet AND
                        concerne.idcentrale_centrale = ce.idcentrale AND l.idlogin = u.idlogin_loginpassword AND s.idstatutprojet = concerne.idstatutprojet_statutprojet
                        AND ce.idcentrale =? and lower(u.prenom) like lower(?) AND trashed =FALSE
                        union
                        SELECT p.titre,p.idprojet,p.numero,u.idutilisateur,p.refinterneprojet,p.dateprojet,ce.libellecentrale,s.libellestatutprojet,null as demandeur,u.nom||' -  '|| u.prenom as porteur
                        FROM projet p ,utilisateur u,concerne c,loginpassword l ,centrale ce,statutprojet s,utilisateurporteurprojet up
                        WHERE c.idprojet_projet = p.idprojet AND c.idcentrale_centrale = ce.idcentrale AND l.idlogin = u.idlogin_loginpassword
                        AND s.idstatutprojet = c.idstatutprojet_statutprojet AND up.idprojet_projet = p.idprojet AND up.idutilisateur_utilisateur = u.idutilisateur
                        AND ce.idcentrale =? and lower(u.nom) like lower(?) AND trashed =FALSE
                        union
                        SELECT p.titre,p.idprojet,p.numero,u.idutilisateur,p.refinterneprojet,p.dateprojet,ce.libellecentrale,s.libellestatutprojet,u.nom||' -  '|| u.prenom as demandeur, null as porteur
                        FROM projet p,creer c,utilisateur u,concerne,loginpassword l,centrale ce,statutprojet s
                        WHERE p.idprojet = c.idprojet_projet AND c.idutilisateur_utilisateur = u.idutilisateur AND concerne.idprojet_projet = p.idprojet AND
                        concerne.idcentrale_centrale = ce.idcentrale AND l.idlogin = u.idlogin_loginpassword AND s.idstatutprojet = concerne.idstatutprojet_statutprojet
                        AND ce.idcentrale =? and lower(p.refinterneprojet) like lower(?) AND trashed =FALSE
                        union
                        SELECT p.titre,p.idprojet,p.numero,u.idutilisateur,p.refinterneprojet,p.dateprojet,ce.libellecentrale,s.libellestatutprojet,null as demandeur,u.nom||' -  '|| u.prenom as porteur
                        FROM projet p ,utilisateur u,concerne c,loginpassword l ,centrale ce,statutprojet s,utilisateurporteurprojet up
                        WHERE c.idprojet_projet = p.idprojet AND c.idcentrale_centrale = ce.idcentrale AND l.idlogin = u.idlogin_loginpassword
                        AND s.idstatutprojet = c.idstatutprojet_statutprojet AND up.idprojet_projet = p.idprojet AND up.idutilisateur_utilisateur = u.idutilisateur
                        AND ce.idcentrale =? and lower(p.refinterneprojet) like lower(?)  AND trashed =FALSE
                        union
                        SELECT p.titre,p.idprojet,p.numero,u.idutilisateur,p.refinterneprojet,p.dateprojet,ce.libellecentrale,s.libellestatutprojet,null as demandeur,u.nom||' -  '|| u.prenom as porteur
                        FROM projet p ,utilisateur u,concerne c,loginpassword l ,centrale ce,statutprojet s,utilisateurporteurprojet up
                        WHERE c.idprojet_projet = p.idprojet AND c.idcentrale_centrale = ce.idcentrale AND l.idlogin = u.idlogin_loginpassword
                        AND s.idstatutprojet = c.idstatutprojet_statutprojet AND up.idprojet_projet = p.idprojet AND up.idutilisateur_utilisateur = u.idutilisateur
                        AND ce.idcentrale =?  AND  lower(u.nom) like lower(?) AND trashed =FALSE
                        union
                        SELECT p.titre,p.idprojet,p.numero,u.idutilisateur,p.refinterneprojet,p.dateprojet,ce.libellecentrale,s.libellestatutprojet,null as demandeur,u.nom||' -  '|| u.prenom as porteur
                        FROM projet p ,utilisateur u,concerne c,loginpassword l ,centrale ce,statutprojet s,utilisateurporteurprojet up
                        WHERE c.idprojet_projet = p.idprojet AND c.idcentrale_centrale = ce.idcentrale AND l.idlogin = u.idlogin_loginpassword
                        AND s.idstatutprojet = c.idstatutprojet_statutprojet AND up.idprojet_projet = p.idprojet AND up.idutilisateur_utilisateur = u.idutilisateur
                        AND ce.idcentrale =?  AND  lower(u.prenom) like lower(?) AND trashed =FALSE );
                    ", array(
                        $idcentrale, '%' . $_POST['rechercheglobale'] . '%', $idcentrale, '%' . $_POST['rechercheglobale'] . '%',$idcentrale, '%'. $_POST['rechercheglobale'] . '%', 
                        $idcentrale, '%' . $_POST['rechercheglobale'] . '%', $idcentrale, '%' . $_POST['rechercheglobale'] . '%',$idcentrale, '%'. $_POST['rechercheglobale'] . '%', 
                        $idcentrale, '%' . $_POST['rechercheglobale'] . '%', $idcentrale, '%' . $_POST['rechercheglobale'] . '%',$idcentrale, "%". $_POST['rechercheglobale'] . "%",
                        $idcentrale, "%".  $_POST['rechercheglobale'] . "%", $idcentrale, '%' . $_POST['rechercheglobale'] . '%',$idcentrale, '%'. $_POST['rechercheglobale'] . '%', 
                        $idcentrale, "%".  $_POST['rechercheglobale'] . "%")
                );
            } elseif ($idtypeutilisateur == ADMINNATIONNAL) {
                $manager->exeRequete("drop table if exists TMPRECHERCHE");
                $manager->getRequete("CREATE TABLE TMPRECHERCHE AS (
                        SELECT p.titre,p.idprojet,p.numero,u.idutilisateur,p.refinterneprojet,p.dateprojet,ce.libellecentrale,s.libellestatutprojet,u.nom||' -  '|| u.prenom as demandeur,null as porteur
                            FROM projet p,creer c,utilisateur u,concerne,loginpassword l,centrale ce,statutprojet s
                            WHERE p.idprojet = c.idprojet_projet AND c.idutilisateur_utilisateur = u.idutilisateur AND concerne.idprojet_projet = p.idprojet AND
                            concerne.idcentrale_centrale = ce.idcentrale AND l.idlogin = u.idlogin_loginpassword AND s.idstatutprojet = concerne.idstatutprojet_statutprojet
                            AND lower(p.numero) like lower(?) AND trashed =FALSE
                            union
                            SELECT p.titre,p.idprojet,p.numero,u.idutilisateur,p.refinterneprojet,p.dateprojet,ce.libellecentrale,s.libellestatutprojet,null as demandeur,u.nom||' -  '|| u.prenom as porteur
                            FROM projet p ,utilisateur u,concerne c,loginpassword l ,centrale ce,statutprojet s,utilisateurporteurprojet up
                            WHERE c.idprojet_projet = p.idprojet AND c.idcentrale_centrale = ce.idcentrale AND l.idlogin = u.idlogin_loginpassword
                            AND s.idstatutprojet = c.idstatutprojet_statutprojet AND up.idprojet_projet = p.idprojet AND up.idutilisateur_utilisateur = u.idutilisateur
                            AND  lower(p.numero) like lower(?) AND trashed =FALSE
                            union
                            SELECT p.titre,p.idprojet,p.numero,u.idutilisateur,p.refinterneprojet,p.dateprojet,ce.libellecentrale,s.libellestatutprojet,u.nom||' -  '|| u.prenom as demandeur,null as porteur
                            FROM projet p,creer c,utilisateur u,concerne,loginpassword l,centrale ce,statutprojet s
                            WHERE p.idprojet = c.idprojet_projet AND c.idutilisateur_utilisateur = u.idutilisateur AND concerne.idprojet_projet = p.idprojet AND
                            concerne.idcentrale_centrale = ce.idcentrale AND l.idlogin = u.idlogin_loginpassword AND s.idstatutprojet = concerne.idstatutprojet_statutprojet
                            AND  lower(p.titre) like lower(?) AND trashed =FALSE
                            union
                            SELECT p.titre,p.idprojet,p.numero,u.idutilisateur,p.refinterneprojet,p.dateprojet,ce.libellecentrale,s.libellestatutprojet,null as demandeur,u.nom||' -  '|| u.prenom as porteur
                            FROM projet p ,utilisateur u,concerne c,loginpassword l ,centrale ce,statutprojet s,utilisateurporteurprojet up
                            WHERE c.idprojet_projet = p.idprojet AND c.idcentrale_centrale = ce.idcentrale AND l.idlogin = u.idlogin_loginpassword
                            AND s.idstatutprojet = c.idstatutprojet_statutprojet AND up.idprojet_projet = p.idprojet AND up.idutilisateur_utilisateur = u.idutilisateur
                            AND  lower(p.titre) like lower(?) AND trashed =FALSE
                            union
                            SELECT p.titre,p.idprojet,p.numero,u.idutilisateur,p.refinterneprojet,p.dateprojet,ce.libellecentrale,s.libellestatutprojet,u.nom||' -  '|| u.prenom as demandeur,null as porteur
                            FROM projet p,creer c,utilisateur u,concerne,loginpassword l,centrale ce,statutprojet s
                            WHERE p.idprojet = c.idprojet_projet AND c.idutilisateur_utilisateur = u.idutilisateur AND concerne.idprojet_projet = p.idprojet AND
                            concerne.idcentrale_centrale = ce.idcentrale AND l.idlogin = u.idlogin_loginpassword AND s.idstatutprojet = concerne.idstatutprojet_statutprojet
                            AND  lower(p.description) like lower(?) AND trashed =FALSE
                            union
                            SELECT p.titre,p.idprojet,p.numero,u.idutilisateur,p.refinterneprojet,p.dateprojet,ce.libellecentrale,s.libellestatutprojet,null as demandeur,u.nom||' -  '|| u.prenom as porteur
                            FROM projet p ,utilisateur u,concerne c,loginpassword l ,centrale ce,statutprojet s,utilisateurporteurprojet up
                            WHERE c.idprojet_projet = p.idprojet AND c.idcentrale_centrale = ce.idcentrale AND l.idlogin = u.idlogin_loginpassword
                            AND s.idstatutprojet = c.idstatutprojet_statutprojet AND up.idprojet_projet = p.idprojet AND up.idutilisateur_utilisateur = u.idutilisateur
                            AND lower(p.description) like lower(?) AND trashed =FALSE
                            union
                            SELECT p.titre,p.idprojet,p.numero,u.idutilisateur,p.refinterneprojet,p.dateprojet,ce.libellecentrale,s.libellestatutprojet,u.nom||' -  '|| u.prenom as demandeur, null as porteur
                            FROM projet p,creer c,utilisateur u,concerne,loginpassword l,centrale ce,statutprojet s
                            WHERE p.idprojet = c.idprojet_projet AND c.idutilisateur_utilisateur = u.idutilisateur AND concerne.idprojet_projet = p.idprojet AND
                            concerne.idcentrale_centrale = ce.idcentrale AND l.idlogin = u.idlogin_loginpassword AND s.idstatutprojet = concerne.idstatutprojet_statutprojet
                            AND lower(u.nom) like lower(?) AND trashed =FALSE
                            union
                            SELECT p.titre,p.idprojet,p.numero,u.idutilisateur,p.refinterneprojet,p.dateprojet,ce.libellecentrale,s.libellestatutprojet,u.nom||' -  '|| u.prenom as demandeur, null as porteur
                            FROM projet p,creer c,utilisateur u,concerne,loginpassword l,centrale ce,statutprojet s
                            WHERE p.idprojet = c.idprojet_projet AND c.idutilisateur_utilisateur = u.idutilisateur AND concerne.idprojet_projet = p.idprojet AND
                            concerne.idcentrale_centrale = ce.idcentrale AND l.idlogin = u.idlogin_loginpassword AND s.idstatutprojet = concerne.idstatutprojet_statutprojet
                            AND lower(u.prenom) like lower(?) AND trashed =FALSE
                            union
                            SELECT p.titre,p.idprojet,p.numero,u.idutilisateur,p.refinterneprojet,p.dateprojet,ce.libellecentrale,s.libellestatutprojet,null as demandeur,u.nom||' -  '|| u.prenom as porteur
                            FROM projet p ,utilisateur u,concerne c,loginpassword l ,centrale ce,statutprojet s,utilisateurporteurprojet up
                            WHERE c.idprojet_projet = p.idprojet AND c.idcentrale_centrale = ce.idcentrale AND l.idlogin = u.idlogin_loginpassword
                            AND s.idstatutprojet = c.idstatutprojet_statutprojet AND up.idprojet_projet = p.idprojet AND up.idutilisateur_utilisateur = u.idutilisateur
                            AND lower(u.nom) like lower(?) AND trashed =FALSE
                            union
                            SELECT p.titre,p.idprojet,p.numero,u.idutilisateur,p.refinterneprojet,p.dateprojet,ce.libellecentrale,s.libellestatutprojet,u.nom||' -  '|| u.prenom as demandeur, null as porteur
                            FROM projet p,creer c,utilisateur u,concerne,loginpassword l,centrale ce,statutprojet s
                            WHERE p.idprojet = c.idprojet_projet AND c.idutilisateur_utilisateur = u.idutilisateur AND concerne.idprojet_projet = p.idprojet AND
                            concerne.idcentrale_centrale = ce.idcentrale AND l.idlogin = u.idlogin_loginpassword AND s.idstatutprojet = concerne.idstatutprojet_statutprojet
                            AND  lower(p.refinterneprojet) like lower(?) AND trashed =FALSE
                            union
                            SELECT p.titre,p.idprojet,p.numero,u.idutilisateur,p.refinterneprojet,p.dateprojet,ce.libellecentrale,s.libellestatutprojet,null as demandeur,u.nom||' -  '|| u.prenom as porteur
                            FROM projet p ,utilisateur u,concerne c,loginpassword l ,centrale ce,statutprojet s,utilisateurporteurprojet up
                            WHERE c.idprojet_projet = p.idprojet AND c.idcentrale_centrale = ce.idcentrale AND l.idlogin = u.idlogin_loginpassword
                            AND s.idstatutprojet = c.idstatutprojet_statutprojet AND up.idprojet_projet = p.idprojet AND up.idutilisateur_utilisateur = u.idutilisateur
                            AND  lower(p.refinterneprojet) like lower(?) AND trashed =FALSE
                            union
                            SELECT p.titre,p.idprojet,p.numero,u.idutilisateur,p.refinterneprojet,p.dateprojet,ce.libellecentrale,s.libellestatutprojet,null as demandeur,u.nom||' -  '|| u.prenom as porteur
                            FROM utilisateurporteurprojet up,utilisateur u,projet p,concerne co,loginpassword l,centrale ce,statutprojet s 
                            WHERE up.idprojet_projet = p.idprojet AND up.idutilisateur_utilisateur = u.idutilisateur AND co.idprojet_projet = p.idprojet AND co.idstatutprojet_statutprojet = s.idstatutprojet 
                            AND l.idlogin = u.idlogin_loginpassword AND ce.idcentrale = co.idcentrale_centrale	AND  lower(u.nom) like lower(?) AND trashed =FALSE
                            union
                            SELECT p.titre,p.idprojet,p.numero,u.idutilisateur,p.refinterneprojet,p.dateprojet,ce.libellecentrale,s.libellestatutprojet,null as demandeur,u.nom||' -  '|| u.prenom as porteur
                            FROM utilisateurporteurprojet up,utilisateur u,projet p,concerne co,loginpassword l,centrale ce,statutprojet s 
                            WHERE up.idprojet_projet = p.idprojet AND up.idutilisateur_utilisateur = u.idutilisateur AND co.idprojet_projet = p.idprojet AND co.idstatutprojet_statutprojet = s.idstatutprojet 
                            AND l.idlogin = u.idlogin_loginpassword AND ce.idcentrale = co.idcentrale_centrale	AND  lower(u.prenom) like  lower(?)
                        );", array(
                    '%' . $_POST['rechercheglobale'] . '%', '%' . $_POST['rechercheglobale'] . '%', '%' . $_POST['rechercheglobale'] . '%',
                    '%' . $_POST['rechercheglobale'] . '%', '%' . $_POST['rechercheglobale'] . '%', '%' . $_POST['rechercheglobale'] . '%',
                    '%' . $_POST['rechercheglobale'] . '%', '%' . $_POST['rechercheglobale'] . '%', '%' . $_POST['rechercheglobale'] . '%',
                    '%' . $_POST['rechercheglobale'] . '%', '%' . $_POST['rechercheglobale'] . '%', '%' . $_POST['rechercheglobale'] . '%',
                    '%' . $_POST['rechercheglobale'] . "%")
                );
            } elseif ($idtypeutilisateur == UTILISATEUR) {
                $manager->exeRequete("drop table if exists TMPRECHERCHE");
                $manager->getRequete("CREATE TABLE TMPRECHERCHE AS (
                        SELECT p.titre,p.idprojet,p.numero,u.idutilisateur,p.refinterneprojet,p.dateprojet,ce.libellecentrale,s.libellestatutprojet,u.nom||' -  '|| u.prenom as demandeur,null as porteur
                            FROM projet p,creer c,utilisateur u,concerne,loginpassword l,centrale ce,statutprojet s
                            WHERE p.idprojet = c.idprojet_projet AND c.idutilisateur_utilisateur = u.idutilisateur AND concerne.idprojet_projet = p.idprojet AND
                            concerne.idcentrale_centrale = ce.idcentrale AND l.idlogin = u.idlogin_loginpassword AND s.idstatutprojet = concerne.idstatutprojet_statutprojet
                            AND lower(p.numero) like lower(?) AND trashed =FALSE and l.pseudo=?
                            union
                            SELECT p.titre,p.idprojet,p.numero,u.idutilisateur,p.refinterneprojet,p.dateprojet,ce.libellecentrale,s.libellestatutprojet,null as demandeur,u.nom||' -  '|| u.prenom as porteur
                            FROM projet p ,utilisateur u,concerne c,loginpassword l ,centrale ce,statutprojet s,utilisateurporteurprojet up
                            WHERE c.idprojet_projet = p.idprojet AND c.idcentrale_centrale = ce.idcentrale AND l.idlogin = u.idlogin_loginpassword
                            AND s.idstatutprojet = c.idstatutprojet_statutprojet AND up.idprojet_projet = p.idprojet AND up.idutilisateur_utilisateur = u.idutilisateur
                            AND  lower(p.numero) like lower(?) AND trashed =FALSE and l.pseudo=? 
                            union
                            SELECT p.titre,p.idprojet,p.numero,u.idutilisateur,p.refinterneprojet,p.dateprojet,ce.libellecentrale,s.libellestatutprojet,u.nom||' -  '|| u.prenom as demandeur,null as porteur
                            FROM projet p,creer c,utilisateur u,concerne,loginpassword l,centrale ce,statutprojet s
                            WHERE p.idprojet = c.idprojet_projet AND c.idutilisateur_utilisateur = u.idutilisateur AND concerne.idprojet_projet = p.idprojet AND
                            concerne.idcentrale_centrale = ce.idcentrale AND l.idlogin = u.idlogin_loginpassword AND s.idstatutprojet = concerne.idstatutprojet_statutprojet
                            AND  lower(p.titre) like lower(?) AND trashed =FALSE and l.pseudo=? 
                            union
                            SELECT p.titre,p.idprojet,p.numero,u.idutilisateur,p.refinterneprojet,p.dateprojet,ce.libellecentrale,s.libellestatutprojet,null as demandeur,u.nom||' -  '|| u.prenom as porteur
                            FROM projet p ,utilisateur u,concerne c,loginpassword l ,centrale ce,statutprojet s,utilisateurporteurprojet up
                            WHERE c.idprojet_projet = p.idprojet AND c.idcentrale_centrale = ce.idcentrale AND l.idlogin = u.idlogin_loginpassword
                            AND s.idstatutprojet = c.idstatutprojet_statutprojet AND up.idprojet_projet = p.idprojet AND up.idutilisateur_utilisateur = u.idutilisateur
                            AND  lower(p.titre) like lower(?) AND trashed =FALSE and l.pseudo=? 
                            union
                            SELECT p.titre,p.idprojet,p.numero,u.idutilisateur,p.refinterneprojet,p.dateprojet,ce.libellecentrale,s.libellestatutprojet,u.nom||' -  '|| u.prenom as demandeur,null as porteur
                            FROM projet p,creer c,utilisateur u,concerne,loginpassword l,centrale ce,statutprojet s
                            WHERE p.idprojet = c.idprojet_projet AND c.idutilisateur_utilisateur = u.idutilisateur AND concerne.idprojet_projet = p.idprojet AND
                            concerne.idcentrale_centrale = ce.idcentrale AND l.idlogin = u.idlogin_loginpassword AND s.idstatutprojet = concerne.idstatutprojet_statutprojet
                            AND  lower(p.description) like lower(?) AND trashed =FALSE and l.pseudo=? 
                            union
                            SELECT p.titre,p.idprojet,p.numero,u.idutilisateur,p.refinterneprojet,p.dateprojet,ce.libellecentrale,s.libellestatutprojet,null as demandeur,u.nom||' -  '|| u.prenom as porteur
                            FROM projet p ,utilisateur u,concerne c,loginpassword l ,centrale ce,statutprojet s,utilisateurporteurprojet up
                            WHERE c.idprojet_projet = p.idprojet AND c.idcentrale_centrale = ce.idcentrale AND l.idlogin = u.idlogin_loginpassword
                            AND s.idstatutprojet = c.idstatutprojet_statutprojet AND up.idprojet_projet = p.idprojet AND up.idutilisateur_utilisateur = u.idutilisateur
                            AND lower(p.description) like lower(?) AND trashed =FALSE and l.pseudo=? 
                            union
                            SELECT p.titre,p.idprojet,p.numero,u.idutilisateur,p.refinterneprojet,p.dateprojet,ce.libellecentrale,s.libellestatutprojet,u.nom||' -  '|| u.prenom as demandeur, null as porteur
                            FROM projet p,creer c,utilisateur u,concerne,loginpassword l,centrale ce,statutprojet s
                            WHERE p.idprojet = c.idprojet_projet AND c.idutilisateur_utilisateur = u.idutilisateur AND concerne.idprojet_projet = p.idprojet AND
                            concerne.idcentrale_centrale = ce.idcentrale AND l.idlogin = u.idlogin_loginpassword AND s.idstatutprojet = concerne.idstatutprojet_statutprojet
                            AND lower(u.nom) like lower(?) AND trashed =FALSE and l.pseudo=? 
                            union
                            SELECT p.titre,p.idprojet,p.numero,u.idutilisateur,p.refinterneprojet,p.dateprojet,ce.libellecentrale,s.libellestatutprojet,u.nom||' -  '|| u.prenom as demandeur, null as porteur
                            FROM projet p,creer c,utilisateur u,concerne,loginpassword l,centrale ce,statutprojet s
                            WHERE p.idprojet = c.idprojet_projet AND c.idutilisateur_utilisateur = u.idutilisateur AND concerne.idprojet_projet = p.idprojet AND
                            concerne.idcentrale_centrale = ce.idcentrale AND l.idlogin = u.idlogin_loginpassword AND s.idstatutprojet = concerne.idstatutprojet_statutprojet
                            AND lower(u.prenom) like lower(?) AND trashed =FALSE and l.pseudo=? 
                            union
                            SELECT p.titre,p.idprojet,p.numero,u.idutilisateur,p.refinterneprojet,p.dateprojet,ce.libellecentrale,s.libellestatutprojet,null as demandeur,u.nom||' -  '|| u.prenom as porteur
                            FROM projet p ,utilisateur u,concerne c,loginpassword l ,centrale ce,statutprojet s,utilisateurporteurprojet up
                            WHERE c.idprojet_projet = p.idprojet AND c.idcentrale_centrale = ce.idcentrale AND l.idlogin = u.idlogin_loginpassword
                            AND s.idstatutprojet = c.idstatutprojet_statutprojet AND up.idprojet_projet = p.idprojet AND up.idutilisateur_utilisateur = u.idutilisateur
                            AND lower(u.nom) like lower(?) AND trashed =FALSE and l.pseudo=? 
                            union
                            SELECT p.titre,p.idprojet,p.numero,u.idutilisateur,p.refinterneprojet,p.dateprojet,ce.libellecentrale,s.libellestatutprojet,null as demandeur,u.nom||' -  '|| u.prenom as porteur
                            FROM projet p ,utilisateur u,concerne c,loginpassword l ,centrale ce,statutprojet s,utilisateurporteurprojet up
                            WHERE c.idprojet_projet = p.idprojet AND c.idcentrale_centrale = ce.idcentrale AND l.idlogin = u.idlogin_loginpassword
                            AND s.idstatutprojet = c.idstatutprojet_statutprojet AND up.idprojet_projet = p.idprojet AND up.idutilisateur_utilisateur = u.idutilisateur
                            AND lower(u.prenom) like lower(?) AND trashed =FALSE and l.pseudo=? 
                            union
                            SELECT p.titre,p.idprojet,p.numero,u.idutilisateur,p.refinterneprojet,p.dateprojet,ce.libellecentrale,s.libellestatutprojet,u.nom||' -  '|| u.prenom as demandeur, null as porteur
                            FROM projet p,creer c,utilisateur u,concerne,loginpassword l,centrale ce,statutprojet s
                            WHERE p.idprojet = c.idprojet_projet AND c.idutilisateur_utilisateur = u.idutilisateur AND concerne.idprojet_projet = p.idprojet AND
                            concerne.idcentrale_centrale = ce.idcentrale AND l.idlogin = u.idlogin_loginpassword AND s.idstatutprojet = concerne.idstatutprojet_statutprojet
                            AND  lower(p.refinterneprojet) like lower(?) AND trashed =FALSE and l.pseudo=? 
                            union
                            SELECT p.titre,p.idprojet,p.numero,u.idutilisateur,p.refinterneprojet,p.dateprojet,ce.libellecentrale,s.libellestatutprojet,null as demandeur,u.nom||' -  '|| u.prenom as porteur
                            FROM projet p ,utilisateur u,concerne c,loginpassword l ,centrale ce,statutprojet s,utilisateurporteurprojet up
                            WHERE c.idprojet_projet = p.idprojet AND c.idcentrale_centrale = ce.idcentrale AND l.idlogin = u.idlogin_loginpassword
                            AND s.idstatutprojet = c.idstatutprojet_statutprojet AND up.idprojet_projet = p.idprojet AND up.idutilisateur_utilisateur = u.idutilisateur
                            AND  lower(p.refinterneprojet) like lower(?) AND trashed =FALSE and l.pseudo=? 
                        )", array(
                             '%' . $_POST['rechercheglobale'] . '%' , $pseudo,
                             '%' . $_POST['rechercheglobale'] . '%' , $pseudo,
                             '%' . $_POST['rechercheglobale'] . '%' , $pseudo,
                             '%' . $_POST['rechercheglobale'] . '%' , $pseudo,
                             '%' . $_POST['rechercheglobale'] . '%' , $pseudo,
                             '%' . $_POST['rechercheglobale'] . '%' , $pseudo,
                             '%' . $_POST['rechercheglobale'] . '%' , $pseudo, 
                             '%' . $_POST['rechercheglobale'] . '%' , $pseudo,
                             '%' . $_POST['rechercheglobale'] . '%' , $pseudo,
                             '%' . $_POST['rechercheglobale'] . '%' , $pseudo, 
                             '%' . $_POST['rechercheglobale'] . '%' , $pseudo, 
                             '%' . $_POST['rechercheglobale'] . '%' , $pseudo
                            )
                        );
            }
            $porteur = '';
            $arrayporteur1 = $manager->getList("select distinct numero from tmprecherche");
            $arrayporteur = array();
            foreach ($arrayporteur1 as $key => $value) {
                $arrayporteur = $manager->getList2("select distinct porteur from tmprecherche where  numero=?", $value[0]);
                foreach ($arrayporteur as $key1 => $value1) {
                    if (!empty($value1[0])) {
                        $porteur.= $value1[0] . ' / ';
                    }                    
                    if(!empty($porteur)){
                        $porteur=substr($porteur, 0,-1);
                        $numero = $value[0];                        
                        $tmprecherche = new Tmprecherche($porteur, $numero);
                        $manager->updateRecherche1($tmprecherche, $numero);
                    }
                }
                $porteur = '';
            }
            
            $arrayRecherche = $manager->getList("select * from tmprecherche");
            $nbarray=count($arrayRecherche);
            
            for ($i = 0; $i < $nbarray; $i++) {
                $demandeur= $manager->getSingle2("select nom||' -  '|| prenom from creer,utilisateur where idutilisateur = idutilisateur_utilisateur and idprojet_projet=?", $arrayRecherche[$i]['idprojet']);
                $manager->getRequete("update tmprecherche set demandeur = ? where idprojet=?",array($demandeur,$arrayRecherche[$i]['idprojet']));
            }
            
            
            $rand=  rand(0, 100000);
            $row = $manager->getList("select distinct on(numero) * from (select  * from tmprecherche where demandeur is not null "
                    . "union select * from tmprecherche where porteur is not null ) as toto");
            $fprow = fopen('tmp/resultatrechercheprojet'.$rand.'.json', 'w');
            $datausercompte = "";
            fwrite($fprow, '{"items": [');
            for ($i = 0; $i < count($row); $i++) {
                $datausercompte = ""
                        . '{"numero":' . '"' . $row[$i]['numero'] . '"' . ","
                        . '"dateprojet":' . '"' . $row[$i]['dateprojet'] . '"' . ","
                        . '"titre":' . '"' . filtredonnee($row[$i]['titre']) . '"' . ","
                        . '"refinterneprojet":' . '"' . filtredonnee($row[$i]['refinterneprojet']) . '"' . ","
                        . '"libellestatutprojet":' . '"' . str_replace("''", "'", $row[$i]['libellestatutprojet']) . '"' . ","
                        . '"idutilisateur":' . '"' . $row[$i]['idutilisateur'] . '"' . ","
                        . '"demandeur":' . '"' . ucfirst($row[$i]['demandeur']) . '"' . ","
                        . '"porteur":' . '"' . substr(ucfirst($row[$i]['porteur']),0,-1) . '"' . ","                        
                        . '"imprime":' . '"' . TXT_PDF . '"' . ","
                        . '"idprojet":' . '"' . $row[$i]['idprojet'] . '"' . ","
                        . '"libellecentrale":' . '"' . $row[$i]['libellecentrale'] . '"' . "},";
                fputs($fprow, $datausercompte);
                fwrite($fprow, '');
            }
            fwrite($fprow, ']}');
            $json_filerecherche = "tmp/resultatrechercheprojet".$rand.".json";
            $json_fileRecherche1 = file_get_contents($json_filerecherche);
            $json_fileRecherche = str_replace('},]}', '}]}', $json_fileRecherche1);
            file_put_contents($json_filerecherche, $json_fileRecherche);
            fclose($fprow);
            chmod('tmp/resultatrechercheprojet'.$rand.'.json', 0777);
            if (count($row) == 0) {
                echo '<fieldset id="recherche" style="border-color: #5D8BA2;margin-left:-32px;padding-left:10px;width: 1028px;">
          <legend style="color: #5D8BA2;">' . TXT_NORESULT . '</legend>';
            } else {
                echo '<fieldset id="recherche" style="border-color: #5D8BA2;margin-top:0px;margin-left:-32px;padding-left:10px;width: 1028px;">
          <legend style="color: #5D8BA2;">' . TXT_NBRESULT . ' :' . count($row) . ' ' . '</legend>';
            }
            ?>
            <?php if ($idtypeutilisateur == ADMINNATIONNAL) { ?>
                <div id="gridrechercheglobale" >
                    <script>
                        var gridrechercheglobale, dataStore, store;
                        require([
                            "dojox/grid/DataGrid",
                            "dojo/store/Memory",
                            "dojo/data/ObjectStore",
                            "dojo/request",
                            "dojo/domReady!"
                        ], function(DataGrid, Memory, ObjectStore, request) {
                            request.get("<?php echo '/' . REPERTOIRE.'/tmp/resultatrechercheprojet'.$rand.'.json'?>", {
                                handleAs: "json"
                            }).then(function(data) {
                                store = new Memory({data: data.items});
                                dataStore = new ObjectStore({objectStore: store});

                                function hrefFormatterPDF(index, idx) {
                                    var item = gridrechercheglobale.getItem(idx);
                                    var numero = item.numero;
                                    return "<a  href=\"<?php echo '/' . REPERTOIRE; ?>/pdf_project/<?php echo $lang; ?>/" + numero + "\" target='_blank'>" + '<img title="<?php echo TXT_GENERERPDF; ?>" src="<?php echo '/' . REPERTOIRE; ?>/styles/img/pdf_icongrid.png" />' + "</a>";
                                }
                                gridrechercheglobale = new DataGrid({
                                    store: dataStore,
                                    query: {id: "*"},
                                    structure: [
                                        {name: "<?php echo TXT_NUMERO; ?>", field: "numero", width: "75px"},
                                        {name: "<?php echo TXT_DATEDEMANDE; ?>", field: "dateprojet", width: "75px"},
                                        {name: "<?php echo TXT_TITREPROJET; ?>", field: "titre", width: "220px  "},
                                        {name: " ", field: "imprime", width: "30px", formatter: hrefFormatterPDF},                                        
                                        {name: "<?php echo TXT_REFINTERNE; ?>", field: "refinterneprojet", width: "90px"},
                                        {name: "<?php echo TXT_STATUTPROJETS; ?>", field: "libellestatutprojet", width: "105px"},
                                        {name: "<?php echo TXT_DEMANDEUR; ?>", field: "demandeur", width: "160px"},
                                        {name: "<?php echo TXT_PORTEURS; ?>", field: "porteur", width: "360px"}
                                    ]
                                }, "gridrechercheglobale");
                                gridrechercheglobale.startup();
                            });
                        });
                    </script>
                </div>
                </fieldset>
                <br>
            <?php } else {?>
                <div id="gridrechercheglobale" >
                    <script>
                        var gridrechercheglobale, dataStore, store;
                        require([
                            "dojox/grid/DataGrid",
                            "dojo/store/Memory",
                            "dojo/data/ObjectStore",
                            "dojo/request",
                            "dojo/domReady!"
                        ], function(DataGrid, Memory, ObjectStore, request) {                           
                           request.get("<?php echo '/' . REPERTOIRE.'/tmp/resultatrechercheprojet'.$rand.'.json'?>", {
                                handleAs: "json"
                            }).then(function(data) {
                                store = new Memory({data: data.items});
                                dataStore = new ObjectStore({objectStore: store});
                                function hrefFormatterNumero(numero, idx) {
                                    var item = gridrechercheglobale.getItem(idx);
                                    var centrale = item.libellecentrale;
                                    return "<a  href=\"<?php echo '/' . REPERTOIRE; ?>/controler/controlestatutprojet.php?lang=<?php echo $lang; ?>&numProjet=" + numero + "&centrale=" + centrale + "\">" + numero + "</a>";
                                }
                                function hrefFormatterPDF(index, idx) {
                                    var item = gridrechercheglobale.getItem(idx);
                                    var numero = item.numero;
                                    return "<a  href=\"<?php echo '/' . REPERTOIRE; ?>/pdf_project/<?php echo $lang; ?>/" + numero + "\" target='_blank'>" + '<img title="<?php echo TXT_GENERERPDF; ?>" src="<?php echo '/' . REPERTOIRE; ?>/styles/img/pdf_icongrid.png" />' + "</a>";
                                }
                                gridrechercheglobale = new DataGrid({
                                    store: dataStore,
                                    query: {id: "*"},
                                    structure: [
                                        {name: "<?php echo TXT_NUMERO; ?>", field: "numero", width: "75px", formatter: hrefFormatterNumero},
                                        {name: "<?php echo TXT_DATEDEMANDE; ?>", field: "dateprojet", width: "75px"},
                                        {name: "<?php echo TXT_TITREPROJET; ?>", field: "titre", width: "220px  "},
                                        {name: " ", field: "imprime", width: "30px", formatter: hrefFormatterPDF},                                        
                                        {name: "<?php echo TXT_REFINTERNE; ?>", field: "refinterneprojet", width: "90px"},
                                        {name: "<?php echo TXT_STATUTPROJETS; ?>", field: "libellestatutprojet", width: "105px"},
                                        {name: "<?php echo TXT_DEMANDEUR; ?>", field: "demandeur", width: "160px"},
                                        {name: "<?php echo TXT_PORTEURS; ?>", field: "porteur", width: "360px"}
                                    ]
                                }, "gridrechercheglobale");
                                gridrechercheglobale.startup();
                            });
                        });
                    </script>
                </div>
               </fieldset>
                <br>
            <?php } ?>
            <?php include 'html/footer.html'; ?>
        </div>
    </div>
    <?php
} else {
    echo '<fieldset id="recherche" style="border-color: #5D8BA2;margin-left:-32px;padding-left:10px;width: 1028px;">
          <legend style="color: #5D8BA2;font-size:1.1em">' . TXT_RECHERCHEGLOBALE . '</legend></fieldset>';
}