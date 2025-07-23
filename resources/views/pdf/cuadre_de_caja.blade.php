<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Arqueo de Caja Diario</title>
    <style>
        @page {
            margin: 0cm;
        }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            margin: 0 1cm;
            color: #333;
        }

        h4 {
            margin-bottom: 8px;
            font-size: 16px;
            color: #002160; /* Azul oscuro */
        }

        .header {
            margin-bottom: 15px;
            font-size: 12px;
        }

        .card {
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .card-header {
            /*background-color: #002160; Azul oscuro */
            color: #000;
            padding: 8px 12px;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
        }

        .section-title {
            font-weight: bold;
            color: #002160; /* Azul oscuro */
            font-size: 12px;
            margin: 8px 0 4px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 2px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #000;
            padding: 5px;
            font-size: 10.5px;
        }

        th {
            background-color: #fff;
            font-weight: bold;
        }

        .text-end {
            text-align: right;
        }

        .bg-light-blue {
            background-color: #C0D4EC; /* Azul claro para totales */
        }

        .bg-rosa-suave {
            background-color: #FCE4D7; /* Rosa suave para egresos */
        }

        .bg-primary {
            background-color: #002160 !important; /* Azul oscuro */
            color: white;
        }

        .observaciones {
            padding: 10px;
            font-size: 11px;
            background-color: #f5f5f5;
            border: 1px solid #ddd;
        }

        .totales {
            background-color: #f5f5f5;
            font-weight: bold;
        }

        .resumen-titulo {
            background-color: #002160; /* Azul oscuro */
            color: white;
            padding: 6px 12px;
            font-weight: bold;
            font-size: 12px;
        }

        .table-light {
            background-color: #F9F9F9;
        }

        .bg-light-gray{
            background: #EDEDED;
        }

    </style>
</head>
<body>

    <h4>🧾 Arqueo de Caja Diario</h4>

    <div class="header">
        <strong>Fecha:</strong> {{ \Carbon\Carbon::parse($cuadre->fecha)->format('d-m-Y') }}<br>
        <strong>Orden N°:</strong> {{ $cuadre->numero_orden }}
    </div>

    @foreach($detalle as $index => $item)
        <div class="card">
            <div class="card-header">
                <div>{{ strtoupper($item['responsable']) }} - {{ strtoupper($item['tipo_pago']) }}</div>
                <div>SALDO: $ {{ number_format($item['saldo'], 2, ',', '.') }}</div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th colspan="4" class="bg-primary">                            
                            {{ strtoupper($item['tipo_pago']) }} - INGRESOS
                        </th>
                    </tr>
                    <tr>
                        <th>Cliente</th>
                        <th>Presupuesto</th>
                        <th>Descripción</th>
                        <th class="text-end">Valor</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($item['ingresos'] as $ing)
                        <tr>
                            <td>{{ $ing->cliente }}</td>
                            <td class="bg-rosa-suave">{{ $ing->presupuesto }}</td>
                            <td class="bg-rosa-suave">{{ $ing->descripcion }}</td>
                            <td class="text-end bg-light-gray">$ {{ number_format($ing->valor, 2, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-end bg-light-blue">Total</th>
                        <th class="text-end bg-light-blue">$ {{ number_format($item['total_ingreso'], 2, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>

            <table>
                <thead>
                    <tr>
                        <th colspan="2" class="bg-primary">                            
                            {{ strtoupper($item['tipo_pago']) }} - EGRESOS
                        </th>
                    </tr>
                    <tr>
                        <th>Descripción</th>
                        <th class="text-end">Valor</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($item['egresos'] as $egr)
                        <tr>
                            <td class="bg-light-gray">{{ $egr->descripcion }}</td>
                            <td class="text-end bg-rosa-suave">$ {{ number_format($egr->valor, 2, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th class="text-end bg-light-blue">Total</th>
                        <th class="text-end bg-light-blue">$ {{ number_format($item['total_egreso'], 2, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    @endforeach

    {{-- RESUMEN FINAL --}}
    <table>
        <thead>
            <tr>
                <th colspan="2" class="bg-primary">
                    RESUMEN
                </th>
            </tr>
        </thead>
        <tbody>
            @php $agrupadoPorResponsable = collect($detalle)->groupBy('responsable'); @endphp
            @foreach ($agrupadoPorResponsable as $responsable => $items)
                @foreach ($items as $i => $item)
                    <tr>
                        <td class="text-end">SALDO - {{ $responsable }} - {{ strtoupper($item['tipo_pago']) }}</td>
                        <td class="text-end">${{ number_format($item['saldo'], 2, ',', '.') }}</td>
                    </tr>
                @endforeach
                <tr class="bg-light-blue">
                    <td class="text-end"><strong>Total {{ strtoupper($responsable) }}</strong></td>
                    <td class="text-end"><strong>${{ number_format(collect($items)->sum('saldo'), 2, ',', '.') }}</strong></td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="bg-light-blue">
                <td class="text-end"><strong>Total General</strong></td>
                <td class="text-end"><strong>${{ number_format($saldo_general, 2, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>

    {{-- OBSERVACIONES --}}
    <div class="card" style="margin-top: 20px;">
        <div class="card-header bg-primary">Observaciones</div>
        <div class="observaciones">
            {{ $cuadre->observaciones ?? 'Sin observaciones.' }}
        </div>
    </div>

</body>
</html>
