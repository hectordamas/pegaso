<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Presupuesto Pendiente</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f7f7f7; padding: 20px;">
    <table style="width: 100%; max-width: 600px; margin: auto; background-color: #ffffff; border-radius: 8px; padding: 20px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        <tr>
            <td>
                <h2 style="color: #dc3545;">⚠️ Presupuesto Pendiente</h2>
                <p>Estimado equipo,</p>
                <p>El siguiente presupuesto continúa en estado <strong>pendiente</strong> luego de 10 días:</p>

                <table style="width: 100%; margin-top: 15px; border-collapse: collapse;">
                    <tr>
                        <td><strong>Número:</strong></td>
                        <td>{{ $presupuesto->numerod }}</td>
                    </tr>
                    <tr>
                        <td><strong>Cliente:</strong></td>
                        <td>{{ $presupuesto->saclie->descrip ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Fecha de Emisión:</strong></td>
                        <td>{{ \Carbon\Carbon::parse($presupuesto->fechae)->format('d/m/Y h:i A') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Monto Total:</strong></td>
                        <td>{{ number_format($presupuesto->mtototal, 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Estado Actual:</strong></td>
                        <td>{{ $presupuesto->estatusPre->nombre ?? 'Pendiente' }}</td>
                    </tr>
                </table>

                <p style="margin-top: 20px;">Por favor, tomar las acciones necesarias.</p>

                <p style="color: #6c757d;">Este es un mensaje automático generado por el sistema.</p>
            </td>
        </tr>
    </table>
</body>
</html>
