<?php
include_once '../outils/toolBox.php';
$entity= 'Institut des nanoSciences de Paris (UMR 7588)-Univ- Pierre et Marie CurieInstitut des nanoSciences d';
echo strlen($entity).'<br>';        
echo filterEditor(substr( $entity,0,70)).'<br>';
echo strlen(filterEditor(substr( $entity,0,70)));