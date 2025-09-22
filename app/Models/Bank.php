<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DetalleCxC;

class Bank extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function detallecxc(){
        return $this->hasMany(DetalleCxC::class, 'bank_id', 'id');
    }
}
