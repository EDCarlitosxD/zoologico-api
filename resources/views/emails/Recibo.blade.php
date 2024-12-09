<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo de Compra</title>
    <style>
            body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            }
            .container {
                width: 80%;
                margin: 20px auto;
                background-color: #fff;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }
            .header {
                text-align: center;
                margin-bottom: 20px;
            }
            .header h1 {
                font-size: 24px;
                color: #333;
            }
            .header p {
                font-size: 14px;
                color: #777;
            }
            .details {
                margin-bottom: 20px;
            }
            .details table {
                width: 100%;
                border-collapse: collapse;
            }
            .details th, .details td {
                padding: 8px;
                border: 1px solid #ddd;
                text-align: left;
            }
            .details th {
                background-color: #f7f7f7;
            }
            .total {
                text-align: right;
                font-size: 18px;
                margin-top: 20px;
                font-weight: bold;
            }
            .footer {
                text-align: center;
                margin-top: 40px;
                font-size: 12px;
                color: #888;
            }
            .footer a {
                color: #888;
                text-decoration: none;
            }
    </style>
</head>
<body>

    <div class="container">

        <div class="header">
            <h1>Recibo de Compra</h1>
            <p><strong>Zoo Logic</strong> | Calle del Zoológico, 123, Ciudad</p>
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
                    <th>Tours Comprados</th>
                    <td>
                        <ul>
                            @foreach ($recorridos as $recorrido )
                            <li>{{ $recorrido['tiporecorrido'] }} (cantidad: {{$recorrido['cantidad']}}): ${{number_format(($recorrido['precio'])*($recorrido['cantidad']),2)}} MXN</li>
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
