<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://www.paypal.com/sdk/js?client-id=AUphKr8Lt0haXSonkiotMw4ks0lFN_lv7TCcEbCqT4qtzuSmpWz3GLvLYC3FPMp-D7iTrXDKBSpKoI66"></script>
</head>
<body>
    <div id="paypal-button-container"></div>
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
                            value: '100' // Cambié el valor a una cadena de texto
                        }
                    }]
                });
            },
            onApprove: function(data, actions) {
                actions.order.capture().then(function (details) {
                    window.location.href = "completado.html";
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