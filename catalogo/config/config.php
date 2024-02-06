    <?php 
    define("KEY_TOKEN","APR.wqc-354V");
    define("CLIENT_ID","AUesaDoY7pKUTP5YT14P17ZU40BrHZdUAIPoJ6afNtLBDpZA_EkH21rgpqdoSH3iCYG2UUAak8ubEzh5");
    define("MONEDA","Bs");
    session_start();
    $num_cart = 0;
    if(isset($_SESSION['carrito']['productos'])){
        $num_cart = count($_SESSION['carrito']['productos']);
    }
    ?>