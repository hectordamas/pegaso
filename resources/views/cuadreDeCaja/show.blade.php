@extends('layouts.admin')

@section('styles')
<style>
    .header-azul-oscuro {
        background-color: #002259 !important; /* Azul oscuro */
        color: white !important;
    }

    .table thead.header-azul-oscuro th {
        color: white !important;
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
            <h4 class="mb-4">🧾 ARQUEO DE CAJA DIARIO</h4>
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
                <div class="card-header d-flex justify-content-between header-azul-oscuro">
                    <h6><strong>{{ $index + 1 }}.- {{ strtoupper($item['responsable']) }} - {{ strtoupper($item['tipo_pago']) }}</strong></h6>
                    <h6><strong>SALDO:</strong> $ {{ number_format($item['saldo'], 2, ',', '.') }}</h6>
                </div>
                <div class="card-block mt-3">
                    <div class="row">
                        {{-- INGRESOS --}}
                        <div class="col-md-8">
                            <h6 class="text-dark fw-bold">INGRESOS</h6>
                            <table class="table table-sm table-bordered">
                                <thead class="table-light">
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
                                <tfoot class="table-light">
                                    <tr>
                                        <th colspan="3" class="text-end">Total</th>
                                        <th class="text-end">$ {{ number_format($item['total_ingreso'], 2, ',', '.') }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
    
                        {{-- EGRESOS --}}
                        <div class="col-md-4">
                            <h6 class="text-dark fw-bold">EGRESOS</h6>
                            <table class="table table-sm table-bordered">
                                <thead class="table-light">
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
                                <tfoot class="table-light">
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
                <div class="card-header header-azul-oscuro">
                    <h6 class="mb-0 fw-bold">📊 RESUMEN</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Responsable</th>
                                <th>Método de Pago</th>
                                <th>Saldo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $agrupadoPorResponsable = collect($detalle)->groupBy('responsable');
                            @endphp
        
                            @foreach ($agrupadoPorResponsable as $responsable => $items)
                                @foreach ($items as $index => $item)
                                    <tr>
                                        @if ($index === 0)
                                            <td rowspan="{{ count($items) }}" class="align-middle fw-bold text-uppercase">
                                                {{ $responsable }}
                                            </td>
                                        @endif
                                        <td>{{ strtoupper($item['tipo_pago']) }}</td>
                                        <td>${{ number_format($item['saldo'], 2, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                                <tr class="table-warning">
                                    <td colspan="2" class="text-end"><strong>Total {{ strtoupper($responsable) }}</strong></td>
                                    <td><strong>${{ number_format($items->sum('saldo'), 2, ',', '.') }}</strong></td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-dark">
                                <td colspan="2" class="text-end"><strong>TOTAL GENERAL</strong></td>
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
                <div class="card-header header-azul-oscuro">
                    <strong>📌 OBSERVACIONES</strong>
                </div>
                <div class="card-body">
                    {{ $cuadre->observaciones ?? 'Sin observaciones.' }}
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
