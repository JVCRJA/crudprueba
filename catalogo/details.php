<?php
require 'config/database.php';
require 'config/config.php';


$db = new Database();
$con = $db->conectar();

$id = isset($_GET['id']) ? $_GET['id'] : '';
$token = isset($_GET['token']) ? $_GET['token'] : '';


if ($id == '' || $token == '') {
    echo 'Error en la petición';
    exit;
} else {
    $token_tmp = hash_hmac('sha1', $id, KEY_TOKEN);
    if ($token == $token_tmp) {
        $sql = $con->prepare("SELECT count(id) FROM productos WHERE id=?  AND activo=1");
        $sql->execute([$id]);
        if ($sql->fetchColumn() > 0) {
            $sql = $con->prepare("SELECT nombre, precio ,descripción FROM productos WHERE id=?  AND activo=1 LIMIT 1");
            $sql->execute([$id]);
            $resultados = $sql->fetch(PDO::FETCH_ASSOC);
            $nombre = $resultados['nombre'];
            $precio = $resultados['precio'];
            $descripcion = $resultados['descripción'];
            $dir_images = 'imagenes/productos/' . $id . '/';

            $rutalmg = $dir_images . 'principal.png';

            if (!file_exists($rutalmg)) {
                $rutalmg = 'imagenes/no-photo.png';
            }
            $imagenes = array();
            if (file_exists($dir_images))
            {
            $dir = dir($dir_images);

            while (($archivo = $dir->read()) !== false) {
                if ($archivo !== 'principal.jpg' && (strpos($archivo, 'jpg') || strpos($archivo, 'jpeg'))) {
                    $imagenes[] = $dir_images . $archivo;
                }
            }
            $dir->close();
        }
    }
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <link href="css/estilos.css" rel="stylesheet"></link>
</head>
<body>
    <style> /* Añade margen alrededor del carrusel maneja el logo */
      .navbar-brand img {
        width: 230px;
        height: auto; 
  }
  .navbar-blue {
        background: linear-gradient(to bottom, #2a9af1, #429ae4,#d4dde4);
        padding: 5px; /* Añade margen alrededor del carrusel */
  }
  .navbar-black{
        background-color: black;
  }
   .btn-carrito { /*Boton del carrito */
        background-color: #3392f4; 
        border-color: black;
        color: white; 
       
  }
  header {
        background: linear-gradient(to bottom, #2a9af1, #429ae4,#2a9af1);
        border: 1px solid black; /* Agrega un borde de 1 píxel sólido en color negro */
        padding: 1px; /* Añade espaciado alrededor del encabezado si lo deseas */
        text-align: center;
        color: white; /* Color del texto en el encabezado */
        position: sticky;
  
  }  

    </style>
<div class="top-content">
<header data-bs-theme="blue">
 <h5>Detalles del Producto</h5>
</header>
</div>
<div class="container1">
  <header data-bs-theme="blue">
    <div class="navbar navbar-expand-lg navbar-blue bg-blue">
      <a href="#" class="navbar-brand">
        <img src="imagenes/logo milmed.png" alt="" width="100" height="100">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        <div class="collapse navbar-collapse" id="navbarHeader"></div>
      </button>
      <div class="collapse navbar-collapse" id="navbarHeader">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a href="index.php" class="nav-link active">Catálogo</a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">Contacto</a>
          </li>
        </ul>
        <a href="checkout.php" class="btn btn-carrito">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart4" viewBox="0 0 16 16">
        <path d="M0 2.5A.5.5 0 0 1 .5 2H2a.5.5 0 0 1 .485.379L2.89 4H14.5a.5.5 0 0 1 .485.621l-1.5 6A.5.5 0 0 1 13 11H4a.5.5 0 0 1-.485-.379L1.61 3H.5a.5.5 0 0 1-.5-.5zM3.14 5l.5 2H5V5H3.14zM6 5v2h2V5H6zm3 0v2h2V5H9zm3 0v2h1.36l.5-2H12zm1.11 3H12v2h.61l.5-2zM11 8H9v2h2V8zM8 8H6v2h2V8zM5 8H3.89l.5 2H5V8zm0 5a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0zm9-1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0z"/>
        </svg>  <samp id="num_cart" class="badge bg-secondary"><?php echo $num_cart; ?></samp>
    </a>
 
</svg>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
      </div>
    </div>
  </header> 
<main>
    <div class="container">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
        <div class="row">
            <div class="col-md-6 order-md-1">
                <div id="carouselImagenes" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="<?php echo $rutalmg; ?>" class="d-block w-100" alt="Main Image">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 order-md-2">
                <h2><?php echo $nombre; ?></h2>
                <h2><?php echo MONEDA . number_format($precio, 2, ',', ','); ?></h2>
                <p class="lead">
                    <?php echo $descripcion; ?>
                </p>
                <div class="d-grid gap-3 col-10 mx-auto">
                    <button class="btn btn-primary" type="button">Comprar ahora</button>
                    <button class="btn btn-outline-primary" type="button" onclick="aadProducto(<?php echo $id; ?>, '<?php echo $token_tmp; ?>')">Agregar al carrito</button>
                </div>
            </div>
        </div>
    </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>    
<script>
  function aadProducto(id, token) {
    let url = 'clases/carrito.php'
    let formData = new FormData()
    formData.append('id', id)
    formData.append('token', token)

    fetch(url, {
        method: 'POST',
        body: formData,
        mode: 'cors',
    }).then((response) => response.json())
        .then(data => {
            if (data.ok) {
                let elemento = document.getElementById("num_cart");
                elemento.innerHTML = data.numero
            }
        })
}

</script>

</body>
</html>

