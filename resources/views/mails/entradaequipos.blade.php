<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Chat - Entrada de Equipos</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #eef2f7; margin: 0; padding: 0;">
    <table width="100%" bgcolor="#eef2f7" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" style="padding: 20px;">
                <table width="600px" bgcolor="#ffffff" cellpadding="20" cellspacing="0" 
                    style="border-radius: 8px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1); text-align: left;">
                    
                    <!-- Encabezado -->
                    <tr>
                        <td align="center" style="background-color: #007bff; color: #ffffff; font-size: 22px; font-weight: bold; padding: 15px; 
                            border-top-left-radius: 8px; border-top-right-radius: 8px; letter-spacing: 1px;">
                            Registro de Chat - Entrada de Equipos
                        </td>
                    </tr>

                    <!-- Información del Cliente -->
                    <tr>
                        <td style="color: #333;">
                            <p style="font-size: 16px; font-weight: bold;">Cliente: {{$cliente->descrip}}</p>
                        </td>
                    </tr>

                    <!-- Detalles del Chat -->
                    @foreach($datos as $reg)
                        <tr>
                            <td style="border-bottom: 1px solid #ddd; padding: 15px 20px;">
                                <p style="font-size: 14px; margin: 5px 0;"><strong>📅 Fecha:</strong> {{ \Carbon\Carbon::parse($reg->fechayhora)->format('d/m/Y H:i A') ?? 'Fecha no disponible' }}</p>
                                <p style="font-size: 14px; margin: 5px 0;"><strong>📌 Nro. Entrada:</strong> {{$reg->codentrada}}</p>
                                <p style="font-size: 14px; margin: 5px 0;"><strong>👤 Usuario:</strong> {{$reg->user->name ?? 'No registrado'}}</p>
                                <p style="font-size: 14px; margin: 5px 0;"><strong>💬 Mensaje:</strong></p>
                                <p style="font-size: 14px; color: #000; font-weight: bold; padding: 10px; background-color: #d4edda; 
                                    border-radius: 5px; border-left: 5px solid #28a745;">
                                    {{$reg->mensaje}}
                                </p>
                            </td>
                        </tr>
                    @endforeach

                    <!-- Pie de página -->
                    <tr>
                        <td align="center" style="background-color: #f8f9fa; font-size: 12px; color: #666; padding: 10px; 
                            border-bottom-left-radius: 8px; border-bottom-right-radius: 8px;">
                            &copy; {{ date('Y') }} {{ env("APP_NAME") }} - Todos los derechos reservados.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
