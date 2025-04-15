<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Arqueo de Caja Diario</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            margin: 30px;
            color: #333;
        }

        h4 {
            margin-bottom: 8px;
            font-size: 16px;
            color: #002259;
        }

        .header {
            margin-bottom: 15px;
            font-size: 12px;
        }

        .card {
            border: 1px solid #ddd;
            margin-bottom: 18px;
            border-radius: 5px;
        }

        .card-header {
            background-color: #002259;
            color: white;
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
            color: #444;
            font-size: 12px;
            margin: 8px 0 4px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 2px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
        }

        th, td {
            border: 1px solid #bbb;
            padding: 5px;
            font-size: 10.5px;
        }

        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .text-end {
            text-align: right;
        }

        .bg-warning {
            background-color: #ffe082;
        }

        .bg-primary {
            background-color: #002259;
            color: white;
        }

        .observaciones {
            padding: 10px;
            font-size: 11px;
        }

        .totales {
            background-color: #f5f5f5;
            font-weight: bold;
        }

        .resumen-titulo {
            background-color: #004080;
            color: white;
            padding: 6px 12px;
            font-weight: bold;
            font-size: 12px;
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
                <div>{{ $index + 1 }}.- {{ strtoupper($item['responsable']) }} - {{ strtoupper($item['tipo_pago']) }}</div>
                <div>SALDO: $ {{ number_format($item['saldo'], 2, ',', '.') }}</div>
            </div>

            <div class="section-title">Ingresos</div>
            <table>
                <thead>
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
                            <td>{{ $ing->presupuesto }}</td>
                            <td>{{ $ing->descripcion }}</td>
                            <td class="text-end">$ {{ number_format($ing->valor, 2, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="totales">
                        <td colspan="3" class="text-end">Total</td>
                        <td class="text-end">$ {{ number_format($item['total_ingreso'], 2, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>

            <div class="section-title">Egresos</div>
            <table>
                <thead>
                    <tr>
                        <th>Descripción</th>
                        <th class="text-end">Valor</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($item['egresos'] as $egr)
                        <tr>
                            <td>{{ $egr->descripcion }}</td>
                            <td class="text-end">$ {{ number_format($egr->valor, 2, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="totales">
                        <td class="text-end">Total</td>
                        <td class="text-end">$ {{ number_format($item['total_egreso'], 2, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @endforeach

    {{-- RESUMEN FINAL --}}
    <div class="card">
        <div class="resumen-titulo">Resumen</div>
        <table>
            <thead>
                <tr>
                    <th>Responsable</th>
                    <th>Método de Pago</th>
                    <th class="text-end">Saldo</th>
                </tr>
            </thead>
            <tbody>
                @php $agrupadoPorResponsable = collect($detalle)->groupBy('responsable'); @endphp
                @foreach ($agrupadoPorResponsable as $responsable => $items)
                    @foreach ($items as $i => $item)
                        <tr>
                            @if ($i === 0)
                                <td rowspan="{{ count($items) }}">{{ $responsable }}</td>
                            @endif
                            <td>{{ strtoupper($item['tipo_pago']) }}</td>
                            <td class="text-end">${{ number_format($item['saldo'], 2, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    <tr class="bg-warning">
                        <td colspan="2" class="text-end"><strong>Total {{ strtoupper($responsable) }}</strong></td>
                        <td class="text-end"><strong>${{ number_format(collect($items)->sum('saldo'), 2, ',', '.') }}</strong></td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="totales">
                    <td colspan="2" class="text-end"><strong>Total General</strong></td>
                    <td class="text-end"><strong>${{ number_format($saldo_general, 2, ',', '.') }}</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>

    {{-- OBSERVACIONES --}}
    <div class="card">
        <div class="card-header bg-primary">Observaciones</div>
        <div class="observaciones">
            {{ $cuadre->observaciones ?? 'Sin observaciones.' }}
        </div>
    </div>

</body>
</html>
