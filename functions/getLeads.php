<?php

//Include The Database Connection File
require('../../inc/configuracion.php');

//Include The Database Connection File
require('../../inc/mysql.php');

error_reporting(E_ERROR);

date_default_timezone_set('America/Lima');

$cnx = new db($dbhost, $dbuser, $dbpass, $dbname);

if($_GET)
{
    $id_user = $_GET['id_user'];
    $accion = $_GET['accion'];
    $id_cotizacion = $_GET['id_cot'];

    if ($accion == 'contactado') {
        $insert = $cnx->query("UPDATE registro_derco_oportunidades SET lead_estado='Contactado', lead_contactado='".date('Y-m-d H:i:s')."' WHERE id='$id_cotizacion'");
    } else if ($accion == 'convertido') {
        $insert = $cnx->query("UPDATE registro_derco_oportunidades SET lead_estado='Convertido', lead_convertido='".date('Y-m-d H:i:s')."' WHERE id='$id_cotizacion'");
    } else if ($accion == 'reservado') {
        $insert = $cnx->query("UPDATE registro_derco_oportunidades SET lead_estado='Reservado', lead_reservado='".date('Y-m-d H:i:s')."' WHERE id='$id_cotizacion'");
    } else if ($accion == 'facturado') {
        $insert = $cnx->query("UPDATE registro_derco_oportunidades SET lead_estado='Facturado', lead_facturado='".date('Y-m-d H:i:s')."' WHERE id='$id_cotizacion'");
    }

    $newURL = 'https://derco.com.pe/plataforma/leads/admin/administrador.php?id='.$id_user;

    header('Location: '.$newURL);
}
?>