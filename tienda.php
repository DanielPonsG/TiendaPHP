<?php
require "db/conexion.php";
require "config/config.php";
$consulta = $conn->prepare("SELECT * FROM productos WHERE activo = 1");
$consulta->execute();
$resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./css/style.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Dancing+Script&display=swap" rel="stylesheet">
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
    <div class="contenedor">
      <?php foreach ($resultado as $row) { ?>
        
          <?php
          $id = $row["id"];
          $imagen = "./img/productos/" . $id . "/principal.jpg";

          if (!file_exists($imagen)) {
            $imagen = "./img/productos/no-foto.jpg";
          }
          ?>
          <div class="card">
            <div class="img-container">
              <figure>
                <img src="<?php echo $imagen; ?>" alt="Foto">
              </figure>
            </div>
            <div class="text-container">
              <h2><?php echo $row["nombre"]; ?></h2>
              <p>$ <?php echo $row["precio"]; ?></p>
            </div>
            <div class="btn-container">
              <a href="detalles.php?id=<?php echo $row["id"]; ?>&token=<?php echo hash_hmac('sha1', $row["id"], KEY_TOKEN); ?>" class="btn-btn">Detalles</a>
              <button href="#" class="" onclick="agregarProducto(<?php echo $row['id']; ?>,'<?php echo hash_hmac('sha1', $row['id'], KEY_TOKEN); ?>');">Agregar al carrito</button>
            </div>
          </div>
        <?php } ?>
        
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