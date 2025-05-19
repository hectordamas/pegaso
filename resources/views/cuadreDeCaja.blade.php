@extends('layouts.admin')
@section('metadata')
<title>Cuadre de Caja - {{ env('APP_NAME') }}</title>
@endsection

@section('styles')
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5>Cuadre de Caja</h5>
                    <span>📠 Histórico de Arqueos de caja diarios</span>
                </div>

                <div>
                    <a href="{{ route('cuadre-de-caja.create') }}" class="btn btn-primary shadow"> 
                        <i class="fas fa-cash-register"></i> Registrar Cuadre de Caja
                    </a>
                </div>

            </div>
            <div class="card-block">

                <div class="table-responsive">
                    @if($cuadresDeCaja->isEmpty())
                        <div class="alert alert-warning mb-0">No hay registros de cuadre de caja aún.</div>
                    @else
                        <table class="table table-hover align-middle table-bordered table-striped" id="cuadre-table">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Fecha</th>
                                    <th>N° Orden</th>
                                    <th>Total Daniel</th>
                                    <th>Total DS</th>
                                    <th>Observaciones</th>
                                    <th>Acciones</th>
                                    <th>Revisado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cuadresDeCaja as $cuadre)
                                    <tr>
                                        <td>{{ $cuadre->id }}</td>
                                        <td>{{ \Carbon\Carbon::parse($cuadre->fecha)->format('d/m/Y') }}</td>
                                        <td>{{ $cuadre->numero_orden ?? '—' }}</td>
                                        <td class="text-success fw-bold">$ {{ number_format($cuadre->total_daniel_sousa, 2, ',', '.') }}</td>
                                        <td class="text-primary fw-bold">$ {{ number_format($cuadre->total_ds_sistemas_3000, 2, ',', '.') }}</td>
                                        <td>{{ Str::limit($cuadre->observaciones, 40, '...') }}</td>
                                        <td>
                                            <a href="{{ route('cuadre-de-caja.show', $cuadre->id) }}" class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('cuadre-de-caja.edit', $cuadre->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <input class="checkbox-revisado" 
                                                {{ $cuadre->revisado ? 'checked' : '' }} 
                                                {{ auth()->user()->master ? '' : 'disabled' }} 
                                                data-id="{{ $cuadre->id }}" 
                                                type="checkbox"
                                            >
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        $(document).on('change', '.checkbox-revisado', function() {
            var id = $(this).data('id');
            var revisado = $(this).is(':checked') ? 1 : 0;

            $.ajax({
                url: `cuadre-de-caja/${id}/revisado`,
                method: 'POST',
                data: {
                    revisado: revisado,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.success) {
                        console.log('Estado actualizado');
                    } else {
                        alert('Error al actualizar el estado');
                    }
                },
                error: function () {
                    alert('Error al contactar el servidor');
                }
            });
        });
    });
</script>
@endsection