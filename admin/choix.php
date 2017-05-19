<?php

$ongletcase = false;
$ongletliste1 = false;
$ongletliste2 = false;
if (isset($_GET['libellecentralenom'])) {
    $ongletcase = true;
} elseif (isset($_GET['msgserveurcentralehide'])) {
    $ongletcase = true;
} elseif (isset($_GET['msgserveurtypecentraleshow'])) {
    $ongletcase = true;
} elseif (isset($_GET['libecentrale'])) {
    $ongletcase = true;
} elseif (isset($_GET['msgVilleupdate'])) {
    $ongletcase = true;
} elseif (isset($_GET['libellecentrale'])) {
    $ongletcase = true;
} elseif (isset($_GET['msgEmailcentralupdate'])) {
    $ongletcase = true;
} elseif (isset($_GET['libcentrale'])) {
    $ongletcase = true;
} elseif (isset($_GET['libcentral'])) {
    $ongletcase = true;
} elseif (isset($_GET['msgCodeunitecentralupdate'])) {
    $ongletcase = true;
} elseif (isset($_GET['idressource'])) {
    $ongletcase = true;
} elseif (isset($_GET['msgRessourceupdate'])) {
    $ongletcase = true;
} elseif (isset($_GET['msginsertcentrale'])) {
    $ongletcase = true;
} elseif (isset($_GET['msgserveurressource'])) {
    $ongletcase = true;
} elseif (isset($_GET['msgserveurressourcehide'])) {
    $ongletcase = true;
} elseif (isset($_GET['msgserveursourcefinancement'])) {
    $ongletcase = true;
} elseif (isset($_GET['msgErrsourcefinancementnonsaisie'])) {
    $ongletcase = true;
} elseif (isset($_GET['msgErrCentupdate'])) {
    $ongletcase = true;
} elseif (isset($_GET ['msgserveursourcefinancementhide'])) {
    $ongletcase = true;
} elseif (isset($_GET['msgserveurtypesourcefinancementshow'])) {
    $ongletcase = true;
} elseif (isset($_GET['idsourcefinancement'])) {
    $ongletcase = true;
} elseif (isset($_GET['msgsourcefinancementupdate'])) {
    $ongletcase = true;
} elseif (isset($_GET['msgserveurressourceshow'])) {
    $ongletcase = true;
} elseif (isset($_GET['libecentral'])) {
    $ongletcase = true;
} elseif (isset($_GET['msgErrCentrale'])) {
    $ongletcase = true;
} elseif (isset($_GET['idcentraleproximite'])) {
    $ongletliste2 = true;
}elseif (isset($_GET['choixcentraleproximite'])) {
    $ongletliste2 = true;
}elseif ((isset($_GET['idcentraleproximitemodif']))) {
    $ongletliste2 = true;
}elseif ((isset($_GET['idcentraleproximitemasque']))) {
    $ongletliste2 = true;
}elseif ((isset($_GET['idregion']))) {
    $ongletliste2 = true;
}elseif ((isset($_GET['idregionmodif']))) {
    $ongletliste2 = true;
}elseif ((isset($_GET['idregionmasque']))) {
    $ongletliste2 = true;
}elseif ((isset($_GET['cpns']))) {
    $ongletliste2 = true;
}elseif ((isset($_GET['rens']))) {
    $ongletliste2 = true;
}elseif ((isset($_GET['cpexist']))) {
    $ongletliste2 = true;
}elseif ((isset($_GET['cpadd']))) {
    $ongletliste2 = true;
}elseif (isset($_GET['idtypepartenaire']) || isset($_GET['msgserveurtypepartenaire']) || isset($_GET['msgErrtypepartenairenonsaisie']) || isset($_GET['msgErrtypepartenaireexiste']) 
|| isset($_GET['msgserveurtypepartenaireupdate'])|| isset($_GET['msgErrtypepartreselect'])|| isset($_GET['msgserveurtypepartenaireshow']) || isset($_GET['msgserveurtypepartenairehide']) ){
$ongletliste2 = true;
}
