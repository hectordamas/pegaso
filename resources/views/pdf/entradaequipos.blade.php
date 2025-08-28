<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $titulo }} DE EQUIPO</title>
    <style>
        body { 
            font-family: Arial, Helvetica, sans-serif; 
            font-size: 11px; 
            line-height: 1.4;
            color: #000;
        }
        .title { font-size: 22px; font-weight: bold; }
        .sub-title { font-size: 11px; }
        .info, .signature { font-size: 11px; text-align: left; }
    </style>
</head>
<body>
    <div class="title">SAINT</div>
    <div class="sub-title">
        Ds & Ds Sistemas 3000, C.A. <br>
        J-40163202-5
    </div>
    <div class="info">
        <b>{{ $titulo }} DE EQUIPO Nro:</b> {{ $entrada->codentrada }} <br>
        <b>Fecha:</b> {{ date('d/m/Y', strtotime($entrada->fecha)) }} <br>
        <b>R.I.F.:</b> {{ $entrada->saclie->rif ?? 'N/A' }} <br>
        <b>Cliente:</b> {{ $entrada->saclie->descrip ?? 'N/A' }} <br>
        <b>Código de Cliente:</b> {{ $entrada->saclie->codclie ?? 'N/A' }} <br>
        <b>Estatus:</b> {{ $entrada->estatus->nombre ?? 'N/A' }} <br><br>
        <b>Equipos:</b> {{ $entrada->observacion }} <br>
        <b>Actividad:</b> {{ $entrada->actividad }} <br><br>
        <b>Asignado a:</b> {{ $entrada->consultor->nombre ?? 'N/A' }}<br>
        <b>Usuario:</b> {{ $entrada->user->name ?? 'N/A' }}<br>
    </div>
    <div class="signature">
        Yo, <b>{{ $entrada->saclie->descrip ?? 'N/A' }}</b> notifico que estoy {{ $titulo2 }} los equipos con las observaciones antes descritas en este documento. <br><br>
        <b>NOTA:</b> De no estar de acuerdo no firmar este documento. <br><br><br><br><br><br>
        FIRMA: _______________________
    </div>
    <div class="info" style="margin-top:40px;">
        Con la firma de este documento, está aceptando nuestros términos y condiciones. Puede leerlos en <b>www.saintnet.net</b>, pestaña "Términos y Condiciones" o solicitarlos en recepción.
    </div>
</body>
</html>
