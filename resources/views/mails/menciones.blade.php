<!DOCTYPE html>
<html>
<head>
    <title>Mención en {{ $datos['tipoDeComunicacion'] }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fc;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 600px;
            margin: 0 auto;
        }
        h1 {
            color: #007bff;
            font-size: 24px;
            margin-bottom: 20px;
        }
        p {
            font-size: 16px;
            line-height: 1.6;
        }
        .highlight {
            color: #007bff;
            font-weight: bold;
        }
        .button {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-top: 20px;
        }
        .footer {
            font-size: 12px;
            color: #888;
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Mención en {{ $datos['tipoDeComunicacion'] }}</h1>
        <p>Hola,</p>
        <p>Has sido mencionado en {{ $datos['tipoDeComunicacion'] }} reciente.</p>
        <p><strong class="highlight">Contacto:</strong> {{ $datos['contacto'] }}</p>
        <p><strong class="highlight">Teléfono:</strong> {{ $datos['telefono'] }}</p>
        <p><strong class="highlight">Observación:</strong> {{ $datos['observacion'] }}</p>
        <p>Por favor, revisa los detalles en el sistema.</p>
        <div class="footer">
            <p>Este es un correo automático. No respondas a este mensaje.</p>
        </div>
    </div>
</body>
</html>
