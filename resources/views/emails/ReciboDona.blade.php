<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donation Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }
        .receipt {
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .receipt h1 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f4f4f9;
            color: #333;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="receipt">
        <h1>Recibo de Donación</h1>
        <table>
            <tr>
                <th>Nombre del donante</th>
                <td>{{$nombre}}</td>
            </tr>
            <tr>
                <th>Monto</th>
                <td>${{$datos['monto']}} MXN</td>
            </tr>
            <tr>
                <th>Propósito</th>
                <td>Ayuda para la preservación del zoológico</td>
            </tr>
            <tr>
                <th>Fecha</th>
                <td>{{$fecha}}</td>
            </tr>
        </table>
        <div class="footer">
            <p>Zoo Logic</p>
            <p>Calle del Zoológico, 123, Ciudad </p>
            <p>Gracias por tu apoyo. ¡Esperamos verte pronto!</p>
            <p><a href="http://www.zoologic.com">www.zoologic.com</a></p>
        </div>
    </div>
</body>
</html>
