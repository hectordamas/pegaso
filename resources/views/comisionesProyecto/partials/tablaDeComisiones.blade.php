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
                <tr>
                    <td>Total Proyectos</td>
                    <td>${{ number_format($totalComisionProyecto, 2, '.', ',') }}</td>
                    <td>{{$projectM->proyecto_comision}}%</td>
                </tr>
                <tr>
                    <td>Total Servicios</td>
                    <td>${{ number_format($totalComisionServicio, 2, '.', ',') }}</td>
                    <td>{{$projectM->servicio_comision}}%</td>
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
