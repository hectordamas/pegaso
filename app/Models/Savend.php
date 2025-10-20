<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Safact, saitemfac, DetalleCxC, CierreMensual};

class Savend extends Model
{
    use HasFactory;

    protected $table = 'savend';

    public function safact()
    {
        return $this->hasMany(Safact::class, 'codvend', 'codvend');
    }

    public function saitemfac()
    {
        return $this->hasMany(Saitemfac::class, 'CodVend', 'codvend');
    }


    public function getComisionGerencial(int $mes, int $year = null)
    {
        $year = $year ?? date('Y');

        // Si no tiene comisión configurada, no aplica
        if (!$this->comision_gerencia) {
            return 0;
        }

        // Buscar si el mes está cerrado
        $cierre = CierreMensual::where('mes', $mes)
            ->where('year', $year)
            ->first();

        // Obtener total abonado del departamento (sin cambios)
        $abonosDepartamento = $this->safact()
            ->with(['cxc.detallecxc' => function ($q) use ($mes, $year) {
                $q->whereYear('fechaDePago', $year)
                    ->whereMonth('fechaDePago', $mes);
            }])
            ->get()
            ->sum(function ($factura) {
                return $factura->cxc?->detallecxc->sum('monto') ?? 0;
            });

        // Si existe cierre, usar comisiones guardadas en el JSON
        if ($cierre) {
            $comisiones = json_decode($cierre->comisiones, true);
            $codvend = $this->codvend ?? null;

            if ($codvend && isset($comisiones[$codvend]['gerencia'])) {
                $porcentajeGerencia = $comisiones[$codvend]['gerencia'];
                return ($abonosDepartamento * $porcentajeGerencia) / 100;
            }
        }

        // Si no hay cierre, usar el valor actual del vendedor
        return ($abonosDepartamento * $this->comision_gerencia) / 100;
    }
}
