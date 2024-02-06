<?php
require 'config/database.php';
require 'config/config.php';

$db = new Database();
$con = $db->conectar();
$id = isset($_GET['id']) ? $_GET['id'] : '';
$token = isset($_GET['token']) ? $_GET['token'] : '';

if (isset($_POST['carrito'])) {
  $producto_id = $_POST['producto_id'];
  $cantidad = $_POST['cantidad'];

  if (!isset($_SESSION['carrito'])) {
      $_SESSION['carrito'] = array();
  }

  // Verifica si el producto ya está en el carrito
  if (isset($_SESSION['carrito'][$producto_id])) {
      // Si ya existe, actualiza la cantidad
      $_SESSION['carrito'][$producto_id] += $cantidad;
  } 
 
else {
      // Si no existe, agrega el producto al carrito
      $_SESSION['carrito'][$producto_id] = $cantidad;
  }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>catalogo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <link href="css/estilos.css" rel="stylesheet"></link>
<body>
<style>
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
  #buscar-button { /*Boton del Buscador */
        background-color: #3392f4;
        border-color: black; 
        color: white; }
  header {
        background: linear-gradient(to right, #0cacff, #6ccdff,#00a8ff);
        border: 1px solid black; /* Agrega un borde de 1 píxel sólido en color negro */
        padding: 1px; /* Añade espaciado alrededor del encabezado si lo deseas */
        text-align: center;
        color: white; /* Color del texto en el encabezado */
        position: sticky;
  
  }  
  #myCarousel .carousel-item img {
        width: 100%; /* -Establece lo ancho de la imagen */
        height: 350px; /* -Establece la altura de la imagen */
        object-fit: fill; /* -Cubre el espacio sin distorsionar la imagen */
  }
  .container1 {
        max-height: 100vh; /* -Es el que limita la altura máxima del contenedor1 */
        overflow-y: auto; /*Es el que agrega desplazamiento vertical cuando sea necesario */
        width: 100%;
        z-index: 1000;/*-Este comando lo que hace es la profundidad del contenedor */
        position: relative;

  }
  .top-content {
        position: sticky;
        top: 0;

  }  
</style>
<div class="top-content">
  
<header data-bs-theme="blue">
 <h5>Servicio las 24h</h5>
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
            <a href="#" class="nav-link active">Catálogo</a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">Contacto</a>
          </li>
          <form class="d-flex">
            <input class="form-control me-2" id="buscar" name="buscar" onkeyup="buscar_ahora($('#buscar').val());" type="search" placeholder="Buscar" aria-label="Search">
            <button id="buscar-button" class="btn btn-outline-success" type="submit">Buscar</button>
          </form>
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
</div>
</header>
<div id="myCarousel" class="carousel slide mb-6">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="imagenes/titulo.png" alt="Slide 1">
      <div class="container">
        <div class="carousel-caption text-start">
          <h1>Medicamentos De Primera</h1>
          <p class="opacity-75">Some representative content for the first slide.</p>
          <p><a class="btn btn-lg btn-primary" href="#">Sign up today</a></p>
        </div>
      </div>
    </div>
    <div class="carousel-item">
      <img src="imagenes/titulo2.png" alt="Slide 2">
      <div class="container">
        <div class="carousel-caption text-start">
          <h1>Example headline 2.</h1>
          <p class="opacity-75">Some representative content for the second slide.</p>
          <p><a class="btn btn-lg btn-primary" href="#">Learn more</a></p>
        </div>
      </div>
    </div>
    <div class="carousel-item">
      <img src="imagenes/titulo2.png" alt="Slide 3">
      <div class="container">
        <div class="carousel-caption text-start">
          <h1>Example headline 3.</h1>
          <p class="opacity-75">Some representative content for the third slide.</p>
          <p><a class="btn btn-lg btn-primary" href="#">Browse gallery</a></p>
        </div>
      </div>
    </div>
  </div>
  <!-- Controles de navegación (flechas) -->
  <a class="carousel-control-prev" href="#myCarousel" role="button" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </a>
  <a class="carousel-control-next" href="#myCarousel" role="button" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </a>
</div>
        <main> 
            <div class="container">     
            <div id="datos_buscador" class="container col-12 row">  </div>
        </main>
    </div>
  </div>
  <script type="text/javascript">
        function buscar_ahora(buscar) {
        var parametros = {"buscar":buscar};
        $.ajax({
        data:parametros,
        type: 'POST',
        url: 'buscador.php',
        success: function(data) {
        document.getElementById("datos_buscador").innerHTML = data;
        }
        });
        }
        buscar_ahora();
       
</script>
<script>
function searchProducts() {
    var searchTerm = document.getElementById("buscar").value;


    $.ajax({
        type: 'POST',
        url: 'buscador.php',
        data: { buscar: searchTerm },
        success: function(data) {
  
            $('#product-list').html(data);
        },
        error: function() {
            console.log('Error in the AJAX request.');
        }
    });
}


$(document).ready(function() {
    searchProducts();
});


$('#buscar-button').click(function(e) {
    e.preventDefault(); 

    searchProducts();
});
</script>
<script>
    function aadProducto(id, token){
        let url = 'clases/carrito.php'
        let formData = new FormData()
        formData.append('id', id)
        formData.append('token', token)

        fetch(url , {
            method: 'POST',
            body: formData,
            mode: 'cors'
        }).then(response => response.json())
        .then(data => {
            if(data.ok){
                let elemento = document.getElementById("num_cart")
                elemento.innerHTML = data.numero
            }
        })
    }
    let lastScrollPosition = 0;

window.addEventListener("scroll", function() {
  const topContent = document.querySelector(".top-content");
  const container1 = document.querySelector(".container1");
  
  // Obtener la posición vertical del desplazamiento
  const scrollPosition = window.scrollY;

  // Calcular la diferencia entre la posición actual y la anterior
  const scrollDifference = scrollPosition - lastScrollPosition;

  // Aplicar la transformación para desplazar el contenido .top-content
  topContent.style.transform = `translateY(${Math.max(-scrollPosition, -topContent.clientHeight)}px)`;

  // Aplicar la transformación para desplazar el contenido .container1
  container1.style.transform = `translateY(${Math.max(0, scrollPosition - topContent.clientHeight)}px)`;

  // Actualizar la última posición de desplazamiento
  lastScrollPosition = scrollPosition;
});
</script>

</header>

</body>
</html>