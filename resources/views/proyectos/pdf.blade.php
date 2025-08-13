<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Informe de Proyecto</title>
    <style type="text/css">
        body {
            font-size: 16px;
            font-family: Arial, sans-serif;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        td {
            padding: 8px;
            font-size: 15px;
        }
        .h1 {
            font-size: 21px;
            font-weight: bold;
        }
        .h2 {
            font-size: 18px;
            font-weight: bold;
        }
        .h3 {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            padding: 5px;
            background-color: #dfdfdf;
        }
        .border {
            border: 1px solid #000;
        }
        .linea {
            border-bottom: 1px dotted #000;
            display: block;
            height: 25px;
            width: 100%;
        }
        .firma {
            border-bottom: 1px solid #000;
            height: 40px;
            width: 100%;
        }
        .observaciones {
            height: 80px;
            border: 1px solid #000;
            padding: 10px;
        }
        .campo-escribible {
            height: 30px;
            width: 100%;
            border-bottom: 1px solid #000;
            text-align: center;
        }
        .project-summary {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .project-summary h6 {
            margin-bottom: 5px;
            font-weight: bold;
        }
        .project-summary p {
            margin: 0;
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
    </style>
</head>
<body>

    <!-- Encabezado -->
    <table>
        <tr>
            <td width="10%">
                <img src="{{ public_path('assets/customAssets/img/logo_pdf.jpeg') }}" width="100" height="80">
            </td>
            <td width="60%">
                <span style="font-size:18px;">DS & DS SISTEMAS 3000, C.A.</span><br>
                <span>RIF J-40163202-5</span>
            </td>
            <td width="30%" align="right">
                <strong>Fecha: {{ \Carbon\Carbon::parse($proyecto->fechae)->format('d/m/Y h:i a') }}</strong>
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
        <p><strong>Estatus:</strong> <span style="background-color: {{ $proyecto->estatusPre->color ?? '#e9e9e9' }};
             color: #fff;
             padding: 2px 6px;
             border-radius: 4px;
             font-size: 12px;">
    {{ $proyecto->estatusPre->nombre ?? 'N/A' }}
</span></p>
        <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($proyecto->fechae)->format('d/m/Y h:i a') }}</p>
        <p><strong>Total:</strong> {{ number_format($proyecto->mtototal, 2, ',', '.') }}</p>
    </div>
    <!-- Informe del Proyecto -->
    <div id="informe-contenedor">
        {!! $proyecto->informe ?? '<p style="text-align:center;">No se ha redactado el informe aún.</p>' !!}
    </div>
</body>
</html>
