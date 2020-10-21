<?php
//Include The Database Connection File
require('../inc/configuracion.php');

//Include The Database Connection File
require('../inc/mysql.php');

error_reporting(E_ERROR);

$cnx = new db($dbhost, $dbuser, $dbpass, $dbname);

if($_POST)
{
    //check if its an ajax request, exit if not
    if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {

        $output = json_encode(array( //create JSON data
            'type'=>'error',
            'text' => 'Se debe enviar un AJAX POST'
        ));
        die($output); //exit script outputting json data
    }

    date_default_timezone_set('America/Lima');

    //Sanitize input data using PHP filter_var().
    $proceed = false;
    $user_login = $_POST['username'];
    $user_password = $_POST['password'];

    $sql_query = $cnx->query("SELECT * FROM usuarios WHERE user_email='$user_login' AND user_password='$user_password'")->fetchArray();

    $id = $sql_query['id'];
    $id_society = $sql_query['id_society'];
    $nombre = $sql_query['user_name'];
    $correo = $sql_query['user_email'];

    if($id_society == 0) {
        $user_type = 'Administrador';
    } else {
        $user_type = 'CES';
    }

    $sql_query = $cnx->query("SELECT * FROM tiendas WHERE id_society='$id_society'")->fetchAll();
    
    $store_codes = [];

    foreach ($sql_query as $tienda => $value) {
        $store_codes[$tienda]['store_code'] = $value['code'];
        $store_codes[$tienda]['store_name'] = $value['name'];
    }

    if($id != null) {
        $proceed = true;
    } else {
        $proceed = false;
    }

    if($proceed) {
        session_start();
        $_SESSION["id"] = $id;
        $_SESSION["id_society"] = $id_society;
        $_SESSION["user_name"] = $nombre;
        $_SESSION["user_email"] = $correo;
        $_SESSION["user_stores"] = $store_codes;
        $_SESSION["user_type"] = $user_type; 
 
        $output = json_encode(array('type'=>'correcto','id' => $id, 'id_society' => $id_society, 'nombre'=>$nombre, 'correo' => $correo, 'user_stores_codes' => $store_codes));
    } else {
        $output = json_encode(array('type'=>'error', 'id' => 'El usuario no existe.'));
    }

    die($output);
}