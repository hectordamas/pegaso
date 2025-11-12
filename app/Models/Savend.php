<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Safact, saitemfac, DetalleCxC, CierreMensual};
use Carbon\Carbon;

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
        $startOfMonth = Carbon::createFromDate($year, $mes, 1)->startOfDay();
        $endOfMonth   = Carbon::createFromDate($year, $mes, 1)->endOfMonth()->endOfDay();
        // Si no tiene comisión configurada, no aplica
        if (!$this->comision_gerencia) {
            return 0;
        }

        // Buscar si el mes está cerrado
        $cierre = CierreMensual::where('mes', $mes)
            ->where('year', $year)
            ->first();

        // Obtener total abonado del departamento (sin cambios)
        $abonosDepartamento = Safact::with([
            'cxc.detallecxc', // aquí cargas todos los pagos
            'savend'
        ])
            ->whereIn('codestatus', [3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13])
            ->whereHas('cxc.detallecxc', function ($q) use ($startOfMonth, $endOfMonth) {
                $q->whereNotNull('fechaDePago')->whereBetween('fechaDePago', [
                    $startOfMonth->toDateString(),
                    $endOfMonth->toDateString()
                ]);
            })
            ->whereHas('savend', function ($q) {
                $q->where('activo', true);
            })
            ->get()
            ->sum(function ($factura) use ($mes, $year) {
                return $factura->getAbonosMesActual($mes, $year);
            });


        // Si existe cierre, usar comisiones guardadas en el JSON
        if ($cierre) {
            $comisiones = json_decode($cierre->comisiones, true);
            $codvend = $this->codvend ?? null;

            if ($codvend && isset($comisiones[$codvend]['gerencia'])) {
                $porcentajeGerencia = $comisiones[$codvend]['gerencia'];
                return ($abonosDepartamento) * ($porcentajeGerencia / 100);
            }
        }

        // Si no hay cierre, usar el valor actual del vendedor
        return ($abonosDepartamento) * ($this->comision_gerencia / 100);
    }
}
