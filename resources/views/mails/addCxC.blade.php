@php
	
	

@endphp
<!doctype html>

<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
    <title>DETALLES DE REGISTRO DE WALLET</title>
</head>
<body>
    
	@foreach($datos as $reg)
		<?php  
			if($reg->codoperacion==1){
				$operacion='DEBITO';
			}
			if($reg->codoperacion==2){
				$operacion='CREDITO';
			}
		
		?>
	
		<BR><span style="font-weight:bold;">WALLET: {{$reg->wallet}}</span><BR>
		<p>Estos son los datos del Registro:</p>
		<ul>
			<li>Fecha Registro: {{date('d/m/Y',strtotime($reg->fecha))}}</li>
			<li>Fecha Pago: {{date('d/m/Y',strtotime($reg->fechapag))}}</li>
			<li>Moneda: <b>{{$reg->moneda}}</b> </li>
			<li>Tipo Pago: {{$reg->tipomoneda}} </li>
			<li>Operación: {{$operacion}} </li>
			<li>Monto: {{ number_format($reg->monto,2,',','.')}} </li>
			<li>Usuario: {{$reg->usuario}} </li>
			<li><b>Descripción:</b> <span style="text-align:justify;font-size:12px;font-weight:bold;color:green;" >{{$reg->descripcion}}</span> </li>
			
			<br>
		</ul>
    @endforeach
</body>
</html>