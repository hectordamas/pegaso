<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CxC;

class CorregirAbonosCxC extends Command
{
    protected $signature = 'cxc:corregir-abonos';
    protected $description = 'Corrige el campo abono en CxC para registros tipo Proyecto o Entrega';

    public function handle()
    {
        $this->info("Iniciando corrección de abonos...");

        $cxcs = CxC::with('detallecxc')
            ->where(function ($q) {
                $q->where('observacion', 'like', 'Proyecto:%')
                  ->orWhere('observacion', 'like', 'Entrega:%');
            })
            ->get();

        $totalActualizados = 0;

        foreach ($cxcs as $cxc) {
            $sumaReal = $cxc->detallecxc->sum('monto');

            if ($cxc->abono != $sumaReal) {
                $cxc->abono = $sumaReal;
                $cxc->save();
                $this->line("Actualizado CxC {$cxc->codcxc}: abono = {$sumaReal}");
                $totalActualizados++;
            }
        }

        $this->info("Corrección completada. Total actualizados: {$totalActualizados}");

        return 0;
    }
}
