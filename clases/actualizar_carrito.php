<?php
require '../config/config.php';
require '../db/conexion.php';



if (isset($_POST['action'])) {
    $action = $_POST['action'];
    $id = isset($_POST['id']) ? $_POST['id'] : 0;

    if ($action == 'agregar') {
        $cantidad = isset($_POST['cantidad']) ? $_POST['cantidad'] : 0;
        $respuesta = agregar($id, $cantidad);
        if ($respuesta > 0) {
            $datos['ok'] = true;
        } else {
            $datos['ok'] = false;
        }
        $datos['sub'] = $respuesta;
    } else {
        $datos['ok'] = false;
    }
} else {
    $datos['ok'] = false;
}
echo json_encode($datos);

function agregar($id, $cantidad)
{
    require "db/conexion.php";
    $res = 0;
    if ($id > 0 && $cantidad > 0 && is_numeric(($cantidad))) {
        if (isset($_SESSION['carrito']['productos'][$id])) {
            $_SESSION['carrito']['productos'][$id] = $cantidad;


            $con = $conn->prepare("SELECT precio, descuento FROM productos WHERE id=? AND activo=1 LIMIT 1");
            $con->execute([$id]);
            $row = $con->fetch(PDO::FETCH_ASSOC);
            $precio = $row["precio"];
            $descuento = $row["descuento"];
            $precio_desc = $precio - (($precio * $descuento) / 100);
            $res = $cantidad * $precio_desc;
            return $res;
        }
    } else {
        return $res;
    }
}
?>