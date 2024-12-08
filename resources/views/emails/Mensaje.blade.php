<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mensaje de un usuario</title>
    <style>
               
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            font-size: 16px;
            font-family: Arial, Helvetica, sans-serif;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        header {
            text-align: center;
            margin-bottom: 20px;
            padding: 10px 0;
            background: #007bff;
            color: white;
            border-radius: 8px;
        }

        header h1 {
            font-size: 1.8em;
        }

        .message-container {
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: white;
        }

        .user-info {
            margin-bottom: 20px;
            padding: 10px;
            background: #e9ecef;
            border-radius: 5px;
            color: #555;
        }

        .user-info p {
            margin-bottom: 8px;
            line-height: 1.6;
        }

        .user-message {
            padding: 15px;
            border: 1px solid #007bff;
            border-radius: 5px;
            color: #333;
            line-height: 1.8;
        }

        .user-message h2 {
            margin-bottom: 10px;
            color: #007bff;
        }

        @media (max-width: 768px) {
            html {
                font-size: 14px;
            }
            .container {
                padding: 15px;
            }
        }

    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Mensaje de un usuario</h1>
        </header>
        <div class="message-container">
            <div class="user-info">
                <p><strong>Nombre:</strong> {{$datos['nombre']}}</p>
                <p><strong>Teléfono:</strong> {{$datos['telefono']}}</p>
                <p><strong>Correo electrónico:</strong> {{$datos['email']}}</p>
            </div>
            <div class="user-message">
                <h2>Mensaje</h2>
                <p>{{$datos['mensaje']}}</p>
            </div>
        </div>
    </div>
</body>
</html>
