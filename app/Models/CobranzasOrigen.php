<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Safact};

class CobranzasOrigen extends Model
{
    use HasFactory;

    public function safacts(){
        return $this->hasMany(Safact::class, 'origen_id', 'id');
    }
}
