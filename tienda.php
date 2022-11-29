<?php
    require "db/conexion.php";
    require "config/config.php";
    $consulta = $conn -> prepare("SELECT * FROM productos WHERE activo = 1");
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
            <a href="#">Tienda</a>
            <a href="#">Iniciar Sesión</a>
            <a href="#">Carrito</a>
            <a href="#">Comprar</a>
        </nav>
        </div>

        <main>
        <div class="contenedor">
          <?php foreach($resultado as $row) { ?>
		<div class="contenedor-conciertos">
      <?php
        $id = $row["id"];
        $imagen = "./img/productos/".$id."/principal.jpg";

        if(!file_exists($imagen)){
          $imagen = "./img/productos/no-foto.jpg";
        }
      ?>
			<div class="card">
      <img src="<?php echo $imagen; ?>" alt="Foto" height="200px" style="border-radius: 30px;">
				<div class="textos">
        <h2><?php echo $row["nombre"]; ?></h2>
					<p>$<?php echo $row["precio"]; ?></p>
				</div>
        <a href="detalles.php?id=<?php echo $row["id"]; ?>&token=<?php echo hash_hmac('sha1', $row["id"], KEY_TOKEN); ?>" class="btn-btn">Detalles</a>
        <a href="#" class="btn-btn" style="background-color: green;">Comprar</a>
			</div>
      <?php } ?>
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
</body>
</html>




