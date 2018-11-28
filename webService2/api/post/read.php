<?php

// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
// Blog post query
$result = read();
// Get row count
$num = count($result);
// Check if any posts
if ($num > 0) {   
    $posts_arr = array();
    foreach ($result as $key => $value) {
        $post_item = array(
            'acronyme' => $result[$key]['acronyme'],
            'idprojet' => $result[$key]['idprojet'],
            'iddemandeur' => $result[$key]['iddemandeur'],
            'numero_projet' => $result[$key]['numero_projet'],
            'centrale' => $result[$key]['centrale'],
            'datedebutprojet' => $result[$key]['datedebutprojet'],            
            'duree_projet_mois' => $result[$key]['duree_projet_mois'],
            'date_fin_projet' => $result[$key]['date_fin_projet'],
            'date_statut_fini' => $result[$key]['date_statut_fini']
        );

        // Push to "data"
        array_push($posts_arr, $post_item);
        
    } 

    // Turn to JSON & output
    echo json_encode($posts_arr);
    
} else {
    // No Posts
    echo json_encode(
            array('message' => 'No Posts Found')
    );
}

function read() {
    include '../../../class/Manager.php';
    $db = BD::connecter();
    $manager = new Manager($db);
    $datas = $manager->getList("SELECT acronyme,idprojet,"
            . " idutilisateur_utilisateur as iddemandeur,"
            . " numero as numero_projet,dateprojet as date_demande,libellecentrale as centrale ,datedebutprojet,idstatutprojet as statut, dureeprojet as duree_projet_mois,"
            . " datedebutprojet + interval  '1 month' * dureeprojet as date_fin_projet, datestatutfini as date_statut_fini"
            . " FROM PROJET "
            . " LEFT JOIN concerne co on co.idprojet_projet=idprojet "
            . " LEFT JOIN creer c on c.idprojet_projet=idprojet "
            . " LEFT JOIN centrale on idcentrale_centrale =idcentrale "
            . " LEFT JOIN statutprojet on idstatutprojet_statutprojet =idstatutprojet "
            . " WHERE datedebutprojet BETWEEN '2017-01-01' AND '2018-12-31' AND confidentiel is not TRUE AND idprojet IN(2024,1006)");
    for ($i = 0; $i < count($datas); $i++) {
        foreach ($datas[$i] as $key => $value) {
            if (is_int($key)) {
                unset($datas[$i][$key]);
            }
        }
    }
    return $datas;
}