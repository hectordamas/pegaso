<div class="movimiento-item card shadow-sm mb-3">
    <div class="card-block">
        <div class="row">
            <div class="form-group col-md-3">
                <label>Responsable</label>
                <select name="responsable[]" class="form-control" required>
                    <option value="">Seleccione</option>
                    <option value="DS Sistemas 3000" {{ isset($movimiento) ? ($movimiento->responsable == 'DS Sistemas 3000' ? 'selected' : '') : ''}}>DS Sistemas 3000</option>
                    <option value="Daniel Sousa"  {{ isset($movimiento) ? ($movimiento->responsable == 'Daniel Sousa' ? 'selected' : '') : ''}}>Daniel Sousa</option>
                </select>
            </div>

            <div class="form-group col-md-3">
                <label>Tipo de Pago</label>
                <select name="tipo_pago[]" class="form-control" required>
                    <option value="">Seleccione</option>
                    <option value="Efectivo" {{ isset($movimiento) ? ($movimiento->tipo_pago == 'Efectivo' ? 'selected' : '') : ''}}>Efectivo</option>
                    <option value="Transferencia" {{ isset($movimiento) ? ($movimiento->tipo_pago == 'Transferencia' ? 'selected' : '') : ''}}>Transferencia</option>
                    <option value="Zelle" {{ isset($movimiento) ? ($movimiento->tipo_pago == 'Zelle' ? 'selected' : '') : ''}}>Zelle</option>
                    <option value="Pago Móvil" {{ isset($movimiento) ? ($movimiento->tipo_pago == 'Pago Móvil' ? 'selected' : '') : ''}}>Pago Móvil</option>
                    <option value="Otro" {{ isset($movimiento) ? ($movimiento->tipo_pago == 'Otro' ? 'selected' : '') : ''}}>Otro</option>
                </select>
            </div>

            <div class="form-group col-md-3">
                <label>Tipo de Movimiento</label>
                <select name="tipo_movimiento[]" class="form-control" required>
                    <option value="">Seleccione</option>
                    <option value="Ingreso" {{ isset($movimiento) ? ($movimiento->tipo_movimiento == 'Ingreso' ? 'selected' : '') : ''}}>Ingreso</option>
                    <option value="Egreso" {{ isset($movimiento) ? ($movimiento->tipo_movimiento == 'Egreso' ? 'selected' : '') : ''}}>Egreso</option>
                </select>
            </div>

            <div class="form-group col-md-3">
                <label>Cliente</label>
                <select name="codclie[]" class="form-control select-cliente">
                    <option value="">Seleccione un cliente</option>
                    @foreach ($saclie as $cliente)
                        <option value="{{ $cliente->codclie }}" {{  isset($movimiento) ? ($movimiento->codclie ==  $cliente->codclie ? 'selected' : '') : '' }}>
                            {{ $cliente->descrip }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-md-3">
                <label>Presupuesto</label>
                <input type="text" name="presupuesto[]" class="form-control" value="{{ $movimiento->presupuesto ?? '' }}" >
            </div>

            <div class="form-group col-md-3">
                <label>Descripción</label>
                <input type="text" name="descripcion[]" class="form-control" value="{{ $movimiento->descripcion ?? '' }}" >
            </div>

            <div class="form-group col-md-3">
                <label>Valor</label>
                <input type="text" step="any" name="valor[]" class="form-control montopcd" value="{{ number_format($movimiento->valor, 2, ',', '.')  ?? '' }}"  onkeyup="convertirmonto(this.form)" required>
            </div>

            <div class="form-group col-md-2 d-flex align-items-end">
                <div class="btn-group">
                    <button type="button" class="btn btn-danger" onclick="this.closest('.movimiento-item').remove()">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                    <button type="button" class="btn btn-success" onclick="agregarMovimiento()">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
