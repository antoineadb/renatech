<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../class/Manager.php';
// instantiate database and product object
$db = BD::connecter();
$manager = new Manager($db);

$result = $manager->getList("select * from centrale");
$num = count($result);

// check if more than 0 record found
if ($num > 0) {
    // products array
    $products_arr = array();
    $products_arr["records"] = array();

    for ($i = 0; $i < count($result); $i++) {
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($result);

        $product_item = array(
            "idcentrale" => $result[$i]["idcentrale"],
            "libellecentrale" => $result[$i]["libellecentrale"],
            "villecentrale" => $result[$i]["villecentrale"],
            "codeunite" => $result[$i]["codeunite"],
            "email1" => $result[$i]["email1"],
            "email2" => $result[$i]["email2"]
        );

        array_push($products_arr["records"], $product_item);
    }
echo (json_encode($products_arr));

} else {
    echo json_encode(
            array("message" => "No products found.")
    );
}
?>