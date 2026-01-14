@extends('layouts.admin')

@section('metadata')
    <title>VPS - {{ $client->descrip }} | {{ env('APP_NAME') }}</title>

    <style>
        /* ===== VPS PAYMENTS ===== */

        .vps-payment-card {
            background: #ffffff;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 4px 10px rgba(0, 0, 0, .05);
            display: flex;
            flex-direction: column;
            height: 100%;
            transition: transform .15s ease, box-shadow .15s ease;
        }

        .vps-payment-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(0, 0, 0, .08);
        }

        /* Header */
        .vps-payment-header {
            padding: 14px 16px;
            border-bottom: 1px solid #eef0f3;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .vps-payment-month {
            font-weight: 600;
            font-size: 15px;
            color: #1f2937;
        }

        /* Status */
        .vps-payment-status {
            font-size: 12px;
            padding: 4px 10px;
            border-radius: 20px;
            text-transform: uppercase;
            font-weight: 600;
        }

        .vps-payment-status.paid {
            background: #dcfce7;
            color: #166534;
        }

        .vps-payment-status.pending {
            background: #fef3c7;
            color: #92400e;
        }

        /* Body */
        .vps-payment-body {
            padding: 16px;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .vps-payment-amount {
            font-size: 20px;
            font-weight: 700;
            color: #111827;
        }

        /* Actions */
        .vps-payment-actions {
            display: flex;
            gap: 10px;
        }

        /* Buttons */
        .vps-btn {
            flex: 1;
            padding: 8px 10px;
            border-radius: 6px;
            font-size: 13px;
            cursor: pointer;
            border: none;
            transition: background .15s ease;
        }

        .vps-btn-edit {
            background: #2563eb;
            color: #ffffff;
        }

        .vps-btn-edit:hover {
            background: #1d4ed8;
        }

        .vps-btn-delete {
            background: #dc2626;
            color: #ffffff;
        }

        .vps-btn-delete:hover {
            background: #b91c1c;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header d-flex justify-content-between">
                    <h5>VPS – {{ $client->descrip }}</h5>
                    <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#addPayment">
                        <i class="fas fa-server"></i> Agregar Servicio VPS
                    </button>
                </div>

                <div class="card-block">
                    <div class="row">
                        @php
                            $meses = [
                                1 => 'Enero',
                                2 => 'Febrero',
                                3 => 'Marzo',
                                4 => 'Abril',
                                5 => 'Mayo',
                                6 => 'Junio',
                                7 => 'Julio',
                                8 => 'Agosto',
                                9 => 'Septiembre',
                                10 => 'Octubre',
                                11 => 'Noviembre',
                                12 => 'Diciembre',
                            ];
                        @endphp

                        @forelse ($payments as $payment)
                            <div class="col-md-4">
                                <div class="vps-payment-card">

                                    <div class="vps-payment-header">
                                        <span class="vps-payment-month">
                                            {{ $meses[$payment->month] ?? $payment->month }}
                                        </span>

                                        <span class="vps-payment-status {{ $payment->status }}">
                                            {{ $payment->status == 'paid' ? 'Pagado' : 'Pendiente' }}
                                        </span>
                                    </div>

                                    <div class="vps-payment-body">
                                        <div class="vps-payment-amount">
                                            ${{ number_format($payment->amount, 2) }}
                                        </div>

                                        <div class="vps-payment-actions">
                                            <button class="vps-btn vps-btn-edit" data-bs-toggle="modal"
                                                data-bs-target="#editPaymentModal{{ $payment->id }}">
                                                Ver / Editar
                                            </button>

                                            <form method="POST" action="{{ route('vps.payment.delete', $payment->id) }}"
                                                class="delete-form" data-type="vps">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="vps-btn vps-btn-delete btn-delete">
                                                    Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                </div>
                            </div>


                            <!-- Modal para ver y editar pago -->
                            <div class="modal fade" id="editPaymentModal{{ $payment->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <form method="POST" action="{{ route('vps.payment.update', $payment->id) }}"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')

                                        <div class="modal-content">

                                            <div class="modal-header">
                                                <h5 class="modal-title">
                                                    Pago – {{ $meses[$payment->month] ?? $payment->month }}
                                                    {{ date($payment->year) }}
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>

                                            <div class="modal-body">

                                                {{-- COMPROBANTE (PRIMERO) --}}
                                                <div class="mb-4">
                                                    <label class="fw-bold mb-2 d-block">Comprobante</label>

                                                    @if ($payment->receipt)
                                                        @php
                                                            $mime = explode(';', $payment->receipt)[0];
                                                        @endphp

                                                        <div class="mb-2 text-center">
                                                            @if (str_contains($mime, 'pdf'))
                                                                <iframe src="{{ $payment->receipt }}" width="100%"
                                                                    height="500"
                                                                    style="border:1px solid #ddd;border-radius:6px;"></iframe>
                                                            @else
                                                                <img src="{{ $payment->receipt }}" class="img-fluid"
                                                                    style="max-height:350px;border-radius:6px;"
                                                                    alt="Comprobante">
                                                            @endif
                                                        </div>
                                                    @endif

                                                    <input type="file" name="receipt" class="form-control"
                                                        accept="image/*,application/pdf">

                                                    <small class="text-muted">
                                                        Subir un archivo reemplazará el comprobante actual
                                                    </small>
                                                </div>

                                                {{-- FORMULARIO EN DOS COLUMNAS --}}
                                                <div class="row g-3">

                                                    {{-- MES --}}
                                                    <div class="col-md-6">
                                                        <label class="fw-bold">Fecha del Servicio</label>
                                                        <input type="date" name="fecha" class="form-control" required
                                                            value="{{ $payment->fecha }}">
                                                    </div>

                                                    {{-- MONTO --}}
                                                    <div class="col-md-6">
                                                        <label class="fw-bold">Monto</label>
                                                        <input type="number" step="0.01" name="amount"
                                                            class="form-control" value="{{ $payment->amount }}" required>
                                                    </div>

                                                    {{-- ESTADO --}}
                                                    <div class="col-md-6">
                                                        <label class="fw-bold">Estado</label>
                                                        <select name="status" class="form-control">
                                                            <option value="pending"
                                                                {{ $payment->status == 'pending' ? 'selected' : '' }}>
                                                                Pendiente
                                                            </option>
                                                            <option value="paid"
                                                                {{ $payment->status == 'paid' ? 'selected' : '' }}>
                                                                Pagado
                                                            </option>
                                                        </select>
                                                    </div>

                                                </div>

                                            </div>

                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-dark btn-sm">
                                                    Guardar cambios
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm"
                                                    data-bs-dismiss="modal">
                                                    Cerrar
                                                </button>
                                            </div>

                                        </div>
                                    </form>
                                </div>
                            </div>


                        @empty
                            <h5 class="text-center mb-3">No hay pagos registrados para este cliente.</h5>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        @if (Auth::user()->role == 'Directiva')
            <div class="col-md-12">
                <div class="card shadow-sm mb-4">
                    <div class="card-header d-flex justify-content-between">
                        <h5>Credenciales</h5>

                        <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#addCredentials">
                            <i class="fas fa-key"></i> Agregar Credenciales
                        </button>
                    </div>
                    <div class="card-block">
                        <div class="row">
                            @php
                                $fieldsByGroup = $fields->groupBy('group');
                            @endphp

                            @forelse ($fieldsByGroup as $group => $groupFields)
                                <div class="col-md-6">
                                    <table class="table table-sm table-bordered">
                                        <thead>
                                            <tr class="bg-dark">
                                                <th colspan="3">{{ $group ?? 'Sin grupo' }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($groupFields as $field)
                                                <tr>
                                                    <td class="fw-bold">{{ $field->label }}</td>
                                                    <td>
                                                        {{ $field->value }}
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="d-flex justify-content-center gap-1">
                                                            <button class="btn btn-sm btn-warning btn-edit-credential"
                                                                data-target="#editCredential{{ $field->id }}">
                                                                <i class="fas fa-edit"></i>
                                                            </button>

                                                            <form method="POST"
                                                                action="{{ route('vps.field.delete', $field->id) }}"
                                                                class="delete-form" data-type="credential">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn btn-sm btn-danger btn-delete"
                                                                    title="Eliminar">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>


                                                <div class="modal fade" id="editCredential{{ $field->id }}"
                                                    tabindex="-1">
                                                    <div class="modal-dialog">
                                                        <form method="POST"
                                                            action="{{ route('vps.field.update', $field->id) }}">
                                                            @csrf
                                                            @method('PUT')

                                                            <div class="modal-content">

                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">
                                                                        Editar credencial
                                                                    </h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"></button>
                                                                </div>

                                                                <div class="modal-body">

                                                                    {{-- GRUPO --}}
                                                                    <div class="mb-3">
                                                                        <label class="fw-bold">Grupo</label>
                                                                        <input list="groupsList{{ $field->id }}"
                                                                            name="group" class="form-control"
                                                                            value="{{ $field->group }}">
                                                                        <datalist id="groupsList{{ $field->id }}">
                                                                            <option value="Windows">
                                                                            <option value="AnyDesk">
                                                                            <option value="SQL">
                                                                            <option value="Saint">
                                                                            <option value="RAdmin">

                                                                            <option value="IP Servidor">

                                                                        </datalist>
                                                                    </div>

                                                                    {{-- ETIQUETA --}}
                                                                    <div class="mb-3">
                                                                        <label class="fw-bold">Etiqueta</label>
                                                                        <input type="text" name="label"
                                                                            class="form-control"
                                                                            value="{{ $field->label }}" required>
                                                                    </div>

                                                                    {{-- VALOR --}}
                                                                    <div class="mb-3">
                                                                        <label class="fw-bold">Valor</label>
                                                                        <input type="text" name="value"
                                                                            class="form-control"
                                                                            value="{{ $field->value }}" required>
                                                                    </div>

                                                                </div>

                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn btn-dark btn-sm">
                                                                        Guardar cambios
                                                                    </button>
                                                                    <button type="button"
                                                                        class="btn btn-secondary btn-sm"
                                                                        data-bs-dismiss="modal">
                                                                        Cancelar
                                                                    </button>
                                                                </div>

                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @empty
                                <h5 class="text-center mb-3">No has agregado credenciales de VPS a este cliente.</h5>
                            @endforelse
                        </div>

                    </div>
                </div>

            </div>
        @endif

    </div>

    <div class="modal fade" id="addCredentials" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form method="POST" action="{{ route('vps.fields.add', $client->codclie) }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5>Agregar múltiples credenciales</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" id="credentialsContainer">

                        <div class="credential-row mb-2">
                            <div class="row g-2">
                                {{-- Grupo editable --}}
                                <div class="col-md-4">
                                    <input list="groupsList" name="group[]" class="form-control"
                                        placeholder="Grupo (windows, anydesk)">
                                    <datalist id="groupsList">
                                        <option value="Windows">
                                        <option value="AnyDesk">
                                        <option value="SQL">
                                        <option value="Saint">
                                        <option value="RAdmin">

                                        <option value="IP Servidor">

                                    </datalist>
                                </div>

                                <div class="col-md-4">
                                    <input type="text" name="label[]" class="form-control" placeholder="Etiqueta">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="value[]" class="form-control" placeholder="Valor">
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" id="addRow">+ Agregar fila</button>
                        <button type="submit" class="btn btn-dark btn-sm">Guardar todas</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <div class="modal fade" id="addPayment" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('vps.payment.add', $client->codclie) }}"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5>Agregar pago</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">

                        <div class="mb-3">
                            <label>Fecha del Servicio</label>
                            <input type="date" name="fecha" class="form-control" required
                                value="{{ old('fecha', date('Y-m-d')) }}">
                        </div>

                        <div class="mb-3">
                            <label>Monto</label>
                            <input type="number" step="0.01" name="amount" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Comprobante (opcional)</label>
                            <input type="file" name="receipt" class="form-control" accept="image/*,application/pdf">
                        </div>

                        <div class="mb-3">
                            <label>Estado</label>
                            <select name="status" class="form-control">
                                <option value="pending">Pendiente</option>
                                <option value="paid">Pagado</option>
                            </select>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-dark btn-sm">Guardar pago</button>
                    </div>
                </div>
            </form>
        </div>
    </div>




    <script>
        document.getElementById('addRow').addEventListener('click', function() {
            let container = document.getElementById('credentialsContainer');
            let row = container.querySelector('.credential-row').cloneNode(true);
            row.querySelectorAll('input').forEach(input => input.value = '');
            row.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
            container.appendChild(row);
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteForms = document.querySelectorAll('.delete-form');

            // Aquí defines la clave correcta (idealmente esto debería validarse en el servidor también)
            const correctKey = '$bKe02KI@FL1j&GxAJW-'; // reemplaza con la clave real o usa variable de servidor

            deleteForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    let type = form.dataset.type;
                    let message = '¿Estás seguro que deseas eliminar este elemento?';

                    if (type === 'vps') {
                        message =
                            '¿Deseas eliminar este servicio VPS?\n\nEsta acción no se puede deshacer.';
                    }
                    if (type === 'credential') {
                        message =
                            '¿Deseas eliminar esta credencial?\n\nEsta acción no se puede deshacer.';
                    }

                    // Primero pedimos la clave
                    let userKey = prompt("Ingresa la clave para continuar:");

                    if (userKey === null) return; // si cancela
                    if (userKey !== correctKey) {
                        alert("Clave incorrecta. No se puede continuar.");
                        return;
                    }

                    // Si la clave es correcta, preguntamos la confirmación normal
                    if (confirm(message)) {
                        form.submit();
                    }
                });
            });


        });
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editButtons = document.querySelectorAll('.btn-edit-credential');
            const correctKey = '$bKe02KI@FL1j&GxAJW-'; // tu clave

            editButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault(); // evitamos cualquier acción automática
                    const userKey = prompt("Ingresa la clave para editar esta credencial:");
                    if (userKey === null || userKey !== correctKey) {
                        alert("Clave incorrecta. No puedes editar.");
                        return;
                    }

                    // Clave correcta → abrimos el modal manualmente
                    const target = button.getAttribute('data-target');
                    const modalEl = document.querySelector(target);
                    const modal = new bootstrap.Modal(modalEl);
                    modal.show();
                });
            });
        });
    </script>

@endsection
