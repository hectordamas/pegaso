<!DOCTYPE html>
<html>
<head>
    <title>Nueva Visita Creada</title>
</head>
<body>
    <h2>Detalles de la Visita</h2>
    <p><strong>Fecha:</strong> {{ $visita->fecha }}</p>
    <p><strong>Cliente:</strong> {{ $visita->saclie->descrip ?? 'N/A' }}</p>
    <p><strong>Consultor:</strong> {{ $visita->consultor->nombre ?? 'N/A' }}</p>
    <p><strong>Observación:</strong> {{ $visita->observacion }}</p>

    <p>Adjunto encontrarás la nota de servicio.</p>
</body>
</html>