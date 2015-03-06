<?php


?>
<script>
function addnomCentrale1(nb) {
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
                for (j = nb; j >= 0; i--) {
                    for (i = 0; i < nb; i++) {
                        document.getElementById('divpersonne' + i).style.display = 'block';
                        dijit.byId('nomaccueilcentrale' + i).set('required', 'required');
                        dijit.byId('prenomaccueilcentrale' + i).set('required', 'required');
                        dijit.byId('qualiteaccueilcentrale' + i).set('required', 'required');
                        dijit.byId('mailaccueilcentrale' + i).set('required', 'required');
                    }
                    for (i = j; i < j; i++) {
                        document.getElementById('divpersonne' + i).style.display = 'none';
                        dijit.byId('nomaccueilcentrale' + i).set('required', '');
                        dijit.byId('prenomaccueilcentrale' + i).set('required', '');
                        dijit.byId('qualiteaccueilcentrale' + i).set('required', '');
                        dijit.byId('mailaccueilcentrale' + i).set('required', '');
                    }
                }
                break;
        }
    }
}
</script>