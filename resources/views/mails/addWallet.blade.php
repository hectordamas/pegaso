<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de Registro de Wallet</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0;">
    <table role="presentation" width="100%" style="max-width: 600px; margin: 20px auto; background-color: #fff; border-radius: 8px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); padding: 20px;">
        <tr>
            <td style="text-align: center; background-color: #007bff; color: #fff; padding: 10px; border-radius: 8px 8px 0 0;">
                <h2 style="margin: 0;">Detalles de Registro de Wallet</h2>
            </td>
        </tr>
        <tr>
            <td style="padding: 20px; color: #333;">
                @foreach($datos as $reg)
                    @php
                        $operacion = $reg->codoperacion == 1 ? 'DÉBITO' : 'CRÉDITO';
                    @endphp

                    <p style="font-size: 16px; font-weight: bold; color: #007bff; text-align: center;">
                        WALLET: {{ $reg->wallet }}
                    </p>
                    
                    <p style="font-size: 14px; text-align: center; color: #555;">Estos son los datos del registro:</p>
                    
                    <table role="presentation" width="100%" style="border-collapse: collapse;">
                        <tr>
                            <td style="padding: 8px; border-bottom: 1px solid #ddd;"><strong>Fecha Registro:</strong></td>
                            <td style="padding: 8px; border-bottom: 1px solid #ddd;">{{ date('d/m/Y', strtotime($reg->fecha)) }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px; border-bottom: 1px solid #ddd;"><strong>Fecha Pago:</strong></td>
                            <td style="padding: 8px; border-bottom: 1px solid #ddd;">{{ date('d/m/Y', strtotime($reg->fechapag)) }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px; border-bottom: 1px solid #ddd;"><strong>Moneda:</strong></td>
                            <td style="padding: 8px; border-bottom: 1px solid #ddd; font-weight: bold;">{{ $reg->moneda }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px; border-bottom: 1px solid #ddd;"><strong>Tipo Pago:</strong></td>
                            <td style="padding: 8px; border-bottom: 1px solid #ddd;">{{ $reg->tipomoneda }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px; border-bottom: 1px solid #ddd;"><strong>Operación:</strong></td>
                            <td style="padding: 8px; border-bottom: 1px solid #ddd;">{{ $operacion }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px; border-bottom: 1px solid #ddd;"><strong>Monto:</strong></td>
                            <td style="padding: 8px; border-bottom: 1px solid #ddd; font-weight: bold; color: #28a745;">{{ number_format($reg->monto, 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px; border-bottom: 1px solid #ddd;"><strong>Usuario:</strong></td>
                            <td style="padding: 8px; border-bottom: 1px solid #ddd;">{{ $reg->usuario }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px;"><strong>Descripción:</strong></td>
                            <td style="padding: 8px; font-size: 14px; font-weight: bold; color: green;">{{ $reg->descripcion }}</td>
                        </tr>
                    </table>

                    <br>
                @endforeach
            </td>
        </tr>
        <tr>
            <td style="text-align: center; padding: 20px;">
                <p style="font-size: 12px; color: #777;">Este es un mensaje automático, por favor no responder.</p>
            </td>
        </tr>
    </table>
</body>
</html>
