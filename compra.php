<?php
require "db/conexion.php";
require "config/config.php";

$productos = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos'] : null;
$lista_carrito = array();
if ($productos != null) {

    foreach ($productos as $clave => $cantidad) {
        $consulta = $conn->prepare("SELECT id, nombre, precio, descuento, $cantidad AS cantidad FROM productos WHERE id=? AND activo = 1");
        $consulta->execute([$clave]);
        $lista_carrito[] = $consulta->fetch(PDO::FETCH_ASSOC);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
    <title>Catálogo</title>
</head>

<body>

    <div class="head">
        <div class="logo">
            <a href="#">Logo</a>
        </div>
        <nav class="navbar">
            <a href="index.html">Inicio</a>
            <a href="tienda.php">Tienda</a>
            <a href="#">Iniciar Sesión</a>
            <a href="compra.php" id="">Carrito <span id="carrito"><?php echo $carrito ?></span></a>
            <a href="#">Comprar</a>
        </nav>
    </div>

    <main class="header-tienda">
        <div class="contenedor-carrito">
            <div class="table-container">
                <table class="table">
                    <caption>Productos en el carrito</caption>
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Total</th>
                        </tr>
                        <hr>
                    </thead>
                    <tbody>
                        <?php if ($lista_carrito == null) {
                            echo "<tr>
                                    <td colspan='5'><b>'Lista vacía'</b></td>
                                </tr>";
                        } else {
                            $total = 0;
                            foreach ($lista_carrito as $producto) {
                                $_id = $producto['id'];
                                $nombre = $producto['nombre'];
                                $precio = $producto['precio'];
                                $descuento = $producto['descuento'];
                                $cantidad = $producto['cantidad'];
                                $precio_desc = $precio - (($precio * $descuento) / 100);
                                $subtotal = $cantidad * $precio_desc;
                                $total += $subtotal;
                        ?>
                                <tr>
                                    <td><?php echo $nombre; ?></td>
                                    <td>$ <?php echo $precio_desc; ?></td>
                                    <td><input type="number" min="1" max="10" step="1" value="<?php echo $cantidad; ?>" size="5" id="cantidad_<?php echo $_id ?>" onchange="actualizaCantidad(this.value,<?php echo $_id; ?>)"></input></td>
                                    <td>
                                        <div id="<?php echo $_id; ?>" name="subtotal">$ <?php echo $subtotal; ?></div>
                                    </td>
                                    <td><button>Eliminar</button></td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                        <tr>

                            <td colspan="3"></td>
                            <td colspan="2">
                                <p style="color: black">Total : $ <?php echo $total ?></p>
                            </td>

                        </tr>
                    </tbody>
                </table>
                <div>
                    <div class="button-container">
                        <button class="button">Realizar pago</button>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <div class="content price">
        <div class="social-box">
            <a href="https://www.facebook.com">
                <img src="img/fb.png" alt="">
            </a>
            <a href="https:/www.twitter.com">
                <img src="img/twitter.png" alt="">
            </a>
            <a href="https:/www.linkedin.com">
                <img src="img/linkedin.png" alt="">
            </a>
            <a href="https:/www.instagram.com">
                <img src="img/instagram.png" alt="">
            </a>
        </div>
    </div>



    <script>
        function actualizaCantidad(cantidad, id) {
            let url = 'clases/actualizar_carrito.php'
            let formData = new FormData()
            formData.append('action', 'agregar')
            formData.append('id', id)
            formData.append('cantidad', cantidad)

            fetch(url, {
                    method: 'POST',
                    body: formData,
                    mode: 'cors'
                }).then(response => response.json())
                .then(data => {
                    if (data.ok) {
                        let elemento = document.getElementById("carrito")
                        elemento.innerHTML = data.numero
                    }
                })

        }
    </script>


</body>

</html>