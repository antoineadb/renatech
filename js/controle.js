function checkcentrale(id) {
    require(["dojo/dom", "dijit/registry"], function (dom, registry) {
        var inputs = registry.findWidgets(dom.byId(id));
        centrale = [];
        for (var i = 0, il = inputs.length; i < il; i++) {
            if (inputs[i].checked) {
                centrale.push(inputs[i].value);
            }
        }

    });    
    if (centrale.length === 0) {
        return false;
    } else {
        return true;
    }
}

function donneeRadio(btn) {
    var xhr = getXMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && (xhr.status === 200 || xhr.status === 0)) {
            write(btn);
        }
    }
    xhr.open("POST", document.location.href + '?radio=' + btn, true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    var data = xhr.responseText;
    xhr.send(data);

}
function unhide(id, id2) {
    document.getElementById(id).style.display = 'block';
    document.getElementById(id2).style.display = 'none';
    donneeRadio(id);
    document.getElementById('ind').value = '*';
    //MISE A ZERO DES CHAMPS INDUSTRIEL EN CAS DE CLICK SUR ACADEMIC
    if (dijit.byId('qualiteDemandeurindust')) {
        dijit.byId('qualiteDemandeurindust').set("value", 'qi0');
    }
    if (dijit.byId('typeEntreprise')) {
        dijit.byId('typeEntreprise').set("value", 'te0');
    }
    if (dijit.byId('secteurActivite')) {
        dijit.byId('secteurActivite').set("value", 'se0');
    }
    //MISE A ZERO DES CHAMPS ACADEMIC EN CAS DE CLICK SUR INDUSTRIEL
    if (dijit.byId('qualiteDemandeuraca')) {
        dijit.byId('qualiteDemandeuraca').set("value", 'qa0');
    }
    if (dijit.byId('nomEmployeur')) {
        dijit.byId('nomEmployeur').set("value", 'ne0');
    }
    if (dijit.byId('tutelle')) {
        dijit.byId('tutelle').set("value", 'tu0');
    }
    if (dijit.byId('codeUnite')) {
        dijit.byId('codeUnite').set("value", '*');
    }
    if (dijit.byId('disciplineScientifique')) {
        dijit.byId('disciplineScientifique').set("value", 'di0');
    }

}
function  checkRadio(id1, id2, errmsg) {
    if ((dijit.byId(id1).checked) || dijit.byId(id2).checked) {
        return true;
    } else {
        alert(errmsg);
        return false;
    }
}


function enabledmodif() {
    if (dijit.byId("modif")) {
        dijit.byId("modif").setAttribute('disabled', false);
    }
}

function detectTouche(e) {
    if (parseInt(navigator.appVersion, 10) >= 4) {
        if (navigator.appName === 'Netscape') { // Pour Netscape, firefox, ...
            if (e.which === 20) {
                if (document.getElementById('divMajus')) {
                    if (document.getElementById('divMajus').style.visibility === 'visible') {
                        document.getElementById('divMajus').style.visibility = 'hidden';
                    } else {
                        document.getElementById('divMajus').style.visibility = 'visible';
                    }
                }
            } else { // pour Internet Explorer ou Chrome
                if (e.keyCode === '20') {
                    if (document.getElementById('divMajus')) {
                        if (document.getElementById('divMajus').style.visibility === 'visible') {
                            document.getElementById('divMajus').style.visibility = 'hidden';
                        } else {
                            document.getElementById('divMajus').style.visibility = 'visible';
                        }
                    }
                }
            }
        }
    }
}

function empty(mixed_var) {
    var undef, key, i, len;
    var emptyValues = [undef, null, false, 0, "", "0"];

    for (i = 0, len = emptyValues.length; i < len; i++) {
        if (mixed_var === emptyValues[i]) {
            return true;
        }
    }
    if (typeof mixed_var === "object") {
        for (key in mixed_var) {
            return false;
        }
        return true;
    }
    return false;
}

function afficherAutre(id1, id2) {
    if (dijit.byId(id1).value === 'Autres' || dijit.byId(id1).get('displayedValue') === 'Autres' || dijit.byId(id1).value === 'Others' || dijit.byId(id1).get('displayedValue') === 'Others') {
        console.log(dijit.byId(id2));
        dijit.byId(id2).domNode.style.display = 'block';
    } else {
        dijit.byId(id2).domNode.style.display = 'none';
    }
}

function write(btn) {
    document.getElementById('radioType').innerHTML = btn; //affiche dans le div de reponse
}

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
function strip_tags(input, allowed) {
    allowed = (((allowed || '') + '').toLowerCase().match(/<[a-z][a-z0-9]*>/g) || []).join('');
    var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi,
            commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;
    return input.replace(commentsAndPhpTags, '')
            .replace(tags, function ($0, $1) {
                return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
            });
}

function trim(myString) {
    return myString.replace(/^\s+/g, '').replace(/\s+$/g, '');
}

function stripTags(myString) {
    return myString.replace(/<\w+(\s+("[^"]*"|'[^']*'|[^>])+)?>|<\/\w+>/gi, '');
}

function utf8length(str) {
    var length = 0;
    for (var i = 0, c = str.length; i < c; i++) {
        var codeChar = str.charCodeAt(i);
        var nb_bits = Math.ceil((Math.log(codeChar + 1)) / Math.log(2));
        length++;
        if (nb_bits >= 8)
            length++;
        if (nb_bits >= 12)
            length++;
        if (nb_bits >= 17)
            length++;
    }
    return length;
}

function addnomCentrale(nb) {
    if (nb === 0) {
        document.getElementById('personCent').style.display = 'none';
        for (i = 0; i < 21; i++) {
            dijit.byId('nomaccueilcentrale' + i).set('required', '');
            dijit.byId('prenomaccueilcentrale' + i).set('required', '');
            dijit.byId('qualiteaccueilcentrale' + i).set('required', '');
            dijit.byId('mailaccueilcentrale' + i).set('required', '');
        }
    } else {
        switch (nb) {
            case nb:
                document.getElementById('personCent').style.display = 'block';
                for (i = 0;i <nb; i++) {
                    document.getElementById('divpersonne' + i).style.display = 'block';
                    dijit.byId('nomaccueilcentrale' + i).set('required', 'required');
                    dijit.byId('prenomaccueilcentrale' + i).set('required', 'required');
                    dijit.byId('qualiteaccueilcentrale' + i).set('required', 'required');
                    dijit.byId('mailaccueilcentrale' + i).set('required', 'required');                   
                }
            for (j = nb; j < 21; j++) {
                    document.getElementById('divpersonne' + j).style.display = 'none';
                    dijit.byId('nomaccueilcentrale' + j).set('required', '');
                    dijit.byId('prenomaccueilcentrale' + j).set('required', '');
                    dijit.byId('qualiteaccueilcentrale' + j).set('required', '');
                    dijit.byId('mailaccueilcentrale' + j).set('required', '');
                }
                break;
        }
    }
}