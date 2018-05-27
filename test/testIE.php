<?php
/*
$u_agent = $_SERVER['HTTP_USER_AGENT'];
$ub = '';
if(preg_match('/MSIE/i',$u_agent))
{
    $ub = "Internet Explorer";
    echo 'je suis sur IE';
    //header('Location: index-IE.html');
    exit();
}
elseif(preg_match('/Firefox/i',$u_agent))
{
    $ub = "Firefox";
    header('Location: index-mozilla.html');
    exit();
}
elseif(preg_match('/Chrome/i',$u_agent))
{
    $ub = "Chrome";
    header('Location: index-chrome.html');
    exit();
}
elseif(preg_match('/Safari/i',$u_agent))
{
    $ub = "Safari";
    header('Location: index-safari.html');
    exit();
}
*/
for ($i = 9; $i < 12; $i++) {
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident/7.0; rv:'.$i.'.0') !== false) {
        echo 'IE';
    }elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE '.$i.'.0') !== false) {
        echo 'IE';
    } 
}
