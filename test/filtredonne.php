<?php

include '../outils/toolBox.php';
include '../class/Manager.php';
$db = BD::connecter(); //CONNEXION A LA BASE DE DONNEE
$manager = new Manager($db); //CREATION D'UNE INSTANCE DU MANAGER

$string="<style type=\"text/css\">P { margin-bottom: 0cm; direction: ltr; color: rgb(0, 0, 0); text-align: justify; widows: 2; orphans: 2; }P.western { font-family: &quot;Times New Roman&quot;,serif; font-size: 12pt; }P.cjk { font-family: &quot;Times New Roman&quot;,serif; font-size: 12pt; }P.ctl { font-family: &quot;Times New Roman&quot;,serif; font-size: 12pt; }A:link { color: rgb(0, 0, 255); }</style>


<p align=\"LEFT\"><b>Technologies utilisées</b> : 
</p>
<p align=\"LEFT\">Pulvérisation, Lithographie, Wafer bonding,
équipements de rôdage/polissage, découpe à la scie diamantée,
FIB, MEB, ZYGO.</p><p align=\"LEFT\">Développement du collage Silice et du polissage par CMP.<br /></p>";

        
               
        
      echo filterEditor($string);
      
      