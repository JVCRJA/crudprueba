<?php
require 'config/database.php';
require 'config/config.php';


$db = new Database();
$con = $db->conectar();

$resultados = []; 

if (isset($_POST["buscar"])) {
    $query = "%{$_POST["buscar"]}%";
    $sql = $con->prepare("SELECT id, nombre, precio FROM productos WHERE activo = 1 AND nombre LIKE LOWER(:query)");
    $sql->bindParam(':query', $query, PDO::PARAM_STR);
    $sql->execute();
    $resultados = $sql->fetchAll(PDO::FETCH_ASSOC);
} else {
 
    $sql = $con->prepare("SELECT id, nombre, precio FROM productos WHERE activo = 1");
    $sql->execute();
    $resultados = $sql->fetchAll(PDO::FETCH_ASSOC);
}

?>
<style>
    .card {
    height: 100%; /* Ajusta la altura deseada en píxeles */
}
</style>

<div class="container">
    <div class="row row-cols-1 row-cols-md-3 g-3">
        <?php
        $contador = 0;
        foreach ($resultados as $row) {
            $id = $row['id'];
            $imagen = "imagenes/productos/" . $id . "/principal.png";
            if (!file_exists($imagen)) {
                $imagen = "imagenes/no-photo.png";
            }
            if ($contador % 3 === 0) {
                echo '</div><div class="row row-cols-1 row-cols-md-3 g-3">';
            }
        ?>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <img src="<?php echo $imagen; ?>" class="img-fluid" style="max-width: 100%; height: 100%;">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $row['nombre']; ?></h5>
                    <p class="card-text">Bs<?php echo number_format($row['precio'], 2, ',', ','); ?></p>
                    <div class="d-flex justify-content-between align-items-center">
                        
                        <div class="btn-group">
                            <a href="details.php?id=<?php echo $row['id'];?>&token=<?php echo hash_hmac('sha1',$row['id'],KEY_TOKEN);?>" class="btn btn-primary">Detalles</a>
                        </div>      
                     <button class="btn btn-outline-success" type="button" onclick="aadProducto(<?php echo $row['id']; ?>, '<?php echo hash_hmac('sha1', $row['id'], KEY_TOKEN); ?>')">Agregar al carrito</button>

                     
                    </div>

                </div>
            </div>
        </div>
        <?php
            $contador++;
        }
        ?>
    </div>
</div>




 




