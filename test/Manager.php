<?php

/* * **************************************************************************/
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

if (is_file('../decide-lang.php')) {
    include_once '../decide-lang.php';
} elseif (is_file('decide-lang.php')) {
    include_once 'decide-lang.php';
}
include_once 'Compteur.php';
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
            $requete = $this->_db->prepare('INSERT INTO loginpassword (idlogin,mail,motdepasse,pseudo)VALUES(?,?,?,?)');
            $requete->bindParam(1, $login->getIdlogin(), PDO::PARAM_INT);
            $requete->bindParam(2, $login->getEmail(), PDO::PARAM_STR);
            $requete->bindParam(3, $login->getMotpasse(), PDO::PARAM_STR);
            $requete->bindParam(4, $login->getPseudo(), PDO::PARAM_STR);
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
            $requete->bindParam(1, $loginpassword->getMotpasse(), PDO::PARAM_STR);
            $requete->bindParam(2, $loginpassword->getPseudo(), PDO::PARAM_STR);
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
            $requete->bindParam(1, $loginActif->getActif(), PDO::PARAM_BOOL);
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
            $requete->bindParam(1, $loginMotpasseenvoye->getMotpasseenvoye(), PDO::PARAM_BOOL);
            $requete->bindParam(2, $loginMotpasseenvoye->getPseudo(), PDO::PARAM_STR);
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
            $requete->bindParam(1, $utilisateur->getNom(), PDO::PARAM_STR);
            $requete->bindParam(2, $utilisateur->getPrenom(), PDO::PARAM_STR);
            $requete->bindParam(3, $utilisateur->getEntrepriselaboratoire(), PDO::PARAM_STR);
            $requete->bindParam(4, $utilisateur->getAdresse(), PDO::PARAM_STR);
            $requete->bindParam(5, $utilisateur->getCodePostal(), PDO::PARAM_INT);
            $requete->bindParam(6, $utilisateur->getVille(), PDO::PARAM_STR);
            $requete->bindParam(7, $utilisateur->getDate(), PDO::PARAM_STR);
            $requete->bindParam(8, $utilisateur->getTel(), PDO::PARAM_STR);
            $requete->bindParam(9, $utilisateur->getFax(), PDO::PARAM_STR);
            $requete->bindParam(10, $utilisateur->getNomresponsable(), PDO::PARAM_STR);
            $requete->bindParam(11, $utilisateur->getMailresponsable(), PDO::PARAM_STR);
            $requete->bindParam(12, $utilisateur->getIdtypeutilisateur_typeutilisateur(), PDO::PARAM_INT);
            $requete->bindParam(13, $utilisateur->getIdpays_pays(), PDO::PARAM_INT);
            $requete->bindParam(14, $utilisateur->getIdlogin_loginpassword(), PDO::PARAM_INT);
            $requete->bindParam(15, $utilisateur->getIddiscipline_disciplinescientifique(), PDO::PARAM_INT);
            $requete->bindParam(16, $utilisateur->getIdcentrale_centrale(), PDO::PARAM_INT);
            $requete->bindParam(17, $utilisateur->getIdqualitedemandeuraca_qualitedemandeuraca(), PDO::PARAM_INT);
            $requete->bindParam(18, $utilisateur->getIdtutelle_tutelle(), PDO::PARAM_INT);
            $requete->bindParam(19, $utilisateur->getIdemployeur_nomemployeur(), PDO::PARAM_INT);
            $requete->bindParam(20, $utilisateur->getIdautrestutelle_autrestutelle(), PDO::PARAM_INT);
            $requete->bindParam(21, $utilisateur->getIdautrediscipline_autredisciplinescientifique(), PDO::PARAM_INT);
            $requete->bindParam(22, $utilisateur->getIdautrenomemployeur_autrenomemployeur(), PDO::PARAM_INT);
            $requete->bindParam(23, $utilisateur->getIdautrecodeunite_autrecodeunite(), PDO::PARAM_INT);
            $requete->bindParam(24, $utilisateur->getAcronymelaboratoire(), PDO::PARAM_STR);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRINSERTUSER . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function insertProjetSF(Projetsourcefinancement $projet) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('INSERT INTO projetsourcefinancement(idprojet_projet,idsourcefinancement_sourcefinancement) VALUES (?,?)');
            $requete->bindParam(1, $projet->getIdprojet(), PDO::PARAM_INT);
            $requete->bindParam(2, $projet->getIdsourcefinancement(), PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            ;
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
            $requete->bindParam(1, $projet->getAcronymesource(), PDO::PARAM_STR);
            $requete->bindParam(2, $idprojet, PDO::PARAM_INT);
            $requete->bindParam(3, $projet->getIdsourcefinancement(), PDO::PARAM_INT);
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
            $requete->bindParam(1, $acronyme->getAcronyme(), PDO::PARAM_STR);
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
            $requete->bindParam(1, $utilisateurmailresponsable->getMailresponsable(), PDO::PARAM_STR);
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
            $requete->bindParam(1, $utilisateurType->getIdtypeutilisateur_typeutilisateur(), PDO::PARAM_INT);
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
            $requete->bindParam(1, $utilisateurTypeadmin->getIdtypeutilisateur_typeutilisateur(), PDO::PARAM_INT);
            $requete->bindParam(2, $utilisateurTypeadmin->getIdcentrale_centrale(), PDO::PARAM_INT);
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
            $requete->bindParam(1, $utilisateurTutelle->getIdtutelle_tutelle(), PDO::PARAM_INT);
            $requete->bindParam(2, $utilisateurTutelle->getIdautrestutelle_autrestutelle(), PDO::PARAM_INT);
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
            $requete->bindParam(1, $utilisateurDiscipline->getIddiscipline_disciplinescientifique(), PDO::PARAM_INT);
            $requete->bindParam(2, $utilisateurDiscipline->getIdautrediscipline_autredisciplinescientifique(), PDO::PARAM_INT);
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
            $requete->bindParam(1, $utilisateurNomlabo->getNomlab(), PDO::PARAM_STR);
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
            $requete->bindParam(1, $utilisateur->getNom(), PDO::PARAM_STR);
            $requete->bindParam(2, $utilisateur->getPrenom(), PDO::PARAM_STR);
            $requete->bindParam(3, $utilisateur->getEntrepriselaboratoire(), PDO::PARAM_STR);
            $requete->bindParam(4, $utilisateur->getAdresse(), PDO::PARAM_STR);
            $requete->bindParam(5, $utilisateur->getCodePostal(), PDO::PARAM_INT);
            $requete->bindParam(6, $utilisateur->getVille(), PDO::PARAM_STR);
            $requete->bindParam(7, $utilisateur->getDate(), PDO::PARAM_STR);
            $requete->bindParam(8, $utilisateur->getTel(), PDO::PARAM_STR);
            $requete->bindParam(9, $utilisateur->getFax(), PDO::PARAM_STR);
            $requete->bindParam(10, $utilisateur->getNomresponsable(), PDO::PARAM_STR);
            $requete->bindParam(11, $utilisateur->getMailresponsable(), PDO::PARAM_STR);
            $requete->bindParam(12, $utilisateur->getIdtypeutilisateur_typeutilisateur(), PDO::PARAM_INT);
            $requete->bindParam(13, $utilisateur->getIdpays_pays(), PDO::PARAM_INT);
            $requete->bindParam(14, $utilisateur->getIdlogin_loginpassword(), PDO::PARAM_INT);
            $requete->bindParam(15, $utilisateur->getIddiscipline_disciplinescientifique(), PDO::PARAM_INT);
            $requete->bindParam(16, $utilisateur->getIdqualitedemandeuraca_qualitedemandeuraca(), PDO::PARAM_INT);
            $requete->bindParam(17, $utilisateur->getIdtutelle_tutelle(), PDO::PARAM_INT);
            $requete->bindParam(18, $utilisateur->getIdemployeur_nomemployeur(), PDO::PARAM_INT);
            $requete->bindParam(19, $utilisateur->getIdautrestutelle_autrestutelle(), PDO::PARAM_INT);
            $requete->bindParam(20, $utilisateur->getIdautrediscipline_autredisciplinescientifique(), PDO::PARAM_INT);
            $requete->bindParam(21, $utilisateur->getIdautrenomemployeur_autrenomemployeur(), PDO::PARAM_INT);
            $requete->bindParam(22, $utilisateur->getIdautrecodeunite_autrecodeunite(), PDO::PARAM_INT);
            $requete->bindParam(23, $utilisateur->getAcronymelaboratoire(), PDO::PARAM_STR);
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
            $requete->bindParam(1, $utilisateur->getNom(), PDO::PARAM_STR);
            $requete->bindParam(2, $utilisateur->getPrenom(), PDO::PARAM_STR);
            $requete->bindParam(3, $utilisateur->getEntrepriselaboratoire(), PDO::PARAM_STR);
            $requete->bindParam(4, $utilisateur->getAdresse(), PDO::PARAM_STR);
            $requete->bindParam(5, $utilisateur->getCodePostal(), PDO::PARAM_INT);
            $requete->bindParam(6, $utilisateur->getVille(), PDO::PARAM_STR);
            $requete->bindParam(7, $utilisateur->getDate(), PDO::PARAM_STR);
            $requete->bindParam(8, $utilisateur->getTel(), PDO::PARAM_STR);
            $requete->bindParam(9, $utilisateur->getFax(), PDO::PARAM_STR);
            $requete->bindParam(10, $utilisateur->getNomresponsable(), PDO::PARAM_STR);
            $requete->bindParam(11, $utilisateur->getMailresponsable(), PDO::PARAM_STR);
            $requete->bindParam(12, $utilisateur->getNomentreprise(), PDO::PARAM_STR);
            $requete->bindParam(13, $utilisateur->getIdtypeutilisateur_typeutilisateur(), PDO::PARAM_INT);
            $requete->bindParam(14, $utilisateur->getIdpays_pays(), PDO::PARAM_INT);
            $requete->bindParam(15, $utilisateur->getIdlogin_loginpassword(), PDO::PARAM_INT);
            $requete->bindParam(16, $utilisateur->getIdqualitedemandeurindust_qualitedemandeurindust(), PDO::PARAM_INT);
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
            $requete->bindParam(1, $utilisateurPorteurProjet->getIdprojet(), PDO::PARAM_INT);
            $requete->bindParam(2, $utilisateurPorteurProjet->getIdutilisateur(), PDO::PARAM_INT);
            $requete->bindParam(3, $utilisateurPorteurProjet->getDateaffectation(), PDO::PARAM_STR);
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
    //------------------------------------------------------------------------------------------------------------
//                                       UTILISATEUR ADMINISTRATEUR
//------------------------------------------------------------------------------------------------------------

    public function addUtilisateurAdmin(UtilisateurAdmin $utilisateurAdmin) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("insert into utilisateuradministrateur (idprojet,idutilisateur,dateaffectation) values(?,?,?)");
            $requete->bindParam(1, $utilisateurAdmin->getIdprojet(), PDO::PARAM_INT);
            $requete->bindParam(2, $utilisateurAdmin->getIdutilisateur(), PDO::PARAM_INT);
            $requete->bindParam(3, $utilisateurAdmin->getDateaffectation(), PDO::PARAM_STR);
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
    
    
    

//------------------------------------------------------------------------------------------------------------
//                                              NOMRESPONSABLE
//------------------------------------------------------------------------------------------------------------
    public function updateUtilisateurNomresponsable(UtilisateurNomresponsable $utilisateurnomresponsable, $idutilisateur) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("UPDATE utilisateur SET nomresponsable =? where idutilisateur=?");
            $requete->bindParam(1, $utilisateurnomresponsable->getNomresponsable(), PDO::PARAM_STR);
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
            $requete->bindParam(1, $loginUser->getLoginUtilisateur(), PDO::PARAM_STR);
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
            $requete->bindParam(1, $mailUser->getMailUtilisateur(), PDO::PARAM_STR);
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
            $requete->bindParam(1, $nomUser->getNom(), PDO::PARAM_STR);
            $requete->bindParam(2, $iduser, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRINSERTUSER . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function updatePrenomMoncompte(PrenomUtilisateur $prenomUser, $iduser) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("update utilisateur set prenom= ? where idutilisateur=?");
            $requete->bindParam(1, $prenomUser->getPrenom(), PDO::PARAM_STR);
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
            $requete->bindParam(1, $adresseUser->getAdresse(), PDO::PARAM_STR);
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
            $requete->bindParam(1, $cpUser->getCodePostal(), PDO::PARAM_STR);
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
            $requete->bindParam(1, $villeUser->getVilleUser(), PDO::PARAM_STR);
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
            $requete->bindParam(1, $paysUser->getIdpays_pays(), PDO::PARAM_INT);
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
            $requete->bindParam(1, $telUser->getTelUser(), PDO::PARAM_STR);
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
            $requete->bindParam(1, $faxUser->getFaxUser(), PDO::PARAM_STR);
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
            $requete->bindParam(1, $villeCentrale->getVilleCentrale(), PDO::PARAM_STR);
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
            $requete->bindParam(1, $emailCentrale->getEmailcentrale1(), PDO::PARAM_STR);
            $requete->bindParam(2, $emailCentrale->getEmailcentrale2(), PDO::PARAM_STR);
            $requete->bindParam(3, $emailCentrale->getEmailcentrale3(), PDO::PARAM_STR);
            $requete->bindParam(4, $emailCentrale->getEmailCentrale4(), PDO::PARAM_STR);
            $requete->bindParam(5, $emailCentrale->getEmailCentrale5(), PDO::PARAM_STR);
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
            $requete->bindParam(1, $codeuniteCentrale->getCodeunite(), PDO::PARAM_STR);
            $requete->bindParam(2, $idcentrale, PDO::PARAM_INT);
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
            $requete->bindParam(1, $projet->getIdprojet(), PDO::PARAM_INT);
            $requete->bindParam(2, $projet->getTitre(), PDO::PARAM_STR);
            $requete->bindParam(3, $projet->getNumero(), PDO::PARAM_STR);
            $requete->bindParam(4, $projet->getConfidentiel(), PDO::PARAM_BOOL);
            $requete->bindParam(5, $projet->getDescription(), PDO::PARAM_STR);
            $requete->bindParam(6, $projet->getDateprojet(), PDO::PARAM_STR);
            $requete->bindParam(7, $projet->getContexte(), PDO::PARAM_STR);
            $requete->bindParam(8, $projet->getIdtypeprojet_typeprojet(), PDO::PARAM_INT);
            $requete->bindParam(9, $projet->getAcronyme(), PDO::PARAM_STR);
            $requete->bindParam(10, $projet->getAttachement(), PDO::PARAM_STR);
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
            $requete->bindParam(1, $projet->getTitre(), PDO::PARAM_STR);
            $requete->bindParam(2, $projet->getNumero(), PDO::PARAM_STR);
            $requete->bindParam(3, $projet->getConfidentiel(), PDO::PARAM_BOOL);
            $requete->bindParam(4, $projet->getDescription(), PDO::PARAM_STR);
            $requete->bindParam(5, $projet->getDateprojet(), PDO::PARAM_STR);
            $requete->bindParam(6, $projet->getContexte(), PDO::PARAM_STR);
            $requete->bindParam(7, $projet->getIdtypeprojet_typeprojet(), PDO::PARAM_INT);
            $requete->bindParam(8, $projet->getAttachement(), PDO::PARAM_STR);
            $requete->bindParam(9, $projet->getAcronyme(), PDO::PARAM_STR);
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
nomformateur=?,partenaire1=?,porteurprojet =? where idprojet=?");
            $requete->bindParam(1, $projet2->getContactscentralaccueil(), PDO::PARAM_STR);
            $requete->bindParam(2, $projet2->getIdtypeprojet_typeprojet(), PDO::PARAM_INT);
            $requete->bindParam(3, $projet2->getNbHeure(), PDO::PARAM_INT);
            $requete->bindParam(4, $projet2->getDateDebutTravaux(), PDO::PARAM_STR);
            $requete->bindParam(5, $projet2->getDureeprojet(), PDO::PARAM_INT);
            $requete->bindParam(6, $projet2->getIdperiodicite_periodicite(), PDO::PARAM_INT);
            $requete->bindParam(7, $projet2->getCentralepartenaireprojet(), PDO::PARAM_STR);
            $requete->bindParam(8, $projet2->getIdthematique_thematique(), PDO::PARAM_INT);
            $requete->bindParam(9, $projet2->getIdautrethematique_autrethematique(), PDO::PARAM_INT);
            $requete->bindParam(10, $projet2->getDescriptifTechnologique(), PDO::PARAM_STR);
            $requete->bindParam(11, $projet2->getAttachementdesc(), PDO::PARAM_STR);
            $requete->bindParam(12, $projet2->getVerrouidentifiee(), PDO::PARAM_STR);
            $requete->bindParam(13, $projet2->getNbplaque(), PDO::PARAM_INT);
            $requete->bindParam(14, $projet2->getNbrun(), PDO::PARAM_INT);
            $requete->bindParam(15, $projet2->getEnvoidevis(), PDO::PARAM_BOOL);
            $requete->bindParam(16, $projet2->getEmailrespdevis(), PDO::PARAM_STR);
            $requete->bindParam(17, $projet2->getReussite(), PDO::PARAM_STR);
            $requete->bindParam(18, $projet2->getRefinterneprojet(), PDO::PARAM_STR);
            $requete->bindParam(19, $projet2->getDevtechnologique(), PDO::PARAM_BOOL);
            $requete->bindParam(20, $projet2->getNbeleve(), PDO::PARAM_INT);
            $requete->bindParam(21, $projet2->getNomformateur(), PDO::PARAM_STR);
            $requete->bindParam(22, $projet2->getPartenaire1(), PDO::PARAM_STR);
            $requete->bindParam(23, $projet2->getPorteurprojet(), PDO::PARAM_BOOL);
            $requete->bindParam(24, $idprojet, PDO::PARAM_INT);
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
            $requete->bindParam(1, $tmprecherche->getPorteur(), PDO::PARAM_STR);
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
            $requete->bindParam(1, $tmprecherche->getPorteur(), PDO::PARAM_STR);
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
            $requete->bindParam(1, $centrale->getIdcentrale(), PDO::PARAM_INT);
            $requete->bindParam(2, $centrale->getLibellecentrale(), PDO::PARAM_STR);
            $requete->bindParam(3, $centrale->getMasquecentrale(), PDO::PARAM_BOOL);
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
            $requete->bindParam(1, $centrale->getLibellecentrale(), PDO::PARAM_STR);
            $requete->bindParam(2, $centrale->getMasquecentrale(), PDO::PARAM_BOOL);
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
            $requete->bindParam(1, $centrale->getMasquecentrale(), PDO::PARAM_BOOL);
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
            $requete->bindParam(1, $source->getIdsourcefinancement(), PDO::PARAM_INT);
            $requete->bindParam(2, $source->getLibellesourcefinancement(), PDO::PARAM_STR);
            $requete->bindParam(3, $source->getMasquesourcefinancement(), PDO::PARAM_BOOL);
            $requete->bindParam(4, $source->getLibellesourcefinancementen(), PDO::PARAM_STR);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRSOURCEFINANCEMENT. '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }

    public function updateSourcefinancement(Sourcefinancement $sourcefinancement, $idsourcefinancement) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare("update sourcefinancement set libellesourcefinancement=?,masquesourcefinancement=?,libellesourcefinancementen=? where idsourcefinancement=?");
            $requete->bindParam(1, $sourcefinancement->getLibellesourcefinancement(), PDO::PARAM_STR);
            $requete->bindParam(2, $sourcefinancement->getMasquesourcefinancement(), PDO::PARAM_BOOL);
            $requete->bindParam(3, $sourcefinancement->getLibellesourcefinancementen(), PDO::PARAM_STR);
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
            $requete->bindParam(1, $sourcefinancement->getMasquesourcefinancement(), PDO::PARAM_BOOL);
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
            $requete->bindParam(1, $thematique->getIdthematique(), PDO::PARAM_INT);
            $requete->bindParam(2, $thematique->getLibellethematique(), PDO::PARAM_STR);
            $requete->bindParam(3, $thematique->getMasquethematique(), PDO::PARAM_BOOL);
            $requete->bindParam(4, $thematique->getLibellethematiqueen(), PDO::PARAM_STR);
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
            $requete->bindParam(1, $thematique->getLibellethematique(), PDO::PARAM_STR);
            $requete->bindParam(2, $thematique->getMasquethematique(), PDO::PARAM_BOOL);
            $requete->bindParam(3, $thematique->getLibellethematiqueen(), PDO::PARAM_STR);
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
            $requete->bindParam(1, $thematique->getMasquethematique(), PDO::PARAM_BOOL);
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
            $requete->bindParam(1, $autrethematique->getIdautrethematique(), PDO::PARAM_INT);
            $requete->bindParam(2, $autrethematique->getLibelleautrethematique(), PDO::PARAM_STR);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRUPDATETHEMATIQUE . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }
//------------------------------------------------------------------------------------------------------------
//                                                                              RESTE A FAIRE
//------------------------------------------------------------------------------------------------------------

//------------------------------------------------------------------------------------------------------------
//                                       PAYS
//------------------------------------------------------------------------------------------------------------

    public function addPays(Pays $pays) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('INSERT INTO pays (idpays,nompays,nompaysen,idsituation_situationgeographique,masquepays)VALUES(?,?,?,?,?)');
            $requete->bindParam(1, $pays->getIdpays(), PDO::PARAM_INT);
            $requete->bindParam(2, $pays->getNompays(), PDO::PARAM_STR);
            $requete->bindParam(3, $pays->getNompaysen(), PDO::PARAM_STR);
            $requete->bindParam(4, $pays->getIdsituation_situationgeographique(), PDO::PARAM_INT);
            $requete->bindParam(5, $pays->getMasquepays(), PDO::PARAM_BOOL);
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
            $requete->bindParam(1, $pays->getNompays(), PDO::PARAM_STR);
            $requete->bindParam(2, $pays->getNompaysen(), PDO::PARAM_STR);
            $requete->bindParam(3, $pays->getIdsituation_situationgeographique(), PDO::PARAM_INT);
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
            $requete->bindParam(1, $pays->getMasquepays(), PDO::PARAM_BOOL);
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
            $requete->bindParam(1, $projetpartenaire->getIdpartenaire_partenaireprojet(), PDO::PARAM_INT);
            $requete->bindParam(2, $projetpartenaire->getIdprojet_projet(), PDO::PARAM_INT);
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
            $requete->bindParam(1, $partenaireprojet->getIdpartenaire(), PDO::PARAM_INT);
            $requete->bindParam(2, $partenaireprojet->getNompartenaire(), PDO::PARAM_STR);
            $requete->bindParam(3, $partenaireprojet->getNomlaboentreprise(), PDO::PARAM_STR);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRINSERTPARTENAIREPROJET . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }//ok

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
            $requete->bindParam(1, $typeentreprise->getIdtypeentreprise(), PDO::PARAM_INT);
            $requete->bindParam(2, $typeentreprise->getLibelletypeentreprise(), PDO::PARAM_STR);
            $requete->bindParam(3, $typeentreprise->getMasquetypeentreprise(), PDO::PARAM_BOOL);
            $requete->bindParam(4, $typeentreprise->getLibelletypeentrepriseen(), PDO::PARAM_STR);
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
            $requete->bindParam(1, $typeentreprise->getLibelletypeentreprise(), PDO::PARAM_STR);
            $requete->bindParam(2, $typeentreprise->getMasquetypeentreprise(), PDO::PARAM_BOOL);
            $requete->bindParam(3, $typeentreprise->getLibelletypeentrepriseen(), PDO::PARAM_STR);
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
            $requete->bindParam(1, $typeentreprise->getMasquetypeentreprise(), PDO::PARAM_BOOL);
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
            $requete->bindParam(1, $typeprojet->getIdtypeprojet(), PDO::PARAM_INT);
            $requete->bindParam(2, $typeprojet->getLibelletypeprojet(), PDO::PARAM_STR);
            $requete->bindParam(3, $typeprojet->getMasquetypeprojet(), PDO::PARAM_BOOL);
            $requete->bindParam(4, $typeprojet->getLibelletypeprojeten(), PDO::PARAM_STR);
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
            $requete->bindParam(1, $typeprojet->getLibelletypeprojet(), PDO::PARAM_STR);
            $requete->bindParam(2, $typeprojet->getMasquetypeprojet(), PDO::PARAM_BOOL);
            $requete->bindParam(3, $typeprojet->getLibelletypeprojeten(), PDO::PARAM_STR);
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
            $requete->bindParam(1, $typeprojet->getMasquetypeprojet(), PDO::PARAM_BOOL);
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
            $requete->bindParam(1, $ressource->getIdprojet_projet(), PDO::PARAM_INT);
            $requete->bindParam(2, $ressource->getIdressource_ressource(), PDO::PARAM_INT);
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
            $requete->bindParam(1, $discipline->getIddiscipline(), PDO::PARAM_INT);
            $requete->bindParam(2, $discipline->getLibellediscipline(), PDO::PARAM_STR);
            $requete->bindParam(3, $discipline->getMasquediscipline(), PDO::PARAM_BOOL);
            $requete->bindParam(4, $discipline->getLibelledisciplineen(), PDO::PARAM_STR);
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
            $requete->bindParam(1, $discipline->getLibellediscipline(), PDO::PARAM_STR);
            $requete->bindParam(2, $discipline->getMasquediscipline(), PDO::PARAM_BOOL);
            $requete->bindParam(3, $discipline->getLibelledisciplineen(), PDO::PARAM_STR);
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
            $requete->bindParam(1, $discipline->getMasquediscipline(), PDO::PARAM_BOOL);
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
            $requete->bindParam(1, $secteuractivite->getIdsecteuractivite(), PDO::PARAM_INT);
            $requete->bindParam(2, $secteuractivite->getLibellesecteuractivite(), PDO::PARAM_STR);
            $requete->bindParam(3, $secteuractivite->getLibellesecteuractiviteen(), PDO::PARAM_STR);
            $requete->bindParam(4, $secteuractivite->getMasquesecteuractivite(), PDO::PARAM_BOOL);
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
            $requete->bindParam(1, $secteuractivite->getLibellesecteuractivite(), PDO::PARAM_STR);
            $requete->bindParam(2, $secteuractivite->getLibellesecteuractiviteen(), PDO::PARAM_STR);
            $requete->bindParam(3, $secteuractivite->getMasquesecteuractivite(), PDO::PARAM_BOOL);
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
            $requete->bindParam(1, $secteuractivite->getMasquesecteuractivite(), PDO::PARAM_BOOL);
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
            $requete = $this->_db->prepare("UPDATE utilisateur SET idemployeur_nomemployeur = ?, idautrenomemployeur_autrenomemployeur =? where idutilisateur=?");
            $requete->bindParam(1, $utilisateurNomemployeur->getIdemployeur_nomemployeur(), PDO::PARAM_INT);
            $requete->bindParam(2, $utilisateurNomemployeur->getIdautrenomemployeur_autrenomemployeur(), PDO::PARAM_INT);
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
            $requete->bindParam(1, $nomemployeur->getIdemployeur(), PDO::PARAM_INT);
            $requete->bindParam(2, $nomemployeur->getLibelleemployeur(), PDO::PARAM_STR);
            $requete->bindParam(3, $nomemployeur->getMasquenomemployeur(), PDO::PARAM_BOOL);
            $requete->bindParam(4, $nomemployeur->getLibelleemployeuren(), PDO::PARAM_STR);
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
            $requete->bindParam(1, $nomemployeur->getLibelleemployeur(), PDO::PARAM_STR);
            $requete->bindParam(2, $nomemployeur->getMasquenomemployeur(), PDO::PARAM_BOOL);
            $requete->bindParam(3, $nomemployeur->getLibelleemployeuren(), PDO::PARAM_STR);
            $requete->bindParam(4, $idemployeur, PDO::PARAM_INT);
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
            $requete->bindParam(1, $projetattachement->getAttachement(), PDO::PARAM_STR);
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
            $requete->bindParam(1, $projetattachementdesc->getattachementdesc(), PDO::PARAM_STR);
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
            $requete->bindParam(1, $projetcontextedescriptif->getDescription(), PDO::PARAM_STR);
            $requete->bindParam(2, $projetcontextedescriptif->getContexte(), PDO::PARAM_STR);
            $requete->bindParam(3, $projetcontextedescriptif->getConfidentiel(), PDO::PARAM_BOOL);
            $requete->bindParam(4, $projetcontextedescriptif->getTitre(), PDO::PARAM_STR);
            $requete->bindParam(5, $projetcontextedescriptif->getAcronyme(), PDO::PARAM_STR);
            $requete->bindParam(6, $projetcontextedescriptif->getAttachement(), PDO::PARAM_STR);
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
            $requete->bindParam(1, $tutelle->getIdtutelle(), PDO::PARAM_INT);
            $requete->bindParam(2, $tutelle->getLibelletutelle(), PDO::PARAM_STR);
            $requete->bindParam(3, $tutelle->getMasquetutelle(), PDO::PARAM_BOOL);
            $requete->bindParam(4, $tutelle->getLibelletutelleen(), PDO::PARAM_STR);
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
            $requete->bindParam(1, $tutelle->getLibelletutelle(), PDO::PARAM_STR);
            $requete->bindParam(2, $tutelle->getMasquetutelle(), PDO::PARAM_BOOL);
            $requete->bindParam(3, $tutelle->getLibelletutelleen(), PDO::PARAM_STR);
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
            $requete->bindParam(1, $tutelle->getMasquetutelle(), PDO::PARAM_BOOL);
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
            $requete->bindParam(1, $autrestutelle->getIdautrestutelle(), PDO::PARAM_INT);
            $requete->bindParam(2, $autrestutelle->getLibelleautrestutelle(), PDO::PARAM_STR);
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
            $requete->bindParam(1, $autrenomemployeur->getIdautrenomemployeur(), PDO::PARAM_INT);
            $requete->bindParam(2, $autrenomemployeur->getLibelleautrenomemployeur(), PDO::PARAM_STR);
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
            $requete->bindParam(1, $nomemployeur->getMasquenomemployeur(), PDO::PARAM_BOOL);
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
            $requete->bindParam(1, $autrecodeunite->getIdautrecodeunite(), PDO::PARAM_INT);
            $requete->bindParam(2, $autrecodeunite->getLibelleautrecodeunite(), PDO::PARAM_STR);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRAUTRECODEUNITE . '<br>' . $exc->getLine();
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
            $requete->bindParam(1, $autredisciplinescientifique->getIdautrediscipline(), PDO::PARAM_INT);
            $requete->bindParam(2, $autredisciplinescientifique->getLibelleautrediscipline(), PDO::PARAM_STR);
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
            $requete->bindParam(1, $utilisateurAcronyme->getAcronymelaboratoire(), PDO::PARAM_STR);
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
            $requete->bindParam(1, $appartient->getIdtypeentreprise_typeentreprise(), PDO::PARAM_INT);
            $requete->bindParam(2, $appartient->getIdutilisateur_utilisateur(), PDO::PARAM_INT);
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
            $requete->bindParam(1, $appartient->getIdtypeentreprise_typeentreprise(), PDO::PARAM_INT);
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
            $requete->bindParam(1, $intervient->getIdsecteuractivite_secteuractivite(), PDO::PARAM_INT);
            $requete->bindParam(2, $intervient->getIdutilisateur_utilisateur(), PDO::PARAM_INT);
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
            $requete->bindParam(1, $intervient->getIdsecteuractivite_secteuractivite(), PDO::PARAM_INT);
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
            $requete = $this->_db->prepare("INSERT INTO concerne (idcentrale_centrale,idprojet_projet,idstatutprojet_statutprojet,commentaireprojet)
	       VALUES(?,?,?,?)");
            $requete->bindParam(1, $concerne->getIdcentrale_centrale(), PDO::PARAM_INT);
            $requete->bindParam(2, $concerne->getIdprojet_projet(), PDO::PARAM_INT);
            $requete->bindParam(3, $concerne->getIdstatutprojet_statutprojet(), PDO::PARAM_INT);
            $requete->bindParam(4, $concerne->getCommentaireProjet(), PDO::PARAM_STR);
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
            $requete->bindParam(1, $concerne->getIdstatutprojet_statutprojet(), PDO::PARAM_INT);
            $requete->bindParam(2, $concerne->getCommentaireProjet(), PDO::PARAM_STR);
            $requete->bindParam(3, $concerne->getIdcentrale_centrale(), PDO::PARAM_INT);
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
            $requete->bindParam(1, $concernephase1->getIdstatutprojet_statutprojet(), PDO::PARAM_INT);
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
            $requete->bindParam(1, $datedebut->getDatedebutprojet(), PDO::PARAM_STR);
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
            $requete->bindParam(1, $datefini->getDateStatutFiniProjet(), PDO::PARAM_STR);
            $requete->bindParam(2, $idprojet, PDO::PARAM_INT);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRUPDATEPROJET . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }//ok

//------------------------------------------------------------------------------------------------------------
//                      METHODE PROJETDATECLOTURER
//------------------------------------------------------------------------------------------------------------
    public function updateDateStatutCloturer(DateStatutCloturerProjet $datecloturer, $idprojet) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('UPDATE projet SET  datestatutcloturer =? WHERE idprojet =?');
            $requete->bindParam(1, $datecloturer->getDateStatutCloturerProjet(), PDO::PARAM_STR);
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
            $requete->bindParam(1, $daterefus->getDaterefusprojet(), PDO::PARAM_STR);
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
            $requete->bindParam(1, $creer->getIdutilisateur_utilisateur(), PDO::PARAM_INT);
            $requete->bindParam(2, $creer->getIdprojet_projet(), PDO::PARAM_INT);
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
            $requete = $this->_db->prepare('INSERT INTO personneaccueilcentrale (idpersonneaccueilcentrale,nomaccueilcentrale,prenomaccueilcentrale,idqualitedemandeuraca_qualitedemandeuraca,mailaccueilcentrale,
    telaccueilcentrale,connaissancetechnologiqueaccueil)VALUES (?,?,?,?,?,?,?)  ');
            $requete->bindParam(1, $personne->getIdpersonneaccueilcentrale(), PDO::PARAM_INT);
            $requete->bindParam(2, $personne->getNomaccueilcentrale(), PDO::PARAM_STR);
            $requete->bindParam(3, $personne->getPrenomaccueilcentrale(), PDO::PARAM_STR);
            $requete->bindParam(4, $personne->getIdqualitedemandeuraca_qualitedemandeuraca(), PDO::PARAM_INT);
            $requete->bindParam(5, $personne->getMailaccueilcentrale(), PDO::PARAM_STR);
            $requete->bindParam(6, $personne->getTelaccueilcentrale(), PDO::PARAM_STR);
            $requete->bindParam(7, $personne->getConnaissancetechnologiqueaccueil(), PDO::PARAM_STR);
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
            $requete->bindParam(1, $projet->getIdprojet_projet(), PDO::PARAM_INT);
            $requete->bindParam(2, $projet->getIdpersonneaccueilcentrale_personneaccueilcentrale(), PDO::PARAM_INT);
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
            $requete->bindParam(1, $ressource->getIdressource(), PDO::PARAM_INT);
            $requete->bindParam(2, $ressource->getLibelleressource(), PDO::PARAM_STR);
            $requete->bindParam(3, $ressource->getMasqueressource(), PDO::PARAM_BOOL);
            $requete->bindParam(4, $ressource->getLibelleressourceen(), PDO::PARAM_STR);
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
            $requete->bindParam(1, $ressource->getLibelleressource(), PDO::PARAM_STR);
            $requete->bindParam(2, $ressource->getMasqueressource(), PDO::PARAM_BOOL);
            $requete->bindParam(3, $ressource->getLibelleressourceen(), PDO::PARAM_STR);
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
            $requete->bindParam(1, $ressource->getMasqueressource(), PDO::PARAM_BOOL);
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
            $requete->bindParam(1, $libelleapplication->getLibellefrancais(), PDO::PARAM_STR);
            $requete->bindParam(2, $libelleapplication->getLibelleanglais(), PDO::PARAM_STR);
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
            $requete->bindParam(1, $sitewebapplication->getAdressesitewebcentrale(), PDO::PARAM_STR);
            $requete->bindParam(2, $refsiteweb, PDO::PARAM_STR);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERRUPDATESITEWEB . '<br>' . $exc->getLine();
            $this->_db->rollBack();
        }
    }//ok

//------------------------------------------------------------------------------------------------------------
//                      TYPE FORMATION
//------------------------------------------------------------------------------------------------------------

    public function addTypeFormation(Typeformation $typeformation) { //ajout d'un type dans la liste
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('INSERT INTO typeformation (idtypeformation,libelletypeformation,masquetypeformation,libelletypeformationen)VALUES(?,?,?,?) ');
            $requete->bindParam(1, $typeformation->getIdtypeformation(), PDO::PARAM_INT);
            $requete->bindParam(2, $typeformation->getLibelletypeformation(), PDO::PARAM_STR);
            $requete->bindParam(3, $typeformation->getMasquetypeformation(), PDO::PARAM_BOOL);
            $requete->bindParam(4, $typeformation->getLibelletypeformationen(), PDO::PARAM_STR);
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
            $requete->bindParam(1, $typeformation->getLibelletypeformation(), PDO::PARAM_INT);
            $requete->bindParam(2, $typeformation->getLibelletypeformationen(), PDO::PARAM_INT);
            $requete->bindParam(3, $typeformation->getMasquetypeformation(), PDO::PARAM_BOOL);
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
            $requete->bindParam(1, $typeformation->getMasquetypeformation(), PDO::PARAM_BOOL);
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
            $requete->bindParam(1, $projettypeprojet->getIdtypeformation(), PDO::PARAM_INT);
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
            $requete->bindParam(1, $projettypeprojet->getIdtypeformation(), PDO::PARAM_INT);
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
//              COMPTEUR
//-----------------------------------------------------------------------------------------------------------    
    public function updatecompteur(Compteur $compteur, $var) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('UPDATE compteur SET c_total = ?,c_lastvisit=? WHERE c_login != ?');
            $requete->bindParam(1, $compteur->getC_total(), PDO::PARAM_INT);
            $requete->bindParam(2, $compteur->getClastvisit(), PDO::PARAM_STR);
            $requete->bindParam(3, $var, PDO::PARAM_STR);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERR . '<br>Ligne ' . $exc->getLine().'<br>'.  $exc->getMessage();
            $this->_db->rollBack();
        }
    }
    public function deletecompteur(Compteur $compteur) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('DELETE FROM compteur WHERE c_login != ? AND extract(epoch from(c_firstvisit)) < ?');
            $requete->bindParam(1, $compteur->getClogin(), PDO::PARAM_INT);
            $requete->bindParam(2, $compteur->getClastvisit(), PDO::PARAM_STR);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERR . '<br>Ligne ' . $exc->getLine().'<br>'.  $exc->getMessage();
            $this->_db->rollBack();
        }
    }
    
    public function addcompteur(Compteur $compteur) {
        try {
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();
            $requete = $this->_db->prepare('INSERT INTO compteur (c_firstvisit, c_lastvisit, c_total, c_login, c_time) VALUES (?,?,?,?,?)');
            $requete->bindParam(1, $compteur->getCfirstvisit(), PDO::PARAM_INT);
            $requete->bindParam(2, $compteur->getClastvisit(), PDO::PARAM_INT);
            $requete->bindParam(3, $compteur->getC_total(), PDO::PARAM_INT);
            $requete->bindParam(4, $compteur->getClogin(), PDO::PARAM_STR);
            $requete->bindParam(5, $compteur->getCtime(), PDO::PARAM_STR);
            $requete->execute();
            $this->_db->commit();
        } catch (Exception $exc) {
            echo TXT_ERR . '<br>Ligne ' . $exc->getLine().'<br>'.  $exc->getMessage();
            $this->_db->rollBack();
        }
    }
    
//-----------------------------------------------------------------------------------------------------------
//              SELECT
//-----------------------------------------------------------------------------------------------------------    
    public function getRequete($request, array $param) {
        $requete = $this->_db->prepare($request);
        $requete->execute($param);
    }

    public function exeRequete($request) {
        $requete = $this->_db->prepare($request);
        $requete->execute();
    }

    public function getSingle($request) {
        $requete = $this->_db->prepare($request);
        $requete->execute();
        return $requete->fetch(PDO::FETCH_COLUMN);
    }

    public function getSingle2($request, $param) {
        $requete = $this->_db->prepare($request);
        $requete->execute(array($param));
        return $requete->fetch(PDO::FETCH_COLUMN);
    }

    public function getSinglebyArray($request, array $param) {
        $requete = $this->_db->prepare($request);
        $requete->execute($param);
        return $requete->fetch(PDO::FETCH_COLUMN);
    }

    public function getList($request) {
        $requete = $this->_db->prepare($request);
        $requete->execute();
        return $requete->fetchAll();
    }

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
