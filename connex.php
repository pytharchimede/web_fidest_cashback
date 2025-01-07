<?php 
try
{
//$con = new PDO('mysql:host=localhost;dbname=fidest_db', 'root', '');
$con = new PDO('mysql:host=localhost; dbname=fidestci_app_db', 'fidestci_ulrich', '@Succes2019');
}
catch(Exception $e)
{
echo 'Erreur : '.$e->getMessage().'<br />';
echo 'N째 : '.$e->getCode();
}
?>