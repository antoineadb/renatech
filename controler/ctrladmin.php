<?php

include_once	'../outils/constantes.php';
//PAYS
if (!empty($_POST['masquepays']) && !empty($_POST['idlibellepaysactuel']) && !empty($_POST['modifpays'])) {
    include '../modifBase/affichemasquepays.php';
} elseif (!empty($_POST['affichepays']) && !empty($_POST['modifpays']) && !empty($_POST['idlibellepaysactuel'])) {
    include '../modifBase/affichemasquepays.php';
} elseif (!empty($_POST['modifpays']) && empty($_POST['idlibellepaysactuel'])) {
    include '../modifBase/insertPays.php';
    
} elseif (!empty($_POST['modifpays']) && !empty($_POST['idlibellepaysactuel'])) {
    include '../modifBase/updatePays.php';die;
//--------------------------------------------------------------------------------------------------------------------------------------------
//SECTEUR ACTIVITE
//--------------------------------------------------------------------------------------------------------------------------------------------
} elseif (!empty($_POST['masquesecteuractivite']) && !empty($_POST['modifsecteuractivite']) && !empty($_POST['idlibellesecteuractiviteactuel'])) {
    include '../modifBase/affichemasquesecteurActivite.php';
} elseif (!empty($_POST['affichesecteuractivite']) && !empty($_POST['modifsecteuractivite']) && !empty($_POST['idlibellesecteuractiviteactuel'])) {
    include '../modifBase/affichemasquesecteurActivite.php';
} elseif (!empty($_POST['ajoutesecteuractivite']) && !empty($_POST['modifsecteuractivite'])) {
    include '../modifBase/insertsecteurActivite.php';
} elseif (!empty($_POST['modifsecteur']) && !empty($_POST['idlibellesecteuractiviteactuel'])) {
    include '../modifBase/updateSecteurActivite.php';
//--------------------------------------------------------------------------------------------------------------------------------------------
//TYPE PROJET
//--------------------------------------------------------------------------------------------------------------------------------------------
} elseif (!empty($_POST['masquetypeprojet']) && !empty($_POST['modiftypeprojet']) && !empty($_POST['idtypeprojetactuel'])) {
    include '../modifBase/affichemasqueTypeprojet.php';
} elseif (!empty($_POST['affichetypeprojet']) && !empty($_POST['modiftypeprojet']) && !empty($_POST['idtypeprojetactuel'])) {
    include '../modifBase/affichemasqueTypeprojet.php';
} elseif (empty($_POST['idtypeprojetactuel']) && !empty($_POST['modiftypeprojet'])) {
    include '../modifBase/insertTypeProjet.php';
} elseif (!empty($_POST['modiftypeprojet']) && !empty($_POST['idtypeprojetactuel'])) {
    include '../modifBase/updateTypeProjet.php';
//--------------------------------------------------------------------------------------------------------------------------------------------
//TYPE FORMATION
//--------------------------------------------------------------------------------------------------------------------------------------------
} elseif (!empty($_POST['masquetypeformation']) && !empty($_POST['modiftypeformation']) && !empty($_POST['idtypeformationactuel'])) {
    include  '../modifBase/affichemasqueTypeformation.php';
} elseif (!empty($_POST['affichetypeformation']) && !empty($_POST['modiftypeformation']) && !empty($_POST['idtypeformationactuel'])) {
    include  '../modifBase/affichemasqueTypeformation.php';
} elseif (empty($_POST['idtypeformationactuel']) && !empty($_POST['modiftypeformation'])) {
    include '../modifBase/insertTypeformation.php';
} elseif (!empty($_POST['modiftypeformation']) && !empty($_POST['idtypeformationactuel'])) {
    include  '../modifBase/updateTypeformation.php';    
    
//--------------------------------------------------------------------------------------------------------------------------------------------
//TYPE ENTREPRISE
//--------------------------------------------------------------------------------------------------------------------------------------------
} elseif (!empty($_POST['masquetypeentreprise']) && !empty($_POST['modiftypeentreprise']) && !empty($_POST['idtypeentrepriseactuel'])) {
    include '../modifBase/affichemasqueTypeentreprise.php';
} elseif (!empty($_POST['affichetypeentreprise']) && !empty($_POST['modiftypeentreprise']) && !empty($_POST['idtypeentrepriseactuel'])) {
    include '../modifBase/affichemasqueTypeentreprise.php';
} elseif (!empty($_POST['ajoutetypeentreprise']) && !empty($_POST['modiftypeentreprise'])) {
    include '../modifBase/insertTypeentreprise.php';
} elseif (!empty($_POST['modifTypeentreprise']) && !empty($_POST['modiftypeentreprise'])) {
    include '../modifBase/updateTypeentreprise.php';
//--------------------------------------------------------------------------------------------------------------------------------------------
//TYPE partenaire
//--------------------------------------------------------------------------------------------------------------------------------------------
} elseif (!empty($_POST['affichetypepartenaire']) && !empty($_POST['modiftypepartenaire']) && !empty($_POST['idtypepartenaireactuel'])) {
    include '../modifBase/affichemasqueTypepartenaire.php';
} elseif (!empty($_POST['masquetypepartenaire']) && !empty($_POST['modiftypepartenaire']) && !empty($_POST['idtypepartenaireactuel'])) {
    include '../modifBase/affichemasqueTypepartenaire.php';
} elseif (!empty($_POST['ajoutetypepartenaire']) && !empty($_POST['modiftypepartenaire'])) {
    include '../modifBase/insertTypepartenaire.php';
} elseif (!empty($_POST['modiftypePartenaire']) && !empty($_POST['modiftypepartenaireen'])) {
    include '../modifBase/updateTypepartenaire.php';
//--------------------------------------------------------------------------------------------------------------------------------------------
//THEMATIQUE
//--------------------------------------------------------------------------------------------------------------------------------------------
} elseif (!empty($_POST['masquethematique']) && !empty($_POST['modifthematique']) && !empty($_POST['idthematiqueactuel'])) {
    include '../modifBase/affichemasquethematique.php';
} elseif (!empty($_POST['affichethematique']) && !empty($_POST['modifthematique']) && !empty($_POST['idthematiqueactuel'])) {
    include '../modifBase/affichemasquethematique.php';
} elseif (!empty($_POST['modifthematique']) && empty($_POST['idthematiqueactuel'])) {
    include '../modifBase/insertthematique.php';
} elseif (!empty($_POST['idthematiqueactuel']) && !empty($_POST['modifthematique'])) {
    include '../modifBase/updatethematique.php';
//--------------------------------------------------------------------------------------------------------------------------------------------
//SOURCE FINANCEMENT
//--------------------------------------------------------------------------------------------------------------------------------------------
} elseif (!empty($_POST['masquesourcefinancement']) && !empty($_POST['modifsourcefinancement']) && !empty($_POST['idsourcefinancementactuel'])) {
    include '../modifBase/affichemasqueSourcefinancement.php';
} elseif (!empty($_POST['affichesourcefinancement']) && !empty($_POST['modifsourcefinancement']) && !empty($_POST['idsourcefinancementactuel'])) {
    include '../modifBase/affichemasqueSourcefinancement.php';
} elseif (!empty($_POST['modifsourcefinancement']) && empty($_POST['idsourcefinancementactuel'])) {
    include '../modifBase/insertsourcefinancement.php';
} elseif (!empty($_POST['idsourcefinancementactuel']) && !empty($_POST['modifsourcefinancement'])) {
    include '../modifBase/updatesourcefinancement.php';
//--------------------------------------------------------------------------------------------------------------------------------------------
//DISCIPLINE
//--------------------------------------------------------------------------------------------------------------------------------------------
} elseif (!empty($_POST['masquediscipline']) && !empty($_POST['modifdiscipline']) && !empty($_POST['iddisciplineactuel'])) {
    include '../modifBase/affichemasqueDiscipline.php';
} elseif (!empty($_POST['affichediscipline']) && !empty($_POST['modifdiscipline']) && !empty($_POST['iddisciplineactuel'])) {
    include '../modifBase/affichemasqueDiscipline.php';
} elseif (!empty($_POST['ajoutediscipline']) && !empty($_POST['modifdiscipline'])) {
    include '../modifBase/insertdiscipline.php';
} elseif (!empty($_POST['modifDiscipline']) && !empty($_POST['modifdiscipline'])) {
    include '../modifBase/updatediscipline.php';
//--------------------------------------------------------------------------------------------------------------------------------------------
//NOM EMPLOYEUR
//--------------------------------------------------------------------------------------------------------------------------------------------
} elseif (!empty($_POST['masqueNomemployeur']) && !empty($_POST['modifnomemployeur']) && !empty($_POST['idnomemployeuractuel'])) {
    include '../modifBase/affichemasquenomemployeur.php';
} elseif (!empty($_POST['afficheNomemployeur']) && !empty($_POST['modifnomemployeur']) && !empty($_POST['idnomemployeuractuel'])) {
    include '../modifBase/affichemasquenomemployeur.php';
} elseif (empty($_POST['idnomemployeuractuel']) && !empty($_POST['modifnomemployeur'])) {
    include '../modifBase/insertnomemployeur.php';
} elseif (!empty($_POST['modifnomemployeur']) && !empty($_POST['idnomemployeuractuel'])) {
    include '../modifBase/updatenomemployeur.php';
//TUTELLE    
}elseif (!empty($_POST['masquetutelle']) && !empty($_POST['modiftutelle']) && !empty($_POST['idtutelleactuel'])) {
    include '../modifBase/affichemasquetutelle.php';
} elseif (!empty($_POST['affichetutelle']) && !empty($_POST['modiftutelle']) && !empty($_POST['idtutelleactuel'])) {
    include '../modifBase/affichemasquetutelle.php';
} elseif (!empty($_POST['modiftutelle']) && !empty($_POST['idtutelleactuel'])) {
    include '../modifBase/updateTutelle.php';
} elseif (empty($_POST['idtutelleactuel']) && !empty($_POST['modiftutelle'])) {
    include '../modifBase/inserttutelle.php';
//--------------------------------------------------------------------------------------------------------------------------------------------
//RESSOURCE
//--------------------------------------------------------------------------------------------------------------------------------------------
}elseif (!empty($_POST['masqueressource']) && !empty($_POST['modifressource']) && !empty($_POST['idressourceactuel'])) {
    include '../modifBase/affichemasqueressource.php';
} elseif (!empty($_POST['afficheressource']) && !empty($_POST['modifressource']) && !empty($_POST['idressourceactuel'])) {
    include '../modifBase/affichemasqueressource.php';
} elseif (isset($_POST['modifressource']) && !empty($_POST['idressourceactuel'])) {
    include '../modifBase/updateRessource.php';
} elseif (!empty($_POST['modifressource']) && empty($_POST['idressourceactuel'])) {
    include '../modifBase/insertRessource.php';
//--------------------------------------------------------------------------------------------------------------------------------------------
//NOM CENTRALE
//--------------------------------------------------------------------------------------------------------------------------------------------
} elseif (!empty($_POST['masquenomcentrale']) && !empty($_POST['modifcentralenom']) && !empty($_POST['idcentralactuelnom'])) {
    include '../modifBase/affichemasqueCentrale.php';
} elseif (!empty($_POST['affichenomcentrale']) && !empty($_POST['modifcentralenom']) && !empty($_POST['idcentralactuelnom'])) {
    include '../modifBase/affichemasqueCentrale.php';
} elseif (!empty($_POST['modifcentralenom']) && !empty($_POST['idcentralactuelnom'])) {
    include '../modifBase/updateCentrale.php';
} elseif (!empty($_POST['modifcentralenom']) && empty($_POST['idcentralactuelnom'])) {
    include '../modifBase/insertCentrale.php';
} elseif (!empty($_POST['ajoutenomcentrale']) && empty($_POST['idcentralactuelnom'])) {
    include '../modifBase/insertCentrale.php';
//--------------------------------------------------------------------------------------------------------------------------------------------
//VILLE CENTRALE
//--------------------------------------------------------------------------------------------------------------------------------------------
} elseif (!empty($_POST['modifcentraleville'])) {
    include '../modifBase/updateVille.php';
} elseif (!empty($_POST['idcentraleactuelemail'])) {
//--------------------------------------------------------------------------------------------------------------------------------------------
//EMAIL CENTRALE
//--------------------------------------------------------------------------------------------------------------------------------------------
    include '../modifBase/updateEmailCentrale.php';
} elseif (!empty($_POST['idcentraleactuelcodeunite'])) {
//--------------------------------------------------------------------------------------------------------------------------------------------
//CODE UNITE CENTRALE
//--------------------------------------------------------------------------------------------------------------------------------------------
    include '../modifBase/updateCodeuniteCentrale.php';
//--------------------------------------------------------------------------------------------------------------------------------------------
// AJOUT CENTRALE DE PROXIMITE
//--------------------------------------------------------------------------------------------------------------------------------------------    
} elseif (!empty($_POST['addCentraleProximite']) && empty($_POST['libellecentraleProximite'])&& !empty($_POST['regionCorrespondante'])) {
    include '../modifBase/insertCentraleProximite.php';
//--------------------------------------------------------------------------------------------------------------------------------------------
// Modif CENTRALE DE PROXIMITE
//--------------------------------------------------------------------------------------------------------------------------------------------    
}elseif (!empty($_POST['modifcentraleProximite']) && !empty($_POST['idlibellecentraleProximiteactuel']) && !empty($_POST['regioncorrespondante'])) {
    include '../modifBase/modifCentraleProximite.php';
//--------------------------------------------------------------------------------------------------------------------------------------------
// MASQUE CENTRALE DE PROXIMITE
//--------------------------------------------------------------------------------------------------------------------------------------------    
}elseif (!empty($_POST['idlibellecentraleProximiteactuel']) && !empty($_POST['masquecentraleproximite']) && !empty($_POST['regioncorrespondante'])) {
    include '../modifBase/afficheMasqueCentraleProximite.php';
//--------------------------------------------------------------------------------------------------------------------------------------------
// AJOUT REGION
//--------------------------------------------------------------------------------------------------------------------------------------------    
}elseif (!empty($_POST['region']) && isset($_POST['ajouteRegion'])) {
    include '../modifBase/ajouteRegion.php';
}elseif (!empty($_POST['modifregion']) && !empty($_POST['idregionActuel'])) {
    include '../modifBase/updateRegion.php';
}elseif (!empty($_POST['masqueregion']) && !empty($_POST['idlibelleregionactuel'])) {
    include '../modifBase/afficheMasqueRegion.php';
}else {
    include '../decide-lang.php';
    header('location:/'.REPERTOIRE.'/update_paysErr1/' . $lang );
}

