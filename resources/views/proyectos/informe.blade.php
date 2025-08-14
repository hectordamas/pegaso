@extends('layouts.admin')

@section('metadata')
    <title>Historial de Proyecto - {{ env('APP_NAME') }}</title>
@endsection

@section('styles')
<style>
    .project-summary {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 20px;
    }
    .project-summary h6 { margin-bottom: 10px; font-weight: bold; }
    .project-summary p { margin: 0; }
    .historial-item {
        border: 1px solid #ddd;
        padding: 10px;
        margin-bottom: 10px;
        border-radius: 5px;
    }
    .chat-history {
        display: flex;
        flex-direction: column;
        gap: 10px;
        max-height: 400px;
        overflow-y: auto;
    }
    
    .chat-bubble {
        padding: 10px 15px;
        border-radius: 15px;
        max-width: 75%;
    }
    
    .chat-bubble.me {
        background-color: #d1e7dd;
        align-self: flex-end;
        text-align: right;
    }
    
    .chat-bubble.other {
        background-color: #f8d7da;
        align-self: flex-start;
        text-align: left;
    }
    
    .chat-text {
        margin-top: 5px;
        word-wrap: break-word;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5>Historial de Informes del Proyecto</h5>
                <span>📝 Cada registro representa un estado o bitácora del proyecto.</span>
            </div>
            <div class="card-body">

                <!-- Resumen del proyecto -->
                <div class="project-summary">
                    <h6>Resumen del Proyecto</h6>
                    <p><strong>ID:</strong> {{ $proyecto->id }}</p>
                    <p><strong>Número:</strong> {{ $proyecto->numerod }}</p>
                    <p><strong>Descripción:</strong> {{ $proyecto->descrip ?? 'N/A' }}</p>
                    <p><strong>Vendedor:</strong> {{ $proyecto->savend->descrip ?? 'N/A' }}</p>
                    <p><strong>Estatus:</strong> <span class="badge" style="background: {{ $proyecto->estatusPre->color ?? '#e9e9e9' }}">{{ $proyecto->estatusPre->nombre ?? 'N/A' }}</span></p>
                    <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($proyecto->fechae)->format('d/m/Y h:i a') }}</p>
                </div>


                <!-- Lista de historiales existentes -->
                <h6 class="fw-bold mb-3">Bitácora de Gestión de Proyecto</h6>
                <div class="chat-history">
                    @forelse($proyecto->historyitems as $h)
                        @php
                            $usuario = \App\Models\User::find($h->user_id);
                            $esYo = $h->user_id === auth()->id(); // Para diferenciar tus propios cambios
                        @endphp
                        <div class="chat-bubble {{ $esYo ? 'me' : 'other' }}">
                            <small><strong>{{ $usuario->name ?? 'Usuario' }} - {{ $h->created_at->format('d/m/Y H:i a') }}</strong></small>
                            <div class="chat-text">{!! $h->informe !!}</div>
                        </div>
                    @empty
                        <p>No hay registros en el historial.</p>
                    @endforelse
                </div>



                <hr>

                <!-- Nuevo informe / bitácora -->
                <form id="formHistorial" action="{{ url('proyectos/'.$proyecto->id.'/informe') }}" method="POST">
                    @method('PUT')
                    @csrf
                    <input type="hidden" name="proyecto_id" value="{{ $proyecto->id }}">
                    <div class="mb-3">
                        <label for="informe" class="form-label">Nuevo Cambio</label>
                        <textarea name="informe" id="informe" class="form-control" rows="3" placeholder="Escribe el informe..."></textarea>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Guardar Historial
                        </button>
                        <a href="{{ route('proyectos.informe.pdf', $proyecto->id) }}" class="btn btn-danger" target="_blank">
                            <i class="far fa-file-pdf"></i> Descargar Historial
                        </a>
                        <a href="{{ route('proyectos.index') }}" class="btn btn-secondary">
                            <i class="far fa-times-circle"></i> Volver
                        </a>
                    </div>
                </form>





            </div>
        </div>
    </div>
</div>
@endsection
