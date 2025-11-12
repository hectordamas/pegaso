<form action="{{ url('comisionesSoporte/addComisionesSoporteInfo') }}" method="POST" id="formInfo" class="row">
    @csrf
    <input type="hidden" name="safact_id" value="{{ $safact->id }}">

    <!-- Origen -->
    <div class="col-md-6 form-group">
        <label for="origen">Origen</label>
        <select name="origen" id="origen" class="form-control">
            <option value="">Selecciona un Elemento</option>

            @php
                // Obtener todos los posibles orígenes únicos
                $opcionesOrigen = collect($tiposServicio)
                    ->pluck('origen')
                    ->filter()
                    ->map(fn($o) => json_decode($o, true))
                    ->collapse()
                    ->unique()
                    ->values()
                    ->toArray();
            @endphp

            @foreach ($opcionesOrigen as $origen)
                <option value="{{ $origen }}" {{ $safact->soporte_origen == $origen ? 'selected' : '' }}>
                    {{ $origen }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Tipo de Servicio -->
    <div class="col-md-6 form-group">
        <label for="tipo_servicio">Tipo de Servicio</label>
        <select name="tipo_servicio" id="tipo_servicio" class="form-control">
            <option value="">Selecciona un Tipo</option>
            @foreach ($tiposServicio as $tipo)
                <option value="{{ $tipo->id }}"
                    {{ $safact->soporte_tipo_servicio == $tipo->name ? 'selected' : '' }}>
                    {{ $tipo->name }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Estatus -->
    <div class="col-md-6 form-group">
        <label for="estatus">Estatus</label>
        <select name="estatus" id="estatus" class="form-control">
            <option value="">Selecciona un Estatus</option>
            @foreach (['PENDIENTE', 'EN PROCESO', 'REALIZADO'] as $estatus)
                <option value="{{ $estatus }}" {{ $safact->soporte_status == $estatus ? 'selected' : '' }}>
                    {{ $estatus }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-12 text-end mt-3">
        <button type="submit" class="btn btn-primary">Guardar</button>
    </div>
</form>
