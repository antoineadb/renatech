<?php

function responsable($mail) {
    if (!empty($mail)) {
        $reqemailcentraleRespCent = "select idcentrale from centrale where email1='" . $mail . "'";
        $resemailcentraleRespCent = pg_query($reqemailcentraleRespCent);
        if (!$resemailcentraleRespCent) {
            echo "Une erreur s'est produite.\n";
            exit;
        }
        else {
            $rowemailcentraleRespCent = pg_fetch_row($resemailcentraleRespCent);
            $email1                   = $rowemailcentraleRespCent[0];
            if (!empty($email1)) {
                return true;
                //$idtypeutilisateur_typeutilisateur='4'; //responsable centrale                
            }
            else {
                $idtypeutilisateur_typeutilisateur = '6'; //utilisateur
                //return false;
            }
        }
    }
}

?>