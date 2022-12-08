<?php
require "db/conexion.php";
require "config/config.php";

$id = isset($_GET["id"]) ? $_GET["id"] : "";
$token = isset($_GET["token"]) ? $_GET["token"] : "";

if ($id == "" || $token == "") {
  echo "Error";
  exit;
} else {

  $token_tmp = hash_hmac('sha1', $id, KEY_TOKEN);

  if ($token == $token_tmp) {

    $consulta = $conn->prepare("SELECT count(id) FROM productos WHERE id=? AND activo = 1");
    $consulta->execute([$id]);
    if ($consulta->fetchColumn() > 0) {

      $consulta = $conn->prepare("SELECT nombre, descripcion, precio, descuento FROM productos WHERE id=? AND activo = 1 LIMIT 1");
      $consulta->execute([$id]);
      $row = $consulta->fetch(PDO::FETCH_ASSOC);
      $precio = $row["precio"];
      $nombre = $row["nombre"];
      $descripcion = $row["descripcion"];
      $descuento = $row["descuento"];
      $precio_desc = $precio - (($precio * $descuento) / 100);
      $dir_images = "./img/productos/" . $id . "/";
      $rutaImg = $dir_images . "principal.jpg";

      if (!file_exists($rutaImg)) {
        $rutaImg = "./img/productos/no-foto.jpg";
      }
      $img = array();
      $dir = dir($dir_images);
      if (file_exists($dir_images)) {


        while (($archivo = $dir->read()) != false) {
          if ($archivo != "principal.jpg" && (strpos($archivo, "jpg") || strpos($archivo, "jpeg"))) {
            $img[] = $dir_images . $archivo;
          }
        }
        $dir->close();
      }
    }
  } else {
    echo "Error";
    exit;
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
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@100&display=swap" rel="stylesheet">
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

  <main class="header-detalles">
    <div class="contenedor-detalles">
      <div class="card">
        <div class="img-container">
          <figure>
            <img src="<?php echo $rutaImg ?>" alt="Foto" />
          </figure>
        </div>
        <div class="text-container">
          <h2><?php echo $row["nombre"]; ?></h2>
        </div>
        <div class="btn-container">
          <a href="#" class="btn-btn" style="background-color: green;">Comprar ahora</a>
          <a href="#" class="btn-btn" onclick="agregarProducto(<?php echo $id; ?>,'<?php echo $token_tmp; ?>');">Agregar al carrito</a>
        </div>
      </div>
      <div class="card">
        <h1>Detalles del Producto</h1>
        <div>
          <h2><?php echo $row["descripcion"]; ?></h2>
        </div>
        <?php if ($descuento > 0) { ?>
          <small>Antes</small>
          <h2 style="color: red;"><del>$<?php echo $row["precio"]; ?></del></h2>
          <small>Ahora</small>
          <h2 style="color: green;">$<?php echo $precio_desc ?></h2>
        <?php } else { ?>
          <h2>$<?php echo $row["precio"] ?></h2>
        <?php } ?>
      </div>
    </div>
  </main>



  <div class="content price">

    <div class="social box">
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

    <script>
      function agregarProducto(id, token) {
        let url = 'clases/carrito.php'
        let formData = new FormData()
        formData.append('id', id)
        formData.append('token', token)

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