function getXMLHttpRequest() {
    var xhr2 = null;

    if (window.XMLHttpRequest || window.ActiveXObject) {
        if (window.ActiveXObject) {
            try {
                xhr2 = new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e) {
                xhr2 = new ActiveXObject("Microsoft.XMLHTTP");
            }
        } else {
            xhr2 = new XMLHttpRequest();
        }
    } else {
        alert("Votre navigateur ne supporte pas l'objet XMLHTTPRequest...");
        return null;
    }

    return xhr2;
}
function effaceAttachement(cheminEtFichierPhp) {
    var xhr2 = getXMLHttpRequest();
    xhr2.onreadystatechange = function() {
        if (xhr2.readyState === 4 && (xhr2.status === 200 || xhr2.status === 0)) {
            callback(xhr2.responseText);
        }
    };

    xhr2.open("GET", cheminEtFichierPhp, true);
    xhr2.send(null);
}

/* fonction de traitement de la réponse Ajax*/
function callback(reponse) {
    document.getElementById('reponse').innerHTML = reponse; //affiche dans le div de reponse
    document.getElementById('pieceJointe').style.display = 'none';
    document.getElementById('btnsuppr').style.display = 'none';
}

function effaceAttachementdesc(cheminEtFichierPhp) {
    var xhr2 = getXMLHttpRequest();
    xhr2.onreadystatechange = function() {
        if (xhr2.readyState === 4 && (xhr2.status === 200 || xhr2.status === 0)) {
            callbackdesc(xhr2.responseText);
        }
    };

    xhr2.open("GET", cheminEtFichierPhp, true);
    xhr2.send(null);
}

/* fonction de traitement de la réponse Ajax*/
function callbackdesc(reponse) {
    document.getElementById('reponsedesc').innerHTML = reponse; //affiche dans le div de reponse
    document.getElementById('pieceJointe2').style.display = 'none';
    document.getElementById('btnsuppr2').style.display = 'none';
}

