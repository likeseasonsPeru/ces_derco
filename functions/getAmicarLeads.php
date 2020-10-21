<?php

//Include The Database Connection File
require('../../inc/configuracion2.php');

//Include The Database Connection File
require('../../inc/mysql.php');

error_reporting(E_ERROR);

date_default_timezone_set('America/Lima');

$leads = array();

$cnx = new db($dbhost, $dbuser, $dbpass, $dbname);

$query = $cnx->query("SELECT * FROM registro_amicar ORDER BY fecha_registro DESC")->fetchAll();

$meta = array(
    "page" => 1,
    "pages"=> 1,
    "perpage"=> -1,
    "total"=> count($query),
    "sort"=> "asc",
    "field"=> "id"
);

for ($i = 0; $i<count($query); $i++){
    $leads[] = $query[$i];
}
/* 
foreach($query as $registro){
    $leads[] = $registro;
} */


die(json_encode(array("meta" => $meta,"data" => $leads)));

?>