

<style>
    .comision-wrapper {
        display: flex;
        gap: 20px;
        margin: 10px 0;
        align-items: center;
        font-family: Arial, sans-serif;
    }

    .comision-item {
        display: flex;
        align-items: center;
        gap: 8px;
        background: #f7f7f7;
        padding: 8px 14px;
        border-radius: 8px;
        border: 1px solid #ddd;
        transition: 0.2s;
    }

    .comision-item:hover {
        background: #ececec;
    }

    .comision-item input {
        transform: scale(1.2);
        cursor: pointer;
    }
</style>



<a href="javascript:void(0);" 
    onclick="soporteDetalles({{ $p->id }})"
    class="btn btn-warning">
    <i class="fas fa-list"></i> Ver Detalles
</a>

<div class="comision-wrapper">

    <label class="comision-item">
        <input type="checkbox" class="aplica_comision_soporte" data-id="{{ $p->id }}"
            {{ $p->aplica_comision_soporte ? 'checked' : '' }}>
        <!-- {{ $p->aplica_comision_soporte ? 'disabled' : '' }} -->

        Comisión Soporte
    </label>

    <label class="comision-item">
        <input type="checkbox" class="aplica_comision_proyecto" data-id="{{ $p->id }}"
            {{ $p->aplica_comision_proyecto ? 'checked' : '' }}>

        <!-- {{ $p->aplica_comision_soporte ? 'disabled' : '' }} -->

        Comisión Proyecto
    </label>

</div>
