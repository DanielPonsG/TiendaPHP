<?php
require '../config/config.php';
require '../db/conexion.php';

if (isset($_POST['action'])) {
    $action = $_POST['action'];
    $id = isset($_POST['id']) ? $_POST['id'] : 0;

    if ($action == 'agregar') {
        $cantidad = isset($_POST['cantidad']) ? $_POST['cantidad'] : 0;
    } 
}

function agregar($id, $cantidad){
    
}

?>