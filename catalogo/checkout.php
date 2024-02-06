<?php
require 'config/database.php';
require 'config/config.php';

$db = new Database();
$con = $db->conectar();

// Inicializa la sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$productos = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos'] : null;

$moneda = "Bs"; 

$lista_carrito = array();

if ($productos != null) {
    foreach ($productos as $clave => $cantidad) {
        $sql = $con->prepare("SELECT id, precio, nombre FROM productos WHERE id=? AND activo=1");
        $sql->execute([$clave]);
        $producto = $sql->fetch(PDO::FETCH_ASSOC);
        
        if ($producto) {
            $producto['cantidad'] = $cantidad;
            $lista_carrito[] = $producto;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <link href="css/estilos.css" rel="stylesheet"></link>
</head>
<body>
<header data-bs-theme="dark">
  <div class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a href="#" class="navbar-brand">
        <strong>Carrito de compras</strong>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        <div class="collapse navar-collapse" id="navabarHeader"></div>
      </button>
      <div class="collapse navbar-collapse" id="navbarHeader">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
                <a href="#" class="nav-link active">Catálogo</a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">Contacto</a>
            </li>
        </ul>

      </div>
    </div>
  </div>
</header>
<main>

<div class="container">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Subtotal</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php if ($lista_carrito == null) {
                    echo '<tr><td colspan="5" class="text-center"><b>Lista vacía</b></td></tr>';
                } else {
                    $total = 0;
                    foreach ($lista_carrito as $producto) {
                        $_id = $producto['id'];
                        $nombre = $producto['nombre'];
                        $precio = $producto['precio'];
                        $subtotal = $producto['cantidad'] * $precio;
                        $total += $subtotal;
                        ?>
                        <tr>
                            <td><?php echo $nombre; ?></td>
                            <td>
                                <input type="number" min="1" max="10" step="1" value="<?php echo $producto['cantidad']; ?>"
                                       size="5" id="cantidad_<?php echo $_id; ?>" onchange="actualizarCantidad(<?php echo $_id; ?>, this.value);">
                            </td>
                            <td>
                                <div><?php echo MONEDA . number_format($precio, 2, ',', ','); ?></div>
                            </td>
                            <td>
                                <div id="subtotal_<?php echo $_id; ?>" name="subtotal[]"><?php echo MONEDA . number_format($subtotal, 2, ',', ','); ?></div>
                            </td>
                            <td>
                            <a id="eliminar" class="btn btn-warning btn-sm" data-bs-id="<?php echo $_id; ?>" data-bs-toggle="modal" data-bs-target="#eliminaModal">Eliminar</a>
                            </td>
                        </tr>
                    <?php }
                } ?>
                <tr>
    <td colspan="3"></td>
    <td colspan="2">
        <p class="h3" id="total">
            <?php 
            if (isset($total)) {
                echo MONEDA . number_format($total ,2,',',',');
            } else {
                echo MONEDA . '0.00';
            }
            ?>
        </p>
    </td>
</tr>
            </tbody>
        </table>
        </div>
    </div>
    <?php if ($lista_carrito != null) {?>
    <div class="row">
            <div class="col-md-5 offset-md-7 d-grid gap-2">
                <a href="pago.php"  class="btn btn-primary btn-lg">Realizar pago</a>
            </div>
<?php } ?>
</div>
</main>
<!-- Modal -->
<div class="modal fade" id="eliminaModal" tabindex="-1" aria-labelledby="eliminaModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="eliminaModalLabel">ELIMINAR COMPRA</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        ¿Desea eliminar la compra del carrito?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button id="btn-eliminar"type="button" class="btn btn-primary" onclick="eliminar()">Eliminar</button>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<script>
    let eliminaModal = document.getElementById('eliminaModal')
    eliminaModal.addEventListener('show.bs.modal',function(event){
        let button = event.relatedTarget
        let id = button.getAttribute('data-bs-id')
        let buttonElimina = eliminaModal.querySelector('.modal-footer #btn-eliminar')
        buttonElimina.value = id
    })
    function actualizarCantidad(id, cantidad) {
    let url = 'clases/actualizar_carrito.php';
    let formData = new FormData();
    formData.append('action', 'agregar');
    formData.append('id', id);
    formData.append('cantidad', cantidad);

    fetch(url, {
        method: 'POST',
        body: formData,
        mode: 'cors'
    }).then(response => response.json())
    .then(data => {
        if (data.ok) {
            let divsubtotal = document.getElementById('subtotal_' + id);
            divsubtotal.innerHTML = data.sub;

            let subtotals = document.getElementsByName("subtotal[]");
let total = 0.00;

for (let i = 0; i < subtotals.length; i++) {
    let subtotalValue = subtotals[i].innerHTML;
    subtotalValue = subtotalValue.replace("Bs", "").replace(/Bs,/g, "");
    let subtotalFloat = parseFloat(subtotalValue);
    
    total += subtotalFloat;
}
total = 'Bs' + total.toFixed(2);
document.getElementById('total').innerHTML = total;
        }
    })
}
function eliminar() {
    let botonElimina = document.getElementById('btn-eliminar')
    let id = botonElimina.value

    let url = 'clases/actualizar_carrito.php';
    let formData = new FormData();
    formData.append('action', 'eliminar');
    formData.append('id', id);
 

    fetch(url, {
        method: 'POST',
        body: formData,
        mode: 'cors'
    }).then(response => response.json())
    .then(data => {
        if (data.ok) {
            location.reload() 
}
    })
}
</script>

</body>
</html>

