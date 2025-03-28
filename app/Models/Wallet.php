<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TipoMoneda;

class Wallet extends Model
{
    use HasFactory;

    protected $table = 'wallet';

    public function scopeByCodwallet($query, $codwallet){
        if($codwallet)
            return $query->where('codwallet', $codwallet);
        
    }

}
