        function checkUser(requeteSelect) {
            var value = requeteSelect.substring(0, requeteSelect.length - 1);
            if (dijit.byId(value).checked === true) {
                selectUser(requeteSelect);
            } else {
                deSelectUser(requeteSelect);
            }
        }
        function deSelectUser(requeteSelect) {
            var select = $_('select').innerHTML;
            var from = $_('from').innerHTML;
            dijit.byId('tous').set('checked', false);
            $_('select').innerHTML = select.replace('u.' + trim(requeteSelect) + ',', "");
            if ($_('select').innerHTML.indexOf('u.') === -1) {
                $_('from').innerHTML = from.replace('utilisateur u,', "");
            }

            if ($_('from').innerHTML.indexOf('utilisateur u') === -1) {
                $_('academicIndust').style.visibility = 'hidden';
            } else {
                $_('academicIndust').style.visibility = 'visible';
            }

        }
        function selectUser(requeteSelect) {
            var select = $_('select').innerHTML;
            var from = $_('from').innerHTML;
            dijit.byId('tous').set('checked', false);
            if (select.match(requeteSelect) === null) {
                $_('select').innerHTML += 'u.' + trim(requeteSelect) + ',';
                if (from.match('utilisateur') === null) {
                    $_('from').innerHTML += ' utilisateur u,';
                }
            }
            $_('academicIndust').style.visibility = 'visible';
        }
        function selectCentrale(id) {            
            var position = $_('and').innerHTML.indexOf('and u.idcentrale_centrale=')+'and u.idcentrale_centrale='.length+1;
            if (id !== '0') {
                if ($_('where').innerHTML.length === 0) {
                    $_('where').innerHTML = 'where u.idcentrale_centrale=' + id + ' ';
                } else
                if (verifWhere('where u.idcentrale_centrale=')) {//il y a une centrale dans la close where                
                    var taille = parseInt('where u.idcentrale_centrale='.length) + 1;
                    $_('where').innerHTML = $_('where').innerHTML.replace($_('where').innerHTML.substr(0, taille), '');
                    $_('where').innerHTML = 'where u.idcentrale_centrale=' + id;

                } else {
                    if ($_('and').innerHTML.length === 0) {// il y a rien  dans la close and
                        $_('and').innerHTML = 'and u.idcentrale_centrale=' + id + ' ';
                    } else if (verifAnd('and u.idcentrale_centrale=')) {//Il y a une centrale dans la close and                        
                        $_('and').innerHTML = remplace($_('and').innerHTML,position ,id);
                    }else{
                        $_('and').innerHTML += ' and u.idcentrale_centrale=' + id + ' ';
                    }
                }
            } else {
                $_('and').innerHTML = '';
                $_('where').innerHTML = '';
                dijit.byId('tutelle').set('value', '0');
                dijit.byId('discipline').set('value', '0');
                dijit.byId('acad').set('value', '0');
            }
        }
        function selectQualite(id) {
            var position = $_('and').innerHTML.indexOf('and u.idqualitedemandeuraca_qualitedemandeuraca=')+'and u.idqualitedemandeuraca_qualitedemandeuraca='.length+1;
            if ($_('aca').style.display === 'block') {//Choix académic
                if (id !== '0') {
                    if ($_('where').innerHTML.length === 0) {
                    $_('where').innerHTML = 'where u.idqualitedemandeuraca_qualitedemandeuraca=' + id + ' ';
                } else
                if (verifWhere('where u.idqualitedemandeuraca_qualitedemandeuraca=')) {//il y a une centrale dans la close where                
                    var taille = parseInt('where u.idqualitedemandeuraca_qualitedemandeuraca='.length) + 1;
                    $_('where').innerHTML = $_('where').innerHTML.replace($_('where').innerHTML.substr(0, taille), '');
                    $_('where').innerHTML = 'where u.idqualitedemandeuraca_qualitedemandeuraca=' + id;

                } else {
                    if ($_('and').innerHTML.length === 0) {// il y a rien  dans la close and
                        $_('and').innerHTML = 'and u.idqualitedemandeuraca_qualitedemandeuraca=' + id + ' ';
                    } else if (verifAnd('and u.idqualitedemandeuraca_qualitedemandeuraca=')) {//Il y a une centrale dans la close and                        
                        $_('and').innerHTML = remplace($_('and').innerHTML,position ,id);
                    }else{
                        $_('and').innerHTML += ' and u.idqualitedemandeuraca_qualitedemandeuraca=' + id + ' ';
                    }
                }
                } else {                  
                    dijit.byId('tutelle').set('value', '0');
                    dijit.byId('discipline').set('value', '0');
                    dijit.byId('centrale').set('value', '0');
                    $_('and').innerHTML = '';
                    $_('where').innerHTML = '';
                }
            }
        }
        function selectDiscipline(id) {
            var position = $_('and').innerHTML.indexOf('and u.idautrediscipline_autredisciplinescientifique=')+'and u.idautrediscipline_autredisciplinescientifique='.length+1;
            if (id !== '0') {
                if ($_('where').innerHTML.length === 0) {
                $_('where').innerHTML = 'where u.idautrediscipline_autredisciplinescientifique=' + id + ' ';
            } else
            if (verifWhere('where u.idautrediscipline_autredisciplinescientifique=')) {//il y a une centrale dans la close where                
                var taille = parseInt('where u.idautrediscipline_autredisciplinescientifique='.length) + 1;
                $_('where').innerHTML = $_('where').innerHTML.replace($_('where').innerHTML.substr(0, taille), '');
                $_('where').innerHTML = 'where u.idautrediscipline_autredisciplinescientifique=' + id;

            } else {
                if ($_('and').innerHTML.length === 0) {// il y a rien  dans la close and
                    $_('and').innerHTML = 'and u.idautrediscipline_autredisciplinescientifique=' + id + ' ';
                } else if (verifAnd('and u.idautrediscipline_autredisciplinescientifique=')) {//Il y a une centrale dans la close and                        
                    $_('and').innerHTML = remplace($_('and').innerHTML,position ,id);
                }else{
                    $_('and').innerHTML += ' and u.idautrediscipline_autredisciplinescientifique=' + id + ' ';
                }
            }
            } else {                  
                dijit.byId('acad').set('value', '0');
                dijit.byId('tutelle').set('value', '0');
                dijit.byId('centrale').set('value', '0');
                $_('and').innerHTML = '';
                $_('where').innerHTML = '';            
            }
            }         
        function selectTutelle(id) {
            var position = $_('and').innerHTML.indexOf('and u.idtutelle_tutelle=')+'and u.idtutelle_tutelle='.length+1;
            if (id !== '0') {
                if ($_('where').innerHTML.length === 0) {
                $_('where').innerHTML = 'where u.idtutelle_tutelle=' + id + ' ';
            } else
            if (verifWhere('where u.idtutelle_tutelle=')) {//il y a une centrale dans la close where                
                var taille = parseInt('where u.idtutelle_tutelle='.length) + 1;
                $_('where').innerHTML = $_('where').innerHTML.replace($_('where').innerHTML.substr(0, taille), '');
                $_('where').innerHTML = 'where u.idtutelle_tutelle=' + id;

            } else {
                if ($_('and').innerHTML.length === 0) {// il y a rien  dans la close and
                    $_('and').innerHTML = 'and u.idtutelle_tutelle=' + id + ' ';
                } else if (verifAnd('and u.idtutelle_tutelle=')) {//Il y a une centrale dans la close and                        
                    $_('and').innerHTML = remplace($_('and').innerHTML,position ,id);
                }else{
                    $_('and').innerHTML += ' and u.idtutelle_tutelle=' + id + ' ';
                }
            }
            } else {                  
                dijit.byId('acad').set('value', '0');
                dijit.byId('discipline').set('value', '0');
                dijit.byId('centrale').set('value', '0');
                $_('and').innerHTML = '';
                $_('where').innerHTML = '';            
            }
        }
        function verifWhere(string) {
            var chaine = trim($_('where').innerHTML);
            chaine = chaine.substring(0, chaine.length - 1);
            if (chaine.indexOf(string) !== -1) {
                return true;
            } else {
                return false;
            }
        }
        function verifAnd(string) {
            var chaine = trim($_('and').innerHTML);
            chaine = chaine.substring(0, chaine.length - 1);
            if (chaine.indexOf(string) !== -1) {
                return true;
            } else {
                return false;
            }
        }
        function remplace(chaine, position, caractere) {
            return chaine.substring(0, position - 1) + caractere +chaine.substring(position);
        }
        function clearAll() {
            unselectAll();
            $_('select').innerHTML = 'select ';
            $_('where').innerHTML = '';
            $_('from').innerHTML = 'from ';
            $_('and').innerHTML = '';
            $_('request').innerHTML = '';
            dijit.byId('centrale').set('value', '0');
            dijit.byId('tutelle').set('value', '0');
            dijit.byId('discipline').set('value', '0');
            if (dijit.byId('acad')) {
                dijit.byId('acad').set('value', '0');
            }
            if (dijit.byId('industr')) {
                dijit.byId('industr').set('value', '0');
            }
            if (dijit.byId('secteurActivite')) {
                dijit.byId('secteurActivite').set('value', '0');
            }
            if (dijit.byId('typeEntreprise')) {
                dijit.byId('typeEntreprise').set('value', '0');
            }
            $_('academicIndust').style.visibility = 'hidden';
            dijit.byId('academ').set('checked', false);
            dijit.byId('indust').set('checked', false);
            $_('tdCentrale').style.display = 'none';
            $_('tdDiscipline').style.display = 'none';
            $_('tdTutelle').style.display = 'none';
            $_('tdTypeEntreprise').style.display = 'none';
            $_('tdSecteurActivite').style.display = 'none';
            $_('aca').style.display = 'none';
        }
        function unselectAll() {
            var inputs = document.getElementsByTagName('input');
            for (var j = inputs.length - 1; j >= 0; j--) {
                if (inputs[j].type === 'checkbox') {
                    dijit.byId(inputs[j].value).setValue(false);
                }
            }
            dijit.byId('tous').setValue(false);
        }
        function $_(id) {
            return document.getElementById(id);
        }
        function checkRequest() {
            var select1 =trim($_('select').innerHTML);
            if (select1.match('select') !== null) {
                var select = select1.substring(0, select1.length - 1);
                var from1 = $_('from').innerHTML;
                var from = from1.substring(0, from1.length - 1);
                var where1 = $_('where').innerHTML;
                var where = where1.substring(0, where1.length - 1);
                var and = $_('and').innerHTML;
                var requete;
                if (where1 === 'where') {
                    requete = select + '  ' + from;
                } else if (and === 'And') {
                    requete = select + '  ' + from + '  ' + where;
                } else {
                    requete = select + '  ' + from + '  ' + where + '  ' + and;
                }
            }
            if (trim(requete) === 'select  fro' || trim(requete) === 'select  from' || trim(requete) === 'select  from  where' || $_('select').innerHTML.indexOf('select  from  where') !== -1
                    || trim(select) === 'select') {
                alert("Vous devez sélectionner au moins un élement dans une table!");
            } else {
                $_('request').innerHTML = requete;

            }
        }


        function AfficheMasque(id1, id2) {
            $_(id1).style.display = 'block';
            $_(id2).style.display = 'none';
            if ($_('aca').style.display === 'block') {
                $_('tdCentrale').style.display = 'block';
                $_('tdDiscipline').style.display = 'block';
                $_('tdTutelle').style.display = 'block';
                $_('tdTypeEntreprise').style.display = 'none';
                $_('tdSecteurActivite').style.display = 'none';
            } else if ($_('ind').style.display === 'block') {
                $_('tdCentrale').style.display = 'none';
                $_('tdDiscipline').style.display = 'none';
                $_('tdTutelle').style.display = 'none';
                $_('tdTypeEntreprise').style.display = 'block';
                $_('tdSecteurActivite').style.display = 'block';
            }
        }

        function selectAll(name) {
            checkboxes = document.getElementsByName(name + '[]');
            if (dijit.byId('tous').checked === true) {
                $_('academicIndust').style.visibility = 'visible';
                for (var i = 0; i < checkboxes.length; i++) {
                    $_('select').innerHTML = $_('select').innerHTML.replace('u.' + checkboxes[i].id + ',', '');
                }
                for (var i = 0; i < checkboxes.length; i++) {
                    if (dijit.byId(checkboxes[i].id).value !== 'tous') {
                        dijit.byId(checkboxes[i].id).set('checked', true);
                        $_('select').innerHTML += 'u.' + checkboxes[i].id + ',';
                    }
                }
                if ($_('from').innerHTML.match('utilisateur') === null) {
                    $_('from').innerHTML += ' utilisateur u,';
                }
            } else if (dijit.byId('tous').checked === false) {
                $_('from').innerHTML = $_('from').innerHTML.replace(' utilisateur u,', '');
                $_('academicIndust').style.visibility = 'hidden';
                for (var i = 0; i < checkboxes.length; i++) {
                    dijit.byId(checkboxes[i].id).set('checked', false);
                    $_('select').innerHTML = $_('select').innerHTML.replace('u.' + checkboxes[i].id + ',', '');
                }
            }
        }
        function selectAllProjet(name) {
            checkboxes = document.getElementsByName(name + '[]');
            if (dijit.byId('tousProjet').checked === true) {
                for (var i = 0; i < checkboxes.length; i++) {
                    if (dijit.byId(checkboxes[i].id).value !== 'tousProjet') {
                        dijit.byId(checkboxes[i].id).set('checked', true);                        
                    }
                }                
            } else if (dijit.byId('tousProjet').checked === false) {
                for (var i = 0; i < checkboxes.length; i++) {
                    dijit.byId(checkboxes[i].id).set('checked', false);                    
                }
            }
        }
        
        function addLinkProjet() {
            if ($_('where').innerHTML.length === 0) {
                $_('where').innerHTML = 'where cr.idutilisateur_utilisateur=u.idutilisateur and cr.idprojet_projet=p.idprojet ';
                $_('from').innerHTML +=' projet p,creer cr,';
            } else if(!verifWhere('where cr.idutilisateur_utilisateur=u.idutilisateur and cr.idprojet_projet=p.idproje')){
                if ($_('and').innerHTML.length === 0) {// il y a rien  dans la close and
                        $_('and').innerHTML  = 'and cr.idutilisateur_utilisateur=u.idutilisateur and cr.idprojet_projet=p.idprojet ';
                        $_('from').innerHTML =' projet p,creer cr,';
                } else if (!verifAnd('and cr.idutilisateur_utilisateur=u.idutilisateur and cr.idprojet_projet=p.idproje')) {
                    $_('and').innerHTML += 'and cr.idutilisateur_utilisateur=u.idutilisateur and cr.idprojet_projet=p.idprojet ';
                    $_('from').innerHTML +=' projet p,creer cr,';
                }
            }
        }
        
        function checkProject(){
            addLinkProjet();
        }