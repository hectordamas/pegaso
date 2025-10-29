<div class="card mt-3">
    <div class="card-block p-0">
        <table class="table table-bordered table-sm mb-0 table-striped">
            <thead class="table-dark">
                <tr>
                    <th>TIPO DE SERVICIO</th>
                    <th>COMISIÓN</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($soporteTipoDeServicio as $t)
                    <tr>
                        <td><strong>{{ $t->name }}</strong></td>
                        @if ($t->porcentaje)
                            <td>{{ number_format($t->porcentaje, 2, ',', '.') }}%</td>
                        @else
                            <td>${{ number_format($t->comision_fija, 2, ',', '.') }}</td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
