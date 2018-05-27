<?php
include '../jpgraph-3.5.0b1/src/jpgraph.php';
include '../jpgraph-3.5.0b1/src/jpgraph_pie.php';
include '../jpgraph-3.5.0b1/src/jpgraph_pie3d.php';


define('MYSQL_HOST', 'localhost');
define('MYSQL_USER', 'root');
define('MYSQL_PASS', '');
define('MYSQL_DATABASE', 'tuto_jp_graph');

$tableauAnnees = array();
$tableauNombreVentes = array();

// *****************************************************
// Extraction des données dans la base de données 
// **************************************************

$sql = <<<EOF
	SELECT  
		YEAR(`DTHR_VENTE`) AS ANNEE,
		COUNT(ID) AS NBR_VENTES  
	FROM `ventes`
	GROUP BY YEAR(`DTHR_VENTE`)
EOF;

$mysqlCnx = @mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASS) or die('Pb de connxion mysql');

@mysql_select_db(MYSQL_DATABASE) or die('Pb de sélection de la base');

$mysqlQuery = @mysql_query($sql, $mysqlCnx) or die('Pb de requête');

while ($row = mysql_fetch_array($mysqlQuery,  MYSQL_ASSOC)) {
	// Ajouter année devant, c'est pour la légende
	$tableauAnnees[] = "année " . $row['ANNEE'];
	$tableauNombreVentes[] = $row['NBR_VENTES'];
}

// **************************************
// Création du graphique
// *****************************************

// On spécifie la largeur et la hauteur du graph
$graph = new PieGraph(400,300);

// Ajouter une ombre au conteneur
$graph->SetShadow();

// Donner un titre
$graph->title->Set("Volume des ventes par années style PIE 3D");

// Quelle police et quel style pour le titre
// Prototype: function SetFont($aFamily,$aStyle=FS_NORMAL,$aSize=10)
// 1. famille
// 2. style
// 3. taille
$graph->title->SetFont(FF_GEORGIA,FS_BOLD, 12);

// Créer un camembert 
$pie = new PiePlot3D($tableauNombreVentes);

// Quelle partie se détache du reste
$pie->ExplodeSlice(2);

// Spécifier des couleurs personnalisées... #FF0000 ok
$pie->SetSliceColors(array('red', 'blue', 'green'));

// Légendes qui accompagnent le graphique, ici chaque année avec sa couleur
$pie->SetLegends($tableauAnnees);

// Position du graphique (0.5=centré)
$pie->SetCenter(0.4);

// Type de valeur (pourcentage ou valeurs)
$pie->SetValueType(PIE_VALUE_ABS);

// Personnalisation des étiquettes pour chaque partie
$pie->value->SetFormat('%d ventes');

// Personnaliser la police et couleur des étiquettes
$pie->value->SetFont(FF_ARIAL,FS_NORMAL, 9);
$pie->value->SetColor('blue');

// ajouter le graphique PIE3D au conteneur 
$graph->Add($pie);

// Provoquer l'affichage
$graph->Stroke();

?> 