<!DOCTYPE html>
<html>
<head>
    <title>Orden de Servicio</title>
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
    </style>
</head>
<body>

    <table>
        <tr>
            <td width="10%"><img src="{{public_path('assets/customAssets/img/logo_pdf.jpeg')}}" width="100" height="80"></td>
            <td width="60%">
                <span style="font-size:18px;">DS & DS SISTEMAS 3000, C.A.</span><br>
                <span>RIF J-40163202-5</span>
            </td>
            <td width="30%" align="right"><strong>Fecha: {{ $fecha }}</strong></td>
        </tr>
    </table>

    <br>

    <table class="border">
        <tr>
            <td class="h3">ORDEN DE SERVICIO Nro.: {{ $codvisita }}</td>
        </tr>
        <tr>
            <td><strong>Cliente:</strong> {{ $descrip }}</td>
        </tr>
        <tr>
            <td><strong>Consultor:</strong> {{ $consultor }}</td>
        </tr>
    </table>

    <br>

    <table class="border">
        <tr>
            <td class="h3">Actividades a Realizar</td>
        </tr>
        <tr>
            <td>{!! nl2br($observacion) !!}</td>
        </tr>
    </table>

    <br>

    <table class="border">
        <tr>
            <td class="h3">Observaciones</td>
        </tr>
        <tr>
            <td class="observaciones"></td>
        </tr>
    </table>

    <br>

    <table>
        <tr>
            <td width="25%"><strong>Hora Entrada:</strong></td>
            <td class="campo-escribible"></td>
            <td width="25%"><strong>Hora Salida:</strong></td>
            <td class="campo-escribible"></td>
            <td width="25%"><strong>Cant. Horas:</strong></td>
            <td class="campo-escribible"></td>
        </tr>
    </table>

    <br><br>

    <table>
        <tr>
            <td width="20%"></td>
            <td width="30%" align="center">
                <strong>Firma Cliente</strong>
                <div class="firma"></div>
            </td>
            <td width="30%" align="center">
                <strong>Firma Consultor</strong>
                <div class="firma"></div>
            </td>
            <td width="20%"></td>
        </tr>
    </table>

    <br><br>

    <div style="text-align:center; font-size:10px;">
        Dirección: Av. Francisco de Miranda con Av. Principal de Las Mercedes,<br>
        El Rosal. Torre EASO piso 4 oficina 4D<br>
        Telf.: (0212) 7620253 / 7629064 / 3270160 / 4176900<br>
        www.saintnet.net / ventas@saintnet.net
    </div>

</body>
</html>
