<?php

Class gestionCas{   
    
    public static function choixCas($save=null,$chgstatut=null,$etapeautrecentrale=null,$majcentrale=null,$sendmail=null,$emailNon=null,$maj=null){
        $cas = 'undefined';
        $cas1 = 'undefined';
        if ($save == 'oui' && $maj == 'non') {//ENREGISTREMENT AVEC OU SANS AUTRE CENTRALE 
            $cas = 'enregistrement';            
        } elseif ($save == 'non' && $maj == 'oui' && $majcentrale == 'oui') {
            //MISE A JOUR avec autre centrale déja envoyé
            $cas = 'miseAJourEmailAutreCentrale';
        } elseif ($save == 'non' && $maj == 'oui' && $majcentrale == 'non' && $etapeautrecentrale == 'TRUE' && $sendmail == FALSE) {
            //MISE A JOUR AVEC AUTRE CENTRALE 1ER FOIS 
            $cas = 'miseAJourEmailautreEmailpremierefois'; //--> TO BE DONE
        } elseif ($save == 'non' && $maj == 'oui' && $majcentrale == 'non' && $sendmail == TRUE) {
            //REPONDU NON A L'ENVOI D'UN EMAIL AUX AUTRES CENTRALES
            $cas = 'miseAJourEmail';
        } elseif ($save == 'non' && $maj == 'oui' && $majcentrale == 'non' && $etapeautrecentrale == 'FALSE') {
            //MISE A JOUR SANS AUTRE CENTRALE
            $cas = 'miseAJourEmail';
        }elseif ($save == 'non' && $maj == 'non' & !isset($chgstatut)) {//VALIDATION APRES UNE SAUVEGARDE
            if ($etapeautrecentrale == 'TRUE') {//VALIDATION AVEC UNE ETAPE AUTRE CENTRALE --> OK VERIFER
                $cas = 'creationprojetphase2etape';
            } elseif ($etapeautrecentrale == 'FALSE' && !isset($chgstatut)) {//VALIDATION SANS UNE ETAPE AUTRE CENTRALE --> OK VERIFER
                $cas = 'creerprojetphase2';
            }
        }/*elseif ($etapeautrecentrale == 'FALSE' && $majcentrale == 'oui') {//  CAS PARTICULIER DE LA VALIDATION, SI ON VALIDE UN PROJET AVEC UNE AUTRE CENTRALE SUR LEQUEL L'ADMINLOCAL ENLEVE L'AUTRE CENTRALE on envoi pas le message au autres centrales            
            $cas = 'miseAJourEmail';
        }*/elseif ( $chgstatut == 'oui' && $etapeautrecentrale == 'TRUE' && $majcentrale == 'oui') {
            //CHANGEMENT DE STATUT ON A CLIQUER SUR OUI POUR RENVOYER L'EMAIL AUX AUTRES CENTRALES QU'ON A DEJA ENVOYE
            $cas = 'chgstatutAutCentraleEmailDejaEnvoye';
        } elseif ($chgstatut == 'oui' && $etapeautrecentrale == 'TRUE' && $majcentrale == 'non' && $sendmail == TRUE) {
            //CHANGEMENT DE STATUT ON A CLIQUER SUR NON POUR  NE PAS RENVOYER L'EMAIL AUX AUTRES CENTRALES QU'ON A DEJA ENVOYE
            $cas = 'chgstatutAutCentraleEmailDejaEnvoyeNon';
        } elseif ($chgstatut == 'oui' && $etapeautrecentrale == 'TRUE' && $majcentrale == 'non' && $sendmail == FALSE) {
            //CHANGEMENT DE STATUT AVEC AJOUT D'UNE ETAPE DANS UNE AUTRES CENTRALE POUR LA 1ER FOIS  
            $cas = 'chgstatutAutCentraleEmailJammaisEnvoye';
        } elseif ($chgstatut == 'oui' && $etapeautrecentrale == 'FALSE' && $emailNon=='oui') {
            //CHANGEMENT DE STATUT, PAS D'ETAPE DANS UNE AUTRE CENTRALE
            $cas = 'chgstatut';
        } elseif ($chgstatut == 'oui' && $etapeautrecentrale == 'FALSE' && $emailNon=='non') {
            $cas = 'chgstatut';
            $cas1 = 'chgstatutnoEmail';
        }
        $result=array($cas,$cas1);
        return $result;
    }
}


      
        //----------------------------------------------------------------------------------------------------------------------------------------------------------        
        //                                            MISE A JOUR
        //----------------------------------------------------------------------------------------------------------------------------------------------------------        
        