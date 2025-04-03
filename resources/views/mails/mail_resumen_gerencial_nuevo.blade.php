<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resumen Nuevo Gerencial - {{ $datos["empresa"] }}</title>
    <style type="text/css">
        /* Estilos inline para máxima compatibilidad */
        body {
            font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
            background-color: #f5f7fa;
            margin: 0;
            padding: 0;
        }
        
        /* Contenedor principal */
        .email-container {
            max-width: 640px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
        
        /* Encabezado con color sólido */
        .header {
            background-color: #3f37c9; /* Color primario */
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        
        .company-name {
            font-size: 24px;
            font-weight: 700;
            margin: 0;
            letter-spacing: 0.5px;
        }
        
        .report-title {
            font-size: 28px;
            font-weight: 800;
            color: #1b263b; /* Color oscuro */
            margin: 25px 0 10px;
            text-align: center;
        }
        
        .report-date {
            color: #6c757d;
            text-align: center;
            margin-bottom: 30px;
            font-style: italic;
        }
        
        /* Secciones con bordes sutiles */
        .section {
            margin: 30px 25px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
        }
        
        .section-header {
            background-color: #1b263b; /* Color oscuro */
            color: white;
            padding: 12px 20px;
            font-size: 18px;
            font-weight: 600;
        }
        
        /* Tabla de datos */
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .data-row {
            border-bottom: 1px solid #edf2f7;
        }
        
        .data-row:last-child {
            border-bottom: none;
        }
        
        .data-label {
            padding: 15px 20px;
            font-weight: 500;
            color: #4a5568;
        }
        
        .data-value {
            padding: 15px 20px;
            text-align: right;
            font-weight: 600;
            color: #2d3748;
        }
        
        .highlight {
            background-color: #f8fafc;
        }
        
        .total-row {
            background-color: #ebf4ff;
            font-weight: 700;
        }
        
        /* Colores para estados */
        .positive {
            color: #4cc9f0; /* Color éxito */
        }
        
        .negative {
            color: #f72585; /* Color peligro */
        }
        
        /* Botón con estilo compatible */
        .btn-container {
            text-align: center;
            margin: 40px 0;
        }
        
        .btn-primary {
            display: inline-block;
            background-color: #4361ee; /* Color primario */
            color: white;
            text-decoration: none;
            padding: 14px 28px;
            border-radius: 50px;
            font-weight: 600;
        }
        
        /* Pie de página */
        .footer {
            background-color: #f1f5f9;
            padding: 25px;
            text-align: center;
            color: #64748b;
            font-size: 14px;
        }
        
        .disclaimer {
            font-size: 12px;
            color: #94a3b8;
            text-align: center;
            padding: 20px;
            line-height: 1.5;
        }
        
        /* Media queries para móvil */
        @media only screen and (max-width: 600px) {
            .email-container {
                border-radius: 0;
            }
            
            .section {
                margin: 20px 15px;
            }
            
            .data-label, .data-value {
                padding: 12px 15px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Encabezado -->
        <div class="header">
            <h1 class="company-name">💼 {{ $datos["empresa"] }}</h1>
        </div>
        
        <!-- Título del reporte -->
        <h2 class="report-title">📈 RESUMEN GERENCIAL</h2>
        <p class="report-date">Ds Dashboard - Resumen de Movimientos diarios al {{ date('d/m/Y')." ".date('h:i:s A') }}</p>
        
        <!-- Sección de Ventas -->
        <div class="section">
            <div class="section-header">
                💰 VENTAS (Facturas y Notas de Entrega)
            </div>
            <table class="data-table">
                <!-- Facturas -->
                <tr class="data-row">
                    <td class="data-label">Sub Total Facturas:</td>
                    <td class="data-value">$ {{ number_format($datos["montofacusd"],2,',','.') }}</td>
                </tr>
                <tr class="data-row">
                    <td class="data-label">IVA Facturas:</td>
                    <td class="data-value">$ {{ number_format($datos["ivafacusd"],2,',','.') }}</td>
                </tr>
                <tr class="data-row total-row">
                    <td class="data-label">Total Facturas:</td>
                    <td class="data-value">$ {{ number_format($datos["montofacusd"]+$datos["ivafacusd"],2,',','.') }}</td>
                </tr>
                
                <!-- Notas -->
                <tr class="data-row highlight">
                    <td class="data-label">Sub Total Notas:</td>
                    <td class="data-value">$ {{ number_format($datos["montonevusd"],2,',','.') }}</td>
                </tr>
                <tr class="data-row highlight">
                    <td class="data-label">IVA Notas:</td>
                    <td class="data-value">$ {{ number_format($datos["ivanevusd"],2,',','.') }}</td>
                </tr>
                <tr class="data-row total-row highlight">
                    <td class="data-label">Total Notas:</td>
                    <td class="data-value">$ {{ number_format($datos["montonevusd"]+$datos["ivanevusd"],2,',','.') }}</td>
                </tr>
                
                <!-- Totales -->
                <tr class="data-row">
                    <td class="data-label">Sub Total Ventas:</td>
                    <td class="data-value">$ {{ number_format($datos["montofacusd"]+$datos["montonevusd"],2,',','.') }}</td>
                </tr>
                <tr class="data-row">
                    <td class="data-label">Total IVA:</td>
                    <td class="data-value">$ {{ number_format($datos["ivausd"],2,',','.') }}</td>
                </tr>
                <tr class="data-row total-row">
                    <td class="data-label positive">TOTAL VENTAS:</td>
                    <td class="data-value positive">$ {{ number_format($datos["montofacusd"]+$datos["montonevusd"]+$datos["ivausd"],2,',','.') }}</td>
                </tr>
                
                <!-- Cobros y créditos -->
                <tr class="data-row highlight">
                    <td class="data-label">Total Cobrado CXC:</td>
                    <td class="data-value">$ {{ number_format($datos["cobrocxcusd"],2,',','.') }}</td>
                </tr>
                <tr class="data-row highlight">
                    <td class="data-label negative">Total a Crédito:</td>
                    <td class="data-value negative">$ {{ number_format($datos["vtacredito"],2,',','.') }}</td>
                </tr>
                <tr class="data-row total-row">
                    <td class="data-label positive">TOTAL INGRESOS:</td>
                    <td class="data-value positive">$ {{ number_format(($datos["montofacusd"]+$datos["montonevusd"]+$datos["ivausd"]+$datos["cobrocxcusd"])-$datos["vtacredito"],2,',','.') }}</td>
                </tr>
            </table>
        </div>
        
        <!-- Sección de Operaciones -->
        <div class="section">
            <div class="section-header">
                📊 CANTIDAD DE OPERACIONES
            </div>
            <table class="data-table">
                <tr class="data-row">
                    <td class="data-label">Facturas:</td>
                    <td class="data-value">{{ number_format($datos["operaciones"][0]["facturas"],0,',','.') }}</td>
                </tr>
                <tr class="data-row highlight">
                    <td class="data-label">Devoluciones:</td>
                    <td class="data-value">{{ number_format($datos["operaciones"][0]["devfac"],0,',','.') }}</td>
                </tr>
                <tr class="data-row">
                    <td class="data-label">Notas de Entrega:</td>
                    <td class="data-value">{{ number_format($datos["operaciones"][0]["notas"],0,',','.') }}</td>
                </tr>
                <tr class="data-row highlight">
                    <td class="data-label">Dev. Notas:</td>
                    <td class="data-value">{{ number_format($datos["operaciones"][0]["devnev"],0,',','.') }}</td>
                </tr>
                <tr class="data-row">
                    <td class="data-label">Pedidos:</td>
                    <td class="data-value">{{ number_format($datos["operaciones"][0]["ped"],0,',','.') }}</td>
                </tr>
                <tr class="data-row highlight">
                    <td class="data-label">Presupuestos:</td>
                    <td class="data-value">{{ number_format($datos["operaciones"][0]["pre"],0,',','.') }}</td>
                </tr>
            </table>
        </div>
        
        <!-- Sección de Cajas -->
        <div class="section">
            <div class="section-header">
                💵 RESUMEN DE CAJAS
            </div>
            <table class="data-table">
                @if(count($datos["codesta"]) == 0)
                <tr class="data-row">
                    <td colspan="2" class="data-label" style="text-align: center;">No hay movimientos</td>
                </tr>
                @else
                    @foreach($datos["codesta"] as $reg)
                    <tr class="data-row @if($loop->iteration % 2 == 0) highlight @endif">
                        <td class="data-label">{{ $reg->codesta }}:</td>
                        <td class="data-value">$ {{ number_format($reg->totalusd,2,',','.') }}</td>
                    </tr>
                    @endforeach
                @endif
            </table>
        </div>
        
        <!-- Sección de Inventario -->
        <div class="section">
            <div class="section-header">
                📦 MOVIMIENTOS DE INVENTARIO
            </div>
            <table class="data-table">
                <tr class="data-row">
                    <td class="data-label">Cargos:</td>
                    <td class="data-value">{{ number_format($datos["cargos"],0,',','.') }}</td>
                </tr>
                <tr class="data-row highlight">
                    <td class="data-label">Descargos:</td>
                    <td class="data-value">{{ number_format($datos["descargos"],0,',','.') }}</td>
                </tr>
                <tr class="data-row">
                    <td class="data-label">Ajustes:</td>
                    <td class="data-value">{{ number_format($datos["ajustes"],0,',','.') }}</td>
                </tr>
            </table>
        </div>
        
        <!-- Botón de acción -->
        <div class="btn-container">
            <a href="{{ $datos['dominio'] }}" class="btn-primary" target="_blank">Ir al Dashboard</a>
        </div>
        
        <!-- Pie de página -->
        <div class="footer">
            <p>Gracias por utilizar nuestros servicios.</p>
            <p>Reciba un cordial saludo,</p>
            <p><strong>Saint de Venezuela</strong></p>
        </div>
        
        <!-- Aviso legal -->
        <div class="disclaimer">
            Si la información contenida en este correo no es correcta, o desea realizar algún reclamo del servicio, comuníquese con el CANAL SAINT Autorizado.
        </div>
    </div>
</body>
</html>