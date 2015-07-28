<script>
    require(["dojo/html", "dojo/dom", "dojo/on", "dijit/form/ValidationTextBox", "dojo/domReady!"],
            function(html, dom, on) {
                on(dom.byId("setContent"), "click", function() {
                    dojo.create("div", {id: "nomresponsable"}, "div1");
                    html.set(dom.byId("nomresponsable"), '<tbody>'
                            + '<tr>'
                            + '<td id="nomResp"><label for="nomresponsable" style="width:300px"><?php echo addslashes(TXT_NOMRESPONSABLE); ?></label></td>'
                            + '<td><input type="text"  id="nomresponsable" name="nomresponsable" data-dojo-type="dijit/form/ValidationTextBox" value="" \n\
placeholder="<?php echo  addslashes(TXT_NOMRESPONSABLE); ?>"'
                            + 'autocomplete="on" style="width: 317px;margin-left:5px"'
                            + 'data-dojo-props="regExp:\'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð%&:0-9-\\042\\\'\\’\\=\\0133\\0135\\+\\_\ \,\\.\\-]+\'"'
                            + 'required= true'
                            + 'data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" />'
                            + '</td>'
                            + '</tr></tbody>', {
                                parseContent: true
                            }
                    );


                    dojo.create("div", {id: "emailresponsable"}, "div1");
                    html.set(dom.byId("emailresponsable"), '<tbody>'
                            + '<tr>'
                            + '<td id="emailResp"><label for="emailResponsable" style="width:300px"><?php echo addslashes(TXT_RESPMAILMAIL); ?></label></td>'
                            + '<td><input type="text"  id="emailResponsable" name="emailResponsable" data-dojo-type="dijit/form/ValidationTextBox" value="" placeholder="<?php echo addslashes(TXT_RESPMAILMAIL); ?>"'
                            + 'autocomplete="on"   style="width: 317px;margin-left:5px"'
                            + 'data-dojo-props="regExp:\'^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$\'"'
                            + 'required= true'
                            + 'data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" />'
                            + '</td>'
                            + '</tr></tbody>', {
                                parseContent: true
                            }
                    );
                }
                )
                        ;
                on(dom.byId("setContentne"), "click", function() {
                    dojo.create("div", {id: "autreEmployeur"}, "divne1");
                    html.set(dom.byId("autreEmployeur"), '<tbody>'
                            + '<tr>'
                            + '<td id="autreEmpl"><label for="autreEmployeur" style="width:300px"><?php	echo	addslashes(TXT_AUTREEMPLOYEUR).'*';	?></label></td>'
                            + '<td><input type="text"  id="autreEmployeur" name="autreEmployeur" data-dojo-type="dijit/form/ValidationTextBox" value="" placeholder="<?php	echo	addslashes(TXT_AUTREEMPLOYEUR);	?>"'
                            + 'autocomplete="on"    style="width: 317px;margin-left:5px"'
                            + 'data-dojo-props="regExp:\'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð%&:0-9-\\042\\\'\\’\\=\\0133\\0135\\+\\_\ \,\\.\\-]+\'"'
                            + 'required= true'
                            + 'data-dojo-invalidMessage="<?php	echo TXT_ERRSTRING;?>" />'
                            + '</td>'
                            + '</tr></tbody>', {
                                parseContent: true
                            }
                    );
                }

                );
                on(dom.byId("setContenttu"), "click", function() {
                    dojo.create("div", {id: "autreTutelle"}, "divtu1");
                    html.set(dom.byId("autreTutelle"), '<tbody>'
                            + '<tr>'
                            + '<td id="autreTu"><label for="autreTutelle" style="width:300px"><?php	echo	addslashes(TXT_AUTRETUTELLE).'*';	?></label></td>'
                            + '<td><input type="text"  id="autreTutelle" name="autreTutelle" data-dojo-type="dijit/form/ValidationTextBox" \n\
    value="" placeholder="<?php	echo	addslashes(TXT_AUTRETUTELLE);	?>"'
                            + 'autocomplete="on"  style="width: 317px;margin-left:5px"'
                            + 'data-dojo-props="regExp:\'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð%&:0-9-\\042\\\'\\’\\=\\0133\\0135\\+\\_\ \,\\.\\-]+\'"'
                            + 'required= true'
                            + 'data-dojo-invalidMessage="<?php	echo TXT_ERRSTRING;	?>" />'
                            + '</td>'
                            + '</tr></tbody>',
                            {parseContent: true}
                    );
                }
                );
                on(dom.byId("setContentdi"), "click", function() {
                    dojo.create("div", {id: "autreDiscipline"}, "divdi1");
                    html.set(dom.byId("autreDiscipline"), '<tbody>'
                            + '<tr>'
                            + '<td id="autreDi"><label for="autreDiscipline" style="width:300px"><?php	echo	addslashes(TXT_AUTREDISCIPLINE).'*';	?></label></td>'
                            + '<td><input type="text"  id="autreDiscipline" name="autreDiscipline" data-dojo-type="dijit/form/ValidationTextBox" value="" placeholder="<?php	echo	addslashes(TXT_AUTREDISCIPLINE);	?>"'
                            + 'autocomplete="on"    style="width: 317px;margin-left:5px"'
                            + 'data-dojo-props="regExp:\'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð%&:0-9-\\042\\\'\\’\\=\\0133\\0135\\+\\_\ \,\\.\\-]+\'"'
                            + 'required= true'
                            + 'data-dojo-invalidMessage="<?php	echo TXT_ERRSTRING;	?>" />'
                            + '</td>'
                            + '</tr></tbody>', {
                                parseContent: true
                            }
                    );
                }

                );

            });

</script>
<script>
    require(["dojo/dom", "dojo/on", "dijit/form/ValidationTextBox", "dojo/domReady!"],
            function(dom, on) {
                on(dom.byId("setContent2"), "click", function() {
                    if (dijit.byId("nomresponsable")) {
                        dijit.byId("nomresponsable").destroy();
                    }
                    if (dijit.byId("emailResponsable")) {
                        dijit.byId("emailResponsable").destroy();
                    }
                    if (document.getElementById('nomResp')) {
                        document.getElementById('nomResp').style.display = 'none';
                    }
                    if (document.getElementById('emailResp')) {
                        document.getElementById('emailResp').style.display = 'none';
                    }
                });
                on(dom.byId("setContentne2"), "click", function() {
                    if (dijit.byId("autreEmployeur")) {
                        dijit.byId("autreEmployeur").destroy();
                    }
                    if (document.getElementById('autreEmpl')) {
                        document.getElementById('autreEmpl').style.display = 'none';
                    }
                });
                on(dom.byId("setContenttu2"), "click", function() {
                    if (dijit.byId("autreTutelle")) {
                        dijit.byId("autreTutelle").destroy();
                    }
                    if (document.getElementById('autreTu')) {
                        document.getElementById('autreTu').style.display = 'none';
                    }
                });
                on(dom.byId("setContentdi2"), "click", function() {
                    if (dijit.byId("autreDiscipline")) {
                        dijit.byId("autreDiscipline").destroy();
                    }
                    if (document.getElementById('autreDi')) {
                        document.getElementById('autreDi').style.display = 'none';
                    }
                });

            })
</script>