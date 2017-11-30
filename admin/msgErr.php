<?php

//TYPE PROJET
if (!empty($_GET['msgserveurtypeprojet'])) {
    echo TXT_MESSAGESERVEURTYPEPROJET;
} elseif (!empty($_GET['msgErrtypeProjetnonsaisie'])) {
    echo TXT_MESSAGEERREURTYPEPROJETNONSAISIE;
} elseif (!empty($_GET['msgErrtypeexiste'])) {
    echo TXT_MESSAGESERVEURTYPEEXISTE;
} elseif (!empty($_GET['msgErrtypeprojetselect'])) {
    echo TXT_MESSAGEERREURGETYPEPROJETSELECT;
} elseif (!empty($_GET['msgtypeprojetupdate'])) {
    echo TXT_MESSAGETYPEPROJETUPDATE;
} elseif (!empty($_GET['msgserveurtypeprojethide'])) {
    echo TXT_MESSAGESERVEURTYPEPROJETMASQUER;
} elseif (!empty($_GET['msgserveurtypeprojetshow'])) {
    echo TXT_MESSAGESERVEURTYPEPROJETAFFICHE;
//TYPE  FORMATION
} elseif (!empty($_GET['msgErrtypeformationnonsaisie'])) {
    echo TXT_MESSAGEERREURTYPEFORMATIONNONSAISIE;
} elseif (!empty($_GET['msgErrtypeformationexiste'])) {
    echo TXT_MESSAGESERVEURTYPEFORMATIONEXISTE;
} elseif (!empty($_GET['msgserveurtypeformation'])) {
    echo TXT_MESSAGESERVEURTYPEFORMATION;
} elseif (!empty($_GET['msgErrtypeformationselect'])) {
    echo TXT_MESSAGEERREURGETYPEFORMATIONSELECT;
} elseif (!empty($_GET['msgtypeformationupdate'])) {
    echo TXT_MESSAGETYPEFORMATIONUPDATE;
} elseif (!empty($_GET['msgserveurtypeformationhide'])) {
    echo TXT_MESSAGESERVEURTYPEFORMATIONMASQUER;
} elseif (!empty($_GET['msgserveurtypeformationshow'])) {
    echo TXT_MESSAGESERVEURTYPEFORMATIONAFFICHE;
//THEMATIQUE
} elseif (!empty($_GET['msgErrthematiquenonsaisie'])) {
    echo TXT_MESSAGEERREURTHEMATIQUENONSAISIE;
} elseif (!empty($_GET['msgErrthematiqueexiste'])) {
    echo TXT_MESSAGESERVEURTHEMATIQUEEXISTE;
} elseif (!empty($_GET['msgserveurthematique'])) {
    echo TXT_MESSAGESERVEURTHEMATIQUE;
} elseif (!empty($_GET['msgErrthematiqueselect'])) {
    echo TXT_MESSAGEERREURTHEMATIQUESELECT;
} elseif (!empty($_GET['msgthematiqueupdate'])) {
    echo TXT_MESSAGETHEMATIQUEUPDATE;
} elseif (!empty($_GET['msgserveurthematiquehide'])) {
    echo TXT_MESSAGESERVEURTHEMATIQUEMASQUER;
} elseif (!empty($_GET['msgserveurthematiqueshow'])) {
    echo TXT_MESSAGESERVEURTHEMATIQUEAFFICHE;
//STATUT DU PROJET
} elseif (!empty($_GET['msgErrstatutselect'])) {
    echo TXT_MESSAGEERREURSTATUTSELECT;
} elseif (!empty($_GET['msgErrstatutnonsaisie'])) {
    echo TXT_MESSAGEERREURSTATUTNONSAISIE;
} elseif (!empty($_GET['msgErrstatutexiste'])) {
    echo TXT_MESSAGESERVEURSTATUTEXISTE;
} elseif (!empty($_GET['msgupdatestatut'])) {
    echo TXT_MESSAGESTAUTUPDATE;
} elseif (!empty($_GET['msgserveurstatut'])) {
    echo TXT_MESSAGESERVEURSTATUT;
}
//VILLE PAYS
if (!empty($_GET['msgErrVil'])) {
    echo TXT_MESSAGEERREURVILLENONSAISIE;
} elseif (!empty($_GET['msgErrpays'])) {
    echo TXT_MESSAGEERREURPAYSNONSAISIE;
} elseif (!empty($_GET['msgErrpaysnonsaisie'])) {
    echo TXT_MESSAGEERREURPAYSNONSAISIE;
} elseif (!empty($_GET['msgErrpaysennonsaisie'])) {
    echo TXT_MESSAGEERREURPAYSENNONSAISIE;
} elseif (!empty($_GET['msgErrpaysexiste'])) {
    echo TXT_MESSAGESERVEURPAYSEXISTE;
} elseif ((!empty($_GET['msgErrpaysennonsaisie']))) {
    echo TXT_MESSAGEERREURPAYSENNONSAISIE;
} elseif (!empty($_GET['msgserveurpays'])) {
    echo TXT_MESSAGESERVEURPAYS;
} elseif (!empty($_GET['msgErrpaysselect'])) {
    echo TXT_MESSAGEERREURPAYSENONSELECT;
} elseif (!empty($_GET['msgpaysupdate'])) {
    echo TXT_MESSAGESERVEURUPDATEPAYS;
} elseif (!empty($_GET['msgserveurpayshide'])) {
    echo TXT_MESSAGESERVEURPAYSMASQUER;
} elseif (!empty($_GET['msgserveurpaysshow'])) {
    echo TXT_MESSAGESERVEURPAYSAFFICHE;
// SITUATION GEOGRAPHIQUE
} elseif (!empty($_GET['msgErrsituationselect'])) {
    echo TXT_MESSAGEERREURGEONONSELECT;
//SECTEUR ACTIVITE
} elseif (!empty($_GET['msgErrSecteurnonsaisie'])) {
    echo TXT_MESSAGEERREURSECTEURNONSAISIE;
} elseif (!empty($_GET['msgErrSecteurennonsaisie'])) {
    echo TXT_MESSAGEERREURSECTEURNONSAISIE;
} elseif (!empty($_GET['msgErrsecteurselect'])) {
    echo TXT_MESSAGEERREURGESECTEURONSELECT;
} elseif (!empty($_GET['msgErrSecteurexiste'])) {
    echo TXT_MESSAGESERVEURSECTEUREXISTE;
} elseif (!empty($_GET['msgserveursecteur'])) {
    echo TXT_MESSAGESERVEURSETCEURACTIVITE;
} elseif (!empty($_GET['msgsecteurupdate'])) {
    echo TXT_MESSAGESEECTEURUPDATE;
} elseif (!empty($_GET['msgErrtypeentreselect'])) {
    echo TXT_MESSAGEERREURSELECTSECTEURACTIVITE;
} elseif (!empty($_GET['msgErrtypeEntreprisenonsaisie'])) {
    echo TXT_MESSAGEERREURSECTEURACTIVITENONSAISIE;
} elseif (!empty($_GET['msgErrsecteuractiviteexiste'])) {
    echo TXT_MESSAGEERREURSECTEURACTIVITEEXISTE;
} elseif (!empty($_GET['msgserveursecteuractivitehide'])) {
    echo TXT_MESSAGESERVEURSECTEURACTIVITEMASQUER;
} elseif (!empty($_GET['msgserveursecteuractiviteshow'])) {
    echo TXT_MESSAGESERVEURSECTEURACTIVITEAFFICHE;
//TYTE ENTREPRISE
} elseif (!empty($_GET['msgErrtypeEntreprisenonsaisie'])) {
    echo TXT_MESSAGEERREURTYPEENTREPRISENONSAISIE;
} elseif (!empty($_GET['msgErrtypeentrepriseexiste'])) {
    echo TXT_MESSAGESERVEURTYPEENTREPRISEEXISTE;
} elseif (!empty($_GET['msgserveurtypeentreprise'])) {
    echo TXT_MESSAGESERVEURTYPEENTREPRISE;
} elseif (!empty($_GET['msgErrtypeentreselect'])) {
    echo TXT_MESSAGEERREURTYPEENTRESELECT;
} elseif (!empty($_GET['msgserveurtypeentrepriseupdate'])) {
    echo TXT_MESSAGESERVEURTYPEENTREPRISEUPDATE;
} elseif (!empty($_GET['msgserveurtypeentreprisehide'])) {
    echo TXT_MESSAGESERVEURTYPEENTREPRISEMASQUER;
} elseif (!empty($_GET['msgserveurtypeentrepriseshow'])) {
    echo TXT_MESSAGESERVEURTYPEENTREPRISEAFFICHE;
}

//TYTE partenaire
elseif (!empty($_GET['msgErrtypepartenaireenonsaisie'])) {
    echo TXT_MESSAGEERREURTYPEPARTENAIREENONSAISIE;
} elseif (!empty($_GET['msgErrtypepartenaireeexiste'])) {
    echo TXT_MESSAGESERVEURTYPEPARTENAIREEXISTE;
} elseif (!empty($_GET['msgserveurtypepartenairee'])) {
    echo TXT_MESSAGESERVEURTYPEPARTENAIRE;
} elseif (!empty($_GET['msgErrtypepartenaireselect'])) {
    echo TXT_MESSAGEERREURTYPEPARTENAIRESELECT;
} elseif (!empty($_GET['msgserveurtypepartenaireeupdate'])) {
    echo TXT_MESSAGESERVEURTYPEPARTENAIREUPDATE;
} elseif (!empty($_GET['msgserveurtypepartenaireehide'])) {
    echo TXT_MESSAGESERVEURTYPEPARTENAIREMASQUER;
} elseif (!empty($_GET['msgserveurtypepartenaireshow'])) {
    echo TXT_MESSAGESERVEURTYPEPARTENAIREMASQUER;
} elseif (!empty($_GET['msgserveurtypepartenairehide'])) {
    echo TXT_MESSAGESERVEURTYPEPARTENAIREAFFICHE;
} elseif (!empty($_GET['msgserveurtypepartenaire'])) {
    echo TXT_MESSAGESERVEURTYPEPARTENAIRE;
} elseif (!empty($_GET['msgserveurtypepartenaireupdate'])) {
    echo TXT_MESSAGESERVEURTYPEPARTENAIREUPDATE;
}

//DISCIPLINE SCIENTIFIQUE
elseif ((!empty($_GET['msgErrdisciplinenonsaisie']))) {
    echo TXT_MESSAGEERREURDISCIPLINENONSAISIE;
} elseif ((!empty($_GET['msgserveurdiscipline']))) {
    echo TXT_MESSAGESERVEURDISCIPLINE;
} elseif ((!empty($_GET['msgdisciplineupdate']))) {
    echo TXT_MESSAGEDISCIPLINEUPDATE;
} elseif ((!empty($_GET['msgserveurnomemployeur']))) {
    echo TXT_MESSAGESERVEUREMPLOYEUR;
} elseif (!empty($_GET['msgErrdisciplineselect'])) {
    echo TXT_MESSAGEERREURDISCIPLINESELECT;
} elseif (!empty($_GET['msgserveurdisciplinehide'])) {
    echo TXT_MESSAGESERVEURDISCIPLINEMASQUER;
} elseif (!empty($_GET['msgserveurtypedisciplineshow'])) {
    echo TXT_MESSAGESERVEURDISCIPLINAFFICHE;
} elseif (!empty($_GET['msgErrdisciplineexiste'])) {
    echo TXT_MESSAGESERVEURDISCIPLINEEXISTE;
//NOM EMPLOYEUR
} elseif (!empty($_GET['msgemployeurupdate'])) {
    echo TXT_MESSAGEEMPLOYEURUPDATE;
} elseif (!empty($_GET['msgErremployeurnonsaisie'])) {
    echo TXT_MESSAGEERREURNOMEMPOYEURNONSAISIE;
} elseif (!empty($_GET['msgserveurnomemployeurhide'])) {
    echo TXT_MESSAGESERVEURNOMEMPLOYEURMASQUER;
} elseif (!empty($_GET['msgserveurnomemployeurshow'])) {
    echo TXT_MESSAGESERVEURNOMEMPLOYEURAFFICHE;
} elseif (!empty($_GET['msgErrnomemployeurexiste'])) {
    echo TXT_MESSAGESERVEUREMPLOYEUREXISTE;
//TUTELLE
} elseif (!empty($_GET['msgErrtutelleselect'])) {
    echo TXT_MESSAGEERREURTUTELLESELECT;
} elseif (!empty($_GET['msgErrtutellenonsaisie'])) {
    echo TXT_MESSAGEERREURTUTELLENONSAISIE;
} elseif (!empty($_GET['msgErrtutelleexiste'])) {
    echo TXT_MESSAGESERVEURTUTELLEEXISTE;
} elseif (!empty($_GET['msgTutelleupdate'])) {
    echo TXT_MESSAGETUTELLEUPDATE;
} elseif (!empty($_GET['msgserveurtutelle'])) {
    echo TXT_MESSAGESERVEURTUTELLE;
} elseif (!empty($_GET['msgserveurtutellehide'])) {
    echo TXT_MESSAGESERVEURTUTELLEMASQUER;
} elseif (!empty($_GET['msgserveurtutelleshow'])) {
    echo TXT_MESSAGESERVEURTUTELLEAFFICHE;
//RESSOURCE
} elseif (!empty($_GET['msgserveurressourcehide'])) {
    echo TXT_MESSAGESERVEURRESSOURCEMASQUER;
} elseif (!empty($_GET['msgserveurressourceshow'])) {
    echo TXT_MESSAGESERVEURRESSOURCEAFFICHE;
} elseif (!empty($_GET['msgErrressourcenonsaisie'])) {
    echo TXT_MESSAGEERREURRESSOURCENONSAISIE;
} elseif (!empty($_GET['msgErrressourceexiste'])) {
    echo TXT_MESSAGESERVEURRESSOURCEEXISTE;
} elseif (!empty($_GET['msgserveurressource'])) {
    echo TXT_MESSAGESERVEURRESSOURCE;
} elseif (!empty($_GET['msgErrressourceselect'])) {
    echo TXT_MESSAGEERREURRESSOURCESELECT;
} elseif (isset($_GET['msgRessourceupdate'])) {
    echo TXT_MESSAGERESSOURCEUPDATE;
//CENTRALE
} elseif (!empty($_GET['msgserveurcentralehide'])) {
    echo TXT_MESSAGESERVEURCENTRALEMASQUER;
} elseif (!empty($_GET['msgserveurtypecentraleshow'])) {
    echo TXT_MESSAGESERVEURCENTRALEAFFICHE;
} elseif (!empty($_GET['msgErrCentupdate'])) {
    echo TXT_MESSAGESERVEURUPDATECENTRALE;
} elseif (!empty($_GET['msgErrCentselect'])) {
    echo TXT_MESSAGEERREURCENTRALENONSELECT;
} elseif (!empty($_GET['&msginsertcentrale'])) {
    echo TXT_MESSAGESERVEURCENTRALE;
} elseif (isset($_GET['msgErrCentExist'])) {
    echo TXT_MESSAGESERVEURCENTRALEEXISTE;
} elseif (!empty($_GET['msginsertcentrale'])) {
    echo TXT_MESSAGESERVEURCENTRALE;
} elseif (!empty($_GET['msgErrvilleselect'])) {
    echo TXT_MESSAGEERREURVILLENONSELECT;
} elseif (!empty($_GET['msgErrvillenonsaisie'])) {
    echo TXT_MESSAGEERREURVILLENONSAISIE;
} elseif (!empty($_GET['msgErrvilleexiste'])) {
    echo TXT_MESSAGESERVEURVILLEEXISTE;
} elseif (!empty($_GET['msgVilleupdate'])) {
    echo TXT_MESSAGEVILLEUPDATE;
} elseif (isset($_GET['msgErrcentralemaileselect'])) {
    echo TXT_MESSAGEERREUREMAILCENTRALESELECT;
} elseif (isset($_GET['msgErrcentralemailenomsaisie'])) {
    echo TXT_MESSAGEERREUREMAILCENTRALENONSAISIE;
} elseif (isset($_GET['msgEmailcentralupdate'])) {
    echo TXT_MESSAGEEMAILCENTRALEUPDATE;
} elseif (isset($_GET['msgCodeunitecentralupdate'])) {
    echo TXT_MESSAGECODEUNITECENTRALEUPDATE;
} elseif (isset($_GET['msgErrcentralcodeunitenomsaisie'])) {
    echo TXT_MESSAGEERREURCODEUNITECENTRALENONSAISIE;
} elseif (isset($_GET['msgErrcentralcodeuniteselect'])) {
    echo TXT_MESSAGEERREURECODEUNITECENTRALESELECT;
} elseif (isset($_GET['erreurctrladmin'])) {
    echo TXT_MESSAGEERREURENOSELECT;
} elseif (isset($_GET['msgErrCentrale'])) {
    echo TXT_MESSAGEERREURCENTRALENONSAISIE;
}//SOURCE DE FINANCEMENT
elseif (isset($_GET['msgserveursourcefinancementhide'])) {
    echo TXT_MESSAGESERVEURSOURCEFINANCEMENTMASQUER;
} elseif (isset($_GET['msgserveurtypesourcefinancementshow'])) {
    echo TXT_MESSAGESERVEURSOURCEFINANCEMENTAFFICHE;
} elseif (isset($_GET['msgErrsourcefinancementnonsaisie'])) {
    echo TXT_MESSAGEERREURSOURCEFINANCEMENTNONSAISIE;
} elseif (isset($_GET['msgErridsourcefinancementexiste'])) {
    echo TXT_MESSAGESERVEURSOURCEFINANCEMENTEXISTE;
} elseif (isset($_GET['msgserveursourcefinancement'])) {
    echo TXT_MESSAGESERVEURSOURCEFINANCEMENT;
} elseif (isset($_GET['msgErrtsourcefinancementselect'])) {
    echo TXT_MESSAGEERREURSOURCEFINANCEMENTSELECT;
} elseif (isset($_GET['msgsourcefinancementupdate'])) {
    echo TXT_MESSAGESOURCEFINANCEMENTUPDATE;
}elseif (isset($_GET['mcp'])) {
    echo 'La centrale de proximité est masquée';
}elseif (isset($_GET['acp'])) {
    echo 'La centrale de proximité est de nouveau disponible';
}elseif (isset($_GET['ar'])) {
    echo 'La Région a été ajoutée';
}elseif (isset($_GET['mr'])) {
    echo 'La Région a été Modifié';
}elseif (isset($_GET['mreg'])) {
    echo 'La Région a été masqué';
}elseif (isset($_GET['areg'])) {
    echo 'La Région a été de nouveau disponible';
}elseif (isset($_GET['cpns'])) {
    echo TXT_MESSAGEERREURCPNONSAISIE ;
}elseif (isset($_GET['rens'])) {
    echo TXT_MESGERRREGIONNONSAISIE ;
}elseif (isset($_GET['cpexist'])) {
    echo TXT_MESGERRCPEXISTE ;
}elseif (isset($_GET['cpadd'])) {
    echo TXT_MESSAGESERVEURCP ;
}elseif (isset($_GET['cpupdate'])) {
    echo TXT_MESSAGESERVEURUPDATECENTRALEP ;
}
