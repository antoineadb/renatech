<?php

include '../class/Manager.php';
//FERMETURE DE LA CONNEXION
$db = BD::connecter();
$manager = new Manager($db);
include_once '../outils/toolBox.php';
include '../class/email.php';

$arrayprojetphase2 = 
  array(
//array("refinterneprojet" =>"13-171","email"=>"fabien.volpi@simap.grenoble-inp.fr"),
//array("refinterneprojet" =>"13-148","email"=>"nacer.aitmani@cea.fr"),
//array("refinterneprojet" =>"13-168","email"=>"christophe.vallee@ujf-grenoble.fr"),
//array("refinterneprojet" =>"13-079","email"=>"tony.maindron@cea.fr"),
//array("refinterneprojet" =>"13-145","email"=>"caroline.celle@cea.fr"),
//array("refinterneprojet" =>"13-116","email"=>"david.peyrade@cea.fr"),
//array("refinterneprojet" =>"13-101","email"=>"nicolas.pauc@cea.fr"),
//array("refinterneprojet" =>"13-054","email"=>"emmanuel.defay@cea.fr"),
//array("refinterneprojet" =>"13-051","email"=>"vincent.agache@cea.fr"),
//array("refinterneprojet" =>"13-134","email"=>"anne.bernand-mantel@grenoble.cnrs.fr"),
//array("refinterneprojet" =>"13-117","email"=>"david.peyrade@cea.fr"),
//array("refinterneprojet" =>"13-073","email"=>"laurent.montes@grenoble-inp.fr"),
//array("refinterneprojet" =>"13-163","email"=>"andrei.sabac@insa-lyon.fr"),
//array("refinterneprojet" =>"13-110","email"=>"simon.perraud@cea.fr"),
//array("refinterneprojet" =>"13-109","email"=>"delphine.boutry@cea.fr"),
//array("refinterneprojet" =>"13-007","email"=>"jean-herve.tortai@cea.fr"),
//array("refinterneprojet" =>"13-071","email"=>"laurent.vila@cea.fr"),
//array("refinterneprojet" =>"13-144","email"=>"christopher.bauerle@grenoble.cnrs.fr"),
//array("refinterneprojet" =>"13-126","email"=>"bano@minatec.inpg.fr"),
//array("refinterneprojet" =>"13-099","email"=>"nora.dempsey@grenoble.cnrs.fr"),
//array("refinterneprojet" =>"13-100","email"=>"nora.dempsey@grenoble.cnrs.fr"),
//array("refinterneprojet" =>"13-091","email"=>"roch.espiau-de-lamaestre@cea.fr"),
//array("refinterneprojet" =>"13-132","email"=>"thierry.chevolleau@cea.fr"),
//array("refinterneprojet" =>"13-133","email"=>"thierry.chevolleau@cea.fr"),
//array("refinterneprojet" =>"13-113","email"=>"bassem.salem@cea.fr"),
//array("refinterneprojet" =>"13-160","email"=>"thierry.baron@cea.fr"),
//array("refinterneprojet" =>"13-159","email"=>"manuel.medranomunoz@cea.fr"),
//array("refinterneprojet" =>"13-114","email"=>"gilles.gaudin@cea.fr"),
//array("refinterneprojet" =>"13-157","email"=>"emmanuelle.pauliac-vaujour@cea.fr"),
//array("refinterneprojet" =>"13-149","email"=>"bucci@minatec.grenoble-inp.fr"),
//array("refinterneprojet" =>"13-107","email"=>"frederic.revol-cavalier@cea.fr"),
///array("refinterneprojet" =>"13-106","email"=>"frederic.revol-cavalier@cea.fr"),
//array("refinterneprojet" =>"13-112","email"=>"laurent.montes@grenoble-inp.fr"),
//array("refinterneprojet" =>"13-151","email"=>"roberto.calemczuk@cea.fr"),
//array("refinterneprojet" =>"13-089","email"=>"thierry.baron@cea.fr"),
array("refinterneprojet" =>"13-118","email"=>"david.peyrade@cea.fr"),
array("refinterneprojet" =>"13-111","email"=>"ardilarg@minatec.inpg.fr"),
array("refinterneprojet" =>"13-029","email"=>"pascale.maldivi@cea.fr"),
array("refinterneprojet" =>"13-115","email"=>"estelle.lebaron@cea.fr"),
array("refinterneprojet" =>"13-105","email"=>"guy.parat@cea.rf"),
array("refinterneprojet" =>"13-086","email"=>"Etienne.Gheeraert@grenoble.cnrs.fr"),
array("refinterneprojet" =>"13-062","email"=>"claude.chapelier@cea.fr"),
array("refinterneprojet" =>"13-005","email"=>"gilles.gaudin@cea.fr"),
array("refinterneprojet" =>"13-162","email"=>"christophe.poulain@cea.fr"),
array("refinterneprojet" =>"13-082","email"=>"lamya.ghenim@cea.fr"),
array("refinterneprojet" =>"13-166","email"=>"christophe.poulain@cea.fr"),
array("refinterneprojet" =>"13-119","email"=>"tao.zhou@cea.fr"),
array("refinterneprojet" =>"13-169","email"=>"rouger.nicolas@gmail.com"),
array("refinterneprojet" =>"13-170","email"=>"cecile.gourgon@cea.fr")
        );

for ($i = 0; $i < count($arrayprojetphase2); $i++) {
    echo $manager->getSingle2("select numero from projet where refinterneprojet=?", $arrayprojetphase2[$i]['refinterneprojet']).'<br>';         
    
}


BD::deconnecter();
