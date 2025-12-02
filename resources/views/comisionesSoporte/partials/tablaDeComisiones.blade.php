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
                    <tr class="btnEditarComision" data-id="{{ $t->id }}" style="cursor: pointer;">
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


<div class="modal fade" id="modalEditarComision" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Editar Comisión</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <input type="hidden" id="tipo_id">

                <div class="form-group mb-3">
                    <label>Tipo de Comisión</label>
                    <select id="tipo_comision" class="form-control">
                        <option value="porcentaje">Porcentaje (%)</option>
                        <option value="fija">Comisión Fija ($)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label id="label_monto">Monto (%)</label>
                    <input type="number" step="0.01" class="form-control" id="monto_comision">
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button class="btn btn-primary" id="btnGuardarComision">Guardar Cambios</button>
            </div>

        </div>
    </div>
</div>