<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Informe de Proyecto</title>
    <style type="text/css">
        body {
            font-size: 15px;
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.4;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        td {
            padding: 5px;
            font-size: 14px;
        }

        h1, h2, h3, h4, h5, h6 {
            margin: 0;
        }

        .header-table td {
            vertical-align: middle;
        }

        .project-summary {
            background: #f0f4f8;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .project-summary h6 {
            font-size: 16px;
            margin-bottom: 8px;
            font-weight: bold;
        }

        .project-summary p {
            margin: 3px 0;
        }

        .status-badge {
            color: #fff;
            padding: 3px 7px;
            border-radius: 4px;
            font-size: 12px;
        }

        #informe-contenedor {
            width: 100%;
            max-width: 100%;
            overflow-wrap: break-word;
            word-wrap: break-word;
            word-break: break-word;
        }

        #informe-contenedor table {
            width: 100% !important;
            table-layout: fixed;
            border-collapse: collapse;
        }

        #informe-contenedor img {
            max-width: 100%;
            height: auto;
        }

        .history-item {
            background: #e8f0fe;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 10px;
            border-left: 4px solid #4285f4;
        }

        .history-item .user {
            font-weight: bold;
            font-size: 13px;
            margin-bottom: 4px;
        }

        .history-item .content {
            font-size: 14px;
            margin-left: 5px;
        }

        hr {
            border: none;
            border-top: 1px solid #ddd;
            margin: 15px 0;
        }
    </style>
</head>
<body>

    <!-- Encabezado -->
    <table class="header-table">
        <tr>
            <td width="10%">
                <img src="{{ public_path('assets/customAssets/img/logo_pdf.jpeg') }}" width="100" height="80">
            </td>
            <td width="60%">
                <span style="font-size:18px;">DS & DS SISTEMAS 3000, C.A.</span><br>
                <span>RIF J-40163202-5</span>
            </td>
            <td width="30%" align="right">
                <strong>Fecha: {{ \Carbon\Carbon::parse(now())->format('d/m/Y h:i a') }}</strong>
            </td>
        </tr>
    </table>

    <hr>

    <!-- Información del Proyecto -->
    <div class="project-summary">
        <h6>Resumen del Proyecto</h6>
        <p><strong>ID:</strong> {{ $proyecto->id }}</p>
        <p><strong>Número:</strong> {{ $proyecto->numerod }}</p>
        <p><strong>Descripción:</strong> {{ $proyecto->descrip ?? 'N/A' }}</p>
        <p><strong>Vendedor:</strong> {{ $proyecto->savend->descrip ?? 'N/A' }}</p>
        <p><strong>Estatus:</strong> 
            <span class="status-badge" style="background-color: {{ $proyecto->estatusPre->color ?? '#6c757d' }}">
                {{ $proyecto->estatusPre->nombre ?? 'N/A' }}
            </span>
        </p>
        <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($proyecto->fechae)->format('d/m/Y h:i a') }}</p>
        <p><strong>Total:</strong> {{ number_format($proyecto->mtototal, 2, ',', '.') }}</p>
    </div>

    <!-- Historial de Cambios -->
    @if($proyecto->historyitems->isNotEmpty())
        <h4 style="margin-bottom: 10px;">Bitácora de Gestión de Proyecto</h4>
        <div id="informe-contenedor">
            @foreach($proyecto->historyitems as $h)
                <div class="history-item">
                    <div class="user">{{ \App\Models\User::find($h->user_id)->name ?? 'Usuario' }} - {{ $h->created_at->format('d/m/Y H:i a') }}</div>
                    <div class="content">{!! $h->informe !!}</div>
                </div>
            @endforeach
        </div>
    @else
        <p>No hay registros en el historial.</p>
    @endif

</body>
</html>

