<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CxC;

class CorregirMontosCxC extends Command
{
    protected $signature = 'cxc:corregir-montos';
    protected $description = 'Corrige el campo monto de CxC usando el campo tgravable de Safact (base imponible)';

    public function handle()
    {
        $this->info("Corrigiendo montos de CxC desde tgravable de Safact...");

        $cxcs = CxC::with('safact')
            ->whereNotNull('safact_id') // Asegura que haya relación con una factura
            ->get();

        $totalActualizados = 0;

        foreach ($cxcs as $cxc) {
            $tgravable = optional($cxc->safact)->tgravable / optional($cxc->safact)->factor;

            if ($tgravable !== null && $cxc->monto != $tgravable) {
                $cxc->monto = $tgravable;
                $cxc->save();
                $this->line("Actualizado CxC {$cxc->codcxc}: monto = {$tgravable}");
                $totalActualizados++;
            }
        }

        $this->info("✅ Corrección completa. Total actualizados: {$totalActualizados}");

        return 0;
    }
}