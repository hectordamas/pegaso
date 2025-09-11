<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Safact, saitemfac};

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

    /* =======================================================
     * 1. Totales abonados en un mes (base para las comisiones)
     * ======================================================= 
     * */

    public function totalAbonadoProductosMes(int $mes, int $year = null)
    {
        $year = $year ?? date('Y');
        return $this->safact->sum(fn($f) => $f->totalAbonadoProductosMes($mes, $year));
    }

    public function totalAbonadoServiciosMes(int $mes, int $year = null)
    {
        $year = $year ?? date('Y');
        return $this->safact->sum(fn($f) => $f->totalAbonadoServiciosMes($mes, $year));
    }

    public function totalAbonadoMes(int $mes, int $year = null)
    {
        return $this->totalAbonadoProductosMes($mes, $year) +
               $this->totalAbonadoServiciosMes($mes, $year);
    }

    /* =======================================================
     * 2. Comisiones (aplican tasas sobre la base)
     * ======================================================= */
    public function comisionProductosMes(int $mes, int $year = null)
    {        
        $year = $year ?? date('Y');
        return $this->totalAbonadoProductosMes($mes, $year) * ($this->comision_producto / 100);
    }

    public function comisionServiciosMes(int $mes, int $year = null)
    {
        $year = $year ?? date('Y');
        return $this->totalAbonadoServiciosMes($mes, $year) * ($this->comision_servicio / 100);
    }

    public function comisionGerencialMes(int $mes, int $year = null)
    {
        $year = $year ?? date('Y');
        //return $this->safact->totalAbonadoMes($mes, $year) * ($this->comision_gerencia / 100);
    }
}
