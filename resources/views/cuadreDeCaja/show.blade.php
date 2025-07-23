@extends('layouts.admin')

@section('styles')
<style>
    table{
        border: #000 !important;
    }
    .header-azul-oscuro {
        background-color: #00225B !important; /* Azul oscuro */
        color: white !important;
    }

    .table thead.header-azul-oscuro th {
        color: white !important;
    }

    .bg-light-blue {
        background-color: #C0D4EC !important; /* Azul claro para los totales */
    }

    .bg-light-pink {
        background-color: #FCE4D7 !important; /* Rosa suave para egresos */
    }

    .bg-warning {
        background-color: #FCE4D7; /* Rosa suave para los totales de responsable */
    }

    .bg-primary {
        background-color: #00225B; /* Azul oscuro */
        color: white;
    }

    .totales {
        background-color: #f5f5f5;
        font-weight: bold;
    }
</style>
@endsection

@section('metadata')
<title>Arqueo de Caja Diario - {{ env('APP_NAME') }}</title>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <h5 class="mb-4">🧾 ARQUEO DE CAJA DIARIO</h5>
        </div>

        <div class="col-md-6 text-right">
            <a href="{{ route('cuadre-de-caja.pdf', [ 'id' => $cuadre->id ]) }}" target="_blank" class="btn btn-success">
                <i class="fas fa-print"></i> Imprimir
            </a>
        </div>

        <div class="col-md-12">
            <div class="d-flex justify-content-between mb-3">
                <div><strong>FECHA:</strong> {{ \Carbon\Carbon::parse($cuadre->fecha)->format('d-m-Y') }}</div>
                <div><strong>ORDEN N°:</strong> {{ $cuadre->numero_orden }}</div>
            </div>
        </div>

        @foreach($detalle as $index => $item)
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between">
                    <h6><strong>{{ $index + 1 }}.- {{ strtoupper($item['responsable']) }} - {{ strtoupper($item['tipo_pago']) }}</strong></h6>
                    <h6>$ {{ number_format($item['saldo'], 2, ',', '.') }}</h6>
                </div>
                <div class="card-block">
                    <div class="row">
                        <div class="col-md-8 table-responsive dt-responsive">
                            <table class="table table-sm table-bordered">
                                <thead class="header-azul-oscuro">
                                    <tr>
                                        <th colspan="4" class="fw-bold">                            
                                            {{ strtoupper($item['tipo_pago']) }} - INGRESOS
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th>Cliente</th>
                                        <th>Presupuesto</th>
                                        <th>Descripción</th>
                                        <th class="text-end">Valor</th>
                                    </tr>
                                    @foreach ($item['ingresos'] as $ing)
                                        <tr>
                                            <td>{{ $ing->saclie->descrip ?? 'N/A' }}</td>
                                            <td class="bg-light-pink">{{ $ing->presupuesto }}</td>
                                            <td class="bg-light-pink">{{ $ing->descripcion }}</td>
                                            <td style="background: #EDEDED;" class="text-end">$ {{ number_format($ing->valor, 2, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-light-blue">
                                    <tr>
                                        <th colspan="3" class="text-end">Total</th>
                                        <th class="text-end">$ {{ number_format($item['total_ingreso'], 2, ',', '.') }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
    
                        <div class="col-md-4 table-responsive dt-responsive">
                            <table class="table table-sm table-bordered">
                                <thead class="header-azul-oscuro">
                                    <tr>
                                        <th colspan="4" class="fw-bold">                            
                                            {{ strtoupper($item['tipo_pago']) }} - EGRESOS
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th>Descripción</th>
                                        <th class="text-end">Valor</th>
                                    </tr>
                                    @foreach ($item['egresos'] as $egr)
                                        <tr>
                                            <td style="background: #EDEDED;">{{ $egr->descripcion }}</td>
                                            <td class="text-end bg-light-pink">$ {{ number_format($egr->valor, 2, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-light-blue">
                                    <tr>
                                        <th class="text-end">Total</th>
                                        <th class="text-end">$ {{ number_format($item['total_egreso'], 2, ',', '.') }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        <div class="col-md-8">
            {{-- RESUMEN FINAL AGRUPADO POR RESPONSABLE --}}
            <div class="card shadow-sm">
                <div class="card-body p-0 table-responsive dt-responsive">
                    <table class="table table-bordered mb-0">
                        <thead class="header-azul-oscuro">
                            <tr>
                                <th colspan="2">
                                    📊 RESUMEN
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $agrupadoPorResponsable = collect($detalle)->groupBy('responsable');
                            @endphp
        
                            @foreach ($agrupadoPorResponsable as $responsable => $items)
                                @foreach ($items as $index => $item)
                                    <tr>
                                        <td class="align-middle text-uppercase">
                                            Saldo - {{ $responsable }} - {{ strtoupper($item['tipo_pago']) }}
                                        </td>
                                        <td>${{ number_format($item['saldo'], 2, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                                <tr class="bg-light-blue">
                                    <td class="text-end"><strong>Total {{ strtoupper($responsable) }}</strong></td>
                                    <td><strong>${{ number_format($items->sum('saldo'), 2, ',', '.') }}</strong></td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-light-blue">
                                <td class="text-end"><strong>TOTAL GENERAL</strong></td>
                                <td><strong>${{ number_format($saldo_general, 2, ',', '.') }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        


        <div class="col-md-4">
            {{-- OBSERVACIONES --}}
            <div class="card shadow">
                <div class="card-body p-0 table-responsive dt-responsive">
                    <table class="table table-bordered mb-0">
                        <thead class="header-azul-oscuro">
                            <tr>
                                <th>📌 Observaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    {{ $cuadre->observaciones ?? 'Sin observaciones.' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
