<?php

/* * ************************************************************************* */
/*          Projet Renatech : Outils  de gestion de projet                    */
/*          Copyright (C) 2013 CNRS RENATECH                                  */
/*          Contact: antoineadb@gmail.com                                     */
/*                                                                            */
/*                                                                            */
/* * ************************************************tat********************* */
include_once 'BD.php';
include_once 'Pays.php';
include_once 'Login.php';
include_once 'Autrestutelle.php';
include_once 'Tutelle.php';
include_once 'Autrenomemployeur.php';
include_once 'Autrecodeunite.php';
include_once 'Autredisciplinescientifique.php';
include_once 'Utilisateuracadext.php';
include_once 'Utilisateuracademique.php';
include_once 'Utilisateurindustriel.php';
include_once 'Appartient.php';
include_once 'Intervient.php';
include_once 'Projetresponsablecentrale.php';
include_once 'Projetutilisateur.php';
include_once 'Projetphase1.php';
include_once 'Centralecheck.php';
include_once 'Concerne.php';
include_once 'Creer.php';
include_once 'Nompays.php';
include_once 'CentraleName.php';
include_once 'Thematique.php';
include_once 'Typeentreprise.php';
include_once 'Typeprojet.php';
include_once 'Disciplinescientifique.php';
include_once 'Secteuractivite.php';
include_once 'Nomemployeur.php';
include_once 'Statutprojet.php';
include_once 'Personneaccueilcentrale.php';
include_once 'Partenaireprojet.php';
include_once 'Projetpartenaire.php';
include_once 'Projetphase2.php';
include_once 'Sourcefinancement.php';
include_once 'Autresourcefinancement.php';
include_once 'Autrethematique.php';
include_once 'Projetpersonneaccueilcentrale.php';
include_once 'Ressourceprojet.php';
include_once 'NomUtilisateur.php';
include_once 'PrenomUtilisateur.php';
include_once 'AdresseUser.php';
include_once 'CodepostalUser.php';
include_once 'VilleUser.php';
include_once 'PaysUser.php';
include_once 'TelephoneUser.php';
include_once 'FaxUser.php';
include_once 'LoginUtilisateur.php';
include_once 'MailUtilisateur.php';
include_once 'UtilisateurType.php';
include_once 'DateDebutProjet.php';
include_once 'DateStatutFiniProjet.php';
include_once 'DateStatutCloturerProjet.php';
include_once 'DateStatutRefusProjet.php';
include_once 'LoginPassword.php';
include_once 'LoginMotpasseEnvoye.php';
include_once 'LoginActif.php';
include_once 'UtilisateurMailresponsable.php';
include_once 'UtilisateurNomresponsable.php';
include_once 'UtilisateurNomemployeur.php';
include_once 'UtilisateurTutelle.php';
include_once 'UtilisateurDiscipline.php';
include_once 'Projetattachement.php';
include_once 'Projetattachementdesc.php';
include_once 'ConcernePhase1.php';
include_once 'UtilisateurPorteurProjet.php';
include_once 'Ressource.php';
include_once 'villeCentrale.php';
include_once 'Emailcentrale.php';
include_once 'Codeunitecentrale.php';
include_once 'Partenairefromprojet.php';
include_once 'UtilisateurAcronymelabo.php';
include_once 'UtilisateurTypeadmin.php';
include_once 'Projetphase2Porteur.php';
include_once 'UtilisateurNomlabo.php';
include_once 'Autresource.php';
include_once 'AutreThematiques.php';
include_once 'Acronyme.php';
include_once 'ProjetEditorDescription.php';
include_once 'ProjetEditorContexte.php';
include_once 'Projetsourcefinancement.php';
include_once 'ProjetAcrosourcefinancement.php';
include_once 'Projetcontextedescriptif.php';
include_once 'Tmprecherche.php';
if (is_file('../outils/affichelibelle.php')) {
    include_once '../outils/affichelibelle.php';
} elseif (is_file('outils/affichelibelle.php')) {
    include_once 'outils/affichelibelle.php';
}
include_once 'Libelleapplication.php';
include_once 'Sitewebapplication.php';
include_once 'Projettypeprojet.php';
include_once 'Typeformation.php';
include_once 'Porteurprojet.php';
include_once 'UtilisateurAdmin.php';
include_once 'DateMajProjet.php';

if (is_file('../decide-lang.php')) {
    include_once '../decide-lang.php';
} elseif (is_file('decide-lang.php')) {
    include_once 'decide-lang.php';
}
include_once 'Compteur.php';
include_once 'Projetautrecentrale.php';
include_once 'Rapport.php';
include_once 'Rapportfigure.php';
include_once 'Projet_centraleproximite.php';
include_once 'Centrale_proximite.php';
include_once 'UtilisateurQualiteaca.php';
include_once 'UtilisateurCodeunite.php';
include_once 'UtilisateurAutreCodeunite.php';
include_once 'UtilisateurQualiteindust.php';
include_once 'UtilisateurNomEntreprise.php';
include_once 'UserSecteurActivite.php';
include_once 'UserTypeEntreprise.php';
if (is_file('../outils/toolBox.php')) {
    include_once '../outils/toolBox.php';
} elseif (is_file('outils/toolBox.php')) {
    include_once 'outils/toolBox.php';
}
include_once 'PersonneCentraleQualite.php';
include_once 'Autresqualite.php';
include_once 'LoginParam.php';
include_once 'UtilisateurAdministrateur.php';

showError($_SERVER['PHP_SELF']);


class Manager {

    private $_db; //instance de PDO

    public function __construct($db) {
        $this->setDb($db);
    }

//------------------------------------------------------------------------------------------------------------
//                                       LOGIN
//------------------------------------------------------------------------------------------------------------
    public function addlogin(Login $login) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('INSERT INTO loginpassword (idlogin,mail,motdepasse,pseudo,tmpcx)VALUES(?,?,?,?,?)');
            $idlogin = $login->getIdlogin();
            $mail = $login->getEmail();
            $motdepasse = $login->getMotpasse();
            $tmpcx = $login->getTmpcx();
            $pseudo = $login->getPseudo();
            $requete->bindParam(1, $idlogin, PDO::PARAM_INT);
            $requete->bindParam(2, $mail, PDO::PARAM_STR);
            $requete->bindParam(3, $motdepasse, PDO::PARAM_STR);
            $requete->bindParam(4, $pseudo, PDO::PARAM_STR);
            $requete->bindParam(5, $tmpcx, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_MANAERRLOGIN . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

//------------------------------------------------------------------------------------------------------------
//                                       LOGINPASSWORD
//------------------------------------------------------------------------------------------------------------
    public function updateloginpassword(LoginPassword $loginpassword) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('update loginpassword set motdepasse=? where pseudo=?');
            $motdepasse = $loginpassword->getMotpasse();
            $pseudo = $loginpassword->getPseudo();
            ;
            $requete->bindParam(1, $motdepasse, PDO::PARAM_STR);
            $requete->bindParam(2, $pseudo, PDO::PARAM_STR);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_MANAERRLOGIN . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }
 public function updateloginParam(LoginParam $loginparam) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('update loginpassword set tmpcx=? where pseudo=?');
            $pseudo = $loginparam->getPseudo();
            $tmpcx = $loginparam->getTmpcx();        
            $requete->bindParam(1, $tmpcx, PDO::PARAM_INT);
            $requete->bindParam(2, $pseudo, PDO::PARAM_STR);            
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_MANAERRLOGIN . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }
//------------------------------------------------------------------------------------------------------------
//                                       LOGINACTIF
//------------------------------------------------------------------------------------------------------------
    public function updateLoginActif(LoginActif $loginActif, $idlogin) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('update loginpassword set actif =? where idlogin =?');
            $actif = $loginActif->getActif();
            $requete->bindParam(1, $actif, PDO::PARAM_BOOL);
            $requete->bindParam(2, $idlogin, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_MANAERRLOGIN . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

//------------------------------------------------------------------------------------------------------------
//                                       LOGINMOTDEPASSENVOYE
//------------------------------------------------------------------------------------------------------------
    public function updateMotpasseenvoye(LoginMotPasseEnvoye $loginMotpasseenvoye) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('UPDATE loginpassword SET  motpasseenvoye=? WHERE   pseudo=?');
            $motdepasseenvoye = $loginMotpasseenvoye->getMotpasseenvoye();
            $pseudo = $loginMotpasseenvoye->getPseudo();
            $requete->bindParam(1, $motdepasseenvoye, PDO::PARAM_BOOL);
            $requete->bindParam(2, $pseudo, PDO::PARAM_STR);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_MANAERRLOGIN . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

//------------------------------------------------------------------------------------------------------------
//                                       UTILISATEUR ACADEMIQUE
//------------------------------------------------------------------------------------------------------------
    public function addUtilisateuracademique(Utilisateuracademique $utilisateur) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('INSERT INTO utilisateur(nom, prenom,entrepriselaboratoire, adresse, codepostal, ville, datecreation,telephone, fax,nomResponsable,mailresponsable,idtypeutilisateur_typeutilisateur, idpays_pays, idlogin_loginpassword, iddiscipline_disciplinescientifique,
idcentrale_centrale, idqualitedemandeuraca_qualitedemandeuraca,idtutelle_tutelle, idemployeur_nomemployeur, idautrestutelle_autrestutelle,idautrediscipline_autredisciplinescientifique, idautrenomemployeur_autrenomemployeur,
idautrecodeunite_autrecodeunite,acronymelaboratoire) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
            $nom = $utilisateur->getNom();
            $prenom = $utilisateur->getPrenom();
            $entrepriselaboratoire = $utilisateur->getEntrepriselaboratoire();
            $adresse = $utilisateur->getAdresse();
            $codepostal = $utilisateur->getCodePostal();
            $ville = $utilisateur->getVille();
            $date = $utilisateur->getDate();
            $tel = $utilisateur->getTel();
            $fax = $utilisateur->getFax();
            $nomresponsable = $utilisateur->getNomresponsable();
            $mailresponsable = $utilisateur->getMailresponsable();
            $idtypeutilisateur = $utilisateur->getIdtypeutilisateur_typeutilisateur();
            $idpays = $utilisateur->getIdpays_pays();
            $idlogin = $utilisateur->getIdlogin_loginpassword();
            $iddiscipline = $utilisateur->getIddiscipline_disciplinescientifique();
            $idcentrale = $utilisateur->getIdcentrale_centrale();
            $idqualitedemandeuraca = $utilisateur->getIdqualitedemandeuraca_qualitedemandeuraca();
            $idtutelle = $utilisateur->getIdtutelle_tutelle();
            $idemployeur = $utilisateur->getIdemployeur_nomemployeur();
            $idautrestutelle = $utilisateur->getIdautrestutelle_autrestutelle();
            $idautrediscipline = $utilisateur->getIdautrediscipline_autredisciplinescientifique();
            $idautrenomemployeur = $utilisateur->getIdautrenomemployeur_autrenomemployeur();
            $idautrecodeunite = $utilisateur->getIdautrecodeunite_autrecodeunite();
            $acronymelaboratoire = $utilisateur->getAcronymelaboratoire();
            $requete->bindParam(1, $nom, PDO::PARAM_STR);
            $requete->bindParam(2, $prenom, PDO::PARAM_STR);
            $requete->bindParam(3, $entrepriselaboratoire, PDO::PARAM_STR);
            $requete->bindParam(4, $adresse, PDO::PARAM_STR);
            $requete->bindParam(5, $codepostal, PDO::PARAM_INT);
            $requete->bindParam(6, $ville, PDO::PARAM_STR);
            $requete->bindParam(7, $date, PDO::PARAM_STR);
            $requete->bindParam(8, $tel, PDO::PARAM_STR);
            $requete->bindParam(9, $fax, PDO::PARAM_STR);
            $requete->bindParam(10, $nomresponsable, PDO::PARAM_STR);
            $requete->bindParam(11, $mailresponsable, PDO::PARAM_STR);
            $requete->bindParam(12, $idtypeutilisateur, PDO::PARAM_INT);
            $requete->bindParam(13, $idpays, PDO::PARAM_INT);
            $requete->bindParam(14, $idlogin, PDO::PARAM_INT);
            $requete->bindParam(15, $iddiscipline, PDO::PARAM_INT);
            $requete->bindParam(16, $idcentrale, PDO::PARAM_INT);
            $requete->bindParam(17, $idqualitedemandeuraca, PDO::PARAM_INT);
            $requete->bindParam(18, $idtutelle, PDO::PARAM_INT);
            $requete->bindParam(19, $idemployeur, PDO::PARAM_INT);
            $requete->bindParam(20, $idautrestutelle, PDO::PARAM_INT);
            $requete->bindParam(21, $idautrediscipline, PDO::PARAM_INT);
            $requete->bindParam(22, $idautrenomemployeur, PDO::PARAM_INT);
            $requete->bindParam(23, $idautrecodeunite, PDO::PARAM_INT);
            $requete->bindParam(24, $acronymelaboratoire, PDO::PARAM_STR);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRINSERTUSER . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }
    
    public function updateUtilisateurAdministrateur(UtilisateurAdministrateur $userAdmin,$idutilisateur){
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('update utilisateur set administrateur = ? where idutilisateur =?');            
            $administrateur = $userAdmin->getAdministrateur();
            $requete->bindParam(1, $administrateur, PDO::PARAM_INT);
            $requete->bindParam(2, $idutilisateur, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRUPDATEPROJET . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function insertProjetSF(Projetsourcefinancement $projet) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('INSERT INTO projetsourcefinancement(idprojet_projet,idsourcefinancement_sourcefinancement) VALUES (?,?)');
            $idprojet = $projet->getIdprojet();
            $idsourcefinancement = $projet->getIdsourcefinancement();
            $requete->bindParam(1, $idprojet, PDO::PARAM_INT);
            $requete->bindParam(2, $idsourcefinancement, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRUPDATEPROJET . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function deleteprojetsf($idprojet) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("delete from projetsourcefinancement where idprojet_projet =?");
            $requete->bindParam(1, $idprojet, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRDELETEPROJETDATEDEBUT . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function updateProjetacrosourcefinancement(ProjetAcrosourcefinancement $projet, $idprojet) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("update projetsourcefinancement set acronymesource=? where idprojet_projet=? and idsourcefinancement_sourcefinancement =?");
            $acronymesource = $projet->getAcronymesource();
            $idsourcefinancement = $projet->getIdsourcefinancement();
            $requete->bindParam(1, $acronymesource, PDO::PARAM_STR);
            $requete->bindParam(2, $idprojet, PDO::PARAM_INT);
            $requete->bindParam(3, $idsourcefinancement, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRUPDATEPROJET . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function updateAcronyme(Acronyme $acronyme, $idprojet) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("UPDATE projet SET acronyme =? where idprojet=?");
            $acro = $acronyme->getAcronyme();
            $requete->bindParam(1, $acro, PDO::PARAM_STR);
            $requete->bindParam(2, $idprojet, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRMAIL . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

//------------------------------------------------------------------------------------------------------------
//                                       UTILISATEURMAILRESPONSABLE
//------------------------------------------------------------------------------------------------------------
    public function updateUtilisateurMailResponsable(UtilisateurMailresponsable $utilisateurmailresponsable, $idutilisateur) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("UPDATE utilisateur SET mailresponsable =? where idutilisateur=?");
            $mailresponsable = $utilisateurmailresponsable->getMailresponsable();
            $requete->bindParam(1, $mailresponsable, PDO::PARAM_STR);
            $requete->bindParam(2, $idutilisateur, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $ex) {
            echo TXT_ERRMAIL . '<br>' . $ex->getLine();
            $this->_db->rollBack();
        }
    }

//------------------------------------------------------------------------------------------------------------
//                                       UTILISATEURTYPE
//------------------------------------------------------------------------------------------------------------
    public function updateUtilisateurType(UtilisateurType $utilisateurType, $idutilisateur) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("UPDATE utilisateur SET idtypeutilisateur_typeutilisateur=? WHERE idutilisateur=?");
            $idtypeutilisateur = $utilisateurType->getIdtypeutilisateur_typeutilisateur();
            $requete->bindParam(1, $idtypeutilisateur, PDO::PARAM_INT);
            $requete->bindParam(2, $idutilisateur, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRINSERTUSER . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

//------------------------------------------------------------------------------------------------------------
//                                       UTILISATEURTYPE ADMINISTRATEUR
//------------------------------------------------------------------------------------------------------------
    public function updateUtilisateurTypeAdmin(UtilisateurTypeadmin $utilisateurTypeadmin, $idutilisateur) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("UPDATE utilisateur SET idtypeutilisateur_typeutilisateur=?,idcentrale_centrale=? WHERE idutilisateur=?");
            $idtypeutilisateur = $utilisateurTypeadmin->getIdtypeutilisateur_typeutilisateur();
            $idcentrale = $utilisateurTypeadmin->getIdcentrale_centrale();
            $requete->bindParam(1, $idtypeutilisateur, PDO::PARAM_INT);
            $requete->bindParam(2, $idcentrale, PDO::PARAM_INT);
            $requete->bindParam(3, $idutilisateur, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRINSERTUSER . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

//------------------------------------------------------------------------------------------------------------
//                                       UTILISATEURTUTELLE
//------------------------------------------------------------------------------------------------------------
    public function updateUtilisateurTutelle(UtilisateurTutelle $utilisateurTutelle, $idutilisateur) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("UPDATE utilisateur SET idtutelle_tutelle=?, idautrestutelle_autrestutelle =? where idutilisateur=?");
            $usertutelle = $utilisateurTutelle->getIdtutelle_tutelle();
            $idtutelle = $utilisateurTutelle->getIdautrestutelle_autrestutelle();
            $requete->bindParam(1, $usertutelle, PDO::PARAM_INT);
            $requete->bindParam(2, $idtutelle, PDO::PARAM_INT);
            $requete->bindParam(3, $idutilisateur, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRUPDATETUTELLE . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

//------------------------------------------------------------------------------------------------------------
//                                       UTILISATEURDISCIPLINE
//------------------------------------------------------------------------------------------------------------
    public function updateUtilisateurDiscipline(UtilisateurDiscipline $utilisateurDiscipline, $idutilisateur) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("UPDATE utilisateur SET iddiscipline_disciplinescientifique =?, idautrediscipline_autredisciplinescientifique =? where idutilisateur=?");
            $utilisateurdiscipline = $utilisateurDiscipline->getIddiscipline_disciplinescientifique();
            $iddiscipline = $utilisateurDiscipline->getIdautrediscipline_autredisciplinescientifique();
            $requete->bindParam(1, $utilisateurdiscipline, PDO::PARAM_INT);
            $requete->bindParam(2, $iddiscipline, PDO::PARAM_INT);
            $requete->bindParam(3, $idutilisateur, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRUPDATEDISCILINE . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    //------------------------------------------------------------------------------------------------------------
//                                       UTILISATEURNOMLABO
//------------------------------------------------------------------------------------------------------------
    public function updateUtilisateurNomlabo(UtilisateurNomlabo $utilisateurNomlabo, $idutilisateur) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("UPDATE utilisateur SET entrepriselaboratoire =? where idutilisateur=?");
            $nomlabo = $utilisateurNomlabo->getNomlab();
            $requete->bindParam(1, $nomlabo, PDO::PARAM_STR);
            $requete->bindParam(2, $idutilisateur, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRUPDATEUSER . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

//------------------------------------------------------------------------------------------------------------
//                                       UTILISATEUR ACADEMIQUE EXTERNE
//------------------------------------------------------------------------------------------------------------
    public function addUtilisateuracademiqueext(Utilisateuracadext $utilisateur) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('INSERT INTO utilisateur(nom, prenom,entrepriselaboratoire, adresse, codepostal, ville, datecreation,telephone, fax,nomResponsable,mailresponsable,idtypeutilisateur_typeutilisateur, idpays_pays, idlogin_loginpassword, iddiscipline_disciplinescientifique,
 idqualitedemandeuraca_qualitedemandeuraca,idtutelle_tutelle, idemployeur_nomemployeur, idautrestutelle_autrestutelle,idautrediscipline_autredisciplinescientifique, idautrenomemployeur_autrenomemployeur,
idautrecodeunite_autrecodeunite,acronymelaboratoire) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
            $nom = $utilisateur->getNom();
            $prenom = $utilisateur->getPrenom();
            $entrepriselaboratoire = $utilisateur->getEntrepriselaboratoire();
            $adresse = $utilisateur->getAdresse();
            $codepostal = $utilisateur->getCodePostal();
            $ville = $utilisateur->getVille();
            $date = $utilisateur->getDate();
            $tel = $utilisateur->getTel();
            $fax = $utilisateur->getFax();
            $nomresponsable = $utilisateur->getNomresponsable();
            $mailresponsable = $utilisateur->getMailresponsable();
            $idtypeutilisateur = $utilisateur->getIdtypeutilisateur_typeutilisateur();
            $idpays = $utilisateur->getIdpays_pays();
            $idlogin = $utilisateur->getIdlogin_loginpassword();
            $iddiscipline = $utilisateur->getIddiscipline_disciplinescientifique();
            $idqualitedemandeuraca = $utilisateur->getIdqualitedemandeuraca_qualitedemandeuraca();
            $idtutelle = $utilisateur->getIdtutelle_tutelle();
            $idemployeur = $utilisateur->getIdemployeur_nomemployeur();
            $idautrestutelle = $utilisateur->getIdautrestutelle_autrestutelle();
            $idautrediscipline = $utilisateur->getIdautrediscipline_autredisciplinescientifique();
            $idautrenomemployeur = $utilisateur->getIdautrenomemployeur_autrenomemployeur();
            $idautrecodeunite = $utilisateur->getIdautrecodeunite_autrecodeunite();
            $acronymelaboratoire = $utilisateur->getAcronymelaboratoire();
            $requete->bindParam(1, $nom, PDO::PARAM_STR);
            $requete->bindParam(2, $prenom, PDO::PARAM_STR);
            $requete->bindParam(3, $entrepriselaboratoire, PDO::PARAM_STR);
            $requete->bindParam(4, $adresse, PDO::PARAM_STR);
            $requete->bindParam(5, $codepostal, PDO::PARAM_INT);
            $requete->bindParam(6, $ville, PDO::PARAM_STR);
            $requete->bindParam(7, $date, PDO::PARAM_STR);
            $requete->bindParam(8, $tel, PDO::PARAM_STR);
            $requete->bindParam(9, $fax, PDO::PARAM_STR);
            $requete->bindParam(10, $nomresponsable, PDO::PARAM_STR);
            $requete->bindParam(11, $mailresponsable, PDO::PARAM_STR);
            $requete->bindParam(12, $idtypeutilisateur, PDO::PARAM_INT);
            $requete->bindParam(13, $idpays, PDO::PARAM_INT);
            $requete->bindParam(14, $idlogin, PDO::PARAM_INT);
            $requete->bindParam(15, $iddiscipline, PDO::PARAM_INT);
            $requete->bindParam(16, $idqualitedemandeuraca, PDO::PARAM_INT);
            $requete->bindParam(17, $idtutelle, PDO::PARAM_INT);
            $requete->bindParam(18, $idemployeur, PDO::PARAM_INT);
            $requete->bindParam(19, $idautrestutelle, PDO::PARAM_INT);
            $requete->bindParam(20, $idautrediscipline, PDO::PARAM_INT);
            $requete->bindParam(21, $idautrenomemployeur, PDO::PARAM_INT);
            $requete->bindParam(22, $idautrecodeunite, PDO::PARAM_INT);
            $requete->bindParam(23, $acronymelaboratoire, PDO::PARAM_STR);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRINSERTUSER . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

//------------------------------------------------------------------------------------------------------------
//                                       UTILISATEUR INDUSTRIEL
//------------------------------------------------------------------------------------------------------------
    public function addUtilisateurindustriel(Utilisateurindustriel $utilisateur) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('INSERT INTO utilisateur(nom, prenom,entrepriselaboratoire, adresse, codepostal, ville, datecreation,telephone, fax, nomresponsable,mailresponsable, nomentreprise, idtypeutilisateur_typeutilisateur,
idpays_pays, idlogin_loginpassword,idqualitedemandeurindust_qualitedemandeurindust) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');

            $nom = $utilisateur->getNom();
            $prenom = $utilisateur->getPrenom();
            $entrepriselaboratoire = $utilisateur->getEntrepriselaboratoire();
            $adresse = $utilisateur->getAdresse();
            $codepostal = $utilisateur->getCodePostal();
            $ville = $utilisateur->getVille();
            $date = $utilisateur->getDate();
            $tel = $utilisateur->getTel();
            $fax = $utilisateur->getFax();
            $nomresponsable = $utilisateur->getNomresponsable();
            $mailresponsable = $utilisateur->getMailresponsable();
            $nomentreprise = $utilisateur->getNomentreprise();
            $idtypeutilisateur = $utilisateur->getIdtypeutilisateur_typeutilisateur();
            $idpays = $utilisateur->getIdpays_pays();
            $idlogin = $utilisateur->getIdlogin_loginpassword();
            $idqualitedemandeurindust = $utilisateur->getIdqualitedemandeurindust_qualitedemandeurindust();
            $requete->bindParam(1, $nom, PDO::PARAM_STR);
            $requete->bindParam(2, $prenom, PDO::PARAM_STR);
            $requete->bindParam(3, $entrepriselaboratoire, PDO::PARAM_STR);
            $requete->bindParam(4, $adresse, PDO::PARAM_STR);
            $requete->bindParam(5, $codepostal, PDO::PARAM_INT);
            $requete->bindParam(6, $ville, PDO::PARAM_STR);
            $requete->bindParam(7, $date, PDO::PARAM_STR);
            $requete->bindParam(8, $tel, PDO::PARAM_STR);
            $requete->bindParam(9, $fax, PDO::PARAM_STR);
            $requete->bindParam(10, $nomresponsable, PDO::PARAM_STR);
            $requete->bindParam(11, $mailresponsable, PDO::PARAM_STR);
            $requete->bindParam(12, $nomentreprise, PDO::PARAM_STR);
            $requete->bindParam(13, $idtypeutilisateur, PDO::PARAM_INT);
            $requete->bindParam(14, $idpays, PDO::PARAM_INT);
            $requete->bindParam(15, $idlogin, PDO::PARAM_INT);
            $requete->bindParam(16, $idqualitedemandeurindust, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRINSERTUSER . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

//------------------------------------------------------------------------------------------------------------
//                                       UTILISATEUR PORTEUR PROJET
//------------------------------------------------------------------------------------------------------------

    public function addUtilisateurPorteurProjet(UtilisateurPorteurProjet $utilisateurPorteurProjet) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("insert into utilisateurporteurprojet (idprojet_projet,idutilisateur_utilisateur,dateaffectation) values(?,?,?)");
            $idprojet = $utilisateurPorteurProjet->getIdprojet();
            $idutilisateur = $utilisateurPorteurProjet->getIdutilisateur();
            $dateaffectation = $utilisateurPorteurProjet->getDateaffectation();
            $requete->bindParam(1, $idprojet, PDO::PARAM_INT);
            $requete->bindParam(2, $idutilisateur, PDO::PARAM_INT);
            $requete->bindParam(3, $dateaffectation, PDO::PARAM_STR);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRINSERTUSER . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function deleteUtilisateurPorteurProjet($idprojet, $idutilisateur) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("delete from utilisateurporteurprojet where idprojet_projet =? and idutilisateur_utilisateur=?  ");
            $requete->bindParam(1, $idprojet, PDO::PARAM_INT);
            $requete->bindParam(2, $idutilisateur, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRINSERTUSER . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

     public function deleteUtilisateurPorteur($idprojet) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("delete from utilisateurporteurprojet where idprojet_projet =?");
            $requete->bindParam(1, $idprojet, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRINSERTUSER . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }
    
    //------------------------------------------------------------------------------------------------------------
//                                       UTILISATEUR ADMINISTRATEUR
//------------------------------------------------------------------------------------------------------------

    public function addUtilisateurAdmin(UtilisateurAdmin $utilisateurAdmin) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("insert into utilisateuradministrateur (idprojet,idutilisateur,dateaffectation) values(?,?,?)");
            $idprojet = $utilisateurAdmin->getIdprojet();
            $idutilisateur = $utilisateurAdmin->getIdutilisateur();
            $dateaffectation = $utilisateurAdmin->getDateaffectation();
            $requete->bindParam(1, $idprojet, PDO::PARAM_INT);
            $requete->bindParam(2, $idutilisateur, PDO::PARAM_INT);
            $requete->bindParam(3, $dateaffectation, PDO::PARAM_STR);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRINSERTUSER . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function deleteUtilisateurAdmin($idprojet, $idutilisateur) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("delete from utilisateuradministrateur where idprojet=? and idutilisateur=?");
            $requete->bindParam(1, $idprojet, PDO::PARAM_INT);
            $requete->bindParam(2, $idutilisateur, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRINSERTUSER . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }
    public function deleteUtilisateurAdministrateur($idprojet) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("delete from utilisateuradministrateur where idprojet=?");
            $requete->bindParam(1, $idprojet, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRINSERTUSER . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }
//------------------------------------------------------------------------------------------------------------
//                                              NOMRESPONSABLE
//------------------------------------------------------------------------------------------------------------
    public function updateUtilisateurNomresponsable(UtilisateurNomresponsable $utilisateurnomresponsable, $idutilisateur) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("UPDATE utilisateur SET nomresponsable =? where idutilisateur=?");
            $nomresponsable = $utilisateurnomresponsable->getNomresponsable();
            $requete->bindParam(1, $nomresponsable, PDO::PARAM_STR);
            $requete->bindParam(2, $idutilisateur, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRINSERTUSER . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

//------------------------------------------------------------------------------------------------------------
//                                     update nom de MON COMPTE
//------------------------------------------------------------------------------------------------------------
    public function updateLoginMoncompte(LoginUtilisateur $loginUser, $idlogin) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("update loginpassword set pseudo= ? where idlogin=?");
            $login = $loginUser->getLoginUtilisateur();
            $requete->bindParam(1, $login, PDO::PARAM_STR);
            $requete->bindParam(2, $idlogin, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRINSERTUSER . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function updateMailMoncompte(MailUtilisateur $mailUser, $idlogin) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("update loginpassword set mail= ? where idlogin=?");
            $mail = $mailUser->getMailUtilisateur();
            $requete->bindParam(1, $mail, PDO::PARAM_STR);
            $requete->bindParam(2, $idlogin, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRINSERTUSER . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function updateNomMoncompte(NomUtilisateur $nomUser, $iduser) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("update utilisateur set nom= ? where idutilisateur=?");
            $nomuser = $nomUser->getNom();
            $requete->bindParam(1, $nomuser, PDO::PARAM_STR);
            $requete->bindParam(2, $iduser, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            $this->_db->rollBack();
            header('location:/' . REPERTOIRE . '/updatemoncompteErrNom/' . 'fr' . '/' . rand(0, 10000));
            exit();
        }
    }

    public function updatePrenomMoncompte(PrenomUtilisateur $prenomUser, $iduser) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("update utilisateur set prenom= ? where idutilisateur=?");
            $prenomuser = $prenomUser->getPrenom();
            $requete->bindParam(1, $prenomuser, PDO::PARAM_STR);
            $requete->bindParam(2, $iduser, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRINSERTUSER . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function updateAdresseMoncompte(AdresseUser $adresseUser, $iduser) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("update utilisateur set adresse= ? where idutilisateur=?");
            $adresseuser = $adresseUser->getAdresse();
            $requete->bindParam(1, $adresseuser, PDO::PARAM_STR);
            $requete->bindParam(2, $iduser, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRINSERTUSER . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function updateCodepostalMoncompte(CodepostalUser $cpUser, $iduser) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("update utilisateur set codepostal= ? where idutilisateur=?");
            $cpuser = $cpUser->getCodePostal();
            $requete->bindParam(1, $cpuser, PDO::PARAM_STR);
            $requete->bindParam(2, $iduser, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRINSERTUSER . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function updateVilleMoncompte(VilleUser $villeUser, $iduser) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("update utilisateur set ville= ? where idutilisateur=?");
            $villeuser = $villeUser->getVilleUser();
            $requete->bindParam(1, $villeuser, PDO::PARAM_STR);
            $requete->bindParam(2, $iduser, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRINSERTUSER . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function updatePaysMoncompte(PaysUser $paysUser, $iduser) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("update utilisateur set idpays_pays= ? where idutilisateur=?");
            $idpays = $paysUser->getIdpays_pays();
            $requete->bindParam(1, $idpays, PDO::PARAM_INT);
            $requete->bindParam(2, $iduser, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRINSERTUSER . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function updatetelephoneMoncompte(TelephoneUser $telUser, $iduser) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("update utilisateur set telephone= ? where idutilisateur=?");
            $teluser = $telUser->getTelUser();
            $requete->bindParam(1, $teluser, PDO::PARAM_STR);
            $requete->bindParam(2, $iduser, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRINSERTUSER . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function updateFaxMoncompte(FaxUser $faxUser, $iduser) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("update utilisateur set fax= ? where idutilisateur=?");
            $faxuser = $faxUser->getFaxUser();
            $requete->bindParam(1, $faxuser, PDO::PARAM_STR);
            $requete->bindParam(2, $iduser, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRINSERTUSER . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function updateVilleCentrale(Villecentrale $villeCentrale, $idcentrale) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("update centrale set villecentrale = ? where idcentrale=?");
            $villecentrale = $villeCentrale->getVilleCentrale();
            $requete->bindParam(1, $villecentrale, PDO::PARAM_STR);
            $requete->bindParam(2, $idcentrale, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRCENTRALE . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function updateEmailCentrale(Emailcentrale $emailCentrale, $idcentrale) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("update centrale set email1 = ?,email2=?,email3 = ?,email4=?,email5=? where idcentrale=?");
            $emailcentrale1 = $emailCentrale->getEmailcentrale1();
            $emailcentrale2 = $emailCentrale->getEmailcentrale2();
            $emailcentrale3 = $emailCentrale->getEmailcentrale3();
            $emailcentrale4 = $emailCentrale->getEmailcentrale4();
            $emailcentrale5 = $emailCentrale->getEmailcentrale5();
            $requete->bindParam(1, $emailcentrale1, PDO::PARAM_STR);
            $requete->bindParam(2, $emailcentrale2, PDO::PARAM_STR);
            $requete->bindParam(3, $emailcentrale3, PDO::PARAM_STR);
            $requete->bindParam(4, $emailcentrale4, PDO::PARAM_STR);
            $requete->bindParam(5, $emailcentrale5, PDO::PARAM_STR);
            $requete->bindParam(6, $idcentrale, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRCENTRALE . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function updateCodeuniteCentrale(Codeunitecentrale $codeuniteCentrale, $idcentrale) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("update centrale set codeunite = ? where idcentrale=?");
            $codeunitecentrale = $codeuniteCentrale->getCodeunite();
            $requete->bindParam(1, $codeunitecentrale, PDO::PARAM_STR);
            $requete->bindParam(2, $idcentrale, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRUPDATECENTRALE . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function updateQualiteAcademique(UtilisateurQualiteaca $qualite, $idutilisateur) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("update utilisateur  set idqualitedemandeuraca_qualitedemandeuraca = ? where idutilisateur=?");
            $idqualitedemandeuraca = $qualite->getIdqualitedemandeuraca();
            $requete->bindParam(1, $idqualitedemandeuraca, PDO::PARAM_INT);
            $requete->bindParam(2, $idutilisateur, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERR. '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }
    public function updateQualiteIndustriel(UtilisateurQualiteindust $qualite, $idutilisateur) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("update utilisateur  set idqualitedemandeurindust_qualitedemandeurindust = ? where idutilisateur=?");
            $idqualitedemandeurindust = $qualite->getIdqualitedemandeurindust();
            $requete->bindParam(1, $idqualitedemandeurindust, PDO::PARAM_INT);
            $requete->bindParam(2, $idutilisateur, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRUPDATECENTRALE . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function updateUtilisateurCodeunite(UtilisateurCodeunite $codeunite, $idutilisateur) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("update utilisateur set idcentrale_centrale = ? where idutilisateur=?");
            $idcodeunite = $codeunite->getIdcodeunite();
            $requete->bindParam(1, $idcodeunite, PDO::PARAM_INT);
            $requete->bindParam(2, $idutilisateur, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRUPDATECENTRALE . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

//------------------------------------------------------------------------------------------------------------
//                                       PROJET PHASE 0 ET 1
//------------------------------------------------------------------------------------------------------------

    public function addProjetphase1(Projetphase1 $projet) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("INSERT INTO projet(idprojet,titre,numero,confidentiel,description,dateprojet,contexte,idtypeprojet_typeprojet,acronyme,attachement)VALUES ( ?,?,?,?,?,?,?,?,?,?)");
            $idprojet = $projet->getIdprojet();
            $titre = $projet->getTitre();
            $numero = $projet->getNumero();
            $confidentiel = $projet->getConfidentiel();
            $description = $projet->getDescription();
            $dateprojet = $projet->getDateprojet();
            $contexte = $projet->getContexte();
            $idtypeprojet = $projet->getIdtypeprojet_typeprojet();
            $acronyme = $projet->getAcronyme();
            $attachement = $projet->getAttachement();
            $requete->bindParam(1, $idprojet, PDO::PARAM_INT);
            $requete->bindParam(2, $titre, PDO::PARAM_STR);
            $requete->bindParam(3, $numero, PDO::PARAM_STR);
            $requete->bindParam(4, $confidentiel, PDO::PARAM_BOOL);
            $requete->bindParam(5, $description, PDO::PARAM_STR);
            $requete->bindParam(6, $dateprojet, PDO::PARAM_STR);
            $requete->bindParam(7, $contexte, PDO::PARAM_STR);
            $requete->bindParam(8, $idtypeprojet, PDO::PARAM_INT);
            $requete->bindParam(9, $acronyme, PDO::PARAM_STR);
            $requete->bindParam(10, $attachement, PDO::PARAM_STR);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRINSERTPROJET . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function updateProjetphase1(Projetphase1 $projet, $idprojet) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("update projet set titre=?,numero=?,confidentiel=?,description=?,dateprojet=?,contexte=?,idtypeprojet_typeprojet=?,attachement=?,acronyme=? where idprojet=?");
            $titre = $projet->getTitre();
            $numero = $projet->getNumero();
            $confidentiel = $projet->getConfidentiel();
            $description = $projet->getDescription();
            $dateprojet = $projet->getDateprojet();
            $contexte = $projet->getContexte();
            $idtypeprojet = $projet->getIdtypeprojet_typeprojet();
            $acronyme = $projet->getAcronyme();
            $attachement = $projet->getAttachement();
            $requete->bindParam(1, $titre, PDO::PARAM_STR);
            $requete->bindParam(2, $numero, PDO::PARAM_STR);
            $requete->bindParam(3, $confidentiel, PDO::PARAM_BOOL);
            $requete->bindParam(4, $description, PDO::PARAM_STR);
            $requete->bindParam(5, $dateprojet, PDO::PARAM_STR);
            $requete->bindParam(6, $contexte, PDO::PARAM_STR);
            $requete->bindParam(7, $idtypeprojet, PDO::PARAM_INT);
            $requete->bindParam(8, $attachement, PDO::PARAM_STR);
            $requete->bindParam(9, $acronyme, PDO::PARAM_STR);
            $requete->bindParam(10, $idprojet, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRUPDATEPROJET . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }
//------------------------------------------------------------------------------------------------------------
//                                       PROJET PHASE 2
//------------------------------------------------------------------------------------------------------------
    public function updateProjetphase2(Projetphase2 $projet2, $idprojet) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("update projet set contactscentraleaccueil=?, idtypeprojet_typeprojet=?,nbHeure=?,
dateDebutTravaux=?,dureeprojet=?,idperiodicite_periodicite=?,centralepartenaireprojet=?,idthematique_thematique=?,idautrethematique_autrethematique=?,descriptifTechnologique=?,
attachementdesc=?,verrouidentifiee=?,nbplaque=?,nbrun=?,envoidevis=?,emailrespdevis=?,reussite=?,refinterneprojet=?,devtechnologique=?,nbeleve=?,
nomformateur=?,partenaire1=?,porteurprojet =?,dureeestime=?,periodestime=?,descriptionautrecentrale=?,etapeautrecentrale=?,centraleproximite=?,descriptioncentraleproximite=?,interneexterne=?,internationalnational=? where idprojet=?");
            $contactscentralaccueil = $projet2->getContactscentralaccueil();
            $idtypeprojet = $projet2->getIdtypeprojet_typeprojet();
            $nbheure = $projet2->getNbHeure();
            $datedebuttravaux = $projet2->getDateDebutTravaux();
            $dureeprojet = $projet2->getDureeprojet();
            $idperiodicite = $projet2->getIdperiodicite_periodicite();
            $centralepartenaireprojet = $projet2->getCentralepartenaireprojet();
            $idthematique = $projet2->getIdthematique_thematique();
            $idautrethematique = $projet2->getIdautrethematique_autrethematique();
            $descriptiftechnologique = $projet2->getDescriptifTechnologique();
            $attachementdesc = $projet2->getAttachementdesc();
            $verrouidentifiee = $projet2->getVerrouidentifiee();
            $nbplaque = $projet2->getNbplaque();
            $nbrun = $projet2->getNbrun();
            $envoidevis = $projet2->getEnvoidevis();
            $emailrespdevis = $projet2->getEmailrespdevis();
            $reussite = $projet2->getReussite();
            $refinterneprojet = $projet2->getRefinterneprojet();
            $devtechnologique = $projet2->getDevtechnologique();
            $nbeleve = $projet2->getNbeleve();
            $nomformateur = $projet2->getNomformateur();
            $partenaire1 = $projet2->getPartenaire1();
            $porteurprojet = $projet2->getPorteurprojet();
            $dureestime = $projet2->getDureestime();
            $periodestime = $projet2->getPeriodestime();
            $descriptionautrecentrale = $projet2->getDescriptionautrecentrale();
            $etapeautrecentrale = $projet2->getEtapeautrecentrale();
            $centraleproximite = $projet2->getCentraleproximite();
            $descriptioncentraleproximite = $projet2->getDescriptioncentraleproximite();            
            $interneexterne  = $projet2->getInterneExterne();
            $internationalnational = $projet2->getInternationalNational();
            $requete->bindParam(1, $contactscentralaccueil, PDO::PARAM_STR);
            $requete->bindParam(2, $idtypeprojet, PDO::PARAM_INT);
            $requete->bindParam(3, $nbheure, PDO::PARAM_INT);
            $requete->bindParam(4, $datedebuttravaux, PDO::PARAM_STR);
            $requete->bindParam(5, $dureeprojet, PDO::PARAM_INT);
            $requete->bindParam(6, $idperiodicite, PDO::PARAM_INT);
            $requete->bindParam(7, $centralepartenaireprojet, PDO::PARAM_STR);
            $requete->bindParam(8, $idthematique, PDO::PARAM_INT);
            $requete->bindParam(9, $idautrethematique, PDO::PARAM_INT);
            $requete->bindParam(10, $descriptiftechnologique, PDO::PARAM_STR);
            $requete->bindParam(11, $attachementdesc, PDO::PARAM_STR);
            $requete->bindParam(12, $verrouidentifiee, PDO::PARAM_STR);
            $requete->bindParam(13, $nbplaque, PDO::PARAM_INT);
            $requete->bindParam(14, $nbrun, PDO::PARAM_INT);
            $requete->bindParam(15, $envoidevis, PDO::PARAM_BOOL);
            $requete->bindParam(16, $emailrespdevis, PDO::PARAM_STR);
            $requete->bindParam(17, $reussite, PDO::PARAM_STR);
            $requete->bindParam(18, $refinterneprojet, PDO::PARAM_STR);
            $requete->bindParam(19, $devtechnologique, PDO::PARAM_BOOL);
            $requete->bindParam(20, $nbeleve, PDO::PARAM_INT);
            $requete->bindParam(21, $nomformateur, PDO::PARAM_STR);
            $requete->bindParam(22, $partenaire1, PDO::PARAM_STR);
            $requete->bindParam(23, $porteurprojet, PDO::PARAM_BOOL);
            $requete->bindParam(24, $dureestime, PDO::PARAM_INT);
            $requete->bindParam(25, $periodestime, PDO::PARAM_INT);
            $requete->bindParam(26, $descriptionautrecentrale, PDO::PARAM_STR);
            $requete->bindParam(27, $etapeautrecentrale, PDO::PARAM_BOOL);
            $requete->bindParam(28, $centraleproximite, PDO::PARAM_BOOL);
            $requete->bindParam(29, $descriptioncentraleproximite, PDO::PARAM_STR);
            $requete->bindParam(30, $interneexterne, PDO::PARAM_STR);
            $requete->bindParam(31, $internationalnational, PDO::PARAM_STR);
            $requete->bindParam(32, $idprojet, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRUPDATEPROJET . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function updatepartenairefromprojet(Partenairefromprojet $Partenairefromprojet, $idprojet) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("update projet set centralepartenaireprojet='',partenaire1='' where idprojet=? ");
            $requete->bindParam(1, $idprojet, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRUPDATEPROJET . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

//------------------------------------------------------------------------------------------------------------
//                                       SUPPRESSION D'UN PROJET
//------------------------------------------------------------------------------------------------------------
    public function deleteconcerneprojet($idprojet) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("delete from concerne where idprojet_projet =?");
            $requete->bindParam(1, $idprojet, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRINSERTUSER . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function deletecreerprojet($idprojet) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("delete from creer where idprojet_projet =? ");
            $requete->bindParam(1, $idprojet, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRINSERTUSER . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function deleteprojetsourcefinancement($idprojet) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("delete from projetsourcefinancement where idprojet_projet=? ");
            $requete->bindParam(1, $idprojet, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRINSERTUSER . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function deletepersonnesaccueilcentrale($idpersonneacceuilcentrale) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("delete from personneaccueilcentrale where idpersonneaccueilcentrale =?");
            $requete->bindParam(1, $idpersonneacceuilcentrale, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRINSERTUSER . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function deleteprojetpartenaire($idprojet) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("delete from projetpartenaire where idprojet_projet=?");
            $requete->bindParam(1, $idprojet, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERR . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function deletepartenaireprojets($idpartenaire) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("delete from partenaireprojet where idpartenaire=?");
            $requete->bindParam(1, $idpartenaire, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERR . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function deleteautrethematique($idprojet) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("delete from autrethematique where idautrethematique =(select idautrethematique_autrethematique  from projet where idprojet=?)");
            $requete->bindParam(1, $idprojet, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERR . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function deleteprojet($idprojet) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("delete from projet where idprojet=?");
            $requete->bindParam(1, $idprojet, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERR . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

//-------------------------------------------------------------------------------------------------------------
//                                          RECHERCHE
//-------------------------------------------------------------------------------------------------------------
    public function updateRecherche1(Tmprecherche $tmprecherche, $numero) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('update tmprecherche set porteur =? where numero=?');
            $porteur= $tmprecherche->getPorteur();
            $requete->bindParam(1, $porteur, PDO::PARAM_STR);
            $requete->bindParam(2, $numero, PDO::PARAM_STR);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERR . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

//-------------------------------------------------------------------------------------------------------------
//                                          PROJETCENTRALE
//-------------------------------------------------------------------------------------------------------------
    public function updateProjetcentrale(Tmprecherche $tmprecherche, $numero) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('update tmptous set porteur =? where numero=?');
            $porteur = $tmprecherche->getPorteur();
            $requete->bindParam(1, $porteur, PDO::PARAM_STR);
            $requete->bindParam(2, $numero, PDO::PARAM_STR);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERR . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function updateProjetTouscentrale(Tmprecherche $tmprecherche, $numero) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('update tmpcentraletous set porteur =? where numero=?');
            $porteur = $tmprecherche->getPorteur();
            $requete->bindParam(1, $porteur, PDO::PARAM_STR);
            $requete->bindParam(2, $numero, PDO::PARAM_STR);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERR . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

//------------------------------------------------------------------------------------------------------------
//                                       CENTRALE
//------------------------------------------------------------------------------------------------------------

    public function addCentrale(CentraleName $centrale) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("INSERT INTO centrale (idcentrale,libellecentrale,masquecentrale) VALUES (?,?,?)");
            $idcentrale =$centrale->getIdcentrale();
            $libellecentrale = $centrale->getLibellecentrale();
            $masquecentrale = $centrale->getMasquecentrale();
            $requete->bindParam(1, $idcentrale, PDO::PARAM_INT);
            $requete->bindParam(2, $libellecentrale, PDO::PARAM_STR);
            $requete->bindParam(3, $masquecentrale, PDO::PARAM_BOOL);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRCENTRALE . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function updateNomCentrale(CentraleName $centrale, $idcentrale) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("update centrale set libellecentrale=?,masquecentrale=? where idcentrale=?");
            $libellecentrale = $centrale->getLibellecentrale();
            $masquecentrale = $centrale->getMasquecentrale();
            $requete->bindParam(1, $libellecentrale, PDO::PARAM_STR);
            $requete->bindParam(2, $masquecentrale, PDO::PARAM_BOOL);
            $requete->bindParam(3, $idcentrale, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRUPDATECENTRALE . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function afficheHideCentrale(CentraleName $centrale, $idcentrale) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("UPDATE centrale SET masquecentrale =? WHERE idcentrale=?");
            $masquecentrale = $centrale->getMasquecentrale();
            $requete->bindParam(1, $masquecentrale, PDO::PARAM_BOOL);
            $requete->bindParam(2, $idcentrale, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRUPDATETHEMATIQUE . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

//------------------------------------------------------------------------------------------------------------
//                                       SOURCE DE FINANCEMENT
//------------------------------------------------------------------------------------------------------------

    public function addSourcefinancement(Sourcefinancement $source) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("INSERT INTO sourcefinancement(idsourcefinancement,libellesourcefinancement,masquesourcefinancement,libellesourcefinancementen)VALUES (?,?,?,?)");
            $idsourcefinancement = $source->getIdsourcefinancement();
            $libellesourcefinancement = $source->getLibellesourcefinancement();
            $masquesource = $source->getMasquesourcefinancement();
            $libellesourcefinancementen = $source->getLibellesourcefinancementen();
            $requete->bindParam(1, $idsourcefinancement, PDO::PARAM_INT);
            $requete->bindParam(2, $libellesourcefinancement, PDO::PARAM_STR);
            $requete->bindParam(3, $masquesource ,PDO::PARAM_BOOL);
            $requete->bindParam(4, $libellesourcefinancementen, PDO::PARAM_STR);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRSOURCEFINANCEMENT . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function updateSourcefinancement(Sourcefinancement $sourcefinancement, $idsourcefinancement) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("update sourcefinancement set libellesourcefinancement=?,masquesourcefinancement=?,libellesourcefinancementen=? where idsourcefinancement=?");
            $libellesourcefinancement = $sourcefinancement->getLibellesourcefinancement();
            $masquesourcefinancement = $sourcefinancement->getMasquesourcefinancement();
            $libellesourcefinancementen = $sourcefinancement->getLibellesourcefinancementen();
            $requete->bindParam(1, $libellesourcefinancement, PDO::PARAM_STR);
            $requete->bindParam(2, $masquesourcefinancement, PDO::PARAM_BOOL);
            $requete->bindParam(3, $libellesourcefinancementen, PDO::PARAM_STR);
            $requete->bindParam(4, $idsourcefinancement, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRUPDATESOURCEFINANCEMENT . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function afficheHidesourcefinancement(Sourcefinancement $sourcefinancement, $idsourcefinancement) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("UPDATE sourcefinancement SET masquesourcefinancement =? WHERE idsourcefinancement=?");
            $masquesourcefinancement =$sourcefinancement->getMasquesourcefinancement();
            $requete->bindParam(1, $masquesourcefinancement, PDO::PARAM_BOOL);
            $requete->bindParam(2, $idsourcefinancement, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRUPDATETHEMATIQUE . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function deletesourcefinancementprojet($idprojet) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('DELETE FROM projetsourcefinancement WHERE idprojet_projet =?');
            $requete->bindParam(1, $idprojet, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRDELETEESSOURCEPROJET . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

//------------------------------------------------------------------------------------------------------------
//                                       THEMATIQUE
//------------------------------------------------------------------------------------------------------------

    public function addthematique(Thematique $thematique) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("INSERT INTO thematique(idthematique,libellethematique,masquethematique,libellethematiqueen)VALUES (?,?,?,?)");
            $idthematique = $thematique->getIdthematique();
            $libellethematique =$thematique->getLibellethematique();
            $masquethematique = $thematique->getMasquethematique();
            $libellethematiqueen =$thematique->getLibellethematiqueen();
            $requete->bindParam(1, $idthematique, PDO::PARAM_INT);
            $requete->bindParam(2, $libellethematique, PDO::PARAM_STR);
            $requete->bindParam(3, $masquethematique, PDO::PARAM_BOOL);
            $requete->bindParam(4, $libellethematiqueen, PDO::PARAM_STR);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRTHEMATIQUE . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function updatethematique(Thematique $thematique, $idthematique) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("UPDATE thematique SET libellethematique=?,masquethematique=?,libellethematiqueen=? WHERE idthematique=?");
            $libellethematique =$thematique->getLibellethematique();
            $masquethematique = $thematique->getMasquethematique();
            $libellethematiqueen =$thematique->getLibellethematiqueen();
            $requete->bindParam(1, $libellethematique, PDO::PARAM_STR);
            $requete->bindParam(2, $masquethematique, PDO::PARAM_BOOL);
            $requete->bindParam(3, $libellethematiqueen, PDO::PARAM_STR);
            $requete->bindParam(4, $idthematique, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRUPDATETHEMATIQUE . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function afficheHideThematique(Thematique $thematique, $idthematique) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("UPDATE thematique SET masquethematique=? WHERE idthematique=?");
            $masquethematique = $thematique->getMasquethematique();
            $requete->bindParam(1, $masquethematique, PDO::PARAM_BOOL);
            $requete->bindParam(2, $idthematique, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRUPDATETHEMATIQUE . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

//------------------------------------------------------------------------------------------------------------
//                                     AUTRE  THEMATIQUE
//------------------------------------------------------------------------------------------------------------

    public function addautrethematique(Autrethematique $autrethematique) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("INSERT INTO autrethematique(idautrethematique,libelleautrethematique)VALUES (?,?)");
            $idautrethematique = $autrethematique->getIdautrethematique();
            $libelleautrethematique = $autrethematique->getLibelleautrethematique();
            $requete->bindParam(1, $idautrethematique, PDO::PARAM_INT);
            $requete->bindParam(2, $libelleautrethematique, PDO::PARAM_STR);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRUPDATETHEMATIQUE . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

//------------------------------------------------------------------------------------------------------------
//                                       PAYS
//------------------------------------------------------------------------------------------------------------

    public function addPays(Pays $pays) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('INSERT INTO pays (idpays,nompays,nompaysen,idsituation_situationgeographique,masquepays)VALUES(?,?,?,?,?)');
            $idpays = $pays->getIdpays();
            $nomPays = $pays->getNompays();
            $nomPaysen = $pays->getNompaysen();
            $idsituation_situationgeographique = $pays->getIdsituation_situationgeographique();
            $masquepays = $pays->getMasquepays();
            
            $requete->bindParam(1, $idpays(), PDO::PARAM_INT);
            $requete->bindParam(2, $nomPays, PDO::PARAM_STR);
            $requete->bindParam(3, $nomPaysen, PDO::PARAM_STR);
            $requete->bindParam(4, $idsituation_situationgeographique, PDO::PARAM_INT);
            $requete->bindParam(5, $masquepays, PDO::PARAM_BOOL);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRINSERTPAYS . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function updatePays(Pays $pays, $idpays) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("UPDATE pays  SET nompays=?,nompaysen=?,idsituation_situationgeographique=? WHERE idpays=?");
            $nompays = $pays->getNompays();
            $nompaysen = $pays->getNompaysen();
            $idsituation_situationgeographique = $pays->getIdsituation_situationgeographique();            
            $requete->bindParam(1, $nompays, PDO::PARAM_STR);
            $requete->bindParam(2, $nompaysen, PDO::PARAM_STR);
            $requete->bindParam(3, $idsituation_situationgeographique, PDO::PARAM_INT);
            $requete->bindParam(4, $idpays, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRUPDATEPAYS . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function afficheHidePays(Pays $pays, $idpays) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("UPDATE pays SET masquepays=? WHERE idpays=?");
            $masquepays = $pays->getMasquepays();
            $requete->bindParam(1, $masquepays, PDO::PARAM_BOOL);
            $requete->bindParam(2, $idpays, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRUPDATEPAYS . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

//------------------------------------------------------------------------------------------------------------
//                                       PROJETPARTENAIRE
//------------------------------------------------------------------------------------------------------------

    public function addprojetpartenaire(Projetpartenaire $projetpartenaire) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('INSERT INTO projetpartenaire (idpartenaire_partenaireprojet,idprojet_projet)VALUES(?,?)');
            $idpartenaire_partenaireprojet = $projetpartenaire->getIdpartenaire_partenaireprojet();
            $idprojet_projet = $projetpartenaire->getIdprojet_projet();
            $requete->bindParam(1, $idpartenaire_partenaireprojet, PDO::PARAM_INT);
            $requete->bindParam(2, $idprojet_projet, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRINSERTPROJETPARTENAIRE . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function deletepersonneaccueilcentrale($idpersonneaccueilcentrale) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('delete from personneaccueilcentrale where idpersonneaccueilcentrale=?');
            $requete->bindParam(1, $idpersonneaccueilcentrale, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRDELETEPROJETPERSONNEACCUEILCENTRALE . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

//------------------------------------------------------------------------------------------------------------
//                                       PARTENAIREPROJET
//------------------------------------------------------------------------------------------------------------

    public function addpartenaireprojet(Partenaireprojet $partenaireprojet) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('INSERT INTO partenaireprojet (idpartenaire,nompartenaire,nomlaboentreprise)VALUES(?,?,?) ');
            $idpartenaire = $partenaireprojet->getIdpartenaire();
            $nompartenaire = $partenaireprojet->getNompartenaire();
            $nomlaboentreprise =$partenaireprojet->getNomlaboentreprise();
            $requete->bindParam(1, $idpartenaire, PDO::PARAM_INT);
            $requete->bindParam(2, $nompartenaire, PDO::PARAM_STR);
            $requete->bindParam(3, $nomlaboentreprise, PDO::PARAM_STR);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRINSERTPARTENAIREPROJET . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

//ok

    public function deletepartenaireprojet($idpartenaireprojet) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('delete from partenaireprojet where idpartenaire=?');
            $requete->bindParam(1, $idpartenaireprojet, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRDELTEPARTENAIREPROJET . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

//------------------------------------------------------------------------------------------------------------
//                                       TYPE ENTREPRISE
//------------------------------------------------------------------------------------------------------------

    public function addTypeentreprise(Typeentreprise $typeentreprise) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('INSERT INTO Typeentreprise (idtypeentreprise,libelletypeentreprise,masquetypeentreprise,libelletypeentrepriseen)VALUES(?,?,?,?) ');
            $idtypeentreprise = $typeentreprise->getIdtypeentreprise();
            $libelletypeentreprise = $typeentreprise->getLibelletypeentreprise();
            $masquetypeentreprise = $typeentreprise->getMasquetypeentreprise();
            $libelletypeentrepriseen = $typeentreprise->getLibelletypeentrepriseen();
            $requete->bindParam(1, $idtypeentreprise, PDO::PARAM_INT);
            $requete->bindParam(2, $libelletypeentreprise, PDO::PARAM_STR);
            $requete->bindParam(3, $masquetypeentreprise, PDO::PARAM_BOOL);
            $requete->bindParam(4, $libelletypeentrepriseen, PDO::PARAM_STR);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRINSERTYPEENTREPRISE . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function updateTypeentreprise(Typeentreprise $typeentreprise, $idtypeentreprise) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("UPDATE typeentreprise SET libelletypeentreprise=?,masquetypeentreprise=?,libelletypeentrepriseen=? WHERE idtypeentreprise=?");
            $libelletypeentreprise = $typeentreprise->getLibelletypeentreprise();
            $masquetypeentreprise = $typeentreprise->getMasquetypeentreprise();
            $libelletypeentrepriseen = $typeentreprise->getLibelletypeentrepriseen();            
            $requete->bindParam(1, $libelletypeentreprise, PDO::PARAM_STR);
            $requete->bindParam(2, $masquetypeentreprise, PDO::PARAM_BOOL);
            $requete->bindParam(3, $libelletypeentrepriseen, PDO::PARAM_STR);
            $requete->bindParam(4, $idtypeentreprise, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRINSERTYPEENTREPRISE . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function afficheHideTypeentreprise(Typeentreprise $typeentreprise, $idtypeentreprise) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("UPDATE typeentreprise SET masquetypeentreprise=? WHERE idtypeentreprise=?");
            $masquetypeentreprise = $typeentreprise->getMasquetypeentreprise();
            $requete->bindParam(1, $masquetypeentreprise, PDO::PARAM_BOOL);
            $requete->bindParam(2, $idtypeentreprise, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRINSERTYPEENTREPRISE . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

//------------------------------------------------------------------------------------------------------------
//                                       TYPE PROJET
//------------------------------------------------------------------------------------------------------------

    public function addTypeprojet(Typeprojet $typeprojet) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('INSERT INTO typeprojet (idtypeprojet,libelletype,masquetypeprojet,libelletypeen)VALUES(?,?,?,?) ');
            $idtypeprojet = $typeprojet->getIdtypeprojet();
            $libelletypeprojet = $typeprojet->getLibelletypeprojet();
            $masquetypeprojet = $typeprojet->getMasquetypeprojet();
            $libelletypeprojeten = $typeprojet->getLibelletypeprojeten();
            $requete->bindParam(1, $idtypeprojet, PDO::PARAM_INT);
            $requete->bindParam(2, $libelletypeprojet, PDO::PARAM_STR);
            $requete->bindParam(3, $masquetypeprojet, PDO::PARAM_BOOL);
            $requete->bindParam(4, $libelletypeprojeten, PDO::PARAM_STR);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRINSERTYPEPROJET . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function updateTypeprojet(Typeprojet $typeprojet, $idtypeprojet) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("UPDATE typeprojet  SET libelletype=?,masquetypeprojet=?,libelletypeen=? WHERE idtypeprojet=?");
            $libelletypeprojet = $typeprojet->getLibelletypeprojet();
            $masquetypeprojet = $typeprojet->getMasquetypeprojet();
            $libelletypeprojeten = $typeprojet->getLibelletypeprojeten();
            $requete->bindParam(1, $libelletypeprojet, PDO::PARAM_STR);
            $requete->bindParam(2, $masquetypeprojet, PDO::PARAM_BOOL);
            $requete->bindParam(3, $libelletypeprojeten, PDO::PARAM_STR);
            $requete->bindParam(4, $idtypeprojet, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRUPDATETYPEPROJET . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function afficheHideTypeprojet(Typeprojet $typeprojet, $idtypeprojet) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("UPDATE typeprojet SET masquetypeprojet=? WHERE idtypeprojet=?");
            $masquetypeprojet = $typeprojet->getMasquetypeprojet();
            $requete->bindParam(1, $masquetypeprojet, PDO::PARAM_BOOL);
            $requete->bindParam(2, $idtypeprojet, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRUPDATETYPEPROJET . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

//------------------------------------------------------------------------------------------------------------
//                                       RESSOURCE PROJET
//------------------------------------------------------------------------------------------------------------

    public function addressourceprojet(Ressourceprojet $ressource) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('INSERT INTO ressourceprojet(idprojet_projet, idressource_ressource)VALUES (?,?)');
            $idprojet_projet = $ressource->getIdprojet_projet();
            $idressource_ressource = $ressource->getIdressource_ressource();
            $requete->bindParam(1, $idprojet_projet, PDO::PARAM_INT);
            $requete->bindParam(2, $idressource_ressource, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRESSOURCEPROJET . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function deleteressourceprojet($idprojet) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('DELETE FROM ressourceprojet WHERE idprojet_projet =?');
            $requete->bindParam(1, $idprojet, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRDELETEESSOURCEPROJET . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

//------------------------------------------------------------------------------------------------------------
//                                       DISCIPLINE SCIENTIFIQUE
//------------------------------------------------------------------------------------------------------------

    public function addDiscipline(Disciplinescientifique $discipline) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('INSERT INTO disciplinescientifique (iddiscipline,libellediscipline,masquediscipline,libelledisciplineen)VALUES(?,?,?,?) ');
            $iddiscipline = $discipline->getIddiscipline();
            $libellediscipline =$discipline->getLibellediscipline();
            $masquediscipline = $discipline->getMasquediscipline();
            $libelledisciplineen =$discipline->getLibelledisciplineen();
            $requete->bindParam(1, $iddiscipline, PDO::PARAM_INT);
            $requete->bindParam(2, $libellediscipline, PDO::PARAM_STR);
            $requete->bindParam(3, $masquediscipline, PDO::PARAM_BOOL);
            $requete->bindParam(4, $libelledisciplineen, PDO::PARAM_STR);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRDISCILINE . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function updateDiscipline(Disciplinescientifique $discipline, $iddiscipline) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("UPDATE disciplinescientifique  SET libellediscipline=?,masquediscipline=?,libelledisciplineen=? WHERE iddiscipline=?");
            $libellediscipline =$discipline->getLibellediscipline();
            $masquediscipline = $discipline->getMasquediscipline();
            $libelledisciplineen =$discipline->getLibelledisciplineen();
            $requete->bindParam(1, $libellediscipline, PDO::PARAM_STR);
            $requete->bindParam(2, $masquediscipline, PDO::PARAM_BOOL);
            $requete->bindParam(3, $libelledisciplineen, PDO::PARAM_STR);
            $requete->bindParam(4, $iddiscipline, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRUPDATEDISCILINE . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function afficheHideDiscipline(Disciplinescientifique $discipline, $iddiscipline) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("UPDATE disciplinescientifique SET masquediscipline=? WHERE iddiscipline=?");
            $masquediscipline = $discipline->getMasquediscipline();
            $requete->bindParam(1, $masquediscipline, PDO::PARAM_BOOL);
            $requete->bindParam(2, $iddiscipline, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRUPDATEDISCILINE . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

//------------------------------------------------------------------------------------------------------------
//                                       SECTEUR D'ACTIVITE
//------------------------------------------------------------------------------------------------------------

    public function addSecteuractivite(Secteuractivite $secteuractivite) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('INSERT INTO secteuractivite (idsecteuractivite,libellesecteuractivite,libellesecteuractiviteen,masquesecteuractivite)VALUES(?,?,?,?) ');
            $idsecteuractivite =$secteuractivite->getIdsecteuractivite();
            $libellesecteuractivite =$secteuractivite->getLibellesecteuractivite();
            $libellesecteuractiviteen = $secteuractivite->getLibellesecteuractiviteen();
            $masquesecteuractivite =$secteuractivite->getMasquesecteuractivite();
            $requete->bindParam(1, $idsecteuractivite, PDO::PARAM_INT);
            $requete->bindParam(2, $libellesecteuractivite, PDO::PARAM_STR);
            $requete->bindParam(3, $libellesecteuractiviteen, PDO::PARAM_STR);
            $requete->bindParam(4, $masquesecteuractivite, PDO::PARAM_BOOL);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRSECTEURACTIVITE . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function updateSecteuractivite(Secteuractivite $secteuractivite, $idsecteuractivite) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("UPDATE secteuractivite  SET libellesecteuractivite=?,libellesecteuractiviteen=?,masquesecteuractivite=?  WHERE idsecteuractivite=?");
            $libellesecteuractivite =$secteuractivite->getLibellesecteuractivite();
            $libellesecteuractiviteen = $secteuractivite->getLibellesecteuractiviteen();
            $masquesecteuractivite =$secteuractivite->getMasquesecteuractivite();
            $requete->bindParam(1, $libellesecteuractivite, PDO::PARAM_STR);
            $requete->bindParam(2, $libellesecteuractiviteen, PDO::PARAM_STR);
            $requete->bindParam(3, $masquesecteuractivite, PDO::PARAM_BOOL);
            $requete->bindParam(4, $idsecteuractivite, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRUPDATESECTEURACTIVITE . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function afficheHideSecteurActivite(Secteuractivite $secteuractivite, $idsecteuractivite) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("UPDATE secteuractivite SET masquesecteuractivite=? WHERE idsecteuractivite=?");
            $masquesecteuractivite =$secteuractivite->getMasquesecteuractivite();
            $requete->bindParam(1, $masquesecteuractivite, PDO::PARAM_BOOL);
            $requete->bindParam(2, $idsecteuractivite, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRUPDATESECTEURACTIVITE . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

//------------------------------------------------------------------------------------------------------------
//                                       UTILISATEUR NOM EMPLOYEUR
//------------------------------------------------------------------------------------------------------------
    public function updateUtilisateurNomemployeur(UtilisateurNomemployeur $utilisateurNomemployeur, $idutilisateur) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("update utilisateur SET idemployeur_nomemployeur = ?, idautrenomemployeur_autrenomemployeur =? where idutilisateur=?");
            $idemployeur = $utilisateurNomemployeur->getIdemployeur_nomemployeur();
            $idautreemployeur = $utilisateurNomemployeur->getIdautrenomemployeur_autrenomemployeur();
            $requete->bindParam(1, $idemployeur, PDO::PARAM_INT);
            $requete->bindParam(2, $idautreemployeur, PDO::PARAM_INT);
            $requete->bindParam(3, $idutilisateur, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRUPDATENOMEMPLOYEUR . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

//------------------------------------------------------------------------------------------------------------
//                                       NOM EMPLOYEUR
//------------------------------------------------------------------------------------------------------------

    public function addNomemployeur(Nomemployeur $nomemployeur) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('INSERT INTO nomemployeur (idemployeur,libelleemployeur,masquenomemployeur,libelleemployeuren)VALUES(?,?,?,?) ');
            $idemployeur  =$nomemployeur->getIdemployeur();
            $libelleemployeur = $nomemployeur->getLibelleemployeur();
            $masquenomemployeur = $nomemployeur->getMasquenomemployeur();
            $libelleemployeuren = $nomemployeur->getLibelleemployeuren();
            $requete->bindParam(1, $idemployeur, PDO::PARAM_INT);
            $requete->bindParam(2, $libelleemployeur, PDO::PARAM_STR);
            $requete->bindParam(3, $masquenomemployeur, PDO::PARAM_BOOL);
            $requete->bindParam(4, $libelleemployeuren, PDO::PARAM_STR);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRNOMEMPLOYEUR . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function updateNomemployeur(Nomemployeur $nomemployeur, $idemployeur) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("UPDATE nomemployeur SET libelleemployeur=?,masquenomemployeur=?,libelleemployeuren=? WHERE idemployeur=?");
            $libelleemployeur = $nomemployeur->getLibelleemployeur();
            $masquenomemployeur = $nomemployeur->getMasquenomemployeur();
            $libelleemployeuren = $nomemployeur->getLibelleemployeuren();
            $requete->bindParam(1, $libelleemployeur, PDO::PARAM_STR);
            $requete->bindParam(2, $masquenomemployeur, PDO::PARAM_BOOL);
            $requete->bindParam(3, $libelleemployeuren, PDO::PARAM_STR);
            $requete->bindParam(4, $idemployeur, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRUPDATENOMEMPLOYEUR . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function updateAutrenomemployeur(Autrenomemployeur $autrenomeployeur, $idautrenomemployeur) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("update autrenomemployeur set libelleautrenomemployeur=? where idautrenomemployeur=?");
            $libelleautrenomemployeur = $autrenomeployeur->getLibelleautrenomemployeur();
            $requete->bindParam(1, $autrenomeployeur->getLibelleautrenomemployeur(), PDO::PARAM_STR);
            $requete->bindParam(2, $idautrenomemployeur, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRUPDATENOMEMPLOYEUR . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

//------------------------------------------------------------------------------------------------------------
//                                       PROJET ATTACHEMENT
//------------------------------------------------------------------------------------------------------------
    public function updateProjetattachement(Projetattachement $projetattachement, $idprojet) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("update projet set attachement =? where idprojet=?");
            $attachement = $projetattachement->getAttachement();
            $requete->bindParam(1, $attachement, PDO::PARAM_STR);
            $requete->bindParam(2, $idprojet, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRUPDATESTATUTPROJET . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function updateProjetattachementdesc(Projetattachementdesc $projetattachementdesc, $idprojet) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("update projet set attachementdesc =? where idprojet=?");
            $attachementdesc = $projetattachementdesc->getattachementdesc();
            $requete->bindParam(1, $attachementdesc, PDO::PARAM_STR);
            $requete->bindParam(2, $idprojet, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRUPDATESTATUTPROJET . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function updateprojetcontextedescriptif(Projetcontextedescriptif $projetcontextedescriptif, $idprojet) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("update projet set description =?, contexte=?, confidentiel=?,titre=?, acronyme=? ,attachement =? where idprojet=?");
            $description = $projetcontextedescriptif->getDescription();
            $contexte  = $projetcontextedescriptif->getContexte();
            $confidentiel = $projetcontextedescriptif->getConfidentiel();
            $titre = $projetcontextedescriptif->getTitre();
            $acronyme = $projetcontextedescriptif->getAcronyme();
            $attachement = $projetcontextedescriptif->getAttachement();
            $requete->bindParam(1, $description , PDO::PARAM_STR);
            $requete->bindParam(2, $contexte, PDO::PARAM_STR);
            $requete->bindParam(3, $confidentiel, PDO::PARAM_BOOL);
            $requete->bindParam(4, $titre , PDO::PARAM_STR);
            $requete->bindParam(5, $acronyme, PDO::PARAM_STR);
            $requete->bindParam(6, $attachement, PDO::PARAM_STR);
            $requete->bindParam(7, $idprojet, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRUPDATESTATUTPROJET . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

//------------------------------------------------------------------------------------------------------------
//                                       TUTELLE
//------------------------------------------------------------------------------------------------------------

    public function addtutelle(Tutelle $tutelle) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('INSERT INTO tutelle (idtutelle,libelletutelle,masquetutelle,libelletutelleen )VALUES(?,?,?,?) ');
            $idtutelle = $tutelle->getIdtutelle();
            $libelletutelle =$tutelle->getLibelletutelle();
            $masquetutelle = $tutelle->getMasquetutelle();
            $libelletutelleen = $tutelle->getLibelletutelleen();
            $requete->bindParam(1, $idtutelle, PDO::PARAM_INT);
            $requete->bindParam(2, $libelletutelle , PDO::PARAM_STR);
            $requete->bindParam(3, $masquetutelle, PDO::PARAM_BOOL);
            $requete->bindParam(4, $libelletutelleen, PDO::PARAM_STR);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRTUTELLE . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function updateTutelle(Tutelle $tutelle, $idtutelle) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("update tutelle set libelletutelle =?,masquetutelle=?,libelletutelleen =? where idtutelle=?");
            $libelletutelle =$tutelle->getLibelletutelle();
            $masquetutelle = $tutelle->getMasquetutelle();
            $libelletutelleen = $tutelle->getLibelletutelleen();
            $requete->bindParam(1, $libelletutelle, PDO::PARAM_STR);
            $requete->bindParam(2, $masquetutelle, PDO::PARAM_BOOL);
            $requete->bindParam(3, $libelletutelleen, PDO::PARAM_STR);
            $requete->bindParam(4, $idtutelle, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRUPDATETUTELLE . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function afficheHidetutelle(Tutelle $tutelle, $idtutelle) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("UPDATE tutelle SET masquetutelle=? WHERE idtutelle=?");
            $masquetutelle = $tutelle->getMasquetutelle();
            $requete->bindParam(1, $masquetutelle, PDO::PARAM_BOOL);
            $requete->bindParam(2, $idtutelle, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRUPDATESECTEURACTIVITE . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

//------------------------------------------------------------------------------------------------------------
//                                       AUTRETUTELLE
//------------------------------------------------------------------------------------------------------------

    public function addAutrestutelle(Autrestutelle $autrestutelle) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('INSERT INTO autrestutelle (idautrestutelle,libelleautrestutelle)VALUES(?,?) ');
            $idautretutelle = $autrestutelle->getIdautrestutelle();
            $libelleautrestutelle = $autrestutelle->getLibelleautrestutelle();
            $requete->bindParam(1, $idautretutelle, PDO::PARAM_INT);
            $requete->bindParam(2, $libelleautrestutelle, PDO::PARAM_STR);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRAUTRETUTELLE . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function updateAutreTutelle(Autrestutelle $autrestutelle, $idautretutelle) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('update autretutelle set libelleautrestutelle=? where idautrestutelle=?');
            $libelleautrestutelle =$autrestutelle->getLibelleautrestutelle();
            $requete->bindParam(1, $libelleautrestutelle, PDO::PARAM_STR);
            $requete->bindParam(2, $idautretutelle, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRAUTRETUTELLE . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

//------------------------------------------------------------------------------------------------------------
//                                       AUTRENOMEMPLOYEUR
//------------------------------------------------------------------------------------------------------------

    public function addAutrenomemployeur(Autrenomemployeur $autrenomemployeur) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('INSERT INTO autrenomemployeur (idautrenomemployeur,libelleautrenomemployeur)VALUES(?,?) ');
            $idautrenomemployeur =$autrenomemployeur->getIdautrenomemployeur();
            $libelleautrenomemployeur =$autrenomemployeur->getLibelleautrenomemployeur();
            $requete->bindParam(1, $idautrenomemployeur, PDO::PARAM_INT);
            $requete->bindParam(2, $libelleautrenomemployeur, PDO::PARAM_STR);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRAUTRENOMEMPLOYEUR . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function afficheHidenomemployeur(Nomemployeur $nomemployeur, $idnomemployeur) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("UPDATE nomemployeur SET masquenomemployeur=? WHERE idemployeur= ?");
            $masquenomemployeur = $nomemployeur->getMasquenomemployeur();
            $requete->bindParam(1, $masquenomemployeur, PDO::PARAM_BOOL);
            $requete->bindParam(2, $idnomemployeur, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRAUTRENOMEMPLOYEUR . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

//------------------------------------------------------------------------------------------------------------
//                                       AUTRECODEUNITE
//------------------------------------------------------------------------------------------------------------

    public function addAutrecodeunite(Autrecodeunite $autrecodeunite) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('INSERT INTO autrecodeunite (idautrecodeunite,libelleautrecodeunite)VALUES(?,?) ');
            $idautrecodeunite= $autrecodeunite->getIdautrecodeunite();
            $libelleautrecodeunite =$autrecodeunite->getLibelleautrecodeunite() ;
            $requete->bindParam(1, $idautrecodeunite, PDO::PARAM_INT);
            $requete->bindParam(2, $libelleautrecodeunite, PDO::PARAM_STR);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRAUTRECODEUNITE . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function updateAutrecodeunite(UtilisateurAutreCodeunite $autrecodeunite, $idutilisateur) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('update utilisateur set idautrecodeunite_autrecodeunite = ? where idutilisateur=?');
            $idautrecodunite = $autrecodeunite->getIdautrecodunite();
            $requete->bindParam(1, $idautrecodunite, PDO::PARAM_INT);
            $requete->bindParam(2, $idutilisateur, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRAUTRECODEUNITE . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }
    
    public function updateAutreCU(AutreCodeunite $autrecodeunite, $idautrecodeunite) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('update autrecodeunite set libelleautrecodeunite = ? where idautrecodeunite=?');
            $libelleautrecodunite = $autrecodeunite->getLibelleautrecodeunite();
            $requete->bindParam(1, $libelleautrecodunite, PDO::PARAM_STR);
            $requete->bindParam(2, $idautrecodeunite, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRAUTRECODEUNITE . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function updateUtilisateurNomEntreprise(UtilisateurNomEntreprise $utilisateur, $idutilisateur) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("UPDATE utilisateur SET nomentreprise =? where idutilisateur=?");
            $nomentreprise = $utilisateur->getNomentreprise();
            $requete->bindParam(1, $nomentreprise, PDO::PARAM_STR);
            $requete->bindParam(2, $idutilisateur, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $ex) {
            echo TXT_ERRMAIL . '<br>' . $ex->getLine();
            $this->_db->rollBack();
        }
    }
    
    
//------------------------------------------------------------------------------------------------------------
//                                       DISCIPLINE SCIENTIFIQUE
//------------------------------------------------------------------------------------------------------------

    public function addAutrediscipline(Autredisciplinescientifique $autredisciplinescientifique) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('INSERT INTO autredisciplinescientifique (idautrediscipline,libelleautrediscipline)VALUES(?,?) ');
            $idautrediscipline = $autredisciplinescientifique->getIdautrediscipline();
            $libelleautrediscipline = $autredisciplinescientifique->getLibelleautrediscipline();
            $requete->bindParam(1, $idautrediscipline, PDO::PARAM_INT);
            $requete->bindParam(2, $libelleautrediscipline, PDO::PARAM_STR);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRAUTREDISCIPLINE . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function updateAutreDiscipline(Autredisciplinescientifique $autredisciplinescientifique, $idautrediscipline) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('UPDATE autredisciplinescientifique set libelleautrediscipline =? where idautrediscipline=?');
            $libelleautrediscipline = $autredisciplinescientifique->getLibelleautrediscipline();
            $requete->bindParam(1, $libelleautrediscipline, PDO::PARAM_STR);
            $requete->bindParam(2, $idautrediscipline, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRAUTREDISCIPLINE . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

//------------------------------------------------------------------------------------------------------------
//                                       ACRONYME DU LABORATOIRE
//------------------------------------------------------------------------------------------------------------
    public function updateAcronymelaboratoire(UtilisateurAcronymelabo $utilisateurAcronyme, $idutilisateur) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("UPDATE utilisateur SET acronymelaboratoire=? where idutilisateur=?");
            $useracronyme = $utilisateurAcronyme->getAcronymelaboratoire();
            $requete->bindParam(1, $useracronyme, PDO::PARAM_STR);
            $requete->bindParam(2, $idutilisateur, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRUPDATEUSER . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

//------------------------------------------------------------------------------------------------------------
//                      METHODE APPARTIENT
//------------------------------------------------------------------------------------------------------------
    public function addAppartient(Appartient $appartient) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('INSERT INTO appartient (idtypeentreprise_typeentreprise,idutilisateur_utilisateur)VALUES(?,?) ');
            $idtypeentreprise = $appartient->getIdtypeentreprise_typeentreprise();
            $idutilisateur = $appartient->getIdutilisateur_utilisateur();
            $requete->bindParam(1, $idtypeentreprise, PDO::PARAM_INT);
            $requete->bindParam(2, $idutilisateur, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRAPPARTIENT . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function updateAppartient(Appartient $appartient, $idutilisateur) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('UPDATE appartient SET idtypeentreprise_typeentreprise =? where idutilisateur_utilisateur=?');
            $idtypeentreprise = $appartient->getIdtypeentreprise_typeentreprise();
            $requete->bindParam(1, $idtypeentreprise, PDO::PARAM_INT);
            $requete->bindParam(2, $idutilisateur, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRAPPARTIENT . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

//------------------------------------------------------------------------------------------------------------
//                      METHODE INTERVIENT
//------------------------------------------------------------------------------------------------------------
    public function addIntervient(Intervient $intervient) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('INSERT INTO intervient (idsecteuractivite_secteuractivite,idutilisateur_utilisateur)VALUES(?,?) ');
            $idsecteuractivite = $intervient->getIdsecteuractivite_secteuractivite();
            $idutilisateur = $intervient->getIdutilisateur_utilisateur();
            $requete->bindParam(1, $idsecteuractivite, PDO::PARAM_INT);
            $requete->bindParam(2, $idutilisateur, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRINTERVIENT . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function updateIntervient(Intervient $intervient, $idutilisateur) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('UPDATE intervient SET idsecteuractivite_secteuractivite =? where idutilisateur_utilisateur=?');
            $idsecteuractivite = $intervient->getIdsecteuractivite_secteuractivite();
            $requete->bindParam(1, $idsecteuractivite, PDO::PARAM_INT);
            $requete->bindParam(2, $idutilisateur, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRINTERVIENT . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

//------------------------------------------------------------------------------------------------------------
//                      METHODE CONCERNE
//------------------------------------------------------------------------------------------------------------
    public function addConcerne(Concerne $concerne) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("INSERT INTO concerne (idcentrale_centrale,idprojet_projet,idstatutprojet_statutprojet,commentaireprojet) VALUES(?,?,?,?)");
            $idcentrale = $concerne->getIdcentrale_centrale();
            $idprojet = $concerne->getIdprojet_projet();
            $idstatutprojet = $concerne->getIdstatutprojet_statutprojet();
            $commentaireProjet = $concerne->getCommentaireProjet();
            $requete->bindParam(1, $idcentrale, PDO::PARAM_INT);
            $requete->bindParam(2, $idprojet, PDO::PARAM_INT);
            $requete->bindParam(3, $idstatutprojet, PDO::PARAM_INT);
            $requete->bindParam(4, $commentaireProjet, PDO::PARAM_STR);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRCONCERNE . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function updateConcerne(Concerne $concerne, $idprojet) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("update concerne set idstatutprojet_statutprojet=?,commentaireprojet=? where idcentrale_centrale=?   and idprojet_projet=?");
            $idstatutprojet = $concerne->getIdstatutprojet_statutprojet();
            $commentaireProjet = $concerne->getCommentaireProjet();
            $idcentrale  =$concerne->getIdcentrale_centrale();
            $requete->bindParam(1, $idstatutprojet, PDO::PARAM_INT);
            $requete->bindParam(2, $commentaireProjet, PDO::PARAM_STR);
            $requete->bindParam(3, $idcentrale, PDO::PARAM_INT);
            $requete->bindParam(4, $idprojet, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRMAJCONCERNE . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function deleteConcerne($idprojet) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("delete from concerne where idprojet_projet=?");
            $requete->bindParam(1, $idprojet, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRDELCONCERNE . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function deleteConcerneProjetCentrale($idprojet, $idcentrale) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("delete from concerne where idprojet_projet=? and idcentrale_centrale=?");
            $requete->bindParam(1, $idprojet, PDO::PARAM_INT);
            $requete->bindParam(2, $idcentrale, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRDELCONCERNE . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function updateConcernePhase1(ConcernePhase1 $concernephase1, $idprojet) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("update concerne set idstatutprojet_statutprojet=? where idprojet_projet=?");
            $idstatutprojet = $concernephase1->getIdstatutprojet_statutprojet();
            $requete->bindParam(1, $idstatutprojet, PDO::PARAM_INT);
            $requete->bindParam(2, $idprojet, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRMAJCONCERNE . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

//------------------------------------------------------------------------------------------------------------
//                      METHODE PROJETDATEDEBUT
//------------------------------------------------------------------------------------------------------------
    public function updateDateDebutProjet(DateDebutProjet $datedebut, $idprojet) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('UPDATE projet SET  datedebutprojet =? WHERE idprojet =?');
            $dateDebut = $datedebut->getDatedebutprojet();
            $requete->bindParam(1, $dateDebut, PDO::PARAM_STR); 
            $requete->bindParam(2, $idprojet, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRUPDATEPROJET . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

//------------------------------------------------------------------------------------------------------------
//                      METHODE PROJETDATEFINI
//------------------------------------------------------------------------------------------------------------
    public function updateDateStatutFini(DateStatutFiniProjet $datefini, $idprojet) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('UPDATE projet SET  datestatutfini =? WHERE idprojet =?');
            $dateStatutFiniProjet = $datefini->getDateStatutFiniProjet();
            $requete->bindParam(1, $dateStatutFiniProjet, PDO::PARAM_STR);
            $requete->bindParam(2, $idprojet, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRUPDATEPROJET . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

//ok
//------------------------------------------------------------------------------------------------------------
//                      METHODE PROJETDATECLOTURER
//------------------------------------------------------------------------------------------------------------
    public function updateDateStatutCloturer(DateStatutCloturerProjet $datecloturer, $idprojet) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('UPDATE projet SET  datestatutcloturer =? WHERE idprojet =?');
            $dateStatutCloturerProjet  =$datecloturer->getDateStatutCloturerProjet();
            $requete->bindParam(1, $dateStatutCloturerProjet, PDO::PARAM_STR);
            $requete->bindParam(2, $idprojet, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRUPDATEPROJET . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

//------------------------------------------------------------------------------------------------------------
//                      METHODE PROJETDATEREFUSER
//------------------------------------------------------------------------------------------------------------
    public function updateDateStatutRefuser(DateStatutRefusProjet $daterefus, $idprojet, $idcentrale) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('UPDATE concerne SET  datestatutrefuser =? WHERE idprojet_projet =? and idcentrale_centrale=?');
            $daterefusprojet  =$daterefus->getDaterefusprojet();
            $requete->bindParam(1, $daterefusprojet, PDO::PARAM_STR);
            $requete->bindParam(2, $idprojet, PDO::PARAM_INT);
            $requete->bindParam(3, $idcentrale, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRUPDATEPROJET . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

//------------------------------------------------------------------------------------------------------------
//                      METHODE CREER
//------------------------------------------------------------------------------------------------------------
    public function addCreer(Creer $creer) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('INSERT INTO CREER (idutilisateur_utilisateur,idprojet_projet)VALUES(?,?) ');
            $idutilisateur= $creer->getIdutilisateur_utilisateur();
            $idprojet = $creer->getIdprojet_projet();
            $requete->bindParam(1, $idutilisateur, PDO::PARAM_INT);
            $requete->bindParam(2, $idprojet, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRCREER . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

//------------------------------------------------------------------------------------------------------------
//                      METHODE PERSONNE ACCUEIL CENTRALE
//------------------------------------------------------------------------------------------------------------
    public function addPersonneaccueilcentrale(Personneaccueilcentrale $personne) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
           /*$requete = $this->_db->prepare('INSERT INTO personneaccueilcentrale (idpersonneaccueilcentrale,nomaccueilcentrale,prenomaccueilcentrale,idqualitedemandeuraca_qualitedemandeuraca,mailaccueilcentrale,
    telaccueilcentrale,connaissancetechnologiqueaccueil,idpersonnequalite,idautresqualite)VALUES (?,?,?,?,?,?,?,?,?)  ');*/
            
            $requete = $this->_db->prepare('INSERT INTO personneaccueilcentrale (idpersonneaccueilcentrale,nomaccueilcentrale,prenomaccueilcentrale,idqualitedemandeuraca_qualitedemandeuraca,mailaccueilcentrale,
    telaccueilcentrale,connaissancetechnologiqueaccueil)VALUES (?,?,?,?,?,?,?)  ');
           
            $idpersonneaccueilcentrale = $personne->getIdpersonneaccueilcentrale();
            $nomaccueilcentrale =$personne->getNomaccueilcentrale();
            $prenomaccueilcentrale = $personne->getPrenomaccueilcentrale();
            $idqualitedemandeuraca = $personne->getIdqualitedemandeuraca_qualitedemandeuraca();
            $mailaccueilcentrale =$personne->getMailaccueilcentrale();
            $telaccueilcentrale = $personne->getTelaccueilcentrale();
            $connaissancetechnologiqueaccueil = $personne->getConnaissancetechnologiqueaccueil();
            //$idpersonneQualite = $personne->getIdpersonneQualite();
            //$idautrequalite = $personne->getIdautresqualite();
            $requete->bindParam(1, $idpersonneaccueilcentrale, PDO::PARAM_INT);
            $requete->bindParam(2, $nomaccueilcentrale, PDO::PARAM_STR);
            $requete->bindParam(3, $prenomaccueilcentrale, PDO::PARAM_STR);
            $requete->bindParam(4, $idqualitedemandeuraca, PDO::PARAM_INT);
            $requete->bindParam(5, $mailaccueilcentrale, PDO::PARAM_STR);
            $requete->bindParam(6, $telaccueilcentrale, PDO::PARAM_STR);
            $requete->bindParam(7, $connaissancetechnologiqueaccueil, PDO::PARAM_STR);
            //$requete->bindParam(8, $idpersonneQualite, PDO::PARAM_INT);
            //$requete->bindParam(9, $idautrequalite, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRDELETEPROJETPERSONNEACCUEILCENTRALE . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }
public function addAutreQualite(Autresqualite $autresqualite) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('insert into autresqualite (idautresqualite,libelleautresqualite) values (?,?)');
            $idautresqualite =$autresqualite->getIdautresqualite();
            $libelleautresqualite = $autresqualite->getLibelleautresqualite();
            $requete->bindParam(1, $idautresqualite, PDO::PARAM_INT);
            $requete->bindParam(2, $libelleautresqualite, PDO::PARAM_STR);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRDELETEPROJETPERSONNEACCUEILCENTRALE . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }
//------------------------------------------------------------------------------------------------------------
//                      METHODE PROJETPERSONEACCUEILCENTRALE
//------------------------------------------------------------------------------------------------------------
    public function addprojetpersonneaccueilcentrale(Projetpersonneaccueilcentrale $projet) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('INSERT INTO projetpersonneaccueilcentrale (idprojet_projet,idpersonneaccueilcentrale_personneaccueilcentrale)VALUES(?,?) ');
            $idprojet_projet =$projet->getIdprojet_projet();
            $idpersonneaccueilcentrale = $projet->getIdpersonneaccueilcentrale_personneaccueilcentrale();
            $requete->bindParam(1, $idprojet_projet, PDO::PARAM_INT);
            $requete->bindParam(2, $idpersonneaccueilcentrale, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRDELETEPROJETPERSONNEACCUEILCENTRALE . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function deleteprojetpersonneaccueilcentrale($idprojet) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('delete from projetpersonneaccueilcentrale where idprojet_projet=?');
            $requete->bindParam(1, $idprojet, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRDELETEPROJETPERSONNEACCUEILCENTRALE . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }
   
//------------------------------------------------------------------------------------------------------------
//                                       RESSOURCE
//------------------------------------------------------------------------------------------------------------

    public function addressource(Ressource $ressource) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('INSERT INTO ressource(idressource, libelleressource,masqueressource,libelleressourceen)VALUES (?,?,?,?)');
            $idressource =$ressource->getIdressource();
            $libelleressource =$ressource->getLibelleressource();
            $masqueressource = $ressource->getMasqueressource();
            $libelleressourceen =$ressource->getLibelleressourceen();
            $requete->bindParam(1, $idressource, PDO::PARAM_INT);
            $requete->bindParam(2, $libelleressource, PDO::PARAM_STR);
            $requete->bindParam(3, $masqueressource, PDO::PARAM_BOOL);
            $requete->bindParam(4, $libelleressourceen, PDO::PARAM_STR);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRRESSOURCE . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function updateressource(Ressource $ressource, $idressource) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('update ressource set libelleressource =?,masqueressource=?,libelleressourceen=? WHERE idressource =? ');
            $libelleressource = $ressource->getLibelleressource();
            $masqueressource =$ressource->getMasqueressource();
            $libelleressourceen  =$ressource->getLibelleressourceen();
            $requete->bindParam(1, $libelleressource, PDO::PARAM_STR);
            $requete->bindParam(2, $masqueressource, PDO::PARAM_BOOL);
            $requete->bindParam(3, $libelleressourceen, PDO::PARAM_STR);
            $requete->bindParam(4, $idressource, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRUPDATERESSOURCE . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function afficheHideRessource(Ressource $ressource, $idressource) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("UPDATE ressource SET masqueressource =? WHERE idressource=?");
            $masqueressource = $ressource->getMasqueressource();
            $requete->bindParam(1, $masqueressource, PDO::PARAM_BOOL);
            $requete->bindParam(2, $idressource, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRUPDATERESSOURCE . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

//------------------------------------------------------------------------------------------------------------
//                                       LIBELLE
//------------------------------------------------------------------------------------------------------------

    public function updateLibelleApplication(Libelleapplication $libelleapplication, $reflibelle) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('update libelleapplication set libellefrancais =?,libelleanglais=? WHERE reflibelle =? ');
            $libellefrancais =$libelleapplication->getLibellefrancais();
            $libelleanglais = $libelleapplication->getLibelleanglais();
            $requete->bindParam(1, $libellefrancais, PDO::PARAM_STR);
            $requete->bindParam(2, $libelleanglais, PDO::PARAM_STR);
            $requete->bindParam(3, $reflibelle, PDO::PARAM_STR);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRUPDATESITEWEB . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function updatesitewebApplication(Sitewebapplication $sitewebapplication, $refsiteweb) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('update sitewebapplication set adressesitewebcentrale =? WHERE refsiteweb =? ');
            $adressesitewebcentrale = $sitewebapplication->getAdressesitewebcentrale();
            $requete->bindParam(1, $adressesitewebcentrale(), PDO::PARAM_STR);
            $requete->bindParam(2, $refsiteweb, PDO::PARAM_STR);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRUPDATESITEWEB . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

//ok
//------------------------------------------------------------------------------------------------------------
//                      TYPE FORMATION
//------------------------------------------------------------------------------------------------------------

    public function addTypeFormation(Typeformation $typeformation) { //ajout d'un type dans la liste
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('INSERT INTO typeformation (idtypeformation,libelletypeformation,masquetypeformation,libelletypeformationen)VALUES(?,?,?,?) ');
            $idtypeformation = $typeformation->getIdtypeformation();
            $libelletypeformation = $typeformation->getLibelletypeformation();
            $masquetypeformation = $typeformation->getMasquetypeformation();
            $libelletypeformationen =$typeformation->getLibelletypeformationen();
            $requete->bindParam(1, $idtypeformation, PDO::PARAM_INT);
            $requete->bindParam(2, $libelletypeformation, PDO::PARAM_STR);
            $requete->bindParam(3, $masquetypeformation, PDO::PARAM_BOOL);
            $requete->bindParam(4, $libelletypeformationen, PDO::PARAM_STR);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRUPDATETYPEFORMATION . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function updatetypeformation(Typeformation $typeformation, $idtypeformation) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('update typeformation set libelletypeformation =?,libelletypeformationen=?, masquetypeformation=?  WHERE idtypeformation =? ');
            $libelletypeformation = $typeformation->getLibelletypeformation();
            $masquetypeformation = $typeformation->getMasquetypeformation();
            $libelletypeformationen =$typeformation->getLibelletypeformationen();
            $requete->bindParam(1, $libelletypeformation, PDO::PARAM_INT);
            $requete->bindParam(2, $libelletypeformationen, PDO::PARAM_INT);
            $requete->bindParam(3, $masquetypeformation, PDO::PARAM_BOOL);
            $requete->bindParam(4, $idtypeformation, PDO::PARAM_BOOL);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRUPDATETYPEFORMATION . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function afficheHideTypeformation(Typeformation $typeformation, $idtypeformation) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("UPDATE typeformation SET masquetypeformation=? WHERE idtypeformation=?");
            $masquetypeformation = $typeformation->getMasquetypeformation();
            $requete->bindParam(1, $masquetypeformation, PDO::PARAM_BOOL);
            $requete->bindParam(2, $idtypeformation, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRUPDATETYPEFORMATION . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function addprojettypeprojet(Projettypeprojet $projettypeprojet, $idprojet) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('insert into projettypeprojet (idtypeformation, idprojet) values(?,?) ');
            $idtypeformation = $projettypeprojet->getIdtypeformation();
            $requete->bindParam(1, $idtypeformation, PDO::PARAM_INT);
            $requete->bindParam(2, $idprojet, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRTYPEPROJET . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function updateprojettypeprojet(Projettypeprojet $projettypeprojet, $idprojet) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('update  projettypeprojet set idtypeformation=? where idprojet=?');
            $idtypeformation =$projettypeprojet->getIdtypeformation();
            $requete->bindParam(1, $idtypeformation, PDO::PARAM_INT);
            $requete->bindParam(2, $idprojet, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRUPDATETYPEPROJET . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function deleteprojettypeprojet($idprojet) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('delete from  projettypeprojet where idprojet=?');
            $requete->bindParam(1, $idprojet, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRDELETETYPEPROJET . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

//-----------------------------------------------------------------------------------------------------------
//              MISE A JOUR DU PROJET
//-----------------------------------------------------------------------------------------------------------
    public function updateDatemajProjet(DateMajProjet $datemaj, $idprojet) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('UPDATE projet SET datemaj = ? WHERE idprojet = ?');
            $dateMajProjet = $datemaj->getDateMajProjet();
            $requete->bindParam(1, $dateMajProjet, PDO::PARAM_STR);
            $requete->bindParam(2, $idprojet, PDO::PARAM_STR);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERR . '<br>Ligne ' . $exc->getLine() . '<br>' . $exc->getMessage();
            $this->_db->rollBack();
        }
    }

     public function updateUserTypeentreprise(UserTypeEntreprise $utilisateur, $idutilisateur) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("UPDATE appartient SET idtypeentreprise_typeentreprise =? where idutilisateur_utilisateur=?");
            $idtypeentreprise = $utilisateur->getIdtypeentreprise();
            $requete->bindParam(1, $idtypeentreprise, PDO::PARAM_INT);
            $requete->bindParam(2, $idutilisateur, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRUPDATEUSER . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }
    
    public function updateUserSecteurActivite(UserSecteurActivite $utilisateur, $idutilisateur) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("UPDATE intervient SET idsecteuractivite_secteuractivite =? where idutilisateur_utilisateur=?");
            $idsecteuractivite = $utilisateur->getIdsecteuractivite();
            $requete->bindParam(1, $idsecteuractivite, PDO::PARAM_INT);
            $requete->bindParam(2, $idutilisateur, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRUPDATEUSER . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }
    
//-----------------------------------------------------------------------------------------------------------
//              COMPTEUR
//-----------------------------------------------------------------------------------------------------------
    public function updatecompteur(Compteur $compteur, $var) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('UPDATE compteur SET c_total = ?,c_lastvisit=? WHERE c_login != ?');
            $c_total =$compteur->getC_total();
            $clastvisit=$compteur->getClastvisit();
            $requete->bindParam(1, $c_total, PDO::PARAM_INT);
            $requete->bindParam(2, $clastvisit, PDO::PARAM_STR);
            $requete->bindParam(3, $var, PDO::PARAM_STR);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERR . '<br>Ligne ' . $exc->getLine() . '<br>' . $exc->getMessage();
            $this->_db->rollBack();
        }
    }

    public function deletecompteur(Compteur $compteur) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('DELETE FROM compteur WHERE c_login != ? AND extract(epoch from(c_firstvisit)) < ?');
            $clogin = $compteur->getClogin();
            $clastvisit =$compteur->getClastvisit();
            $requete->bindParam(1, $clogin, PDO::PARAM_INT);
            $requete->bindParam(2, $clastvisit, PDO::PARAM_STR);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERR . '<br>Ligne ' . $exc->getLine() . '<br>' . $exc->getMessage();
            $this->_db->rollBack();
        }
    }

    public function addcompteur(Compteur $compteur) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('INSERT INTO compteur (c_firstvisit, c_lastvisit, c_total, c_login, c_time) VALUES (?,?,?,?,?)');
            $cfirstvisit = $compteur->getCfirstvisit();
            $clastvisit =$compteur->getClastvisit();
            $c_total = $compteur->getC_total();
            $clogin = $compteur->getClogin();
            $ctime = $compteur->getCtime();
            $requete->bindParam(1, $cfirstvisit, PDO::PARAM_INT);
            $requete->bindParam(2, $clastvisit, PDO::PARAM_INT);
            $requete->bindParam(3, $c_total, PDO::PARAM_INT);
            $requete->bindParam(4, $clogin, PDO::PARAM_STR);
            $requete->bindParam(5, $ctime, PDO::PARAM_STR);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERR . '<br>Ligne ' . $exc->getLine() . '<br>' . $exc->getMessage();
            $this->_db->rollBack();
        }
    }

//-----------------------------------------------------------------------------------------------------------
//             PROJETAUTRESCENTRALES
//-----------------------------------------------------------------------------------------------------------

    public function addprojetautrescentrale(Projetautrecentrale $projetautrecentrale) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('INSERT INTO Projetautrecentrale (idcentrale,idprojet) VALUES (?,?)');
            $idcentrale =$projetautrecentrale->getIdcentrale();
            $idprojet = $projetautrecentrale->getIdprojet();
            $requete->bindParam(1, $idcentrale, PDO::PARAM_INT);
            $requete->bindParam(2, $idprojet, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERR . '<br>Ligne ' . $exc->getLine() . '<br>' . $exc->getMessage();
            $this->_db->rollBack();
        }
    }

    public function deleteprojetautrecentrale($idprojet) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('DELETE from Projetautrecentrale WHERE idprojet = ?');
            $requete->bindParam(1, $idprojet, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERR . '<br>Ligne ' . $exc->getLine() . '<br>' . $exc->getMessage();
            $this->_db->rollBack();
        }
    }

//-----------------------------------------------------------------------------------------------------------
//             PROJET CENTRALES DE PROXIMITE
//-----------------------------------------------------------------------------------------------------------
/**
 * 
 * @param Projet_centraleproximite $projetcentraleproximite
 */
    public function addprojetcentraleproximite(Projet_centraleproximite $projetcentraleproximite) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('INSERT INTO projet_centraleproximite (idprojet,idcentrale_proximite) VALUES (?,?)');
            $idprojet = $projetcentraleproximite->getIdprojet();
            $idcentrale_proximite = $projetcentraleproximite->getIdcentrale_proximite();
            $requete->bindParam(1, $idprojet, PDO::PARAM_INT);
            $requete->bindParam(2, $idcentrale_proximite, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERR . '<br>Ligne ' . $exc->getLine() . '<br>' . $exc->getMessage();
            $this->_db->rollBack();
        }
    }
/**
 * 
 * @param type $idprojet
 */
    public function deleteprojetcentraleproximite($idprojet) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('DELETE from projet_centraleproximite WHERE idprojet = ?');
            $requete->bindParam(1, $idprojet, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERR . '<br>Ligne ' . $exc->getLine() . '<br>' . $exc->getMessage();
            $this->_db->rollBack();
        }
    }
/**
 * 
 * @param Centrale_proximite $centraleproximite
 */
    public function addcentraleproximite(Centrale_proximite $centraleproximite) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('INSERT INTO centrale_proximite (idcentrale_proximite,nom_centrale_proximite) VALUES (?,?)');
            $idcentrale_proximite =$centraleproximite->getIdcentrale_proximite();
            $nom_centrale_proximite =$centraleproximite->getNom_centrale_proximite();
            $requete->bindParam(1, $idcentrale_proximite, PDO::PARAM_INT);
            $requete->bindParam(2, $nom_centrale_proximite, PDO::PARAM_STR);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERR . '<br>Ligne ' . $exc->getLine() . '<br>' . $exc->getMessage();
            $this->_db->rollBack();
        }
    }
/**
 * 
 * @param Centrale_proximite $centraleproximite
 * @param type $idcentraleproximite
 */
    public function updatecentraleproximite(Centrale_proximite $centraleproximite, $idcentraleproximite) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('Update centrale_pproximite set nom_centrale_proximite = ? where idcentrale_proximite=?');
            $nom_centrale_proximite =$centraleproximite->getNom_centrale_proximite();
            $requete->bindParam(1, $nom_centrale_proximite, PDO::PARAM_STR);
            $requete->bindParam(2, $idcentraleproximite, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERR . '<br>Ligne ' . $exc->getLine() . '<br>' . $exc->getMessage();
            $this->_db->rollBack();
        }
    }

//-----------------------------------------------------------------------------------------------------------
//             RAPPORTS
//-----------------------------------------------------------------------------------------------------------
    /**
     * 
     * @param Rapport $rapport
     */
    public function addrapport(Rapport $rapport) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('INSERT INTO rapport (idrapport,title,author,entity,villepays,instituteinterest,fundingsource,collaborator,thematics,startingdate,objectif,results,valorization,technologicalwc,'
                    . 'logo,logocentrale,figure,idprojet,legend,datecreation,datemiseajour) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
            $idrapport = $rapport->getIdrapport();
            $title = $rapport->getTitle();
            $author = $rapport->getAuthor();
            $entity =$rapport->getEntity();
            $villepays =$rapport->getVillepays();
            $instituteinterest = $rapport->getInstituteinterest();
            $fundingsource =$rapport->getFundingsource();
            $collaborator =$rapport->getCollaborator();
            $thematics =$rapport->getThematics();
            $startingdate =$rapport->getStartingdate();
            $objectif =$rapport->getObjectif();
            $results = $rapport->getResults();
            $valorisation = $rapport->getValorisation();
            $technologicalwc =$rapport->getTechnologicalwc();
            $logo = $rapport->getLogo();
            $logocentrale = $rapport->getLogocentrale();
            $figure =$rapport->getFigure();
            $idprojet =$rapport->getIdprojet();
            $legend =$rapport->getLegend();
            $datecreation = $rapport->getDatecreation();
            $datemiseajour = $rapport->getDatemiseajour();
            $requete->bindParam(1, $idrapport, PDO::PARAM_INT);
            $requete->bindParam(2, $title, PDO::PARAM_STR);
            $requete->bindParam(3, $author, PDO::PARAM_STR);
            $requete->bindParam(4, $entity, PDO::PARAM_STR);
            $requete->bindParam(5, $villepays, PDO::PARAM_STR);
            $requete->bindParam(6, $instituteinterest, PDO::PARAM_STR);
            $requete->bindParam(7, $fundingsource, PDO::PARAM_STR);
            $requete->bindParam(8, $collaborator, PDO::PARAM_STR);
            $requete->bindParam(9, $thematics, PDO::PARAM_STR);
            $requete->bindParam(10, $startingdate, PDO::PARAM_STR);
            $requete->bindParam(11, $objectif, PDO::PARAM_STR);
            $requete->bindParam(12, $results, PDO::PARAM_STR);
            $requete->bindParam(13, $valorisation, PDO::PARAM_STR);
            $requete->bindParam(14, $technologicalwc, PDO::PARAM_STR);
            $requete->bindParam(15, $logo, PDO::PARAM_STR);
            $requete->bindParam(16, $logocentrale, PDO::PARAM_STR);
            $requete->bindParam(17, $figure, PDO::PARAM_STR);
            $requete->bindParam(18, $idprojet, PDO::PARAM_INT);
            $requete->bindParam(19, $legend, PDO::PARAM_STR);
            $requete->bindParam(20, $datecreation, PDO::PARAM_STR);
            $requete->bindParam(21, $datemiseajour, PDO::PARAM_STR);            
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERR . '<br>Ligne ' . $exc->getLine() . '<br>' . $exc->getMessage().'<br> getFile'.$exc->getFile().'<br> getFile'.$exc->getTraceAsString();
            
            $this->_db->rollBack();
        }
    }
/**
 * 
 * @param Rapport $rapport
 * @param type $idprojet
 */
    public function updateRapport(Rapport $rapport, $idprojet) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('update rapport SET author = ?,title=?,entity=?,villepays=?,instituteinterest=?,fundingsource=?,collaborator=?,thematics=?,startingdate=?,objectif=?,results=?,valorization=?,'
                    . 'technologicalwc=?,logo=?,logocentrale=?,figure=?,legend=?,datecreation=?, datemiseajour=? WHERE idprojet = ?');
            $author = $rapport->getAuthor();
            $title = $rapport->getTitle();
            $entity =$rapport->getEntity();
            $villepays =$rapport->getVillepays();
            $instituteinterest = $rapport->getInstituteinterest();
            $fundingsource =$rapport->getFundingsource();
            $collaborator =$rapport->getCollaborator();
            $thematics =$rapport->getThematics();
            $startingdate =$rapport->getStartingdate();
            $objectif =$rapport->getObjectif();
            $results = $rapport->getResults();
            $valorisation = $rapport->getValorisation();
            $technologicalwc =$rapport->getTechnologicalwc();
            $logo = $rapport->getLogo();
            $logocentrale = $rapport->getLogocentrale();
            $figure =$rapport->getFigure();
            $legend =$rapport->getLegend();
            $datecreation = $rapport->getDatecreation();
            $datemiseajour = $rapport->getDatemiseajour();
            $requete->bindParam(1, $author, PDO::PARAM_STR);
            $requete->bindParam(2, $title, PDO::PARAM_STR);
            $requete->bindParam(3, $entity, PDO::PARAM_STR);
            $requete->bindParam(4, $villepays, PDO::PARAM_STR);
            $requete->bindParam(5, $instituteinterest, PDO::PARAM_STR);
            $requete->bindParam(6, $fundingsource, PDO::PARAM_STR);
            $requete->bindParam(7, $collaborator, PDO::PARAM_STR);
            $requete->bindParam(8, $thematics, PDO::PARAM_STR);
            $requete->bindParam(9, $startingdate, PDO::PARAM_STR);
            $requete->bindParam(10, $objectif, PDO::PARAM_STR);
            $requete->bindParam(11, $results, PDO::PARAM_STR);
            $requete->bindParam(12, $valorisation, PDO::PARAM_STR);
            $requete->bindParam(13, $technologicalwc, PDO::PARAM_STR);
            $requete->bindParam(14, $logo, PDO::PARAM_STR);
            $requete->bindParam(15, $logocentrale, PDO::PARAM_STR);
            $requete->bindParam(16, $figure, PDO::PARAM_STR);
            $requete->bindParam(17, $legend, PDO::PARAM_STR);
            $requete->bindParam(18, $datecreation, PDO::PARAM_STR);
            $requete->bindParam(19, $datemiseajour, PDO::PARAM_STR);
            $requete->bindParam(20, $idprojet, PDO::PARAM_INT);            
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERR . '<br>Ligne ' . $exc->getLine() . '<br>' . $exc->getMessage();
            $this->_db->rollBack();
        }
    }
/**
 * 
 * @param Rapportfigure $rapportfigure
 * @param type $idrapport
 */
    public function updateRapportfigure(Rapportfigure $rapportfigure, $idrapport) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('update rapport SET figure=? WHERE idrapport = ?');
            $figure =$rapportfigure->getfigure();
            $requete->bindParam(1, $figure, PDO::PARAM_STR);
            $requete->bindParam(2, $idrapport, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERR . '<br>Ligne ' . $exc->getLine() . '<br>' . $exc->getMessage();
            $this->_db->rollBack();
        }
    }
/**
 * 
 * @param type $idprojet
 */
    public function deleterapport($idprojet) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("delete from rapport where idprojet =?");
            $requete->bindParam(1, $idprojet, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRDELETEPROJETDATEDEBUT . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }
    /**
 * 
 * @param type $idprojet
 */
    public function delRapport($idrapport) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("delete from rapport where idrapport =?");
            $requete->bindParam(1, $idrapport, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRDELETEPROJETDATEDEBUT . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

//-----------------------------------------------------------------------------------------------------------
//              SELECT
//-----------------------------------------------------------------------------------------------------------
    /**
     * 
     * @param type $request
     * @param array $param
     */
    public function getRequete($request, array $param) {
        $requete = $this->_db->prepare($request);
        $requete->execute($param);
    }
/**
 * 
 * @param type $request
 */
    public function exeRequete($request) {
        $requete = $this->_db->prepare($request);
        $requete->execute();
    }
/**
 * 
 * @param type $request
 * @return type
 */
    public function getSingle($request) {
        $requete = $this->_db->prepare($request);
        $requete->execute();
        return $requete->fetch(PDO::FETCH_COLUMN);
    }
/**
 * 
 * @param type $request
 * @param type $param
 * @return type
 */
    public function getSingle2($request, $param) {
        $requete = $this->_db->prepare($request);
        $requete->execute(array($param));
        return $requete->fetch(PDO::FETCH_COLUMN);
    }

    /**
     * 
     * @param type $request
     * @param array $param
     * @return type
     */
    public function getSinglebyArray($request, array $param) {
        $requete = $this->_db->prepare($request);
        $requete->execute($param);
        return $requete->fetch(PDO::FETCH_COLUMN);
    }

    /**
     * 
     * @param type $request
     * @return type
     */
    public function getList($request) {
        $requete = $this->_db->prepare($request);
        $requete->execute();
        return $requete->fetchAll();
    }
    /**
     * This function return an array of an array of the request
     * @param type string $request is the request 
     * @param type string or integer $param is the only parameter concerning the request
     * @return type
     */
    public function getList2($request, $param) {
        $requete = $this->_db->prepare($request);
        $requete->execute(array($param));
        return $requete->fetchAll();
    }

    public function getListbyArray($request, array $param) {
        $requete = $this->_db->prepare($request);
        $requete->execute($param);
        return $requete->fetchAll();
    }

    public function getListObjet($request) {// A CORRIGER
        $requete = $this->_db->prepare($request);
        $requete->execute();
        return $requete->fetchObject();
    }

    public function getdataArray($request) {// A CORRIGER
        $attachement = array();
        $requete = $this->_db->query($request);
        while ($donnees = $requete->fetch(PDO::FETCH_COLUMN)) {
            $attachement[] .=$donnees;
        }
        return $attachement;
    }

    public function setDb(PDO $db) {
        $this->_db = $db;
    }

}
