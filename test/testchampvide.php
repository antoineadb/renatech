<?php

include '../class/Manager.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER
//DELETE FROM partenaireprojet where nompartenaire='' and nomlaboentreprise=''
/*
$a_idpartenairevide = $manager->getListbyArray("select idpartenaire from partenaireprojet where nompartenaire=? and nomlaboentreprise=? order by idpartenaire asc", array('', ''));
//echo '<pre>';print_r($a_idpartenairevide);echo '</pre>';
for ($i = 0; $i < count($a_idpartenairevide); $i++) {
    echo $manager->getSingle2("select idprojet_projet from projetpartenaire where idpartenaire_partenaireprojet = ? order by idprojet_projet asc", $a_idpartenairevide[$i][0]) . '<br>';
}

*/
$arrayidprojet = array(array("idprojet" => 3),array("idprojet" => 4),array("idprojet" => 77),array("idprojet" => 84),
array("idprojet" => 85),array("idprojet" => 86),array("idprojet" => 87),array("idprojet" => 88),array("idprojet" => 91),array("idprojet" => 93),array("idprojet" => 94),array("idprojet" => 95),array("idprojet" => 96),
array("idprojet" => 100),array("idprojet" => 101),array("idprojet" => 106),array("idprojet" => 107),array("idprojet" => 111),array("idprojet" => 112),array("idprojet" => 113),array("idprojet" => 114),array("idprojet" => 116),
array("idprojet" => 118),array("idprojet" => 119),array("idprojet" => 120),array("idprojet" => 121),array("idprojet" => 122),array("idprojet" => 126),array("idprojet" => 127),array("idprojet" => 128),array("idprojet" => 129),
array("idprojet" => 132),array("idprojet" => 134),array("idprojet" => 135),array("idprojet" => 139),array("idprojet" => 140),array("idprojet" => 149),array("idprojet" => 150),array("idprojet" => 154),array("idprojet" => 155),
array("idprojet" => 158),array("idprojet" => 160),array("idprojet" => 161),array("idprojet" => 162),array("idprojet" => 163),array("idprojet" => 165),array("idprojet" => 166),array("idprojet" => 169),array("idprojet" => 170),
array("idprojet" => 180),array("idprojet" => 184),array("idprojet" => 186),array("idprojet" => 188),array("idprojet" => 190),array("idprojet" => 191),array("idprojet" => 192),array("idprojet" => 196),array("idprojet" => 197),
array("idprojet" => 199),array("idprojet" => 202),array("idprojet" => 203),array("idprojet" => 206),array("idprojet" => 207),array("idprojet" => 208),array("idprojet" => 209),array("idprojet" => 210),array("idprojet" => 211),
array("idprojet" => 213),array("idprojet" => 214),array("idprojet" => 215),array("idprojet" => 216),array("idprojet" => 217),array("idprojet" => 219),array("idprojet" => 221),array("idprojet" => 222),array("idprojet" => 223),
array("idprojet" => 224),array("idprojet" => 225),array("idprojet" => 226),array("idprojet" => 228),array("idprojet" => 229),array("idprojet" => 230),array("idprojet" => 231),array("idprojet" => 232),array("idprojet" => 233),
array("idprojet" => 234),array("idprojet" => 235),array("idprojet" => 237),array("idprojet" => 238),array("idprojet" => 239),array("idprojet" => 240),array("idprojet" => 241),array("idprojet" => 242),array("idprojet" => 244),
array("idprojet" => 245),array("idprojet" => 246),array("idprojet" => 248),array("idprojet" => 251),array("idprojet" => 252),array("idprojet" => 253),array("idprojet" => 254),array("idprojet" => 256),array("idprojet" => 257),
array("idprojet" => 258),array("idprojet" => 259),array("idprojet" => 265),array("idprojet" => 266),array("idprojet" => 267),array("idprojet" => 268),array("idprojet" => 270),array("idprojet" => 276),array("idprojet" => 277),
array("idprojet" => 278),array("idprojet" => 279),array("idprojet" => 280),array("idprojet" => 281),array("idprojet" => 282),array("idprojet" => 283),array("idprojet" => 284),array("idprojet" => 297),array("idprojet" => 299),
array("idprojet" => 301),array("idprojet" => 302),array("idprojet" => 304),array("idprojet" => 311),array("idprojet" => 355),array("idprojet" => 379),array("idprojet" => 380),array("idprojet" => 381),array("idprojet" => 382),
array("idprojet" => 469),array("idprojet" => 471),array("idprojet" => 474),array("idprojet" => 475),array("idprojet" => 476),array("idprojet" => 481),array("idprojet" => 483),array("idprojet" => 493),array("idprojet" => 495),
array("idprojet" => 498),array("idprojet" => 500),array("idprojet" => 501),array("idprojet" => 503),array("idprojet" => 504),array("idprojet" => 505),array("idprojet" => 509),array("idprojet" => 513),array("idprojet" => 515),
array("idprojet" => 520),array("idprojet" => 521),array("idprojet" => 522),array("idprojet" => 523),array("idprojet" => 525),array("idprojet" => 526),array("idprojet" => 527),array("idprojet" => 528),array("idprojet" => 529),
array("idprojet" => 530),array("idprojet" => 531),array("idprojet" => 532),array("idprojet" => 534),array("idprojet" => 537),array("idprojet" => 538),array("idprojet" => 539),array("idprojet" => 540),array("idprojet" => 541),
array("idprojet" => 543),array("idprojet" => 544),array("idprojet" => 545),array("idprojet" => 546),array("idprojet" => 547),array("idprojet" => 548),array("idprojet" => 549),array("idprojet" => 550),array("idprojet" => 553),
array("idprojet" => 554),array("idprojet" => 555),array("idprojet" => 559),array("idprojet" => 560),array("idprojet" => 561),array("idprojet" => 562),array("idprojet" => 563),array("idprojet" => 564),array("idprojet" => 565),
array("idprojet" => 566),array("idprojet" => 567),array("idprojet" => 568),array("idprojet" => 569),array("idprojet" => 570),array("idprojet" => 571),array("idprojet" => 572),array("idprojet" => 573),array("idprojet" => 574),
array("idprojet" => 575),array("idprojet" => 576),array("idprojet" => 577),array("idprojet" => 578),array("idprojet" => 579),array("idprojet" => 580),array("idprojet" => 581),array("idprojet" => 582),array("idprojet" => 583),
array("idprojet" => 584),array("idprojet" => 585),array("idprojet" => 586),array("idprojet" => 587),array("idprojet" => 588),array("idprojet" => 589),array("idprojet" => 590),array("idprojet" => 591),array("idprojet" => 592),
array("idprojet" => 593),array("idprojet" => 594),array("idprojet" => 595),array("idprojet" => 596),array("idprojet" => 597),array("idprojet" => 598),array("idprojet" => 599),array("idprojet" => 600),array("idprojet" => 601),
array("idprojet" => 602),array("idprojet" => 603),array("idprojet" => 604),array("idprojet" => 605),array("idprojet" => 606),array("idprojet" => 607),array("idprojet" => 608),array("idprojet" => 609),array("idprojet" => 610),
array("idprojet" => 611),array("idprojet" => 612),array("idprojet" => 613),array("idprojet" => 614),array("idprojet" => 615),array("idprojet" => 616),array("idprojet" => 617),array("idprojet" => 618),array("idprojet" => 619),
array("idprojet" => 620),array("idprojet" => 621),array("idprojet" => 622),array("idprojet" => 623),array("idprojet" => 624),array("idprojet" => 625),array("idprojet" => 626),array("idprojet" => 627),array("idprojet" => 628),
array("idprojet" => 629),array("idprojet" => 630),array("idprojet" => 631),array("idprojet" => 632),array("idprojet" => 633),array("idprojet" => 634),array("idprojet" => 635),array("idprojet" => 636),array("idprojet" => 637),
array("idprojet" => 638),array("idprojet" => 639),array("idprojet" => 640),array("idprojet" => 642),array("idprojet" => 643),array("idprojet" => 644),array("idprojet" => 646),array("idprojet" => 647),array("idprojet" => 648),
array("idprojet" => 649),array("idprojet" => 650),array("idprojet" => 651),array("idprojet" => 652),array("idprojet" => 653),array("idprojet" => 654),array("idprojet" => 655),array("idprojet" => 656),array("idprojet" => 657),
array("idprojet" => 658),array("idprojet" => 660),array("idprojet" => 661),array("idprojet" => 662),array("idprojet" => 663),array("idprojet" => 664),array("idprojet" => 665),array("idprojet" => 666),array("idprojet" => 667),
array("idprojet" => 668),array("idprojet" => 669),array("idprojet" => 670),array("idprojet" => 671),array("idprojet" => 672),array("idprojet" => 673),array("idprojet" => 674),array("idprojet" => 675),array("idprojet" => 676),
array("idprojet" => 677),array("idprojet" => 678),array("idprojet" => 680),array("idprojet" => 685),array("idprojet" => 687),array("idprojet" => 690),array("idprojet" => 697),array("idprojet" => 698),array("idprojet" => 712),
array("idprojet" => 713),array("idprojet" => 717),array("idprojet" => 719),array("idprojet" => 735),array("idprojet" => 737),array("idprojet" => 740),array("idprojet" => 741),array("idprojet" => 742),array("idprojet" => 746),
array("idprojet" => 747),array("idprojet" => 748),array("idprojet" => 749),array("idprojet" => 754),array("idprojet" => 755),array("idprojet" => 757),array("idprojet" => 761),array("idprojet" => 763),array("idprojet" => 764),
array("idprojet" => 767),array("idprojet" => 775),array("idprojet" => 778),array("idprojet" => 781),array("idprojet" => 782),array("idprojet" => 789),array("idprojet" => 798),array("idprojet" => 799),array("idprojet" => 800),
array("idprojet" => 801),array("idprojet" => 802),array("idprojet" => 803),array("idprojet" => 804),array("idprojet" => 805),array("idprojet" => 806),array("idprojet" => 807),array("idprojet" => 808),array("idprojet" => 809),
array("idprojet" => 810),array("idprojet" => 811),array("idprojet" => 812),array("idprojet" => 813),array("idprojet" => 814),array("idprojet" => 816),array("idprojet" => 817),array("idprojet" => 818),array("idprojet" => 819),
array("idprojet" => 820),array("idprojet" => 822),array("idprojet" => 823),array("idprojet" => 824),array("idprojet" => 825),array("idprojet" => 826),array("idprojet" => 827),array("idprojet" => 828),array("idprojet" => 829),
array("idprojet" => 830),array("idprojet" => 831),array("idprojet" => 832),array("idprojet" => 833),array("idprojet" => 834),array("idprojet" => 835),array("idprojet" => 836),array("idprojet" => 837),array("idprojet" => 838),
array("idprojet" => 839),array("idprojet" => 840),array("idprojet" => 841),array("idprojet" => 842),array("idprojet" => 843),array("idprojet" => 844),array("idprojet" => 845),array("idprojet" => 853),array("idprojet" => 860),
array("idprojet" => 904),array("idprojet" => 932),array("idprojet" => 935),array("idprojet" => 958),array("idprojet" => 960),array("idprojet" => 995),array("idprojet" => 996),array("idprojet" => 1017),array("idprojet" => 1018),
array("idprojet" => 1020),array("idprojet" => 1022),array("idprojet" => 1023),array("idprojet" => 1024),array("idprojet" => 1028),array("idprojet" => 1029),array("idprojet" => 1031),array("idprojet" => 1032),array("idprojet" => 1033),
array("idprojet" => 1038),array("idprojet" => 1039),array("idprojet" => 1040),array("idprojet" => 1042),array("idprojet" => 1044),array("idprojet" => 1045),array("idprojet" => 1052),array("idprojet" => 1054),array("idprojet" => 1056),
array("idprojet" => 1057),array("idprojet" => 1059),array("idprojet" => 1061),array("idprojet" => 1063),array("idprojet" => 1064),array("idprojet" => 1065),array("idprojet" => 1068),array("idprojet" => 1069),array("idprojet" => 1070),
array("idprojet" => 1071)
);
//echo '<pre>';print_r($arrayidprojet);echo '</pre>';
for ($i = 0; $i < count($arrayidprojet); $i++) {
    //			echo $manager->getSingle2("select idpartenaire_partenaireprojet  from projetpartenaire  where idprojet_projet =? ",	$arrayidprojet[$i]['idprojet']).'<br>';
    $manager->exeRequete("delete from projetpartenaire where idprojet_projet=" . $arrayidprojet[$i]['idprojet'] . " and idpartenaire_partenaireprojet in (
select idpartenaire from partenaireprojet where nompartenaire='' and nomlaboentreprise='')");
}

$manager->exeRequete("DELETE FROM partenaireprojet where nompartenaire='' and nomlaboentreprise=''");
$arrayidprojet0 =array(
array("idprojet"=>859),array("idprojet"=>894),array("idprojet"=>896),array("idprojet"=>861),array("idprojet"=>862),array("idprojet"=>863),array("idprojet"=>864),array("idprojet"=>865),
array("idprojet"=>867),array("idprojet"=>868),array("idprojet"=>869),array("idprojet"=>870),array("idprojet"=>871),array("idprojet"=>872),array("idprojet"=>873),array("idprojet"=>874),array("idprojet"=>875),
array("idprojet"=>876),array("idprojet"=>877),array("idprojet"=>879),array("idprojet"=>881),array("idprojet"=>882),array("idprojet"=>883),array("idprojet"=>884),array("idprojet"=>885),array("idprojet"=>886),
array("idprojet"=>887),array("idprojet"=>888),array("idprojet"=>889),array("idprojet"=>890),array("idprojet"=>891),array("idprojet"=>892),array("idprojet"=>893),array("idprojet"=>924),array("idprojet"=>923),
array("idprojet"=>922),array("idprojet"=>921),array("idprojet"=>915),array("idprojet"=>913),array("idprojet"=>907),array("idprojet"=>910),array("idprojet"=>908),array("idprojet"=>901),array("idprojet"=>906),
array("idprojet"=>903),array("idprojet"=>925),array("idprojet"=>905),array("idprojet"=>909),array("idprojet"=>926),array("idprojet"=>927),array("idprojet"=>928),array("idprojet"=>929),array("idprojet"=>930),
array("idprojet"=>931),array("idprojet"=>933),array("idprojet"=>938),array("idprojet"=>949),array("idprojet"=>951),array("idprojet"=>964),array("idprojet"=>965),array("idprojet"=>966),array("idprojet"=>967),
array("idprojet"=>936),array("idprojet"=>945),array("idprojet"=>992),array("idprojet"=>999),array("idprojet"=>1008),array("idprojet"=>1009),array("idprojet"=>1077),array("idprojet"=>1078),array("idprojet"=>1079),
array("idprojet"=>952),array("idprojet"=>1007),array("idprojet"=>981),array("idprojet"=>1074),array("idprojet"=>1013),array("idprojet"=>1090),array("idprojet"=>1000),array("idprojet"=>1084),array("idprojet"=>911),
array("idprojet"=>1101),array("idprojet"=>1102)
);
$arrayidprojet1 =array(
array("idprojet"=>518),array("idprojet"=>536),array("idprojet"=>542),array("idprojet"=>551),array("idprojet"=>552),array("idprojet"=>556),array("idprojet"=>557),array("idprojet"=>558),array("idprojet"=>524),
array("idprojet"=>519),array("idprojet"=>895),array("idprojet"=>964),array("idprojet"=>965),array("idprojet"=>1004),array("idprojet"=>963),array("idprojet"=>1012),array("idprojet"=>920),array("idprojet"=>993),
array("idprojet"=>1088),array("idprojet"=>1086),array("idprojet"=>970),array("idprojet"=>1089),array("idprojet"=>1103),array("idprojet"=>1104));

$arrayidprojet2 =array(
array("idprojet"=>858),array("idprojet"=>897),array("idprojet"=>964),array("idprojet"=>965),array("idprojet"=>968),array("idprojet"=>972),array("idprojet"=>976),array("idprojet"=>982),array("idprojet"=>943),
array("idprojet"=>969)
);

$arrayidprojet3 =array(array("idprojet"=>956),array("idprojet"=>964),array("idprojet"=>965));


//echo '<pre>';print_r($arrayidprojet);echo '</pre>';


for ($i = 0; $i < count($arrayidprojet0); $i++) { 
        $manager->exeRequete("delete from projetpartenaire where idprojet_projet=" . $arrayidprojet0[$i]['idprojet'] . " and idpartenaire_partenaireprojet in (
    select idpartenaire from partenaireprojet where nompartenaire='nomPartenaire0' and nomlaboentreprise='nomLaboEntreprise0')");
}
for ($i = 0; $i < count($arrayidprojet1); $i++) { 
    $manager->exeRequete("delete from projetpartenaire where idprojet_projet=" . $arrayidprojet1[$i]['idprojet'] . " and idpartenaire_partenaireprojet in (
select idpartenaire from partenaireprojet where nompartenaire='nomPartenaire1' and nomlaboentreprise='nomLaboEntreprise1')");
}
for ($i = 0; $i < count($arrayidprojet2); $i++) { 
    $manager->exeRequete("delete from projetpartenaire where idprojet_projet=" . $arrayidprojet2[$i]['idprojet'] . " and idpartenaire_partenaireprojet in (
select idpartenaire from partenaireprojet where nompartenaire='nomPartenaire2' and nomlaboentreprise='nomLaboEntreprise2')");
}
for ($i = 0; $i < count($arrayidprojet3); $i++) { 
    $manager->exeRequete("delete from projetpartenaire where idprojet_projet=" . $arrayidprojet3[$i]['idprojet'] . " and idpartenaire_partenaireprojet in (
select idpartenaire from partenaireprojet where nompartenaire='nomPartenaire3' and nomlaboentreprise='nomLaboEntreprise3')");
}

$manager->exeRequete("DELETE FROM partenaireprojet where nompartenaire='nomPartenaire0' and nomlaboentreprise='nomLaboEntreprise0'");
$manager->exeRequete("DELETE FROM partenaireprojet where nompartenaire='nomPartenaire1' and nomlaboentreprise='nomLaboEntreprise1'");
$manager->exeRequete("DELETE FROM partenaireprojet where nompartenaire='nomPartenaire2' and nomlaboentreprise='nomLaboEntreprise2'");
$manager->exeRequete("DELETE FROM partenaireprojet where nompartenaire='nomPartenaire3' and nomlaboentreprise='nomLaboEntreprise3'");
 