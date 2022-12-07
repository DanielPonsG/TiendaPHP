<?php


define('KEY_TOKEN', 'ABCsdad-321!');
session_start();

$carrito = 0;
if (isset($_SESSION['carrito']['productos'])) {
    $carrito = count($_SESSION['carrito']['productos']);
}

?>