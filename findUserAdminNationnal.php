<?php
include_once 'decide-lang.php';
include_once 'class/Manager.php';

if (isset($_POST['page_precedente']) && $_POST['page_precedente'] == 'gestiondroitadminnationnal.php' || $_GET['page_precedente'] == 'ctrlcompte.php') {
    $db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
    $manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
    include_once 'outils/toolBox.php';
    if (isset($_SESSION['pseudo'])) {
        check_authent($_SESSION['pseudo']);
    } else {
        echo '<script>window.location.replace("erreurlogin.php")</script>';
    }
    if (!empty($_POST['nom']) && $_POST['nom'] != '*') {
        $nom = $_POST['nom'];
        $req = "SELECT u.nom, u.prenom, c.libellecentrale,u.idutilisateur, t.libelletype FROM   utilisateur u,centrale c,typeutilisateur t
        WHERE u.idcentrale_centrale = c.idcentrale AND u.idtypeutilisateur_typeutilisateur = t.idtypeutilisateur  
        and nom = ? and idqualitedemandeuraca_qualitedemandeuraca >0";
        $param = array($nom);
    } else {
        $req = "select * from
        (
            SELECT distinct u.idutilisateur, u.nom, u.prenom,null as libellecentrale, t.libelletype FROM   utilisateur u,typeutilisateur t
            WHERE u.idtypeutilisateur_typeutilisateur = t.idtypeutilisateur and idqualitedemandeuraca_qualitedemandeuraca is null 
            union
            SELECT distinct u.idutilisateur, u.nom, u.prenom,c.libellecentrale,  t.libelletype FROM   utilisateur u,typeutilisateur t,centrale c
            WHERE u.idtypeutilisateur_typeutilisateur = t.idtypeutilisateur and u.idcentrale_centrale = c.idcentrale 
            union
            SELECT distinct u.idutilisateur, u.nom, u.prenom,null as libellecentrale, t.libelletype FROM   utilisateur u,typeutilisateur t
            WHERE u.idtypeutilisateur_typeutilisateur = t.idtypeutilisateur and idqualitedemandeuraca_qualitedemandeuraca is not null and idcentrale_centrale is null 
            )as foo
            order by idutilisateur asc ";       
    }

    if (isset($_POST['prenom']) && !empty($_POST['prenom'])) {
        $prenom = $_POST['prenom'];
        $req = "SELECT u.nom, u.prenom, c.libellecentrale,u.idutilisateur, t.libelletype FROM   utilisateur u,centrale c,typeutilisateur t
        WHERE u.idcentrale_centrale = c.idcentrale AND u.idtypeutilisateur_typeutilisateur = t.idtypeutilisateur  and prenom =? and idqualitedemandeuraca_qualitedemandeuraca >0";
        $param = array($prenom);
    }

    if (isset($_POST['nom']) && !empty($_POST['nom']) && isset($_POST['prenom']) && !empty($_POST['prenom'])) {
        $req = "SELECT u.nom, u.prenom, c.libellecentrale,u.idutilisateur, t.libelletype FROM   utilisateur u,centrale c,typeutilisateur t
        WHERE u.idcentrale_centrale = c.idcentrale AND u.idtypeutilisateur_typeutilisateur = t.idtypeutilisateur  and nom =? and prenom =? and idqualitedemandeuraca_qualitedemandeuraca >0";
        $param = array($nom, $prenom);
    }
    ?>

    <?php
    if (isset($_GET['idutilisateur'])) {
        $req = "SELECT u.nom, u.prenom, c.libellecentrale,u.idutilisateur, t.libelletype FROM   utilisateur u,centrale c,typeutilisateur t
        WHERE u.idcentrale_centrale = c.idcentrale AND u.idtypeutilisateur_typeutilisateur = t.idtypeutilisateur and  idutilisateur =? and idqualitedemandeuraca_qualitedemandeuraca >0";
        $param = array($_GET['idutilisateur']);
    }
    $row = $manager->getListbyArray($req, $param);

    $fprow = fopen('tmp/userAdmin.json', 'w');
    $datauserAdmin = "";
    fwrite($fprow, '{"items": [');
    for ($i = 0; $i < count($row); $i++) {
        $datauserAdmin = "" . '{"nom":' . '"' . $row[$i]['nom'] . '"' . "," . '"prenom":' . '"' . $row[$i]['prenom'] . '"' . "," .
                '"libellecentrale":' . '"' . $row[$i]['libellecentrale'] . '"' . "," .
                '"libelletype":' . '"' . $row[$i]['libelletype'] . '"' . "," .
                '"idutilisateur":' . '"' . $row[$i]['idutilisateur'] . '"' . "},";
        fputs($fprow, $datauserAdmin);
        fwrite($fprow, '');
    }
    fwrite($fprow, ']}');
    $json_fileuserAdmin = "tmp/userAdmin.json";
    $jsonuserAdmin1 = file_get_contents($json_fileuserAdmin);
    $jsonuserAdmin = str_replace('},]}', '}]}', $jsonuserAdmin1);
    file_put_contents($json_fileuserAdmin, $jsonuserAdmin);
    fclose($fprow);
    chmod('tmp/userAdmin.json', 0777);

    $i = count($row);
    if ($i == 0) {
        echo TXT_NORESULT;
    } else {
        echo '<div style="text-align:center;width: auto;height:25px" >' . TXT_NBRESULT . ' :' . $i . '</div>';
    }
    ?>
    <div style="height: 350px;">
        <div id="grideuserAdmin" ></div>
        <script>
            var grideuserAdmin, dataStore, store;
            require([
                "dojox/grid/DataGrid",
                "dojo/store/Memory",
                "dojo/data/ObjectStore",
                "dojo/request",
                "dojo/domReady!"
            ], function(DataGrid, Memory, ObjectStore, request){
                request.get("tmp/userAdmin.json", {
                    handleAs: "json"
                }).then(function(data){
                    store = new Memory({ data: data.items });
                    dataStore = new ObjectStore({ objectStore: store });
                                                
                    function hrefFormatterNom(nom,idx){
                        var item = grideuserAdmin.getItem(idx);
                        var idqualiteaca =item.idqualitedemandeuraca_qualitedemandeuraca;
                        var valeuidqualiteindust =item.idqualitedemandeurindust_qualitedemandeurindust;
                        var iduser = item.idutilisateur;
                        return "<a  href=\"gestioncompteadminnationnal.php?lang=<?php echo $lang; ?>&iduser="+iduser+"&idqualiteaca=" + idqualiteaca+"&idqualiteindust= "+valeuidqualiteindust+" \">"+nom+"</a>";    
                    }
                    function hrefFormatterPrenom(prenom,idx){
                        var item = grideuserAdmin.getItem(idx);
                        var idqualiteaca =item.idqualitedemandeuraca_qualitedemandeuraca;
                        var valeuidqualiteindust =item.idqualitedemandeurindust_qualitedemandeurindust;
                        var iduser = item.idutilisateur;
                        return "<a  href=\"gestioncompteadminnationnal.php?lang=<?php echo $lang; ?>&iduser="+iduser+"&idqualiteaca=" + idqualiteaca+"&idqualiteindust= "+valeuidqualiteindust+" \">"+prenom+"</a>";    
                    }
                                                
                    function hrefFormatterPseudo(pseudo,idx){
                        var item = grideuserAdmin.getItem(idx);
                        var idqualiteaca =item.idqualitedemandeuraca_qualitedemandeuraca;
                        var valeuidqualiteindust =item.idqualitedemandeurindust_qualitedemandeurindust;
                        var iduser = item.idutilisateur;
                        return "<a  href=\"gestioncompteadminnationnal.php?lang=<?php echo $lang; ?>&iduser="+iduser+"&idqualiteaca=" + idqualiteaca+"&idqualiteindust= "+valeuidqualiteindust+" \">"+pseudo+"</a>";               
                    }           
                    function hrefFormatterDate(date,idx){
                        var item = grideuserAdmin.getItem(idx);
                        var idqualiteaca =item.idqualitedemandeuraca_qualitedemandeuraca;
                        var valeuidqualiteindust =item.idqualitedemandeurindust_qualitedemandeurindust;
                        var iduser = item.idutilisateur;
                        return "<a  href=\"gestioncompteadminnationnal.php?lang=<?php echo $lang; ?>&iduser="+iduser+"&idqualiteaca=" + idqualiteaca+"&idqualiteindust= "+valeuidqualiteindust+" \">"+date+"</a>";    
                    }
                                           
                                                                
                    grideuserAdmin = new DataGrid({
                        store: dataStore,
                        query: { id: "*" },
                        structure: [
                            { name: "Nom", field: "nom", width: "auto",formatter: hrefFormatterNom},
                            { name: "Prénom", field: "prenom", width: "auto" ,formatter: hrefFormatterPreom},
                            { name: "Centrale", field: "libellecentrale", width: "auto",formatter: hrefFormatterCentrale},
                            { name: "Droit actuel", field: "libelletype", width: "auto" ,formatter: hrefFormatterDroit},
                        ]
                    }, "grideuserAdmin");
                    grideuserAdmin.startup();
                });
            });
        </script>
    </div>

    <?php
    /*
      $i = count($row);
      echo '<table style="background-color: #EDEFED; border: 1px solid #57758D;clear: both;margin-top: 1px;padding: 0;width: 535px;"><th></th><th style="background-color: #57758D;padding-left:6px; color: #FFFFFF;">' . TXT_NOM . '</th><th style="background-color: #57758D;padding-left:6px; color: #FFFFFF;">' . TXT_PRENOM . '</th><th style="background-color: #57758D;padding-left:6px;color: #FFFFFF;">' . TXT_CENTRALE . '</th><th style="background-color: #57758D;padding-left:6px; color: #FFFFFF;">' . TXT_DROITACTUEL . '</th>';
      for ($j = 0; $j < count($row); $j++) {
      $resRequeteNom[] = $row[$j]['nom'];
      $resRequetePrenom[] = $row[$j]['prenom'];
      $resRequeteCentrale[] = $row[$j]['libellecentrale'];
      $resRequeteIdUser[] = $row[$j]['idutilisateur'];
      $libelleUser[] = $row[$j]['libelletype'];
      echo '
      <tr>
      <td><input style="display:none"   type="text" id="iduser' . $j . '" value="' . $resRequeteIdUser[$j] . '" /></td>
      <td><button class="role" onclick="copieNom(\'iduser' . $j . '\',\'idnom' . $j . '\');" style="width:160px;text-align:left;color: #006699;border-bottom: 1px dotted #CBD2D6;" id="idnom' . $j . '" name="' . $resRequeteNom[$j] . '" value="' . $resRequeteNom[$j] . '"/>' . $resRequeteNom[$j] . '</button></td>
      <td><button class="role" onclick="copieNom(\'iduser' . $j . '\',\'idnom' . $j . '\');" style="width:100px;text-align:left;color: #006699;border-bottom: 1px dotted #CBD2D6;" id="idprenom' . $j . '" name="' . $resRequetePrenom[$j] . '" value="' . $resRequetePrenom[$j] . '">' . $resRequetePrenom[$j] . '</button></td>
      <td><button class="role" onclick="copieNom(\'iduser' . $j . '\',\'idnom' . $j . '\');" style="width:120px;text-align:left;color: #006699;border-bottom: 1px dotted #CBD2D6;" id="idcentrale' . $j . '" name="' . $resRequeteCentrale[$j] . '" value="' . $resRequeteCentrale[$j] . '">' . $resRequeteCentrale[$j] . '</button></td>
      <td style="width:180px;text-align:left;color: #006699;border-bottom: 1px dotted #CBD2D6;">' . $libelleUser[$j] . '</td>
      </tr>';
      }
      if ($i == 0) {
      echo 'pas de résultats';
      } else {
      echo '<div style="text-align:center;width: 535px;" >Nombre de résultats : ' . $i . '</div>';
      }
      echo '</table>';
     *  <script>
      function copieNom(id1,id2){
      document.getElementById('fielddroit').style.display='block';
      document.getElementById('copienom').innerHTML=document.getElementById(id2).name;
      document.getElementById('copieNom').value=document.getElementById(id1).value;
      }
      </script>
     */
} else {
    echo '<script>window.location.replace("erreurlogin.php?lang=' . $lang . '")</script>';
}
BD::deconnecter();
?>