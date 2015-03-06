<?php
include '../class/Chiffrement.php';
 echo 'login = '.Chiffrement::decrypt("Fw7DZ1hR9FuV1Ns+MxGcBw==").'<br>';
 echo 'password = '.Chiffrement::decrypt("3fEpqrO0ES38ODx0vVPr1w==").'<br>';
 echo Chiffrement::decrypt("f5OWYoqO9uAEEsudTqVbfryBBfNAGX79mdQt0KCvr4TH0bQXINWuYvUan+3w7YDHhuugOpaS7w6t1tWBXANYBTPkaJSSWc3iHOsjqkUYn/Y=");

 