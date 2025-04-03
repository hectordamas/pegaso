<?php

namespace App\Http\Controllers\Updaters;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Safact;
use App\Model\Saitemfac;

class SafactController extends Controller
{
    public function SafactWs(Request $request)
	{
		try	{
			$data = Array();
			$datosReq = $request->all(); 
			for ($i = 0; $i < count($datosReq); $i++) 
			{
				$data['serial'][$i] 	= @$datosReq[$i]['Serial'];
				$data['tipofac'][$i] 	= @$datosReq[$i]['Tipofac'];
				$data['numerod'][$i] 	= @$datosReq[$i]['Numerod'];
				$data['otipo'][$i] 		= @$datosReq[$i]['OTipo'];
				$data['onumero'][$i] 	= @$datosReq[$i]['ONumero'];
				$data['nrounico'][$i] 	= @$datosReq[$i]['Nrounico'];
				$data['numerof'][$i] 	= @$datosReq[$i]['Numerof'];
				$data['numeror'][$i] 	= @$datosReq[$i]['Numeror'];
				$data['factor'][$i] 	= @$datosReq[$i]['Factor'];
				$data['codclie'][$i] 	= @$datosReq[$i]['Codclie'];
				$data['descrip'][$i] 	= @$datosReq[$i]['Descrip'];
				$data['monto'][$i] 		= @$datosReq[$i]['Monto'];
				$data['mtotax'][$i] 	= @$datosReq[$i]['Mtotax'];
				$data['fletes'][$i] 	= @$datosReq[$i]['Fletes'];
				$data['tgravable'][$i] 	= @$datosReq[$i]['Tgravable'];
				$data['texento'][$i] 	= @$datosReq[$i]['Texento'];
				$data['costoprd'][$i] 	= @$datosReq[$i]['Costoprd'];
				$data['costosrv'][$i] 	= @$datosReq[$i]['Costosrv'];
				$data['reteniva'][$i] 	= @$datosReq[$i]['Reteniva'];
				$data['fechae'][$i] 	= @$datosReq[$i]['Fechae'];
				$data['mtototal'][$i] 	= @$datosReq[$i]['Mtototal'];
				$data['contado'][$i] 	= @$datosReq[$i]['Contado'];
				$data['credito'][$i] 	= @$datosReq[$i]['Credito'];
				$data['descto1'][$i] 	= @$datosReq[$i]['Descto1'];
				$data['descto2'][$i]	= @$datosReq[$i]['Descto2'];
				$data['codesta'][$i] 	= @$datosReq[$i]['CodEsta'];
				$data['codusua'][$i] 	= @$datosReq[$i]['CodUsua'];
				$data['codvend'][$i] 	= @$datosReq[$i]['CodVend'];
				$data['signo'][$i] 		= @$datosReq[$i]['Signo'];
				$data['codubic'][$i] 	= @$datosReq[$i]['CodUbic'];
				$data['evento'][$i]		= @$datosReq[$i]['Evento'];
			}
			for ($i=0; $i < count($data['serial']); $i++) 
			{
				$pgConsulSQL = Safact::where('serial', '=', $data['serial'][$i])
							->where('tipofac','=',$data['tipofac'][$i])
							->where('numerod','=',$data['numerod'][$i])
							->where('nrounico','=',$data['nrounico'][$i])
							->first();

				if($data['evento'][$i]=='D')
				{
					$pgConsulSQL->delete();
					
				}else{

					if(empty($pgConsulSQL)) 
					{
						$pgSQL = new Safact();
						$pgSQL->serial=$data['serial'][$i];
						$pgSQL->tipofac=$data['tipofac'][$i];
						$pgSQL->numerod=$data['numerod'][$i];
						$pgSQL->otipo=$data['otipo'][$i];
						$pgSQL->onumero=$data['onumero'][$i];
						$pgSQL->nrounico=(int)$data['nrounico'][$i];
						$pgSQL->numerof=$data['numerof'][$i];
						$pgSQL->numeror=$data['numeror'][$i];
						$pgSQL->factor=str_replace(',','.',$data['factor'][$i]);
						$pgSQL->codclie=trim($data['codclie'][$i]);
						$pgSQL->descrip=$data['descrip'][$i];
						$pgSQL->monto=str_replace(',','.',$data['monto'][$i]);
						$pgSQL->mtotax=str_replace(',','.',$data['mtotax'][$i]);
						$pgSQL->fletes=str_replace(',','.',$data['fletes'][$i]);
						$pgSQL->tgravable=str_replace(',','.',$data['tgravable'][$i]);
						$pgSQL->texento=str_replace(',','.',$data['texento'][$i]);
						$pgSQL->costoprd=str_replace(',','.',$data['costoprd'][$i]);
						$pgSQL->costosrv=str_replace(',','.',$data['costosrv'][$i]);
						$pgSQL->reteniva=str_replace(',','.',$data['reteniva'][$i]);
						$pgSQL->fechae= $data['fechae'][$i];
						$pgSQL->mtototal=str_replace(',','.',$data['mtototal'][$i]);
						$pgSQL->contado=str_replace(',','.',$data['contado'][$i]);
						$pgSQL->credito=str_replace(',','.',$data['credito'][$i]);
						$pgSQL->descto1=str_replace(',','.',$data['descto1'][$i]);
						$pgSQL->descto2=str_replace(',','.',$data['descto2'][$i]);
						$pgSQL->codesta=$data['codesta'][$i];
						$pgSQL->codusua=$data['codusua'][$i];
						$pgSQL->codvend=$data['codvend'][$i];
						$pgSQL->signo=$data['signo'][$i];
						$pgSQL->codubic = $data['codubic'][$i];
						$pgSQL->save();
					}else{
						/*$pgConsulSQL->numerof	=	$data['numerof'][$i];
						$pgConsulSQL->numeror	=	$data['numeror'][$i];
						$pgConsulSQL->fechae	= 	$data['fechae'][$i];
						$pgConsulSQL->otipo		=	$data['otipo'][$i];
						$pgConsulSQL->onumero	=	$data['onumero'][$i];
						$pgConsulSQL->codvend	=	$data['codvend'][$i];
						$pgConsulSQL->codubic 	= 	$data['codubic'][$i];
						$pgConsulSQL->factor	=	str_replace(',','.',$data['factor'][$i]);*/
						$pgConsulSQL->otipo		=	$data['otipo'][$i];
						$pgConsulSQL->onumero	=	$data['onumero'][$i];
						$pgConsulSQL->nrounico	=	(int)$data['nrounico'][$i];
						$pgConsulSQL->numerof	=	$data['numerof'][$i];
						$pgConsulSQL->numeror	=	$data['numeror'][$i];
						$pgConsulSQL->factor	=	str_replace(',','.',$data['factor'][$i]);
						$pgConsulSQL->codclie	=	trim($data['codclie'][$i]);
						$pgConsulSQL->descrip	=	$data['descrip'][$i];
						$pgConsulSQL->monto		=	str_replace(',','.',$data['monto'][$i]);
						$pgConsulSQL->mtotax	=	str_replace(',','.',$data['mtotax'][$i]);
						$pgConsulSQL->fletes	=	str_replace(',','.',$data['fletes'][$i]);
						$pgConsulSQL->tgravable	=	str_replace(',','.',$data['tgravable'][$i]);
						$pgConsulSQL->texento	=	str_replace(',','.',$data['texento'][$i]);
						$pgConsulSQL->costoprd	=	str_replace(',','.',$data['costoprd'][$i]);
						$pgConsulSQL->costosrv	=	str_replace(',','.',$data['costosrv'][$i]);
						$pgConsulSQL->reteniva	=	str_replace(',','.',$data['reteniva'][$i]);
						$pgConsulSQL->fechae	=	$data['fechae'][$i];
						$pgConsulSQL->mtototal	=	str_replace(',','.',$data['mtototal'][$i]);
						$pgConsulSQL->contado	=	str_replace(',','.',$data['contado'][$i]);
						$pgConsulSQL->credito	=	str_replace(',','.',$data['credito'][$i]);
						$pgConsulSQL->descto1	=	str_replace(',','.',$data['descto1'][$i]);
						$pgConsulSQL->descto2	=	str_replace(',','.',$data['descto2'][$i]);
						$pgConsulSQL->codesta	=	$data['codesta'][$i];
						$pgConsulSQL->codusua	=	$data['codusua'][$i];
						$pgConsulSQL->codvend	=	$data['codvend'][$i];
						$pgConsulSQL->signo		=	$data['signo'][$i];
						$pgConsulSQL->codubic	=	$data['codubic'][$i];
						$pgConsulSQL->save();
					}
				}
			}
			$response = ["success" => "COD_000","mensaje"=>"Registro Exitoso..."];
		}catch (\Exception $e){
			$response = ["success" => "CODE_000","mensaje"=>$e->getMessage().' Linea: '.$e->getLine()];
        }
		return response()->json($response);	
	}


	public function SaitemfacWs(Request $request)
	{
	    try{
		
    		$data = Array();
            $datosReq = $request->all(); 
            for ($i = 0; $i < count($datosReq); $i++) 
            {
				$data['serial'][$i] 	= @$datosReq[$i]['Serial'];
    			$data['TipoFac'][$i] 	= @$datosReq[$i]['TipoFac'];
    			$data['NumeroD'][$i] 	= @$datosReq[$i]['NumeroD'];
    			$data['OTipo'][$i] 		= @$datosReq[$i]['OTipo'];
    			$data['ONumero'][$i]  	= @$datosReq[$i]['ONumero'];
    			$data['Status'][$i] 	= @$datosReq[$i]['Status'];
    			$data['NroLinea'][$i] 	= @$datosReq[$i]['NroLinea'];
    			$data['NroLineaC'][$i] 	= @$datosReq[$i]['NroLineaC'];
    			$data['CodItem'][$i] 	= @$datosReq[$i]['CodItem'];
    			$data['CodUbic'][$i] 	= @$datosReq[$i]['CodUbic'];
    			$data['CodMeca'][$i] 	= @$datosReq[$i]['CodMeca'];
    			$data['CodVend'][$i] 	= @$datosReq[$i]['CodVend'];
    			$data['Descrip1'][$i] 	= @$datosReq[$i]['Descrip1'];
    			$data['Descrip2'][$i] 	= @$datosReq[$i]['Descrip2'];
    			$data['Descrip3'][$i] 	= @$datosReq[$i]['Descrip3'];
    			$data['Descrip4'][$i] 	= @$datosReq[$i]['Descrip4'];
    			$data['Descrip5'][$i] 	= @$datosReq[$i]['Descrip5'];
    			$data['Descrip6'][$i] 	= @$datosReq[$i]['Descrip6'];
    			$data['Descrip7'][$i] 	= @$datosReq[$i]['Descrip7'];
    			$data['Descrip8'][$i] 	= @$datosReq[$i]['Descrip8'];
    			$data['Descrip9'][$i] 	= @$datosReq[$i]['Descrip9'];
    			$data['Descrip10'][$i] 	= @$datosReq[$i]['Descrip10'];
    			$data['Refere'][$i] 	= @$datosReq[$i]['Refere'];
    			$data['Signo'][$i] 		= @$datosReq[$i]['Signo'];
    			$data['CantMayor'][$i] 	= @$datosReq[$i]['CantMayor'];
    			$data['Cantidad'][$i] 	= @$datosReq[$i]['Cantidad'];
    			$data['CantidadO'][$i] 	= @$datosReq[$i]['CantidadO'];
    			$data['Tara'][$i] 		= @$datosReq[$i]['Tara'];
    			$data['ExistAntU'][$i] 	= @$datosReq[$i]['ExistAntU'];
    			$data['ExistAnt'][$i] 	= @$datosReq[$i]['ExistAnt'];
    			$data['CantidadU'][$i] 	= @$datosReq[$i]['CantidadU'];
    			$data['CantidadC'][$i] 	= @$datosReq[$i]['CantidadC'];
    			$data['CantidadA'][$i] 	= @$datosReq[$i]['CantidadA'];
    			$data['CantidadUA'][$i] = @$datosReq[$i]['CantidadUA'];
    			$data['TotalItem'][$i] 	= @$datosReq[$i]['TotalItem'];
    			$data['Costo'][$i] 		= @$datosReq[$i]['Costo'];
    			$data['Precio'][$i] 	= @$datosReq[$i]['Precio'];
    			$data['Descto'][$i] 	= @$datosReq[$i]['Descto'];
    			$data['NroUnicoL'][$i] 	= @$datosReq[$i]['NroUnicoL'];
    			$data['NroLote'][$i] 	= @$datosReq[$i]['NroLote'];
    			$data['FechaE'][$i] 	= @$datosReq[$i]['FechaE'];
    			$data['FechaL'][$i] 	= @$datosReq[$i]['FechaL'];
    			$data['FechaV'][$i] 	= @$datosReq[$i]['FechaV'];
    			$data['EsServ'][$i] 	= @$datosReq[$i]['EsServ'];
				$data['EsUnid'][$i] 	= @$datosReq[$i]['EsUnid'];
				$data['EsFreeP'][$i] 	= @$datosReq[$i]['EsFreeP'];
				$data['EsPesa'][$i] 	= @$datosReq[$i]['EsPesa'];
				$data['EsExento'][$i] 	= @$datosReq[$i]['EsExento'];
				$data['UsaServ'][$i] 	= @$datosReq[$i]['UsaServ'];
				$data['DEsLote'][$i] 	= @$datosReq[$i]['DEsLote'];
				$data['DEsSeri'][$i] 	= @$datosReq[$i]['DEsSeri'];
				$data['DEsComp'][$i] 	= @$datosReq[$i]['DEsComp'];
				$data['NumeroE'][$i] 	= @$datosReq[$i]['NumeroE'];
				$data['CodSucu'][$i] 	= @$datosReq[$i]['CodSucu'];
				$data['MtoTax'][$i] 	= @$datosReq[$i]['MtoTax'];
				$data['PriceO'][$i] 	= @$datosReq[$i]['PriceO'];
				$data['MtoTaxO'][$i] 	= @$datosReq[$i]['MtoTaxO'];
				$data['TipoPVP'][$i] 	= @$datosReq[$i]['TipoPVP'];
				$data['evento'][$i]		= @$datosReq[$i]['Evento'];
				/*$data['Factor'][$i] = $datosReq[$i]['Factor'];
				$data['ONroLinea'][$i] = $datosReq[$i]['ONroLinea'];
				$data['ONroLineaC'][$i] = $datosReq[$i]['ONroLineaC'];
				$data['CantidadT'][$i] = $datosReq[$i]['CantidadT'];*/
						
    		}
    		for ($i=0; $i < count($data['serial']); $i++) 
			{
    			$pgConsulSQL = saitemfac::where('serial','=',$data['serial'][$i])
										->where('TipoFac','=',$data['TipoFac'][$i])
										->where('NumeroD','=',$data['NumeroD'][$i])
										->where('NroLinea','=',$data['NroLinea'][$i])
										//->where('NroLineaC','=',0)
										->first();

				if($data['evento'][$i]=='D')
				{
					$pgConsulSQL->delete();
					
				}else{

					if(empty($pgConsulSQL)) 
					{
						$pgSQL = new saitemfac();
						$pgSQL->serial=$data['serial'][$i];
						$pgSQL->TipoFac=$data['TipoFac'][$i];
						$pgSQL->NumeroD=$data['NumeroD'][$i];
						$pgSQL->OTipo= $data['OTipo'][$i];
						$pgSQL->ONumero=$data['ONumero'][$i];
						$pgSQL->Status=$data['Status'][$i];
						$pgSQL->NroLinea=$data['NroLinea'][$i];
						$pgSQL->NroLineaC=$data['NroLineaC'][$i];
						$pgSQL->CodItem=$data['CodItem'][$i];
						$pgSQL->CodUbic=$data['CodUbic'][$i];
						$pgSQL->CodMeca=$data['CodMeca'][$i];
						$pgSQL->CodVend=$data['CodVend'][$i];
						$pgSQL->Descrip1=$data['Descrip1'][$i];
						$pgSQL->Descrip2=$data['Descrip2'][$i];
						$pgSQL->Descrip3=$data['Descrip3'][$i];
						$pgSQL->Descrip4=$data['Descrip4'][$i];
						$pgSQL->Descrip5=$data['Descrip5'][$i];
						$pgSQL->Descrip6=$data['Descrip6'][$i];
						$pgSQL->Descrip7=$data['Descrip7'][$i];
						$pgSQL->Descrip8=$data['Descrip8'][$i];
						$pgSQL->Descrip9=$data['Descrip9'][$i];
						$pgSQL->Descrip10=$data['Descrip10'][$i];
						$pgSQL->Refere=$data['Refere'][$i];
						$pgSQL->Signo=$data['Signo'][$i];
						$pgSQL->CantMayor=$data['CantMayor'][$i];
						$pgSQL->Cantidad=$data['Cantidad'][$i];
						$pgSQL->CantidadO=$data['CantidadO'][$i];
						$pgSQL->Tara=$data['Tara'][$i];
						$pgSQL->ExistAntU=$data['ExistAntU'][$i];
						$pgSQL->ExistAnt=$data['ExistAnt'][$i];
						$pgSQL->CantidadU=$data['CantidadU'][$i];
						$pgSQL->CantidadC=$data['CantidadC'][$i];
						$pgSQL->CantidadA=$data['CantidadA'][$i];
						$pgSQL->CantidadUA=$data['CantidadUA'][$i];
						$pgSQL->TotalItem=$data['TotalItem'][$i];
						$pgSQL->Costo=$data['Costo'][$i];
						$pgSQL->Precio=$data['Precio'][$i];
						$pgSQL->Descto=$data['Descto'][$i];
						$pgSQL->NroUnicoL=$data['NroUnicoL'][$i];
						$pgSQL->NroLote=$data['NroLote'][$i];
						$pgSQL->FechaE= $data['FechaE'][$i];// $data['FechaE'][$i];
					   // $pgSQL->FechaL=$data['FechaL'][$i];
					   // $pgSQL->FechaV=$data['FechaV'][$i];
						$pgSQL->EsServ=$data['EsServ'][$i];
						$pgSQL->EsUnid=$data['EsUnid'][$i];
						$pgSQL->EsFreeP=$data['EsFreeP'][$i];
						$pgSQL->EsPesa=$data['EsPesa'][$i];
						$pgSQL->EsExento=$data['EsExento'][$i];
						$pgSQL->UsaServ=$data['UsaServ'][$i];
						$pgSQL->DEsLote=$data['DEsLote'][$i];
						$pgSQL->DEsSeri=$data['DEsSeri'][$i];
						$pgSQL->DEsComp=$data['DEsComp'][$i];
						$pgSQL->NumeroE=$data['NumeroE'][$i];
						$pgSQL->CodSucu=$data['CodSucu'][$i];
						$pgSQL->MtoTax=$data['MtoTax'][$i];
						$pgSQL->TipoPVP=$data['TipoPVP'][$i];
						/*$pgSQL->Factor=$data['Factor'][$i];
						$pgSQL->ONroLinea=$data['ONroLinea'][$i];
						$pgSQL->ONroLineaC=$data['ONroLineaC'][$i];
						$pgSQL->CantidadT=$data['CantidadT'][$i];*/
								
						$pgSQL->save();
					}else{
						$pgConsulSQL->FechaE= $data['FechaE'][$i];
						$pgConsulSQL->save();
					}
    			}		
    		}
    		$response = ["success" => "COD_000"];

		}catch (\Exception $e){
			
			$response = ["success" => "CODE_000","mensaje"=>$e->getMessage().' Linea: '.$e->getLine()];

        }
        
        return response()->json($response);	
		//return response()->json($datosReq);	
	}
}
