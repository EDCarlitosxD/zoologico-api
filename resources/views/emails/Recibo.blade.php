<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo de Compra</title>
    <link rel="stylesheet" href="{{ asset('css/recibodesign.css') }}">
</head>
<body>

    <div class="container">

        <div class="header">
            <h1>Recibo de Compra</h1>
            <p><strong>ZooLogic</strong> | Calle del Zoológico, 123, Ciudad</p>
            <p><strong>Fecha:</strong> {{ $fechaactual}}</p>
        </div>


        <div class="details">
            <h3>Detalles de la Compra</h3>
            <table>
                <tr>
                    <th>Nombre</th>
                    <td>{{ $nombre }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ $email }}</td>
                </tr>
                <tr>
                    <th>Boletos Comprados</th>
                    <td>
                        <ul>
                            @foreach ($boletos as $boleto )
                            <li>{{ $boleto['tipoboleto'] }} (cantidad: {{$boleto['cantidad']}}): ${{number_format(($boleto['precio'])*($boleto['cantidad']),2)}} MXN</li>
                            @endforeach
                        </ul>
                    </td>
                    
                </tr>
                <tr>
                    <th>Total</th>
                    <td>${{ number_format($total, 2) }} MXN</td>
                </tr>
            </table>
        </div>


        <div class="total">
            <p><strong>Total a Pagar:</strong> ${{ number_format($total, 2) }} MXN</p>
        </div>


        <div class="footer">
            <p>Gracias por tu compra. ¡Esperamos verte pronto!</p>
            <p><a href="http://www.zoologic.com">www.zoologic.com</a></p>
        </div>
    </div>

</body>
</html>
