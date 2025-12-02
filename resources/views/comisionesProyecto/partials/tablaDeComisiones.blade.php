<div class="card mt-3">
    <div class="card-block p-0">
        <table class="table table-bordered table-sm mb-0 table-striped">
            <thead class="table-dark">
                <tr>
                    <th>TIPO DE SERVICIO</th>
                    <th>COMISIÓN</th>
                    <th>COMISIÓN PORCENTAJE</th>
                </tr>
            </thead>
            <tbody>
                <tr class="btnEditarComisionGeneral" data-type="proyecto" data-id="{{ $projectM->id }}" style="cursor: pointer;">
                    <td>Total Proyectos</td>
                    <td>${{ number_format($totalComisionProyecto, 2, '.', ',') }}</td>
                    <td>{{ $projectM->proyecto_comision }}%</td>
                </tr>

                <tr class="btnEditarComisionGeneral" data-type="servicio" data-id="{{ $projectM->id }}" style="cursor: pointer;">
                    <td>Total Servicios</td>
                    <td>${{ number_format($totalComisionServicio, 2, '.', ',') }}</td>
                    <td>{{ $projectM->servicio_comision }}%</td>
                </tr>
            </tbody>
            <tfoot class="table-dark">
                <tr>
                    <th>Total Comisión</th>
                    <th>${{ number_format($totalComision, 2, '.', ',') }}</th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<div class="modal fade" id="modalEditarComisionGeneral" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="tituloModalGeneral">Editar Comisión</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <input type="hidden" id="general_id">
                <input type="hidden" id="general_type">

                <div class="form-group mb-3">
                    <label>Monto (%)</label>
                    <input type="number" step="0.01" class="form-control" id="general_monto">
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button class="btn btn-primary" id="btnGuardarComisionGeneral">Guardar</button>
            </div>

        </div>
    </div>
</div>

