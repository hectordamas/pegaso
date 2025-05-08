<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Proyecto Aprobado</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8fafc;
            color: #333;
            padding: 20px;
        }

        .card {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 20px;
            max-width: 700px;
            margin: auto;
        }

        h2 {
            color: #28a745;
            text-align: center;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            text-align: left;
            vertical-align: top;
        }

        th {
            width: 30%;
            color: #555;
        }

        .badge {
            display: inline-block;
            padding: 5px 10px;
            font-size: 13px;
            color: #fff;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="card">
        <h2>✅ Proyecto Aprobado</h2>

        <p>Estimado equipo,</p>
        <p>El siguiente proyecto ha sido aprobado con éxito:</p>

        <table>
            <tr>
                <th>ID:</th>
                <td>{{ $proyecto->id }}</td>
            </tr>
            <tr>
                <th>Fecha:</th>
                <td>{{ \Carbon\Carbon::parse($proyecto->fechae)->format('d/m/Y h:i A') }}</td>
            </tr>
            <tr>
                <th>Número:</th>
                <td class="text-success fw-bold">PRE - {{ $proyecto->numerod }}</td>
            </tr>
            <tr>
                <th>Cliente:</th>
                <td>{{ $proyecto->saclie->descrip ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Exento:</th>
                <td>{{ number_format($proyecto->texento, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Gravable:</th>
                <td>{{ number_format($proyecto->tgravable, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Impuesto:</th>
                <td>{{ number_format($proyecto->mtotax, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Factor:</th>
                <td>{{ number_format($proyecto->factor, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Total:</th>
                <td>{{ number_format($proyecto->mtototal, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Total (Div):</th>
                <td>{{ $proyecto->factor ? number_format($proyecto->mtototal / $proyecto->factor, 2, ',', '.') : '0,00' }}</td>
            </tr>
            <tr>
                <th>Vendedor:</th>
                <td>{{ $proyecto->savend->descrip ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Estatus:</th>
                <td>
                    <span class="badge" style="background: {{ $proyecto->estatusPre->color ?? '#999' }}">
                        {{ $proyecto->estatusPre->nombre ?? 'N/A' }}
                    </span>
                </td>
            </tr>
        </table>

        <p style="margin-top: 20px;">Saludos,<br>Equipo {{ env('APP_NAME') }}</p>
    </div>
</body>
</html>
