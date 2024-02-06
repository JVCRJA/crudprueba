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
}else {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="AUesaDoY7pKUTP5YT14P17ZU40BrHZdUAIPoJ6afNtLBDpZA_EkH21rgpqdoSH3iCYG2UUAak8ubEzh5" crossorigin="anonymous">
    <link href="css/estilos.css" rel="stylesheet">
</head>
<body>
<header data-bs-theme="dark">
  <div class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a href="#" class="navbar-brand">
        <strong>Detalle de la compra</strong>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        <div class="collapse navar-collapse" id="navabarHeader"></div>
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
        <a href="carrito.php" class="btn btn-primary">Carrito</a><samp id="num_cart" class="badge bg-secondary"><?php echo $num_cart; ?></samp>
      </div>
    </div>
  </div>
</header>
<main>

<div class="container">
    <div class="row">
        <div class="col-6">
            <h4>Detalles de pago</h4>
            <div id="paypal-button-container"></div>
        </div>
        <div class="col-6">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Producto</th>
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
                                <div id="subtotal_<?php echo $_id; ?>" name="subtotal[]"><?php echo MONEDA . number_format($subtotal, 2, ',', ','); ?></div>
                            </td>
                        </tr>
                    <?php }
                } ?>
                <tr>
</td>
    <td colspan="2">
        <p class="h3 text-end" id="total">
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

    </div>
</div>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<script src="https://www.paypal.com/sdk/js?client-id=<?php echo CLIENT_ID; ?>"></script>
<script>
        paypal.Buttons({
            style: {
                color: 'black',
                shape: 'pill',
                label: 'pay'
            },
            createOrder: function(data, actions) { // Corrección: "createOrder" en lugar de "crateOrder"
                return actions.order.create({ // Corrección: "actions" en lugar de "action"
                    purchase_units: [{
                        amount: {
                            value: <?php echo $total;?>
                        }
                    }]
                });
            },
            onApprove: function(data, actions) {
                let URL = 'clases/captura.php'
                actions.order.capture().then(function (detalles) {
                   console.log(detalles)

                   let url = 'clases/captura.php'

                   return fetch(url,{
                    headers:{
                        'content-type': 'application/json'
                    },
                    body: JSON.stringify({
                        detalles: detalles
                    })
                   })
                });
            },
            onCancel: function(data) {
                alert("Pago Cancelado");
                console.log(data);
            }
        }).render('#paypal-button-container');
    </script>
</body>
</html>
