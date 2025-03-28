@php
    $fecha = $datos['fecha'];
    $cliente = $datos['cliente'];
    $consultor = $datos['consultor'];
    $tipo = $datos['tipo']; // 'asignacion', 'anulacion' recordatorio o 'modificacion'

    if($tipo == 'recordatorio'){
        $codconsultor = $datos['codconsultor'];
        $codclie = $datos['codclie'];
        $interactionType = $datos['interactionType'];
        $description = $datos['description'];
        $fechaFinal = $datos['fechaFinal'];


        // URLs para los formularios (ajusta según corresponda)
        $urlVisita = url("visitas?requestAccion=crear&requestFecha={$fecha}&requestFechaFinal={$fechaFinal}&requestCliente={$codclie}&requestConsultor={$codconsultor}&requestDescripcion={$description}");
        $urlComunicacion = url("comunicaciones?requestAccion=crear&requestFecha={$fecha}&requestFechaFinal={$fechaFinal}&requestCliente={$codclie}&requestConsultor={$codconsultor}&requestDescripcion={$description}");
    }

@endphp

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Visita en Calendario</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            margin: auto;
        }
        h2 {
            color: #333;
            text-align: center;
        }
        p {
            font-size: 16px;
            color: #555;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            background: #f9f9f9;
            margin: 5px 0;
            padding: 10px;
            border-radius: 5px;
            font-size: 14px;
            color: #333;
        }
        strong {
            color: #222;
        }
        .button-container {
            display: flex;
            gap: 20px; /* Espacio entre los botones */
            justify-content: center;
            margin-top: 20px;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: 600;
            text-align: center;
            text-decoration: none;
            border-radius: 8px;
            border: 2px solid transparent;
            transition: all 0.3s ease;
            cursor: pointer;
            background-color: #007bff; /* Azul por defecto */
            color: white !important;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .button:hover {
            background-color: #0056b3; /* Azul más oscuro */
            border-color: #0056b3;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .button:active {
            transform: scale(0.98);
        }

        .button:focus {
            outline: none;
        }

        /* Botón de 'Registrar Llamada' */
        .button[data-type='llamada'] {
            background-color: #28a745; /* Verde */
            border-color: #28a745;
        }

        .button[data-type='llamada']:hover {
            background-color: #218838; /* Verde más oscuro */
            border-color: #218838;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #777;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>
        @if($tipo === 'asignacion')
            📅 Registro de Nuevo Evento en Calendario
        @elseif($tipo === 'anulacion')
            ❌ Anulación de Evento en Calendario
        @elseif($tipo === 'modificacion')
            🔄 Modificación de Evento en Calendario
        @elseif($tipo === 'recordatorio')
            ⏳ Recordatorio de Evento Programado en Calendario
        @endif
    </h2>

    @if($tipo !== 'recordatorio')
    <p>Se ha realizado la siguiente acción en el calendario:</p>
    @else
    <p>Se ha programado el siguiente evento en los próximos 30 minutos</p>
    @endif

    <ul>
        <li><strong>Nombre del Evento:</strong> {{$datos['consultor'] ?? 'N/A'}}</li>
        <li><strong>Descripción del Evento:</strong> {{$datos['description'] ?? 'N/A'}}</li>
        @if($tipo === 'modificacion')
            <li><strong>Nueva Fecha:</strong> {{\Carbon\Carbon::parse($fecha)->format('d-m-Y h:i a')}}</li>
        @else
            <li><strong>Fecha:</strong> {{\Carbon\Carbon::parse($fecha)->format('d-m-Y h:i a')}}</li>
        @endif
        <li><strong>Cliente:</strong> {{$cliente}}</li>
        <li><strong>Consultor:</strong> {{$consultor}}</li>
    </ul>

    @if($tipo === 'asignacion' || $tipo === 'modificacion')
    <div class="button-container">
        @if($interactionType == 'Visita')
            <a href="{{ $urlVisita }}" class="button">Registrar Visita</a>
        @endif

        @if($interactionType == 'Llamada')
            <a href="{{ $urlComunicacion }}" class="button" style="background-color: #28a745;">Registrar Llamada</a>
        @endif
    </div>
    @endif
    

    <div class="footer">
        <p>Este es un mensaje automático. Por favor, no respondas a este correo.</p>
    </div>
</div>

</body>
</html>
