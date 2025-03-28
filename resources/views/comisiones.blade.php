@extends('layouts.admin')

@section('metadata')
<title>Comisiones - {{ env('APP_NAME') }}</title>
@endsection

@php
    $meses = collect([
        ['numero' => 1, 'nombre' => 'Enero'],
        ['numero' => 2, 'nombre' => 'Febrero'],
        ['numero' => 3, 'nombre' => 'Marzo'],
        ['numero' => 4, 'nombre' => 'Abril'],
        ['numero' => 5, 'nombre' => 'Mayo'],
        ['numero' => 6, 'nombre' => 'Junio'],
        ['numero' => 7, 'nombre' => 'Julio'],
        ['numero' => 8, 'nombre' => 'Agosto'],
        ['numero' => 9, 'nombre' => 'Septiembre'],
        ['numero' => 10, 'nombre' => 'Octubre'],
        ['numero' => 11, 'nombre' => 'Noviembre'],
        ['numero' => 12, 'nombre' => 'Diciembre'],
    ])->map(fn($mes) => (object) $mes);
@endphp

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Reporte de Comisiones 💰</h5>
                <span>Filtra y analiza las comisiones del equipo de ventas</span>
            </div>
            <div class="card-block">
                <div class="row">    
                    <div class="col-md-4 form-group">
                        <label for="mes" class="fw-bold mb-2">Selecciona un Mes</label>
                        <select name="mes" id="mes" class="form-control">
                            @foreach($meses as $mes)
                                <option {{ $mes->numero == date('n') ? 'selected' : '' }} value="{{ $mes->numero }}">
                                    {{ $mes->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
    
                    <div class="col-md-12">
                        <button type="button" class="btn btn-primary" id="filtrar">
                            <i class="fas fa-search"></i> Filtrar
                        </button>
                    </div>
    
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-12" id="comisionesContainer">

    </div>

</div>
@endsection

@section('scripts')
<script>    
    var comisionesTable;

    function initializeComisionesTable(){
        if ($.fn.DataTable.isDataTable('#comisiones-table')) {
            $('#comisiones-table').dataTable().fnClearTable();
            $('#comisiones-table').dataTable().fnDestroy();
        } 

        $('#comisiones-table').DataTable({
            deferRender: true,
            order: [[0, 'desc']],
            responsive: true,
            lengthChange: false,
            autoWidth: false,
            lengthMenu: [
                [10, 50, 100, 150, -1],
                [10, 50, 100, 150, 'Todos']
            ],
            pageLength: -1,
            dom: 'Bfrtip',
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
            language: {
                sProcessing: "Procesando...",
                sLengthMenu: "Mostrar _MENU_ registros",
                sZeroRecords: "No se encontraron resultados",
                sEmptyTable: "Ningún dato disponible en esta tabla",
                sInfo: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
                sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
                sSearch: "Buscar:",
                oPaginate: {
                    sFirst: "Primero",
                    sLast: "Último",
                    sNext: "Siguiente",
                    sPrevious: "Anterior"
                },
                buttons: {
                    pageLength: {
                        _: "Ver %d Registros",
                        '-1': "Todos"
                    },
                    colvis: "Columnas",
                    copy: "Copiar",
                    print: "Imprimir"
                }
            }
        });
    }

    function getComisionData(){
        $("#loadingSpinner").css("display", "flex"); 

        let formData = {
            mes: $('#mes').val(),
            comisiones: []
        };

        // Recorrer la tabla y capturar los valores de los inputs
        if($('#comisiones-table tbody .comisiones-row').length){
            $('#comisiones-table tbody .comisiones-row').each(function() {
                let row = $(this);

                let data = {
                    id: row.data('id'),  // Asumimos que cada fila tiene un atributo data-id con el ID de la comisión
                    servicio: row.find('.servicio').val(),
                    producto: row.find('.producto').val(),
                    gerencia: row.find('.gerencia').val(),
                    es_gerente: row.find('.es_gerente').is(':checked') ? 1 : 0
                };

                formData.comisiones.push(data);
            });
        }

        $.ajax({
            url: "{{ route('comisiones.balance') }}",
            type: "GET",
            data: formData,
            success: function (response) {
                console.log(response)

                $("#loadingSpinner").css("display", "none"); 
                $('#comisionesContainer').html(response.html);
                $("[data-toggle='tooltip']").tooltip(); //Tooltip
                initializeComisionesTable();
            },
            error: function (err) {
                console.log(err)
                $("#loadingSpinner").css("display", "none"); 
                Swal.fire({
                    title: "Error",
                    text: "Ocurrió un problema al intentar actualizar los datos.",
                    icon: "error",
                    confirmButtonText: "Entendido"
                });
            }
        });
    }

    $(document).ready(function(){
        getComisionData(); // Ejecutar al iniciar la vista

        $(document).on('change', 'input[type="checkbox"], #mes', function() {
            getComisionData();
        });

        $(document).on('click', '.update-comisiones, #filtrar', function() {
            getComisionData();
        });
    });
</script>
@endsection

