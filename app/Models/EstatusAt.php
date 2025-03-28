<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AtencionCliente;

class EstatusAt extends Model
{
    use HasFactory;

    protected $table = "estatusat";
    protected $primaryKey = 'codestatus';

    public function atencionclientes(){
        return $this->hasMany(AtencionCliente::class, 'codestatus', 'codestatus');
    }
}
