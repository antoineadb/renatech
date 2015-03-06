<?php

function	deconnection()	{
					if	(!empty($_GET['action'])	&& $_GET['action']	==	'logout')	{
										$_SESSION	=	array();
										session_destroy();
										session_start();
					}
}
