<?php
if(isset ($_GET['lang'])){
	$lang = $_GET['lang'];
}else{
$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'],0,2);
}
?>