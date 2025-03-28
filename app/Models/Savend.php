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
}
