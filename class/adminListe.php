<?php
session_start();
include('decide-lang.php');
include 'html/header.html';
?>
<div id="global">
    <?php
    include 'html/entete.html';
    if (isset($_SESSION['pseudo'])) {
        check_authent($_SESSION['pseudo']);
    } else {
        echo '<script>window.location.replace("erreurlogin.php")</script>';
    }
    ?>
    <div data-dojo-type="dijit/layout/TabContainer" style="width: 570px;" doLayout="false">
        <div data-dojo-type="dijit/layout/ContentPane" title="  Administratif: Listes déroulantes  " style="width: 570px; height: auto;" >
            <form id='administ' name='administ' method="post" action ="controler/ctrladmin.php" data-dojo-type="dijit/form/Form" >
                <input name="page_precedente" type="hidden" value="<?php echo basename(__FILE__) ; ?>">
                <script type="dojo/method" data-dojo-event="onSubmit">
                    if(this.validate()){
                    return true;
                    }else{
                    alert('<?php echo TXT_MESSAGEERREURCONTACT; ?>');
                    return false;
                    exit();
                    }
                </script>
                <table>
                    <!-- Gestion des Pays -->
                    <tr> <th><label for ="pays"  style="margin-left: 20px;"><?php echo TXT_PAYS ?> :</label></th></tr>
                    <tr>
                        <th><div id="ciblePays"></div></th>
                    <th><div id="ciblesituationgeo"></div></th>
                    </tr>
                    <tr>
                        <th><input type="text" id="nouveaunompays" name="nouveaunompays"  style="margin-left: 20px;height:20px; width: 220px;vertical-align:middle;" data-dojo-type="dijit/form/ValidationTextBox"
                                   placeholder="<?php echo TXT_PAYS; ?>"  data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð\'()_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >
                        </th>
                        <th>
                            <button class="admin"  type="submit" name="modifpays"><?php echo TXT_MODIFIER; ?></button>
                            <button class="admin"  type="submit" name="ajoutepays" > <?php echo TXT_AJOUTER; ?></button>
                        </th>
                    </tr><tr><td><br></td></tr>
                    <!-- Gestion  des secteurs d'activités -->
                    <tr><th><label for ="ciblesecteuractivite" style="margin-left: 20px"><?php echo TXT_SECTEURACTIVITE; ?></label></th></tr>
                    <tr>
                        <th><div id="ciblesecteuractivite"></div></th>
                    </tr>
                    <tr>
                        <th><input type="text" id="modifsecteur" name="modifsecteur"  style="height:20px;margin-left: 20px; width: 220px;vertical-align:middle;" data-dojo-type="dijit/form/ValidationTextBox"  placeholder="<?php echo TXT_SECTEURACTIVITE; ?>"
                                   data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð\'()_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >
                        </th>

                        <th>
                            <button class="admin"  type="submit" name="modifsecteurActivite" ><?php echo TXT_MODIFIER; ?></button>
                            <button class="admin"  type="submit" name="ajoutesecteurActivite"><?php echo TXT_AJOUTER; ?></button>
                        </th>
                    </tr>
                </tr><tr><td><br></td></tr>
            <!-- Gestion des type d'entreprise -->
            <tr><th><label for="cibletypeentreprise" style="margin-left: 20px;"><?php echo TXT_TYPEENTREPRISE; ?></label></th></tr>
            <tr>
                <th><div id="cibletypeentreprise"></div></th>
            </tr>
            <tr>
                <th>
                    <input type="text" id="modiftypeentreprise" name="modiftypeentreprise"  style="margin-left: 20px;;height:20px; width: 220px;vertical-align:middle;" data-dojo-type="dijit/form/ValidationTextBox"  placeholder="<?php echo TXT_TYPEENTREPRISE; ?>"
                           data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð\'()_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" ></th>
                <th>
                    <button class="admin"  type="submit" name="modiftypeentre" ><?php echo TXT_MODIFIER; ?></button>
                    <button class="admin"  type="submit" name="ajoutetypeentreprise"><?php echo TXT_AJOUTER; ?></button>
                </th>
            </tr>
            <tr><td><br></td></tr>
            <!-- Discipline scientifique -->
            <tr><th><label for="ciblediscipline" style="margin-left: 20px;width:220px"><?php echo TXT_DISCIPLINESCIENTIFIQUE; ?></label></th></tr>
            <tr>
                <th><div id="ciblediscipline"></div></th>

            </tr>
            <tr>
                <th>
                    <input type="text" id="modifdiscipline" name="modifdiscipline" style="height:20px;margin-left: 20px; width: 220px;vertical-align:middle;" data-dojo-type="dijit/form/ValidationTextBox"  placeholder="<?php echo TXT_DISCIPLINESCIENTIFIQUE; ?>"
                           data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð\'()_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" ></th>

                <th>
                    <button class="admin"  type="submit" name="modifdiscip" ><?php echo TXT_MODIFIER; ?></button>
                    <button class="admin"  type="submit" name="ajoutediscipline"><?php echo TXT_AJOUTER; ?></button>
                </th>
            </tr>
            <tr><td><br></td></tr>
            <!-- Nom employeur -->
            <tr><th><label for="ciblenomemployeur" style="margin-left:20px"><?php echo TXT_NOMEMPLOYEUR; ?></label></th></tr>
            <tr>
                <th><div id="ciblenomemployeur"></div></th>
            <th>
                </tr>
            <tr>
                <th>
                    <input type="text" id="modifnomemployeur" name="modifnomemployeur" style="height:20px;margin-left:20px; width: 220px;vertical-align:middle;" data-dojo-type="dijit/form/ValidationTextBox" placeholder="<?php echo TXT_NOMEMPLOYEUR; ?>"
                           data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð\'()_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >
                </th>

                <th><button class="admin"  type="submit" name="modifemployeur" ><?php echo TXT_MODIFIER; ?></button><button class="admin"  type="submit" name="ajouteemployeur"><?php echo TXT_AJOUTER; ?></button></th>
            </tr>
            <tr><td><br></td></tr>
            <!-- Tutelle -->
            <tr><th><label for="cibletutelle" style="width:220px;margin-left:20px"><?php echo TXT_TUTELLE; ?></label></th></tr>
            <tr>
                <th><div id="cibletutelle"></div></th>
            </tr>
            <tr>
                <th>
                    <input type="text" id="modiftutel" name="modiftutel" placeholder="<?php echo TXT_TUTELLE; ?>" style="height:20px;margin-left:20px; width: 220px;vertical-align:middle;" data-dojo-type="dijit/form/ValidationTextBox"
                           data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð\'()_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >
                </th>
                <th>
                    <button class="admin"  type="submit" name="modiftutelle" ><?php echo TXT_MODIFIER; ?></button>
                    <button class="admin"  type="submit" name="ajoutetutelle"><?php echo TXT_AJOUTER; ?></button>
                </th>
            </tr>
            <tr><td><br></td></tr>
            <?php
            if (!empty($_GET['msgErrVil'])) {
                echo TXT_MESSAGEERREURVILLENONSAISIE;
            } elseif (!empty($_GET['msgErrpays'])) {
                echo TXT_MESSAGEERREURPAYSNONSAISIE;
            } elseif (!empty($_GET['msgErrpaysnonsaisie'])) {
                echo TXT_MESSAGEERREURPAYSNONSAISIE;
            } elseif (!empty($_GET['msgErrpaysexiste'])) {
                echo TXT_MESSAGESERVEURPAYSEXISTE;
            } elseif (!empty($_GET['msgserveurpays'])) {
                echo TXT_MESSAGESERVEURPAYS;
            } elseif (!empty($_GET['msgErrpaysselect'])) {
                echo TXT_MESSAGEERREURPAYSENONSELECT;
            } elseif (!empty($_GET['msgpaysupdate'])) {
                echo TXT_MESSAGESERVEURUPDATEPAYS;
            } elseif (!empty($_GET['msgErrsituationselect'])) {
                echo TXT_MESSAGEERREURGEONONSELECT;
            } elseif (!empty($_GET['msgErrSecteurnonsaisie'])) {
                echo TXT_MESSAGEERREURSECTEURNONSAISIE;
            } elseif (!empty($_GET['msgErrsecteurselect'])) {
                echo TXT_MESSAGEERREURGESECTEURONSELECT;
            } elseif (!empty($_GET['msgErrSecteurexiste'])) {
                echo TXT_MESSAGESERVEURSECTEUREXISTE;
            } elseif (!empty($_GET['msgserveursecteur'])) {
                echo TXT_MESSAGESERVEURSETCEURACTIVITE;
            } elseif (!empty($_GET['msgsecteurupdate'])) {
                echo TXT_MESSAGESEECTEURUPDATE;
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
            } elseif ((!empty($_GET['msgErrdisciplinenonsaisie']))) {
                echo TXT_MESSAGEERREURDISCIPLINENONSAISIE;
            } elseif ((!empty($_GET['msgserveurdiscipline']))) {
                echo TXT_MESSAGESERVEURDISCIPLINE;
            } elseif ((!empty($_GET['msgdisciplineupdate']))) {
                echo TXT_MESSAGEDISCIPLINEUPDATE;
            } elseif ((!empty($_GET['msgserveurnomemployeur']))) {
                echo TXT_MESSAGESERVEUREMPLOYEUR;
            } elseif (!empty($_GET['msgemployeurupdate'])) {
                echo TXT_MESSAGEEMPLOYEURUPDATE;
            } elseif (!empty($_GET['msgErremployeurnonsaisie'])) {
                echo TXT_MESSAGEERREURNOMEMPOYEURNONSAISIE;
            } elseif (!empty($_GET['msgErrtutelleselect'])) {
                echo TXT_MESSAGEERREURTUTELLESELECT;
            } elseif (!empty($_GET['msgErrtutellenonsaisie'])) {
                echo TXT_MESSAGEERREURTUTELLENONSAISIE;
            } elseif (!empty($_GET['msgErrtutelleexiste'])) {
                echo TXT_MESSAGESERVEURTUTELLEEXISTE;
            } elseif (!empty($_GET['msgupdatetutelle'])) {
                echo TXT_MESSAGETUTELLEUPDATE;
            } elseif (!empty($_GET['msgserveurtutelle'])) {
                echo TXT_MESSAGESERVEURTUTELLE;
            }
            ?>
            <!-- input caché récupération des champs select-->

            <input type="text" name="copieCiblePays" id="copieCiblePays" style="display:none;"/>
            <input type="text" name="copiesituationgeo" id="copiesituationgeo" style="display:none;"/>
            <input type="text" name="copiesecteurActivite" id="copiesecteurActivite" style="display:none;"/>
            <input type="text" name="copietypeentreprise" id="copietypeentreprise" style="display:none;"/>
            <input type="text" name="copiediscipline" id="copiediscipline" style="display:none;"/>
            <input type="text" name="copienomemployeur" id="copienomemployeur" style="display:none;"/>
            <input type="text" name="copietutelle" id="copietutelle" style="display:none;"/>
            <tr><br><td><br></td></tr><tr><br><td><br></td></tr>

</table>

</form>
</div>
<div data-dojo-type="dijit/layout/ContentPane" title="  Projets: Listes déroulantes  " style="width: 570px; height: auto;" >
    <!-- Gestion des types de projets -->
    <form id='administListe' name='administListe' method="post" action ="controler/ctrladmin.php" data-dojo-type="dijit/form/Form" >
        <table>
            <tr><th><label for="cibletypeprojet" style="margin-left:20px"><?php echo TXT_TYPEPROJET; ?></label></th></tr>
            <tr>
                <th><div id="cibletypeprojet"></div></th>
        </tr><tr>
        <th><input type="text" id="modiftype" name="modiftype" style="height:20px;margin-left:20px; width: 220px;vertical-align:middle;"  placeholder="<?php echo TXT_TYPEPROJET; ?>"   data-dojo-type="dijit/form/ValidationTextBox"  placeholder="<?php echo TXT_SECTEURACTIVITE; ?>"
                   data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð\'()_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" ></th>
        <th><button class="admin"  type="submit" name="modiftypeProjet" ><?php echo TXT_MODIFIER; ?></button><button class="admin"  type="submit" name="ajoutetypeProjet"><?php echo TXT_AJOUTER; ?></button></th>
    </tr>    
    <tr><td><br></tr>
    <!-- Thématique -->
    <tr><th><label for="ciblethematique" style="margin-left:20px"><?php echo TXT_THEMATIQUE; ?></label></th></tr>
    <tr>
        <th><div id="ciblethematique"></div></th>
    </tr>
    <tr>
        <th><input type="text" id="modifthematique" name="modifthematique"  style="margin-left:20px;height:20px; width: 220px;vertical-align:middle;" data-dojo-type="dijit/form/ValidationTextBox"  placeholder="<?php echo TXT_THEMATIQUE; ?>"
                   data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð\'()_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" ></th>
        <th>
            <button class="admin"  type="submit" name="modifthema" ><?php echo TXT_MODIFIER; ?>
            </button><button class="admin"  type="submit" name="ajoutethema"><?php echo TXT_AJOUTER; ?></button>
        </th>
    </tr>
    <tr><td><br></td></tr>
    <!-- Statut projet -->
    <tr><th><label for="ciblestatut" style="margin-left:20px"><?php echo TXT_STATUTPROJETS; ?></label></th></tr>
    <tr>
        <th><div id="ciblestatut"></div></th>
    </tr>
    <tr>
        <th>
            <input type="text" id="modifstatut" name="modifstatut" placeholder="<?php echo TXT_STATUTPROJETS; ?>" style="margin-left:20px;height:20px; width: 220px;vertical-align:middle;" data-dojo-type="dijit/form/ValidationTextBox"
                   data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð\'()_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" ></th>
        <th>
            <button class="admin"  type="submit" name="modifstatuts" ><?php echo TXT_MODIFIER; ?></button>
            <button class="admin"  type="submit" name="ajoutestatut"><?php echo TXT_AJOUTER; ?></button>
        </th>
    </tr>
    <?php 
        if (!empty($_GET['msgErrtypeProjetnonsaisie'])) {
                    echo TXT_MESSAGEERREURTYPEPROJETNONSAISIE;
                } elseif (!empty($_GET['msgErrtypeexiste'])) {
                    echo TXT_MESSAGESERVEURTYPEEXISTE;
                } elseif (!empty($_GET['msgserveurtypeprojet'])) {
                    echo TXT_MESSAGESERVEURTYPEPROJET;
                } elseif (!empty($_GET['msgErrtypeprojetselect'])) {
                    echo TXT_MESSAGEERREURGETYPEPROJETSELECT;
                } elseif (!empty($_GET['msgtypeprojetupdate'])) {
                    echo TXT_MESSAGETYPEPROJETUPDATE;
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
    ?>
    <input type="text" name="copiestypeprojet" id="copiestypeprojet" style="display: none;"/>
    <input type="text" name="copiethematique" id="copiethematique" style="display: none;"/>
    <input type="text" name="copiestatut" id="copiestatut" style="display: none;"/>
</table>
</form>
</div>
<div data-dojo-type="dijit/layout/ContentPane" title="  Administratif:  cases à cocher  " style="width: 570px; height: auto;" >
    <form id='admincase' name='admincase' method="post" action ="controler/ctrladmin.php" data-dojo-type="dijit/form/Form" >
    <table>
        <tr><td><br></td></tr>
            <!-- Centrale -->
            <tr><th><label for="ciblecentrale" style="width:220px;margin-left:20px"><?php echo TXT_CENTRALE; ?></label></th></tr>
            <tr>
                <th><div id="ciblecentrale"></div></th>
            </tr>
            <tr>
                <th>
                    <input type="text" id="modifcentrale" name="modifcentrale" placeholder="<?php echo TXT_CENTRALE; ?>" style="height:20px;margin-left:20px; width: 220px;vertical-align:middle;" data-dojo-type="dijit/form/ValidationTextBox"
                           data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð\'()_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >
                </th>
                <th>
                    <button class="admin"  type="submit" name="modifcentrale" ><?php echo TXT_MODIFIER; ?></button>
                    <button class="admin"  type="submit" name="ajoutecentrale"><?php echo TXT_AJOUTER; ?></button>
                </th>
            </tr>
            <tr><td><br></td></tr>
            <!-- RESSOURCE -->
            <tr><th><label for="cibleressource" style="width:220px;margin-left:20px"><?php echo TXT_RESSOURCES; ?></label></th></tr>
            <tr>
                <th><div id="cibleressource"></div></th>
            </tr>
            <tr>
                <th>
                    <input type="text" id="modifressource" name="modifressource" placeholder="<?php echo TXT_RESSOURCES; ?>" style="height:20px;margin-left:20px; width: 220px;vertical-align:middle;" data-dojo-type="dijit/form/ValidationTextBox"
                           data-dojo-props="regExp:'[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð\'()_ ,.-]+'" data-dojo-invalidMessage="<?php echo TXT_ERRSTRING; ?>" >
                </th>
                <th>
                    <button class="admin"  type="submit" name="modifressource" ><?php echo TXT_MODIFIER; ?></button>
                    <button class="admin"  type="submit" name="ajouteressource"><?php echo TXT_AJOUTER; ?></button>
                </th>
            </tr>
            
    <?php 
        if (!empty($_GET['msgErrtypeProjetnonsaisie'])) {
                    echo TXT_MESSAGEERREURTYPEPROJETNONSAISIE;
                } elseif (!empty($_GET['msgErrtypeexiste'])) {
                    echo TXT_MESSAGESERVEURTYPEEXISTE;
                } elseif (!empty($_GET['msgserveurtypeprojet'])) {
                    echo TXT_MESSAGESERVEURTYPEPROJET;
                } elseif (!empty($_GET['msgErrtypeprojetselect'])) {
                    echo TXT_MESSAGEERREURGETYPEPROJETSELECT;
                } 
    ?>
            
         <input type="text" name="copiecentrale" id="copiecentrale" style="display: none;"/>
         <input type="text" name="copieressource" id="copieressource" style="display: none;"/>
    </table>
    </form>
</div>

</div>
<!--</fieldset>-->

</div><div id="Contenuindexchoix">
    <?php include 'html/footer.html'; ?>
</div>

</body>
</html>
<!-- Requête + script génération des listes déroulantes -->
<?php
$requetePays = " SELECT  nompays, nompays as autre  FROM pays order by nompays ASC";
$cheminPays = "tmp";
$nomselectPays = "selectPays.json";
$libellePays = "nompays";
$libellePays1 = "autre";
creerJson($requetePays, $cheminPays, $nomselectPays, $libellePays, $libellePays1);
?>
<script>
    require(["dijit/form/Select","dojo/data/ObjectStore","dojo/store/Memory"],
    function(Select, ObjectStore, Memory){
        var store = new Memory({
            data:	<?php require 'tmp/selectPays.json'; ?>	});
        var os = new ObjectStore({ objectStore: store });
        var s = new Select({
            store: os,
            style : 'margin-left:20px'
        }, "ciblePays");
        s.startup();
        s.on("change", function(){
            var valeur =dijit.byId('ciblePays').get('value')
            document.administ.copieCiblePays.value = valeur;
        })})
</script>
<!-- select situation géographique  -->
<?php
$requetesituationgeo = " SELECT  libellesituationgeo, libellesituationgeo as autre FROM situationgeographique order by situationgeographique ASC";
$cheminsitgeo = "tmp";
$nomselectsitgeo = "selectsituationgeo.json";
$libellesitgeo = "libellesituationgeo";
$libellesitgeo1 = "autre";
creerJson($requetesituationgeo, $cheminsitgeo, $nomselectsitgeo, $libellesitgeo, $libellesitgeo1);
?>
<script>
    require(["dijit/form/Select","dojo/data/ObjectStore","dojo/store/Memory"],
    function(Select, ObjectStore, Memory){
        var store = new Memory({
            data:	<?php require 'tmp/selectsituationgeo.json'; ?>	});
        var os = new ObjectStore({ objectStore: store });
        var s = new Select({store: os
        }, "ciblesituationgeo");
        s.startup();
        s.on("change", function(){
            var valeur =dijit.byId('ciblesituationgeo').get('value')
            document.administ.copiesituationgeo.value = valeur;
        })})
</script>
<!-- Select secteur d'ativité -->
<?php
$requetesecact = " SELECT  libellesecteuractivite,libellesecteuractivite as libsecteuractivite FROM secteuractivite ";
$cheminsectact = "tmp";
$nomselectsecact = "selectSecteurActivite.json";
$libellesecact = "libellesecteuractivite";
$libellesecact1 = "libsecteuractivite";
creerJson($requetesecact, $cheminsectact, $nomselectsecact, $libellesecact, $libellesecact1);
?>
<script>
    require(["dijit/form/Select","dojo/data/ObjectStore","dojo/store/Memory"],
    function(Select, ObjectStore, Memory){
        var store = new Memory({
            data:	<?php require 'tmp/selectSecteurActivite.json'; ?>	});
        var os = new ObjectStore({ objectStore: store });
        var s = new Select({store: os,
            style : 'margin-left:20px'
        }, "ciblesecteuractivite");
        s.startup();
        s.on("change", function(){
            var valeur =dijit.byId('ciblesecteuractivite').get('value')
            document.administ.copiesecteurActivite.value = valeur;
        })})
</script>
<!-- Type d'entreprise -->
<?php
$requetetypeentreprise = " SELECT  libelletypeentreprise,libelletypeentreprise as libeltype FROM typeentreprise ";
$chemintypeentreprise = "tmp";
$nomselecttypeentreprise = "selectLibelleTypeentreprise.json";
$libelletypeentreprise = "libelletypeentreprise";
$libelletypeentreprise1 = "libeltype";
creerJson($requetetypeentreprise, $chemintypeentreprise, $nomselecttypeentreprise, $libelletypeentreprise, $libelletypeentreprise1);
?>
<script>
    require(["dijit/form/Select","dojo/data/ObjectStore","dojo/store/Memory"],
    function(Select, ObjectStore, Memory){
        var store = new Memory({
            data:	<?php require 'tmp/selectLibelleTypeentreprise.json'; ?>	});
        var os = new ObjectStore({ objectStore: store });
        var s = new Select({store: os,
            style : 'margin-left:20px'}, "cibletypeentreprise");
        s.startup();
        s.on("change", function(){
            var valeur =dijit.byId('cibletypeentreprise').get('value')
            document.administ.copietypeentreprise.value = valeur;
        })
    })
</script>
<!-- discipline -->
<?php
$requetediscipline = " SELECT  libellediscipline, libellediscipline as libeldiscipline FROM disciplinescientifique ";
$chemindiscipline = "tmp";
$nomselectdiscipline = "selectlibellediscipline.json";
$libellediscipline = "libellediscipline";
$libellediscipline1 = "libeldiscipline";
creerJson($requetediscipline, $chemindiscipline, $nomselectdiscipline, $libellediscipline, $libellediscipline1);
?>
<script>
    require(["dijit/form/Select","dojo/data/ObjectStore","dojo/store/Memory"],
    function(Select, ObjectStore, Memory){
        var store = new Memory({
            data:	<?php require 'tmp/selectlibellediscipline.json'; ?>	});
        var os = new ObjectStore({ objectStore: store });
        var s = new Select({store: os,
            style : 'margin-left:20px'}, "ciblediscipline");
        s.startup();
        s.on("change", function(){
            var valeur =dijit.byId('ciblediscipline').get('value')
            document.administ.copiediscipline.value = valeur;
        })})
</script>
<!-- Nom employeur -->
<?php
$requeteemployeur = " SELECT  libelleemployeur, libelleemployeur as libeleemployeur FROM nomemployeur ";
$cheminemployeur = "tmp";
$nomselectemployeur = "selectlibelleemployeur.json";
$libelleemployeur = "libelleemployeur";
$libelleemployeur1 = "libeleemployeur";
creerJson($requeteemployeur, $cheminemployeur, $nomselectemployeur, $libelleemployeur, $libelleemployeur1);
?>
<script>
    require(["dijit/form/Select","dojo/data/ObjectStore","dojo/store/Memory"],
    function(Select, ObjectStore, Memory){
        var store = new Memory({
            data:	<?php require 'tmp/selectlibelleemployeur.json'; ?>	});
        var os = new ObjectStore({ objectStore: store });
        var s = new Select({store: os,
            style : 'margin-left:20px'}, "ciblenomemployeur");
        s.startup();
        s.on("change", function(){
            var valeur =dijit.byId('ciblenomemployeur').get('value')
            document.administ.copienomemployeur.value = valeur;
        })})
</script>
<!-- Tutelle -->
<?php
$requetetutelle = " SELECT  libelletutelle, libelletutelle as libeltutelle FROM tutelle";
$chemintutelle = "tmp";
$nomselecttutelle = "selectlibelletutelle.json";
$libelletutelle = "libelletutelle";
$libelletutelle1 = "libeltutelle";
creerJson($requetetutelle, $chemintutelle, $nomselecttutelle, $libelletutelle, $libelletutelle1);
?>
<script>
    require(["dijit/form/Select","dojo/data/ObjectStore","dojo/store/Memory"],
    function(Select, ObjectStore, Memory){
        var store = new Memory({
            data:	<?php require 'tmp/selectlibelletutelle.json'; ?>	});
        var os = new ObjectStore({ objectStore: store });
        var s = new Select({store: os,
            style : 'margin-left:20px'}, "cibletutelle");
        s.startup();
        s.on("change", function(){
            var valeur =dijit.byId('cibletutelle').get('value')
            document.administ.copietutelle.value = valeur;
        })})
</script>
<!-- Type de projet -->
<?php
$requetetype = " SELECT  libelletype,libelletype as libeltype FROM typeprojet where libelletype!='n/a' ";
$chemintype = "tmp";
$nomselecttype = "selectlibelletype.json";
$libelletype = "libelletype";
$libelletype1 = "libeltype";
creerJson($requetetype, $chemintype, $nomselecttype, $libelletype, $libelletype1);
?>
<script>
    require(["dijit/form/Select","dojo/data/ObjectStore","dojo/store/Memory"],
    function(Select, ObjectStore, Memory){
        var store = new Memory({
            data:	<?php require 'tmp/selectlibelletype.json'; ?>	});
        var os = new ObjectStore({ objectStore: store });
        var s = new Select({store: os,
            style : 'margin-left:20px'}, "cibletypeprojet");
        s.startup();
        s.on("change", function(){
            var valeur =dijit.byId('cibletypeprojet').get('value')
            document.administListe.copiestypeprojet.value = valeur;
        })})
</script>
<!-- Thématique -->
<?php
$requetethematique = " SELECT  libellethematique,libellethematique as libelthematique FROM thematique ";
$cheminthematique = "tmp";
$nomselectthematique = "selectlibellethematique.json";
$libellethematique = "libellethematique";
$libellethematique1 = "libelthematique";
creerJson($requetethematique, $cheminthematique, $nomselectthematique, $libellethematique, $libellethematique1);
?>
<script>
    require(["dijit/form/Select","dojo/data/ObjectStore","dojo/store/Memory"],
    function(Select, ObjectStore, Memory){
        var store = new Memory({
            data:	<?php require 'tmp/selectlibellethematique.json'; ?>	});
        var os = new ObjectStore({ objectStore: store });
        var s = new Select({store: os,
            style : 'margin-left:20px'}, "ciblethematique");
        s.startup();
        s.on("change", function(){
            var valeur =dijit.byId('ciblethematique').get('value')
            document.administListe.copiethematique.value = valeur;
        })})
</script>
<!-- Statut projet -->
<?php
$requetestatut = " SELECT  libellestatutprojet, libellestatutprojet as libelstatutprojet FROM statutprojet ";
$cheminstatut = "tmp";
$nomselectstatut = "selectlibellestatutprojet.json";
$libellestatutprojet = "libellestatutprojet";
$libellestatutprojet1 = "libelstatutprojet";
creerJson($requetestatut, $cheminstatut, $nomselectstatut, $libellestatutprojet, $libellestatutprojet1);
?>
<script>
    require(["dijit/form/Select","dojo/data/ObjectStore","dojo/store/Memory"],
    function(Select, ObjectStore, Memory){
        var store = new Memory({
            data:	<?php require 'tmp/selectlibellestatutprojet.json'; ?>	});
        var os = new ObjectStore({ objectStore: store });
        var s = new Select({store: os,
            style : 'margin-left:20px'}, "ciblestatut");
        s.startup();
        s.on("change", function(){
            var valeur =dijit.byId('ciblestatut').get('value')
            document.administListe.copiestatut.value = valeur;
        })})
</script>
<!--CENTRALE-->
<?php
$requetecentrale = " SELECT  libellecentrale,libellecentrale as libellecentral FROM centrale ";
$chemincentrale = "tmp";
$nomselectcentrale = "selectcentrale.json";
$libellecentrale = "libellecentrale";
$libellecentrale1 = "libellecentral";
creerJson($requetecentrale, $chemincentrale, $nomselectcentrale, $libellecentrale, $libellecentrale1);
?>
<script>
    require(["dijit/form/Select","dojo/data/ObjectStore","dojo/store/Memory"],
    function(Select, ObjectStore, Memory){
        var store = new Memory({
            data:	<?php require 'tmp/selectcentrale.json'; ?>	});
        var os = new ObjectStore({ objectStore: store });
        var s = new Select({store: os,
            style : 'margin-left:20px'}, "ciblecentrale");
        s.startup();
        s.on("change", function(){
            var valeur =dijit.byId('ciblecentrale').get('value')
            document.admincase.copiecentrale.value = valeur;
        })})
</script>
<!--RESSOURCE-->
<?php
$requeteressource = " SELECT  libelleressource,libelleressource as libelressource FROM ressource ";
$cheminressource = "tmp";
$nomselectressource = "selectressource.json";
$libelleressource = "libelleressource";
$libelleressource1 = "libelressource";
creerJson($requeteressource, $cheminressource, $nomselectressource, $libelleressource, $libelleressource1);
?>
<script>
    require(["dijit/form/Select","dojo/data/ObjectStore","dojo/store/Memory"],
    function(Select, ObjectStore, Memory){
        var store = new Memory({
            data:	<?php require 'tmp/selectressource.json'; ?>	});
        var os = new ObjectStore({ objectStore: store });
        var s = new Select({store: os,
            style : 'margin-left:20px'}, "cibleressource");
        s.startup();
        s.on("change", function(){
            var valeur =dijit.byId('cibleressource').get('value')
            document.admincase.copieressource.value = valeur;
        })})
</script>