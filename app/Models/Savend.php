<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Safact, saitemfac, DetalleCxC};

class Savend extends Model
{
    use HasFactory;

    protected $table = 'savend';

    public function safact(){
        return $this->hasMany(Safact::class, 'codvend', 'codvend');
    }

    public function saitemfac(){
        return $this->hasMany(Saitemfac::class, 'CodVend', 'codvend');
    }


    //Comision gerencial 
    public function getComisionGerencial(int $mes, int $year = null)
    {
        $year = $year ?? date('Y');

        if (!$this->comision_gerencia) {
            return 0;
        }

        $abonosDepartamento = $this->safact()
            ->with(['cxc.detallecxc' => function ($q) use ($mes, $year) {
                $q->whereYear('fechaDePago', $year)
                  ->whereMonth('fechaDePago', $mes);
            }])
            ->get()
            ->sum(function ($factura) {
                return $factura->cxc?->detallecxc->sum('monto') ?? 0;
            });

        return ($abonosDepartamento * $this->comision_gerencia) / 100;
    }
}
