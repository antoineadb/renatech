/**
 * 
 * @returns {window.XMLHttpRequest|ActiveXObject}
 */
function getXMLHttpRequest() {
    if (window.XMLHttpRequest) {
        return new window.XMLHttpRequest;
    }
    else {
        try {
            return new ActiveXObject("MSXML2.XMLHTTP.3.0");
        }
        catch (ex) {
            return null;
        }
    }
}
/**
 * 
 * @param {type} cheminEtFichierPhp
 * @returns {undefined}
 */
function disablednomlabo(cheminEtFichierPhp) {
    var xhr2 = getXMLHttpRequest();
    xhr2.onreadystatechange = function () {
        if (xhr2.readyState === 4 && (xhr2.status === 200 || xhr2.status === 0)) {
            callbacknomlabo(xhr2.responseText);
        }
    };

    xhr2.open("GET", cheminEtFichierPhp, true);
    xhr2.send(null);
}

/**
 * 
 * @param {type} reponse
 * @returns {undefined}
 */
function callbacknomlabo(reponse) {
    document.getElementById('reponsenomlabo').value = reponse; //affiche dans le div de reponse
    if (reponse === 'TRUE') {
        dijit.byId('centralepartenaireprojet').set('required', '');
        //dijit.byId('nomPartenaire01').set('required', '');
        dijit.byId('integerspinner2').set('value', 0);
        document.getElementById('msgporteurProjet').style.display = 'none';
    } else {
        document.getElementById('msgporteurProjet').style.display = 'block';
        dijit.byId('centralepartenaireprojet').set('required', 'true');
        //dijit.byId('nomPartenaire01').set('required', 'true');
        dijit.byId('integerspinner2').set('value', 1);
    }
}
/**
 * 
 * @param {type} cheminEtFichierPhp
 * @returns {undefined}
 */
function disabledevis(cheminEtFichierPhp) {
    var xhr2 = getXMLHttpRequest();
    xhr2.onreadystatechange = function () {
        if (xhr2.readyState === 4 && (xhr2.status === 200 || xhr2.status === 0)) {
            callbackdevis(xhr2.responseText);
        }
    };

    xhr2.open("GET", cheminEtFichierPhp, true);
    xhr2.send(null);
}
/**
 * 
 * @param {type} reponse
 * @returns {undefined}
 */
function callbackdevis(reponse) {
    document.getElementById('reponsedevis').value = reponse; //affiche dans le div de reponse
    if (reponse === 'FALSE') {
        document.getElementById('mailResp').style.display = 'none';
        dijit.byId('mailresp').set('required', '');
    } else {
        dijit.byId('mailresp').set('required', 'true');
        document.getElementById('mailResp').style.display = 'block';
    }
}
/**
 * 
 * @param {type} cheminEtFichierPhp  path to the file who execute the request
 * @returns {undefined}
 */
function afficheNomCentrale(cheminEtFichierPhp) {
    enabledmodif();
    var xhr2 = getXMLHttpRequest();
    xhr2.onreadystatechange = function () {
        if (xhr2.readyState === 4 && (xhr2.status === 200 || xhr2.status === 0)) {
            callbackcentrale(xhr2.responseText);
        }
    };

    xhr2.open("GET", cheminEtFichierPhp, true);
    xhr2.send(null);
}

/**
 * 
 * @param {type} reponse 
 * @returns {undefined}
 */
function callbackcentrale(reponse) {
    if (reponse!=='' && reponse!=="Autres") {
        dijit.byId('nomlabo').set("value", reponse);
        dijit.byId('nomlabo').setDisabled(true);
        document.getElementById('tr_autrecodeunite').style.display='none';
        if(dijit.byId('autreCodeunite1')){
            dijit.byId('autreCodeunite1').set('required', '');
        }
        if(dijit.byId('autreCodeunite')){
            dijit.byId('autreCodeunite').set('required', '');
        }
        if(dijit.byId('nomlabo')){
            dijit.byId('nomlabo').set('required', '');
        }
    } else if(reponse==="Autres") {
        document.getElementById('tr_autrecodeunite').style.display='table-row'; 
        dijit.byId('nomlabo').setDisabled(false);      
        dijit.byId('nomlabo').set("value", '');
        if(dijit.byId('autreCodeunite1')){
            dijit.byId('autreCodeunite1').set('required', 'true');
        }
        if(dijit.byId('autreCodeunite')){
            dijit.byId('autreCodeunite').set('required', 'true');
        }
        if(dijit.byId('nomlabo')){
            dijit.byId('nomlabo').set('required', 'true');
        }
    }else{
        dijit.byId('nomlabo').setDisabled(false);        
        dijit.byId('nomlabo').set("value", '');
        if(dijit.byId('autreCodeunite1')){
            dijit.byId('autreCodeunite1').set('required', '');
        }
        if(dijit.byId('autreCodeunite')){
            dijit.byId('autreCodeunite').set('required', '');
        }
        if(dijit.byId('nomlabo')){
            dijit.byId('nomlabo').set('required', '');
        }
        document.getElementById('tr_autrecodeunite').style.display='none'; 
    }
}


/**
 * 
 * @param {type} cheminEtFichierPhp
 * @returns {undefined}
 */
function autreemployer(cheminEtFichierPhp) {
    var xhr2 = getXMLHttpRequest();
    xhr2.onreadystatechange = function () {
        if (xhr2.readyState === 4 && (xhr2.status === 200 || xhr2.status === 0)) {
            callbackemployeur(xhr2.responseText);
        }
    };
    xhr2.open("GET", cheminEtFichierPhp, true);
    xhr2.send(null);
}
/**
 * 
 * @param {type} reponse
 * @returns {undefined}
 */
function callbackemployeur(reponse) {
    if (reponse === 'ne5') {
        document.getElementById('autreemp').innerHTML = '<input id="autrenomemployeurtest" type="text" name="autrenomemployeurtest" required="required" \n\
				data-dojo-type="dijit.form.ValidationTextBox"  data-dojo-invalidMessage="Titre non valide." data-dojo-props="regExp:\'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð%&:0-9\042/\’/=°()_ ,.-]+\'" />';

    } else {
        document.getElementById('autre').innerHTML = '';
    }
}
/**
 * 
 * @param {type} cheminEtFichierPhp
 * @returns {undefined}
 */
function modifAcronymeLabo(cheminEtFichierPhp) {
    var xhr2 = getXMLHttpRequest();
    xhr2.onreadystatechange = function () {
        if (xhr2.readyState === 4 && (xhr2.status === 200 || xhr2.status === 0)) {
            callbackmodifAcronyme(xhr2.responseText);
        }
    };

    xhr2.open("GET", cheminEtFichierPhp, true);
    xhr2.send(null);
}

function callbackmodifAcronyme(reponse) {
    document.getElementById('afficheMessage').value = reponse; //affiche dans le div de reponse
    if (reponse === 'TRUE') {
        document.getElementById('afficheMessage').style.display='block';
    } else {
      document.getElementById('afficheMessage').style.display='none';
    }
}
function refreshLogs(cheminEtFichierPhp) {
    var xhr2 = getXMLHttpRequest();
    xhr2.onreadystatechange = function () {
        if (xhr2.readyState === 4 && (xhr2.status === 200 || xhr2.status === 0)) {
            callbackrefreshLogs(xhr2.responseText);
        }
    };

    xhr2.open("GET", cheminEtFichierPhp, true);
    xhr2.send(null);
}
function callbackrefreshLogs(reponse){
    document.getElementById('zoneText').innerHTML = reponse; 
}
function relanceEmail(cheminEtFichierPhp) {
    var xhr2 = getXMLHttpRequest();
    xhr2.onreadystatechange = function () {
        if (xhr2.readyState === 4 && (xhr2.status === 200 || xhr2.status === 0)) {
            callbackrelance(xhr2.responseText);
        }
    };

    xhr2.open("GET", cheminEtFichierPhp, true);
    xhr2.send(null);
}
function callbackrelance(reponse){
    document.getElementById('test2').innerHTML = reponse; 
}


/**
 * 
 * @param {type} cheminEtFichierPhp
 * @returns {undefined}
 */
function modifCentraleAffectation(cheminEtFichierPhp) {
    var xhr2 = getXMLHttpRequest();
    xhr2.onreadystatechange = function () {
        if (xhr2.readyState === 4 && (xhr2.status === 200 || xhr2.status === 0)) {
            callbackmodifCentraleAffectation(xhr2.responseText);
        }
    };

    xhr2.open("GET", cheminEtFichierPhp, true);
    xhr2.send(null);
}

function callbackmodifCentraleAffectation(reponse) {
    document.getElementById('selection').style.display = "block";
    document.getElementById('default').style.display = "none";
    document.getElementById('selection').innerHTML = reponse; 
}
/**
 * 
 * @param {type} cheminEtFichierPhp
 * @returns {undefined}
 */
function afficheCentraleProximite(cheminEtFichierPhp) {
    var xhr2 = getXMLHttpRequest();
    xhr2.onreadystatechange = function () {
        if (xhr2.readyState === 4 && (xhr2.status === 200 || xhr2.status === 0)) {
            callBackAfficheCentraleProximite(xhr2.responseText);
        }
    };

    xhr2.open("GET", cheminEtFichierPhp, true);
    xhr2.send(null);
}

function callBackAfficheCentraleProximite(reponse) {
    document.getElementById('chckcentraleproximite').style.display = "block";
    document.getElementById('chckcentraleproximite').innerHTML = reponse; 
}

/**
 * 
 * @param {type} cheminEtFichierPhp
 * @returns {undefined}
 */
function calculNbdeProjet(cheminEtFichierPhp) {
    var xhr2 = getXMLHttpRequest();
    xhr2.onreadystatechange = function () {
        if (xhr2.readyState === 4 && (xhr2.status === 200 || xhr2.status === 0)) {
            callbackafficheCentraleproximite(xhr2.responseText);
        }
    };

    xhr2.open("GET", cheminEtFichierPhp, true);
    xhr2.send(null);
}

function callbackafficheCentraleproximite(reponse) {
    document.getElementById('nbProjet').style.display = "block";
    document.getElementById('nbProjet').innerHTML = reponse; 
}

function modifDateDebutProjet(cheminEtFichierPhp) {
    var xhr2 = getXMLHttpRequest();
    xhr2.onreadystatechange = function () {
        if (xhr2.readyState === 4 && (xhr2.status === 200 || xhr2.status === 0)) {
            callbackModifDateDebutProjet(xhr2.responseText);
        }
    };
    xhr2.open("GET", cheminEtFichierPhp, true);
    xhr2.send(null);
}

function callbackModifDateDebutProjet(reponse) {
    if (reponse === 'TRUE') {
        document.getElementById('majDateDebutProjet').style.display='block';      
    }    
}

function effaceFigure(cheminEtFichierPhp) {
    var xhr2 = getXMLHttpRequest();
    xhr2.onreadystatechange = function() {
        if (xhr2.readyState === 4 && (xhr2.status === 200 || xhr2.status === 0)) {
            callbackEffaceFigure(xhr2.responseText);
        }
    };

    xhr2.open("GET", cheminEtFichierPhp, true);
    xhr2.send(null);
}
/* fonction de traitement de la réponse Ajax*/
function callbackEffaceFigure(reponse) {
    document.getElementById('response').style.display='block';
}

function adminParamProjet(cheminEtFichierPhp,str,str2=null) {
    var xhr2 = getXMLHttpRequest();console.log(str2);
    xhr2.onreadystatechange = function() {
        if (xhr2.readyState === 4 && (xhr2.status === 200 || xhr2.status === 0)) {
            if(str=='acronyme'){
                afficheInfoAcronyme(xhr2.responseText);
            }else if(str=='accueil'){
                if(str2!=-1){
                    afficheInfoAccueil(xhr2.responseText);
                }else{
                    afficheErrInfoAccueil(xhr2.responseText);
                }
            }
        }
    };
    xhr2.open("GET", cheminEtFichierPhp, true);
    xhr2.send(null);
}
function afficheInfoAcronyme(response) {
    document.getElementById('imgConfigAcronyme').style.display = "block";
}
function afficheInfoAccueil(response) {
    document.getElementById('imgConfigAccueil').style.display = "block";
    document.getElementById('errConfigAccueil').style.display = "none";
}
function afficheErrInfoAccueil(response) {
    document.getElementById('errConfigAccueil').style.display = "block";
}